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

    // Prepare and execute the check query to determine if the user has already created a course folder with the same course code
    $checkQuery = "SELECT * FROM prof_course_subject WHERE account_id = ? AND course_code = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param("is", $account_id, $course_code);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult->num_rows > 0) {
        // The user has already created a course folder with the same course code
        echo "<script>
            alert('You have already created a course folder with this course code. Please use a different course code.');
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
        } else {
            echo "<script>
                alert('Course Folder Creation Failed.');
                window.location.href = 'myexams.php';
            </script>";
        }
    }
}


// Delete
if (isset($_GET['delete'])) {
    $course_subject_id = $_GET['delete'];
    $mysqli->query("DELETE FROM prof_course_subject WHERE course_subject_id = '$course_subject_id'")
        or die(mysqli_error($mysqli));
    header("location: myexams.php");
}
