<?php
$course_topic_id = 0;
$course_topics = '';
$date_created = '';
$difficulty = '';
$course_subject_id = 0;
$update = false;

require('config/db.php');

// Create
if (isset($_POST['save'])) {
    $account_id = $_POST['account_id'];
    $course_code = $_POST['course_code'];
    $difficulty = $_POST['difficulty'];
    $course_topics = $_POST['course_topics'];
    $course_subject_id = $_POST['course_subject_id'];
    $date_created = date('Y-m-d');

    $result = $mysqli->query("INSERT INTO prof_course_topic (account_id, course_topics, date_created, difficulty, course_subject_id) 
                            VALUES ('$account_id', '$course_topics', '$date_created', '$difficulty', '$course_subject_id')")
        or die(mysqli_error($mysqli));

    if ($result) {
        echo '<script>alert("Course Topic Created.");</script>';
        echo '<script>window.location.href = "topic.php?course_code=' .$course_code. '";</script>';
        exit;
    } else {
        echo '<script>alert("Course Topic Creation Failed.");</script>';
    }
}

// Edit
if (isset($_GET['edit'])) {
    $course_topic_id = $_GET['edit'];
    $update = true;

    $result = $mysqli->query("SELECT * FROM prof_course_topic WHERE course_topic_id='$course_topic_id'")
        or die(mysqli_error($mysqli));

    if (mysqli_num_rows($result) === 1) {
        $row = $result->fetch_array();
        $account_id = $row['account_id'];
        $course_topics = $row['course_topics'];
        $course_subject_id = $row['course_subject_id'];
        $date_created = $row['date_created'];
        $difficulty = $row['difficulty'];
    }
}

// Update
if (isset($_POST['update'])) {
    $course_topic_id = $_POST['course_topic_id'];
    $account_id = $_POST['account_id'];
    $course_topics = $_POST['course_topics'];
    $course_subject_id = $_POST['course_subject_id'];
    $difficulty = $_POST['difficulty'];

    $mysqli->query("UPDATE prof_course_topic SET 
                    account_id='$account_id', 
                    course_topic_id='$course_topic_id',
                    course_topics='$course_topics',
                    difficulty='$difficulty'  
                    WHERE course_topic_id='$course_topic_id'")
        or die(mysqli_error($mysqli));

    header("location: topic.php");
}

// Delete
if (isset($_GET['delete'])) {
    $course_topic_id = $_GET['delete'];
    $mysqli->query("DELETE FROM prof_course_topic WHERE course_topic_id = '$course_topic_id'")
        or die(mysqli_error($mysqli));
    header("location: topic.php?course_code=");
}