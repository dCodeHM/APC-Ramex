<?php
include("../../config/db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get params exam_id from URL
$exam_id = $_GET['exam_id'];

// Get the total questions by exam_id
$sql = "SELECT COUNT(question_id) as total_questions FROM question WHERE exam_id = $exam_id";

// Execute SQL Query
$result = $conn->query($sql);

// Check if the result is not empty
if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();

    // Return the total_questions
    echo $row['total_questions'];
} else {
    // Return 0 if no total_questions found
    echo 0;
}
