<!---seller login check--->
<?php
session_start(); // Start the session
if (!isset($_SESSION['seller'])) { // Check if the user is logged in
    header('location:  ../login.php');
    exit;
}
// Include the database config file
require('../env/database.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="..assets/css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="asset/css/main.css" />
</head>
<?php
include 'navbar_s.php';
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
    <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
    <!--------------------------------------------------------------------------------------- Card Item -->
    <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>

    <div class="col-md-10 mx-auto">
        <div class="row ">

            <?php

            $semail = $_SESSION['seller'];
            $sql = "SELECT * FROM user WHERE user_email='$semail'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
            $sname = $row['user_name'];
            $sid = $row['user_id'];

            $sql = "SELECT * FROM orders 
            INNER JOIN order_p ON orders.order_id=order_p.order_id
            INNER JOIN product ON product.product_id=order_p.order_item 
            WHERE product.product_userid ='$sid' AND order_date = CURDATE() ORDER BY order_date DESC"; // order by date
            $result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);
            // sql for total order
            $sql = "SELECT * FROM orders
            INNER JOIN order_p ON orders.order_id=order_p.order_id 
            INNER JOIN product ON product.product_id=order_p.order_item 
            WHERE product.product_userid ='$sid' ORDER BY order_date DESC";
            $result = mysqli_query($conn, $sql);
            $tCount = mysqli_num_rows($result);
            // sql for total product
            $sql = "SELECT * FROM product WHERE product.product_userid ='$sid';";
            $result = mysqli_query($conn, $sql);
            $pCount = mysqli_num_rows($result);
            // sql for total revenue
            $sql = "SELECT  order_p.order_qu, product.product_price
            FROM order_p
            INNER JOIN orders ON orders.order_id=order_p.order_id
            INNER JOIN product ON product.product_id=order_p.order_item
            WHERE product.product_userid ='$sid'AND orders.order_date = CURDATE();";
            $result = mysqli_query($conn, $sql);
            $rev = 0;
            while ($row = mysqli_fetch_array($result)) { // loop to store the data in an associative array.
                $sum =  $row['product_price'] * $row['order_qu'];
                $rev += $sum;
            }

            ?>
            <!-----Card item show-------->
            <div>
                <p class="h4">Welcome <?php echo $sname ?></p>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card l-bg-cherry">
                    <div class="card-statistic-3 p-4">
                        <div class="card-icon pe-2 card-icon-large"><i class="fas fa-cart-plus"></i></div>
                        <div class="mb-4">
                            <h5 class="card-title mb-0">Today's Orders</h5>
                        </div>
                        <div class="row align-items-center mb-2 d-flex">
                            <div class="col-8">
                                <h2 class="d-flex align-items-center mb-0">
                                    <?php echo $count; ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card l-bg-orange-dark">
                    <div class="card-statistic-3 p-4">
                        <div class="card-icon pe-2 card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                        <div class="mb-4">
                            <h5 class="card-title mb-0">Total Orders</h5>
                        </div>
                        <div class="row align-items-center mb-2 d-flex">
                            <div class="col-8">
                                <h2 class="d-flex align-items-center mb-0">
                                    <?php echo $tCount; ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card l-bg-blue-dark">
                    <div class="card-statistic-3 p-4">
                        <div class="card-icon pe-2 card-icon-large"><i class="fas fa-boxes-alt"></i></div>
                        <div class="mb-4">
                            <h5 class="card-title mb-0">Total Products</h5>
                        </div>
                        <div class="row align-items-center mb-2 d-flex">
                            <div class="col-8">
                                <h2 class="d-flex align-items-center mb-0">
                                    <?php echo $pCount; ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card l-bg-green-dark">
                    <div class="card-statistic-3 p-4">
                        <div class="card-icon pe-2 card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                        <div class="mb-4">
                            <h5 class="card-title mb-0">Today's Total Sales</h5>
                        </div>
                        <div class="row align-items-center mb-2 d-flex">
                            <div class="col-8">
                                <h3 class="d-flex align-items-center mb-0">
                                    ₹ <?php echo $rev; ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!------------------ Recent five orders ------------------------------->
    <div class="col-md-10 mx-auto d-flex thide ">
        <div class="col-lg-7 col-md-7 col-sm-12 mx-auto ">
            <div class="card shadow-2-strong" style="background-color: #f5f7fa;">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th align="center">Recent 5 Orders</th>
                                </tr>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Order Date</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <?php
                            $semail = $_SESSION['seller'];
                            $sql = "SELECT * FROM `user` WHERE `user_email`='$semail'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $user_id = $row['user_id'];
                            // sql for recent 5 orders
                            $sql = "SELECT * FROM orders 
                            INNER JOIN order_p ON orders.order_id=order_p.order_id 
                            INNER JOIN product ON product.product_id=order_p.order_item 
                            INNER JOIN user ON user.user_id=orders.order_user 
                            WHERE product.product_userid ='$user_id' ORDER BY orders.order_id DESC LIMIT 5  ";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) { // while loop for recent 5 orders
                                $id = $row['order_id'];
                                $user_name = $row['user_name'];
                                $product_name = $row['product_name'];
                                $product_quantity = $row['order_qu'];
                                $order_date = $row['order_date'];
                                $order_status = $row['order_status'];
                            ?>
                                <tbody>
                                    <tr>
                                        <td><?php echo $product_name ?></td>
                                        <td><?php echo $product_quantity ?> Pcs</td>
                                        <td><?php echo $order_date ?></td>
                                        <td><?php echo $order_status ?></td>
                                    </tr>
                                <?php
                            }
                                ?>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 thide mx-auto offset-1">
            <?php
            include 'calender.php';
            ?>
        </div>
    </div>
</body>
<style>
    .thide {
        display: block;
    }

    @media only screen and (max-width: 600px) {
        .thide {
            display: none !important;
        }
    }
</style>

</html>