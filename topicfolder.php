<?php
session_start();
include("config/db.php");
include("config/functions.php");

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

    // Insert the course topic into the prof_course_topic table
    $sql = "INSERT INTO prof_course_topic (course_subject_id, account_id, course_topics, date_created) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("iis", $course_subject_id, $account_id, $course_topics);
    if (!$stmt->execute()) {
        die("Error inserting course topic: " . $stmt->error);
    }
    $course_topic_id = $stmt->insert_id;

    // Create the exam with the specified number of easy, normal, and hard questions
    $exam_name = "Exam for " . $course_topics;
    $sql = "INSERT INTO exam (exam_name, course_topic_id, date_created, easy, normal, hard) 
            VALUES (?, ?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("siiii", $exam_name, $course_topic_id, $easy_questions, $normal_questions, $hard_questions);
    if (!$stmt->execute()) {
        die("Error creating exam: " . $stmt->error);
    }
    $exam_id = $stmt->insert_id;

    $order = 1;

    // Function to get related questions based on course_topic_id
    function getRelatedQuestions($conn, $course_topic_id)
    {
        // Get the course_subject_id based on the course_topic_id
        $sql = "SELECT course_subject_id FROM prof_course_topic WHERE course_topic_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
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
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
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
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
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
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param(str_repeat('i', count($exam_ids)), ...$exam_ids);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get all related questions
    $relatedQuestions = getRelatedQuestions($conn, $course_topic_id);

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
    function selectAndInsertQuestions($conn, $exam_id, $questions, $desiredCount, $difficulty, &$order)
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
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
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
        selectAndInsertQuestions($conn, $exam_id, $easyQuestions, $easy_questions, 'E', $order);
    }

    if (count($normalQuestions) > 0 && $normal_questions > 0) {
        selectAndInsertQuestions($conn, $exam_id, $normalQuestions, $normal_questions, 'N', $order);
    }

    if (count($hardQuestions) > 0 && $hard_questions > 0) {
        selectAndInsertQuestions($conn, $exam_id, $hardQuestions, $hard_questions, 'H', $order);
    }

    // Redirect to the topic page with course_subject_id and course_code
    header("Location: topic.php?course_subject_id=$course_subject_id&course_code=$course_code");
    exit();
}


if (isset($_GET['delete'])) {
    $course_topic_id = $_GET['delete'];
    $course_subject_id = $_GET['course_subject_id'];
    $course_code = $_GET['course_code']; // Retrieve course_code from GET data

    $sql = "DELETE FROM prof_course_topic WHERE course_topic_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $course_topic_id);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // Redirect to the topic page with course_subject_id and course_code
    header("Location: topic.php?course_subject_id=$course_subject_id&course_code=$course_code");
    exit();
}
