<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | Program List</title>
        <link rel="stylesheet" href="./css/adminstyle.css">

        <link rel="stylesheet" href="css/settings.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <script src="https://kit.fontawesome.com/e85940e9f2.js" crossorigin="anonymous"></script>
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
                <a href="usersettings.php" class="midbutton">
                    <p> User Profile </p>
                </a>
            </div>

            <div class="buttonmid">
                <a href="adminset.php" class="midbutton">
                    <p> Admin Settings </p>
                </a>
            </div>

            <div class="buttonmid">
                <a href="programlist.php" class="midbutton active">
                    <p> Program List </p>
                </a>
            </div>
        </div>
    </div>
</navigation>

<!-- body -->
<div class="column">
    <div class="right">
        
        <div class="container">

            <div class="righthead">

                <div class="adminicon">
                    <img lass="iconadmin" src ="./img/user.png" min-width=100%>
                </div>

                <div class="programlisthead">
                    <p> Program List</p>
                </div>

                <div class="search">
                    <div>
                        <input type="text" class="searchbar">
                    </div>

                    <div class="searchicon">
                    </div>
                </div>
            </div>

            <div class="adminline" >
            </div>
            
            <div class="table"style="overflow: auto;">
                <div class="tablecontent">

                    <div class="adminame">
                        <p>
                            Academic Program
                        </p>
                        <div class="adname">
                            <p> Computer Engineering </p>
                        </div>

                        <div class="ademail">
                            <p> (45 course files) </p>
                        </div>
                    </div>

                    <div class="adremove">
                        <a href="#"> Remove </a>
                    </div>
                </div>

                <div class="tablecontent">

                    <div class="adminame">
                        <p>
                            Academic Program
                        </p>
                        <div class="adname">
                            <p> Electronics Engineering </p>
                        </div>

                        <div class="ademail">
                            <p> (34 course files) </p>
                        </div>
                    </div>

                    <div class="adremove">
                        <a href="#"> Remove </a>
                    </div>
                </div>

                <div class="tablecontent">

                    <div class="adminame">
                        <p>
                            Academic Program
                        </p>
                        <div class="adname">
                            <p> Computer Science </p>
                        </div>

                        <div class="ademail">
                            <p> (54 course files) </p>
                        </div>
                    </div>

                    <div class="adremove">
                        <a href="#"> Remove </a>
                    </div>
                </div>

                <div class="tablecontent">

                    <div class="adminame">
                        <p>
                            Academic Program
                        </p>
                        <div class="adname">
                            <p> Chemistry </p>
                        </div>

                        <div class="ademail">
                            <p> (69 course files) </p>
                        </div>
                    </div>

                    <div class="adremove">
                        <a href="#"> Remove </a>
                    </div>
                </div>

                <div class="tablecontent">

                    <div class="adminame">
                        <p>
                            Academic Program
                        </p>
                        <div class="adname">
                            <p> Civil Engineering </p>
                        </div>

                        <div class="ademail">
                            <p> (7 course files) </p>
                        </div>
                    </div>

                    <div class="adremove">
                        <a href="#"> Remove </a>
                    </div>
                </div>

                <div class="tablecontent">

                    <div class="adminame">
                        <p>
                            Academic Program
                        </p>
                        <div class="adname">
                            <p> Architecture </p>
                        </div>

                        <div class="ademail">
                            <p> (0 course files) </p>
                        </div>
                    </div>

                    <div class="adremove">
                        <a href="#"> Remove </a>
                    </div>
                </div>
                
            </div>

            <div class="info">
                <div class="rolesinfo">
                    <a href="#"><i class="fa-solid fa-circle-info"></i>  Program Information </a>
                </div>
            </div>

        </div>
        
        

    </div>

</div>
    
</body>
</html>