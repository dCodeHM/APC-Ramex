<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../phpmailer/PHPMailerAutoload.php';
include_once "../api/config.php";

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

$response = array(); // Initialize response array

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = mysqli_real_escape_string($conn, $_POST['txt-firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['txt-lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['txt-emailSignup']);
    $password = mysqli_real_escape_string($conn, $_POST['txt-pass']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['txt-cpass']);

    $otp_str = str_shuffle("123456789");
    $otp = substr($otp_str, 0, 6);
   
    $act_str = rand(100000, 10000000);
    $activation_code = str_shuffle("abcdefghijklmno".$act_str);
   
    $currentDate = date("Y-m-d H:i:s");
    $status = "unverified";

    if (!empty($firstName) && !empty($lastName) && !empty($email) && !empty($password) && !empty($currentDate) && !empty($otp) && !empty($activation_code)&& !empty($status)) {
        if ($password === $confirmPassword) {
            // Validate email format using isValidEmail function
            if (isValidEmail($email)) {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Create the SQL query
                $sql = "INSERT INTO account(user_email, user_password, first_name, last_name, signup_date, otp, otp_date, activation_code, user_status) VALUES ('$email', '$hashedPassword', '$firstName', '$lastName', '$currentDate', '$otp', '$currentDate', '$activation_code', '$status')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    // Registration successful

                    $mail = new PHPMailer;
                                              // Enable verbose debug output

                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'apc.academx@outlook.ph';                 // SMTP username
                    $mail->Password = 'uiandtobeysx@2024';                           // SMTP password
                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                    // TCP port to connect to

                    $mail->From = 'apc.academx@outlook.ph';
                    $mail->FromName = 'APC AcademX';
                    $mail->addAddress($email);     // Add a recipient
                    $mail->WordWrap = 50;     // Add a recipient
                    $mail->isHTML(true);                                  // Set email format to HTML

                    $mail->Subject = 'Verify your APC AcademX account.';
                    $mail->Body    = '
                    <p>To verify your email address, enter this verification code when prompt: <b>' . $otp . '</b>.</p>
                    <p>Sincerely, APC Academx Team.</p>';

                    $mail->send();
                    // if($mail->send()) {
                    //     $response['data']['activation_code'] = $activation_code;
                    // } else {
                    //     $response['data']['activation_code'] = $activation_code;
                    // } 
                    
                    echo json_encode(array("activation_code" => $activation_code));
                } else {
                    // Database error
                    $response['message'] = "Error: Registration failed";
                }
            } else {
                // Invalid email format
                $response['message'] = "Invalid email format";
            }
        } else {
            // Passwords do not match
            $response['message'] = "Passwords do not match";
        }
    } else {
        // Required fields are empty
        $response['message'] = "Please fill in all required fields";
    }

    // Close the database connection
    $conn->close();
}

?>
