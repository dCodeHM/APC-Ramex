<?php
include("../../config/db.php");
include("../../config/functions.php");

$exam_id = $_POST['exam_id'];
$question_ids = $_POST['question_id'];
$question_texts = $_POST['question_text'];
$clo_ids = $_POST['clo_id'];
$difficulties = $_POST['difficulty'];
$question_points = $_POST['question_points'];

foreach ($question_ids as $index => $question_id) {
    $question_text = mysqli_real_escape_string($conn, $question_texts[$index]);
    $clo_id = mysqli_real_escape_string($conn, $clo_ids[$index]);
    $difficulty = mysqli_real_escape_string($conn, $difficulties[$index]);
    $points = intval($question_points[$index]);

    // Handle question image upload
    if (isset($_FILES['question_image']['tmp_name'][$index]) && !empty($_FILES['question_image']['tmp_name'][$index])) {
        $question_image = file_get_contents($_FILES['question_image']['tmp_name'][$index]);
        $sql = "UPDATE question SET question_text = ?, question_image = ?, clo_id = ?, difficulty = ?, question_points = ? WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ssssii", $question_text, $question_image, $clo_id, $difficulty, $points, $question_id);
    } else {
        $sql = "UPDATE question SET question_text = ?, clo_id = ?, difficulty = ?, question_points = ? WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("sssii", $question_text, $clo_id, $difficulty, $points, $question_id);
    }
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
}

echo json_encode(array('success' => true));
exit();
