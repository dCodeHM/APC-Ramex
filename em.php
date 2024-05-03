<?php
session_start();
require('config/db.php');
if (!isset($_SESSION['account_id'])) {
    // Redirect to the login page if the user is not logged in
    echo '<script>alert("User is not logged in, directing to login page.")</script>';
    echo "<script> window.location.assign('login.php'); </script>";
    exit();
}


$account_id = $_SESSION['account_id'];


// Display the user-specific information
$sql = "SELECT * FROM account WHERE account_id = $account_id";
$result = mysqli_query($conn, $sql); // Replace with data from the database
if ($result) {
    $row = mysqli_fetch_array($result);
    $user_email = $row['user_email'];
    $pwd = $row['pwd'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $role = $row['role'];
}
?>
<!DOCTYPE html>
<link rel="stylesheet" href="./css/adminstyle.css">
<link rel="stylesheet" href="./css/style.css">
<link rel="stylesheet" href="./css/emstyle.css">

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | Exam Maker</title>
        <link rel="stylesheet" href="./css/style.css">
    </head>

    <body>
        
    <?php include('topnavAdmin.php'); ?>

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

            <!--header-->
            <div class="containerem">

                <div class="rightemhead">

                    <div class="adminemicon">
                        <img class="iconemadmin" src ="./img/Exam Maker Icon.png" width="100%">
                    </div>

                    <div class="adminemhead"> 
                        <p> Exam Maker </p>
                    </div>
                </div>

                <!--line-->
                <div class="adminemline">
                </div>     
            

                <!--boxes-->
                <section id="emservices">            
                    <div class="container2">        
                        <div class="embox">
                            <div class="boxem">
                                <a href="myexams.php" class="fill-div">
                                <img src="./img/My Exams.png">
                                <p><span style="font-weight:700;">Customize Your Exams: </span> Efficiently design, store, and organize your multiple-choice exams using the system’s features.</p>
                                </a>
                            </div>

                            <div class="boxem">
                                <a href="#" class="fill-div">
                                <img src="./img/Exam Library.png">
                                <p><span style="font-weight:700;">Browse Exams: </span>Reuse and edit accessible, pre-made exams from created by other professors for your own convenience.</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>




        <script src="" async defer></script>
    </body>
</html>