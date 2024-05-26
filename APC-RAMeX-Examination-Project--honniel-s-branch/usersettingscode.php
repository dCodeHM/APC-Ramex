<?php
session_start();
include("config/db.php");
include("config/functions.php");

$user_data = check_login($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stud_data'])) {
    $firstname = trim($_POST['updateFirstname']);
    $lastname = trim($_POST['updateLastname']);
    
    // Server-side validation: allow only alphabetic characters and spaces
    if (preg_match('/^[a-zA-Z\s]+$/', $firstname) && preg_match('/^[a-zA-Z\s]+$/', $lastname)) {
        $id = $_SESSION['account_id'];
        $stmt = $conn->prepare("UPDATE account SET first_name = ?, last_name = ? WHERE account_id = ?");
        $stmt->bind_param("ssi", $firstname, $lastname, $id);
        
        if ($stmt->execute()) {
            $_SESSION['status'] = "Data Updated Successfully";
        } else {
            $_SESSION['status'] = "Not Updated";
        }

        $stmt->close();
    } else {
        $_SESSION['status'] = "Invalid input. Only alphabetic characters and spaces are allowed.";
    }

    // Redirect based on role after updating the data
    $current_role = $user_data['role']; // Assuming role is stored in $user_data
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
