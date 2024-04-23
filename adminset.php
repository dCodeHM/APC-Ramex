<?php session_start();
include("config/db.php");

$id = $_SESSION['account_id'];

$result = mysqli_query($conn, "SELECT * FROM account"); //data get from database
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | Admin Settings</title>
        <link rel="stylesheet" href="./css/adminstyle.css">

        <link rel="stylesheet" href="css/settings.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/adminset.css">
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

                    <input type = "text" class = "form-control" id  = "live_search" autocomplete="off" placeholder = "Search...">
                </form>
                <div id = "searchresult"></div>
            </div>

            <form id = "form" action = "adminsetcode.php" method = "POST" >
             <div class="adminline" style="overflow: auto;">

                <div class="table" style="overflow: auto;">
                    <div class="tablecontent">
                        <div class="adminame">
                            <div class="adname">
                                <p> <?php
                                $conn = mysqli_connect("localhost", "root", "", "ramexdb");
                                $sql = "SELECT * FROM account WHERE account_id = '$id' LIMIT 1"; 
                                $result = $conn->query($sql);

                                if ($result) {
                                    // output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        
                                        echo " " . $row["last_name"]. " ";
                                        echo " " . $row["first_name"]. " ";            
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?> </p>
                            </div>
    
                            <div class="ademail">
                                <p>
                                    <?php
                                    $result = $conn->query($sql);
                                    if ($result) {
                                    // output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        
                                        echo " " . $row["user_email"]. " ";         
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?> </p>
                            </div>
                        </div>

                        <!-- //BUTTON -->
                        <div class="form-group mb- 3">
                                <button type="submit" onclick="alert('Your profile has been updated')" name="update_admin_data" class="updatebtn" style = "vertical-align:middle">Update</button>
                            </div>

                        <!-- <div class="adrequest">
                            <p> New </p>
                        </div> -->
<!--     
                        <div class="adassign">
                            <div class="adminassigned" style="position:relative; bottom: 30px">
                                <p> Unassigned </p>   
                            </div>                  
                        </div> -->
    
 

                              <!-- ITO YUNG MAAYOS NA TALAGA -->
                              <div class="dropdown">
                                <select name="updateRole" id="lastName">
                                    <?php
                                    $sql = "SELECT role FROM account WHERE account_id = '$id'";
                                    $result = mysqli_query($conn, $sql) or die("Query failed: " . mysqli_error($conn));

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        $currentRole = mysqli_fetch_assoc($result)['role'];  // Get current role
                                        
                                        $sql = "SELECT * FROM role ";
                                        $data = mysqli_query($conn, $sql) or die("Query failed: " . mysqli_error($conn));
                                        
                                        while ($row = mysqli_fetch_assoc($data)) {
                                            $selected = $currentRole == $row["role"] ? "selected" : ""; // Set selected attribute dynamically
                                            echo "<option value='" . $row["role"] . "' $selected>" . $row["role"] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No Role Found</option>";  // Handle no role case
                                    }
                                    ?>
                                </select>
                            </div>
                              
                        <div class="adremove">
                            <?php
                            $sql = "SELECT * FROM account WHERE account_id = 'id'"; 
                            if (isset($_GET['role_request']))
                            {
                                $role_request=$_GET['role_request'];
                                // echo $_GET['role_request'];
                                $delete = mysqli_query($conn, "DELETE role_request FROM users WHERE 'user_id'");
                            }
                            ?>
                            <a href = 'adminset.php?".$result["role_request"]."'>Delete</a>
                            
                        </div>
                    </div>
                </div>
                </div>
            
                <div class="info">
                <div class="rolesinfo">
                    <a href="#"><i class="fa-solid fa-circle-info"></i>  Admin Information </a>
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
</html>