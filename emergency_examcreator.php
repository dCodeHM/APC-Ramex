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

// Fetch the course_subject_id using the course_code from the URL EWAN KO RIN 
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


function resizeImage($imageData, $maxWidth, $maxHeight) {
    $image = imagecreatefromstring($imageData);
    if ($image === false) {
        return false;
    }

    $width = imagesx($image);
    $height = imagesy($image);

    // Calculate new dimensions
    if ($width > $height) {
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = ($height / $width) * $maxWidth;
        } else {
            return $imageData; // No resize needed
        }
    } else {
        if ($height > $maxHeight) {
            $newHeight = $maxHeight;
            $newWidth = ($width / $height) * $maxHeight;
        } else {
            return $imageData; // No resize needed
        }
    }

    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    ob_start();
    imagejpeg($newImage, null, 100); // Use 100 for maximum quality
    $resizedImageData = ob_get_contents();
    ob_end_clean();

    imagedestroy($image);
    imagedestroy($newImage);

    return $resizedImageData;
}
// For question images
if (!empty($_FILES['question_image']['name'][0])) {
    $uploadedFile = $_FILES['question_image']['tmp_name'][0];
    $imageData = file_get_contents($uploadedFile);
    $resizedImageData = resizeImage($imageData, 800, 600);
    if ($resizedImageData !== false) {
        // Save $resizedImageData to your database
        // $stmt = $pdo->prepare("UPDATE questions SET question_image = ? WHERE id = ?");
        // $stmt->execute([$resizedImageData, $questionId]);
    }
}

// For answer images
if (!empty($_FILES['answer_image']['name'][0])) {
    foreach ($_FILES['answer_image']['tmp_name'] as $key => $tmp_name) {
        if (!empty($tmp_name)) {
            $imageData = file_get_contents($tmp_name);
            $resizedImageData = resizeImage($imageData, 400, 300);
            if ($resizedImageData !== false) {
                // Save $resizedImageData to your database
                // $stmt = $pdo->prepare("UPDATE answers SET answer_image = ? WHERE id = ?");
                // $stmt->execute([$resizedImageData, $answerId]);
            }
        }
    }
}

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
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
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
                <a href="myexams.php">
                    <img src="img/back.png">
                </a>
            </div>
            <div class="help_buttonexam">
                <img src="img/help.png">
            </div>
        </div>
    </nav>

    <!-- Question Library -->
    <div class="main_container pb-36 max-h-xs">
        <div class="buttons">
            <button id="btn_diva" class="button active" type="button">
                <img src="./img/book.png" alt="Icon"> Question Library
            </button>
            <button id="btn_divb" class="button" type="button">
                <img src="./img/examsettings.png" alt="Icon"> Exam Settings
            </button>
        </div>

