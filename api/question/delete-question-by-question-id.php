<?php
include("../../config/db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// If 

// Get question_id from URL
$question_id = $_GET['question_id'];

// Delete question by question_id
$sql = "DELETE FROM question WHERE question_id = $question_id";

// Execute SQL Query
if ($conn->query($sql) === TRUE) {
    echo "Question deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
