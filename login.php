<?php
session_start();

include("config/RAMeXSO.php");
include("config/functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_POST['username'];
    $pwd = $_POST['password'];

    if (!empty($user_email) && !empty($pwd)) {
        // Query database for user
        $query = "SELECT * FROM account WHERE user_email = '$user_email' LIMIT 1";
        $result = mysqli_query($conn_soe, $query);
        
            
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            // Check if the password matches
            // Note: replace this line with password_verify if using hashed passwords
            if ($user_data['user_password'] === $pwd) {
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

<!-- <!DOCTYPE html>
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

</html> -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/starting.css">
    <link rel="shortcut icon" href="img/icon.png" type="image/x-icon">
    <title>APC AcademX - Log In</title>
</head>

<body>
    <div class="form title">
        <div class="form-group">
            <img src="img/logo.png" alt="logo">
        </div>
        <p>
            <b>Welcome to APC AcademX! </b>Your all-in-one solution for efficient and data-driven education
            management. Efficiently manage student grades, assess courses, and
            create exams using the powerful features of this system.
            <br />
            <br />
            With a strong focus on a CLO-based (Course Learning Outcomes)
            system, APC AcademX is your trusted partner in elevating education
            to the next level.
        </p>
    </div>

    <div class="space"></div>

    <div class="container">
        <div class="row">
            <section class="form login">
                <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <div class="form-control">
                            <label>Email</label>
                            <input type="email" name="username" id="emailLogin" autocomplete="username" />
                            <div id="emailLoginError" class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">
                            <label>Password</label>
                            <input type="password" name="password" id="passwordLogin" autocomplete="current-password" />
                            <div id="passwordLoginError" class="error-message"></div>
                            <span id="passwordLoginToggle"><i class="fa-regular fa-eye-slash"></i></span>
                        </div>
                    </div>
                    <div class="form-control">
                        <a class="forgotpassword" href="forgotpassword.php">Forgot Password?</a>
                    </div>
                    <div class="form-group">
                        <a class="graybutton" href="signup.php"><span>Sign-up</span></a>
                        <input type="submit" name="loginb" value="Login" class="yellowbutton">
                    </div>
                </form>
            </section>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/9e5ba2e3f5.js" crossorigin="anonymous"></script>
    <script src="js/login1.js"></script>
</body>

</html>

