<?php
include("config/db.php");  // Connection to ramexdb
include("AYpageDB.php");  // Connection to soe_assessment_db

// Fetch data from soe_assessment_db.course
$query_soe = "SELECT course_code, acy_id, term, submitted FROM course";
$result_soe = mysqli_query($conn_soe, $query_soe);

while ($row_soe = mysqli_fetch_assoc($result_soe)) {
    $course_code = $row_soe['course_code'];
    $acy_id = $row_soe['acy_id'];
    $term = $row_soe['term'];
    $submitted = $row_soe['submitted'];

    // Update ramexdb.prof_course_subject
    $update_query = "UPDATE prof_course_subject 
                     SET acy_id = ?, term = ?, submitted = ? 
                     WHERE course_code = ?";
    
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "issi", $acy_id, $term, $submitted, $course_code);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
mysqli_close($conn_soe);

echo "Update completed.";
?>