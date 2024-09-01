<?php

$db_server = "localhost:3306";
$db_user = "root";
$db_pass = "";
$db_name = "ramexdb";

// connection for AIRHUB change if
// $db_server = "localhost";
// $db_user = "marj";
// $db_pass = "RAMIcpe211";
// $db_name = "ramexdb";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if ($conn) {
} else {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    exit;
}
$mysqli = new mysqli($db_server, $db_user, $db_pass, $db_name) or die(mysqli_error($mysqli));

if (mysqli_error($mysqli)) {
}
