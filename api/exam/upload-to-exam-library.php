<?php
// upload-to-exam-library.php

// Include necessary configurations and database connections
include("config/RAMeXSO.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_id = $_POST['exam_id'];

    // Start a transaction
    $conn_ramex->begin_transaction();

    try {
        // Update exam details if necessary

        // Process sections
        foreach ($_POST['sections'] as $section) {
            // Insert or update section data
            // Use $section['item_name'], $section['total_points'], $section['clo_id_range']
        }

        // Process questions
        foreach ($_POST['questions'] as $question) {
            // Insert question
            $stmt = $conn_ramex->prepare("INSERT INTO question (exam_id, question_text, clo_id, difficulty, question_points) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isssi", $exam_id, $question['question_text'], $question['clo_id'], $question['difficulty'], $question['question_points']);
            $stmt->execute();
            $question_id = $stmt->insert_id;

            // Process choices
            foreach ($question['choices'] as $choice) {
                // Insert choice
                $stmt = $conn_ramex->prepare("INSERT INTO question_choices (question_id, is_correct, answer_text) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $question_id, $choice['is_correct'], $choice['answer_text']);
                $stmt->execute();

                // Handle answer image upload if present
                if (isset($choice['answer_image'])) {
                    // Process and save the image file
                }
            }
        }

        // Commit the transaction
        $conn_ramex->commit();
        echo "Exam successfully uploaded to the library.";
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn_ramex->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}