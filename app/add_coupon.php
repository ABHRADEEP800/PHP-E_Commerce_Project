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
                <h1 class="text-center">Add New Coupon</h1>
            </div>
            <div class="flex mx-auto col-lg-6 col-sm-12">
                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example1">Coupon Code</label>
                    <input type="text" name="c_name" id="form4Example1" placeholder="Enter Coupon Code" class="form-control" />
                </div>
                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example1">Coupon Discount (%)</label>
                    <input type="text" name="discount" id="form4Example1" placeholder="Enter Discount %" class="form-control" />
                </div>
                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example1">Expeiry Date</label>
                    <input type="date" name="date" id="form4Example1" placeholder="Enter Coupon Code" class="form-control" />
                </div>

            </div>
            <!-- Submit button -->
            <div class="d-flex mt-5 justify-content-center">
                <button type="submit" name="add_c" class="btn btn-primary btn-block mb-4">Add Coupon</button>
            </div>
        </form>
    </div>

    <?php
    // add coupon to database
    if (isset($_POST["add_c"])) {
        $code = $_POST["c_name"];
        $discount = $_POST["discount"];
        $date = date("Y-m-d", strtotime($_POST["date"]));
        $sql = "INSERT INTO Coupon (code, discount, exp_date) VALUES ('$code', '$discount', '$date')"; // insert query
        if (mysqli_query($conn, $sql)) {
            echo "<script>
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr['success']('Coupon Added Successfully');
        setTimeout(function(){ window.location.href='coupon.php'; }, 5000);
        </script>";
        } else {
            echo "<script>
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr['error']('Something Went Wrong!'); 
        </script>";
        }
    }
    ?>

</body>

</html>