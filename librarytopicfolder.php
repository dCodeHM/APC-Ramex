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
