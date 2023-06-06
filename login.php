<?php
session_start();
ob_start();

// including database connection file
require('env/database.php');

if (isset($_COOKIE['customer'])) {
  $data = unserialize($_COOKIE['customer']);
  $email = $data['email'];
  $session_id = $data['session_id'];
  // checking if session id is correct
  $sql = "SELECT * FROM session WHERE email = '$email' AND session_id='$session_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $expdate = $row['exp_date'];
  $expiry_timestamp = strtotime($expdate);
  if (time() > $expiry_timestamp) {
    setcookie("customer", "", time() - 3600, "/");
    echo "<script>alert('Your session has expired. Please login again.')</script>";
    echo "<script>window.location.href='login.php'</script>";
  } else {
    // setting session variable for customer
    $_SESSION['customer'] = $email;

    // if customer redirecting to  index page
    echo "<script>window.location.href='index.php'</script>";
  }
}
if (isset($_COOKIE['seller'])) {
  $data = unserialize($_COOKIE['seller']);
  $email = $data['email'];
  $session_id = $data['session_id'];
  // checking if session id and exp dateis correct
  $sql = "SELECT * FROM session WHERE email = '$email' AND session_id='$session_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $expdate = $row['exp_date'];
  $expiry_timestamp = strtotime($expdate);
  if (time() > $expiry_timestamp) {
    setcookie("seller", "", time() - 3600, "/");
    echo "<script>alert('Your session has expired. Please login again.')</script>";
    echo "<script>window.location.href='login.php'</script>";
  } else {
    // setting session variable for customer
    $_SESSION['seller'] = $email;

    // if customer redirecting to  index page
    echo "<script>window.location.href='app/seller_index.php'</script>";
  }
}

// if user is logged in, redirect to index page
if (isset($_SESSION['seller'])) {
  header('location: app/seller_index.php');
  exit;
}
// if user is logged in, redirect to index page
if (isset($_SESSION['customer'])) {
  header('location: index.php');
  exit;
}

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

  <!---------------------header -------------------------->
  <?php

  include 'header.php';
  ?>
  <!-------------------------------------------------body----------------------------------------------------------->
  <style>
    .divider:after,
    .divider:before {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
    }

    .h-custom {
      height: calc(100% - 73px);

    }

    @media (max-width: 450px) {
      .h-custom {
        height: 100%;
      }

    }
  </style>

  <section class="">
    <div class="container-fluid h-custom">
      <div class="row d-flex justify-content-center align-items-center ">
        <div class="col-md-9 col-lg-6 col-xl-5">
          <img src="assets/image/login.svg" class="img-fluid" alt="Sample image">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
          <form method="post">

            <div class="divider d-flex align-items-center my-4">
              <H2 class="text-center">Login</H2>
            </div>

            <!-- Email input -->
            <div class="form-outline mb-4">
              <label class="form-label" for="form3Example3">Email address</label>
              <input type="email" name="email" id="form3Example3" class="form-control form-control-lg" placeholder="Enter a valid email address" />

            </div>

            <!-- Password input -->
            <div class="form-outline mb-3">
              <label class="form-label" for="form3Example4">Password</label>
              <input type="password" name="pass" id="form3Example4" class="form-control form-control-lg" placeholder="Enter password" />
            </div>

            <!-- show captcha -->
            <div class="form-outline mb-3">
              <label class="form-label" for="form3Example4">Captcha</label><br>
              <img src="captcha.php" alt="captcha" /><br>

              <!-- input captcha  -->
              <input type="text" name="captcha" id="form3Example4" class="form-control form-control-lg" placeholder="Enter Captcha Code" />
            </div>

            <!-- remember me -->
            <div class="form-check mb-2">
              <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" name="remember" />
              <label class="form-check-label" for="form2Example3"> Remember me </label>
            </div>

            <!-- Submit button -->
            <div class="text-center text-lg-start mt-4 pt-2">
              <div class="row">
                <div class="col-4">
                  <button type="submit" name="login" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                  <!-- forget password -->
                </div>
                <div class="col-8 pt-2">
                  <a href="reset_pass.php" class="offset-1 ps-3 ">Forget Password</a>
                </div>
              </div>
            </div>
            <div class="mt-5">
              <p class="text-center">Don't have an account? <a href="signup.php">Register</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!----------------- footer  -------------->
  <?php
  // including footer file
  include 'footer.php';
  ?>
</body>

