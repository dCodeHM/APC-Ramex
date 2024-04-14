<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirect to the login page
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/settings.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="shortcut icon" href="img/icon.png" type="image/x-icon">
    <title>APC AcademX - Settings</title>
        
</head>

<body>


<navigation class="navbar">

<ul class="right-header">
    <li class="logo">
        <a href="homepage.php"><img id="logo" src="img/logo.png"></a>
    </li>
    <li class="sas">
        <a href="student_assessment.php"onmouseover="document.getElementById('sas').src = 'img/sas_logo.png'" onmouseout="document.getElementById('sas').src = 'img/sas_logo_white.png'"><img id="sas" src="img/sas_logo_white.png"></a>
    </li>
    <li class="cas">
        <a href="course_assessment.php"onmouseover="document.getElementById('cas').src = 'img/cas_logo.png'" onmouseout="document.getElementById('cas').src = 'img/cas_logo_white.png'"><img id="cas" src="img/cas_logo_white.png"></a>
    </li>
    <li class="ems">
        <a href="exam_maker.php"onmouseover="document.getElementById('ems').src = 'img/ems_logo.png'" onmouseout="document.getElementById('ems').src = 'img/ems_logo_white.png'"><img id="ems" src="img/ems_logo_white.png"></a>
    </li>
</ul>

<ul class="left-header">
<?php
    // Check if the session variable exists
    if(isset($_SESSION['user'])) {
        // Retrieve data from the session variable
        $userData = $_SESSION['user'];
        
        // // Access specific data from the session variable
        // $username = $userData['username'];
        // $email = $userData['email'];
        
        // Output the retrieved data in HTML text
        echo "<li class='username'><h3>$userData</h3></li>";
    } else {
        // Session variable does not exist or user is not logged in
        echo "<li class='username'><h3>Null</h3></li>";
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
        <img src="img/back.png">
    </div>
    <div class="help_button">
        <img src="img/help.png">
    </div>
</div>

<div class="mid">

        <div class="midnav">

            <div class="midhead">
                <p> Settings </p>
            </div>

            <div class="line">
            </div>

            <div class="buttonmid">
                <a href="usersettings.html" class="midbutton active">
                    <p> User Profile </p>
                </a>
            </div>

            <div class="buttonmid">
                <a href="adminset.html" class="midbutton">
                    <p> Admin Settings </p>
                </a>
            </div>

            <div class="buttonmid">
                <a href="programlist.php" class="midbutton">
                    <p> Program List </p>
                </a>
            </div>

        </div>

    </div>











    <script src="https://kit.fontawesome.com/9e5ba2e3f5.js" crossorigin="anonymous"></script>
    <script src="js/header.js"></script>
</body>
</html>