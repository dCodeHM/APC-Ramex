<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("config/db.php");
include("config/functions.php");

// Retrieve the course_topic_id from the URL
$course_topic_id = isset($_GET['course_topic_id']) ? intval($_GET['course_topic_id']) : 0;

// Fetch the course_subject_id using the course_topic_id
$sql = "SELECT course_subject_id FROM prof_course_topic WHERE course_topic_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $course_topic_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$course_topic = $result->fetch_assoc();
$course_subject_id = $course_topic['course_subject_id'];

// Fetch the exam details based on the course_topic_id
$sql = "SELECT * FROM exam WHERE course_topic_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $course_topic_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$exam = $result->fetch_assoc();

$exam_id = $exam['exam_id'];

if (empty($exam_id)) {
    die("Exam ID is not set.");
}

// Fetch the questions linked to the exam_id
$sql = "SELECT * FROM question WHERE exam_id = ? ORDER BY `order`";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $exam_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$questions_result = $stmt->get_result();

// Fetch the instructions linked to the exam_id
$sql = "SELECT * FROM instruction WHERE exam_id = ? ORDER BY `order`";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $exam_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$instructions_result = $stmt->get_result();

// Handle form submission
if (isset($_POST['save_exam'])) {
    // Update exam details
    $exam_name = $_POST['exam_name'];
    $sql = "UPDATE exam SET exam_name = ? WHERE exam_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("si", $exam_name, $exam_id);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // Update existing questions
    if (isset($_POST['question_id'])) {
        $question_ids = $_POST['question_id'];
        $question_texts = $_POST['question_text'];
        $clo_ids = $_POST['clo_id'];
        $difficulties = $_POST['difficulty'];
        $question_points = $_POST['question_points'];
        $orders = $_POST['order'];

        foreach ($question_ids as $index => $question_id) {
            $question_text = mysqli_real_escape_string($conn, $question_texts[$index]);
            $clo_id = mysqli_real_escape_string($conn, $clo_ids[$index]);
            $difficulty = mysqli_real_escape_string($conn, $difficulties[$index]);
            $points = intval($question_points[$index]);
            $order = intval($orders[$index]);

            // Handle question image upload
            if (isset($_FILES['question_image']['tmp_name'][$index]) && !empty($_FILES['question_image']['tmp_name'][$index])) {
                if ($_FILES['question_image']['error'][$index] === UPLOAD_ERR_OK) {
                    $question_image = file_get_contents($_FILES['question_image']['tmp_name'][$index]);
                    $sql = "UPDATE question SET question_text = ?, question_image = ?, clo_id = ?, difficulty = ?, question_points = ?, `order` = ? WHERE question_id = ?";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        die("Error preparing statement: " . $conn->error);
                    }
                    $stmt->bind_param("ssssiii", $question_text, $question_image, $clo_id, $difficulty, $points, $order, $question_id);
                } else {
                    echo "Error uploading file: " . $_FILES['question_image']['error'][$index];
                }
            } else {
                $sql = "UPDATE question SET question_text = ?, clo_id = ?, difficulty = ?, question_points = ?, `order` = ? WHERE question_id = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Error preparing statement: " . $conn->error);
                }
                $stmt->bind_param("sssiii", $question_text, $clo_id, $difficulty, $points, $order, $question_id);
            }
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }

            // Save to question library
            $sql = "INSERT INTO question_library (question_text, question_image, clo_id, difficulty, question_points, course_subject_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("ssssii", $question_text, $question_image, $clo_id, $difficulty, $points, $course_subject_id);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }
        }
    }

    // Update existing instructions


    // Insert new questions
    if (isset($_POST['new_question_text'])) {
        $new_question_texts = $_POST['new_question_text'];
        $new_clo_ids = $_POST['new_clo_id'];
        $new_difficulties = $_POST['new_difficulty'];
        $new_question_points = $_POST['new_question_points'];
        $new_orders = $_POST['new_order'];

        foreach ($new_question_texts as $index => $new_question_text) {
            $new_question_text = mysqli_real_escape_string($conn, $new_question_text);
            $new_clo_id = mysqli_real_escape_string($conn, $new_clo_ids[$index]);
            $new_difficulty = mysqli_real_escape_string($conn, $new_difficulties[$index]);
            $new_points = intval($new_question_points[$index]);
            $new_order = intval($new_orders[$index]);

            if (isset($_FILES['new_question_image']['tmp_name'][$index]) && !empty($_FILES['new_question_image']['tmp_name'][$index])) {
                if ($_FILES['new_question_image']['error'][$index] === UPLOAD_ERR_OK) {
                    $new_question_image = file_get_contents($_FILES['new_question_image']['tmp_name'][$index]);
                    $sql = "INSERT INTO question (exam_id, question_text, question_image, clo_id, difficulty, question_points, `order`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        die("Error preparing statement: " . $conn->error);
                    }
                    $stmt->bind_param("issssii", $exam_id, $new_question_text, $new_question_image, $new_clo_id, $new_difficulty, $new_points, $new_order);
                } else {
                    echo "Error uploading file: " . $_FILES['new_question_image']['error'][$index];
                }
            } else {
                $sql = "INSERT INTO question (exam_id, question_text, clo_id, difficulty, question_points, `order`) 
                    VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Error preparing statement: " . $conn->error);
                }
                $stmt->bind_param("isssii", $exam_id, $new_question_text, $new_clo_id, $new_difficulty, $new_points, $new_order);
            }
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }

            // Get the inserted question_id
            $new_question_id = $conn->insert_id;

            // Save to question library
            $sql = "INSERT INTO question_library (question_text, question_image, clo_id, difficulty, question_points, course_subject_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("ssssii", $new_question_text, $new_question_image, $new_clo_id, $new_difficulty, $new_points, $course_subject_id);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }
        }
    }

    if (isset($_POST['new_instruction_text'])) {
        $new_instruction_texts = $_POST['new_instruction_text'];
        $new_instruction_orders = $_POST['new_instruction_order'];

        foreach ($new_instruction_texts as $index => $new_instruction_text) {
            $new_instruction_text = mysqli_real_escape_string($conn, $new_instruction_text);
            $new_instruction_order = intval($new_instruction_orders[$index]);

            $sql = "INSERT INTO `instruction` (`exam_id`, `instruction_text`, `order`) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("isi", $exam_id, $new_instruction_text, $new_instruction_order);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }
        }
    }

    // Redirect back to the exam creator page
    header("Location: examcreator.php?course_topic_id=$course_topic_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="APC AcademX">
    <title>APC AcademX | Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#add_question").click(function() {
                var exam_id = <?php echo $exam_id; ?>;
                $.ajax({
                    url: 'get_highest_order.php',
                    method: 'POST',
                    data: {
                        exam_id: exam_id,
                        type: 'question'
                    },
                    dataType: 'json',
                    success: function(response) {
                        var highestOrder = response.highestOrder;
                        var latestType = response.latestType;
                        var newOrder;

                        if (latestType === 'instruction') {
                            newOrder = 1;
                        } else {
                            newOrder = highestOrder + 1;
                        }

                        var questionHTML = `
                        <div class="bg-zinc-100 mt-6 p-6 gap-4 outline-zinc-300 rounded-md outline outline-1 flex flex-col question">
                            <div class="flex flex-col">
                                <label class="mb-2" for="question_text">Question Text</label>
                                <textarea class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="new_question_text[]"></textarea>
                            </div>
                            <div class="flex flex-col">
                                <label class="mb-2" for="question_image">Question Image</label>
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_question_image[]">
                            </div>
                            <div class="flex flex-col">
                                <label class="mb-2" for="clo_id">CLO ID</label>
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_clo_id[]">
                            </div>
                            <div class="flex flex-col">
                                <label class="mb-2" for="difficulty">Difficulty</label>
                                <select class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="new_difficulty[]">
                                    <option value="E">Easy</option>
                                    <option value="N">Normal</option>
                                    <option value="H">Hard</option>
                                </select>
                            </div>
                            <div class="flex flex-col">
                                <label class="mb-2" for="question_points">Question Points</label>
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="number" name="new_question_points[]">
                            </div>
                            <div class="flex flex-col">
                                <label class="mb-2" for="order">Order</label>
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="number" name="new_order[]" value="${newOrder}" readonly>
                            </div>
                            <button class="remove_question px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="button">Remove Question</button>
                        </div>
                    `;
                        $("#new_questions").append(questionHTML);
                    }
                });
            });

            // Add new instruction dynamically
            $("#add_instruction").click(function() {
                var exam_id = <?php echo $exam_id; ?>;
                $.ajax({
                    url: 'get_highest_order.php',
                    method: 'POST',
                    data: {
                        exam_id: exam_id,
                        type: 'instruction'
                    },
                    dataType: 'json',
                    success: function(response) {
                        var highestOrder = response.highestOrder;
                        var newOrder = highestOrder + 1;

                        var instructionHTML = `
                        <div class="bg-zinc-100 mt-6 p-6 gap-4 outline-zinc-300 rounded-md outline outline-1 flex flex-col instruction">
                            <div class="flex flex-col">
                                <label class="mb-2" for="instruction_text">Instruction Text</label>
                                <textarea class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="new_instruction_text[]"></textarea>
                            </div>
                            <div class="flex flex-col">
                                <label class="mb-2" for="order">Order</label>
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="number" name="new_instruction_order[]" value="${newOrder}" readonly>
                            </div>
                            <button class="remove_instruction px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="button">Remove Instruction</button>
                        </div>
                    `;
                        $("#new_instructions").append(instructionHTML);
                    }
                });
            });

            // Remove question dynamically
            $(document).on("click", ".remove_question", function() {
                $(this).closest(".question").remove();
            });

            // Remove instruction dynamically
            $(document).on("click", ".remove_instruction", function() {
                $(this).closest(".instruction").remove();
            });
        });
    </script>

    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;

        }
    </style>
