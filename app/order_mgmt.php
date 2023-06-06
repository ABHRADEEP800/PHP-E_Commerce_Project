<?php
// start session
session_start(); // Start the session
if (!isset($_SESSION['admin'])) { // If no session value is present, redirect the user:
  header('location:  ../admin_login.php'); // Redirect the user
  exit; // Quit the script
}
require('../env/database.php'); // Include the database connection.

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
  <link rel="stylesheet" href="asset/card.css" />
  <link rel="stylesheet" href="../assets/css/main.css" />
  <link rel="stylesheet" href="asset/css/main.css" />
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
  <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="text-center">Order Management</h1>
      </div>
    </div>
    <div class="pt-5">
      <form action="order_mgmt.php" method="GET">
        <div class="d-flex pb-3">

          <div class="col-7 d-flex">
            <div class="col-lg-8 col-sm-10">
              <input class="search_bar form-control border-end-0 border rounded-pill" type="text" placeholder="MM/DD/YYYY" onfocus="(this.type='date')" onblur="(this.type='text')" name="order_date" required pattern="\d{4}-\d{2}-\d{2}">
            </div>
            <div class="col-lg-4 col-sm-2 ms-2">
              <button type="submit" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" name="submit-search">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>

          <div class="col-5 d-flex justify-content-end">
            <div class="px-4 ">
              <button class="btn btn-outline-primary dropdown-toggle float-end drop_btn" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus pe-2"></i>Add
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="coupon.php">Coupon</a></li>
                <li><a class="dropdown-item" href="add_order.php">Create Order</a></li>
              </ul>
            </div>
          </div>
        </div>
      </form>
      <div class="table-responsive">

        <table class="table" border="1">
          <tr>
            <th>User Name</th>
            <th>Ordered Date</th>
            <th>Order Status</th>
            <th>Order Item</th>
            <th>Invoice</th>
            <th>Edit</th>

          </tr>

          <?php
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
          $adjacents = "2"; //starting point of each page

          if (isset($_GET['order_date'])) { // If the user clicks the search button
            $order_date = $_GET['order_date'];
            // Search the database for the user's input
            $sql = "SELECT * FROM `orders` 
            INNER JOIN  user ON user.user_id= orders.order_user
            WHERE orders.order_date LIKE '%$order_date%'
            ORDER BY orders.order_id DESC LIMIT $offset, $total_records_per_page";
            $result1 = $conn->query("SELECT count(order_id) AS order_id FROM orders WHERE orders.order_date LIKE '%$order_date%'"); // Get the total number of records
            $grapple = "&order_date=$order_date";
          }
          // If the user does not click the search button, then display all the records
          else {
            $sql = "SELECT * FROM `orders` 
            INNER JOIN  user ON user.user_id= orders.order_user
            ORDER BY orders.order_id DESC LIMIT $offset, $total_records_per_page";
            $result1 = $conn->query("SELECT count(order_id) AS order_id FROM orders"); // Get the total number of records

          }
          $countAll = $result1->fetch_all(MYSQLI_ASSOC);

          $total_records = $countAll[0]['order_id']; //total number of records
          $total_no_of_pages = ceil($total_records / $total_records_per_page);
          $second_last = $total_no_of_pages - 1; // total page minus 


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
                  // sql for getting the product name and quantity of the order
                  $sql1 = "SELECT product.product_name, order_p.order_qu
                        FROM order_p
                        INNER JOIN product ON product.product_id=order_p.order_item
                        WHERE order_p.order_id=$id";
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
              <td><a href="invoice.php?orderId=<?php echo $id ?>" class="btn btn-success">Invoice</a></td>
              <td> <a href="edit_order.php?orderId=<?php echo $id ?>" class="btn btn-primary">Edit</a></td>

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
          </ul>
        </nav>

      </div>


</body>

</html>