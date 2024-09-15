<!-- topicfolder.php -->
<?php
session_start();
include("config/RAMeXSO.php");
include("config/functions.php");

// Make sure you have the Endroid QR Code library installed via Composer
require "vendor/autoload.php";
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['account_id'])) {
    echo '<script>alert("User is not logged in, directing to login page.")</script>';
    echo "<script> window.location.assign('login.php'); </script>";
    exit();
}

if (isset($_POST['create_exam'])) {
    $course_subject_id = $_POST['course_subject_id'];
    $account_id = $_POST['account_id'];
    $course_topics = $_POST['course_topics'];
    $easy_questions = $_POST['easy_questions'];
    $normal_questions = $_POST['normal_questions'];
    $hard_questions = $_POST['hard_questions'];
    $course_code = $_POST['course_code']; // Retrieve course_code from POST data
    $activity_id = $_POST['course_topics'];  // This is the activity_id
    // $syllabus_course_id = $_GET['syllabus_course_id'];
    // $acy_id = $_GET['acy_id'];
    // $term_id = $_GET['term_id'];

    $fetch_activity_name_sql = "SELECT activity_id, activity_name FROM activity WHERE activity_id = ?";
    $fetch_stmt = $conn_soe->prepare($fetch_activity_name_sql);
    if (!$fetch_stmt) {
        die("Error preparing statement to fetch activity name: " . $conn_soe->error);
    }
    $fetch_stmt->bind_param("i", $_POST['course_topics']);
    if (!$fetch_stmt->execute()) {
        die("Error executing statement to fetch activity name: " . $fetch_stmt->error);
    }
    $fetch_result = $fetch_stmt->get_result();
    $activity_row = $fetch_result->fetch_assoc();
    $activity_id = $activity_row['activity_id'];
    $activity_name = $activity_row['activity_name'];
    $fetch_stmt->close();

// Check if course_topics or activity_id already exists
$sql = "SELECT * FROM prof_course_topic WHERE course_topics = ? OR activity_id = ?";
$stmt = $conn_ramex->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn_ramex->error);
}

$stmt->bind_param("si", $activity_name, $activity_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['course_topics'] == $activity_name) {
        echo '<script>alert("Course topic already exists.")</script>';
    } elseif ($row['activity_id'] == $activity_id) {
        echo '<script>alert("Activity ID already exists for this course.")</script>';
    } else {
        echo '<script>alert("Duplicate entry found.")</script>';
    }
    echo "<script> window.location.assign('topic.php?course_subject_id=$course_subject_id&course_code=$course_code'); </script>";
    exit();
}

