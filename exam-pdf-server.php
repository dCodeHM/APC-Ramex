<?php
session_start();
include("config/db.php");
include("config/functions.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// No cache header
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

$user_data = check_login($conn);

if (!isset($_SESSION['account_id'])) {
    // Redirect to the login page if the user is not logged in
    echo '<script>alert("User is not logged in, directing to login page.")</script>';
    echo "<script> window.location.assign('login.php'); </script>";
    exit();
}

// Assuming $user_data contains information about the user's role
$user_role = $user_data['role'];

// Check the user's role and set the redirection URL accordingly
if ($user_role == 'Executive Director') {
    $redirect_url = 'index.php'; // Redirect admin users to admin homepage
} elseif ($user_role == 'Program Director') {
    $redirect_url = 'index.php'; // Redirect professor users to professor homepage
} elseif ($user_role == 'Professor') {
    $redirect_url = 'professoruser.php'; // Redirect professor users to professor homepage
} else {
    $redirect_url = 'unauthorized.php'; // Redirect other users to a default homepage
}

$account_id = $_SESSION['account_id'];

// Display the user-specific information
$sql = "SELECT * FROM account WHERE account_id = $account_id";
$result = mysqli_query($conn, $sql); // Replace with data from the database
if ($result) {
    $row = mysqli_fetch_array($result);
    $user_email = $row['user_email'];
    $pwd = $row['pwd'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $role = $row['role'];
}

// Retrieve the course_topic_id from the URL
$course_topic_id = isset($_GET['course_topic_id']) ? intval($_GET['course_topic_id']) : 0;

// Fetch the course_subject_id using the course_topic_id
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
$course_topic = $result->fetch_assoc();
$course_subject_id = $course_topic['course_subject_id'];

// Fetch the exam details based on the course_topic_id
$sql = "SELECT * FROM exam WHERE course_topic_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $course_topic_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();
$exam = $result->fetch_assoc();
$exam_id = $exam['exam_id'];


// Fetch the instructions based on the exam_id
$sql = "SELECT * FROM question WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $exam_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$questions_result = $stmt->get_result();


// ------------------- Fetch CLOs -------------------

// Fetch the course_subject_id using the course_code from the URL
$course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';

$sql = "SELECT course_subject_id FROM prof_course_subject WHERE course_code = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $course_code);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$course_subject = $result->fetch_assoc();
$course_subject_id = $course_subject['course_subject_id'];

// Fetch the course_syllabus_id using the course_subject_id
$sql = "SELECT course_syllabus_id FROM course_syllabus WHERE course_code = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $course_code);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$course_syllabus = $result->fetch_assoc();
$course_syllabus_id = $course_syllabus['course_syllabus_id'];

// Fetch the CLOs using the course_syllabus_id
$sql = "SELECT clo_id, clo_number, clo_details FROM course_outcomes WHERE course_syllabus_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $course_syllabus_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$clos = $result->fetch_all(MYSQLI_ASSOC);

$clos_json = json_encode($clos);


// ------------------- Question Library (Fetch Related Questions) -------------------

// Function to fetch related questions based on course_topic_id
function fetchRelatedQuestions($conn, $course_topic_id)
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

    // Fetch questions based on the exam_ids
    $placeholders = implode(',', array_fill(0, count($exam_ids), '?'));
    $sql = "
        SELECT q.*, qc.question_choices_id, qc.answer_text, qc.answer_image, qc.is_correct, qc.letter, co.clo_number
        FROM question q
        LEFT JOIN question_choices qc ON q.answer_id = qc.answer_id
        LEFT JOIN course_outcomes co ON q.clo_id = co.clo_id
        WHERE q.exam_id IN ($placeholders) AND q.in_question_library = 1
        ORDER BY q.question_id
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param(str_repeat('i', count($exam_ids)), ...$exam_ids);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    $result = $stmt->get_result();

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[$row['question_id']]['details'] = $row;
        $questions[$row['question_id']]['choices'][] = $row;
    }

    return $questions;
}

// Get the course_topic_id and exam_id from the URL
$course_topic_id = isset($_GET['course_topic_id']) ? intval($_GET['course_topic_id']) : 0;
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

// Use exam_id to get the easy, normal, and hard int columns in the exam table
$sql = "SELECT * FROM exam WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $exam_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();
$exam = $result->fetch_assoc();

$easy = $exam['easy'];
$normal = $exam['normal'];
$hard = $exam['hard'];

// Fetch the related questions
$related_questions = fetchRelatedQuestions($conn, $course_topic_id, $easy, $normal, $hard);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>