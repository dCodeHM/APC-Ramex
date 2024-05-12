<?php
$course_subject_id = 0;
$course_code = '';
$program_name = '';
$update = false;

require('database/connect.php');

// Create

if (isset($_POST['save'])) {
    $course_subject_id = $_POST['course_subject_id'];
    $program_name = $_POST['program_name'];
    $account_id = $_POST['account_id'];
    $course_code = $_POST['course_code'];

    // Use prepared statements to prevent SQL injection
    $stmt = $mysqli->prepare("INSERT INTO prof_course_subject (course_subject_id, account_id, program_name, course_code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $course_subject_id, $account_id, $program_name, $course_code);

    if ($stmt->execute()) {
        echo '<script>alert("Course Folder Created.");</script>';
        echo '<script>window.location.href = "myexams.php";</script>';
        exit; // terminate script execution after redirect
    } else {
        // Print SQL error if the query fails
        echo '<script>alert("Course Folder Creation Failed. SQL Error: ' . $stmt->error . '");</script>';
    }

    $stmt->close(); // Close the prepared statement
}


// Update

if (isset($_GET['edit'])) {
    $course_subject_id = $_GET['edit'];

    $result = $mysqli->query("SELECT * FROM prof_course_subject WHERE course_subject_id='$course_subject_id'") or die(mysqli_error($mysqli));

    if (mysqli_num_rows($result) === 1) {
        $row = $result->fetch_array();
        $course_subject_id = $row['course_subject_id'];
        $program_name = $row['program_name'];
        $account_id = $row['account_id'];
        $course_subject = $row['course_subject'];
        $course_code = $row['course_code'];
        $course_syllabus_id = $row['course_syllabus_id'];
        $course_topic_id = $row['course_topic_id'];
    }
    
    $update = true;
}

if (isset($_POST['update'])) {
    $course_subject_id = $_POST['course_subject_id'];
    $account_id = $_POST['account_id'];
    $course_subject = $_POST['course_subject'];
    $program_name = $_POST['program_name'];
    $course_code = $_POST['course_code'];
    $course_syllabus_id = $_POST['course_syllabus_id'];
    $course_topic_id = $_POST['course_topic_id'];

    $mysqli->query("UPDATE prof_course_subject SET course_subject_id='$course_subject_id', account_id='$account_id', 
    course_subject='$course_subject', program_name='$program_name',
    course_code='$course_code', 
    course_syllabus_id='course_syllabus_id',
    course_topic_id='course_topic_id'  
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
