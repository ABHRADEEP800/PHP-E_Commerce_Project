<?php
// start session
session_start();
if (!isset($_SESSION['admin'])) { // if admin is not logged in
    header('location:  ../admin_login.php'); // redirect to admin login page
    exit; // stop executing the script
}
// include database connection file
require('../env/database.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="asset/image/logo-bg.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
    <link rel="stylesheet" href="asset/card.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="asset/css/main.css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<?php
include 'navbar.php';
?>

<body>
    <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
    <div id="loading">
        <img src="asset/svg-logo/LOADER.svg" alt="Loading..." />
    </div>
    <script>
        var loader = document.getElementById("loading");
        window.addEventListener("load", function() {
            loader.style.display = "none";
        })
    </script>

    <div class="container ">
        <form method="post" enctype="multipart/form-data">
            <div class="px-auto">
                <h1 class="text-center">Add New User</h1>
            </div>
            <div class="flex mx-auto col-lg-6 col-sm-12">
                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example1">User Name</label>
                    <input type="text" name="user_name" id="form4Example1" placeholder="Enter User Name" class="form-control" />
                </div>

                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example2">User Email</label>
                    <input type="email" name="user_email" id="form4Example2" placeholder="Enter User Email" class="form-control" />

                </div>

                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example3">Password</label>
                    <input type="password" class="form-control" name="user_pass" id="form4Example3" placeholder="Enter Password" rows="4"></input>
                </div>
                <!-- Password confirmation -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example3">Confirm Password</label>
                    <input type="password" class="form-control" name="user_conf_pass" id="form4Example3" placeholder="Retype Password" rows="4"></input>
                </div>
                <!-- user type -->
                <label class="form-label" for="form4Example3">User Type</label>
                <select name="utype" class="form-select" aria-label="select example">
                    <option value="Customer">Customer</option>
                    <option value="Seller">Seller</option>
                </select>
            </div>
            <!-- Submit button -->
            <div class="d-flex mt-5 justify-content-center">
                <button type="submit" name="add_user" class="btn btn-primary btn-block  mb-4">Add User</button>
            </div>
        </form>
    </div>

    <?php
    // add user
    if (isset($_POST["add_user"])) { // if add user button is clicked
        $user_name = $_POST["user_name"]; // get user name
        $user_email = $_POST["user_email"]; // get user email
        $user_pass = $_POST["user_pass"]; // get user password
        $user_conf_pass = $_POST["user_conf_pass"]; // get user confirm password
        $utype = $_POST["utype"]; // get user type
        if ($user_pass != $user_conf_pass) { // if password and confirm password does not match
            echo "<script>
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['error']('Password and Confirm Password does not match!'); 
                </script>";
        } else { // if password and confirm password match
            $user_pass = md5($user_pass); // encrypt password
            $sql = "INSERT INTO `user` (`user_name`, `user_email`, `user_pass`, `user_type`) VALUES ('$user_name', '$user_email', '$user_pass', '$utype')"; // insert user data into database
            $result = mysqli_query($conn, $sql); // execute query
            if ($result) { // if query executed successfully
                echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['success']('User Added Successfully');
            setTimeout(function(){ window.location.href='user_mgmt.php'; }, 5000);
            </script>";
            } else { // if query failed
                echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['error']('Something Went Wrong!'); 
            </script>";
            }
        }
    }
    ?>

</body>

</html>