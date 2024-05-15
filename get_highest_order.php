<?php
include("config/db.php");

if (isset($_POST['exam_id']) && isset($_POST['type'])) {
    $exam_id = intval($_POST['exam_id']);

    // Get the latest item (question or instruction) based on date_created
    $sql = "SELECT *
            FROM (
                SELECT 'question' AS item_type, `order`, date_created
                FROM question
                WHERE exam_id = ?
                UNION ALL
                SELECT 'instruction' AS item_type, `order`, date_created
                FROM instruction
                WHERE exam_id = ?
            ) AS combined
            ORDER BY date_created DESC
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die(json_encode(['error' => 'Error preparing statement: ' . $conn->error]));
    }
    $stmt->bind_param("ii", $exam_id, $exam_id);
    if (!$stmt->execute()) {
        die(json_encode(['error' => 'Error executing statement: ' . $stmt->error]));
    }
    $result = $stmt->get_result();
    $latestItem = $result->fetch_assoc();

    if ($latestItem) {
        $highestOrder = $latestItem['order'];
        $latestType = $latestItem['item_type'];
    } else {
        $highestOrder = 0;
        $latestType = '';
    }

    echo json_encode(['highestOrder' => $highestOrder, 'latestType' => $latestType]);
} else {
    echo json_encode(['error' => 'Exam ID or type not provided']);
}
