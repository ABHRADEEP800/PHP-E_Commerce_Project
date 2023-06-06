<?php
session_start();

// if user is not logged in, redirect to login page
if (!isset($_SESSION['customer'])) {
  header('location: login.php');
  exit;
}

// including database connection file
require('env/database.php');

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Order Invoice</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">

  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
  <script src='main.js'></script>
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

  <!-- html to pdf script -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    function generatePDF() {
      const element = document.getElementById('container_content');
      var opt = {
        margin: 0,
        filename: 'invoice.pdf',
        image: {
          type: 'jpeg',
          quality: 0.98
        },
        html2canvas: {
          scale: 2
        },
        jsPDF: {
          unit: 'in',
          format: 'A3',
          orientation: 'portrait'
        }
      };
      // Choose the element that our invoice is rendered in.
      html2pdf().set(opt).from(element).save();
    }
  </script>
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

  <!-- download invoice button -->
  <div class="container text-center " style="padding:20px;">
    <div class="row">
      <div class="col-6 d-flex justify-content-start">

        <a href="account.php?tab=orders" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i>Back</a>

      </div>
      <div class="col-6 d-flex  justify-content-end">
        <button class=" btn btn-primary" onclick="generatePDF()">Invoice<i class="fas fa-download ms-2"></i></button>
      </div>
    </div>
  </div>


  <?php
  // taking order id from url
  $id = $_GET['orderId'];

  //fetch user_id
  $user_email = $_SESSION['customer'];
  $sql = "SELECT user_id FROM user WHERE user_email = '$user_email'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $user_id = $row['user_id'];
  // fetching order details from database
  $sql1 = "SELECT  user.user_name, user.user_email, product.product_name, product.product_img, order_p.order_qu, product.product_price, orders.order_date, orders.order_status, orders.order_id, orders.discount,orders.shipping_address
  FROM orders
  INNER JOIN user ON user.user_id=orders.order_user
  INNER JOIN order_p ON order_p.order_id=orders.order_id
  INNER JOIN product ON product.product_id=order_p.order_item
  WHERE orders.order_id = '$id' && orders.order_user = '$user_id'";

  // executing query
  $result1 = mysqli_query($conn, $sql1);
  //fetch row count
  $rowcount = mysqli_num_rows($result1);
  if ($rowcount > 0) {
    //fetching result in variable
    $row = mysqli_fetch_assoc($result1);
  } else {
    echo "<script>alert('Invalid Order Id');</script>";
    echo "<script>window.location.href='account.php';</script>";
  }



  ?>

  <!------------- main invoice for pdf  -->
  <div id="container_content">
    <div class="container mt-5 mb-5">
      <div class="row d-flex justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="text-left logo p-2 px-5">
              <img src="app\asset\image\logo-bg.svg" width="100" />
            </div>

            <div class="invoice p-3">
              <div class="d-flex justify-content-center">
                <div>
                  <h2>Invoice</h2>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="py-2">
                    <span class="d-block text-muted">User</span>
                    <span><?= $row['user_name'] ?></span>
                  </div>
                </div>

                <div class="col-md-6 d-flex justify-content-end">
                  <div class="py-2">
                    <span class="d-block text-muted">Shipping Address</span>
                    <span><?= $row['shipping_address'] ?></span>
                  </div>
                </div>

                <!-- order details -->
                <div class="payment border-top mt-3 mb-3 border-bottom table-responsive">
                  <!-- order details table -->
                  <table class="table table-borderless">
                    <tbody>
                      <tr>
                        <td>
                          <div class="py-2">
                            <span class="d-block text-muted">Order Date</span>
                            <span><?= $row['order_date'] ?></span>
                          </div>
                        </td>

                        <td>
                          <div class="py-2">
                            <span class="d-block text-muted">Order No</span>
                            <span><?= $row['order_id'] ?></span>
                          </div>
                        </td>

                        <td>
                          <div class="py-2">
                            <span class="d-block text-muted">Payment</span>
                            <span>Cash On delivery</span>
                          </div>
                        </td>

                        <td>
                          <div class="py-2">
                            <span class="d-block text-muted">Order Status</span>
                            <span><?= $row['order_status'] ?></span>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- order items -->
                <div class="product border-bottom table-responsive">
                  <table class="table table-borderless">
                    <tbody>
                      <?php
                      $total = 0;
                      $order_id = $row['order_id'];
                      $order_discount = $row['discount'];
                      // getting order items from database
                      $sql = "SELECT * FROM order_p 
                      INNER JOIN product ON product.product_id = order_p.order_item
                      WHERE order_p.order_id = '$order_id'";
                      $result = mysqli_query($conn, $sql);
                      while ($row1 = mysqli_fetch_assoc($result)) {
                        $product_name = $row1['product_name'];
                        $product_price = $row1['price'];
                        $product_img = $row1['product_img'];
                        $product_qty = $row1['order_qu'];
                        $product_id = $row1['product_id'];
                        $product_total = $product_price * $product_qty;
                      ?>
                        <tr>
                          <td width="20%">
                            <img src="<?php echo $product_img; ?>" width="90" />
                          </td>

                          <td width="60%">
                            <span class="font-weight-bold"><?php echo $product_name; ?></span>
                            <div class="product-qty">
                              <span class="d-block">Quantity:<?php echo $product_qty; ?> Pcs</span>
                            </div>
                          </td>
                          <td width="20%">
                            <div class="text-right">
                              <span class="font-weight-bold">₹ <?php echo $product_price; ?></span>
                            </div>
                          </td>
                        </tr>
                      <?php

                        $total = $total + $product_total;
                      }

                      ?>

                    </tbody>
                  </table>
                </div>

                <!-- order total -->
                <div class="row d-flex justify-content-end">
                  <div class="col-md-5">
                    <table class="table table-borderless">
                      <tbody class="totals">
                        <tr>
                          <td>
                            <div class="text-left">
                              <span class="text-muted">Subtotal</span>
                            </div>
                          </td>
                          <td>
                            <div class="text-right">
                              <span>₹ <?php echo $total; ?></span>
                            </div>
                          </td>
                        </tr>

                        <tr>
                          <td>
                            <div class="text-left">
                              <span class="text-muted">Shipping Fee</span>
                            </div>
                          </td>
                          <td>
                            <div class="text-right">
                              <span>₹ 0</span>
                            </div>
                          </td>
                        </tr>

                        <tr>
                          <td>
                            <div class="text-left">
                              <span class="text-muted">Discount</span>
                            </div>
                          </td>
                          <td>
                            <div class="text-right">
                              <span class="text-success"><?= $order_discount ?> %</span>
                            </div>
                          </td>
                        </tr>

                        <tr class="border-top border-bottom">
                          <td>
                            <div class="text-left">
                              <span class="font-weight-bold">Total</span>
                            </div>
                          </td>
                          <td>
                            <div class="text-right">
                              <span class="font-weight-bold">₹ <?php echo $total - ($total * $order_discount / 100); ?></span>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- order status -->

                <p class="font-weight-bold mb-0">Thanks for shopping with us!</p>
                <span>Grapple Team</span>
              </div>

              <div class="d-flex justify-content-between footer p-3">

                <span>© 2023 Grapple Inc. All rights reserved</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>

</html>