<!-- Question Library Section -->
<div id="question-library" class="p-6 overflow-y-auto h-[calc(100vh-110px)] bg-gray-100 rounded-lg shadow-inner">
    <!-- Don't display add 5 questions if there are less than 5 questions -->
    <?php if (count($related_questions) >= 5) : ?>
        <button id="add_5_questions" class="sticky top-0 z-10 w-full px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-2xl font-medium rounded-lg text-white mb-4 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg" type="button">Add 5 Questions</button>
    <?php endif; ?>

    <?php if (empty($related_questions)) : ?>
        <p class="text-gray-700 text-2xl">No related questions found.</p>
    <?php else : ?>
        <div class="space-y-4">
            <?php foreach ($related_questions as $question) : ?>
                <div class="question-item bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out cursor-pointer" data-id="<?php echo $question['details']['question_id']; ?>" onclick="duplicateQuestion(<?php echo $question['details']['question_id']; ?>)">
                <div class="flex flex-col lg:flex-row justify-between items-start">
                <div class="space-y-4 flex-grow w-full lg:w-3/4 mb-4 lg:mb-0">
                            <p class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($question['details']['question_text'] ?? ''); ?></p>
                            <?php if ($question['details']['question_image']) : ?>
                                <?php
                                $imgData = base64_encode($question['details']['question_image']);
                                $src = 'data:image/jpeg;base64,' . $imgData;
                                ?>
                                <img src="<?php echo $src; ?>" alt="Question Image" class="max-w-full h-auto object-contain rounded-md" style="max-height: 200px;">
                            <?php endif; ?>
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                <?php foreach ($question['choices'] as $choice) : ?>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xl text-gray-700"><?php echo htmlspecialchars($choice['letter'] ?? '') . '. ' . htmlspecialchars($choice['answer_text'] ?? ''); ?></span>
                                        <?php if ($choice['answer_image']) : ?>
                                            <?php
                                            $choiceImgData = base64_encode($choice['answer_image']);
                                            $choiceSrc = 'data:image/jpeg;base64,' . $choiceImgData;
                                            ?>
                                            <img src="<?php echo $choiceSrc; ?>" alt="Choice Image" class="max-w-full h-auto object-contain rounded-md" style="max-height: 100px;">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="flex flex-col items-end space-y-2 lg:ml-4 w-full lg:w-1/4">
                        <div class="flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 w-full justify-center">
                        <?php
                                switch ($question['details']['difficulty']) {
                                    case 'E':
                                        echo '<span class="text-green-500 font-medium text-xl">Easy</span>';
                                        break;
                                    case 'N':
                                        echo '<span class="text-yellow-500 font-medium text-xl">Normal</span>';
                                        break;
                                    case 'H':
                                        echo '<span class="text-red-500 font-medium text-xl">Hard</span>';
                                        break;
                                    default:
                                        echo '<span class="text-gray-500 font-medium text-xl">Unknown</span>';
                                }
                                ?>
                            </div>
                            <div class="bg-gray-100 rounded-full px-4 py-2 w-full text-center">
                                <p class="font-semibold text-xl text-gray-700"><?php echo htmlspecialchars($question['details']['question_points'] ?? ''); ?> pts.</p>
                            </div>
                            <div class="bg-gray-100 rounded-full px-4 py-2 w-full text-center">
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
                                $cloNumbersString = str_replace('CLO', '', $cloNumbersString);
                                ?>
                                <p class="font-semibold text-xl text-gray-700">CLO: <?php echo $cloNumbersString; ?></p>
                            </div>
                            <!-- Plus Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 hover:text-blue-600">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="16" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                            </svg>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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
        <div id="exam-settings" class="text-2xl flex flex-col gap-4 p-6 " style="display: none;">
            <input type="hidden" id="exam_id" value="<?php echo $exam_id; ?>">
            <!-- Text area for Exam Instruction -->
            <div class="w-full flex flex-col gap-4 mb-4 flex-grow">
            <div class="flex items-center space-x-4">
                    <label class="font-semibold mb-4 text-4xl text-white-800 drop-shadow-lg text-white" for="exam_instruction">Exam Rules:</label>
                    <label class="font-medium mb-4 text-xl text-green-400 drop-shadow-lg" for="exam_instruction_description">(Input rule/s for this exam.)</label>
                </div>
                    <textarea class="p-6 w-full h-[calc(100vh-400px)] min-h-[400px] font-medium text-black-800 text-2xl rounded-xl resize-none border border-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-500" id="exam_instruction" name="exam_instruction"><?php echo htmlspecialchars($exam['exam_instruction']);?></textarea>

            </div>

            <div class="flex w-full items-center gap-2 mb-2">
                <!-- <button class="w-full bg-white text-zinc-800 font-medium py-4 rounded-xl flex items-center justify-center" type="button">Preview</button> -->
                <button id="download-exam-btn" class="w-full bg-[#F3C44C] hover:bg-[#F5D78B] py-4 rounded-xl flex font-medium items-center justify-center transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg" type="button">Download</button>
            </div>
            <button id="save-progress-btn" class="hover:bg-[#F5D78B] transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg mb-2 w-full bg-[#F3C44C] py-4 rounded-xl flex font-medium items-center justify-center" type="button">Save Progress</button>

            <script>
                // If save-progress-btn is clicked, click the Save Exam button
                document.getElementById("save-progress-btn").addEventListener("click", function() {
                    document.getElementById("save-exam-btn").click();
                });
            </script>
            <button id="upload-to-exam-library-btn" class="hover:bg-[#F5D78B] transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg mb-2 w-full bg-[#F3C44C] py-4 rounded-xl flex font-medium items-center justify-center" type="button">Upload to Exam Library</button>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById("upload-to-exam-library-btn").addEventListener("click", function() {
                        var exam_id = <?php echo $exam_id; ?>;

                        fetch("http://localhost:8000/api/exam/upload-to-exam-library.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: "exam_id=" + exam_id
                            })
                            .then(function(response) {
                                if (response.ok) {
                                    return response.text();
                                } else {
                                    throw new Error("An error occurred while uploading the exam to the library.");
                                }
                            })
                            .then(function(text) {
                                alert(text);
                                // Optionally, you can reload the page or update the UI as needed
                            })
                            .catch(function(error) {
                                console.error(error);
                                alert(error.message);
                            });
                    });
                });
            </script>

        </div>
    </div>

    <!-- Main Exam Creator -->
    <main class="ml-[400px] mt-[70px] px-20 py-10">
        <form id="exam-form" class="w-full" method="POST" enctype="multipart/form-data">

        <!-- // In examcreator.php -->
        <div class="mb-8">
    <h2 class="font-semibold mb-4 text-4xl text-blue-800 drop-shadow-lg">Exam Information:</h2>
    
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Exam Details and Rules Column -->
        <div class="flex-grow md:w-3/4">
            <!-- Exam Details -->
            <div class="mb-6">
                <h3 class="font-semibold mb-2 text-3xl text-blue-800 drop-shadow-lg">Exam Details:</h3>
                <p class="font-medium w-full py-3 px-6 rounded-lg text-2xl bg-white shadow-lg border-l-4 border-blue-500 transition duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl">
                    <?php echo htmlspecialchars($exam['exam_name']); ?>
                </p>
            </div>

