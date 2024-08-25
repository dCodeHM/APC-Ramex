<?php
include("../../config/db.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_data = $_POST['question_data'];

    foreach ($question_data as $question) {
        $question_id = $question['question_id'];

        // Update existing choices
        if (isset($question['choices'])) {
            foreach ($question['choices'] as $choice) {
                $stmt = $conn->prepare("UPDATE question_choices SET is_correct = ?, answer_text = ? WHERE question_choices_id = ?");
                $stmt->bind_param("isi", $choice['is_correct'], $choice['answer_text'], $choice['question_choices_id']);
                $stmt->execute();

                // Handle answer image update if provided
                if (isset($_FILES["question_data"]["name"][$key]["choices"][$choiceKey]["answer_image"])) {
                    $imageFile = $_FILES["question_data"]["tmp_name"][$key]["choices"][$choiceKey]["answer_image"];
                    $imageData = file_get_contents($imageFile);
                    $stmt = $conn->prepare("UPDATE question_choices SET answer_image = ? WHERE question_choices_id = ?");
                    $stmt->bind_param("bi", $imageData, $choice['question_choices_id']);
                    $stmt->execute();
                }
            }
        }

        // Insert new choices
        if (isset($question['new_choices'])) {
            foreach ($question['new_choices'] as $newChoice) {
                $stmt = $conn->prepare("INSERT INTO question_choices (answer_id, is_correct, answer_text, letter) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiss", $question_id, $newChoice['is_correct'], $newChoice['answer_text'], $newChoice['letter']);
                $stmt->execute();

                $newChoiceId = $conn->insert_id;

                // Handle answer image for new choice if provided
                if (isset($_FILES["question_data"]["name"][$key]["new_choices"][$choiceKey]["answer_image"])) {
                    $imageFile = $_FILES["question_data"]["tmp_name"][$key]["new_choices"][$choiceKey]["answer_image"];
                    $imageData = file_get_contents($imageFile);
                    $stmt = $conn->prepare("UPDATE question_choices SET answer_image = ? WHERE question_choices_id = ?");
                    $stmt->bind_param("bi", $imageData, $newChoiceId);
                    $stmt->execute();
                }
            }
        }
    }

    echo json_encode(["message" => "Question choices updated successfully"]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
