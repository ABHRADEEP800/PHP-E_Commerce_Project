<?php
session_start(); // Start the session
if (!isset($_SESSION['admin'])) { // If no session value is present, redirect the user:
    header('location: /admin_login.php'); 
  exit;
}
include 'database.php'; // Include the database connection.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
    include 'navbar.php';
    include 'database.php';     
?>

<body>

        <?php
            $email=$_SESSION['admin']; // Get the admin email from the session.
        ?>

        <div class="container ">
            <form method="post" enctype="multipart/form-data">
                <div class="px-auto">
                    <h1 class="text-center">Reset Admin Password</h1>
                </div>
                <div class="flex mx-auto col-6 " >
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example1">Admin Email</label>
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
                    <button type="submit" name="update" class="btn btn-primary btn-block col-3 mb-4">Reset Password</button>
                </div>
                
            </form>
        </div>
<?php
if(isset($_POST["update"])) 
{   $old_pass=md5($_POST['old_pass']); // Get the old password from the form.
    $sql="SELECT * FROM admin WHERE admin_email='$email' AND admin_pass='$old_pass'"; // Check if the old password is correct.
    $result=mysqli_query($conn,$sql); 
    $row=mysqli_fetch_assoc($result);
    if ($row['admin_pass'] == $old_pass) { // If the old password is correct, update the password.
        $new_pass = $_POST['new_pass']; // Get the new password from the form.
        $cnf_pass = $_POST['cnf_pass']; // Get the confirm password from the form.
        if ($new_pass == $cnf_pass) { // If the new password and confirm password matches, update the password.
            $new_pass = md5($new_pass); // Encrypt the new password.
            $sql = "UPDATE admin SET admin_pass='$new_pass' WHERE admin_email='$email'"; // Update the password.
            mysqli_query($conn, $sql); 
            echo '<script>
                alert("Password changed successfully");
                window.location.href="admin_index.php";
            </script>';
        } else {
            echo '<script>
                alert("Password does not match");
            </script>';
        }
    }else{
        echo '<script>
                alert("Old Password is incorrect");
            </script>';
        }
}
?>
</body>
</html>