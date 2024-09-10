<?php
include("config/RAMeXSO.php");  // This should include connections to both databases

// Step 1: Sync acy_id from soe_assessment_db.academic_year to ramexdb.prof_course_subject
$query_acy = "SELECT acy_id, academic_year FROM academic_year ORDER BY academic_year DESC";
$result_acy = mysqli_query($conn_soe, $query_acy);

while ($row_acy = mysqli_fetch_assoc($result_acy)) {
    $acy_id = $row_acy['acy_id'];
    $academic_year = $row_acy['academic_year'];
    
    // Check if acy_id exists in ramexdb.prof_course_subject
    $check_acy_query = "SELECT acy_id FROM prof_course_subject WHERE acy_id = ? LIMIT 1";
    $check_acy_stmt = mysqli_prepare($conn_ramex, $check_acy_query);
    mysqli_stmt_bind_param($check_acy_stmt, "i", $acy_id);
    mysqli_stmt_execute($check_acy_stmt);
    $check_acy_result = mysqli_stmt_get_result($check_acy_stmt);

    if (mysqli_num_rows($check_acy_result) == 0) {
        // Insert a placeholder record for the new academic year
        $insert_acy_query = "INSERT INTO prof_course_subject (acy_id, course_code, term, submitted) VALUES (?, 'PLACEHOLDER', NULL, 0)";
        $insert_acy_stmt = mysqli_prepare($conn_ramex, $insert_acy_query);
        mysqli_stmt_bind_param($insert_acy_stmt, "i", $acy_id);
        mysqli_stmt_execute($insert_acy_stmt);
        mysqli_stmt_close($insert_acy_stmt);
    }

    mysqli_stmt_close($check_acy_stmt);
}

// Step 2: Sync course data from soe_assessment_db.course to ramexdb.prof_course_subject
$query_soe = "SELECT course_id, course_code, acy_id, term, submitted, user_id FROM course";
$result_soe = mysqli_query($conn_soe, $query_soe);

while ($row_soe = mysqli_fetch_assoc($result_soe)) {
    $course_id = $row_soe['course_id'];
    $course_code = $row_soe['course_code'];
    $acy_id = $row_soe['acy_id'];
    $term = $row_soe['term'];
    $submitted = $row_soe['submitted'];
    $user_id = $row_soe['user_id'];

    // Check if the course already exists in prof_course_subject
    $check_query = "SELECT course_subject_id FROM prof_course_subject WHERE course_code = ? AND acy_id = ? AND term = ?";
    $check_stmt = mysqli_prepare($conn_ramex, $check_query);
    mysqli_stmt_bind_param($check_stmt, "sis", $course_code, $acy_id, $term);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        // Update existing record
        $update_query = "UPDATE prof_course_subject 
                         SET submitted = ?, account_id = (SELECT account_id FROM account WHERE user_id = ?)
                         WHERE course_code = ? AND acy_id = ? AND term = ?";
        $update_stmt = mysqli_prepare($conn_ramex, $update_query);
        mysqli_stmt_bind_param($update_stmt, "iisis", $submitted, $user_id, $course_code, $acy_id, $term);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
    } else {
        // Insert new record
        $insert_query = "INSERT INTO prof_course_subject (course_code, acy_id, term, submitted, account_id) 
                         VALUES (?, ?, ?, ?, (SELECT account_id FROM account WHERE user_id = ?))";
        $insert_stmt = mysqli_prepare($conn_ramex, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "sisis", $course_code, $acy_id, $term, $submitted, $user_id);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);
    }

    mysqli_stmt_close($check_stmt);
}

// Remove the placeholder records
$remove_placeholder_query = "DELETE FROM prof_course_subject WHERE course_code = 'PLACEHOLDER'";
mysqli_query($conn_ramex, $remove_placeholder_query);

echo "Synchronization completed.";

mysqli_close($conn_ramex);
mysqli_close($conn_soe);
?>