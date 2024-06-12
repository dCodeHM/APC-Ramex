<?php
include("../../config/db.php");
include("../../config/functions.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$examId = $_POST["exam_id"];
$examInstruction = $_POST["exam_instruction"];

// Log
$log->info('Updating exam instruction');

// Log
$log->info('Exam ID' . $examId);
$log->info('Exam Instruction' . $examInstruction);

// Update the exam instruction in the database
$sql = "UPDATE exam SET exam_instruction = ? WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $examInstruction, $examId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["message" => "Exam instruction updated successfully"]);
} else {
    echo json_encode(["message" => "No changes made to the exam instruction"]);
}
