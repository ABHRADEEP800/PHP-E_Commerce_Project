<?php

session_start();
if (!isset($_SESSION['pass_otp'])) {
  header("Location: reset_pass.php");
}
// including database connection
require('env/database.php');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Reset Password</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <script src="jquery.js"></script>
  <script src="main.js"></script>
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
  <?php
  // including header
  include 'header.php';
  ?>

  <!-------------------------------------------------body----------------------------------------------------------->



  <div class="container-fluid px-5 my-5">
    <div class="row justify-content-center">
      <div class="col-xl-6">
        <div class="card border-0 rounded-3 shadow-lg overflow-hidden">
          <div class="card-body p-0">
            <div class="row g-0 d-flex justify-content-center">
              <div class="col-sm-10 p-4">
                <div class="text-center">
                  <div class="h3 fw-light">Reset Password</div>
                  <p class="mb-4 text-muted">Enter Otp</p>
                </div>

                <form method="post">



                  <!-- Email Input -->
                  <div class="form-floating mb-3">
                    <input class="form-control" id="otp" type="text" placeholder="Enter otp sent to your email" name="otp" data-sb-validations="required,email" />
                    <label for="otp">Enter Otp</label>
                    <p class=" text-muted">*Otp Is Valid For 2 Minutes.</p>
                  </div>


                  <!-- new pass -->
                  <div class="form-floating mb-3">
                    <input class="form-control" id="newpass" type="password" placeholder="Enter Your New Password" name="newpass" data-sb-validations="required" />
                    <label for="newpass">New Password</label>
                  </div>

                  <!-- confirm pass -->
                  <div class="form-floating mb-3">
                    <input class="form-control" id="confirmpass" type="password" placeholder="Enter Your Confirm Password" name="confirmpass" data-sb-validations="required" />
                    <label for="confirmpass">Confirm Password</label>
                  </div>
                  <!-- Submit button -->
                  <div class="d-grid">
                    <button class="btn btn-primary btn-lg " name="submit" id="submitButton" type="submit">Change Password</button>
                  </div>
                </form>
                <!-- End of contact form -->

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  // checking if submit button is clicked
  if (isset($_POST['submit'])) {

    if (time() - $_SESSION['pass_otp_time'] > 120) {
      session_destroy();
      echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['error']('Otp expired!');
              setTimeout(function(){ window.location.href='reset_pass.php'; }, 5000);
              </script>";
      exit;
    } else {
      // checking if captcha is matched
      if ($_SESSION['pass_otp'] == $_POST['otp']) {

        // verifying if new password and confirm password are same
        if ($_POST['newpass'] == $_POST['confirmpass']) {
          if (preg_match('/^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $_POST['newpass'])) {

            $email = $_SESSION['email'];
            $newpass = md5($_POST['newpass']);
            $sql = "UPDATE `user` SET `user_pass`='$newpass' WHERE `user_email`='$email'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
              // destroying session
              session_destroy();
              echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['success']('Password Changed Successfully');
              setTimeout(function(){ window.location.href='login.php'; }, 5000);
              </script>";
            } else {
              echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['error']('Something Went Wrong!'); 
            </script>";
            }
          } else {
            echo "<script>
          toastr.options.closeButton = true;
          toastr.options.progressBar = true;
          toastr['error']('Password must contain atleast 8 characters, 1 uppercase, 1 lowercase, 1 number and 1 special character'); 
          </script>";
          }
        } else {
          echo "<script>
          toastr.options.closeButton = true;
          toastr.options.progressBar = true;
          toastr['error']('New Password and Confirm Password does not matched!'); 
          </script>";
        }
      } else {
        echo "<script>
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr['error']('Otp does not matched!'); 
        </script>";
      } // end of if of captcha
    }
  } // end of if of submit button
  ?>

  <!------------------------------------------------- footer ----------------------------------------------------------->

  <?php
  // including footer
  include 'footer.php';
  ?>

</body>

</html>