<?php
session_start();
// checking if user is logged in or not
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
  <title>Orders</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">

  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />


</head>

<body>
  <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
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
  // including header
  include 'header.php';
  ?>
  <!----------------------------------------------Body---------------------------------------------------------->

  <?php
  // getting user details from database
  $user_email = $_SESSION['customer'];
  $sql = "SELECT * FROM user WHERE user_email = '$user_email'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $user_name = $row['user_name'];
  $user_id = $row['user_id'];
  ?>
  <section>
    <!------------------ order Details --------------------->
    <div class="container mt-4">
      <div class="card mb-4">
        <div class="card-body">
          <div class="mb-4">
            <div class="section-title">
              <p class="h3">All Orders</p>
            </div>
            <hr>
            <?php
            // getting orders of user from database
            $sql = "SELECT * FROM orders WHERE order_user = '$user_id' ORDER BY order_id DESC";
            $result = mysqli_query($conn, $sql);

            // displaying orders of user
            while ($row = mysqli_fetch_assoc($result)) {
              $order_id = $row['order_id'];
              $order_date = date("d M Y ", strtotime($row['order_date']));
              $order_status = $row['order_status'];
              // getting order items of user from database
              $sql1 = "SELECT product.product_name, order_p.order_qu, product.product_img ,product.product_id
                        FROM order_p
                        INNER JOIN product ON product.product_id=order_p.order_item
                        WHERE order_p.order_id=$order_id ";
              $result1 = mysqli_query($conn, $sql1);

              // displaying order items of user
              while ($row1 = mysqli_fetch_assoc($result1)) {
                $product_name = $row1['product_name'];
                $product_quantity = $row1['order_qu'];
                $product_img = $row1['product_img'];
                $product_id = $row1['product_id'];

            ?>

                <div class="row mb-3 bb-1 pt-0">
                  <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                    <img class="thumb_img" src="<?php echo $product_img; ?>">
                  </div>
                  <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                    <a class="d-flex" href="order_view.php?order_id=<?php echo $order_id; ?>">
                      <div class="col-6 mt-2 me-3">
                        <p class="h4"><?php echo $product_name; ?></p>
                      </div>
                      <div class="col-4 mt-2">
                        <small><?php echo $product_quantity . " Pcs"; ?></small>

                      </div>
                      <div class="col-2 mt-2">
                        <i class="fa fa-angle-right"></i>
                      </div>
                    </a>
                    <div class="d-flex">
                      <div class="col-6 mt-2">
                        <small class="text-muted "><?php echo $order_status . " on " . $order_date; ?></small>
                      </div>
                      <?php
                      // checking if order is delivered or not
                      if ($order_status == "Delivered") {
                        // getting review of product from database
                        $sql3 = "SELECT * FROM review WHERE product_id = '$product_id' AND order_id = '$order_id'";
                        $result22 = mysqli_query($conn, $sql3);
                        $row_count = mysqli_num_rows($result22);
                        // checking if user has already rated the product or not
                        if ($row_count == 0) {
                      ?>
                          <div class='col-6 mt-2 text-end'>
                            <a class='text-end' href='rating.php?order_id=<?php echo $order_id; ?>&product_id=<?= $product_id ?>'>Rate This product >></a>
                          </div>
                      <?php

                        }
                      }
                      ?>
                    </div>
                  </div>
                </div>
                <!-- bottom seperator line -->
                <hr>
            <?php
              }
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!----------------------------------- footer -------------------------------------------->
  <?php
  // including footer
  include 'footer.php';
  ?>
</body>

</html>