<!-- Exam Rules -->
<div>
    <h3 class="font-semibold mb-2 text-3xl text-blue-800 drop-shadow-lg">Exam Rules:</h3>
    <p class="font-medium w-full py-3 px-6 rounded-lg text-2xl bg-white shadow-lg border-l-4 border-blue-500 transition duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl">
        <?php $examInstruction = $exam['exam_instruction'] ?? '';
        // Trim the entire string, then remove leading spaces or tabs from each line
        $formattedInstruction = preg_replace('/^[ \t]+/m', '', trim($examInstruction));
        echo htmlspecialchars($formattedInstruction);?>
    </p>
</div>
        </div>

        <!-- Exam QR Code -->
        <div class="flex-shrink-0 md:w-1/8 flex flex-col items-center">
            <h3 class="font-bold mb-2 text-xl text-blue-800 drop-shadow-lg">Exam QR Code:</h3>
            <?php
            if (!empty($exam['qr_code']) && file_exists($exam['qr_code'])) {
                $qrCodePath = htmlspecialchars($exam['qr_code']);
                echo "<div class='flex justify-center items-center bg-white p-4 rounded-lg shadow-lg'>";
                echo "<img src='$qrCodePath' alt='Exam QR Code' class='w-32 h-32 object-contain'>";
                echo "</div>";
            } else {
                echo '<p class="text-red-500">QR Code not available</p>';
            }
            ?>
        </div>
    </div>
