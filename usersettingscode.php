<?php
session_start();

$_SESSION;
$conn = mysqli_connect("localhost","root","","ramexdb");
$id = $_SESSION['account_id'];
$sql = "SELECT * FROM account WHERE account_id = '$id' LIMIT 1"; 


//UPDATING DATA
if(isset($_POST['update_stud_data']))//update_stud_data is the button name for update
{

    //['updateVALUE'] is the name of of each class in the user 
    // $password = $_POST['updatePassword'];
    $firstname = $_POST['updateFirstname'];
    $lastname = $_POST['updateLastname'];
    // $role = $_POST['updateRole'];

    //=$result = mysqli_query($conn, "SELECT * FROM student WHERE id=1");

    $query = "UPDATE account SET first_name='$firstname', last_name='$lastname'  WHERE account_id= '$id'";
    //  pwd='$password',
    // roles='$role'
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