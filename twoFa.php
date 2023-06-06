<?php
ob_start();
session_start();

if (!isset($_SESSION['2faotp'])) {
    header("Location: login.php");
}
require('env/database.php');

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Login</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
    <script src="jquery.js"></script>
    <link rel='stylesheet' type='text/css' media='screen' href='assets/css/main.css'>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>

<body>
    <!------------------------------------------------------Loading Screen-------------------------------------------------------- -->
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
                            <p class=" text-muted">*Otp Is Valid For 2 Minutes.</p>

                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6">
                            <a href="login.php">Back</a>
                        </div>
                        <div class="col-6 text-end">
                            <form action="" method="POST">
                                <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
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
    $email = $_SESSION['email'];
    $otp = rand(100000, 999999);
    $_SESSION['2faotp'] = $otp;
    $_SESSION['2fa_time'] = time();
    require 'env/smtp.php';
    $mail->addAddress($email);     //Add a recipient

    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'OTP for Login';
    $mail->Body    = 'OTP for Login is - ' . $otp;

    if ($mail->send()) {
        echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['success']('OTP sent to your email.'); 
              </script>";
    } else {
        echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['error']('Something went wrong.'); 
              </script>";
    }
}


if (isset($_POST['submit'])) {

    if (time() - $_SESSION['2fa_time'] > 120) {
        session_destroy();
        echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['error']('Otp expired!');
            setTimeout(function(){ window.location.href='login.php'; }, 5000);
            </script>";
        exit;
    } else {

        if ($_SESSION['2faotp'] == $_POST['otp']) {
            if ($_SESSION['user_type'] == 'Seller') {
                if (isset($_SESSION['remember'])) {
                    $data = $_SESSION['data'];
                    $email = $data['email'];
                    $session_id = $data['session_id'];


                    // Serialize the array into a string
                    $data_string = serialize($data);

                    // setting cookie for customer
                    setcookie("seller", $data_string, time() + (7 * 24 * 60 * 60), "/");

                    // Set the duration of the cookie (in seconds)
                    $cookie_duration = 7 * 24 * 60 * 60; // 7 days

                    // Calculate the expiry timestamp
                    $expiry_timestamp = time() + $cookie_duration;

                    // Convert the expiry timestamp to a SQL date format
                    $expiry_date = date('Y-m-d H:i:s', $expiry_timestamp);
                    //delete previous session id from database
                    $sql = "DELETE FROM session WHERE email = '$email'";
                    $result = mysqli_query($conn, $sql);

                    // updating session id in database
                    $sql = "INSERT INTO session (session_id, email, exp_date) VALUES ('$session_id', '$email', '$expiry_date')";
                    $result = mysqli_query($conn, $sql);

                    // setting session variable for seller
                    $_SESSION['seller'] = $email;

                    // if seller redirecting to seller index page
                    echo "<script>window.location.href='app/seller_index.php'</script>";
                } else {
                    $email = $_SESSION['email'];
                    // setting session variable for seller
                    $_SESSION['seller'] = $email;

                    // if seller redirecting to seller index page
                    echo "<script>window.location.href='app/seller_index.php'</script>";
                }
            } elseif ($_SESSION['user_type'] == 'Customer') {
                if (isset($_SESSION['remember'])) {
                    $data = $_SESSION['data'];
                    $email = $data['email'];
                    $session_id = $data['session_id'];
                    // Serialize the array into a string
                    $data_string = serialize($data);

                    // setting cookie for customer
                    setcookie("customer", $data_string, time() + (7 * 24 * 60 * 60), "/");

                    // Set the duration of the cookie (in seconds)
                    $cookie_duration = 7 * 24 * 60 * 60; // 7 days

                    // Calculate the expiry timestamp
                    $expiry_timestamp = time() + $cookie_duration;

                    // Convert the expiry timestamp to a SQL date format
                    $expiry_date = date('Y-m-d H:i:s', $expiry_timestamp);
                    //delete previous session id from database
                    $sql = "DELETE FROM session WHERE email = '$email'";
                    $result = mysqli_query($conn, $sql);

                    // updating session id in database
                    $sql = "INSERT INTO session (session_id, email, exp_date) VALUES ('$session_id', '$email', '$expiry_date')";
                    $result = mysqli_query($conn, $sql);

                    // setting session variable for seller
                    $_SESSION['customer'] = $email;

                    // if seller redirecting to seller index page
                    echo "<script>window.location.href='index.php'</script>";
                } else {
                    $email = $_SESSION['email'];
                    // setting session variable for seller
                    $_SESSION['customer'] = $email;

                    // if seller redirecting to seller index page
                    echo "<script>window.location.href='index.php'</script>";
                }
            }
        } else {
            echo "<script>
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['error']('Wrong OTP!'); 
                </script>";
        }
    }
}
ob_end_flush();


?>