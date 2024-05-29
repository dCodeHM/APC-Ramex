<?php
session_start();
include("config/db.php");
include("config/functions.php");

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
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/examsettings.css">
    <link rel="stylesheet" href="./css/helpbutton.css">
    <link rel="shortcut icon" type="x-icon" href="./img/icon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <input type="hidden" name="instruction_id[]" value="<?php echo htmlspecialchars($instruction['instruction_id']); ?>">

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
        <!--OTHER CODE -->
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


    <!-- QUESTION Library -->

    <div class="main_container">
    <div class="buttons">
        <button id="btn_diva" class="button">
            <img src="./img/book.png" alt="Icon"> Question Library
        </button>
        <button id="btn_divb" class="button">
            <img src="./img/examsettings.png" alt="Icon"> Exam Settings
        </button>
    </div>

    <!-- div 1 -->
    <div class="diva" id="diva">
      Content A
    </div>

    <!-- div 2 -->
    <div class="divb" id="divb">
        <div class = "settingsbuttonONE">
            <style>
            body{
                font: 15px/1.5 Arial, Helvetica, sans-serif;
            }
            .examrule {
                width: 100%;
                color: black;
                background-color: white;
                height: 400px;
                padding: 20px; /* Adjusted padding for better spacing */
                margin: 0 auto; /* Center the div if necessary */
                overflow: auto; /* Adds scrollbar if content exceeds the div */
                box-sizing: border-box; /* Includes padding and border in the width and height */
                font: 14.1px/1.5 Arial, Helvetica, sans-serif;
                border-radius: 7px;
                border-radius: 12px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .examrule h1 {
                text-align: center;
                margin-top: 0; /* Removes default top margin */
                padding-bottom: 10px; /* Adds space below the title */
            }

            .examrule p {
                margin: 10px 0; /* Adds vertical spacing between paragraphs */
            }

            .button-container{
                display: flex;
                justify-content: center; /* Centers the buttons horizontally */
                align-items: center; /* Centers the buttons vertically if needed */
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
                <button id="previewBTN" class ="prevBTN">Preview</button>
                <button id="downloadBTN" class ="downBTN">Download</button>
            </div>
            
            <div class = "button-container-lower">
            <button id="savedBTN" class ="savedBTN">Save Progress</button>
            </div>
            <div class = "button-container-lower">
            <button id="uploadBTN" class ="uploadBTN">Upload to Exam Library</button>
            </div>
        </div>
    </div>
  </div>

  <script>
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

    <section class="ml-[400px] mt-[70px] px-20 py-10">
        <form class="w-full" method="POST" action="" enctype="multipart/form-data">
            <h2 class="font-semibold mb-2">Exam Details</h2>
            <!-- <input class="mb-4 outline outline-zinc-600 outline-1 py-2 px-4 rounded-lg" type="text" name="exam_name" value="<?php echo htmlspecialchars($exam['exam_name']); ?>"> -->
            <div class="mb-4 outline outline-zinc-600 outline-1 py-2 px-4 rounded-lg">
                <?php echo htmlspecialchars($exam['exam_name']); ?>
            </div>
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
                        <div class="bg-blue-100/40 shadow-xl p-6 gap-4 outline-zinc-300 rounded-md outline outline-1 flex flex-col relative">
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