</div>
            <!-- Divider -->
            <hr class="mb-4 border-2 border-gray-400 rounded-lg">

            <div class="flex items-center mb-4 gap-6">
                <h3 class="font-semibold text-3xl text-black drop-shadow-lg flex items-center">
                    Total Questions
                    <span class="text-xl font-medium text-green-400 ml-2" id="total-questions"></span>
                </h3>
                <h3 class="font-semibold text-3xl text-black drop-shadow-lg flex items-center">
                    Total Points
                    <span class="text-xl font-medium text-green-400 ml-2" id="total-points"></span>
                </h3>
            </div>
            
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

                                                                <!-- hide or unhide to check the question id if its working from the database: please refer it -->
                                <!-- <div class="flex flex-row"> 
                                    <label class="mb-2" for="question_id">Question ID</label>
                                    <input class="bg-white font-medium text-xl py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="question_id[]" value="<?php echo htmlspecialchars($question['question_id']); ?>" readonly>
                                </div> -->
                        <div class="existing-question bg-blue-100/40 shadow-xl p-6 gap-4 outline-zinc-300 rounded-md outline outline-1 flex flex-col relative <?php if ($question['in_question_library'] == 0) : ?>cursor-not-allowed<?php endif; ?>" data-question-id="<?php echo $question['question_id']; ?>">
            <div class="ml-auto relative">
                <svg class="trash-icon cursor-pointer" data-question-id="<?php echo $question['question_id']; ?>" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                    <path d="M3 6h18" />
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                    <line x1="10" x2="10" y1="11" y2="17" />
                    <line x1="14" x2="14" y1="11" y2="17" />
                </svg>
                
                <!-- Tooltip -->
                <span class="absolute hidden bg-gray-700 text-white text-xl font-semibold px-2 py-1 rounded-lg -top-8 left-1/2 transform -translate-x-1/2 trash-tooltip">
                    Delete
                </span>
            </div>

            <script>
                // JavaScript to handle hover effect
                document.querySelector('.trash-icon').addEventListener('mouseenter', function() {
                    this.nextElementSibling.classList.remove('hidden');
                });

                document.querySelector('.trash-icon').addEventListener('mouseleave', function() {
                    this.nextElementSibling.classList.add('hidden');
                });
            </script>
                            <div class="flex flex-col">
                                <label class="mb-2 text-2xl font-bold" for="question_text">Question Text
                                    <?php if (empty($question['question_text'])) : ?>
                                        <span class="text-red-400 font-bold font-medium text-xl">No Question Text*</span>
                                    <?php endif; ?>
                                </label>
                                <textarea class="bg-white font-medium text-xl py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="question_text[]" <?php if ($question['in_question_library'] == 0) : ?>readonly<?php endif; ?>><?php echo htmlspecialchars($question['question_text']); ?></textarea>
                            </div>

                            <div class="flex flex-col space-y-2">
            <label class="mb-2 text-2xl font-bold" for="question_image">Question Image</label>
            <!-- <input 
                class="bg-white py-2 font-medium text-xl px-4 rounded-lg outline outline-1 outline-zinc-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                type="file" 
                name="question_image[]" 
                accept=".jpeg,.jpg,.png"
                onchange="validateAndPreviewImage(this, 'imagePreview<?php echo $question['question_id']; ?>')"
                <?php if ($question['in_question_library'] == 0) : ?>disabled<?php endif; ?>
            > -->
            <!-- <p class="text-sm text-gray-500">Only JPEG or PNG files, max 10MB. Image will be resized to 800x600 pixels.</p> -->

            <div id="imagePreview<?php echo $question['question_id']; ?>" class="mt-2">
                <?php if (!empty($question['question_image'])) : ?>
                    <?php
                    $imgData = base64_encode($question['question_image']);
                    $src = 'data:image/jpeg;base64,' . $imgData;
                    ?>
                    <img src="<?php echo $src; ?>" alt="Question Image" class="max-w-[200px] max-h-[200px] object-contain rounded-lg">
                <?php endif; ?>
            </div>
        </div>

        <script>
        function validateAndPreviewImage(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            const maxSize = 10 * 1024 * 1024; // 10MB

            // Clear previous preview
            preview.innerHTML = '';

            if (file) {
                // Check file type
                if (!['image/jpeg', 'image/png'].includes(file.type)) {
                    alert('Please select a JPEG or PNG image.');
                    input.value = '';
                    return;
                }

                // Check file size
                if (file.size > maxSize) {
                    alert('File size exceeds 10MB. Please choose a smaller image.');
                    input.value = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Question Image';
                    img.className = 'max-w-[200px] max-h-[200px] object-contain rounded-lg';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
        </script>

        <?php
        // Place this PHP code in your form processing script

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_FILES['question_image']['name'][0])) {
            $uploadedFile = $_FILES['question_image']['tmp_name'][0];
            $sourceImage = imagecreatefromstring(file_get_contents($uploadedFile));
            $sourceWidth = imagesx($sourceImage);
            $sourceHeight = imagesy($sourceImage);

            $targetWidth = 800;
            $targetHeight = 600;

            $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

            ob_start();
            imagejpeg($targetImage, null, 90);
            $imageData = ob_get_clean();

            // Here you would typically save $imageData to your database
            // For example:
            // $stmt = $pdo->prepare("UPDATE questions SET question_image = ? WHERE id = ?");
            // $stmt->execute([$imageData, $questionId]);

            imagedestroy($sourceImage);
            imagedestroy($targetImage);
        }
        ?>

                            <div class="flex flex-col">
                                <label class="mb-2 text-2xl font-bold" for="clo_id">Course Learning Outcome (CLO) ID
                                    <?php if (empty($question['clo_id'])) : ?>
                                        <span class="text-red-400 font-medium text-xl">No CLO ID*</span>
                                    <?php endif; ?>
                                </label>
                                <select class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300 font-medium text-xl" name="clo_id[]" multiple <?php if ($question['in_question_library'] == 0) : ?>disabled<?php endif; ?>>
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
                                <label class="mb-2 font-bold text-2xl" for="difficulty">Difficulty
                                    <?php if (empty($question['difficulty'])) : ?>
                                        <span class="text-red-400 font-medium text-xl">No Difficulty*</span>
                                    <?php endif; ?>
                                </label>
                                <select class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300 font-medium text-xl" name="difficulty[]" <?php if ($question['in_question_library'] == 0) : ?>disabled<?php endif; ?>>
                                    >
                                    <option value="E" <?php if ($question['difficulty'] == 'E') echo 'selected'; ?>>Easy</option>
                                    <option value="N" <?php if ($question['difficulty'] == 'N') echo 'selected'; ?>>Normal</option>
                                    <option value="H" <?php if ($question['difficulty'] == 'H') echo 'selected'; ?>>Hard</option>
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label class="mb-2 font-bold text-2xl" for="question_points">Question Points
                                    <?php if (empty($question['question_points'])) : ?>
                                        <span class="text-red-400 font-medium text-xl">No Question Points*</span>
                                    <?php endif; ?>
                                </label>
                                <input class="bg-white py-2 px-4 rounded-lg font-medium text-xl outline outline-1 outline-zinc-300 existing-question-points" type="number" name="question_points[]" value="<?php echo htmlspecialchars($question['question_points']); ?>" <?php if ($question['in_question_library'] == 0) : ?>readonly<?php endif; ?>>
                            </div>

                            <!-- Display question choices -->
                            <h3 class="font-bold text-2xl mt-4">Choices</h3>
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
<div class="choice flex gap-6 items-center">
    <!-- Is Correct -->
    <input type="checkbox" 
           name="is_correct[<?php echo $question['question_id']; ?>][]" 
           value="<?php echo $choice['is_correct']; ?>" 
           <?php if ($choice['is_correct']) echo 'checked'; ?>
           <?php if ($question['in_question_library'] == 0) : ?> disabled<?php endif; ?>
           class="w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

    <!-- Letter -->
    <p class="font-semibold text-2xl"><?php echo $choiceLetter; ?></p>


                                    <!-- Answer Text -->
                                    <div class="flex flex-col w-full">
                                        <input class="bg-white py-2 px-4 font-medium text-xl rounded-lg outline outline-1 outline-zinc-300" type="text" name="answer_text[<?php echo $question['question_id']; ?>][]" value="<?php echo htmlspecialchars($choice['answer_text']); ?>" <?php if ($question['in_question_library'] == 0) : ?> readonly<?php endif; ?>>
                                    </div>

                                    <!-- Image -->
                                    <!-- <div class="flex flex-col">
                                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="answer_image[<?php echo $question['question_id']; ?>][]" <?php if ($question['in_question_library'] == 0) : ?> disabled<?php endif; ?>>

                                    </div> -->

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

                                    <button type="button" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="toggleImage_<?php echo $imageId; ?>()">
                                        Hide Image
                                    </button>

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
                <button class="px-6 py-3 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white text-xl font-bold" type="button" id="add_question">Add Question</button>
                <button id="save-exam-btn" class="px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white hidden" type="submit">Save Exam</button>
            </div>
        </form>
    </main>

    <!-- Use JSPDF to print the .page elements, A4 -->
    <script>
        // Console log something when the download button is clicked
        document.getElementById('download-exam-btn').addEventListener('click', function() {
            // Get currently URL
            const urlParams = new URLSearchParams(window.location.search);

            // Replace 'examcreator' with 'exam-pdf' url and redirect
            window.location.href = `
                http://localhost:8000/exam-pdf.php?${urlParams.toString()}
            `;
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
                <p class="font-semibold text-2xl">${choiceLetter}</p>
                <div class="flex flex-col w-full">
                    <input class="font-medium text-xl bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_answer_text[]" placeholder="Type answer text here...">
                </div>
                <div class="flex flex-col">
                    <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_answer_image[]">
                </div>
                <button type="button" class="remove-choice-btn px-4 py-2 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 transition duration-200 ease-in-out">
    X
</button>

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

                // Validate that each new question has at least one CLO selected
                var allCLOSelected = true;
                newQuestions.each(function() {
                    var cloSelect = $(this).find("select[name^='new_clo_id']");
                    if (cloSelect.val() === null || cloSelect.val().length === 0) {
                        allCLOSelected = false;
                        return false; // Exit the loop
                    }
                });

                if (!allCLOSelected) {
                    alert("Please select at least one CLO for each new question.");
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

                    // If there are ay new question text is not filled out, alert and return
                    var newQuestionText = [];

                    newQuestions.each(function() {
                        var questionText = $(this).find("textarea[name='new_question_text[]']").val();
                        newQuestionText.push(questionText);
                    });

                    if (newQuestionText.includes("")) {
                        alert("Please fill in all question texts for each new question.");
                        return;
                    }

                    // If there are new question points that are not filled out, alert and return
                    var newQuestionPoints = [];

                    newQuestions.each(function() {
                        var questionPoints = $(this).find("input[name='new_question_points[]']").val();
                        newQuestionPoints.push(questionPoints);
                    });

                    if (newQuestionPoints.includes("")) {
                        alert("Please fill in all question points for each new question.");
                        return;
                    }

                    // --

                    try {
                        // -- Post Question Choices --

                        // Create a new FormData object
                        var formData = new FormData(this);

                        // Append the exam ID to the form data
                        formData.append("exam_id", <?php echo $exam_id; ?>);

                        // Loop through each new question
                        newQuestions.each(function(questionIndex) {
                            var questionElement = $(this);

                            // Get the new_is_correct values for the current question
                            var newIsCorrect = [];
                            questionElement.find("input[name='new_is_correct[]']").each(function() {
                                newIsCorrect.push($(this).is(":checked") ? 1 : 0);
                            });
                            formData.append(`new_is_correct[${questionIndex}]`, JSON.stringify(newIsCorrect));

                            // Get the new_answer_text values for the current question
                            var newAnswerText = [];
                            questionElement.find("input[name='new_answer_text[]']").each(function() {
                                newAnswerText.push($(this).val());
                            });
                            formData.append(`new_answer_text[${questionIndex}]`, JSON.stringify(newAnswerText));

                            // Append the new_answer_image files to the FormData object
                            questionElement.find("input[name='new_answer_image[]']").each(function(choiceIndex) {
                                var file = $(this)[0].files[0];
                                if (file) {
                                    formData.append(`new_answer_image[${questionIndex}][${choiceIndex}]`, file);
                                }
                            });
                        });

                        // Send the form data using the Fetch API
                        const response = await fetch("http://localhost:8000/api/question-choices/post-question-choices.php", {
                            method: "POST",
                            body: formData
                        });

                        if (!response.ok) {
                            console.error("Error posting question choices");
                        }

                        const data = await response.json();
                        console.log(data.message);

                        // reload the page
                        location.reload();

                        
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

                        // Check if the exam instruction is null or an empty string
                        if (examInstruction === null || examInstruction === '') {
                            // Set the exam instruction to an empty string or a default value
                            examInstruction = '';
                        }

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
        var questionId = questionElement.data("question-id");
        console.log("Processing question ID:", questionId);

        var questionChoices = [];
        var newChoices = [];

        questionElement.find(".choice").each(function(index) {
            var choiceElement = $(this);
            var isCorrectValue = choiceElement.find("input[type='checkbox']").is(":checked") ? 1 : 0;
            var answerTextValue = choiceElement.find("input[type='text']").val();
            var answerImageInput = choiceElement.find("input[type='file']")[0];
            var questionChoicesId = choiceElement.find("input[name^='question_choices_id']").val();

            if (questionChoicesId) {
                // Existing choice
                questionChoices.push({
                    is_correct: isCorrectValue,
                    answer_text: answerTextValue,
                    question_choices_id: questionChoicesId
                });

                if (answerImageInput && answerImageInput.files[0]) {
                    questionChoices[questionChoices.length - 1].answer_image = answerImageInput.files[0];
                }
            } else {
                // New choice
                newChoices.push({
                    is_correct: isCorrectValue,
                    answer_text: answerTextValue,
                    letter: String.fromCharCode(65 + index) // A, B, C, ...
                });

                if (answerImageInput && answerImageInput.files[0]) {
                    newChoices[newChoices.length - 1].answer_image = answerImageInput.files[0];
                }
            }
        });

        questionData.push({
            question_id: questionId,
            choices: questionChoices,
            new_choices: newChoices
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

        question.new_choices.forEach((choice, choiceIndex) => {
            formData.append(`question_data[${questionIndex}][new_choices][${choiceIndex}][is_correct]`, choice.is_correct);
            formData.append(`question_data[${questionIndex}][new_choices][${choiceIndex}][answer_text]`, choice.answer_text);
            formData.append(`question_data[${questionIndex}][new_choices][${choiceIndex}][letter]`, choice.letter);

            if (choice.answer_image) {
                formData.append(`question_data[${questionIndex}][new_choices][${choiceIndex}][answer_image]`, choice.answer_image);
            }
        });
    });

    console.log('Form Data for Question Choices:', formData);

    try {
        const response = await fetch("http://localhost:8000/api/question-choices/update-existing-question-choices.php", {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            console.log(data.message);
            // Optionally, update the UI to reflect the changes
            // You might want to reload the page or update the question choices dynamically
            location.reload();
        } else {
            console.error("Error updating question choices");
            alert("Error updating question choices. Please try again.");
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred while updating question choices. Please try again.");
    }
}
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

                // This is for the questionHTML below to know the ORDER ID
        //         <div class="flex flex-col">
        //     <label class="mb-2" for="order">Order</label>
        //     <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="number" name="new_order[]" value="${newOrder}" readonly>
        // </div>

        // afer ${questionCounter} below
        // <div class="flex flex-col">
        // <label class="mb-2 text-2xl font-bold" for="new_clo_id">Course Learning Outcome (CLO) ID</label>
        //     <select class="bg-white font-medium text-xl py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="new_clo_id[${cloIndex}][]" multiple>
        //         ${cloOptions}
        //     </select>
        // </div>
                var questionHTML = `
    <div class="new-question bg-zinc-100 mt-6 p-6 gap-4 outline-zinc-300 rounded-md outline outline-1 flex flex-col question">
        <div class="flex flex-col">
            <label class="mb-2 text-2xl font-bold" for="question_text">Question Text</label>
            <textarea class="bg-white py-2 px-4 font-medium text-xl rounded-lg outline outline-1 outline-zinc-300" name="new_question_text[]"></textarea>
        </div>
<div class="flex flex-col space-y-2">
    <label class="mb-2 text-2xl font-bold" for="question_image">Question Image</label>
    <input 
        class="bg-white py-2 font-medium text-xl px-4 rounded-lg outline outline-1 outline-zinc-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
        type="file" 
        name="new_question_image[]"
        accept=".jpeg,.jpg,.png"
        onchange="validateAndPreviewImage(this, 'imagePreview_${questionCounter}', 'fileSizeMessage_${questionCounter}')"
    >
    <p id="fileSizeMessage_${questionCounter}" class="text-sm mt-1 hidden"></p>
    <p class="text-sm text-gray-500">Only JPEG or PNG files, max 10MB. Image will be resized if larger than 800x600 pixels.</p>
    <div id="imagePreview_${questionCounter}" class="mt-2"></div>
</div>



        <div class="flex flex-col">
        <label class="mb-2 text-2xl font-bold" for="new_clo_id">Course Learning Outcome (CLO) ID</label>
            <select class="bg-white font-medium text-xl py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="new_clo_id[${cloIndex}][]" multiple>
                ${cloOptions}
            </select>
        </div>


        
        <div class="flex flex-col">
            <label class="mb-2 font-bold text-2xl" for="difficulty">Difficulty</label>
            <select class="bg-white font-medium text-xl py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="new_difficulty[]">
                <option value="E">Easy</option>
                <option value="N">Normal</option>
                <option value="H">Hard</option>
            </select>
        </div>
        <div class="flex flex-col">
            <label class="mb-2 font-bold text-2xl" for="question_points">Question Points</label>
            <input class="bg-white py-2 font-medium text-xl px-4 rounded-lg outline outline-1 outline-zinc-300 new-question-points" type="number" name="new_question_points[]">
        </div>

        <hr class="mb-4 border-2 border-gray-400 rounded-lg">
        
        <div class="question-choices">
            <h4 class="font-bold mb-2 text-2xl">Choices</h4>
            <div class="choices-container flex flex-col gap-4">
                <div class="choice flex gap-4 items-center">
                    <input type="checkbox" name="new_is_correct[]" value="1">
                    <p class="font-semibold text-2xl">A</p>
                    <div class="flex flex-col w-full">
                        <input class="font-medium text-xl bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_answer_text[]" placeholder="Type answer text here...">
                    </div>
                    <div class="flex flex-col">
                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_answer_image[]">
                    </div>
                </div>
                <div class="choice flex gap-4 items-center">
                    <input type="checkbox" name="new_is_correct[]" value="1">
                    <p class="font-semibold text-2xl">B</p>
                    <div class="flex flex-col w-full">
                        <input class="font-medium text-xl bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_answer_text[]" placeholder="Type answer text here...">
                    </div>
                    <div class="flex flex-col">
                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_answer_image[]">
                    </div>
                </div>
            </div>
            <button type="button" class="add-choice-btn px-4 py-2 bg-[#19da19] hover:bg-[#38dc38]/80 text-white text-xl font-bold rounded-md mt-2">+ Add Choice</button>
        </div>
        <div class = "flex justify-end">
        <button class="remove_question px-6 py-3 bg-[#f04a26] hover:bg-[#ff6643]/80 rounded-md text-white text-xl font-bold" type="button">Remove Question</button>
        </div>
    </div>
    `;

                // Increment the CLO index
                cloIndex++;

                document.getElementById("new_questions").insertAdjacentHTML('beforeend', questionHTML);
            });

            function resizeImage(file, maxWidth, maxHeight) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = function() {
            let width = img.width;
            let height = img.height;

            // Only resize if the image is larger than the max dimensions
            if (width > maxWidth || height > maxHeight) {
                if (width > height) {
                    if (width > maxWidth) {
                        height *= maxWidth / width;
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width *= maxHeight / height;
                        height = maxHeight;
                    }
                }
            }

            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob((blob) => {
                resolve(new File([blob], file.name, {
                    type: file.type,
                    lastModified: Date.now()
                }));
            }, file.type);
        };
        img.onerror = reject;
        img.src = URL.createObjectURL(file);
    });
}

function compressImage(file, quality = 0.6) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = event => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                canvas.toBlob(
                    blob => {
                        if (blob === null) {
                            return reject(new Error('Canvas is empty'));
                        }
                        resolve(new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        }));
                    },
                    'image/jpeg',
                    quality
                );
            };
            img.onerror = error => reject(error);
        };
        reader.onerror = error => reject(error);
    });
}

