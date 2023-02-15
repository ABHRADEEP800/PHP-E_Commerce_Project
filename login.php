<?php
session_start();

// if user is logged in, redirect to index page
if (isset($_SESSION['seller'])) {
    header('location: app/seller_index.php');
  exit;
}

// including database connection file
include 'database.php';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo1.svg">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
    <script src="jquery.js"></script> 
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    
</head>
<body>

<!---------------------header -------------------------->
<?php
    include 'header.php';
?>
<!-------------------------------------------------body----------------------------------------------------------->


<section class="">
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center ">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="assets/image/seller_login.png"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <form method="post">

          <div class="divider d-flex align-items-center mb-4">
            <H2 class="text-center">Login</H2>
          </div>

          <!-- Email input -->
          <div class="form-outline mb-4">
          <label class="form-label" for="form3Example3">Email address</label>
            <input type="email" name="email" id="form3Example3" class="form-control form-control-lg"
              placeholder="Enter a valid email address" />
            
          </div>

          <!-- Password input -->
          <div class="form-outline mb-3">
          <label class="form-label" for="form3Example4">Password</label>
            <input type="password" name="pass" id="form3Example4" class="form-control form-control-lg"
              placeholder="Enter password" />            
          </div> 

          <!-- show captcha -->
          <div class="form-outline mb-3">
            <label class="form-label" for="form3Example4">Captcha</label><br>
            <img src="captcha.php" alt="captcha" /><br><br>

            <!-- input captcha  -->
            <input type="text" name="captcha" id="form3Example4" class="form-control form-control-lg"
              placeholder="Enter Captcha Code" />            
          </div>  
          
          <!-- Submit button -->
          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" name="login" class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
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
    
    // checking if login button is clicked
    if(isset($_POST['login'])){

      // checking if captcha is correct
      if($_SESSION['captcha'] == $_POST['captcha']){

        // getting email and password from form
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $password =md5($password);

        // fetching user details from database
        $sql = "SELECT * FROM user WHERE user_email = '$email'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        // taking user type and password from database
        $pass = $row['user_pass'];

        // checking if user is seller and matching password
        if($row['user_type'] == 'Seller' && $pass == $password){
            session_start();
            unset($_SESSION['captcha']);

            // setting session variable for seller
            $_SESSION['seller'] = $row['user_email'];

            // if seller redirecting to seller index page
            echo "<script>window.location.href='app/seller_index.php'</script>";
        }

        // checking if user is customer and matching password
        else if($row['user_type'] == 'Customer' && $pass == $password){
          session_start();
          unset($_SESSION['captcha']);

          // setting session variable for customer
          $_SESSION['customer'] = $row['user_email'];

          // if customer redirecting to  index page
          echo "<script>window.location.href='index.php'</script>";
        }else{
            echo "<script>alert('Invalid Email or Password')</script>";
        }
      }
      else{
          echo "<script>alert('Invalid Captcha')</script>";
      }
    }
?>