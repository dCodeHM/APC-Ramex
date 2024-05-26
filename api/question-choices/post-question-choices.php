<?php
include("../../config/db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the request data
$data = json_decode(file_get_contents("php://input"), true);

$image = $_FILES['answer_image']['tmp_name'];
$image_content = addslashes(file_get_contents($image));

$answer_text = $_POST['answer_text'];
$is_correct = $_POST['is_correct'];
$question_id = $_POST['question_id'];
$letter = $_POST['letter'];

// Prepare the SQL statement
$sql = "INSERT INTO question_choices (answer_text, answer_image, is_correct, question_id, letter) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind the parameters to the prepared statement
$stmt->bind_param("ssiis", $answer_text, $image_content, $is_correct, $question_id, $letter);

// Execute the prepared statement
if ($stmt->execute()) {
    // Return a success response
    $response = array('message' => 'Question choice inserted successfully');
    echo json_encode($response);
} else {
    // Return an error response
    $response = array('error' => 'Failed to insert question choice');
    echo json_encode($response);
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
