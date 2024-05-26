<?php
session_start();
include("config/db.php");
include("config/functions.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_data = check_login($conn);

if (!isset($_SESSION['account_id'])) {
    // Redirect to the login page if the user is not logged in
    echo '<script>alert("User is not logged in, directing to login page.")</script>';
    echo "<script> window.location.assign('login.php'); </script>";
    exit();
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

// Handle form submission
if (isset($_POST['save_exam'])) {
    // ----------------------------- Exam Details -----------------------------

    // Update exam details
    $exam_name = $_POST['exam_name'];

    // Prepare SQL statement
    $sql = "UPDATE exam SET exam_name = ? WHERE exam_id = ?";

    // Execute SQL statement
    $stmt = $conn->prepare($sql);

    // Check if the SQL statement is valid
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the parameters to the prepared statement
    $stmt->bind_param("si", $exam_name, $exam_id);

    // Execute the prepared statement
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // ----------------------------- Question Handling -----------------------------

    // Update existing questions
    $question_ids = isset($_POST['question_id']) ? $_POST['question_id'] : array();
    $question_texts = isset($_POST['question_text']) ? $_POST['question_text'] : array();
    $clo_ids = isset($_POST['clo_id']) ? $_POST['clo_id'] : array();
    $difficulties = isset($_POST['difficulty']) ? $_POST['difficulty'] : array();
    $question_points = isset($_POST['question_points']) ? $_POST['question_points'] : array();
    $orders = isset($_POST['order']) ? $_POST['order'] : array();

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
    }



    // Insert new questions
    $new_question_texts = $_POST['new_question_text'];
    $new_clo_ids = $_POST['new_clo_id'];
    $new_difficulties = $_POST['new_difficulty'];
    $new_question_points = $_POST['new_question_points'];
    $new_orders = $_POST['new_order'];

    foreach ($new_question_texts as $index => $new_question_text) {
        // Prepare question data
        $question_text = mysqli_real_escape_string($conn, $new_question_text);
        $clo_id = mysqli_real_escape_string($conn, $new_clo_ids[$index]);
        $difficulty = mysqli_real_escape_string($conn, $new_difficulties[$index]);
        $points = intval($new_question_points[$index]);
        $order = intval($new_orders[$index]);

        // Get the highest answer_id from the question_choices table
        $sql = "SELECT MAX(answer_id) AS max_answer_id FROM question_choices";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $answer_id = $row['max_answer_id'] + 1;
        } else {
            $answer_id = 1;
        }

        // Check if question image is uploaded
        $question_image = null;
        if (isset($_FILES['new_question_image']['tmp_name'][$index]) && !empty($_FILES['new_question_image']['tmp_name'][$index])) {
            if ($_FILES['new_question_image']['error'][$index] === UPLOAD_ERR_OK) {
                $question_image = file_get_contents($_FILES['new_question_image']['tmp_name'][$index]);
            } else {
                echo "Error uploading file: " . $_FILES['new_question_image']['error'][$index];
                continue; // Skip to the next iteration if there's an error uploading the image
            }
        }

        // Prepare SQL statement based on the presence of question image
        if ($question_image !== null) {
            $sql = "INSERT INTO question (exam_id, question_text, question_image, clo_id, difficulty, question_points, `order`, answer_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("issssiis", $exam_id, $question_text, $question_image, $clo_id, $difficulty, $points, $order, $answer_id);
        } else {
            $sql = "INSERT INTO question (exam_id, question_text, clo_id, difficulty, question_points, `order`, answer_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("isssiis", $exam_id, $question_text, $clo_id, $difficulty, $points, $order, $answer_id);
        }

        // Execute SQL statement
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }

        // Loop through the choices and insert them into the question_choices table one by one based on the answer_id
        $new_answer_texts = isset($_POST['new_answer_text']) ? $_POST['new_answer_text'] : array();
        $new_is_correct = isset($_POST['new_is_correct']) ? $_POST['new_is_correct'] : array();

        for ($i = 0; $i < count($new_answer_texts); $i++) {
            // Extract each choice data
            $new_answer_text = $new_answer_texts[$i];

            $new_image_content = null;

            if (
                !empty($_FILES['new_answer_image']['tmp_name'][$i])
                && file_exists($_FILES['new_answer_image']['tmp_name'][$i])
            ) {
                $new_image_content = file_get_contents($_FILES['new_answer_image']['tmp_name'][$i]);
            }

            $new_is_correct_value = in_array(chr(65 + $i), $new_is_correct) ? 1 : 0;
            $new_letter = chr(65 + $i); // Get the letter from the form data

            // Prepare SQL statement based on the presence of answer image
            if ($new_image_content !== null) {
                $sql = "INSERT INTO question_choices (answer_text, answer_image, is_correct, answer_id, letter) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Error preparing statement: " . $conn->error);
                }
                $stmt->bind_param("ssiss", $new_answer_text, $new_image_content, $new_is_correct_value, $answer_id, $new_letter);
            } else {
                $sql = "INSERT INTO question_choices (answer_text, is_correct, answer_id, letter) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Error preparing statement: " . $conn->error);
                }
                $stmt->bind_param("siss", $new_answer_text, $new_is_correct_value, $answer_id, $new_letter);
            }

            // Execute SQL statement
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }

            // Close the prepared statement
            $stmt->close();
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
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="APC AcademX">

    <!-- Title -->
    <title>APC AcademX | Welcome</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/examsettings.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/helpbutton.css?v=<?php echo time(); ?>">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <input type="hidden" name="instruction_id[]" value="<?php echo htmlspecialchars($instruction['instruction_id']); ?>">
