<?php
session_start();

$_SESSION;
$conn = mysqli_connect("localhost:3307", "root", "", "ramexdb");
$id = $_SESSION['account_id'];
$sql = "SELECT * FROM account WHERE account_id = '$id' LIMIT 1
        SELECT * FROM role
        SELECT * FROM program_name";

//UPDATING DATA
if (isset($_POST['update_admin_data'])) {
    $id = $_POST['user_id']; // Assuming 'user_id' is the name of the hidden input in the form

    // Sanitize and validate incoming data
    $role = mysqli_real_escape_string($conn, $_POST['user_role']);

    // Prepare and execute the SQL update statement
    $query = "UPDATE account SET role=? WHERE account_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $role, $id);

    $query_run = mysqli_stmt_execute($stmt);

    if ($query_run) {
        $_SESSION['status'] = "Data Updated Successfully";
    } else {
        $_SESSION['status'] = "Not Updated";
    }

    // Redirect to the adminset.php page
    header("Location: adminset.php");
    exit(); // Make sure to call exit after header redirection
}

if (isset($_POST['update_program_data'])) {
    $id = $_POST['user_id']; // Assuming 'user_id' is the name of the hidden input in the form

    // Sanitize and validate incoming data
    $role = mysqli_real_escape_string($conn, $_POST['user_program']);

    // Prepare and execute the SQL update statement
    $query = "UPDATE account SET program_name=? WHERE account_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $role, $id);

    $query_run = mysqli_stmt_execute($stmt);

    if ($query_run) {
        $_SESSION['status'] = "Data Updated Successfully";
    } else {
        $_SESSION['status'] = "Not Updated";
    }

    // Redirect to the adminset.php page
    header("Location: adminset.php");
    exit(); // Make sure to call exit after header redirection
}

// DELETING USERS
if (isset($_POST['user_delete'])) {
    // Get the user ID to delete
    $id = $_POST['user_delete'];

    // Prepare the SQL statement using prepared statements
    $query = "DELETE FROM account WHERE account_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'i', $id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // User deleted successfully
        $_SESSION['status'] = "User Deleted Successfully";
        // Redirect to the same page after a brief delay
        echo "<script>setTimeout(function(){ window.location.href = 'adminset.php'; }, 1000);</script>";
    } else {
        // Error occurred while deleting user
        $_SESSION['status'] = "Failed to delete user";
        // Redirect to the same page
        header("Location: adminset.php");
    }

    // Close statement
    mysqli_stmt_close($stmt);
}
