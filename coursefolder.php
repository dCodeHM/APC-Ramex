<?php
$course_subject_id = 0;
$syllabus_course_id = '';
$update = false;

require('config/RAMeXSO.php');
global $mysqli_soe, $mysqli_ramex, $conn_ramex;

function redirectWithMessage($message, $acy_id, $term, $submitted) {
    echo "<script>
        alert('$message');
        window.location.href = 'myexams.php?acy_id=" . urlencode($acy_id) . "&term=" . urlencode($term) . "&submitted=" . urlencode($submitted) . "';
    </script>";
    exit;
}

// Create
if (isset($_POST['save'])) {
    $account_id = $_POST['account_id'];
    $syllabus_course_id = $_POST['syllabus_course_id'];
    $course_code = $_POST['selected_course_code'];
    $acy_id = isset($_POST['acy_id']) ? intval($_POST['acy_id']) : 0;
    $term = isset($_POST['term']) ? intval($_POST['term']) : 0;
    $submitted = isset($_POST['submitted']) ? intval($_POST['submitted']) : 0;

    // Check if the user has already created a course folder with the same syllabus_course_id for this acy_id and term
    $checkQuery = "SELECT * FROM prof_course_subject WHERE account_id = ? AND syllabus_course_id = ? AND acy_id = ? AND term = ?";
    $stmt = $mysqli_ramex->prepare($checkQuery);
    $stmt->bind_param("iiii", $account_id, $syllabus_course_id, $acy_id, $term);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult->num_rows > 0) {
        redirectWithMessage('You have already created a course folder for this course in the selected academic year and term.', $acy_id, $term, $submitted);
    } else {
        // Insert the new course folder
        $insertQuery = "INSERT INTO prof_course_subject (account_id, syllabus_course_id, course_code, acy_id, term) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli_ramex->prepare($insertQuery);
        $stmt->bind_param("iisii", $account_id, $syllabus_course_id, $course_code, $acy_id, $term);
        $insertResult = $stmt->execute();

        if ($insertResult) {
            redirectWithMessage('Course Folder Created Successfully.', $acy_id, $term, $submitted);
        } else {
            redirectWithMessage('Failed to create Course Folder. Error: ' . $mysqli_ramex->error, $acy_id, $term, $submitted);
        }
    }
}

// Delete
if (isset($_GET['delete'])) {
    $course_subject_id = $_GET['delete'];
    $acy_id = isset($_GET['acy_id']) ? intval($_GET['acy_id']) : 0;
    $term = isset($_GET['term']) ? intval($_GET['term']) : 0;
    $submitted = isset($_GET['submitted']) ? intval($_GET['submitted']) : 0;

    $deleteQuery = "DELETE FROM prof_course_subject WHERE course_subject_id = ?";
    $stmt = $mysqli_ramex->prepare($deleteQuery);
    $stmt->bind_param("i", $course_subject_id);
    $deleteResult = $stmt->execute();

    if ($deleteResult) {
        redirectWithMessage('Course Folder Deleted Successfully.', $acy_id, $term, $submitted);
    } else {
        redirectWithMessage('Failed to delete Course Folder. Error: ' . $mysqli_ramex->error, $acy_id, $term, $submitted);
    }
}
?>