<?php
include("config/RAMeXSO.php");
include("../../config/functions.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get params exam_id from URL
$exam_id = $_GET['exam_id'];

// Create a SQL query that goes into question table, finds matching exam_id, and sums up the question_points
$sql = "SELECT SUM(question_points) as total_points FROM question WHERE exam_id = $exam_id";

// Execute SQL Query
$result = $conn_ramex->query($sql);

// Check if the result is not empty
if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();

    // Return the total_points
    echo $row['total_points'];
} else {
    // Return 0 if no total_points found
    echo 0;
}