</html>
<?php
$session_id = substr(str_shuffle("23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 10);
// checking if login button is clicked
if (isset($_POST['login'])) {

  // checking if captcha is correct
  if ($_SESSION['captcha'] == $_POST['captcha']) {

    // getting email and password from form
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $password = md5($password);

    // fetching user details from database
    $sql = "SELECT * FROM user WHERE user_email = '$email'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    // taking user type and password from database
    $pass = $row['user_pass'];

    // checking if user is seller and matching password
    if ($row['user_type'] == 'Seller' && $pass == $password) {
      session_start();
      unset($_SESSION['captcha']);
      // if remember me is checked
      if (isset($_POST['remember'])) {
        // Create an array with multiple data
        $data = array(
          'email' => $email,
          'session_id' => $session_id,
          'user_type' => 'Seller'
        );

        if ($row['2fa'] == 'ON') {
          $_SESSION['remember'] = 'ON';
          $_SESSION['email'] = $email;
          $_SESSION['data'] = $data;
          $_SESSION['user_type'] = 'Seller';
          $otp = rand(100000, 999999);
          $_SESSION['2faotp'] = $otp;
          $_SESSION['2fa_time'] = time();
          require 'env/smtp.php';
          $mail->addAddress($email);     //Add a recipient

          $mail->isHTML(true);                                  //Set email format to HTML
          $mail->Subject = 'OTP for Login';
          $mail->Body    = 'OTP for Login is - ' . $otp;

          if ($mail->send()) {
            echo "<script>window.location.href='twoFa.php'</script>";
            exit;
          } else {
            echo "<script>
             toastr.options.closeButton = true;
             toastr.options.progressBar = true;
            toastr['error']('Something Went Wrong!'); 
            </script>";
            exit;
          }
        }

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
        $_SESSION['seller'] = $row['user_email'];

        // if seller redirecting to seller index page
        echo "<script>window.location.href='app/seller_index.php'</script>";
      } else {
        if ($row['2fa'] == 'ON') {
          $_SESSION['email'] = $row['user_email'];
          $_SESSION['user_type'] = 'Seller';
          $otp = rand(100000, 999999);
          $_SESSION['2faotp'] = $otp;
          $_SESSION['2fa_time'] = time();
          require 'env/smtp.php';
          $mail->addAddress($email);     //Add a recipient

          $mail->isHTML(true);                                  //Set email format to HTML
          $mail->Subject = 'OTP for Login';
          $mail->Body    = 'OTP for Login is - ' . $otp;

          if ($mail->send()) {
            echo "<script>window.location.href='twoFa.php'</script>";
            exit;
          } else {
            echo "<script>
             toastr.options.closeButton = true;
             toastr.options.progressBar = true;
            toastr['error']('Something Went Wrong!'); 
            </script>";
            exit;
          }
        }
        // setting session variable for seller
        $_SESSION['seller'] = $row['user_email'];

        // if seller redirecting to seller index page
        echo "<script>window.location.href='app/seller_index.php'</script>";
      }
    }

    // checking if user is customer and matching password
    else if ($row['user_type'] == 'Customer' && $pass == $password) {
      session_start();
      unset($_SESSION['captcha']);

      // if remember me is checked
      if (isset($_POST['remember'])) {
        // Create an array with multiple data
        $data = array(
          'email' => $email,
          'session_id' => $session_id,
          'user_type' => 'Customer'
        );
        if ($row['2fa'] == 'ON') {
          $_SESSION['remember'] = 'ON';
          $_SESSION['user_type'] = 'Customer';
          $_SESSION['email'] = $email;
          $_SESSION['data'] = $data;
          $otp = rand(100000, 999999);
          $_SESSION['2faotp'] = $otp;
          $_SESSION['2fa_time'] = time();
          require 'env/smtp.php';
          $mail->addAddress($email);     //Add a recipient

          $mail->isHTML(true);                                  //Set email format to HTML
          $mail->Subject = 'OTP for Login';
          $mail->Body    = 'OTP for Login is - ' . $otp;

          if ($mail->send()) {
            echo "<script>window.location.href='twoFa.php'</script>";
            exit;
          } else {
            echo "<script>
             toastr.options.closeButton = true;
             toastr.options.progressBar = true;
            toastr['error']('Something Went Wrong!'); 
            </script>";
            exit;
          }
        }



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

        // setting session variable for customer
        $_SESSION['customer'] = $row['user_email'];

        // if customer redirecting to  index page
        echo "<script>window.location.href='index.php'</script>";
      } else {
        if ($row['2fa'] == 'ON') {
          $_SESSION['email'] = $row['user_email'];
          $_SESSION['user_type'] = 'Customer';
          $otp = rand(100000, 999999);
          $_SESSION['2faotp'] = $otp;
          $_SESSION['2fa_time'] = time();
          require 'env/smtp.php';
          $mail->addAddress($email);     //Add a recipient

          $mail->isHTML(true);                                  //Set email format to HTML
          $mail->Subject = 'OTP for Login';
          $mail->Body    = 'OTP for Login is - ' . $otp;

          if ($mail->send()) {
            echo "<script>window.location.href='twoFa.php'</script>";
            exit;
          } else {
            echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
             toastr['error']('Something Went Wrong!'); 
             </script>";
            exit;
          }
        }

        // setting session variable for customer
        $_SESSION['customer'] = $row['user_email'];

        // if customer redirecting to  index page
        echo "<script>window.location.href='index.php'</script>";
      }
    } else {
      echo "<script>
             toastr.options.closeButton = true;
             toastr.options.progressBar = true;
            toastr['error']('Invalid Email or Password!'); 
            </script>";
    }
  } else {
    echo "<script>
             toastr.options.closeButton = true;
             toastr.options.progressBar = true;
            toastr['error']('Invalid Captcha!'); 
            </script>";
  }
}
ob_end_flush();
?>