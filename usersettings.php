<!DOCTYPE html>
<html >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width" , initial-scale="1">
        <link rel="system icon" type="x-icon" href="./img/icon.png">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | User Settings</title>
        <link rel="stylesheet" href="./css/adminstyle.css">
        
</head>

<body>
<header> 
    <!-- nav -->
    <!--done-->
    <div class="container1">
        <div id="branding">
            <a href="index.php"><img src="./img/APC AcademX Logo.png"></a>
            <a href="sa.php"><img id="saheader" src="./img/Student Assessment Header.png"></a>
            <a href="ca.php"><img id="caheader" src="./img/Course Assessment Header.png"></a>
            <a href="em.php"><img id="emheader" src="./img/Exam Maker Header.png"></a>
        </div>

<!--DONE-->
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

<!-- left navigation -->
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

    <!--THE THREE SETTINGS-->
    <div class="mid">

        <div class="midnav">

            <div class="midhead">
                <p> Settings </p>
            </div>

            <div class="line">
            </div>

            <div>
                <a href="usersettings.php" class="midbutton active">
                    <p> User Profile </p>
                </a>
            </div>

            <div>
                <a href="adminset.php" class="midbutton">
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

            <div class="userline">
                <div class="table" style="overflow: auto;">
                    <div class="tablecontent">
                        <p style="position:relative; left: 15px; top: 5px">
                            <b>
                                School Role
                            </b>
                        </p>
    
                        <div class="adassign" style="position:relative; right: 90px">
                            <p> 
                            <?php
                                $conn = mysqli_connect("localhost", "root", "", "ramexdb");
                                $sql = "SELECT role FROM users WHERE account_id = 1"; 
                                $result = $conn->query($sql);

                                if ($result) {
                                    // output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        
                                        echo " " . $row["role"]. " ";      
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </p>     
                                         
                        </div>
                        
                        <!-- dropdown -->
                        <div class="dropdown">
                            <button class="dropbtn"><img lass="arrowdown" src ="./img/arrowdown.png"></button>
                            <div class="dropdown-content">
                              <a href="#">Unassigned</a>
                              <a href="#">Professor</a>
                              <a href="#">Program Director (PD)</a>
                              <a href="#">Executive Director (EX-D)</a>
                            </div>
                          </div>

                          <div class="tooltip">
                                <img lass="information" src ="./img/information.png">
                                <span class="tooltiptext">
                                <img src ="./img/information.png" width="10px">
                                <b>Role information</b>
                                <br>
                                <span><b>1. Unassigned</b> - Has no access
                                    <span><br><b>2. Professor</b> - Has access to the Student Assessment and Exam Maker.
                                        <span><br><b>3. Program Director (PD)</b> - Has access to the Student Assessment, Course Assessment,and Exam Maker.
                                            <span><br><b>4. Executive Director (EX-D)</b> - Has access to the Student Assessment, Course Assessment, Exam Maker, and Admin Settings.
                                        </span>
                                    </span>
                                </span>
                            </div>

                            <div class="adrequest">
                                <p> Request </p>
                            </div>
                    </div>

                    <!-- FIRST NAME WITH DATABASE -->
                    <div class="tablecontent">
                        <p style="position:relative; left: 15px; top: 5px">
                            <b>First Name</b>
                            </p>
                            <div class="adassign">
                                <p style="position:relative; right: 84px"> 
                                <?php
                                $conn = mysqli_connect("localhost", "root", "", "ramexdb");
                                $sql = "SELECT first_name FROM account WHERE account_id = 1"; ;
                                $result = $conn->query($sql);

                                if ($result) {
                                    // output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo " " . $row["first_name"]. " ";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                                </p>                        
                            </div>
                    </div>
                    
                    <!-- LAST NAME WITH DATABASE -->
                    <div class="tablecontent">
                            <p style="position:relative; left: 15px; top: 5px">
                            <b>Last Name</b>
                            </p>
    
                            <div class="adassign">
                            <p style="position:relative; right: 80px">
                            <?php
                            $sql = "SELECT last_name FROM account WHERE account_id = 1"; ;
                                $result = $conn->query($sql);

                                if ($result) {
                                    // output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo " " . $row["last_name"]. " ";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </p>                        
                        </div>
                    </div>
    
                    <!-- EMAIL WITH DATABASE -->
                    <div class="tablecontent">
                            <p style="position:relative; left: 15px; top: 5px">
                            <b>Email Address</b>
                            </p>
    
                            <div class="adassign">
                                    <p style="position:relative; right: 107px"> 
                                    <?php
                            $sql = "SELECT user_email FROM account WHERE account_id = 1"; ;
                                $result = $conn->query($sql);

                                if ($result) {
                                    // output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo " " . $row["user_email"]. " ";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                                    </p>
                                </div>
                            </div>


                    <!-- PASSWORD WITH DATABASE -->
                    <div class="tablecontent">
                        <p style="position:relative; left: 15px; top: 5px">
                            <b>Password</b>
                        </p>
                        
                        <div class="adassign">
                                <p style="position:relative; right: 76px"> 
                                <?php
                                $sql = "SELECT pwd FROM account WHERE account_id = 1"; ;
                                $result = $conn->query($sql);

                                if ($result) {
                                    // output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        
                                        // echo " " . $row["pwd"]. " ";             this there is no hashing
                                        echo $hash = password_hash("pwd", PASSWORD_DEFAULT); 
                                        // . $row["pwd"];   just remove the comment and same line above it
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                                </p>           
                        </div>
                    </div>
                </div>
            </div>
            

    </div>

</div>
<!--sheesh
-->
</body>
</html>