<?php
session_start();
include("config/db.php");
include("config/functions.php");

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
    $total_questions = $_POST['total_questions'];
    $difficulty = $_POST['difficulty'];

    // Insert the course topic into the prof_course_topic table
    $sql = "INSERT INTO prof_course_topic (course_subject_id, account_id, course_topics, difficulty, date_created) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("iiss", $course_subject_id, $account_id, $course_topics, $difficulty);
    if (!$stmt->execute()) {
        die("Error inserting course topic: " . $stmt->error);
    }
    $course_topic_id = $stmt->insert_id;

    // Create the exam
    $exam_name = "Exam for " . $course_topics;
    $sql = "INSERT INTO exam (exam_name, course_topic_id, date_created) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("si", $exam_name, $course_topic_id);
    if (!$stmt->execute()) {
        die("Error creating exam: " . $stmt->error);
    }
    $exam_id = $stmt->insert_id;

    function getRandomQuestions($conn, $difficulty, $limit)
    {
        $sql = "SELECT * FROM question_library WHERE difficulty = ? ORDER BY RAND() LIMIT ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("si", $difficulty, $limit);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        return $stmt->get_result();
    }

    $order = 1;

    // Fetch and insert random easy questions
    $easy_questions_result = getRandomQuestions($conn, 'E', $easy_questions);
    while ($question = $easy_questions_result->fetch_assoc()) {
        $sql = "INSERT INTO question (exam_id, question_text, question_image, clo_id, difficulty, question_points, `order`, date_created)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("issssii", $exam_id, $question['question_text'], $question['question_image'], $question['clo_id'], $question['difficulty'], $question['question_points'], $order);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $order++;
    }

    // Fetch and insert random normal questions
    $normal_questions_result = getRandomQuestions($conn, 'N', $normal_questions);
    while ($question = $normal_questions_result->fetch_assoc()) {
        $sql = "INSERT INTO question (exam_id, question_text, question_image, clo_id, difficulty, question_points, `order`, date_created)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("issssii", $exam_id, $question['question_text'], $question['question_image'], $question['clo_id'], $question['difficulty'], $question['question_points'], $order);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $order++;
    }

    // Fetch and insert random hard questions
    $hard_questions_result = getRandomQuestions($conn, 'H', $hard_questions);
    while ($question = $hard_questions_result->fetch_assoc()) {
        $sql = "INSERT INTO question (exam_id, question_text, question_image, clo_id, difficulty, question_points, `order`, date_created)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("issssii", $exam_id, $question['question_text'], $question['question_image'], $question['clo_id'], $question['difficulty'], $question['question_points'], $order);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $order++;
    }

    // Redirect to examcreator.php with the course_topic_id
    header("Location: examcreator.php?course_topic_id=$course_topic_id&course_code=$courseCode");
    exit();
}

if (isset($_GET['delete'])) {
    $course_topic_id = $_GET['delete'];
    $sql = "DELETE FROM prof_course_topic WHERE course_topic_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $course_topic_id);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    header("Location: topic.php?course_code=$courseCode&course_subject_id=$course_subject_id");
    exit();
}
