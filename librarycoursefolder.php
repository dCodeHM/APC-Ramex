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

    // Prepare and execute the check query
    $checkQuery = "SELECT * FROM prof_course_subject WHERE course_code = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param("s", $course_code);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Course code already exists
        echo "<script>
            alert('Course code already exists. Please use a different course code.');
            window.location.href = 'myexams.php';
        </script>";
        exit; // terminate script execution after displaying the alert
    } else {
        // Prepare and execute the insert query
        $insertQuery = "INSERT INTO prof_course_subject (course_subject_id, account_id, course_code, program_name) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insertQuery);
        $stmt->bind_param("iiss", $course_subject_id, $account_id, $course_code, $program_name);
        $insertResult = $stmt->execute();

        if ($insertResult) {
            echo "<script>
                alert('Course Folder Created.');
                window.location.href = 'myexams.php';
            </script>";
            exit; // terminate script execution after redirect
        } else {
            echo "<script>
                alert('Course Folder Creation Failed.');
                window.location.href = 'myexams.php';
            </script>";
            exit; // terminate script execution after displaying the alert
        }
    }
}
