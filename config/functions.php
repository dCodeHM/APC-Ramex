<?php
require __DIR__ . '/../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

// Function to get the logger instance
function getLogger()
{
  static $log = null;
  if ($log === null) {
    $log = new Logger('app');
    $log->pushHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Logger::DEBUG));
  }
  return $log;
}

// Make the logger globally accessible
$log = getLogger();

function check_login($conn)
{
  global $log;

  if (isset($_SESSION['account_id'])) {
    $id = $_SESSION['account_id'];
    $_query = "SELECT * FROM account WHERE account_id = '$id' LIMIT 1";

    $result = mysqli_query($conn, $_query);
    if ($result && mysqli_num_rows($result) > 0) {
      $user_data = mysqli_fetch_assoc($result);
      $log->info('User logged in: ' . $id);
      return $user_data;
    }
  }

  // Log redirection to login
  $log->warning('User not logged in. Redirecting to login page.');

  //redirect to login
  header("Location: login.php");
  die;
}

// function random_num($length){
//   $text = "";
//   if($length < 5){
//     $length = 5;
//   }
//   $len = rand(4, $length);
//   for ($i=0; $i < $len; $i++) {
//     # code...
//     $text .= rand(0,9);
//   }
//   return $text;
// }
