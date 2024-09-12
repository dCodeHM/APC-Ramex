<?php

if (!defined('RAMEXSO_INCLUDED')) {
    define('RAMEXSO_INCLUDED', true);

    // Database connection details
    $db_server = "localhost:3306";
    $db_user = "root";
    $db_pass = "";

    // RAMEX Database
    $db_name_ramex = "ramexdb";

    // SOE Assessment Database
    $db_name_soe = "soe_assessment_db";

    // Function to create a database connection
    if (!function_exists('createConnection')) {
        function createConnection($server, $user, $pass, $db_name) {
            $conn = mysqli_connect($server, $user, $pass, $db_name);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            return $conn;
        }
    }

    // Create connections
    $conn_ramex = createConnection($db_server, $db_user, $db_pass, $db_name_ramex);
    $conn_soe = createConnection($db_server, $db_user, $db_pass, $db_name_soe);

    // Check connections
    if ($conn_ramex && $conn_soe) {
        // echo "Successfully connected to both RAMEX and SOE Assessment databases.";
    } else {
        echo "Error: Unable to connect to one or both databases." . PHP_EOL;
        exit;
    }

    // Create MySQLi objects if needed
    $mysqli_ramex = new mysqli($db_server, $db_user, $db_pass, $db_name_ramex);
    $mysqli_soe = new mysqli($db_server, $db_user, $db_pass, $db_name_soe);

    // Check for MySQLi connection errors
    if ($mysqli_ramex->connect_error || $mysqli_soe->connect_error) {
        die("MySQLi Connection failed: " . $mysqli_ramex->connect_error . " " . $mysqli_soe->connect_error);
    }

    // Use $conn_ramex for RAMEX database queries
    // Use $conn_soe for SOE Assessment database queries
    // Use $mysqli_ramex and $mysqli_soe for MySQLi object-oriented style queries if needed
}

// // Database connection details
// $db_server = "localhost:3306";
// $db_user = "root";
// $db_pass = "";

// // RAMEX Database
// $db_name_ramex = "ramexdb";

// // SOE Assessment Database
// $db_name_soe = "soe_assessment_db";

// // Function to create a database connection
// function createConnection($server, $user, $pass, $db_name) {
//     $conn = mysqli_connect($server, $user, $pass, $db_name);
//     if (!$conn) {
//         die("Connection failed: " . mysqli_connect_error());
//     }
//     return $conn;
// }

// // Create connections
// $conn_ramex = createConnection($db_server, $db_user, $db_pass, $db_name_ramex);
// $conn_soe = createConnection($db_server, $db_user, $db_pass, $db_name_soe);

// // Check connections
// if ($conn_ramex && $conn_soe) {
//     // echo "Successfully connected to both RAMEX and SOE Assessment databases.";
// } else {
//     echo "Error: Unable to connect to one or both databases." . PHP_EOL;
//     exit;
// }

// // Create MySQLi objects if needed
// $mysqli_ramex = new mysqli($db_server, $db_user, $db_pass, $db_name_ramex);
// $mysqli_soe = new mysqli($db_server, $db_user, $db_pass, $db_name_soe);

// // Check for MySQLi connection errors
// if ($mysqli_ramex->connect_error || $mysqli_soe->connect_error) {
//     die("MySQLi Connection failed: " . $mysqli_ramex->connect_error . " " . $mysqli_soe->connect_error);
// }

// // Use $conn_ramex for RAMEX database queries
// // Use $conn_soe for SOE Assessment database queries
// // Use $mysqli_ramex and $mysqli_soe for MySQLi object-oriented style queries if needed
?>