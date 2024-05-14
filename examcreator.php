<?php
    session_start();
    include("config/db.php");
    include("config/functions.php");
    
    // if (!isset($_SESSION['account_id'])) {
    //     // Redirect to the login page if the user is not logged in
    //     echo '<script>alert("User is not logged in, directing to login page.")</script>';
    //     echo "<script> window.location.assign('login.php'); </script>";
    //     exit();
    // }

    // $account_id = $_SESSION['account_id'];    

    // // Display the user-specific information
    // $sql = "SELECT * FROM account WHERE account_id = $account_id";
    // $result = mysqli_query($conn, $sql); // Replace with data from the database
    // if ($result) {
    //     $row = mysqli_fetch_array($result);
    //     $user_email = $row['user_email'];
    //     $pwd = $row['pwd'];
    //     $first_name = $row['first_name'];
    //     $last_name = $row['last_name'];
    //     $role = $row['role'];
    // }
    // ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | Welcome</title>
        <link rel="stylesheet" href="./css/examheader.css">
        <link rel="stylesheet" href="./css/examsidebar.css">
        <link rel="stylesheet" href="./css/exammain.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/settings.css">
    </head>

    <body>

    <navigation class="navbar">

    <ul class="right-header">
    <li class="logo">
        <a href="index.php"><img id="logo" src="img/logo.png"></a>
    </li>
    </ul>

    <ul class="left-header">
    <?php
    // // Check if the session variable exists
    // if(isset($_SESSION['user'])) {
    //     // Retrieve data from the session variable
    //     $userData = $_SESSION['user'];
        
    //     // // Access specific data from the session variable
    //     // $username = $userData['username'];
    //     // $email = $userData['email'];
        
    //     // Output the retrieved data in HTML text
    //     echo "<li class='username'><h3>$userData</h3></li>";
    // } else {
    //     // Session variable does not exist or user is not logged in
    //     echo "<li class='username'><h3>$row[first_name] $row[last_name]</h3></li>";
    // }
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
        <ul class="user-drop dropdown" id="user-drop" style="display: none;">
            <h3>Admin</h3>
            <p>School Role</p>
            <a href="userprofile.php" class="settings"><span>Settings</span></a>
            <a href="logout.php" class="logout"><span>Logout</span></a>
        </ul>
    </li>
</ul>

<div class="sidebar">
    <div class="back_button">
        <a href="index.php">
        <img src="img/back.png">
        </a>
    </div>
    <div class="help_button">
        <img src="img/help.png">
    </div>
</div>

<!-- SIDE BAR QUESTION LIBRARY AND EXAM SETTINGS -->
<div class="mid" style="width: 350px;">

        <div class="midnav">
        
                    <div class="question_library">Question Library</div>
        
                    <div class="exam_settings">Exam Settings</div>

                    <div class="topic_questions">

                        <p class="topic_title">V Processor Management</p>

                        <div class="questions" onclick="insertQuestion(event)">

                            <p id="question1">What event occurs when no space is enough for any waiting process, even if partitions are available?</p>

                        </div>

                        <div class="questions" onclick="insertQuestion(event)">

                            <p id="question2">This is the term used for base register under MMU.</p>

                        </div>

                        <div class="questions" onclick="insertQuestion(event)">

                            <p id="question3">What strategy produces the largest leftover hole, which may be more useful than the smallest leftover hole?</p>

                        </div>

                        <div class="questions" onclick="insertQuestion(event)">

                            <p id="question4">This item keeps in memory only those instructions and data that are needed at any given time.</p>

                        </div>

                        <div class="questions" onclick="insertQuestion(event)">

                            <p id="question5">It is the moving of process upwards in the main memory so that the free memory locations may be grouped together in one large block.</p>

                        </div>

                    </div>
                    
                    <div class="topic_questions">

                        <p class="topic_title">> Operating System Fundamentals</p>

                    </div>
        
                </div>
    </div>
