<?php
include("config/RAMeXSO.php");  // This should include connections to both databases

session_start();
$account_id = $_SESSION['account_id'];

// Step 1: Get the program_name and program_id for the current user
$query_user_program = "SELECT pn.program_id, pn.program_name
                       FROM account a
                       JOIN program_name pn ON a.program_name = pn.program_name
                       WHERE a.account_id = ?";
$stmt_user_program = mysqli_prepare($conn_soe, $query_user_program);
mysqli_stmt_bind_param($stmt_user_program, "i", $account_id);
mysqli_stmt_execute($stmt_user_program);
$result_user_program = mysqli_stmt_get_result($stmt_user_program);
$user_program = mysqli_fetch_assoc($result_user_program);
mysqli_stmt_close($stmt_user_program);

if (!$user_program) {
    die("Error: Unable to fetch user's program information.");
}

$user_program_id = $user_program['program_id'];

// Step 2: Sync course data from soe_assessment_db.course to ramexdb.prof_course_subject
$query_soe = "SELECT DISTINCT c.acy_id, c.term, c.submitted, c.user_id, c.course_code, c.syllabus_course_id
              FROM course c
              WHERE c.program_id = ?
              ORDER BY c.acy_id DESC, c.term ASC";
$stmt_soe = mysqli_prepare($conn_soe, $query_soe);
mysqli_stmt_bind_param($stmt_soe, "i", $user_program_id);
mysqli_stmt_execute($stmt_soe);
$result_soe = mysqli_stmt_get_result($stmt_soe);

while ($row_soe = mysqli_fetch_assoc($result_soe)) {
    $acy_id = $row_soe['acy_id'];
    $term = $row_soe['term'];
    $submitted = $row_soe['submitted'];
    $user_id = $row_soe['user_id'];
    $course_code = $row_soe['course_code'];
    $syllabus_course_id = $row_soe['syllabus_course_id'];

    // Check if the course already exists in prof_course_subject
    $check_query = "SELECT course_subject_id, submitted FROM prof_course_subject 
                    WHERE acy_id = ? AND term = ? AND syllabus_course_id = ? AND account_id = ?";
    $check_stmt = mysqli_prepare($conn_ramex, $check_query);
    mysqli_stmt_bind_param($check_stmt, "iiis", $acy_id, $term, $syllabus_course_id, $account_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        $existing_record = mysqli_fetch_assoc($check_result);
        // Update existing record only if submitted status has changed
        if ($existing_record['submitted'] != $submitted) {
            $update_query = "UPDATE prof_course_subject 
                             SET submitted = ?
                             WHERE course_subject_id = ?";
            $update_stmt = mysqli_prepare($conn_ramex, $update_query);
            mysqli_stmt_bind_param($update_stmt, "ii", $submitted, $existing_record['course_subject_id']);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);
        }
    } else {
        // Insert new record
        $insert_query = "INSERT INTO prof_course_subject (acy_id, term, submitted, account_id, course_code, syllabus_course_id) 
                         VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn_ramex, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "iiissi", $acy_id, $term, $submitted, $account_id, $course_code, $syllabus_course_id);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);
    }

    mysqli_stmt_close($check_stmt);
}

mysqli_stmt_close($stmt_soe);

echo "Synchronization completed.";

mysqli_close($conn_ramex);
mysqli_close($conn_soe);
?>