// Now use $activity_name instead of $course_topics in the INSERT query
$sql = "INSERT INTO prof_course_topic (course_subject_id, account_id, course_topics, activity_id, date_created) 
        VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn_ramex->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn_ramex->error);
}
$stmt->bind_param("iisi", $course_subject_id, $account_id, $activity_name, $activity_id);
if (!$stmt->execute()) {
    die("Error inserting course topic: " . $stmt->error);
}
$course_topic_id = $stmt->insert_id;

    // Create the exam with the specified number of easy, normal, and hard questions
    $exam_name = "Exam for " . $activity_name;
    $sql = "INSERT INTO exam (exam_name, course_topic_id, date_created, easy, normal, hard) 
        VALUES (?, ?, NOW(), ?, ?, ?)";
    $stmt = $conn_ramex->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn_ramex->error);
    }
    $stmt->bind_param("siiii", $exam_name, $course_topic_id, $easy_questions, $normal_questions, $hard_questions);
    if (!$stmt->execute()) {
        die("Error creating exam: " . $stmt->error);
    }
    $exam_id = $stmt->insert_id;

   // Generate QR Code
   $qrCodeData = $exam_id . '-' . urlencode($exam_name);
   $qrCode = QrCode::create($qrCodeData)
       ->setEncoding(new Encoding('UTF-8'))
       ->setSize(300)
       ->setMargin(10);

   $writer = new SvgWriter();
   $result = $writer->write($qrCode);

   // Generate a unique filename
   $qrCodeFilename = 'qrcodes/exam_' . $exam_id . '_' . time() . '.svg';
   
   // Save the QR code image
   $result->saveToFile($qrCodeFilename);

   // Store the QR code filename in the database
   $sql = "UPDATE exam SET qr_code = ? WHERE exam_id = ?";
   $stmt = $conn_ramex->prepare($sql);
   if (!$stmt) {
       die("Error preparing statement: " . $conn_ramex->error);
   }
   $stmt->bind_param("si", $qrCodeFilename, $exam_id);
   if (!$stmt->execute()) {
       die("Error updating exam with QR code filename: " . $stmt->error);
   }

    $order = 1;

    // Function to get related questions based on course_topic_id
    function getRelatedQuestions($conn_ramex, $course_topic_id)
    {
        // Get the course_subject_id based on the course_topic_id
        $sql = "SELECT course_subject_id FROM prof_course_topic WHERE course_topic_id = ?";
        $stmt = $conn_ramex->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn_ramex->error);
        }
        $stmt->bind_param("i", $course_topic_id);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $course_subject_id = $row['course_subject_id'];

        // Get all the course_topic_ids with the same course_subject_id
        $sql = "SELECT course_topic_id FROM prof_course_topic WHERE course_subject_id = ?";
        $stmt = $conn_ramex->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn_ramex->error);
        }
        $stmt->bind_param("i", $course_subject_id);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $course_topic_ids = [];
        while ($row = $result->fetch_assoc()) {
            $course_topic_ids[] = $row['course_topic_id'];
        }

        // Get all the exam_ids based on the course_topic_ids
        $placeholders = implode(',', array_fill(0, count($course_topic_ids), '?'));
        $sql = "SELECT exam_id FROM exam WHERE course_topic_id IN ($placeholders)";
        $stmt = $conn_ramex->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn_ramex->error);
        }
        $stmt->bind_param(str_repeat('i', count($course_topic_ids)), ...$course_topic_ids);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $exam_ids = [];
        while ($row = $result->fetch_assoc()) {
            $exam_ids[] = $row['exam_id'];
        }

        // Get related questions based on exam_ids
        $placeholders = implode(',', array_fill(0, count($exam_ids), '?'));
        $sql = "SELECT * FROM question WHERE exam_id IN ($placeholders) AND in_question_library = 1";
        $stmt = $conn_ramex->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn_ramex->error);
        }
        $stmt->bind_param(str_repeat('i', count($exam_ids)), ...$exam_ids);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get all related questions
    $relatedQuestions = getRelatedQuestions($conn_ramex, $course_topic_id);

    // Separate related questions by difficulty
    $easyQuestions = array_filter($relatedQuestions, function ($question) {
        return $question['difficulty'] === 'E';
    });
    $normalQuestions = array_filter($relatedQuestions, function ($question) {
        return $question['difficulty'] === 'N';
    });
    $hardQuestions = array_filter($relatedQuestions, function ($question) {
        return $question['difficulty'] === 'H';
    });

    // Function to select and insert questions based on difficulty
    function selectAndInsertQuestions($conn_ramex, $exam_id, $questions, $desiredCount, $difficulty, &$order)
    {
        if ($desiredCount === 0) {
            return;
        }

        $selectedQuestions = array_rand($questions, min($desiredCount, count($questions)));
        if (!is_array($selectedQuestions)) {
            $selectedQuestions = [$selectedQuestions];
        }

        foreach ($selectedQuestions as $questionIndex) {
            $question = $questions[$questionIndex];
            $sql = "INSERT INTO question (exam_id, question_text, question_image, clo_id, difficulty, question_points, date_created, answer_id, in_question_library) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, 0)";
            $stmt = $conn_ramex->prepare($sql);
            if (!$stmt) {
                die("Error preparing statement: " . $conn_ramex->error);
            }
            $stmt->bind_param("issssii", $exam_id, $question['question_text'], $question['question_image'], $question['clo_id'], $question['difficulty'], $question['question_points'], $question['answer_id']);
            if (!$stmt->execute()) {
                die("Error inserting question: " . $stmt->error);
            }
            $order++;
        }
    }

    // Prevent inserting if there's no questions found.
    if (count($easyQuestions) > 0 && $easy_questions > 0) {
        selectAndInsertQuestions($conn_ramex, $exam_id, $easyQuestions, $easy_questions, 'E', $order);
    }

    if (count($normalQuestions) > 0 && $normal_questions > 0) {
        selectAndInsertQuestions($conn_ramex, $exam_id, $normalQuestions, $normal_questions, 'N', $order);
    }

    if (count($hardQuestions) > 0 && $hard_questions > 0) {
        selectAndInsertQuestions($conn_ramex, $exam_id, $hardQuestions, $hard_questions, 'H', $order);
    }

    // Redirect to the topic page with course_subject_id and course_code
    // Redirect to the topic page with course_subject_id and course_code
    header("Location: topic.php?course_subject_id=$course_subject_id&course_code=$course_code&syllabus_course_id=$syllabus_course_id&account_id=$account_id&acy_id=$acy_id&term_id=$term_id");
    exit();
}


if (isset($_GET['delete'])) {
    $course_topic_id = $_GET['delete'];
    $course_subject_id = $_GET['course_subject_id'];
    $course_code = $_GET['course_code']; // Retrieve course_code from GET data
    $syllabus_course_id = $_GET['syllabus_course_id'];
    $account_id = $_GET['account_id'];
    $acy_id = $_GET['acy_id'];
    $term_id = $_GET['term_id'];

    $sql = "DELETE FROM prof_course_topic WHERE course_topic_id = ?";
    $stmt = $conn_ramex->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn_ramex->error);
    }
    $stmt->bind_param("i", $course_topic_id);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // Redirect to the topic page with course_subject_id and course_code
    header("Location: topic.php?course_subject_id=$course_subject_id&course_code=$course_code&syllabus_course_id=$syllabus_course_id&account_id=$account_id&acy_id=$acy_id&term_id=$term_id");
    exit();
}
