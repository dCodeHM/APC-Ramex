<?php
include("../../config/db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get question_id from URL
$question_id = $_GET['question_id'];

// Get the answer_id based on the question_id from the question table
$sql = "SELECT answer_id FROM question WHERE question_id = $question_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $answer_id = $row['answer_id'];

    // Delete question choices based on answer_id in the question_choices table
    $sql = "DELETE FROM question_choices WHERE answer_id = $answer_id";

    if ($conn->query($sql) === TRUE) {
        echo "Question choices deleted successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Delete question by question_id
    $sql = "DELETE FROM question WHERE question_id = $question_id";

    // Execute SQL Query
    if ($conn->query($sql) === TRUE) {
        echo "Question deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Question not found";
}

// Close the connection
$conn->close();
