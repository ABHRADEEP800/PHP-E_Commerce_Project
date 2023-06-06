<?php
session_start(); // Start the session
if (!isset($_SESSION['seller'])) { // Check if the user is logged in
  header('location:  ../login.php'); // If user is not logged in then redirect him/her to login page
  exit; // Quit the script
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
  <link rel="stylesheet" href="asset/css/main.css" />
  <link rel="stylesheet" href="../assets/css/main.css" />
</head>
<?php
// warehouse address check
include 'navbar_s.php';
$semail = $_SESSION['seller'];
$sql = "SELECT * FROM `user` WHERE `user_email`='$semail'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$address = $row['warehouse'];

// if warehouse address is not updated then redirect to update warehouse address page
if ($address == "") {
  echo '<script>alert("Please Update Your Warehouse Address");</script>';
  echo '<script>window.location.href ="s_warehouse.php";</script>';
  exit; // Quit the script
}
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
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="text-center">Order Management</h1>
      </div>
    </div>
    <div class="pt-5">
      <div class="d-flex pb-3">
        <div class="ps-3">
          <form action="s_omgmt.php" method="GET">
            <label>
              Enter Date to Search Order:-
            </label><br>
            <div class="d-flex">
              <input class="search_bar form-control border-end-0 border rounded-pill" type="text" placeholder="MM/DD/YYYY" onfocus="(this.type='date')" onblur="(this.type='text')" name="order_date" required pattern="\d{4}-\d{2}-\d{2}" />
              <button type="submit" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill mx-4" name="submit-search">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </form>
        </div>

      </div>
      <!-------------show order by date search or show all orders--->
      <div class="table-responsive">
        <table class="table" border="1">
          <tr>
            <th>User Name</th>
            <th>Ordered Date</th>
            <th>Order Status</th>
            <th>Order Item</th>
            <th>Invoice</th>
            <th>Shipping Label</th>
            <th>Edit</th>

          </tr>
          <?php

          $semail = $_SESSION['seller']; // get seller email from session
          $sql = "SELECT * FROM `user` WHERE `user_email`='$semail'"; // get seller id from user table
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $user_id = $row['user_id']; // get seller id from user table
          $grapple = null;


          if (isset($_GET['page']) && $_GET['page'] != "") {
            $page_no = $_GET['page'];
          } else {
            $page_no = 1;
          }

          $total_records_per_page = 5;
          $offset = ($page_no - 1) * $total_records_per_page;
          $previous_page = $page_no - 1;
          $next_page = $page_no + 1;
          $adjacents = "2"; //starting point of each pages


          // if search button is clicked then search order by date
          if (isset($_GET['order_date'])) {
            $order_date = $_GET['order_date'];

            $sql = "SELECT DISTINCT orders.order_id,orders.order_user,orders.order_date,orders.order_status,user.user_name
                FROM orders
                INNER JOIN order_p ON orders.order_id = order_p.order_id
                INNER JOIN product ON product.product_id = order_p.order_item
                INNER JOIN `user` ON `user`.user_id = orders.order_user
                  WHERE product.product_userid ='$user_id'AND orders.order_date LIKE '%$order_date%' ORDER BY orders.order_id DESC LIMIT $offset, $total_records_per_page ";
            $result1 = $conn->query("SELECT COUNT(DISTINCT(orders.order_id)) AS order_id FROM orders INNER JOIN order_p ON orders.order_id=order_p.order_id INNER JOIN product ON product.product_id=order_p.order_item INNER JOIN user ON user.user_id=orders.order_user WHERE product.product_userid ='$user_id'AND orders.order_date LIKE '%$order_date%'");
            $grapple = "&order_date=$order_date";
          }
          // if search button is not clicked then show all orders
          else {
            $sql = "SELECT DISTINCT orders.order_id,orders.order_user,orders.order_date,orders.order_status,user.user_name
                FROM orders
                INNER JOIN order_p ON orders.order_id = order_p.order_id
                INNER JOIN product ON product.product_id = order_p.order_item
                INNER JOIN `user` ON `user`.user_id = orders.order_user
                 WHERE product.product_userid ='$user_id' ORDER BY orders.order_id DESC LIMIT $offset, $total_records_per_page ";
            $result1 = $conn->query("SELECT COUNT(DISTINCT(orders.order_id)) AS order_id FROM orders INNER JOIN order_p ON orders.order_id=order_p.order_id INNER JOIN product ON product.product_id=order_p.order_item INNER JOIN user ON user.user_id=orders.order_user WHERE product.product_userid ='$user_id'");
          }
          $countAll = $result1->fetch_all(MYSQLI_ASSOC);

          $total_records = $countAll[0]['order_id']; // get total number of orders
          $total_no_of_pages = ceil($total_records / $total_records_per_page);
          $second_last = $total_no_of_pages - 1; // total page minus

          // get order details
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['order_id'];
            $user_name = $row['user_name'];
            $order_date = $row['order_date'];
            $order_status = $row['order_status'];
          ?>
            <tr>
              <td><?php echo $user_name ?></td>
              <td><?php echo $order_date ?></td>
              <td><?php echo $order_status ?></td>
              <td>
                <table class="table">

                  <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                  </tr>
                  <?php
                  $sql1 = "SELECT product.product_name, order_p.order_qu
                        FROM order_p
                        INNER JOIN product ON product.product_id=order_p.order_item
                        WHERE order_p.order_id=$id AND product.product_userid ='$user_id' ";
                  $result1 = mysqli_query($conn, $sql1);
                  while ($row1 = mysqli_fetch_assoc($result1)) {
                    $product_name = $row1['product_name'];
                    $product_quantity = $row1['order_qu'];

                    echo " <tr>
                            <td>$product_name</td>
                            <td>$product_quantity Pcs</td>
                        </tr>";
                  }
                  ?>
                </table>
              </td>
              <td><a href="s_invoice.php?orderId=<?php echo $id ?>" class="btn btn-success">Invoice</a></td>
              <td><a href="s_olabel.php?orderId=<?php echo $id ?>" class="btn btn-warning">Shipping Label</a></td>
              <td> <a href="s_oedit.php?orderId=<?php echo $id ?>" class="btn btn-primary">Edit</a></td>

            </tr>
          <?php
          }
          ?>
        </table>
      </div>

      <div class="row mt-3">
        <div class="col-lg-3 col-sm-12" style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
          <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
        </div>
        <nav aria-label="..." class="col-lg-9 col-sm-12 d-flex justify-content-end">
          <ul class="pagination">
            <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } 
            ?>

            <li class="page-item <?php if ($page_no <= 1) {
                                    echo 'disabled';
                                  } ?>">
              <a class="page-link" <?php if ($page_no > 1) {
                                      echo "href='?page=$previous_page $grapple'";
                                    } ?>>Previous</a>
            </li>

            <?php
            if ($total_no_of_pages <= 1) {
              echo "<li class='page-item active'><a class='page-link'>1</a></li>";
            } elseif ($total_no_of_pages <= 7) {
              for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                if ($counter == $page_no) {
                  echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                } else {
                  echo "<li class='page-item'><a class='page-link' href='?page=$counter $grapple'>$counter</a></li>";
                }
              }
            } else {
              if ($page_no <= 2) {
                for ($counter = 1; $counter <= 3; $counter++) {
                  if ($counter == $page_no) {
                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                  } else {
                    echo "<li class='page-item'><a class='page-link' href='?page=$counter $grapple'>$counter</a></li>";
                  }
                }
                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                echo "<li class='page-item'><a class='page-link' href='?page=$total_no_of_pages $grapple'>$total_no_of_pages</a></li>";
              } elseif ($page_no > 2 && $page_no < $total_no_of_pages - 1) {
                echo "<li class='page-item'><a class='page-link' href='?page=1 $grapple'>1</a></li>";
                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                for ($counter = $page_no - 1; $counter <= $page_no + 1; $counter++) {
                  if ($counter == $page_no) {
                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                  } else {
                    echo "<li class='page-item'><a class='page-link' href='?page=$counter $grapple'>$counter</a></li>";
                  }
                }
                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                echo "<li class='page-item'><a class='page-link' href='?page=$total_no_of_pages $grapple'>$total_no_of_pages</a></li>";
              } else {
                echo "<li class='page-item'><a class='page-link' href='?page=1 $grapple'>1</a></li>";
                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                for ($counter = $total_no_of_pages - 2; $counter <= $total_no_of_pages; $counter++) {
                  if ($counter == $page_no) {
                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                  } else {
                    echo "<li class='page-item'><a class='page-link' href='?page=$counter $grapple'>$counter</a></li>";
                  }
                }
              }
            }
            ?>

            <li class="page-item <?php if ($page_no >= $total_no_of_pages) {
                                    echo 'disabled';
                                  } ?>">
              <a class="page-link" <?php if ($page_no < $total_no_of_pages) {
                                      echo "href='?page=$next_page $grapple'";
                                    } ?>>Next</a>
            </li>

            <?php
            // if($page_no < $total_no_of_pages) {
            //   echo "<li class='page-item'><a class='page-link' href='?page=$total_no_of_pages $grapple'>Last &rsaquo;&rsaquo;</a></li>";
            // }
            ?>
          </ul>
        </nav>

      </div>


</body>

</html>