<?php 
session_start();

include("config/db.php");
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
    mysqli_query($conn, $query);
    //redirect to login
    header("Location: login.php");
    die;
  }
  else{
    echo "Please enter some valid information!";
  }
}
?>

<!DOCTYPE html>
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
</html>