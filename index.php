<?php
session_start();

include("config/db.php");
include("config/functions.php");

$user_data = check_login($conn); // Check if the user is logged in and was previously an Executive Director
if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'Executive Director') {
    header("Location: login.php"); // Redirect to login page if not logged in or no longer an Executive Director
    exit(); // Ensure script stops executing after redirection
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width" , initial-scale="1.0">
    <meta name="author" content="APC AcademX">
    <title>APC AcademX</title>
    <!-- STYLES FOR WEBSITE -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/homepage.css">
    <link rel="shortcut icon" type="x-icon" href="./img/icon.png">
</head>

<body>
    <!--OTHER CODE -->
    <navigation class="navbar">

        <ul class="left-header">

            <!-- TOBEY'S CODE FOR LOGIN -->
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
            //     echo "<li class='username'><h3>Null</h3></li>";
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
                    <a href="adminusersettings.php" class="settings"><span>Settings</span></a>
                    <a href="logout.php" class="logout"><span>Logout</span></a>
                </ul>
            </li>
        </ul>
    </navigation>

    <div class="content">
        <img src="./img/banner.png" class="showcase">
    </div>

    <div class="system-list">

        <div class="box">
            <a href="sa.html" class="fill-div">
                <img src="./img/sas.png">
            </a>
        </div>

        <div class=space></div>

        <div class="box center">
            <a href="ca.html" class="fill-div">
                <img src="./img/cas.png">
            </a>
        </div>

        <div class=space></div>

        <div class="box">
            <a href="em.php" class="fill-div">
                <img src="./img/ems.png">
            </a>
        </div>

    </div>
    <script src="https://kit.fontawesome.com/9e5ba2e3f5.js" crossorigin="anonymous"></script>
    <script src="js/header.js"></script>
</body>

</html>