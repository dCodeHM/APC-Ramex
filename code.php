<?php
session_start();
$conn = mysqli_connect("localhost","root","","ramexdb");
$sql = "SELECT id, stud_name, stud_class, stud_phone FROM student WHERE id = 1"; 

//UPDATING DATA
if(isset($_POST['update_stud_data']))
{
    $name = $_POST['name'];
    $class = $_POST['class'];
    $phone = $_POST['phone'];

    //=$result = mysqli_query($conn, "SELECT * FROM student WHERE id=1");

    $query = "UPDATE student SET stud_name='$name', stud_class='$class', stud_phone='$phone' WHERE id=1 ";

    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $_SESSION['status'] = "Data Updated Successfully";
        header("Location: index2.php");
    }
    else
    {
        $_SESSION['status'] = "Not Updated";
        header("Location: index2.php");
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