<?php 
session_start();

include("config/RAMeXSO.php");
include ("config/functions.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{ 
  // something was posted
  $user_email = $_POST['username'];
  $pwd = $_POST['password'];
  $first_name = $_POST['Firstname'];
  $last_name = $_POST['Lastname'];

  if(!empty($user_email) && !empty($pwd) && !is_numeric($first_name) && !is_numeric($last_name)){
    // $user_id = random_num(20);

    //SAVE TO DATABASE
    $query = "INSERT INTO account (user_email, pwd, first_name, last_name) VALUES ('$user_email', '$pwd', '$first_name', '$last_name')";
    
    //save to database
    mysqli_query($conn_soe, $query);
    //redirect to login
    header("Location: login.php");
    die;
  }
  else{
    echo "Please enter some valid information!";
  }
}
?>

<!-- <!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="author" content="APC AcademX">
        <title>APC AcademX | Signup</title>
        <link rel="stylesheet" href="./css/teststyle.css">
        <link rel="stylesheet" href="./css/registerdesign.css">
    </head>

    <body>
        
        <a href="index.php"><h1></h1></a>
        
        <script src="" async defer></script>

        <div id = "box">
            <form method = "post">
                <div>Signup</div>
                <input type = "email" name = "username" placeholder = "Email">
                <input type = "password" name = "password" placeholder = "Password">
                <input type = "text" name = "Firstname" placeholder = "Firstname">
                <input type = "text" name = "Lastname" placeholder = "Lastname">
                <input type = "submit" value = "Signup">

                <a href = "login.php">Go back to login</a>
            </form>
        </div>
    </body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/starting.css">
    <link rel="shortcut icon" href="img/icon.png" type="image/x-icon">
    <title>APC AcademX - Sign-Up</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <section class="form signup">
                <form id="signup" action="" method="POST">
                    <div class="form-group">
                        <img src="img/logo.png" alt="logo" >
                    </div>


                    <div class="form-group">
                        <div class="form-control">
                            <label>First Name</label>
                            <input type="text" name="txt-firstName" id="firstName" autocomplete="username"/>
                            <div id="firstNameError" class="error-message"></div>
                        </div>
                        <div class="form-control">
                            <label>Last Name</label>
                            <input type="text" name="txt-lastName" id="lastName" autocomplete="username"/>
                            <div id="lastNameError" class="error-message"></div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="form-control">
                            <label>Email</label>
                            <input type="email" name="txt-emailSignup" id="emailSignup" autocomplete="new-email"/>
                            <div id="emailSignupError" class="error-message"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-control">
                            <label>Repeat Email</label>
                            <input type="email" name="txt-repeatemailSignup" id="repeatemailSignup" autocomplete="new-email"/>
                            <div id="repeatemailSignupError" class="error-message"></div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="form-control">
                            <label>Password</label>
                            <input type="password" name="txt-pass" id="password" autocomplete="new-password"/>
                            <span id="passwordToggle"><i class="fa-regular fa-eye-slash"></i></span>
                            <div id="passwordError" class="error-message"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-control">
                            <label>Repeat Password</label>
                            <input type="password" name="txt-cpass" id="confirmPassword" autocomplete="new-password"/>
                            <span id="confirmPasswordToggle"><i2 class="fa-regular fa-eye-slash"></i></span>
                            <div id="confirmPasswordError" class="error-message"></div>
                        </div>
                    </div>


                    </br>
                    <div class="form-group">
                        <a class="graybutton" href="login.php"><span>Back</span></a>
                        <input type="submit" name="register" value="Sign Up" class="yellowbutton">
                    </div>    
                </form>         
            </section>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/9e5ba2e3f5.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/signup.js"></script>
</body>
</html>
