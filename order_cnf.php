<?php
  session_start();
  // checking if user is logged in or not
  if(!isset($_SESSION['customer'])){
    header("Location: login.php");
  }
  // including database connection
  include('database.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Confirmed</title>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo1.svg" />
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
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />

  </head>
  <body>
    <?php include 'header.php'; ?>
    <!-- Body --------------------------------------------------------------->
    <?php 
      // getting user details from session
      if(isset($_SESSION['customer'])){
        
        $email = $_SESSION['customer'];
        // getting user name from database
        $sql = "SELECT * FROM user WHERE user_email = '$email'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $user_name = $row['user_name'];
        $first_name = substr($user_name, 0, strpos($user_name, " "));
      }

      // getting order details from database
      $order_id = $_GET['order_id'];
      $sql = "SELECT * FROM orders WHERE order_id = '$order_id'";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $order_date = $row['order_date'];
      $order_status = $row['order_status'];
      $order_discount = $row['discount'];

    ?>
    <div class="container mt-5 mb-5">
      <div class="row d-flex justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="text-left logo p-2 px-5">
              <img src="app\asset\image\logo.svg" width="100" />
            </div>

            <div class="invoice p-3">
              <div class="d-flex justify-content-between">
                <div>
                  <h5>Your order Confirmed!</h5>
                </div>
                <div>
                  <!-- download invoice -->
                  <a href="invoice.php?orderId=<?php echo $order_id; ?>" class="">Invoice<i class="fas fa-download ms-2"></i></a>
                </div>
              </div>

              <span class="font-weight-bold d-block mt-4">Hello, <?php echo $first_name; ?></span>
              <span
                >You order has been confirmed and will be shipped in next two
                days!</span
              >

              <div
                class="payment border-top mt-3 mb-3 border-bottom table-responsive"
              >
                <table class="table table-borderless">
                  <tbody>
                    <tr>
                      <td>
                        <div class="py-2">
                          <span class="d-block text-muted">Order Date</span>
                          <span><?php echo $order_date; ?></span>
                        </div>
                      </td>

                      <td>
                        <div class="py-2">
                          <span class="d-block text-muted">Order No</span>
                          <span><?php echo $order_id; ?></span>
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
                          <span><?php echo $order_status; ?></span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="product border-bottom table-responsive">
                <table class="table table-borderless">
                  <tbody>
                    <?php 
                      $total = 0;

                      // fetching order details from database
                      $sql = "SELECT * FROM order_p 
                      INNER JOIN product ON product.product_id = order_p.order_item
                      WHERE order_p.order_id = '$order_id'";
                      $result = mysqli_query($conn, $sql);
                      // showing all order details inn loop
                      while($row1=mysqli_fetch_assoc($result)){
                        $product_name = $row1['product_name'];
                        $product_price = $row1['product_price'];
                        $product_img = $row1['product_img'];
                        $product_qty = $row1['order_qu'];
                        $product_total = $product_price * $product_qty;

                    ?>
                    <tr>
                      <td width="20%">
                        <img src="app/<?php echo $product_img; ?>" width="90" />
                      </td>

                      <td width="60%">
                        <span class="font-weight-bold"><?php echo $product_name; ?></span>
                        <div class="product-qty">
                          <span class="d-block">Quantity:<?php echo $product_qty; ?> Pcs</span>
                          <!-- <span>Color:Dark</span> -->
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
                            <span class="text-success"><?=$order_discount?> %</span>
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
                            <span class="font-weight-bold">₹ <?php echo $total-($total*$order_discount/100); ?></span>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <p>
                We will be sending shipping confirmation email when the item
                shipped successfully!
              </p>
              <p class="font-weight-bold mb-0">Thanks for shopping with us!</p>
              <span>Grapple Team</span>
            </div>

            <div class="d-flex justify-content-between footer p-3">
              <span
                >Need Help? visit our
                <a href="contact_us.php"> help center</a></span
              >
              <span>© 2023 Grapple Inc. All rights reserved</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Footer --------------------------------------------------------------->
    <?php include 'footer.php'; ?>
  </body>
</html>
