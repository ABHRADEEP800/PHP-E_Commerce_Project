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
    <title>Contact Us</title>
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
                <div class="h3 fw-light">Contact Form</div>
                <p class="mb-4 text-muted">Grapple Inc.</p>
              </div>

              <form method="post">
                

                <!-- Name Input -->
                <div class="form-floating mb-3">
                  <input class="form-control" id="name" type="text" placeholder="Enter Your Name" name="name" data-sb-validations="required" />
                  <label for="name">Name</label>
                </div>

                <!-- Email Input -->
                <div class="form-floating mb-3">
                  <input class="form-control" id="emailAddress" type="email" placeholder="Enter Your Email Address" name="email" data-sb-validations="required,email" />
                  <label for="emailAddress">Email Address</label>
                </div>

                <!-- Message Input -->
                <div class="form-floating mb-3">
                  <textarea class="form-control" id="message" name="message" type="text" placeholder="Enter Message" style="height: 10rem;" data-sb-validations="required"></textarea>
                  <label for="message">Message</label>
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
                  <button class="btn btn-primary btn-lg " name="submit" id="submitButton" type="submit">Submit</button>
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
      if($_SESSION['captcha'] == $_POST['captcha']){
        
        // taking form data into variables
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        // sql query to insert data into database
        $sql = "INSERT INTO `contact` (`name`, `email`, `message`) VALUES ('$name', '$email', '$message')";

        // executing query
        $result = mysqli_query($conn, $sql);

        // checking if query is executed
        if($result){
            echo "<script>alert('We will contact you soon!')</script>";
        }
        else{
            echo "<script>alert('Something went wrong!')</script>";
        }
    }
    else{
        echo "<script>alert('Captcha is not matched!')</script>";

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