</head>

<body>
    <nav class="bg-blue-900 text-white fixed top-0 w-full flex items-center justify-between py-2 px-4 z-50">

        <ul class="flex items-center space-x-4">
            <li>
                <a href="index.php">
                    <img id="logo" src="img/logo.png" class="h-12">
                </a>
            </li>
        </ul>

        <ul class="flex items-center space-x-4">
            <?php
            // if (isset($_SESSION['user'])) {
            //     $userData = $_SESSION['user'];
            //     echo "<li class='username'><h3 class='text-xl font-medium'>$userData</h3></li>";
            // } else {
            //     echo "<li class='username'><h3 class='text-xl font-medium'>$row[first_name] $row[last_name]</h3></li>";
            // }
            ?>

            <li class="relative">
                <a href="#" id="toggleNotif">
                    <img id="notification" src="img/notification.png" class="h-8">
                </a>
                <ul class="absolute left-1/2 transform -translate-x-1/2 mt-2 bg-gray-200 rounded-lg shadow-lg w-80 hidden" id="notif-drop">
                    <h3 class="text-gray-900 text-xl font-semibold px-4 py-2">Notifications</h3>
                    <hr class="border-gray-400 mx-4">
                    <div class="p-4">
                        <div class="mb-4">
                            <label class="block">
                                <p class="text-sm font-medium text-gray-900">Sergio Peruda</p>
                                <p class="text-xs text-gray-600">5/22/24</p>
                            </label>
                            <label class="block">
                                <p class="text-sm text-gray-700">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>
                        <!-- Repeat the .mb-4 div for each notification -->
                    </div>
                </ul>
            </li>

            <li class="relative">
                <a href="#" id="toggleUser">
                    <img id="profile" src="img/profile.png" class="h-8">
                </a>
                <ul class="absolute left-1/2 transform -translate-x-1/2 mt-2 bg-gray-200 rounded-lg shadow-lg w-56 hidden" id="user-drop">
                    <h3 class="text-gray-900 text-xl font-semibold px-4 py-2">Admin</h3>
                    <p class="text-gray-700 text-sm px-4">School Role</p>
                    <a href="userprofile.php" class="block text-center text-blue-600 hover:underline py-2">Settings</a>
                    <a href="logout.php" class="block text-center text-red-600 hover:underline py-2">Logout</a>
                </ul>
            </li>
        </ul>
    </nav>

    <div class="fixed top-[0px] pt-[calc(64px+32px)] overflow-y-scroll left-0 flex flex-col space-y-4 bg-gray-700 text-white h-full py-8 px-4 shadow-lg">
        <div class="back_button">
            <a href="index.php">
                <img src="img/back.png" class="h-8">
            </a>
        </div>
        <div class="help_button">
            <img src="img/help.png" class="h-8">
        </div>
    </div>

    <div class="fixed top-[0px] pt-[calc(64px+32px)] left-[64px] bg-gray-500 text-white h-full max-w-[340px] py-8 px-8 overflow-y-scroll">
        <div class="space-y-4">
            <div class="question_library text-center font-semibold text-lg">Question Library</div>
            <div class="exam_settings text-center font-semibold text-lg">Exam Settings</div>
            <div class="topic_questions">
                <p class="topic_title text-yellow-400 font-semibold">
                    <!-- Get topic `course_topic_id` in the URL -->
                    <?php
                    $sql = "SELECT course_topics FROM prof_course_topic WHERE course_topic_id = ?";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        die("Error preparing statement: " . $conn->error);
                    }
                    $stmt->bind_param("i", $course_topic_id);
                    if (!$stmt->execute()) {
                        die("Error executing statement: " . $stmt->error);
                    }
                    $result = $stmt->get_result();

                    $topic = $result->fetch_assoc();
                    echo "> " . $topic['course_topics'];
                    ?>
                </p>

                <?php
                $sql = "SELECT * FROM question_library";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='questions cursor-pointer bg-white text-black p-4 rounded-lg shadow-lg mt-4' onclick='insertQuestion(event)'>{$row['question_text']} ({$row['difficulty']})</div>";
                }
                ?>
            </div>
        </div>
    </div>
    </div>

    <section class="ml-[400px] mt-[70px] px-20 py-10">
        <form class="w-full" method="POST" action="" enctype="multipart/form-data">
            <h2 class="font-semibold mb-2">Exam Details</h2>
            <input class="mb-4 outline outline-zinc-600 outline-1 py-2 px-4 rounded-lg w-full" type="text" name="exam_name" value="<?php echo htmlspecialchars($exam['exam_name']); ?>">
            <h3 class="w-full font-semibold mb-2">Questions
                <span class="text-base font-normal text-gray-400 ml-1">Total Questions: <?php echo $questions_result->num_rows; ?></span>
            </h3>

            <div class="flex flex-col gap-6">
                <?php
                $combined_result = array();

                while ($question = $questions_result->fetch_assoc()) {
                    $combined_result[] = array(
                        'type' => 'question',
                        'data' => $question
                    );
                }

                while ($instruction = $instructions_result->fetch_assoc()) {
                    $combined_result[] = array(
                        'type' => 'instruction',
                        'data' => $instruction
                    );
                }

                usort($combined_result, function ($a, $b) {
                    return strtotime($a['data']['date_created']) - strtotime($b['data']['date_created']);
                });

                $questionOrder = 1;
                $instructionOrder = 1;

                foreach ($combined_result as $item) {
                    if ($item['type'] === 'question') {
                        $question = $item['data'];
                ?>
                        <div class="bg-white shadow-xl p-6 gap-4 outline-zinc-600 rounded-md outline outline-1 flex flex-col relative">
                            <div class="flex flex-col">
                                <label class="mb-2" for="question_id">Question ID</label>
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="question_id[]" value="<?php echo htmlspecialchars($question['question_id']); ?>" readonly>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2" for="question_text">Question Text
                                    <?php if (empty($question['question_text'])) : ?>
                                        <span class="text-red-400">No Question Text*</span>
                                    <?php endif; ?>
                                </label>
                                <textarea class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="question_text[]"><?php echo htmlspecialchars($question['question_text']); ?></textarea>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2" for="question_image">Question Image
                                    <?php if (empty($question['question_image'])) : ?>
                                        <span class="text-red-400">No Question Image*</span>
                                    <?php endif; ?>
                                </label>
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="question_image[]">

                                <?php if (!empty($question['question_image'])) : ?>
                                    <?php
                                    $imgData = base64_encode($question['question_image']);
                                    $src = 'data:image/jpeg;base64,' . $imgData;
                                    ?>
                                    <img src="<?php echo $src; ?>" alt="Question Image" style="max-width: 200px; max-height: 200px;">
                                <?php endif; ?>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2" for="clo_id">CLO ID
                                    <?php if (empty($question['clo_id'])) : ?>
                                        <span class="text-red-400">No CLO ID*</span>
                                    <?php endif; ?>
                                </label>
                                <select class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="clo_id[]">
                                    <option value="1" <?php if ($question['clo_id'] == 1) echo 'selected'; ?>>1</option>
                                    <option value="2" <?php if ($question['clo_id'] == 2) echo 'selected'; ?>>2</option>
                                    <option value="3" <?php if ($question['clo_id'] == 3) echo 'selected'; ?>>3</option>
                                    <option value="4" <?php if ($question['clo_id'] == 4) echo 'selected'; ?>>4</option>
                                    <option value="5" <?php if ($question['clo_id'] == 5) echo 'selected'; ?>>5</option>
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2" for="difficulty">Difficulty
                                    <?php if (empty($question['difficulty'])) : ?>
                                        <span class="text-red-400">No Difficulty*</span>
                                    <?php endif; ?>
                                </label>
                                <select class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="difficulty[]">
                                    <option value="E" <?php if ($question['difficulty'] == 'E') echo 'selected'; ?>>Easy</option>
                                    <option value="N" <?php if ($question['difficulty'] == 'N') echo 'selected'; ?>>Normal</option>
                                    <option value="H" <?php if ($question['difficulty'] == 'H') echo 'selected'; ?>>Hard</option>
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2" for="question_points">Question Points
                                    <?php if (empty($question['question_points'])) : ?>
                                        <span class="text-red-400">No Question Points*</span>
                                    <?php endif; ?>
                                </label>
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="number" name="question_points[]" value="<?php echo htmlspecialchars($question['question_points']); ?>">
                            </div>

                            <input type="hidden" name="order[]" value="<?php echo htmlspecialchars($question['order']); ?>">
                            <p class="absolute right-[100%] py-2 px-4 rounded-l-lg -z-10 outline outline-1 outline-zinc-200 bg-yellow-400 text-white"><?php echo $questionOrder; ?></p>
                        </div>
                    <?php
                        $questionOrder++;
                    } elseif ($item['type'] === 'instruction') {
                        $instruction = $item['data'];
                    ?>
                        <div class="bg-yellow-100/40 shadow-xl p-6 gap-4 outline-zinc-300 rounded-md outline outline-1 flex flex-col relative">
                            <div class="flex flex-col">
                                <label class="mb-2" for="instruction_text">Instruction Text</label>
                                <textarea class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="instruction_text[]"><?php echo htmlspecialchars($instruction['instruction_text']); ?></textarea>
                            </div>
                            <!-- <input type="hidden" name="instruction_id[]" value="<?php echo htmlspecialchars($instruction['instruction_id']); ?>"> -->
                            <input type="hidden" name="instruction_order[]" value="<?php echo htmlspecialchars($instruction['order']); ?>">
                            <p class="absolute right-[100%] py-2 px-4 rounded-l-lg -z-10 outline outline-1 outline-zinc-200 bg-yellow-400 text-white"><?php echo $instructionOrder; ?></p>
                        </div>
                <?php
                        $instructionOrder++;
                    }
                }
                ?>
            </div>

            <div id="new_questions"></div>
            <div id="new_instructions"></div>

            <div class="mt-4">
                <button class="px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="button" id="add_question">Add Question</button>
                <button class="px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="button" id="add_instruction">Add Instruction</button>
                <button class="px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="submit" name="save_exam">Save Exam</button>
            </div>

            <?php while ($instruction = $instructions_result->fetch_assoc()) : ?>
                <div>
                    <label for="instruction_text">Instruction Text</label>
                    <input type="hidden" name="instruction_id[]" value="<?php echo htmlspecialchars($instruction['instruction_id']); ?>">
                    <textarea name="instruction_text[]"><?php echo htmlspecialchars($instruction['instruction_text']); ?></textarea>
                </div>
            <?php endwhile; ?>

        </form>
    </section>
</body>

</html>