<?php session_start();
include("config/db.php");

// Check if the user is logged in and was previously an Executive Director
if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'Executive Director') {
    header("Location: login.php"); // Redirect to login page if not logged in or no longer an Executive Director
    exit(); // Ensure script stops executing after redirection
}

$id = $_SESSION['account_id'];

$result = mysqli_query($conn, "SELECT * FROM account"); //data get from database
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
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | Admin Settings</title>

        <link rel="stylesheet" href="css/settings.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/adminset.css">
        <link rel="stylesheet" href="css/boxset.css">
        <link rel="stylesheet" href="css/searchdesign.css">
        <!-- for admin Information -->
        <link rel="stylesheet" href="css/adminstyle.css">
        <link rel="shortcut icon" type="x-icon" href="./img/icon.png">



        <script src="https://kit.fontawesome.com/e85940e9f2.js" crossorigin="anonymous"></script>
</head>

<body>
<navigation class="navbar">

    <ul class="right-header">
    <li class="logo">
        <a href="index.php"><img id="logo" src="img/APC AcademX Logo.png"></a>
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
                <a href="adminusersettings.php" class="midbutton">
                    <p> User Profile </p>
                </a>
            </div>

            <div class="buttonmid">
                <a href="adminset.php" class="midbutton active">
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

    <div class="right">
        <div class="container">
            <div class="righthead">
                <div class="adminicon">
                    <img lass="iconadmin" src ="./img/adminsett.png"  min-width="100%"  >
                </div>

                <div class="adminhead">
                    <p> Admin Settings</p>
                </div>

                <!-- THIS IS THE SEARCH BAR -->
                <form action="" method="GET" class="searchicon " style="position:relative; left: auto">
                    <input type = "text" name = "searchbox" class = "form-control" id  = "live_search" autocomplete="on" placeholder = "Search...">
                    <img src="./img/search.png" alt="" class="search-icon">
                </form>
            </div>

            <form id = "form" action = "adminsetcode.php" method = "POST" >
             <div class="adminline">

                        <!-- THIS IS TABLE FOR SEARCHING and TABLE -->
                        <div id = "searchresult"></div> 

                        <div class="info">
                    <div class="rolesinfo">
                        <p>Admin Information:</p>
                        <ul>
                            <li>Manage user accounts</li>
                            <li>Assign roles and programs</li>
                            <li>Monitor system activities</li>
                        </ul>
                    </div>
                    <a href="#"><i class="fa-solid fa-circle-info"></i>Admin Information</a>
                </div>
             </div>
            </form>
        </div>
    </div>
</div>



<script src="https://kit.fontawesome.com/9e5ba2e3f5.js" crossorigin="anonymous"></script>
<script src="js/header.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // Function to fetch all data
        function fetchAllData() {
            $.ajax({
                url: "livesearch.php",
                method: "POST",
                data: { input: "" }, // Sending empty input to fetch all data
                success: function(data){
                    $("#searchresult").html(data);
                    $("#searchresult").css("display", "block");
                }
            });
        }

        // Call fetchAllData function initially to display all data
        fetchAllData();

        // Keyup event for live search
        $("#live_search").keyup(function(){
            var input = $(this).val();
            if(input != ""){
                $.ajax({
                    url: "livesearch.php",
                    method: "POST",
                    data: { input: input },
                    success: function(data){
                        $("#searchresult").html(data);
                        $("#searchresult").css("display", "block");
                    }
                });
            } else {
                $("#searchresult").html(""); // Clear search result if input is empty
                fetchAllData(); // Fetch all data again when input is empty
            }
        });
    });
</script>
</body>
<?php
                            }
                        }
                    }
?>
</html>