</head>

<body>
    <!-- Navbar -->
    <navigation class="navbar">
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
            <div class="help_buttonec">
                <img src="img/help.png" alt="Help Icon">
            </div>
        </div>
    </navigation>


    <!-- Question Library -->
    <div class="main_container">
        <div class="buttons">
            <button id="btn_diva" class="button">
                <img src="./img/book.png" alt="Icon"> Question Library
            </button>
            <button id="btn_divb" class="button">
                <img src="./img/examsettings.png" alt="Icon"> Exam Settings
            </button>
        </div>

        <!-- DIV 1 -->
        <div class="diva" id="diva">
            Content A
        </div>

        <<<<<<< HEAD <!-- DIV 2 -->
            <div class="divb" id="divb">
                <div class="settingsbuttonONE">
                    <button id="previewBTN" class="prevBTN">1</button>
                    <button id="downloadBTN" class="downBTN">2</button>
                    <button id="savedButton" class="savedBTN">3</button>
                    <button id="uploadBTN" class="uploadBTN">4</button>
                    =======
                    <!-- div 2 -->
                    <div class="divb" id="divb">
                        <div class="settingsbuttonONE">
                            <style>
                                body {
                                    font: 15px/1.5 Arial, Helvetica, sans-serif;
                                }

                                .examrule {
                                    width: 100%;
                                    color: black;
                                    background-color: white;
                                    height: 400px;
                                    padding: 20px;
                                    /* Adjusted padding for better spacing */
                                    margin: 0 auto;
                                    /* Center the div if necessary */
                                    overflow: auto;
                                    /* Adds scrollbar if content exceeds the div */
                                    box-sizing: border-box;
                                    /* Includes padding and border in the width and height */
                                    font: 14.1px/1.5 Arial, Helvetica, sans-serif;
                                    border-radius: 7px;
                                    border-radius: 12px;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                                }

                                .examrule h1 {
                                    text-align: center;
                                    margin-top: 0;
                                    /* Removes default top margin */
                                    padding-bottom: 10px;
                                    /* Adds space below the title */
                                }

                                .examrule p {
                                    margin: 10px 0;
                                    /* Adds vertical spacing between paragraphs */
                                }

                                .button-container {
                                    display: flex;
                                    justify-content: center;
                                    /* Centers the buttons horizontally */
                                    align-items: center;
                                    /* Centers the buttons vertically if needed */
                                }

                                .prevBTN {
                                    width: 100%;
                                    background-color: #FFFFFF;
                                    border: none;
                                    color: black;
                                    padding: 15px 32px;
                                    text-align: center;
                                    text-decoration: none;
                                    font-size: 16px;
                                    margin: 4px 2px;
                                    transition-duration: 0.4s;
                                    cursor: pointer;
                                    border-radius: 12px;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                                }

                                .downBTN {
                                    width: 100%;
                                    background-color: #F3C44C;
                                    border: none;
                                    color: white;
                                    padding: 15px 32px;
                                    text-align: center;
                                    text-decoration: none;
                                    font-size: 16px;
                                    margin: 4px 2px;
                                    transition-duration: 0.4s;
                                    cursor: pointer;
                                    border-radius: 12px;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                                }

                                .savedBTN {
                                    background-color: #F3C44C;
                                    border: none;
                                    color: white;
                                    padding: 15px 32px;
                                    text-align: center;
                                    width: 100%;
                                    text-decoration: none;
                                    display: inline-block;
                                    font-size: 16px;
                                    margin: 4px 2px;
                                    transition-duration: 0.4s;
                                    cursor: pointer;
                                    border-radius: 12px;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                                }

                                .uploadBTN {
                                    background-color: #F3C44C;
                                    border: none;
                                    color: white;
                                    padding: 15px 32px;
                                    text-align: center;
                                    width: 100%;
                                    text-decoration: none;
                                    display: inline-block;
                                    font-size: 16px;
                                    margin: 4px 2px;
                                    transition-duration: 0.4s;
                                    cursor: pointer;
                                    border-radius: 12px;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                                }

                                button:hover {
                                    opacity: 0.8;
                                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                                }
                            </style>

                            <div class="examrule">
                                <h1><b>Exam Rules</b></h1>
                                <p><b>1.</b> Read, understand, and follow every specified direction carefully.</p>
                                <p><b>2.</b> Avoid using your cellular phone during exam proper.</p>
                                <p><b>3.</b> This exam is CLOSED NOTES.</p>
                                <p><b>4.</b> Shade your answer on the answer sheet.</p>
                                <p><b>5.</b> NO ERASURE. Erasure means wrong.</p>
                                <p><b>6.</b> Strictly NO CHEATING. Anybody caught cheating will receive a FAILING MARK.</p>
                            </div>
                            <div class="button-container">
                                <button id="previewBTN" class="prevBTN">Preview</button>
                                <button id="downloadBTN" class="downBTN">Download</button>
                            </div>

                            <div class="button-container-lower">
                                <button id="savedBTN" class="savedBTN">Save Progress</button>
                            </div>
                            <div class="button-container-lower">
                                <button id="uploadBTN" class="uploadBTN">Upload to Exam Library</button>
                                >>>>>>> test
                            </div>
                        </div>
                    </div>

                    <section class="ml-[400px] mt-[70px] px-20 py-10">
                        <form class="w-full" method="POST" enctype="multipart/form-data">
                            <h2 class="font-semibold mb-2">Exam Details</h2>
                            <<<<<<< HEAD <input class="mb-4 outline w-full outline-zinc-300 outline-1 py-2 px-4 rounded-lg" type="text" name="exam_name" value="<?php echo htmlspecialchars($exam['exam_name']); ?>">
                                =======
                                <!-- <input class="mb-4 outline outline-zinc-600 outline-1 py-2 px-4 rounded-lg" type="text" name="exam_name" value="<?php echo htmlspecialchars($exam['exam_name']); ?>"> -->
                                <div class="mb-4 outline outline-zinc-600 outline-1 py-2 px-4 rounded-lg">
                                    <?php echo htmlspecialchars($exam['exam_name']); ?>
                                </div>
                                >>>>>>> test
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
                                    $instructionOrder = 1;

                                    foreach ($combined_result as $item) {
                                        if ($item['type'] === 'question') {
                                            $question = $item['data'];
                                    ?>
                                            <div class="bg-blue-100/40 shadow-xl p-6 gap-4 outline-zinc-300 rounded-md outline outline-1 flex flex-col relative">
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
                                                    <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300 existing-question-points" type="number" name="question_points[]" value="<?php echo htmlspecialchars($question['question_points']); ?>">
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

                                                while ($choice = $choices_result->fetch_assoc()) {
                                                ?>
                                                    <div class="choice flex gap-4 items-center">
                                                        <div class="flex items-center w-[140px]">
                                                            <input class="mr-2" type="checkbox" name="new_is_correct[]" value="A">
                                                            <label for="is_correct">Correct Answer</label>
                                                        </div>
                                                        <p class="font-semibold">A</p>
                                                        <div class="flex flex-col w-full">
                                                            <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_answer_text[]" placeholder="Type answer text here...">
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_answer_image[]">
                                                        </div>
                                                    </div>

                                                    <div class="choice flex gap-4 items-center">
                                                        <div class="flex items-center w-[140px]">
                                                            <input class="mr-2" type="checkbox" name="new_is_correct[]" value="B">
                                                            <label for="is_correct">Correct Answer</label>
                                                        </div>
                                                        <p class="font-semibold">B</p>
                                                        <div class="flex flex-col w-full">
                                                            <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_answer_text[]" placeholder="Type answer text here...">
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_answer_image[]">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>

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
                                                <input type="hidden" name="instruction_id[]" value="<?php echo htmlspecialchars($instruction['instruction_id']); ?>">
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

                                <div class="mt-4">
                                    <button class="px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="button" id="add_question">Add Question</button>
                                    <button class="px-4 py-2 bg-[#1E3A8A] hover:bg-[#1E3A8A]/80 rounded-md text-white" type="submit" name="save_exam">Save Exam</button>
                                </div>
                        </form>
                    </section>

                    <script>
                        totalQuestions = 0;
                        totalPoints = 0;

                        // ----------------------------- Helper Functions -----------------------------

                        // Function to fetch total points from the API
                        async function fetchTotalPoints() {
                            const urlParams = new URLSearchParams(window.location.search);
                            const courseTopicId = urlParams.get('course_topic_id');

                            var res = await fetch(`http://localhost/ramex/api/exam/get-exam-id-by-course-topic-id.php?course_topic_id=${courseTopicId}`);
                            var data = await res.json();
                            if (data.error) {
                                console.error(data.error);
                                return;
                            }
                            var examId = data;
                            console.log("Exam ID:", examId);

                            res = await fetch(`http://localhost/ramex/api/question/get-total-points-by-exam-id.php?exam_id=${examId}`);
                            data = await res.json();
                            if (data.error) {
                                console.error(data.error);
                                return;
                            }
                            var totalPointsData = data;
                            console.log("Total Points:", totalPointsData);

                            res = await fetch(`http://localhost/ramex/api/question/get-total-questions-by-exam-id.php?exam_id=${examId}`)
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

                        // Read event for click in svg delete-question
                        $(document).on("click", ".trash-icon", async function() {
                            const questionId = $(this).data("question-id");
                            const questionElement = $(this).closest(".question");

                            if (confirm("Are you sure you want to delete this question?")) {
                                try {
                                    const res = await fetch(`http://localhost/ramex/api/question/delete-question-by-question-id.php?question_id=${questionId}`);
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

                        $(document).on("click", ".add-choice-btn", function() {
                            var choicesContainer = $(this).siblings(".choices-container");
                            var choiceCount = choicesContainer.children(".choice").length;

                            if (choiceCount < 5) {
                                var choiceLetter = String.fromCharCode(65 + choiceCount);
                                var choiceHTML = `
            <div class="choice flex gap-4 items-center">
                <div class="flex items-center w-[140px]">
                    <input class="mr-2" type="checkbox" name="new_is_correct[]" value="${choiceCount}">
                    <label for="is_correct">Correct Answer</label>
                </div>
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

                        $(document).on("click", ".remove-choice-btn", function() {
                            $(this).closest(".choice").remove();
                        });

                        $(document).ready(function() {
                            fetchTotalPoints();

                            $("#add_question").click(function() {
                                // Increment the new order
                                totalQuestions++;
                                // Display the total questions
                                document.getElementById('total-questions').innerText = `(${totalQuestions} Questions)`;

                                var newOrder = $("#new_questions .question").length + 1;

                                const html = String.raw;

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
                            <select class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" name="new_clo_id[]">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
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
                    <div class="flex items-center w-[140px]">
                        <input class="mr-2" type="checkbox" name="new_is_correct[]" value="1">
                        <label for="is_correct">Correct Answer</label>
                    </div>
                    <p class="font-semibold">A</p>
                    <div class="flex flex-col w-full">
                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="text" name="new_answer_text[]" placeholder="Type answer text here...">
                    </div>
                    <div class="flex flex-col">
                        <input class="bg-white py-2 px-4 rounded-lg outline outline-1 outline-zinc-300" type="file" name="new_answer_image[]">
                    </div>
                </div>

                <div class="choice flex gap-4 items-center">
                    <div class="flex items-center w-[140px]">
                        <input class="mr-2" type="checkbox" name="new_is_correct[]" value="1">
                        <label for="is_correct">Correct Answer</label>
                    </div>
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

                                // Append to the new_questions div
                                $("#new_questions").append(questionHTML);


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

                        var btn_diva = document.getElementById("btn_diva");
                        var btn_divb = document.getElementById("btn_divb");
                        var diva = document.getElementById("diva");
                        var divb = document.getElementById("divb");

                        function activateButton(activeButton) {
                            // Remove the active class from all buttons
                            document.querySelectorAll('.button').forEach(button => {
                                button.classList.remove('active');
                            });
                            // Add the active class to the clicked button
                            activeButton.classList.add('active');
                        }

                        btn_diva.addEventListener("click", () => {
                            diva.style.display = "flex";
                            divb.style.display = "none";
                            activateButton(btn_diva);
                        });

                        btn_divb.addEventListener("click", () => {
                            diva.style.display = "none";
                            divb.style.display = "flex";
                            activateButton(btn_divb);
                        });

                        // Display DIV A and set button DIV A as active on initial load
                        window.addEventListener('load', () => {
                            diva.style.display = "flex";
                            divb.style.display = "none";
                            activateButton(btn_diva);
                        });
                    </script>
</body>

</html>