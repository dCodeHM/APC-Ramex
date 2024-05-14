<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ramexdb");
$id = $_SESSION['account_id'];

// Fetch the current user's role
$sql = "SELECT * FROM account WHERE account_id = '$id' LIMIT 1"; 
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $current_role = $user_data['role'];
} else {
    die("User not found.");
}

// Updating Data
if(isset($_POST['update_stud_data'])) { // update_stud_data is the button name for update
    $firstname = $_POST['updateFirstname'];
    $lastname = $_POST['updateLastname'];

    $query = "UPDATE account SET first_name='$firstname', last_name='$lastname' WHERE account_id='$id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $_SESSION['status'] = "Data Updated Successfully";
    } else {
        $_SESSION['status'] = "Not Updated";
    }

    // Redirect based on role after updating the data
    switch ($current_role) {
        case 'Executive Director':
        case 'Program Director':
            header("Location: adminusersettings.php");
            exit;
        case 'Professor':
            header("Location: usersettings.php");
            exit;
        case 'Unassigned':
            header("Location: unassignedsettings.php");
            exit;
        default:
            header("Location: unauthorized_access.php");
            exit;
    }
}
