<?php
ob_start();
session_start();
require('env/database.php');

if (!isset($_SESSION['signup'])) {
    header('location:signup.php');
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Signup</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
    <script src="jquery.js"></script>
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/main.css'>

</head>

<body>
    <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
    <div id="loading">
        <img src="assets/svg-logo/LOADER.svg" alt="Loading..." />
    </div>
    <script>
        var loader = document.getElementById("loading");
        window.addEventListener("load", function() {
            loader.style.display = "none";
        })
    </script>


    <!-------------------------------------------------body----------------------------------------------------------->

    <div class="container mt-5">
        <!-- otp card -->
        <div class="col-lg-4 col-md-6 col-sm-10 mx-auto">
            <div class="card ">
                <div class="card-body">
                    <h5 class="card-title">Enter OTP</h5>
                    <p class="card-text">Enter the OTP sent to your email</p>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="otp" class="form-label">OTP</label>
                            <input type="number" class="form-control" id="otp" pattern="[0-9]{6}" maxlength="6" minlength="6" name="otp" placeholder="Enter OTP">
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6">
                            <a href="signup.php">Back</a>
                        </div>
                        <div class="col-6 text-end">
                            <form action="" method="POST">
                                <input type="hidden" name="email" value="email">
                                <button type="submit" name="resendOtp" class="btn btn-link" id="resend">Resend OTP</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>

<?php

if (isset($_POST['resendOtp'])) {
    $data = $_SESSION['signup'];
    $email = $data['user_email'];
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    require 'env/smtp.php';
    $mail->addAddress($email);     //Add a recipient

    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'OTP for Signup';
    $mail->Body    = 'OTP for Signup is - ' . $otp;

    if ($mail->send()) {
        echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['success']('OTP sent to your email');
              </script>";
    } else {
        echo "<script>
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['error']('Something Went Wrong!'); 
                </script>";
    }
}


if (isset($_POST['submit'])) {
    if ($_SESSION['otp'] == $_POST['otp']) {
        $data = $_SESSION['signup'];
        $email = $data['user_email'];
        $pass = $data['user_pass'];
        $name = $data['user_name'];
        $utype = $data['user_type'];
        $query = "INSERT INTO `user`(`user_email`,`user_name`, `user_pass`, `user_type`) VALUES ('$email','$name','$pass','$utype')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            unset($_SESSION['captcha']);
            unset($_SESSION['otp']);
            unset($_SESSION['signup']);
            echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['success']('Registered Successfully');
              setTimeout(function(){ window.location.href='login.php'; }, 5000);
              </script>";
        } else {
            unset($_SESSION['captcha']);
            unset($_SESSION['otp']);
            unset($_SESSION['signup']);
            echo "<script>
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['error']('Something Went Wrong!'); 
                setTimeout(function(){ window.location.href='signup.php'; }, 5000);
                </script>";
        }
    } else {
        echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['error']('Wrong Otp!'); 
            </script>";
    }
}

ob_end_flush();


?>