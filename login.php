<?php 
session_start();

include("config/db.php");
include ("config/functions.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{ 
  // something was posted
  $user_email = $_POST['username'];
  $pwd = $_POST['password'];

  if(!empty($user_email) && !empty($pwd)){
    // $user_id = random_num(20);

    //READ TO DATABASE
    $query = "SELECT * FROM account WHERE user_email = '$user_email' LIMIT 1";

    $result = mysqli_query($conn, $query);

    // check if all login in fine
    if($result)
        {
            if($result && mysqli_num_rows($result) > 0)
            {
                $user_data = mysqli_fetch_assoc($result);
                
                if($user_data['pwd'] === $pwd){

                    $_SESSION['account_id'] = $user_data['account_id'];
                    header("Location: index.php");
                    die;
                }
            }
        }
        echo "Wrong email or password";
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
  <title>APC AcademX | Login</title>
  <link rel="stylesheet" href="./css/teststyle.css">
  <link rel="stylesheet" href="css/logindesign.css">
</head>

<body>
  <a href="index.php">
    <h1>Welcome!</h1>
  </a>

  <script src="" async defer></script>

  <div id="box">
    <form method="post">
      <h2>LOGIN</h2>
      <input type="text" name="username" placeholder="Username">
      <input type="password" name="password" placeholder="Password">
      <input type="submit" value="Login">

      <a href="signup.php">Register</a>
    </form>
  </div>
</body>

</html>
