<?php
session_start();
// including database file
require('env/database.php');
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Signup</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <script src="jquery.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
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

  <!----------------------------------header -------------------------- -->
  <?php
  // including header file
  include 'header.php';
  ?>
  <!-------------------------------------------------body----------------------------------------------------------->

  <!------------------------- css  ----------------------------->
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
  <!----------------------------------- main body -------------------------------->
  <section>
    <div class="container-fluid h-custom mb-5">
      <div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-9 col-lg-6 col-xl-5">
          <img src="assets/image/signup.svg" class="img-fluid" alt="Sample image" />
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
          <form method="post">
            <div class="divider d-flex align-items-center mb-4">
              <h2 class="text-center">Signup</h2>
            </div>
            <!-- Name input -->

            <div class="d-flex ">
              <div class="form-outline pe-1 col-6 mb-4">
                <label class="form-label" for="form3Example3">First Name</label>
                <input type="text" name="fname" id="form3Example3" class="form-control form-control-lg" placeholder="Enter your First name" required />
              </div>
              <div class="form-outline ps-1 col-6 mb-4">
                <label class="form-label" for="form3Example3">Last Name</label>
                <input type="text" name="lname" id="form3Example3" class="form-control form-control-lg" placeholder="Enter your Last name" required />
              </div>
            </div>


            <!-- Email input -->
            <div class="form-outline mb-4">
              <label class="form-label" for="form3Example3">Email address</label>
              <input type="email" id="form3Example3" class="form-control form-control-lg" name="email" placeholder="Enter a valid email address" required />
            </div>

            <!-- Password input -->
            <div class="form-outline mb-3">
              <label class="form-label" for="form3Example4">Password</label>
              <input type="password" name="pass" id="form3Example4" class="form-control form-control-lg" placeholder="Enter password" required />
            </div>

            <!-- Confirm Password input -->
            <div class="form-outline mb-3">
              <label class="form-label" for="form3Example4">Confirm Password</label>
              <input type="password" name="cpass" id="form3Example4" class="form-control form-control-lg" placeholder="Retype password" required />
            </div>

            <!-- User Type -->
            <label class="form-label">Choose User Type</label>
            <select class="form-select" name="utype" aria-label="select example">
              <option value="Customer">Customer</option>
              <option value="Seller">Seller</option>
            </select>
            <!-- captcha -->
            <div class="form-outline mb-3">
              <label class="form-label" for="form3Example4">Captcha</label><br>
              <img src="captcha.php" alt="captcha" /><br>

              <!-- captcha input -->
              <input type="text" name="captcha" id="form3Example4" class="form-control form-control-lg" placeholder="Enter Captcha Code" required />
            </div>

            <!-- Submit button -->
            <div class="text-center text-lg-start mt-4 pt-2">
              <button type="submit" name="signup" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem">
                Register
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <?php

  // check if signup button is clicked
  if (isset($_POST['signup'])) {

    // check if captcha is correct
    if ($_SESSION['captcha'] == $_POST['captcha']) {

      // checking for empty fields
      if (($_POST['email'] == "") || ($_POST['pass'] == "") || ($_POST['cpass'] == "") || ($_POST['utype'] == "") || ($_POST['fname'] == "") || ($_POST['lname'] == "")) {
        echo "<script>alert('Please fill all the fields')</script>";
        echo "<script>window.location='signup.php'</script>";
      }

      // if all fields are not empty
      else {

        // taking form data into variables
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $cpass = $_POST['cpass'];
        $utype = $_POST['utype'];
        $name = $_POST['fname'] . " " . $_POST['lname'];

        // check if email already exists
        $sql =
          "SELECT * FROM `user` WHERE `user_email`='$email'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);

        // if email already exists
        if ($num == 1) {
          echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['error']('Email Already Exists.'); 
              </script>";
        } // end of if

        // if email does not exists
        else {

          // check if password and confirm password matches
          if ($pass == $cpass) {
            if (preg_match('/^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $pass)) {

              // encrypting password
              $pass = md5($pass);

              $signup = array(
                'user_name' => $name,
                'user_email' => $email,
                'user_pass' => $pass,
                'user_type' => $utype
              );
              $_SESSION['signup'] = $signup;
              $otp = rand(100000, 999999);
              $_SESSION['otp'] = $otp;
              require 'env/smtp.php';
              $mail->addAddress($email);     //Add a recipient

              $mail->isHTML(true);                                  //Set email format to HTML
              $mail->Subject = 'OTP for Signup';
              $mail->Body    = 'OTP for Signup is - ' . $otp;

              if ($mail->send()) {
                echo "<script>window.location.href='cnf_acc.php'</script>";
                exit;
              } else {
                echo "<script>
                    toastr.options.closeButton = true;
                    toastr.options.progressBar = true;
                    toastr['error']('Something Went Wrong.'); 
                    </script>";
                exit;
              }
            } else {
              echo "<script>
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['error']('Password must contain atleast 8 characters, 1 uppercase, 1 lowercase, 1 number and 1 special character.'); 
                </script>";
            }
          } else {
            echo "<script>
                    toastr.options.closeButton = true;
                    toastr.options.progressBar = true;
                    toastr['error']('Password and Confirm Password does not match.'); 
                    </script>";
          }
        }
      }
    }

    // if captcha does not match
    else {
      echo "<script>
          toastr.options.closeButton = true;
          toastr.options.progressBar = true;
          toastr['error']('Captcha does not match.'); 
          </script>";
    }
  }
  ?>

  <!-----------------------footer  ------------------------>
  <?php
  // including footer file
  include 'footer.php';
  ?>
</body>

</html>