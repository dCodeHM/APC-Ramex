<?php
include("config/RAMeXSO.php");
include("../../config/functions.php");

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

header('Content-Type: application/json');

try {
    foreach ($_POST['question_data'] as $questionIndex => $questionData) {
        $questionId = intval($questionData['question_id']);

        foreach ($questionData['choices'] as $choiceIndex => $choice) {
            $isCorrectValue = intval($choice['is_correct']);
            $answerTextValue = mysqli_real_escape_string($conn_ramex, $choice['answer_text']);
            $questionChoicesId = intval($choice['question_choices_id']);

            if (isset($_FILES['question_data']['tmp_name'][$questionIndex]['choices'][$choiceIndex]['answer_image'])) {
                $answerImageValue = file_get_contents($_FILES['question_data']['tmp_name'][$questionIndex]['choices'][$choiceIndex]['answer_image']);
                $sql = "UPDATE question_choices SET answer_text = ?, answer_image = ?, is_correct = ? WHERE question_choices_id = ?";
                $stmt = $conn_ramex->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error preparing statement: " . $conn_ramex->error);
                }
                $stmt->bind_param("ssii", $answerTextValue, $answerImageValue, $isCorrectValue, $questionChoicesId);
            } else {
                $sql = "UPDATE question_choices SET answer_text = ?, is_correct = ? WHERE question_choices_id = ?";
                $stmt = $conn_ramex->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error preparing statement: " . $conn_ramex->error);
                }
                $stmt->bind_param("sii", $answerTextValue, $isCorrectValue, $questionChoicesId);
            }

            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
        }
    }

    echo json_encode(array('success' => true, 'message' => 'Question choices updated successfully'));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => $e->getMessage()));
}
exit();
