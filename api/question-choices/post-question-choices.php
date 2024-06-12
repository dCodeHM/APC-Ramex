<?php
include("../../config/db.php");
include("../../config/functions.php");

$log->info('POST request to post-question-choices.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Retrieve the exam ID from the request
        if (!isset($_POST['exam_id'])) {
            throw new Exception("Exam ID not provided");
        }
        $exam_id = $_POST['exam_id'];
        $log->info('Exam ID: ' . $exam_id);

        // Insert new questions
        // if (!isset($_POST['new_question_text']) || !isset($_POST['new_clo_id']) || !isset($_POST['new_difficulty']) || !isset($_POST['new_question_points'])) {
        //     throw new Exception("Missing question data");
        // }
        $new_question_texts = $_POST['new_question_text'];
        $log->info('New question texts: ' . print_r($new_question_texts, true));

        $new_clo_ids = $_POST['new_clo_id'];
        $log->info('New clo ids: ' . print_r($new_clo_ids, true));

        $new_difficulties = $_POST['new_difficulty'];
        $new_question_points = $_POST['new_question_points'];

        $num_questions = count($new_question_texts);
        $log->info('Number of questions: ' . $num_questions);

        for ($question_index = 0; $question_index < $num_questions; $question_index++) {
            // Prepare question data
            $question_text = mysqli_real_escape_string($conn, $new_question_texts[$question_index]);
            $log->info('Question text: ' . $question_text);

            if (!isset($new_clo_ids[$question_index])) {
                throw new Exception("CLO IDs not provided for question index: " . $question_index);
            }
            // $clo_ids_array = $new_clo_ids[$question_index];
            // $clo_ids_string = implode(',', $clo_ids_array);

            $clo_ids_string = implode(',', $new_clo_ids[$question_index]);

            $log->info('Combined clo ids: ' . $clo_ids_string);

            $difficulty = mysqli_real_escape_string($conn, $new_difficulties[$question_index]);
            $log->info('Difficulty: ' . $difficulty);

            $points = intval($new_question_points[$question_index]);
            $log->info('Points: ' . $points);

            // Get the highest answer_id from the question_choices table
            $sql = "SELECT MAX(answer_id) AS max_answer_id FROM question_choices";
            $result = $conn->query($sql);

            if ($result === false) {
                throw new Exception("Error executing query: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $answer_id = $row['max_answer_id'] + 1;
            } else {
                $answer_id = 1;
            }
            $log->info('Answer ID: ' . $answer_id);

            // Check if question image is uploaded
            $question_image = null;
            if (isset($_FILES['new_question_image']['tmp_name'][$question_index]) && !empty($_FILES['new_question_image']['tmp_name'][$question_index])) {
                if ($_FILES['new_question_image']['error'][$question_index] === UPLOAD_ERR_OK) {
                    $question_image = file_get_contents($_FILES['new_question_image']['tmp_name'][$question_index]);
                } else {
                    throw new Exception("Error uploading question image: " . $_FILES['new_question_image']['error'][$question_index]);
                }
            }

            // Prepare SQL statement based on the presence of question image
            if ($question_image !== null) {
                $sql = "INSERT INTO question (exam_id, question_text, question_image, clo_id, difficulty, question_points, answer_id, in_question_library)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error preparing statement: " . $conn->error);
                }
                $stmt->bind_param("issssii", $exam_id, $question_text, $question_image, $clo_ids_string, $difficulty, $points, $answer_id);
            } else {
                $sql = "INSERT INTO question (exam_id, question_text, clo_id, difficulty, question_points, answer_id, in_question_library)
                VALUES (?, ?, ?, ?, ?, ?, 1)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error preparing statement: " . $conn->error);
                }
                $stmt->bind_param("isssii", $exam_id, $question_text, $clo_ids_string, $difficulty, $points, $answer_id);
            }

            // Execute SQL statement
            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
            $log->info('Question inserted successfully: ' . $stmt->insert_id);

            // Parse the new_is_correct and new_answer_text arrays
            if (!isset($_POST['new_is_correct_string']) || !isset($_POST['new_answer_text_string'])) {
                throw new Exception("Missing question choice data");
            }
            $new_is_correct_parsed = json_decode($_POST['new_is_correct_string'], true);
            $new_answer_text_parsed = json_decode($_POST['new_answer_text_string'], true);

            $log->info('New is correct parsed: ' . print_r($new_is_correct_parsed, true));
            $log->info('New answer text parsed: ' . print_r($new_answer_text_parsed, true));

            $num_choices = count($new_is_correct_parsed[$question_index]);

            for ($choice_index = 0; $choice_index < $num_choices; $choice_index++) {
                // Extract each choice data
                $new_image_content = null;
                if (
                    !empty($_FILES['new_answer_image']['tmp_name'][$question_index][$choice_index])
                    && file_exists($_FILES['new_answer_image']['tmp_name'][$question_index][$choice_index])
                ) {
                    $new_image_content = file_get_contents($_FILES['new_answer_image']['tmp_name'][$question_index][$choice_index]);
                }

                $new_letter = chr(65 + $choice_index);
                $is_correct = $new_is_correct_parsed[$question_index][$choice_index];
                $new_answer_text = $new_answer_text_parsed[$question_index][$choice_index];

                // Prepare SQL statement based on the presence of answer image
                if ($new_image_content !== null) {
                    $sql = "INSERT INTO question_choices (answer_text, answer_image, is_correct, letter, answer_id)
                            VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Error preparing statement: " . $conn->error);
                    }
                    $stmt->bind_param("ssisi", $new_answer_text, $new_image_content, $is_correct, $new_letter, $answer_id);
                } else {
                    $sql = "INSERT INTO question_choices (answer_text, is_correct, letter, answer_id)
                            VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Error preparing statement: " . $conn->error);
                    }
                    $stmt->bind_param("sisi", $new_answer_text, $is_correct, $new_letter, $answer_id);
                }

                // Execute SQL statement
                if (!$stmt->execute()) {
                    throw new Exception("Error executing statement: " . $stmt->error);
                }
                $log->info('Question choice inserted successfully: ' . $stmt->insert_id);

                // Close the prepared statement
                $stmt->close();
            }
        }

        // Send a success response
        http_response_code(200);
        echo json_encode(array("message" => "Exam saved successfully"));
    } catch (Exception $e) {
        // Log the error message
        $log->error("Error saving exam: " . $e->getMessage());
        error_log("Error saving exam: " . $e->getMessage());

        // Send an error response with the exception message
        http_response_code(500);
        echo json_encode(array("message" => "Error saving exam: " . $e->getMessage()));
    }
} else {
    // Send an error response for invalid request method
    http_response_code(405);
    echo json_encode(array("message" => "Invalid request method"));
}
