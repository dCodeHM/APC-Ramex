<?php 
session_start();

include("config/db.php");
include("config/functions.php");

$user_data = check_login($conn);

if (isset($_SESSION['status'])) {
echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
<strong>Hey!</strong>' . $_SESSION['status'] . '
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
unset($_SESSION['status']);
}

$id = $_SESSION['account_id'];
$sql = "SELECT * FROM  account WHERE account_id = '$id' LIMIT 1";
$gotResults = mysqli_query($conn, $sql);
if ($gotResults){
if(mysqli_num_rows($gotResults)>0){
while($row = mysqli_fetch_array($gotResults)){
// print_r($row['first_name']);

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
        <link rel="stylesheet" href="./css/settings.css">
        <link rel="stylesheet" href="./css/sidebar.css">
        <link rel="stylesheet" href="./css/adminstyle.css">
        <link rel="stylesheet" href="./css/userset.css">
        <link rel="stylesheet" href="./css/homepage.css">
        <link rel="stylesheet" href="./css/helpbutton.css">
        <link rel="shortcut icon" type="x-icon" href="./img/icon.png">
    </head>

    <body>
        <!--OTHER CODE -->
        <navigation class = "navbar">
                                        
        <ul class="right-header">
        <li class="logo">
            <a href="<?php echo $redirect_url; ?>"><img id="logo" src="img/APC AcademX Logo.png"></a>
        </li>
        </ul>

            <ul class = "left-header">
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
    <div class="back_button" style="padding-left: 5px">
        <a href="<?php echo $redirect_url; ?>">
        <img src="img/back.png">
        </a>
    </div>
    <div class="help_buttonem">
        <img src="img/help.png" alt="Help Icon">
    </div>

</div>
</navigation>

<div class="column">
    <div class="emright">
    <div class="content">
    <div class="righthead">

        <div class="adminicon">
            <img lass="iconadmin" src ="./img/LogoExamMaker.png" min-width="100%">
        </div>

        <div class="userhead">
            <p style="font-size: 50px; padding-bottom: 50px"> Exam Maker</p>
        </div>
        </div>

        <div class="adminline" style="padding-bottom: 50px">
        </div>

    <div class="system-list">

        <div class="embox">
            <a href="AcademicYearExam.php" class="fill-div">
            <img src="./img/MyExams.png">
            </a>
        </div>

        <div class=space></div>
         
        <div class="embox">
            <a href="examlibrary.php" class="fill-div">
            <img src="./img/ExamLibrary.png">
            </a>
        </div>
        
    </div>
    </div>
    </div>
    </div>
    
    <script src="https://kit.fontawesome.com/9e5ba2e3f5.js" crossorigin="anonymous"></script>
    <script src="js/header.js"></script>
    </body>
    <?php
                                }
                            }
                        }
                    
?>
</html>