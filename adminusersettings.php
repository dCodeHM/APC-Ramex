<?php 
session_start();
include("config/db.php");
include("config/functions.php");

// // Check if the user is not logged in
// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     // Redirect to the login page
//     header("Location: login.php");
//     exit;
// }
if(isset($_SESSION['status']))
{
?>

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>  
    <?php
    unset($_SESSION['status']);
}
?>
<?php                           
        $id = $_SESSION['account_id'];
        $sql = "SELECT * FROM  account WHERE account_id = '$id' LIMIT 1";
        $gotResults = mysqli_query($conn, $sql);

        if ($gotResults){
        if(mysqli_num_rows($gotResults)>0){
            while($row = mysqli_fetch_array($gotResults)){
            // print_r($row['first_name']);
?>
<!DOCTYPE html>
<html >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width" , initial-scale="1">
        <link rel="system icon" type="x-icon" href="./img/icon.png">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | User Settings</title>
        <link rel="stylesheet" href="./css/adminstyle.css">

        <link rel="stylesheet" href="css/userset.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/boxset.css">
        <link rel="stylesheet" href="css/settings.css">
        <script defer src = "./usersettingAction.js"></script>
        
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

<div class="mid">

        <div class="midnav">

            <div class="midhead">
                <p> Settings </p>
            </div>

            <div class="line">
            </div>

            <div class="buttonmid">
                <a href="adminusersettings.php" class="midbutton active">
                    <p> User Profile </p>
                </a>
            </div>

            <div class="buttonmid">
                <a href="adminset.php" class="midbutton">
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
</navigation>

<div class = "column">
    <!--Title-->
    <div class="right">
        
        <div class="container">

            <div class="righthead">

                <div class="adminicon">
                    <img lass="iconadmin" src ="./img/user.png" min-width="100%">
                </div>

                <div class="userhead">
                    <p> User Profile</p>
                </div>
            </div>


            <form id = "form" action = "usersettingscode.php" method = "POST" >
            <div class="adminline">
                <div class="table">
                    <div class="usercontent">
                        <p>
                            
                            <b style="position:relative; left: 15px; top: 5px">
                                School Role
                            </b>
                        </p>
    
                        <div class="adassign">
                                    <p style="position:relative; right: 91px" name="userEmail" class="form-control">
                                    <?php echo $row['role']; ?>
                                    </p>
                                </div>
                        
                                <div class="tooltip" style="position:relative; right: 100px;">
                                <img  lass="information" src ="./img/information.png">
                                <span class="tooltiptext">
                                <img src ="./img/information.png" width="10px">
                                <b>Role information</b>
                                <br>
                                <span><b>1. Unassigned</b> - Has no access
                                    <span><br><b>2. Professor</b> - Has access to the Student Assessment and Exam Maker.
                                            <span><br><b>3. Executive Director (EX-D)</b> - Has access to the Student Assessment, Course Assessment, Exam Maker, and Admin Settings.
                                        </span>
                                    </span>
                                </span>
                                </div>

                            </div>

                            <!-- FIRST NAME WITH DATABASE -->
                            <div class="usercontent">
                                <p>
                                    <b style="position:relative; left: 15px; top: 10px">First Name</b>
                                </p>
                                <div class="useredit">
                                    <p style="position:relative; right: 95px">
                                        <input type="text" id="firstName" name="updateFirstname" class="form-control" value="<?php echo htmlspecialchars($row['first_name']); ?>" oninput="validateInput(this)">
                                        <div class="error"></div>
                                    </p>                        
                                </div>
                            </div>

                            <script>
                                function validateInput(input) {
                                    input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
                                }
                            </script>

                    
                    <!-- LAST NAME WITH DATABASE -->
                    <div class="usercontent">
                            <p style="position:relative; left: 15px; top: 10px">
                            <b>Last Name</b>
                            </p>
    
                            <div class="useredit">
                            <p style="position:relative; right: 93px">
                            <input type="text" 
                            id = "lastName" name="updateLastname" class="form-control" value="<?php echo htmlspecialchars($row['last_name']); ?>" oninput="validateInput(this)">
                            <div class="error"></div>
                            </p>                        
                        </div>
                    </div>
    
                    <!-- EMAIL WITH DATABASE -->
                    <div class="usercontent">
                            <p style="position:relative; left: 15px; top: 5px">
                            <b>Email Address</b>
                            </p>
                            <div class="adassign">
                                    <p style="position:relative; right: 107px" name="userEmail" class="form-control">
                                    <?php echo $row['user_email']; ?>
                                    </p>
                                </div>
                            </div>


                    <!-- PASSWORD WITH DATABASE -->
                    <div class="usercontent">
                        <p style="position:relative; left: 15px; top: 10px">
                            <b>Password</b>
                        </p>
                        
                        <div class="useredit">
                                <p style="position:relative; right:85px">  

                                <input 
                                type="password" name="updatePassword" class="form-control" id = "userInput" value="<?php echo $row['pwd']; ?>">
                                <!-- echo $hash = password_hash("pwd", PASSWORD_DEFAULT);  -->
                                <input id = "passWord" type="checkbox" class="showPW" onclick="myFunction()">show
                                <!-- THIS IS FOR THE FORGOT PASSWORD -->
                                <a href="forgotpassword.php" target="_blank">Change</a>
                                <div class = "error"></div>

                                <!-- JS FOR SHOWING PASSWORD -->
                                <script>
                                    function myFunction() {
                                    var x = document.getElementById("userInput");
                                    if (x.type === "password") {
                                        x.type = "text";
                                    } else {
                                        x.type = "password";
                                    }
                                    }
                                    </script>
                                </p>           
                        </div>

                        
                    </div>  
                </div>
                                <!-- SUBMIT BUTTON -->
                                <div class="form-group mb-3">
                                    <button type="submit" onclick="return confirmUpdate()" name="update_stud_data" class="updatebtn" style="vertical-align:middle">Update</button>
                                </div>
                    </div>
        </form>            
    </div>

<script>
    function confirmUpdate() {
        if (confirm('Do you want to update your profile?')) {
            alert('Your profile has been updated');
            return true; // Proceed with form submission
        } else {
            return false; // Cancel form submission
        }
    }
</script>

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