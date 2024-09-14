<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/starting.css">
    <link rel="shortcut icon" href="img/icon.png" type="image/x-icon">
    <title>APC AcademX - Change Password</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <section class="form forgotpassword">
                <form id="forgotpassword">
                    <div class="form-group">
                        <img src="img/logo.png" alt="logo" >
                    </div>

                    <div class="form-control">
                        <h1><b>Forgot Password</b></h1>
                        <h3>Please enter your email address. An OTP will be sent to your email for verification.</h3>
                    </div>
                
                    <div class="form-group">
                        <div class="form-control">
                            <label>Email</label>
                            <input type="email" name="txt-emailForgot" id="emailForgot" autocomplete="new-email"/>
                            <div id="emailForgotError" class="error-message"></div>
                        </div>
                    </div>

                    </br>
                    <div class="form-group">
                        <a class="graybutton" href="login.php"><span>Back</span></a>
                        <button type="submit" class="yellowbutton">Send</button>
                    </div>      
                </form>         
            </section>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/9e5ba2e3f5.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/forgotpassword.js"></script>
</body>
</html>
