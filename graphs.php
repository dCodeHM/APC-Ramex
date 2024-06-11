<?php
session_start();
require('config/db.php');

$update = isset($_GET['update']) && $_GET['update'] === 'true';

if (!isset($_SESSION['account_id'])) {
    // Redirect to the login page if the user is not logged in
    echo '<script>alert("User is not logged in, directing to login page.")</script>';
    echo "<script> window.location.assign('login.php'); </script>";
    exit();
}


$account_id = $_SESSION['account_id'];


// Display the user-specific information
$sql = "SELECT * FROM account WHERE account_id = $account_id";
$result = mysqli_query($conn, $sql); // Replace with data from the database
if ($result) {
    $row = mysqli_fetch_array($result);
    $user_email = $row['user_email'];
    $pwd = $row['pwd'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $role = $row['role'];
}
// // Assuming $course_code is the course folder name
// if (isset($course_code)) {
//     $_SESSION['course_folder_name'] = $course_code;
// }
// require('coursefolder.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="APC AcademX">
    <title>APC AcademX | Exam Maker</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/adminstyle.css">
    <link rel="stylesheet" href="./css/emstyle.css">
    <link rel="stylesheet" href="./css/myexamstyle.css">
    <script src="https://kit.fontawesome.com/e85940e9f2.js" crossorigin="anonymous"></script>

</head>

<body>

    <?php include('topnavAdmin.php'); ?>

    <div class="column">

        <div class="left">

            <?php include('sidenavAdmin.php'); ?>
        </div>

        <div class="mid">
            <div class="midnav">
            <div class="midhead">
                <h1 style="color:white"> CRKTLEC </h1>
                <h4>Academic Year 2022-2023</h4>
                <h4>Term 3 | CpE-221</h4>
            </div>

            <div class="line">
            </div>

            <div class="buttonmid">
                <a href="" class="midbutton active">
                    <p> Keirchoff's Law </p>
                </a>
            </div>

            <div class="buttonmid">
                <a href="" class="midbutton">
                    <p> Ohms Law </p>
                </a>
            </div>

            <div class="buttonmid">
                <a href="" class="midbutton">
                    <p> Mesh Analysis </p>
                </a>
            </div>
            </div>
            </div>

        <!--header-->
        <div class="containerem">
            <?php

            // blue line in the graph
            $dataPoints1 = array(
                array("label" => "Easy", "y" => 36),
                array("label" => "Easy", "y" => 34),
                array("label" => "Easy", "y" => 40),
                array("label" => "Easy", "y" => 35),
                array("label" => "Easy", "y" => 39),
            );
            // green line in the graph
            $dataPoints2 = array(
                array("label" => "Normal", "y" => 64),
                array("label" => "Normal", "y" => 70),
                array("label" => "Normal", "y" => 72),
                array("label" => "Normal", "y" => 81),
                array("label" => "Normal", "y" => 49),
            );
            // red line in the graph
            $dataPoints3 = array(
                array("label" => "Hard", "y" => 50),
                array("label" => "Hard", "y" => 90),
                array("label" => "Hard", "y" => 40),
                array("label" => "Hard", "y" => 49),
                array("label" => "Hard", "y" => 63),
            );
            // 4th line in the graph
            $dataPoints4 = array(
                array("label" => "Amount of Created Exams", "y" => 64),
                array("label" => "Amount of Created Exams", "y" => 70),
                array("label" => "Amount of Created Exams", "y" => 85),
                array("label" => "Amount of Created Exams", "y" => 56),
                array("label" => "Amount of Created Exams", "y" => 96),
            );
            // label below
            $dataPoints5 = array(
                array("label" => "CLO-1", "y" => 0),
                array("label" => "CLO-2", "y" => 0),
                array("label" => "CLO-3", "y" => 0),
                array("label" => "CLO-4", "y" => 0),
                array("label" => "CLO-5", "y" => 0),
            );
            

            ?>
            <!DOCTYPE HTML>
            <html>

            <head>
                <script>
                    window.onload = function() {

                        //legend and the numbers on top of the graph

                        var chart = new CanvasJS.Chart("chartContainer", {
                            animationEnabled: true,
                            theme: "light2",
                            title: {
                                text: "Keirchoff's Law"
                            },
                            axisY: {
                                includeZero: true
                            },
                            legend: {
                                cursor: "pointer",
                                verticalAlign: "center",
                                horizontalAlign: "right",
                                itemclick: toggleDataSeries
                            },
                            data: [{
                                type: "column",
                                name: "Easy",
                                indexLabel: "{y}",
                                yValueFormatString: "#0.##",
                                showInLegend: true,
                                dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                            }, {
                                type: "column",
                                name: "Normal",
                                indexLabel: "{y}",
                                yValueFormatString: "#0.##",
                                showInLegend: true,
                                dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                            }, {
                                type: "column",
                                name: "Hard",
                                indexLabel: "{y}",
                                yValueFormatString: "#0.##",
                                showInLegend: true,
                                dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
                            }, {
                                type: "column",
                                name: "Amount of Created Exams",
                                indexLabel: "{y}",
                                yValueFormatString: "#0.##",
                                showInLegend: true,
                                dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
                            }, { 
                                type: "column",
                                name: "",
                                indexLabel: "{y}",
                                yValueFormatString: "#0.##",
                                showInLegend: false,
                                dataPoints: <?php echo json_encode($dataPoints5, JSON_NUMERIC_CHECK); ?>
                            }]
                        });
                        chart.render();

                        // hovering on the graph
                        function toggleDataSeries(e) {
                            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                e.dataSeries.visible = false;
                            } else {
                                e.dataSeries.visible = true;
                            }
                            chart.render();
                        }

                    }
                </script>
            </head>

            <body>
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
            </body>

            </html>
        </div>