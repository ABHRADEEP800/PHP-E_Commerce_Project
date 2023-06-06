<?php
session_start();
if (!isset($_SESSION['customer'])) {
  header("Location: login.php");
}
// including database connection
require('env/database.php');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Payment</title>
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
  <!-- including header -->
  <?php
  include 'header.php';
  ?>
  <!------------------------------------------------- main body----------------------------------------------------------->
  <div class="container d-lg-flex">
    <div class="col-md-8 mb-4">
      <div class="card mb-4">
        <div class="card-header py-3">
          <h5 class="mb-0">Order details</h5>
        </div>
        <div class="card-body">
          <div class="container">
            <table class="table">
              <!-- table head -->
              <thead class="text-center">
                <tr>
                  <th scope="col"> Product image </th>
                  <th scope="col">Product Name</th>
                  <th scope="col">Product Price</th>
                  <th scope="col">Quantity</th>
                </tr>
              </thead>
              <!-- table body -->
              <tbody class="text-center">
                <?php
                $gtotal = 0;
                $tdiscount = 0;
                $pdiscount = 0;

                if (isset($_POST['continue'])) {
                  $caddress = $_POST['address'] . ", " . $_POST['state'] . ", " . $_POST['country'] . " -" . $_POST['zip'];
                  $_SESSION['address'] = $caddress;
                } else {
                  $caddress = $_SESSION['address'];
                }

                // if cart is not empty then we will show all the products in cart
                foreach ($_SESSION['cart'] as $key => $value) {
                  // fetching product details from database
                  $product_id = $value['product_id'];
                  $product_quantity = $value['Quantity'];
                  $sql = "SELECT * FROM product WHERE product_id = '$product_id'";
                  $result = mysqli_query($conn, $sql);


                  // checking if product is available in database or not
                  while ($row = mysqli_fetch_assoc($result)) {
                    // storing product details in variables
                    $p_name = $row['product_name'];
                    $p_price = $row['product_price'];
                    $p_image = $row['product_img'];
                  } // end of while loop

                  // displaying product details in table

                  echo "
                      <tr>
                        <td><img src='$p_image' width='80px' height='60px'></td>
                        <td>$p_name</td>
                        <td>$p_price<input type='hidden' class='iprice' value='$p_price'></td>
                        <td>$product_quantity Pcs
                        </td>
                      </tr>
                    ";
                  $total = $p_price * $value['Quantity'];
                  $gtotal = $gtotal + $total;
                } // end of foreach loop

                ?>
              </tbody>
            </table>
          </div>
          <form>
            <!-- 2 column grid layout with text inputs for the first and last names -->
            <div class="row mb-2">

              <!-- Text input -->
              <div class="form-outline mb-4">
                <label class="form-label h6" for="form7Example4">Shipping Address</label>
                <input type="text" id="form7Example4" disabled class="form-control" value="<?= $caddress ?>" />
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 ms-lg-4 mb-4">
    <div class="card mb-4">
      <div class="card-header py-3">
        <h5 class="mb-0">Summary</h5>
      </div>
      <div class="card-body">

        <!-- List group -->
        <ul class="list-group list-group-flush">

          <!-- Subtotal -->
          <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-2">
            Subtotal
            <span id="gtotal">₹ <?= $gtotal ?></span>
          </li>
          <?php
          $email = $_SESSION['customer'];
          $sql = "SELECT * FROM `user` WHERE `user_email` = '$email'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $membership = $row['membership'];
          if ($membership == 'Yes') {
            $end_date = $row['membership_end_date'];
            $t_date = date('y-m-d');
            $l_date = strtotime($end_date) - strtotime($t_date);
            $l_date = round($l_date / 86400);
            if ($l_date >= '0') {
              $pdiscount = ($gtotal * 5 / 100);
              $gtotal = $gtotal - $pdiscount;
              $tdiscount = 5;
              echo "
                    <div>
                      <p><strong class='text-success'>Premium Membership Discount Applied (5%)</strong></p>
                    </div>
                    <script>
                      gtotal.innerHTML='₹$gtotal';
                    </script>
                  ";
            } else {
              $sql = "UPDATE user SET membership = 'No' WHERE user_email = '$email'";
              $result = mysqli_query($conn, $sql);
              if ($result) {
                echo "<script>
                    toastr.options.closeButton = true;
                    toastr.options.progressBar = true;
                    toastr['error']('Your Membership Expired.'); 
                    </script>";
              }
            }
          }
          ?>
          <?php
          // if coupon code is applied then we will show discount amount
          if (isset($_POST['apply_coupon'])) {
            // fetching coupon details from database
            $coupon_code = $_POST['coupon_code'];
            $sql = "SELECT * FROM Coupon WHERE code='$coupon_code'";
            $result = mysqli_query($conn, $sql);
            $row_count = mysqli_num_rows($result);

            // checking if coupon code is valid or not
            if ($row_count == 0) {
              echo "
                  <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    <strong>Sorry!</strong> Coupon code is invalid.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>
                ";
            }

            // checking if coupon code is expired or not
            else {
              $row = mysqli_fetch_assoc($result);
              $exp_date = $row['exp_date'];
              $discount = $row['discount'];
              $today = date('Y-m-d');
              // checking if coupon code is expired or not
              if ($today > $exp_date) {
                echo "
                      <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        <strong>Sorry!</strong> Coupon code is expired.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>
                    ";
              }

              // if coupon code is valid then we will show discount amount
              else {
                $gtotal = $gtotal + $pdiscount;
                $tdiscount = $tdiscount + $discount;
                $gtotal = $gtotal - ($gtotal * $tdiscount / 100);


                echo "
                      <div>
                        <p>Coupon Code: <strong class='text-success'>$coupon_code </strong>Applied</p>
                        <p>Discount: <strong>$discount% </strong></p>
                        
                      </div>
                      <div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <strong>Congratulations!</strong> You have got $discount% discount.
                        <button type='button' class=': GET10 Applied

                        btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>
                      <script>
                        gtotal.innerHTML='₹$gtotal';
                      </script>
                    ";
              }
            }
          }
          ?>
          <!-- apply coupon code -->
          <li class="list-group-item d-flex justify-content-center">
            <form method="POST">
              <!-- apply coupon code -->
              <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2" name="coupon_code">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2" name="apply_coupon">Apply</button>
              </div>
            </form>
          </li>

          <!-- payment method -->
          <li class="list-group-item">
            <div class="form-check">
              <input class="form-check-input" type="radio" value="COD" id="flexRadioDefault2" checked>
              <label class="form-check-label" for="flexRadioDefault2">
                Cash On Delivery
              </label>
            </div>
          </li>

          <!-- total amount -->
          <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
            <div>
              <strong>Total amount</strong>
            </div>
            <span id="gtotal">₹<?= $gtotal ?></span>
          </li>
        </ul>

        <!-- Purchase button -->
        <form action="purchase.php" method="POST">
          <input type="hidden" name="discount" value="<?= $tdiscount ?>">
          <button type="submit" name="purchase" class="btn btn-primary btn-lg btn-block">
            Place Order
          </button>
        </form>
      </div>
    </div>
  </div>
  </div>

  <!-----------------------------------------------------footer ---------------------------------->
  <?php
  include 'footer.php';
  ?>
</body>