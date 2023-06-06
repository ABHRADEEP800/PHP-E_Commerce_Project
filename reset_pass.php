<?php
session_start();
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
                  <p class="mb-4 text-muted">Enter Your Email</p>
                </div>

                <form method="post">

                  <!-- Email Input -->
                  <div class="form-floating mb-3">
                    <input class="form-control" id="emailAddress" type="email" placeholder="Enter Your Email Address" name="email" data-sb-validations="required,email" />
                    <label for="emailAddress">Email Address</label>
                  </div>

                  <!-- Showing Captcha  -->
                  <div>
                    <img src="captcha.php" alt="captcha" /><br><br>
                  </div>

                  <!-- Captcha Input  -->
                  <div class="form-floating mb-3">
                    <input type="text" name="captcha" id="captcha" class="form-control form-control-lg" placeholder="Enter Captcha Code" />
                    <label for="captcha">Captcha Code</label>
                  </div>

                  <!-- Submit button -->
                  <div class="d-grid">
                    <button class="btn btn-primary btn-lg " name="submit" id="submitButton" type="submit">Send Otp</button>
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

    // checking if captcha is matched
    if ($_SESSION['captcha'] == $_POST['captcha']) {

      // taking form data into variables
      $email = $_POST['email'];
      $sql = "SELECT * FROM user WHERE user_email = '$email'";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $count = mysqli_num_rows($result);
      if ($count > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['pass_otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['pass_otp_time'] = time();
        require 'env/smtp.php';
        $mail->addAddress($email);     //Add a recipient

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'OTP for reset password';
        $mail->Body    = 'OTP for reset password is ' . $otp;

        if ($mail->send()) {
          echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['success']('Otp Sent to Your Email.');
              setTimeout(function(){ window.location.href='cnf_pass.php'; }, 5000);
              </script>";
        } else {
          echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['error']('Something went wrong.'); 
              </script>";
        }
      } else {
        echo "<script>
          toastr.options.closeButton = true;
          toastr.options.progressBar = true;
          toastr['error']('Email Not Found!'); 
          </script>";
      }
    } else {
      echo "<script>
      toastr.options.closeButton = true;
      toastr.options.progressBar = true;
      toastr['error']('Wrong Captvha!'); 
      </script>";
    } // end of if of captcha

  } // end of if of submit button
  ?>

  <!------------------------------------------------- footer ----------------------------------------------------------->

  <?php
  // including footer
  include 'footer.php';
  ?>

</body>

</html>