</navigation>

        <div class="column">
    
            <div class="left">
        
                <div class="sidenav" id="bar">
                    
                    <div class="back">
                        <a href="topic.php">
                            <img src="./img/Exam Maker (5) 6.png">
                        </a>
                    </div>
                    
                    <div class="help">
                        <a href="#">
                            <img src="./img/Help.png"> 
                        </a>
                    </div>
                
                </div>
            </div>

        
            <!-- Main Exam Body Scrollable -->
            <div id="exambody" style=" margin-left: 300px">

                <div id="main-container">
                    
                    <div class="title">
                        <span class="title_bold">Topic:</span>
                        <span class="topic">Processor Management</span><br>
                    </div>

                    <div class="line"></div>

                    

                <!-- Topic Div -->
                <div class="section1">
                    <div class="section_number">I</div>

                    <div class="instructions">
                        <span>Instructions</span><br>
                        <td><input class="textbox" type="text" name="f1" /></td>

                        <!-- Delete Section Link -->
                        <div class="delete-button-container">
                            <a href="#" class="delete-section" onclick="deleteSection(this)">Delete Section</a>
                        </div>
                    </div>
                </div>

                <script>
                    function deleteSection(link) {
                        // Get the parent div of the link and remove it
                        var sectionDiv = link.closest('.section1');
                        sectionDiv.remove();
                    }
                </script>
                <script>
                    function deleteSection(button) {
                        // Get the parent div of the button and remove it
                        var sectionDiv = button.closest('.section1');
                        sectionDiv.remove();
                    }
                </script>
                    
                    <!-- Question Div -->
                    <div class="question-container">

                    <div class="question_number">1</div>

<div class="question">
    <td><input id="textbox" type="text" name="f1" /></td>

<!-- Answer Options Form -->
<form class="answer-options1" id="answerOptionsForm">
    <div class="answer">
        <input type="checkbox" id="answer1" name="answer1" value="1">
        <input class="textbox" type="text" name="f1" />
    </div>

    <div class="answer">
        <input type="checkbox" id="answer2" name="answer2" value="2">
        <input class="textbox" type="text" name="f2" />
    </div>

    <!-- Add Answer Link -->
    <a href="#" class="add-answer" onclick="addAnswer(event)">Add Answer</a>
</form>

<script>
    function addAnswer(event) {
        event.preventDefault();

        // Get the form element
        var form = document.getElementById('answerOptionsForm');

        // Get the number of current answers to create unique IDs and names
        var answerCount = form.getElementsByClassName('answer').length;

        // Create a new div element for the new answer
        var newAnswerDiv = document.createElement('div');
        newAnswerDiv.className = 'answer';

        // Create the checkbox input
        var newCheckbox = document.createElement('input');
        newCheckbox.type = 'checkbox';
        newCheckbox.id = 'answer' + (answerCount + 1);
        newCheckbox.name = 'answer' + (answerCount + 1);
        newCheckbox.value = answerCount + 1;

        // Create the textbox input
        var newTextbox = document.createElement('input');
        newTextbox.type = 'text';
        newTextbox.className = 'textbox';
        newTextbox.name = 'f' + (answerCount + 1);

        // Append the new inputs to the new answer div
        newAnswerDiv.appendChild(newCheckbox);
        newAnswerDiv.appendChild(newTextbox);

        // Append the new answer div to the form
        form.insertBefore(newAnswerDiv, form.getElementsByClassName('add-answer')[0]);
    }
</script>

<style>
    .add-answer {
        display: inline-block;
        padding: 8px 16px;
        margin-top: 10px;
        background-color: #4CAF50;
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .add-answer:hover {
        background-color: #45a049;
    }
</style>


    <!-- question points -->
    <div class="question_points">
        <form action="/action_page.php">
            <label for="points">Question Points:</label>
            <input type="number" id="points" name="points" min="0" step="1" placeholder="Enter points">
        </form>
    </div>

    <!-- SHOULD BE CONNECTED TO COURSE LEARNING OUTCOMES -->
    <div class="learning_outcomes">
        <form action="/action_page.php">
            <label for="clo">Learning Outcomes:</label>
            <select id="clo" name="clo">
                <option value="1">CLO 1</option>
                <option value="2">CLO 2</option>
                <option value="3">CLO 3</option>
                <option value="4">CLO 4</option>
            </select>
        </form>
    </div>

    <div class="difficulty">
        <form action="action_page.php" method="POST">
            <label for="difficulty">Difficulty:</label>
            <select id="difficulty" name="difficulty" required>
                <option value="" disabled selected>Select difficulty</option>
                <option value="easy">EASY</option>
                <option value="average">AVERAGE</option>
                <option value="difficult">DIFFICULT</option>
            </select>
        </form>
    </div>

    <!-- Delete Question Button -->
    <button class="delete-question" onclick="deleteQuestion(this)">Delete Question</button>
</div>

<script>
    function deleteQuestion(button) {
        // Get the parent div of the button and remove it
        var questionDiv = button.closest('.question');
        questionDiv.remove();
    }
</script>

<!-- Adding sections and questions -->
<div class="buttons">
    <button id="section" onclick="addSection()">+ ADD SECTION</button>
    <button id="question" onclick="addQuestion()">+ ADD QUESTION</button>
</div>
                
            </div>

        </div>

        <script src="./exammaker.js"></script>

    </body>

</html>