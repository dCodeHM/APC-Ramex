<?php
session_start();
include("config/RAMeXSO.php");
include("config/functions.php");

if (isset($_SESSION['status'])) {
?>

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
    unset($_SESSION['status']);
}
?>
<?php
$id = $_SESSION['account_id'];
$sql = "SELECT * FROM  account WHERE account_id = '$id' LIMIT 1";
$gotResults = mysqli_query($conn_soe, $sql);
if ($gotResults) {
    if (mysqli_num_rows($gotResults) > 0) {
        while ($row = mysqli_fetch_array($gotResults)) {
            // print_r($row['first_name']);
?>
            <!DOCTYPE html>
            <html>
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
                <script defer src="./usersettingAction.js"></script>
                <script defer src="./js/css.js"></script>

            </head>

            <body>
                <!-- Navbar -->
                <navigation class="navbar">
                    <ul class="right-header">
                        <li class="logo">
                            <a href="index.php"><img id="logo" src="img/APC AcademX Logo.png"></a>
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
                            <ul class="user-drop dropdown" id="user-drop" style="display: none;">
                                <h3>Admin</h3>
                                <p>School Role</p>
                                <a href="userprofile.php" class="settings"><span>Settings</span></a>
                                <a href="logout.php" class="logout"><span>Logout</span></a>
                            </ul>
                        </li>
                    </ul>
                </navigation>

                <!-- Main Sidebar -->
                <div class="sidebar">
                    <div class="back_button">
                        <a href="index.php">
                            <img src="img/back.png">
                        </a>
                    </div>
                    <div class="help_button relative">
                    <img src="img/help.png">
                    <div class="help-text absolute hidden bg-white text-zinc-800 text-sm p-6 rounded-md z-50 w-[300px] shadow-xl left-[calc(100%+20px)] bottom-full mb-2">
                        Help information: <i class="fas fa-question-circle"></i>
                        <br><br>
                        1. <span class="underline">User Profile</span> - View and update your personal information
                        <br><br>
                        2. <span class="underline">Admin Settings</span> - Manage system settings and configurations
                        <br><br>
                        3. <span class="underline">Program List</span> - View and manage the list of programs
                    </div>
                </div>

                <script>
                    // Make help text visible on hover with smooth transition
                    const helpText = document.querySelector('.help-text');
                    const helpButton = document.querySelector('.help_button');

                    helpButton.addEventListener('mouseover', () => {
                        helpText.classList.remove('hidden');
                        helpText.classList.add('block');
                    });

                    helpButton.addEventListener('mouseout', () => {
                        helpText.classList.remove('block');
                        helpText.classList.add('hidden');
                    });

                    // Close help text when clicked outside
                    document.addEventListener('click', function(e) {
                        if (!helpButton.contains(e.target)) {
                            helpText.classList.remove('block');
                            helpText.classList.add('hidden');
                        }
                    });
                </script>
                </div>

                <!-- Settings Sidebar -->
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

                <main class="ml-[300px] p-10 mt-[70px]">
                    <div class="flex gap-8 items-center w-full mb-6">
                        <img class="iconadmin" src="./img/user.png" min-width="100%">

                        <h1 class="text-4xl font-medium">User Profile</h1>
                    </div>
                    <div class="w-full bg-zinc-400 h-1 mb-6">
                    </div>

                    <form class="bg-zinc-100 outline outline-1 outline-zinc-200 rounded-xl" id="form" action="usersettingscode.php" method="POST">
                        <div class="flex-col flex p-10 gap-6">

                            <!-- School Role -->
                            <div>
                                <p class="font-medium mb-4">
                                    School Role
                                </p>
                                <div class="flex items-center ">
                                    <p class="py-4 px-8 outline bg-white outline-1 outline-zinc-800 rounded-xl w-[60%]">
                                        <?php echo $row['role']; ?>
                                    </p>
                                    <!-- Icon -->
                                    <i class="fas text-zinc-800 fa-info-circle cursor-pointer ml-8 text-4xl relative">
                                        <div class="tooltip-text outline outline-1 outline-zinc-400 absolute hidden bg-white text-zinc-800 text-sm p-6 rounded-md z-10 w-[300px] shadow-xl mt-3">
                                            Role information: <i class="fas fa-info-circle"></i>
                                            <br><br>
                                            1. <span class="underline">Unassigned</span> - Has no access
                                            <br><br>
                                            2. <span class="underline">Professor</span> - Has access to the Student Assessment and Exam Maker
                                            <br><br>
                                            3. <span class="underline">Executive Director (EX-D)</span> - Has access to the Student Assessment, Course Assessment, Exam Maker, and Admin Settings
                                            <br><br>
                                            4. <span class="underline">Program Director</span> - Has access to the Student Assessment, Course Assessment, Exam Maker, and Admin Settings
                                        </div>
                                    </i>
                                    <script>
                                        // Make tooltip visible on hover with smooth transition
                                        const tooltip = document.querySelector('.tooltip-text');
                                        const infoIcon = document.querySelector('.fa-info-circle');

                                        infoIcon.addEventListener('mouseover', () => {
                                            tooltip.classList.remove('hidden');
                                            tooltip.classList.add('block');
                                        });

                                        infoIcon.addEventListener('mouseout', () => {
                                            tooltip.classList.remove('block');
                                            tooltip.classList.add('hidden');
                                        });

                                        // Close tooltip when clicked outside
                                        document.addEventListener('click', function(e) {
                                            if (!infoIcon.contains(e.target)) {
                                                tooltip.classList.remove('block');
                                                tooltip.classList.add('hidden');
                                            }
                                        });
                                    </script>
                                </div>
                            </div>

                            <!-- JS FOR LIMIT USER INPUTS -->
                            <script>
                                function restrictInputToLetters(event) {
                                    const charCode = event.which || event.keyCode;
                                    const charStr = String.fromCharCode(charCode);
                                    if (!/^[a-zA-Z]+$/.test(charStr)) {
                                        event.preventDefault();
                                    }
                                }
                                
                                document.addEventListener('DOMContentLoaded', function () {
                                    document.getElementById('firstName').addEventListener('keypress', restrictInputToLetters);
                                    document.getElementById('lastName').addEventListener('keypress', restrictInputToLetters);
                                });
                            </script>

                            <!-- First Name w/ Database -->
                            <div>
                                <p class="font-medium mb-4">
                                    First Name
                                </p>
                                <input type="text" id="firstName" name="updateFirstname" value="<?php echo $row['first_name']; ?>" class="py-4 px-8 outline outline-1 w-[60%] outline-zinc-800 rounded-xl">
                            </div>

                            <!-- Last Name w/ Database -->
                            <div>
                                <p class="font-medium mb-4">
                                    Last Name
                                </p>
                                <input type="text" id="lastName" name="updateLastname" value="<?php echo $row['last_name']; ?>" class="py-4 px-8 outline outline-1 outline-zinc-800 rounded-xl w-[60%]">
                            </div>

                            <!-- Email Address w/ Database -->
                            <div>
                                <p class="font-medium mb-4">
                                    Email Address
                                </p>
                                <p name="userEmail" class="py-4 px-8 bg-white outline outline-1 outline-zinc-800 rounded-xl w-[60%]">
                                    <?php echo $row['user_email']; ?>
                                </p>
                            </div>

                            <div>
                                <p class="font-medium mb-4">
                                    Password
                                </p>
                                <div class="flex items-center gap-8">
                                    <div class="py-4 px-8 w-[60%] bg-white outline outline-1 outline-zinc-800 rounded-xl flex gap-4 justify-between">
                                        <input class="bg-white" type="password" name="updatePassword" id="userInput" value="<?php echo $row['user_password']; ?>">
                                        <div class="flex gap-4">
                                            <p class="text-zinc-400 cursor-pointer" id="showHide">Show</p>
                                            <!-- <div class="error"></div> -->
                                        </div>
                                    </div>
                                    <a href="forgotpassword.php" class="hover:underline" target="_blank">Change</a>
                                </div>


                                <!-- JS for showing password -->
                                <script>
                                    const passwordInput = document.getElementById('userInput');
                                    const showHide = document.getElementById('showHide');

                                    showHide.addEventListener('click', () => {
                                        if (passwordInput.type === 'password') {
                                            passwordInput.type = 'text';
                                            showHide.textContent = 'Hide';
                                        } else {
                                            passwordInput.type = 'password';
                                            showHide.textContent = 'Show';
                                        }
                                    });
                                </script>
                            </div>

                        <!-- Submit Button -->
                        <div class="form-group mb-3">
                            <button type="submit" onclick="alert('Your profile has been updated')" name="update_stud_data" class="updatebtn" style="vertical-align:middle">Update</button>
                        </div>

                        <script>
                            function refreshPage() {
                                // Refresh the page after a short delay
                                setTimeout(function() {
                                    location.reload();
                                }, 100);
                                
                                // Refresh the page again after a longer delay
                                setTimeout(function() {
                                    location.reload();
                                }, 200);
                            }
                        </script>
                    </form>
                </main>
                <script src="https://kit.fontawesome.com/9e5ba2e3f5.js" crossorigin="anonymous"></script>
                <script src="js/header.js"></script>
            </body>
<?php
        }
    }
}

?>

            </html>