<?php
session_start();
$conn = mysqli_connect("localhost","root","","ramexdb");
$sql = "SELECT account_id, user_email, pwd, first_name, last_name, roles FROM account WHERE account_id = 1"; 

//UPDATING DATA
if(isset($_POST['update_stud_data']))//update_stud_data is the button name for update
{

    //['updateVALUE'] is the name of of each class in the user 
    $password = $_POST['updatePassword'];
    $firstname = $_POST['updateFirstname'];
    $lastname = $_POST['updateLastname'];
    $role = $_POST['updateRole'];

    //=$result = mysqli_query($conn, "SELECT * FROM student WHERE id=1");

    $query = "UPDATE account SET pwd='$password', first_name='$firstname', last_name='$lastname', roles='$roles' WHERE id=1 ";

    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $_SESSION['status'] = "Data Updated Successfully";
        header("Location: usersettings.php");
    }
    else
    {
        $_SESSION['status'] = "Not Updated";
        header("Location: usersettings.php");
    }


    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result); // Assuming single row retrieval
    // Proceed to display the data in HTML inputs
    } else {
    // Handle query error (e.g., display error message)
    }

    // <input type = "" value

}
?>

?>