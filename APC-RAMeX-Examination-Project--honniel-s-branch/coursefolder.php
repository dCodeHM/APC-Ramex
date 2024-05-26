<?php

$course_subject_id = 0;
$course_subject = '';
$course_code = '';
$course_syllabus_id = 0;
$course_topic_id = 0;
$program_name = '';
$update = false;

require('config/db.php');

// Create

if (isset($_POST['save'])) {
    $course_subject_id = $_POST['course_subject_id'];
    $account_id = $_POST['account_id'];
    $course_code = $_POST['course_code'];
    $program_name = $_POST['program_name'];

    $result = $mysqli->query("INSERT INTO prof_course_subject (course_subject_id, account_id, course_code,program_name) VALUES ('$course_subject_id', '$account_id', '$course_code','$program_name')")
        or die(mysqli_error($mysqli));

    if ($result) {
        echo '<script>alert("Course Folder Created.");</script>';
        echo '<script>window.location.href = "myexams.php";</script>';
        exit; // terminate script execution after redirect
    } else {
        echo '<script>alert("Course Folder Creation Failed.");</script>';
    }
}

// Update

if (isset($_GET['edit'])) {
    $course_subject_id = $_GET['edit'];

    $result = $mysqli->query("SELECT * FROM prof_course_subject WHERE course_subject_id='$course_subject_id'") or die(mysqli_error($mysqli));

    if (mysqli_num_rows($result) === 1) {
        $row = $result->fetch_array();
        $course_subject_id = $row['course_subject_id'];
        $account_id = $row['account_id'];
        $course_code = $row['course_code'];
        $program_name = $row['program_name'];
    }
    $update = true;
}

if (isset($_POST['update'])) {
    $course_subject_id = $_POST['course_subject_id'];
    $account_id = $_POST['account_id'];
    $course_code = $_POST['course_code'];
    $program_name = $_POST['program_name'];

    $mysqli->query("UPDATE prof_course_subject SET course_subject_id='$course_subject_id', account_id='$account_id', 
    program_name='$program_name',
    course_code='$course_code', 
    WHERE course_subject_id='$course_subject_id'")
        or die(mysqli_error($mysqli));

    header("location: myexams.php");
    exit();
}

// Delete
if (isset($_GET['delete'])) {
    $course_subject_id = $_GET['delete'];
    $mysqli->query("DELETE FROM prof_course_subject WHERE course_subject_id = '$course_subject_id'")
        or die(mysqli_error($mysqli));
    header("location: myexams.php");
}
