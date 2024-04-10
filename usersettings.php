<?php 
  include("config/db.php");

  
?>
<?php 
                    if(isset($_SESSION['status']))
                    {
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
                                $sql = "SELECT * FROM  account WHERE account_id = 1";
                                $gotResults = mysqli_query($connection, $sql);
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

            <form action = "usersettingscode.php" method = "POST">
            <div class="userline"  >
                <div class="table" style="overflow: auto;">
                    <div class="tablecontent">
                        <p style="position:relative; left: 15px; top: 5px">
                            <b>
                                School Role
                            </b>
                        </p>
    
                        <div class="adassign" style="position:relative; right: 90px">
                            <p> 
                            <input type="text" name="updateRole" class="form-control" value="<?php echo $row['roles']; ?>">
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
                                <input type="text" name="updateFirstname" class="form-control" value="<?php echo $row['first_name']; ?>">
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
                            <input type="text" name="updateLastname" class="form-control" value="<?php echo $row['last_name']; ?>">
                            </p>                        
                        </div>
                    </div>
    
                    <!-- EMAIL WITH DATABASE -->
                    <div class="tablecontent">
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
                    <div class="tablecontent">
                        <p style="position:relative; left: 15px; top: 5px">
                            <b>Password</b>
                        </p>
                        
                        <div class="adassign">
                                <p style="position:relative; right: 76px"> 
                                <input type="text" name="updatePassword" class="form-control" value="<?php echo $row['pwd']; ?>">
                                <!-- echo $hash = password_hash("pwd", PASSWORD_DEFAULT);  -->
                                </p>           
                        </div>

                        <div class="form-group mb-3">
                                <button type="submit" name="update_stud_data" class="btn btn-primary">Update Data</button>
                            </div>
                    </div>
                </div>
            </div>
            

    </div>

</div>
<!--sheesh
-->
</body>
<?php
                                }
                            }
                        }

?>
</html>