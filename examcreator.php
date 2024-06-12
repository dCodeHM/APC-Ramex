<?php
session_start();
include("config/db.php");
include("config/functions.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// No cache header
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

$user_data = check_login($conn);

if (!isset($_SESSION['account_id'])) {
    // Redirect to the login page if the user is not logged in
    echo '<script>alert("User is not logged in, directing to login page.")</script>';
    echo "<script> window.location.assign('login.php'); </script>";
    exit();
}

// Assuming $user_data contains information about the user's role
$user_role = $user_data['role'];

// Check the user's role and set the redirection URL accordingly
if ($user_role == 'Executive Director') {
    $redirect_url = 'index.php'; // Redirect admin users to admin homepage
} elseif ($user_role == 'Program Director') {
    $redirect_url = 'index.php'; // Redirect professor users to professor homepage
} elseif ($user_role == 'Professor') {
    $redirect_url = 'professoruser.php'; // Redirect professor users to professor homepage
} else {
    $redirect_url = 'unauthorized.php'; // Redirect other users to a default homepage
}

$account_id = $_SESSION['account_id'];

// Display the user-specific information
$sql = "SELECT * FROM account WHERE account_id = $account_id";
$result = mysqli_query($conn, $sql); // Replace with data from the database
if ($result) {
    $row = mysqli_fetch_array($result);
    $user_email = $row['user_email'];
    $pwd = $row['pwd'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $role = $row['role'];
}

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


// Fetch the instructions based on the exam_id
$sql = "SELECT * FROM question WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $exam_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$questions_result = $stmt->get_result();


// ------------------- Fetch CLOs -------------------

// Fetch the course_subject_id using the course_code from the URL
$course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';

$sql = "SELECT course_subject_id FROM prof_course_subject WHERE course_code = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $course_code);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$course_subject = $result->fetch_assoc();
$course_subject_id = $course_subject['course_subject_id'];

// Fetch the course_syllabus_id using the course_subject_id
$sql = "SELECT course_syllabus_id FROM course_syllabus WHERE course_code = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $course_code);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$course_syllabus = $result->fetch_assoc();
$course_syllabus_id = $course_syllabus['course_syllabus_id'];

// Fetch the CLOs using the course_syllabus_id
$sql = "SELECT clo_id, clo_number, clo_details FROM course_outcomes WHERE course_syllabus_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $course_syllabus_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$clos = $result->fetch_all(MYSQLI_ASSOC);

$clos_json = json_encode($clos);


// ------------------- Question Library (Fetch Related Questions) -------------------

// Function to fetch related questions based on course_topic_id
function fetchRelatedQuestions($conn, $course_topic_id)
{
    // Get the course_subject_id based on the course_topic_id
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
    $row = $result->fetch_assoc();
    $course_subject_id = $row['course_subject_id'];

    // Get all the course_topic_ids with the same course_subject_id
    $sql = "SELECT course_topic_id FROM prof_course_topic WHERE course_subject_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $course_subject_id);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $course_topic_ids = [];
    while ($row = $result->fetch_assoc()) {
        $course_topic_ids[] = $row['course_topic_id'];
    }

    // Get all the exam_ids based on the course_topic_ids
    $placeholders = implode(',', array_fill(0, count($course_topic_ids), '?'));
    $sql = "SELECT exam_id FROM exam WHERE course_topic_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param(str_repeat('i', count($course_topic_ids)), ...$course_topic_ids);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $exam_ids = [];
    while ($row = $result->fetch_assoc()) {
        $exam_ids[] = $row['exam_id'];
    }

    // Fetch questions based on the exam_ids
    $placeholders = implode(',', array_fill(0, count($exam_ids), '?'));
    $sql = "
        SELECT q.*, qc.question_choices_id, qc.answer_text, qc.answer_image, qc.is_correct, qc.letter, co.clo_number
        FROM question q
        LEFT JOIN question_choices qc ON q.answer_id = qc.answer_id
        LEFT JOIN course_outcomes co ON q.clo_id = co.clo_id
        WHERE q.exam_id IN ($placeholders) AND q.in_question_library = 1
        ORDER BY q.question_id
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param(str_repeat('i', count($exam_ids)), ...$exam_ids);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    $result = $stmt->get_result();

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[$row['question_id']]['details'] = $row;
        $questions[$row['question_id']]['choices'][] = $row;
    }

    return $questions;
}

// Get the course_topic_id and exam_id from the URL
$course_topic_id = isset($_GET['course_topic_id']) ? intval($_GET['course_topic_id']) : 0;
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

// Use exam_id to get the easy, normal, and hard int columns in the exam table
$sql = "SELECT * FROM exam WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $exam_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();
$exam = $result->fetch_assoc();

$easy = $exam['easy'];
$normal = $exam['normal'];
$hard = $exam['hard'];

// Fetch the related questions
$related_questions = fetchRelatedQuestions($conn, $course_topic_id, $easy, $normal, $hard);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="APC AcademX">

    <!-- Title -->
    <title>APC AcademX | Welcome</title>
    <link rel="shortcut icon" type="x-icon" href="./img/icon.png">
    <!-- Styles -->
    <link rel="stylesheet" href="./css/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/examsettings.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/helpbutton.css">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js"></script>


</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">

        <ul class="right-header">
            <li class="logo">
                <a href="<?php echo $redirect_url; ?>"><img id="logo" src="img/APC AcademX Logo.png"></a>
            </li>
        </ul>
        <ul class="left-header">
            <?php
            // Check if the session variable exists
            if (isset($_SESSION['user'])) {
                // Retrieve data from the session variable
                $userData = $_SESSION['user'];

                // // Access specific data from the session variable
                // $username = $userData['username'];
                // $email = $userData['email'];

                // Output the retrieved data in HTML text
                echo "<li class='username'><h3>$userData</h3></li>";
            } else {
                // Session variable does not exist or user is not logged in
                echo "<li class='username'><h3>$row[first_name] $row[last_name]</h3></li>";
            }
            ?>


            <li class="notification">
                <a href="#" id="toggleNotif"><img id="notification" src="img/notification.png"></a>
                <ul class="notif-drop dropdown" id="notif-drop" style="display: none;">
                    <h3>Notifications</h3>
                    <hr>
                    <div class="notif-list">
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                        <div class="notif">
                            <label id="notifname">
                                <p class="notifname">Sergio Peruda</p>
                                <p class="notifdate">5/22/24</p>
                            </label>
                            <label id="notifname">
                                <p class="notifdetails">A program director assigned a course<br> [GRAPHYS] to you.</p>
                            </label>
                        </div>;
                    </div>
                </ul>
            </li>

            <li class="user">
                <a href="#" id="toggleUser"><img id="profile" src="img/profile.png"></a>
                <ul class="user-d   rop dropdown" id="user-drop" style="display: none;">
                    <h3>Admin</h3>
                    <p>School Role</p>
                    <a href="adminusersettings.php" class="settings"><span>Settings</span></a>
                    <a href="logout.php" class="logout"><span>Logout</span></a>
                </ul>
            </li>
        </ul>

        <div class="sidebar">
            <div class="back_button">
                <a href="em.php">
                    <img src="img/back.png">
                </a>
            </div>
            <div class="help_buttonexam">
                <img src="img/help.png">
            </div>
        </div>
    </nav>

    <!-- Question Library -->
    <div class="main_container overflow-y-scroll pb-36">
        <div class="buttons">
            <button id="btn_diva" class="button active" type="button">
                <img src="./img/book.png" alt="Icon"> Question Library
            </button>
            <button id="btn_divb" class="button" type="button">
                <img src="./img/examsettings.png" alt="Icon"> Exam Settings
            </button>
        </div>

        <!-- Question Library Section -->
        <div id="question-library" class="p-6 !overflow-y-scroll">
            <!-- Don't display add 5 questions if there are less than 5 questions -->
            <?php if (count($related_questions) >= 5) : ?>
                <button id="add_5_questions" class="px-4 py-2 bg-white text-xl font-medium rounded-md text-black mb-4" type="button">Add 5 Questions</button>
            <?php endif; ?>

            <?php if (empty($related_questions)) : ?>
                <p class="text-white text-2xl">No related questions found.</p>
            <?php else : ?>
                <?php foreach ($related_questions as $question) : ?>
                    <div class="question-item bg-zinc-100 p-6 gap-4 mb-2 outline-zinc-300 rounded-md outline outline-1 flex justify-between items-center cursor-pointer" data-id="<?php echo $question['details']['question_id']; ?>" onclick="duplicateQuestion(<?php echo $question['details']['question_id']; ?>)">

                        <div>
                            <p class="text-2xl font-semibold"><?php echo htmlspecialchars($question['details']['question_text'] ?? ''); ?></p>
                            <?php if ($question['details']['question_image']) : ?>
                                <?php
                                $imgData = base64_encode($question['details']['question_image']);
                                $src = 'data:image/jpeg;base64,' . $imgData;
                                ?>
                                <img src="<?php echo $src; ?>" alt="Question Image" class="max-w-xs max-h-xs">
                            <?php endif; ?>
                            <?php foreach ($question['choices'] as $choice) : ?>
                                <div class="flex flex-col">
                                    <span class="text-2xl"><?php echo htmlspecialchars($choice['letter'] ?? '') . '. ' . htmlspecialchars($choice['answer_text'] ?? ''); ?></span>
                                    <?php if ($choice['answer_image']) : ?>
                                        <?php
                                        $choiceImgData = base64_encode($choice['answer_image']);
                                        $choiceSrc = 'data:image/jpeg;base64,' . $choiceImgData;
                                        ?>
                                        <img src="<?php echo $choiceSrc; ?>" alt="Choice Image" class="max-w-xs max-h-xs mt-1">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="flex gap-4 items-center">
                            <div class="flex flex-col items-start gap-1 justify-start">
                                <div class="flex items-center gap-4 bg-[#FAFAFA] shadow-lg rounded-lg flex items-center justify-center p-2">
                                    <?php
                                    switch ($question['details']['difficulty']) {
                                        case 'E':
                                            echo '<span class="text-green-500 font-medium text-2xl">Easy</span>';
                                            break;
                                        case 'N':
                                            echo '<span class="text-yellow-500 font-medium text-2xl">Normal</span>';
                                            break;
                                        case 'H':
                                            echo '<span class="text-red-500 font-medium text-2xl">Hard</span>';
                                            break;
                                        default:
                                            echo '<span class="text-zinc-600 font-medium text-2xl">Unknown</span>';
                                    }
                                    ?>
                                </div>
                                <div class="flex items-center gap-4 bg-[#FAFAFA] shadow-lg rounded-lg flex items-center justify-center p-2">
                                    <p class="font-semibold text-2xl"><?php echo htmlspecialchars($question['details']['question_points'] ?? ''); ?> pts.</p>
                                </div>
                                <div class="flex items-center gap-4 bg-[#FAFAFA] shadow-lg rounded-lg flex items-center justify-center p-2">
                                    <?php
                                    $cloIds = explode(',', $question['details']['clo_id']);
                                    $cloNumbers = array();
                                    foreach ($cloIds as $cloId) {
                                        $sql = "SELECT clo_number FROM course_outcomes WHERE clo_id = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $cloId);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $cloNumbers[] = $row['clo_number'];
                                        }
                                    }
                                    $cloNumbersString = implode(', ', $cloNumbers);
                                    ?>
                                    <p class="font-semibold text-2xl">CLO: <?php $cloNumbersString = str_replace('CLO', '', $cloNumbersString);
                                                                            echo $cloNumbersString; ?></p>
                                </div>
                            </div>
                            <!-- Plus Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-circle">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="16" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                            </svg>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <script>
            document.getElementById("add_5_questions").addEventListener("click", function() {
                const questionItems = document.querySelectorAll(".question-item");

                // If less than 5 questions, then alert
                if (questionItems.length < 5) {
                    alert("Not enough questions to add.");
                    return;
                }

                // Click 5 .question-item elements
                for (let i = 0; i < 5; i++) {
                    const questionItem = questionItems[i];
                    if (questionItem) {
                        questionItem.click();
                    }
                }






            });
        </script>



        <!-- Exam Settings Section -->
        <div id="exam-settings" class="text-2xl flex flex-col gap-2 p-6" style="display: none;">
            <!-- Text area for Exam Instruction -->
            <div class="w-full flex flex-col gap-2 mb-2">
                <label class="w-full text-white" for="exam_instruction">Exam Rules</label>
                <textarea class="p-4 w-full text-zinc-800 rounded-xl" id="exam_instruction" name="exam_instruction" cols="30" rows="10"><?php echo
                                                                                                                                        htmlspecialchars($exam['exam_instruction']);
                                                                                                                                        ?></textarea>
            </div>

            <div class="flex w-full items-center gap-2 mb-2">
                <button class="w-full bg-white text-zinc-800 font-medium py-4 rounded-xl flex items-center justify-center" type="button">Preview</button>
                <button id="download-exam-btn" class="w-full bg-[#F3C44C] py-4 rounded-xl flex font-medium items-center justify-center" type="button">Download</button>
            </div>
            <button class="mb-2 w-full bg-[#F3C44C] py-4 rounded-xl flex font-medium items-center justify-center" type="button">Save Progress</button>
            <button class="mb-2 w-full bg-[#F3C44C] py-4 rounded-xl flex font-medium items-center justify-center" type="button">Upload to Exam Library</button>
        </div>
    </div>

    <!-- Main Exam Creator -->
    <main class="ml-[400px] mt-[70px] px-20 py-10">
        <form id="exam-form" class="w-full" method="POST" enctype="multipart/form-data">

            <h2 class="font-semibold mb-2">Exam Details</h2>

            <input class="mb-4 outline w-full outline-zinc-300 outline-1 py-2 px-4 rounded-lg" type="text" name="exam_name" value="<?php echo htmlspecialchars($exam['exam_name']); ?>">

            <h2 class="font-semibold mb-2">Exam Rules</h2>
            <!-- Textarea -->
            <textarea class="mb-4 outline w-full outline-zinc-200 p-4 font-normal rounded-lg text-xl" name="exam_instruction" id="exam_instruction" cols="30" rows="8" readonly><?php echo htmlspecialchars($exam['exam_instruction']); ?></textarea>

            <!-- Divider -->
            <hr class="mb-4">


            <h3 class="w-full font-semibold mb-2">Questions
                <span class="text-base font-normal text-gray-400 ml-1" id="total-questions"></span>
                <span class="text-base font-normal text-gray-400 ml-1" id="total-points"></span>
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

                usort($combined_result, function ($a, $b) {
                    return strtotime($a['data']['date_created']) - strtotime($b['data']['date_created']);
                });

                $questionOrder = 1;

                foreach ($combined_result as $item) {
                    if ($item['type'] === 'question') {
                        $question = $item['data'];
                ?>
                        <div class="existing-question bg-blue-100/40 shadow-xl p-6 gap-4 outline-zinc-300 rounded-md outline outline-1 flex flex-col relative <?php if ($question['in_question_library'] == 0) : ?>cursor-not-allowed<?php endif; ?>" data-question-id="<?php echo $question['question_id']; ?>">
                            <div class="flex w-full justify-between">
                                <div class="flex flex-col">
                                    <label class="mb-2" for="question_id">Question ID</label>
                                    <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="question_id[]" value="<?php echo htmlspecialchars($question['question_id']); ?>" readonly>


                                </div>
                                <svg class="trash-icon" data-question-id="<?php echo $question['question_id']; ?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2" for="question_text">Question Text
                                    <?php if (empty($question['question_text'])) : ?>
                                        <span class="text-red-400">No Question Text*</span>
                                    <?php endif; ?>
                                </label>
                                <textarea class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="question_text[]" <?php if ($question['in_question_library'] == 0) : ?>readonly<?php endif; ?>><?php echo htmlspecialchars($question['question_text']); ?></textarea>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2" for="question_image">Question Image</label>
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="question_image[]" <?php if ($question['in_question_library'] == 0) : ?>disabled<?php endif; ?>>

                                <?php if (!empty($question['question_image'])) : ?>
                                    <?php
                                    $imgData = base64_encode($question['question_image']);
                                    $src = 'data:image/jpeg;base64,' . $imgData;
                                    ?>
                                    <img src="<?php echo $src; ?>" alt="Question Image" style="max-width: 200px; max-height: 200px; object-fit: cover;" class="existing-question-image">
                                <?php endif; ?>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2" for="clo_id">CLO ID
                                    <?php if (empty($question['clo_id'])) : ?>
                                        <span class="text-red-400">No CLO ID*</span>
                                    <?php endif; ?>
                                </label>
                                <select class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="clo_id[]" multiple <?php if ($question['in_question_library'] == 0) : ?>disabled<?php endif; ?>>
                                    <?php
                                    $selectedCloIds = explode(',', $question['clo_id']);
                                    foreach ($clos as $clo) :
                                    ?>
                                        <option value="<?php echo $clo['clo_id']; ?>" <?php if (in_array($clo['clo_id'], $selectedCloIds)) echo 'selected'; ?>>
                                            <?php echo $clo['clo_number'] . ' - ' . $clo['clo_details']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2" for="difficulty">Difficulty
                                    <?php if (empty($question['difficulty'])) : ?>
                                        <span class="text-red-400">No Difficulty*</span>
                                    <?php endif; ?>
                                </label>
                                <select class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="difficulty[]" <?php if ($question['in_question_library'] == 0) : ?>disabled<?php endif; ?>>
                                    >
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
                                <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300 existing-question-points" type="number" name="question_points[]" value="<?php echo htmlspecialchars($question['question_points']); ?>" <?php if ($question['in_question_library'] == 0) : ?>readonly<?php endif; ?>>
                            </div>

                            <!-- Display question choices -->
                            <h3 class="font-semibold mt-4">Choices</h3>
                            <?php
                            $sql = "SELECT * FROM question_choices WHERE answer_id = ?";
                            $stmt = $conn->prepare($sql);
                            if (!$stmt) {
                                die("Error preparing statement: " . $conn->error);
                            }

                            $stmt->bind_param("i", $question['answer_id']);
                            if (!$stmt->execute()) {
                                die("Error executing statement: " . $stmt->error);
                            }

                            $choices_result = $stmt->get_result();
                            $choiceIndex = 0;

                            while ($choice = $choices_result->fetch_assoc()) {
                                $choiceLetter = chr(65 + $choiceIndex);
                                $imageId = "answer_image_{$question['question_id']}_{$choiceIndex}";
                            ?>
                                <!-- Main Question Choices -->
                                <div class="choice flex gap-4 items-center">
                                    <!-- Is Correct -->
                                    <input type="checkbox" name="is_correct[<?php echo $question['question_id']; ?>][]" value="<?php echo $choice['is_correct']; ?>" <?php if ($choice['is_correct']) echo 'checked'; ?><?php if ($question['in_question_library'] == 0) : ?> disabled<?php endif; ?>>

                                    <!-- Letter -->
                                    <p class="font-semibold"><?php echo $choiceLetter; ?></p>

                                    <!-- Answer Text -->
                                    <div class="flex flex-col w-full">
                                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="answer_text[<?php echo $question['question_id']; ?>][]" value="<?php echo htmlspecialchars($choice['answer_text']); ?>" <?php if ($question['in_question_library'] == 0) : ?> readonly<?php endif; ?>>
                                    </div>

                                    <!-- Image -->
                                    <div class="flex flex-col">
                                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="answer_image[<?php echo $question['question_id']; ?>][]" <?php if ($question['in_question_library'] == 0) : ?> disabled<?php endif; ?>>
                                        >
                                    </div>

                                    <!-- Hidden input field for question_choices_id -->
                                    <input type="hidden" name="question_choices_id[<?php echo $question['question_id']; ?>][]" value="<?php echo $choice['question_choices_id']; ?>">

                                    <!-- Create a toggle to open and close image -->
                                    <?php if (!empty($choice['answer_image'])) : ?>
                                        <script>
                                            function toggleImage_<?php echo $imageId; ?>() {
                                                var x = document.getElementById("<?php echo $imageId; ?>");
                                                if (x.style.display === "none") {
                                                    x.style.display = "block";
                                                } else {
                                                    x.style.display = "none";
                                                }
                                            }
                                        </script>

                                        <button type="button" onclick="toggleImage_<?php echo $imageId; ?>()">Toggle Image</button>

                                        <?php
                                        $imgData = base64_encode($choice['answer_image']);
                                        $src = 'data:image/jpeg;base64,' . $imgData;
                                        ?>
                                        <img id="<?php echo $imageId; ?>" style="display: block; max-width: 200px; max-height: 200px;" src="<?php echo $src; ?>" alt="Answer Image" />
                                    <?php endif; ?>
                                </div>

                            <?php
                                $choiceIndex++;
                            }
                            ?>
                            <p class="absolute right-[100%] py-2 px-4 rounded-l-lg -z-10 outline outline-1 outline-zinc-200 bg-yellow-400 text-white"><?php echo $questionOrder; ?></p>
                        </div>

                <?php
                        $questionOrder++;
                    }
                }
                ?>
            </div>


            <div id="new_questions"></div>

            <div class="mt-4">
                <button class="px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="button" id="add_question">Add Question</button>
                <button class="px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="submit">Save Exam</button>
            </div>
        </form>
    </main>

    <!-- Exam Preview -->
    <div id="exam-preview" class="hidden fixed justify-center items-center z-[100000] left-[50%] top-[50%] transform-gpu -translate-x-1/2 -translate-y-1/2 w-screen h-screen bg-black/40 backdrop-blur-xl">
        <div class="flex text-white bg-[#343A40] w-[80%] h-[80%] rounded-xl shadow-xl">
            <div class="w-[70%] overflow-y-scroll flex flex-col gap-8">
                <?php
                $totalQuestions = count($combined_result);
                $questionsPerPage = 30;
                $totalPages = ceil($totalQuestions / $questionsPerPage);

                for ($page = 1; $page <= $totalPages; $page++) {
                    $startIndex = ($page - 1) * $questionsPerPage;
                    $endIndex = min($startIndex + $questionsPerPage, $totalQuestions);
                ?>
                    <div class="page py-8 px-20 bg-white rounded-xl text-2xl text-zinc-800">
                        <div class="w-full flex items-center justify-between gap-4 text-xl font-normal text-zinc-800">
                            <!-- Get the params course_code in the URL -->
                            <p>
                                <?php
                                $course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
                                echo $course_code;
                                ?>
                            </p>

                            <img src="img/APC AcademX Logo.png" alt="APC AcademX Logo" class="max-w-[100px]">

                            <h4 class="text-zinc-800">
                                <?php echo htmlspecialchars($exam['exam_name']); ?>
                            </h4>
                        </div>
                        <div class="w-full h-0.5 my-8 bg-black"></div>

                        <?php if ($page === 1) { ?>
                            <div class="w-full flex items-center h-[100px] border-black border-1 mb-6">
                                <div class="w-[80%] flex flex-col h-full">
                                    <div class="h-full p-4 border-[1px] border-black">Name:</div>
                                    <div class="flex h-full">
                                        <div class="w-full h-full p-4 border-[1px] border-black">Section:</div>
                                        <div class="w-full h-full p-4 border-[1px] border-black">Date:</div>
                                    </div>
                                </div>
                                <div class="w-[20%] h-full p-4 border-[1px] border-black">
                                    Score:
                                </div>
                            </div>

                            <h1 class="font-medium">General Instructions</h1>
                            <p class="mb-6">
                                1. Read, understand and follow every specified direction carefully.<br />
                                2. Avoid using your cellular phone during exam proper.<br />
                                3. This is exam CLOSED NOTES<br />
                                4. Shade your answer on the answer sheet.<br />
                                5. NO ERASURES. Erasure means WRONG.<br />
                                6. Strictly NO CHEATING of any form. Anybody caught cheating will receive a FAILING MARK.
                            </p>
                        <?php } ?>

                        <!-- Answer Sheet -->
                        <div id="answer-sheet">
                            <div class="flex justify-between">
                                <?php
                                $questionsPerColumn = 15;
                                $columnsPerPage = 2;

                                for ($column = 1; $column <= $columnsPerPage; $column++) {
                                    $columnStartIndex = $startIndex + ($column - 1) * $questionsPerColumn;
                                    $columnEndIndex = min($columnStartIndex + $questionsPerColumn, $endIndex);
                                ?>
                                    <div class="column w-1/2">
                                        <?php for ($i = $columnStartIndex; $i < $columnEndIndex; $i++) {
                                            $item = $combined_result[$i];
                                            if ($item['type'] === 'question') {
                                                $question = $item['data'];
                                        ?>
                                                <div class="question flex gap-4 items-center">
                                                    <p class="font-semibold"><?php echo $i + 1; ?>.</p>
                                                    <div class="choices-container flex gap-4">
                                                        <?php
                                                        $sql = "SELECT * FROM question_choices WHERE answer_id = ?";
                                                        $stmt = $conn->prepare($sql);
                                                        if (!$stmt) {
                                                            die("Error preparing statement: " . $conn->error);
                                                        }

                                                        $stmt->bind_param("i", $question['answer_id']);
                                                        if (!$stmt->execute()) {
                                                            die("Error executing statement: " . $stmt->error);
                                                        }

                                                        $choices_result = $stmt->get_result();
                                                        $choiceIndex = 0;

                                                        while ($choice = $choices_result->fetch_assoc()) {
                                                            $choiceLetter = chr(65 + $choiceIndex);
                                                        ?>
                                                            <div class="choice flex items-center">
                                                                <div class="w-6 h-6 rounded-full border-[1px] border-black flex items-center justify-center">
                                                                    <span class="text-base font-semibold"><?php echo $choiceLetter; ?></span>
                                                                </div>
                                                            </div>
                                                        <?php
                                                            $choiceIndex++;
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="w-full h-0.5 mt-8 bg-black"></div>
                        <div class="w-full flex justify-center mt-4 text-lg">
                            <p>Page <?php echo $page; ?> of <?php echo $totalPages; ?></p>
                        </div>


                    </div>
                <?php } ?>

                <!-- Questions and Choices -->
                <?php
                $totalQuestionsWithChoices = count($combined_result);
                $questionsPerPageWithChoices = 30;
                $totalPagesWithChoices = ceil($totalQuestionsWithChoices / $questionsPerPageWithChoices);

                for ($page = 1; $page <= $totalPagesWithChoices; $page++) {
                    $startIndex = ($page - 1) * $questionsPerPageWithChoices;
                    $endIndex = min($startIndex + $questionsPerPageWithChoices, $totalQuestionsWithChoices);
                ?>
                    <div class="page py-8 px-20 bg-white rounded-xl text-2xl text-zinc-800">
                        <div class="w-full flex items-center justify-between gap-4 text-xl font-normal text-zinc-800">
                            <!-- Get the params course_code in the URL -->
                            <p>
                                <?php
                                $course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
                                echo $course_code;
                                ?>
                            </p>

                            <img src="img/APC AcademX Logo.png" alt="APC AcademX Logo" class="max-w-[100px]">

                            <h4 class="text-zinc-800">
                                <?php echo htmlspecialchars($exam['exam_name']); ?>
                            </h4>
                        </div>
                        <div class="w-full h-0.5 my-8 bg-black"></div>

                        <div class="flex justify-between">
                            <?php
                            $questionsPerColumnWithChoices = 15;
                            $columnsPerPageWithChoices = 2;

                            for ($column = 1; $column <= $columnsPerPageWithChoices; $column++) {
                                $columnStartIndex = $startIndex + ($column - 1) * $questionsPerColumnWithChoices;
                                $columnEndIndex = min($columnStartIndex + $questionsPerColumnWithChoices, $endIndex);
                            ?>
                                <div class="column w-1/2">
                                    <?php for ($i = $columnStartIndex; $i < $columnEndIndex; $i++) {
                                        $item = $combined_result[$i];
                                        if ($item['type'] === 'question') {
                                            $question = $item['data'];
                                    ?>
                                            <div class="question mb-4">
                                                <p class="font-semibold mb-2"><?php echo $i + 1; ?>. <?php echo $question['question_text']; ?></p>
                                                <div class="choices-container">
                                                    <?php
                                                    $sql = "SELECT * FROM question_choices WHERE answer_id = ?";
                                                    $stmt = $conn->prepare($sql);
                                                    if (!$stmt) {
                                                        die("Error preparing statement: " . $conn->error);
                                                    }

                                                    $stmt->bind_param("i", $question['answer_id']);
                                                    if (!$stmt->execute()) {
                                                        die("Error executing statement: " . $stmt->error);
                                                    }

                                                    $choices_result = $stmt->get_result();
                                                    $choiceIndex = 0;

                                                    while ($choice = $choices_result->fetch_assoc()) {
                                                        $choiceLetter = chr(65 + $choiceIndex);
                                                    ?>
                                                        <p class="mb-1"><?php echo $choiceLetter; ?>. <?php echo $choice['answer_text']; ?></p>
                                                    <?php
                                                        $choiceIndex++;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Footer -->
                        <div class="w-full h-0.5 mt-8 bg-black"></div>
                        <div class="w-full flex justify-center mt-4 text-lg">
                            <p>Page <?php echo $page; ?> of <?php echo $totalPagesWithChoices; ?></p>
                        </div>
                    </div>
                <?php } ?>

                <!-- Answer Keys -->
                <?php
                $totalAnswerKeys = count($combined_result);
                $answerKeysPerPage = 30;
                $totalAnswerKeyPages = ceil($totalAnswerKeys / $answerKeysPerPage);

                for ($page = 1; $page <= $totalAnswerKeyPages; $page++) {
                    $startIndex = ($page - 1) * $answerKeysPerPage;
                    $endIndex = min($startIndex + $answerKeysPerPage, $totalAnswerKeys);
                ?>
                    <div class="page py-8 px-20 bg-white rounded-xl text-2xl text-zinc-800">
                        <div class="w-full flex items-center justify-between gap-4 text-xl font-normal text-zinc-800">
                            <!-- Get the params course_code in the URL -->
                            <p>
                                <?php
                                $course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
                                echo $course_code;
                                ?>
                            </p>

                            <img src="img/APC AcademX Logo.png" alt="APC AcademX Logo" class="max-w-[100px]">

                            <h4 class="text-zinc-800">
                                <?php echo htmlspecialchars($exam['exam_name']); ?>
                            </h4>
                        </div>
                        <div class="w-full h-0.5 my-8 bg-black"></div>

                        <div class="flex justify-between">
                            <?php
                            $answerKeysPerColumn = 15;
                            $columnsPerAnswerKeyPage = 2;

                            for ($column = 1; $column <= $columnsPerAnswerKeyPage; $column++) {
                                $columnStartIndex = $startIndex + ($column - 1) * $answerKeysPerColumn;
                                $columnEndIndex = min($columnStartIndex + $answerKeysPerColumn, $endIndex);
                            ?>
                                <div class="column w-1/2">
                                    <?php for ($i = $columnStartIndex; $i < $columnEndIndex; $i++) {
                                        $item = $combined_result[$i];
                                        if ($item['type'] === 'question') {
                                            $question = $item['data'];
                                    ?>
                                            <div class="question mb-4">
                                                <p class="font-semibold mb-2"><?php echo $i + 1; ?>. <?php echo $question['question_text']; ?></p>
                                                <div class="choices-container">
                                                    <?php
                                                    $sql = "SELECT * FROM question_choices WHERE answer_id = ? AND is_correct = 1";
                                                    $stmt = $conn->prepare($sql);
                                                    if (!$stmt) {
                                                        die("Error preparing statement: " . $conn->error);
                                                    }

                                                    $stmt->bind_param("i", $question['answer_id']);
                                                    if (!$stmt->execute()) {
                                                        die("Error executing statement: " . $stmt->error);
                                                    }

                                                    $choices_result = $stmt->get_result();
                                                    $choiceIndex = 0;

                                                    while ($choice = $choices_result->fetch_assoc()) {
                                                        $choiceLetter = chr(65 + $choiceIndex);
                                                    ?>
                                                        <p class="mb-1"><?php echo $choiceLetter; ?>. <?php echo $choice['answer_text']; ?></p>
                                                    <?php
                                                        $choiceIndex++;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Footer -->
                        <div class="w-full h-0.5 mt-8 bg-black"></div>
                        <div class="w-full flex justify-center mt-4 text-lg">
                            <p>Answer Keys - Page <?php echo $page; ?> of <?php echo $totalAnswerKeyPages; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="w-[30%] h-full flex flex-col p-8 items-center">
                <h2 class="text-2xl font-medium mb-6">Exam Preview</h2>
                <button id="print-exam-btn" class="w-full mt-4 bg-yellow-400 mb-4 text-2xl py-6 rounded-xl flex font-medium items-center justify-center text-zinc-800" type="button">Save as PDF</button>
                <button id="close-exam-download-btn" class="w-full bg-[#EDEDED] text-2xl text-zinc-800 py-6 rounded-xl flex font-medium items-center justify-center" type="button">Back</button>

            </div>
        </div>
    </div>
    <!-- Questionairre -->
    <script>
        // Create a PDF Answer Sheet using pdfmake onload
        document.addEventListener('DOMContentLoaded', async function() {

            // Get the course_code from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const courseCode = urlParams.get('course_code');

            // Get the exam name
            const examName = document.querySelector('input[name="exam_name"]').value;

            // Get the question_text[]
            const questionText = document.querySelectorAll('textarea[name="question_text[]"]');

            // Get the question id
            const questionId = document.querySelectorAll('input[name="question_id[]"]');

            // Get the question image using existing-question-image
            const questionImages = document.querySelectorAll('.existing-question-image');

            // Generate the content for the PDF
            const content = [];

            // ----------------- Questionnaire -----------------

            // Add a horizontal line, fill the width of the page
            content.push({
                table: {
                    widths: ['*'],
                    body: [
                        [" "],
                        [" "]
                    ]
                },
                layout: {
                    hLineWidth: function(i, node) {
                        return (i === 0 || i === node.table.body.length) ? 0 : 2;
                    },
                    vLineWidth: function(i, node) {
                        return 0;
                    },
                }
            }, );

            // Add the General Instructions
            content.push({
                text: 'General Instructions',
                style: 'header',
                margin: [0, 10, 0, 10]
            });

            let leftColumn = [];
            let rightColumn = [];
            let currentColumn = leftColumn;

            // Add the questions
            questionText.forEach((question, index) => {
                const questionContent = [];

                // Display the question image if it exists
                if (questionImages[index]) {
                    questionContent.push({
                        image: questionImages[index].src,
                        width: 200,
                        height: 200,
                        margin: [0, 0, 0, 10]
                    });
                }

                questionContent.push({
                    text: `${index + 1}. ${question.value}`,
                    margin: [0, 0, 0, 10]
                });

                // Loop through the choices and question id to get the question choices
                const choices = document.querySelectorAll(`input[name="answer_text[${questionId[index].value}][]"]`);

                choices.forEach((choice, choiceIndex) => {
                    const choiceImage = document.querySelector(`img[id="answer_image_${questionId[index].value}_${choiceIndex}"]`);
                    const choiceLetter = String.fromCharCode(65 + choiceIndex);
                    questionContent.push({
                        text: `${choiceLetter}. ${choice.value}`,
                        margin: [20, 0, 0, 0]
                    });

                    if (choiceImage) {
                        questionContent.push({
                            image: choiceImage.src,
                            width: 200,
                            height: 200,
                            margin: [20, 0, 0, 10]
                        });
                    }
                });

                // Add question content to the current column
                currentColumn.push({
                    stack: questionContent,
                    margin: [0, 0, 0, 20]
                });

                // Alternate columns
                currentColumn = (currentColumn === leftColumn) ? rightColumn : leftColumn;
            });

            // Add columns to the content
            content.push({
                columns: [{
                        width: '50%',
                        stack: leftColumn
                    },
                    {
                        width: '50%',
                        stack: rightColumn
                    }
                ],
                margin: [0, 0, 0, 20]
            });



            // ----------------- Answer Sheet -----------------

            // Add a page break
            content.push({
                text: ' ',
                pageBreak: 'after'
            });


            /*
                // Example (2 columns)
                1. (a), (b), (c), (d), (e) 2. (a), (b)
                ...
            */

            // Add the Answer Sheet
            content.push({
                text: 'Answer Sheet',
                style: 'header',
                alignment: 'center',
                margin: [30, 30, 30, 30]
            });

            // Create an array to store the answer sheet content
            const answerSheetContent = [];

            // Loop through the questions and divide them into columns
            questionText.forEach((question, index) => {
                // Loop through the choices and add them to the question content
                const choices = document.querySelectorAll(`input[name="answer_text[${questionId[index].value}][]"]`);
                const choiceTexts = [];
                choices.forEach((choice, choiceIndex) => {
                    // Add the choice letter
                    const choiceLetter = String.fromCharCode(97 + choiceIndex);
                    choiceTexts.push(`(${choiceLetter})`);
                });

                // Join the choices into a single string with a space in between
                const choicesString = choiceTexts.join(' ');

                // Add the question number and choices to the answer sheet content
                answerSheetContent.push({
                    text: `${index + 1}. ${choicesString}`,
                    margin: [0, 0, 10, 0]
                });
            });

            // Add the answer sheet content to the content
            content.push({
                columns: answerSheetContent,
                columnGap: 20
            });

            // ----------------- Answer Keys -----------------

            // Add a page break
            content.push({
                text: ' ',
                pageBreak: 'after'
            });

            /*
                // Example (2 columns)
                1. (a) 2. (b)
                ...
            */

            // Add the Answer Keys
            content.push({
                text: 'Answer Keys',
                style: 'header',
                alignment: 'center',
                margin: [30, 30, 30, 30]
            });

            // Get correct answers
            const correctAnswers = [];

            // Loop through the existing questions and get the correct answers
            questionId.forEach((questionId, index) => {
                // Get the correct choices
                const correctChoices = document.querySelectorAll(`input[name="is_correct[${questionId.value}][]"]`);

                // Loop through the choices and get the correct choice
                correctChoices.forEach((choice, choiceIndex) => {
                    if (choice.checked) {
                        const choiceLetter = String.fromCharCode(65 + choiceIndex);
                        correctAnswers.push(choiceLetter);
                    }
                });
            });

            // Display the correct answers
            const correctAnswerContent = [];

            // Loop through the correct answers and divide them into columns
            correctAnswers.forEach((correctAnswer, index) => {
                correctAnswerContent.push({
                    text: `${index + 1}. (${correctAnswer})`,
                    margin: [0, 0, 10, 0]
                });
            });

            // Add the correct answer content to the content
            content.push({
                columns: correctAnswerContent,
                columnGap: 20
            });






            // Create a PDF document
            const docDefinition = {
                content: content,
                pageMargins: [30, 30, 30, 30],
                defaultStyle: {
                    columnGap: 20
                },
                footer: function(currentPage, pageCount) {
                    // Add APC AcademX Logo and Page Number

                    return {
                        columns: [{
                                width: '50%',
                                text: 'APC AcademX',
                                alignment: 'left'
                            },
                            {
                                width: '50%',
                                text: currentPage.toString() + ' of ' + pageCount,
                                alignment: 'right'
                            }
                        ],
                        margin: [10, 10, 10, 10]
                    };
                },
                header: function(currentPage, pageCount) {
                    // Add the course code, APC AcademX Logo, and Exam Name

                    return {
                        columns: [{
                                width: '33%',
                                text: courseCode
                            },
                            {
                                width: '33%',
                                text: 'APC AcademX',
                                alignment: 'center'
                            },
                            {
                                width: '33%',
                                text: examName,
                                alignment: 'right'
                            }
                        ],
                        margin: [10, 10, 10, 10]
                    };
                },
            };

            // Create a PDF document, make it narrow margins
            const pdfDocGenerator = pdfMake.createPdf(docDefinition);

            // Save the PDF document
            pdfDocGenerator.download('exam-preview.pdf');
        });
    </script>



    <script>
        function duplicateQuestion(questionId) {
            // Get the course topic ID from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const courseTopicId = urlParams.get('course_topic_id');

            $.ajax({
                url: 'duplicate_question.php',
                type: 'POST',
                data: {
                    question_id: questionId,
                    course_topic_id: courseTopicId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        location.reload();
                    } else {
                        console.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var btn_diva = document.getElementById("btn_diva");
            var btn_divb = document.getElementById("btn_divb");
            var diva = document.getElementById("question-library");
            var divb = document.getElementById("exam-settings");

            function activateButton(activeButton) {
                // Remove the active class from all buttons
                document.querySelectorAll('.button').forEach(button => {
                    button.classList.remove('active');
                });
                // Add the active class to the clicked button
                activeButton.classList.add('active');
            }

            // Event listeners for the buttons
            btn_diva.addEventListener("click", (event) => {
                event.preventDefault(); // Prevent the default behavior of the button
                diva.style.display = "block";
                divb.style.display = "none";
                activateButton(btn_diva);
            });

            btn_divb.addEventListener("click", (event) => {
                event.preventDefault(); // Prevent the default behavior of the button
                diva.style.display = "none";
                divb.style.display = "block";
                activateButton(btn_divb);
            });

            // Display DIV A and set button DIV A as active on initial load
            window.addEventListener('load', () => {
                diva.style.display = "block";
                divb.style.display = "none";
                activateButton(btn_diva);
            });
        });
    </script>

    <script>
        // Console log something when the download button is clicked
        document.getElementById('download-exam-btn').addEventListener('click', function() {
            // Show the modal
            document.getElementById('exam-preview').style.position = 'fixed';
            document.getElementById('exam-preview').style.display = 'flex';
        });

        // Close the modal when the close button is clicked
        document.getElementById('close-exam-download-btn').addEventListener('click', function() {
            // Hide the modal
            document.getElementById('exam-preview').style.display = 'none';
        });
    </script>

    <script>
        totalQuestions = 0;
        totalPoints = 0;

        // ----------------------------- Helper Functions -----------------------------

        // Function to fetch total points from the API
        async function fetchTotalPoints() {
            const urlParams = new URLSearchParams(window.location.search);
            const courseTopicId = urlParams.get('course_topic_id');

            var res = await fetch(`http://localhost:8000/api/exam/get-exam-id-by-course-topic-id.php?course_topic_id=${courseTopicId}`);
            var data = await res.json();
            if (data.error) {
                console.error(data.error);
                return;
            }
            var examId = data;
            console.log("Exam ID:", examId);

            res = await fetch(`http://localhost:8000/api/question/get-total-points-by-exam-id.php?exam_id=${examId}`);
            data = await res.json();
            if (data.error) {
                console.error(data.error);
                return;
            }
            var totalPointsData = data;
            console.log("Total Points:", totalPointsData);

            res = await fetch(`http://localhost:8000/api/question/get-total-questions-by-exam-id.php?exam_id=${examId}`)
            data = await res.json();
            if (data.error) {
                console.error(data.error);
                return;
            }
            var totalQuestionsData = data;
            console.log("Total Questions:", totalQuestionsData);

            // Add totalPointsData to the totalPoints variable
            totalPoints = totalPointsData;
            document.getElementById('total-points').innerText = `(${totalPoints} Points)`;

            // Add totalQuestionsData to the totalQuestions variable
            totalQuestions = totalQuestionsData;
            document.getElementById('total-questions').innerText = `(${totalQuestions} Questions)`;
        }

        // ----------------------------- Main Script -----------------------------

        // Set empty arrays for new_is_correct, new_answer_text, and new_answer_image
        var new_is_correct = [];
        var new_answer_text = [];
        var new_answer_image = [];

        function updateIsCorrectArray() {
            new_is_correct = [];
            $(".new-question").each(function() {
                var is_correct = [];
                $(this).find("input[name='new_is_correct[]']").each(function() {
                    is_correct.push($(this).is(":checked") ? 1 : 0);
                });
                new_is_correct.push(is_correct);
            });
            console.log("New Is Correct:", new_is_correct);
        }

        function updateAnswerTextArray() {
            new_answer_text = [];
            $(".new-question").each(function() {
                var answer_text = [];
                $(this).find("input[name='new_answer_text[]']").each(function() {
                    answer_text.push($(this).val());
                });
                new_answer_text.push(answer_text);
            });
            console.log("New Answer Text:", new_answer_text);
        }

        function updateAnswerImageArray() {
            new_answer_image = [];
            $(".new-question").each(function() {
                var answer_image = [];
                $(this).find("input[name='new_answer_image[]']").each(function() {
                    var file = $(this)[0].files[0];
                    answer_image.push(file ? file : null);
                });
                new_answer_image.push(answer_image);
            });
            console.log("New Answer Image:", new_answer_image);
        }

        function updateHiddenInputs() {
            var new_is_correct_string = JSON.stringify(new_is_correct);
            var new_answer_text_string = JSON.stringify(new_answer_text);
            $("input[name='new_is_correct_string']").remove();
            $("input[name='new_answer_text_string']").remove();
            $("<input>").attr({
                type: "hidden",
                name: "new_is_correct_string",
                value: new_is_correct_string
            }).appendTo("form");
            $("<input>").attr({
                type: "hidden",
                name: "new_answer_text_string",
                value: new_answer_text_string
            }).appendTo("form");
        }

        // ----------------------------- Event Listeners -----------------------------

        // Read event for click in add_question button
        $(document).on("change", "input[name='new_is_correct[]']", function() {
            updateIsCorrectArray();
            updateHiddenInputs();
        });

        // Read event for input in new_answer_text
        $(document).on("input", "input[name='new_answer_text[]']", function() {
            updateAnswerTextArray();
            updateHiddenInputs();
        });

        // Read event for change in new_answer_image
        $(document).on("change", "input[name='new_answer_image[]']", function() {
            updateAnswerImageArray();
        });

        // Read event for click in svg delete-question
        $(document).on("click", ".trash-icon", async function() {
            const questionId = $(this).data("question-id");
            const questionElement = $(this).closest(".question");

            if (confirm("Are you sure you want to delete this question?")) {
                try {
                    const res = await fetch(`http://localhost:8000/api/question/delete-question-by-question-id.php?question_id=${questionId}`);
                    const data = await res.text();
                    console.log(data);
                    questionElement.remove();
                    fetchTotalPoints();

                    // Reload the page
                    location.reload();
                } catch (error) {
                    console.error("Error deleting question:", error);
                }
            }
        });

        // Update total points dynamically for new and existing questions
        $(document).on("input", ".new-question-points, .existing-question-points", function() {
            var newQuestionPoints = 0;
            $(".new-question-points, .existing-question-points").each(function() {
                newQuestionPoints += parseInt($(this).val() || 0);
            });
            $("#total-points").text(`(${newQuestionPoints} Points)`);
        });

        // Add question button
        $(document).on("click", ".add-choice-btn", function() {
            var choicesContainer = $(this).siblings(".choices-container");
            var choiceCount = choicesContainer.children(".choice").length;
            var questionIndex = $(this).closest(".question").index();

            if (choiceCount < 5) {
                var choiceLetter = String.fromCharCode(65 + choiceCount);
                var choiceHTML = `
            <div class="choice flex gap-4 items-center">
                <input type="checkbox" name="new_is_correct[]" value="1">
                <p class="font-semibold">${choiceLetter}</p>
                <div class="flex flex-col w-full">
                    <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_answer_text[]" placeholder="Type answer text here...">
                </div>
                <div class="flex flex-col">
                    <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_answer_image[]">
                </div>
                <button type="button" class="remove-choice-btn px-2 py-1 bg-red-500 text-white rounded-md">X</button>
            </div>
        `;
                choicesContainer.append(choiceHTML);
            } else {
                alert("Maximum of 5 choices allowed.");
            }
        });

        // Remove choice button
        $(document).on("click", ".remove-choice-btn", function() {
            $(this).closest(".choice").remove();
        });

        // Add question button
        $(document).ready(function() {
            fetchTotalPoints();

            // Form submission validation
            $("#exam-form").on("submit", async function(event) {
                event.preventDefault(); // Prevent the default form submission

                // -- Validation

                // Get all the new-question elements
                var newQuestions = $(".new-question");
                var newQuestionsLength = newQuestions.length;

                var existingQuestionsLength = $(".existing-question").length;

                // Alert
                if (newQuestionsLength === 0 && existingQuestionsLength === 0) {
                    alert(`Please add at least one question. New Questions: ${newQuestionsLength} | Existing Questions: ${existingQuestionsLength}`);
                    return;
                }

                // If there is new question, then there are no checked checkboxes for correct answers then alert
                if (newQuestionsLength > 0) {
                    var newIsCorrect = [];
                    newQuestions.each(function() {
                        var isCorrect = $(this).find("input[name='new_is_correct[]']:checked").length;
                        newIsCorrect.push(isCorrect);
                    });

                    if (newIsCorrect.includes(0)) {
                        alert("Please select at least one correct answer for each new question.");
                        return;
                    }

                    // If there are unfilled answer texts then alert
                    var newAnswerText = [];

                    newQuestions.each(function() {
                        var answerText = $(this).find("input[name='new_answer_text[]']").val();
                        newAnswerText.push(answerText);
                    });

                    if (newAnswerText.includes("")) {
                        alert("Please fill in all answer texts for each new question.");
                        return;
                    }

                    // --

                    try {
                        // -- Post Question Choices --

                        // Create a new FormData object
                        var formData = new FormData(this);

                        // Append the exam ID to the form data
                        formData.append("exam_id", <?php echo $exam_id; ?>);

                        // Append the new_is_correct and new_answer_text arrays to the FormData object
                        formData.append("new_is_correct", JSON.stringify(new_is_correct));
                        formData.append("new_answer_text", JSON.stringify(new_answer_text));

                        // Append the new_answer_image files to the FormData object
                        for (var i = 0; i < new_answer_image.length; i++) {
                            for (var j = 0; j < new_answer_image[i].length; j++) {
                                if (new_answer_image[i][j]) {
                                    formData.append(`new_answer_image[${i}][${j}]`, new_answer_image[i][j]);
                                }
                            }
                        }

                        // Send the form data using the Fetch API
                        // const response = await fetch("http://localhost/ramex/api/question-choices/post-question-choices.php", {
                        const response = await fetch("http://localhost:8000/api/question-choices/post-question-choices.php", {
                            method: "POST",
                            body: formData
                        });

                        if (!response.ok) {
                            console.error("Error posting question choices");
                        }

                        const data = await response.json();
                        console.log(data.message);

                    } catch (error) {
                        console.error("Error:", error.message);
                    }
                }


                // TODO: Fix updating of existing questions
                if (existingQuestionsLength > 0) {
                    console.log('Existing Questions Length:', existingQuestionsLength);

                    // -- Update Existing Questions --
                    async function updateExistingQuestions() {
                        // Create a new FormData object
                        var formData = new FormData();

                        // Append the exam ID to the form data
                        formData.append("exam_id", <?php echo $exam_id; ?>);

                        // Get the exam instruction
                        var examInstruction = $("#exam_instruction").val();
                        formData.append("exam_instruction", examInstruction);

                        // Loop through each existing question
                        $(".existing-question").each(function() {
                            var questionElement = $(this);

                            // Get the question ID
                            var questionId = questionElement.find("input[name='question_id[]']").val();
                            formData.append("question_id[]", questionId);

                            // Get the question text
                            var questionText = questionElement.find("textarea[name='question_text[]']").val();
                            formData.append("question_text[]", questionText);

                            // Get the question image
                            var questionImage = questionElement.find("input[name='question_image[]']")[0].files[0];
                            if (questionImage) {
                                formData.append("question_image[]", questionImage);
                            }

                            // Get the CLO ID
                            var cloId = questionElement.find("select[name='clo_id[]']").val();
                            formData.append("clo_id[]", cloId);

                            // Get the difficulty
                            var difficulty = questionElement.find("select[name='difficulty[]']").val();
                            formData.append("difficulty[]", difficulty);

                            // Get the question points
                            var questionPoints = questionElement.find("input[name='question_points[]']").val();
                            formData.append("question_points[]", questionPoints);
                        });

                        console.log("Form Data:", formData);

                        try {
                            // Send the form data to the PHP script using fetch
                            const response = await fetch('http://localhost:8000/api/question/update-existing-questions.php', {
                                method: 'POST',
                                body: formData
                            });

                            if (response.ok) {
                                const data = await response.json();
                                console.log("Server response:", data);
                                // Handle the success response here
                            } else {
                                console.error("Error updating existing questions");
                                // Handle the error response here
                            }
                        } catch (error) {
                            console.error("Error:", error);
                            // Handle any network or other errors here
                        }

                        // Update the exam instruction
                        try {
                            const examInstructionResponse = await fetch('http://localhost:8000/api/exam/update-exam-instruction.php', {
                                method: 'POST',
                                body: formData
                            });

                            if (examInstructionResponse.ok) {
                                const data = await examInstructionResponse.json();
                                console.log("Exam instruction update response:", data);

                                // Reload the page
                                location.reload();
                            } else {
                                console.error("Error updating exam instruction");
                            }
                        } catch (error) {
                            console.error("Error:", error);
                            // Handle any network or other errors here
                        }
                    }

                    async function updateExistingQuestionChoices() {
                        var questionData = [];

                        $(".existing-question").each(function() {
                            var questionElement = $(this);

                            var questionId = questionElement.find("input[name='question_id[]']").val();
                            console.log("Processing question ID:", questionId);

                            var questionChoices = [];

                            questionElement.find(".choice").each(function() {
                                var choiceElement = $(this);
                                var isCorrectValue = choiceElement.find("input[name='is_correct[" + questionId + "][]']").is(":checked") ? 1 : 0;
                                var answerTextValue = choiceElement.find("input[name='answer_text[" + questionId + "][]']").val();
                                var answerImageValue = choiceElement.find("input[name='answer_image[" + questionId + "][]']")[0].files[0];
                                var questionChoicesId = choiceElement.find("input[name='question_choices_id[" + questionId + "][]']").val();

                                console.log('Answer Image Value:', answerImageValue);

                                questionChoices.push({
                                    is_correct: isCorrectValue,
                                    answer_text: answerTextValue,
                                    question_choices_id: questionChoicesId
                                });

                                if (answerImageValue) {
                                    questionChoices[questionChoices.length - 1].answer_image = answerImageValue;
                                }
                            });

                            questionData.push({
                                question_id: questionId,
                                choices: questionChoices
                            });
                        });

                        console.log("Question Data:", questionData);

                        var formData = new FormData();

                        questionData.forEach((question, questionIndex) => {
                            formData.append(`question_data[${questionIndex}][question_id]`, question.question_id);

                            question.choices.forEach((choice, choiceIndex) => {
                                formData.append(`question_data[${questionIndex}][choices][${choiceIndex}][is_correct]`, choice.is_correct);
                                formData.append(`question_data[${questionIndex}][choices][${choiceIndex}][answer_text]`, choice.answer_text);
                                formData.append(`question_data[${questionIndex}][choices][${choiceIndex}][question_choices_id]`, choice.question_choices_id);

                                if (choice.answer_image) {
                                    formData.append(`question_data[${questionIndex}][choices][${choiceIndex}][answer_image]`, choice.answer_image);
                                }
                            });
                        });

                        console.log('Form Data for Question Choices:', formData);

                        const response = await fetch("http://localhost:8000/api/question-choices/update-existing-question-choices.php", {
                            method: "POST",
                            body: formData
                        });

                        if (response.ok) {
                            const data = await response.json();
                            console.log(data.message);
                        } else {
                            console.error("Error updating question choices");
                        }
                    }

                    updateExistingQuestions();
                    updateExistingQuestionChoices();

                }
            });

            // Add this script to pass CLO data to JavaScript
            const clos = <?php echo $clos_json; ?>;

            // CLO Index Counter to make it 2D array
            var cloIndex = 0;

            document.getElementById("add_question").addEventListener("click", function() {
                totalQuestions++;
                document.getElementById('total-questions').innerText = `(${totalQuestions} Questions)`;

                const html = String.raw;
                var newOrder = document.querySelectorAll("#new_questions .question").length + 1;

                var cloOptions = clos.map(clo => `<option value="${clo.clo_id}">${clo.clo_number} - ${clo.clo_details}</option>`).join('');

                var questionHTML = `
    <div class="new-question bg-zinc-100 mt-6 p-6 gap-4 outline-zinc-300 rounded-md outline outline-1 flex flex-col question">
        <div class="flex flex-col">
            <label class="mb-2" for="question_text">Question Text</label>
            <textarea class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="new_question_text[]"></textarea>
        </div>
        <div class="flex flex-col">
            <label class="mb-2" for="question_image">Question Image</label>
            <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_question_image[]">
        </div>
        <div class="flex flex-col">
            <label class="mb-2" for="new_clo_id">CLO ID</label>
            <select class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="new_clo_id[${cloIndex}][]" multiple>
                ${cloOptions}
            </select>
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
            <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300 new-question-points" type="number" name="new_question_points[]">
        </div>
        <div class="flex flex-col">
            <label class="mb-2" for="order">Order</label>
            <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="number" name="new_order[]" value="${newOrder}" readonly>
        </div>
        <hr class="my-6">
        <div class="question-choices">
            <h4 class="font-semibold mb-2">Question Choices</h4>
            <div class="choices-container flex flex-col gap-4">
                <div class="choice flex gap-4 items-center">
                    <input type="checkbox" name="new_is_correct[]" value="1">
                    <p class="font-semibold">A</p>
                    <div class="flex flex-col w-full">
                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_answer_text[]" placeholder="Type answer text here...">
                    </div>
                    <div class="flex flex-col">
                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_answer_image[]">
                    </div>
                </div>
                <div class="choice flex gap-4 items-center">
                    <input type="checkbox" name="new_is_correct[]" value="1">
                    <p class="font-semibold">B</p>
                    <div class="flex flex-col w-full">
                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_answer_text[]" placeholder="Type answer text here...">
                    </div>
                    <div class="flex flex-col">
                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_answer_image[]">
                    </div>
                </div>
            </div>
            <button type="button" class="add-choice-btn px-4 py-2 bg-green-500 text-white rounded-md mt-2">+ Add Choice</button>
        </div>
        <button class="remove_question px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="button">Remove Question</button>
    </div>
    `;

                // Increment the CLO index
                cloIndex++;

                document.getElementById("new_questions").insertAdjacentHTML('beforeend', questionHTML);
            });



            // Update total points dynamically, incrementing by the new question points
            $(document).on("input", ".new-question-points", function() {
                var newQuestionPoints = totalPoints;
                $(".new-question-points").each(function() {
                    newQuestionPoints += parseInt($(this).val() || 0);
                });
                $("#total-points").text(`(${newQuestionPoints} Points)`);
            });

            // Remove question dynamically
            $(document).on("click", ".remove_question", function() {
                $(this).closest(".question").remove();
                totalQuestions--;
                document.getElementById('total-questions').innerText = `(${totalQuestions} Questions)`;

                // Decrement the total points
                var newQuestionPoints = totalPoints;
                $(".new-question-points").each(function() {
                    newQuestionPoints += parseInt($(this).val() || 0);
                });
                $("#total-points").text(`(${newQuestionPoints} Points)`);
            });
        });
    </script>
</body>

</html>