<?
$conn = mysqli_connect('localhost', 'root', '123', 'ramexdb');
// database information (these are variables)
$user = "root"; 
$pass = "";
$db = "ramexdb";
$host = "localhost";

// creates a succesful connection or not
$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to MySQL");

echo "Great work!!!";

if(!$conn){
    echo 'Connection Error: ' . mysqli_connect_error();
}