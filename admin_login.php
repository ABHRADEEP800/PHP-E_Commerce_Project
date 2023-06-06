<?php
session_start();
// if admin is already logged in, redirect to admin_index.php
if (isset($_SESSION['admin'])) {
  header('location: app/admin_index.php');
  exit;
}
// including database connection file
require('env/database.php');
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Admin Login</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <script src="jquery.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
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
  <!------------------------------- header --------------------------->
  <?php
  // including header file
  include 'header.php';
  ?>
  <!-------------------------------------------------body----------------------------------------------------------->

  <!-- css -->
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

  <!-- main body -->
  <section class="">
    <div class="container-fluid h-custom">
      <div class="row d-flex justify-content-center align-items-center ">
        <div class="col-md-9 col-lg-6 col-xl-5">
          <img src="assets/image/admin-login3.svg" class="img-fluid" alt="Sample image">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
          <form method="post">


            <div class="divider d-flex align-items-center my-4">
              <H2 class="text-center">Admin Login</H2>
            </div>

            <!-- Email input -->
            <div class="form-outline mb-4">
              <label class="form-label" for="form3Example3">Email address</label>
              <input type="email" id="form3Example3" class="form-control form-control-lg" placeholder="Enter a valid email address" name="adminEmail" />

            </div>

            <!-- Password input -->
            <div class="form-outline mb-3">
              <label class="form-label" for="form3Example4">Password</label>
              <input type="password" id="form3Example4" class="form-control form-control-lg" placeholder="Enter password" name="adminPass" />

            </div>

            <!-- captcha -->
            <div class="form-outline mb-3">
              <label class="form-label" for="form3Example4">Captcha</label><br>
              <!-- showing captcha -->
              <img src="captcha.php" alt="captcha" /><br>

              <!-- input captcha -->
              <input type="text" name="captcha" id="form3Example4" class="form-control form-control-lg" placeholder="Enter Captcha Code" />
            </div>

            <!-- Submit button -->
            <div class="text-center text-lg-start mt-4 pt-2">
              <input type="submit" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;" value="Login" name="adminLogin">
            </div>

          </form>
        </div>
      </div>
    </div>

  </section>

  <?php
  include 'footer.php';
  ?>

</body>

</html>

<?php

// if admin login button is clicked
if (isset($_POST['adminLogin'])) {

  // checking captcha
  if ($_SESSION['captcha'] == $_POST['captcha']) {

    // getting admin email and password from form data
    $email = $_POST['adminEmail'];
    $password = $_POST['adminPass'];

    // encrypting password
    $password = md5($password);

    // fetching admin data from database
    $sql = "SELECT * FROM admin WHERE admin_email = '$email'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    // checking database password with form password
    if ($row['admin_pass'] == $password) {
      if ($row['2fa'] == 'ON') {
        $_SESSION['email'] = $row['admin_email'];
        $otp = rand(100000, 999999);
        $_SESSION['2faotp'] = $otp;
        $_SESSION['2fa_time'] = time();
        require 'env/smtp.php';
        $mail->addAddress($row['admin_email']);     //Add a recipient

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'OTP for Login';
        $mail->Body    = 'OTP for Admin Login is - ' . $otp;

        if ($mail->send()) {
          echo "<script>window.location.href='admin_twoFa.php'</script>";
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
      session_start();
      unset($_SESSION['captcha']);

      // setting session variable
      $_SESSION['admin'] = $row['admin_email'];
      echo "<script>window.location.href='app/admin_index.php'</script>";
    } else {
      echo "<script>
          toastr.options.closeButton = true;
          toastr.options.progressBar = true;
          toastr['error']('Invalid Email & Password!'); 
          </script>";
    }
  } else {
    echo "<script>
      toastr.options.closeButton = true;
      toastr.options.progressBar = true;
      toastr['error']('Wrong Captcha!'); 
      </script>";
  }
}

?>