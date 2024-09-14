<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

include "config/RAMeXSO.php";


session_start(); // Start or resume the session

if (isset($_GET['user_email']) && isset($_GET['pwd'])) {
    $email = mysqli_real_escape_string($conn_soe, $_GET['user_email']);
    $password = mysqli_real_escape_string($conn_soe, $_GET['pwd']);

        // Construct the SQL query
        $query = "SELECT * FROM account WHERE user_email = '$email' LIMIT 1";
        // Execute the query
        $result = mysqli_query($conn_soe, $query) or die(mysqli_error($conn_soe));
        // Check if user exists
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $storedHashedPassword = $row['user_password'];
            // Verify the password
            if (password_verify($password, $storedHashedPassword)) {
                // Password is correct, set session variables and redirect
                $_SESSION['user'] = $row['first_name'] . " " . $row['last_name'];
                $_SESSION['id'] = $row['account_id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION["loggedin"] = true;

                $exists = true;
            } else {
                $exists = false;
            }
        } else {
            $exists = false;
        }
    // Return the result as JSON
    echo json_encode(array("exists" => $exists));
}
?>

<?php
function check_login($conn_soe) {
    if (!isset($_SESSION['account_id'])) {
        // If the user is not logged in, redirect them to the login page
        header("Location: login.php");
        die();
    }

    // Otherwise, return the user data from the database
    $id = $_SESSION['account_id'];
    $query = "SELECT * FROM account WHERE account_id = '$id' LIMIT 1";
    $result = mysqli_query($conn_soe, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        // If the user is not found, redirect them to the login page
        header("Location: login.php");
        die();
    }
}
?>

