<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | Admin Settings</title>
        <link rel="stylesheet" href="./css/adminstyle.css">
        <script src="https://kit.fontawesome.com/e85940e9f2.js" crossorigin="anonymous"></script>
</head>

<body>

<header> 
    <!-- nav -->
    <div class="container1">
    
        <div id="branding">
            <a href="index.php"><img src="./img/APC AcademX Logo.png"></a>
            <a href="sa.php"><img id="saheader" src="./img/Student Assessment Header.png"></a>
            <a href="ca.php"><img id="caheader" src="./img/Course Assessment Header.png"></a>
            <a href="em.php"><img id="emheader" src="./img/Exam Maker Header.png"></a>

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


<!-- body -->
<div class="column">
    
    <div class="left">

        <div class="sidenav" id="bar">
            
            <div class="back">
                <a href="index.php">
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

    <div class="mid">

        <div class="midnav">

            <div class="midhead">
                <p> Settings </p>
            </div>

            <div class="line">
            </div>

            <div>
                <a href="usersettings.php" class="midbutton">
                    <p> User Settings </p>
                </a>
            </div>

            <div>
                <a href="adminset.php" class="midbutton active">
                    <p> Admin Settings </p>
                </a>
            </div>

            <div>
                <a href="programlist.php" class="midbutton">
                    <p> Program List </p>
                </a>
            </div>

        </div>


    </div>
    
    <div class="right">
        
        <div class="container">

            <div class="righthead">

                <div class="adminicon">
                    <img lass="iconadmin" src ="./img/adminsett.png"  min-width="100%"  >
                </div>

                <div class="adminhead">
                    <p> Admin Settings</p>
                </div>

                <div class="searchicon " style="position:relative; left: auto">
                    <input type="text" class="searchbar" >
                </div>
            </div>

            <div class="adminline" style="overflow: auto;">

                <div class="table" style="overflow: auto;">
                    <div class="tablecontent">
                        
                        <div class="adminame">
                            <div class="adname">
                                <p> <?php
                                $conn = mysqli_connect("localhost", "root", "", "ramexdb");
                                $sql = "SELECT * FROM account WHERE account_id = 1"; 
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
    
                        <div class="adrequest">
                            <p> New </p>
                        </div>
    
                        <div class="adassign">
                            <div class="adminassigned" style="position:relative; bottom: 30px">
                                <p> Unassigned </p>   
                            </div>                  
                        </div>
    
                        <div class="dropdown">
                                <button class="dropbtn"><img lass="arrowdown" src ="./img/arrowdown.png"></button>
                                <div class="dropdown-content">
                                  <a href="#">Unassigned</a>
                                  <a href="#">Professor</a>
                                  <a href="#">Program Director (Computer Engineering)</a>
                                  <a href="#">Program Director (Electronics Engineering)</a>
                                  <a href="#">Program Director (Civil Engineering)</a>
                                  <a href="#">Program Director (Architecture)</a>
                                  <a href="#">Executive Director (EX-D)</a>
                                </div>
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

        </div>
    </div>

</div>
    
</body>
</html>