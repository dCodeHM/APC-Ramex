<?php
include("../../config/db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get params course_topic_id from URL
$course_topic_id = $_GET['course_topic_id'];

// Create SQL Query
$sql = "SELECT exam_id FROM exam WHERE course_topic_id = $course_topic_id";

// Execute SQL Query
$result = $conn->query($sql);

// Check if the result is not empty
if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();

    // Return the exam_id
    echo $row['exam_id'];
} else {
    // Return 0 if no exam_id found
    echo 0;
}

// Close the connection
$conn->close();
