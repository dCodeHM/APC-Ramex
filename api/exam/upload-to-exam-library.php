<?php
include("../../config/db.php");
include("../../config/functions.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_id = $_POST["exam_id"];

    // Update the in_exam_library column to 1 for the specific exam
    $sql = "UPDATE exam SET in_exam_library = 1 WHERE exam_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $exam_id);

    if ($stmt->execute()) {
        echo "Exam uploaded to library successfully.";
    } else {
        echo "Error uploading exam to library: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
