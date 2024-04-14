<?php 
session_start();

include("config/db.php");
include("config/functions.php");

$user_data = check_login($conn);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" type="x-icon" href="./img/icon.png">
        <meta name="viewport" content="width=device-width" , initial-scale="1">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | Welcome</title>
        <link rel="stylesheet" href="./css/style.css">
    </head>

    <body>
        <!--TTHIS IS THE NAVIGATION BAR-->
        <header>
            <div class="container1">
                <div id="branding">
                    <a href="index.php"><img src="./img/APC AcademX Logo.png"></a>
                </div>

                <nav>
                    <ul>
                        <li class="username"><h3>Einstein Yong</h3></li>

                        <li class="notification">
                            <a href="#"><img src="./img/Notification.png"></a>
                            <ul class="dropdown">
                                <img src="./img/Notification Title.png">
                            </ul>
                        </li>

                        <li class="user">
                            <a href="#"><img src="./img/LOGO (2) 1.png"></a>
                            <ul class="dropdown">
                                <h3>ADMIN</h3>
                                <p>PROFESSOR</p>
                                <a href="usersettings.php" class="settings"><li>Settings⚙️</li></a>
                                <a href="test.php" class="logout"><li>Logout[➡</li></a>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <!--THIS IS THE YELLOW-->
        <section id="showcase">
            <div class="container">
                <img src="./img/yellowbg.png" class="showcase">
            </div>
        </section>

        <!-- THIS IS THE 3 BOXES BELOW YELLOW-->
        <section id="services">
            <div class="container2">
                <div class="box">
                    <a href="sa.php" class="fill-div">
                    <img src="./img/StudentAssessment.png">
                    <p>Organize and Record Grades: Encode the grades of students using CLO-based rubrics.</p>
                    </a>
                </div>

                <div class="box">
                    <a href="ca.php" class="fill-div">
                    <img src="./img/CourseAssessment.png">
                    <p>Assess Courses: Assign courses and use the automatic assessment to make informed decisions.</p>
                    </a>
                </div>

                <div class="box">
                    <a href="em.php" class="fill-div">
                    <img src="./img/ExamMaker.png">
                    <p>Create Exams: Easily make multiple-choice exams using the system's question and exam library.</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- <script src="./headerManager.js"></script> -->
    </body>

</html>