<?php
session_start();
// including database connection
include 'database.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Reset Password</title>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo1.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script
      src="https://kit.fontawesome.com/db79afedbd.js"
      crossorigin="anonymous"
    ></script>
    <script src="jquery.js"></script>
 
    <script src="main.js"></script>
  </head>
  <body>
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
    if(isset($_POST['submit'])){

      // checking if captcha is matched
      if($_SESSION['otp'] == $_POST['otp']){
        
        // verifying if new password and confirm password are same
        if($_POST['newpass'] == $_POST['confirmpass']){
          $email = $_SESSION['email'];
          $newpass = md5($_POST['newpass']);
          $sql = "UPDATE `user` SET `user_pass`='$newpass' WHERE `user_email`='$email'";
          $result = mysqli_query($conn, $sql);
          if($result){
            echo "<script>alert('Password Changed Successfully!')</script>";
            // destroying session
            session_destroy();
            echo "<script>window.location.href='login.php'</script>";
          }
          else{
            echo "<script>alert('Something went wrong!')</script>";
          }
        }
        else{
          echo "<script>alert('New Password and Confirm Password does not matched!')</script>";
        }

        
    }
    else{
        echo "<script>alert('Otp does not matched!')</script>";

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