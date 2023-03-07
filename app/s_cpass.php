<!---Seller check login--->
<?php
session_start(); // Start the session
if (!isset($_SESSION['seller'])) { // Check if the user is logged in
    header('location: /login.php'); // If user is not logged in then redirect him/her to login page
  exit; // Quit the script
}
// Include the database config file
include 'database.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"/>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
    ></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
    ></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
    <link rel="stylesheet" href="asset/card.css" />
</head>
<?php
    include 'navbar_s.php';
    include 'database.php';     
?>
<body>
<?php
        $email=$_SESSION['seller'];
        
        ?>
        <!----show seller email to confirm and change password--->
        <div class="container ">
            <form method="post" enctype="multipart/form-data">
                <div class="px-auto">
                    <h1 class="text-center">Reset Your Password</h1>
                </div>
                <div class="flex mx-auto col-9 " >
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example1">Your Email</label>
                        <input type="text" name="product_name" id="form4Example1" value="<?=$email?>" disabled class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example2">Enter Old Password</label>
                        <input type="password" name="old_pass" id="form4Example2" placeholder="Enter Old Password" class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example2">Enter New Password</label>
                        <input type="password" name="new_pass" id="form4Example2" placeholder="Enter new Password" class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example3">Confirm Password</label>
                        <input type="password" class="form-control" name="cnf_pass" id="form4Example3" placeholder="Retype Password" rows="4"></input>
                    </div>
                </div>
                <!-- Submit button -->
                <div class="d-flex justify-content-center">
                    <button type="submit" name="update" class="btn btn-primary btn-block col-5 mb-4">Reset Password</button>
                </div>
                
            </form>
        </div>
        <!----php code for change password--->
<?php
// password change
if(isset($_POST["update"])) 
{   $old_pass=md5($_POST['old_pass']);
    $sql="SELECT * FROM user WHERE user_email='$email' AND user_pass='$old_pass'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);
    // if old password is correct
    if ($row['user_pass'] == $old_pass) {
        $new_pass = $_POST['new_pass'];
        $cnf_pass = $_POST['cnf_pass'];

        // if new password and confirm password is same
        if ($new_pass == $cnf_pass) {
            $new_pass = md5($new_pass);
            $sql = "UPDATE user SET user_pass='$new_pass' WHERE user_email='$email'";
            mysqli_query($conn, $sql);
            echo '<script>
                alert("Password changed successfully");
                window.location.href="seller_index.php";
            </script>';
        }
        // if new password and confirm password is not same 
        else {
            echo '<script>
                alert("Password does not match");
            </script>';
        }
        //  if old password is incorrect
    }else{
        echo '<script>
                alert("Old Password is incorrect");
            </script>';
        }
}
?>
</body>
</html>