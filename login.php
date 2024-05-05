<?php 
session_start();

include("config/db.php");
include("config/functions.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{ 
  $user_email = $_POST['username'];
  $pwd = $_POST['password'];

  if(!empty($user_email) && !empty($pwd)){
    // Query database for user
    $query = "SELECT * FROM account WHERE user_email = '$user_email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0)
    {
        $user_data = mysqli_fetch_assoc($result);
        
        // Check if the password matches
        // Note: replace this line with password_verify if using hashed passwords
        if($user_data['pwd'] === $pwd){
            $_SESSION['account_id'] = $user_data['account_id'];
            $_SESSION['user_email'] = $user_data['user_email'];
            $_SESSION['first_name'] = $user_data['first_name'];
            $_SESSION['last_name'] = $user_data['last_name'];
            $_SESSION['role'] = $user_data['role'];

            // Redirect based on role
            switch ($user_data['role']) {
                case 'Executive Director':
                    header("Location: index.php");
                    exit;
                case 'Professor':
                    header("Location: professoruser.php");
                    exit;
                case 'Unassigned':
                    header("Location: unassigneduser.php");
                    break;
                default:
                    header("Location: unauthorized_access.php");
                    exit;
            }
        } else {
            echo "Wrong email or password";
        }
    } else {
        echo "Wrong email or password";
    }
  } else {
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
