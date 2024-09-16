<?php
include("../../config/db.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_id = $_POST['exam_id'];
    $questions = $_POST['questions'];

    try {
        $conn_ramex->begin_transaction();

        foreach ($questions as $index => $question) {
            $question_text = $question['question_text'];
            $clo_id = $question['clo_id'];
            $question_points = $question['question_points'];

            // Check if the question already exists
            $stmt = $conn_ramex->prepare("SELECT question_id FROM question WHERE exam_id = ? AND question_order = ?");
            $stmt->bind_param("ii", $exam_id, $index);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update existing question
                $row = $result->fetch_assoc();
                $question_id = $row['question_id'];
                $stmt = $conn_ramex->prepare("UPDATE question SET question_text = ?, clo_id = ?, question_points = ? WHERE question_id = ?");
                $stmt->bind_param("ssii", $question_text, $clo_id, $question_points, $question_id);
            } else {
                // Insert new question
                $stmt = $conn_ramex->prepare("INSERT INTO question (exam_id, question_text, clo_id, question_points, question_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issii", $exam_id, $question_text, $clo_id, $question_points, $index);
            }

            $stmt->execute();
        }

        $conn_ramex->commit();
        echo json_encode(['success' => true, 'message' => 'Exam progress saved successfully']);
    } catch (Exception $e) {
        $conn_ramex->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}