function resizeAndCompressImage(file, maxWidth, maxHeight, quality = 0.6) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = function() {
            let width = img.width;
            let height = img.height;

            if (width > maxWidth || height > maxHeight) {
                if (width > height) {
                    if (width > maxWidth) {
                        height *= maxWidth / width;
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width *= maxHeight / height;
                        height = maxHeight;
                    }
                }
            }

            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob((blob) => {
                compressImage(new File([blob], file.name, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                }), quality).then(resolve).catch(reject);
            }, 'image/jpeg');
        };
        img.onerror = reject;
        img.src = URL.createObjectURL(file);
    });
}

function validateAndPreviewImage(input, previewId, messageId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    const messageElement = document.getElementById(messageId);
    const maxSize = 10 * 1024 * 1024; // 10MB

    // Clear previous preview and message
    preview.innerHTML = '';
    messageElement.textContent = '';
    messageElement.classList.add('hidden');

    if (file) {
        // Check file type
        if (!['image/jpeg', 'image/png'].includes(file.type)) {
            messageElement.textContent = 'Please select a JPEG or PNG image.';
            messageElement.classList.remove('hidden', 'text-green-500');
            messageElement.classList.add('text-red-500');
            input.value = '';
            return;
        }

        // Check file size
        if (file.size > maxSize) {
            messageElement.textContent = `File size (${(file.size / 1024 / 1024).toFixed(2)} MB) exceeds the 10 MB limit. Please choose a smaller file.`;
            messageElement.classList.remove('hidden', 'text-green-500');
            messageElement.classList.add('text-red-500');
            input.value = ''; // Clear the input
            return;
        }

        // Resize and compress image
        resizeAndCompressImage(file, 800, 600, 0.6).then(processedFile => {
            // Preview processed image
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Question Image';
                img.className = 'max-w-[200px] max-h-[200px] object-contain rounded-lg';
                preview.appendChild(img);

                messageElement.textContent = `Original size: ${(file.size / 1024 / 1024).toFixed(2)} MB, Processed size: ${(processedFile.size / 1024 / 1024).toFixed(2)} MB`;
                messageElement.classList.remove('hidden', 'text-red-500');
                messageElement.classList.add('text-green-500');
            }
            reader.readAsDataURL(processedFile);

            // Update the input's files with the processed file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(processedFile);
            input.files = dataTransfer.files;
        }).catch(error => {
            console.error('Error processing image:', error);
            messageElement.textContent = 'Error processing image. Please try again.';
            messageElement.classList.remove('hidden', 'text-green-500');
            messageElement.classList.add('text-red-500');
            input.value = '';
        });
    }
}



// Ensure you have a counter for unique IDs
let questionCounter = 0;
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