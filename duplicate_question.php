<?php
include("config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = intval($_POST['question_id']);
    $course_topic_id = intval($_POST['course_topic_id']);

    // Fetch the original question
    $sql = "SELECT * FROM question WHERE question_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $original_question = $result->fetch_assoc();

    if (!$original_question) {
        echo json_encode(['success' => false, 'message' => 'Question not found']);
        exit();
    }

    // Fetch the new exam ID using the course topic ID
    $sql = "SELECT exam_id FROM exam WHERE course_topic_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_topic_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $new_exam = $result->fetch_assoc();
    $new_exam_id = $new_exam['exam_id'];

    if (!$new_exam_id) {
        echo json_encode(['success' => false, 'message' => 'Exam not found for the given course topic ID']);
        exit();
    }

    // Insert the duplicated question with the new exam ID
    $sql = "INSERT INTO question (exam_id, question_text, question_image, clo_id, difficulty, question_points, answer_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssii",
        $new_exam_id,
        $original_question['question_text'],
        $original_question['question_image'],
        $original_question['clo_id'],
        $original_question['difficulty'],
        $original_question['question_points'],
        $original_question['answer_id']
    );
    $stmt->execute();
    $new_question_id = $stmt->insert_id;

    // Fetch the original choices
    $sql = "SELECT * FROM question_choices WHERE answer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $original_question['answer_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $original_choices = $result->fetch_all(MYSQLI_ASSOC);

    // Insert the duplicated choices
    foreach ($original_choices as $choice) {
        $sql = "INSERT INTO question_choices (answer_text, answer_image, is_correct, letter, answer_id)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssisi",
            $choice['answer_text'],
            $choice['answer_image'],
            $choice['is_correct'],
            $choice['letter'],
            $new_question_id
        );
        $stmt->execute();
    }

    echo json_encode(['success' => true]);
}
