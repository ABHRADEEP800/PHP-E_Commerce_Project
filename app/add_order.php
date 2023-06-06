<?php
// Start the session
session_start();
if (!isset($_SESSION['admin'])) {
  header('location:  ../admin_login.php');
  exit;
}
// Connect to the database
require('../env/database.php');
$count = 0;
if (isset($_SESSION['cart'])) {
  $count = count($_SESSION['cart']);
}
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
  <link rel="stylesheet" type="text/css" media="screen" href="../assetS/css/main.css" />
  <link rel="stylesheet" href="../assets/css/main.css" />
  <link rel="stylesheet" href="asset/css/main.css" />
  <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<?php
// include the navbar
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

    function add_cart(id) {
      $.ajax({
        url: "manage_cart.php",
        data: "product_id=" + id + "&Add_To_Cart=",
        method: "post",
        success: function(response) {
          //receive json response and check in json status = added
          var data = JSON.parse(response);
          //convert data to array
          var arr = Object.values(data);
          if (arr[0]['status'] == 'added') {
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['success']('Product added to cart.');
            $("#count").html(arr[0]['cart_count']);
          } else if (arr[0]['status'] == 'already') {
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['warning']('Product already in cart.');
          } else if (arr[0]['status'] == 'not_login') {
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['warning']('Please login to continue.');
            setTimeout(function() {
              window.location.href = '../admin_login.php';
            }, 5000);

          }
        }
      });
    }
  </script>



  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="text-center">Add New Order</h1>
      </div>
    </div>
    <div class="pt-5">
      <form method="GET">
        <div class="d-flex pb-3">

          <div class="col-7 d-flex">
            <div class="col-lg-8 col-sm-10">
              <input class="search_bar form-control border-end-0 border rounded-pill" type="text" name="search" placeholder="Search Product By Name">
            </div>
            <div class="col-lg-4 col-sm-2 ms-2">
              <button type="submit" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" name="submit-search">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>

          <div class="col-5">
            <!-- add product  -->

            <a href="cnf_order.php" class="btn btn-warning float-end">
              <i class='fa-solid fa-cart-shopping'></i>
              <span id="count"><?= $count ?></span>

            </a>
          </div>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table" border="1">
          <tr>
            <th>Product Name</th>
            <th>Product Image</th>
            <th>Product Description</th>
            <th>Product Price</th>
            <th>Product Quantity</th>
            <th>Add To Cart</th>
          </tr>

          <?php
          $grapple = null;
          if (isset($_GET['page']) && $_GET['page'] != "") {
            $page_no = $_GET['page'];
          } else {
            $page_no = 1;
          }

          $total_records_per_page = 10;
          $offset = ($page_no - 1) * $total_records_per_page;
          $previous_page = $page_no - 1;
          $next_page = $page_no + 1;
          $adjacents = "2"; //starting point of each page


          if (isset($_GET['search'])) { // If the user clicks the search button
            $query = $_GET['search']; // Get the search query
            $sql = "SELECT * FROM product WHERE  product_name LIKE '%$query%' LIMIT $offset, $total_records_per_page"; // Search the product by name
            $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product WHERE  product_name LIKE '%$query%'"); // Get the total number of records
            $grapple = "&search=$query";
          } else { // If the user doesn't click the search button

            $sql = "SELECT * FROM product LIMIT $offset, $total_records_per_page"; // Get all the products from the database
            $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product"); // Get the total number of records

          }
          $countAll = $result1->fetch_all(MYSQLI_ASSOC);

          $total_records = $countAll[0]['product_id']; //total number of records
          $total_no_of_pages = ceil($total_records / $total_records_per_page);
          $second_last = $total_no_of_pages - 1; // total page minus 

          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {  // Fetch all the products from the database
            $id = $row['product_id'];
            $name = $row['product_name'];
            $image = $row['product_img'];
            $description = $row['product_description'];
            $price = $row['product_price'];
            $product_quantity = $row['product_qu'];
          ?>
            <tr>
              <td><?php echo $name ?></td>
              <td><img src="<?php echo "../" . $image ?>" width="100px"> </td>
              <td><?php echo $description ?></td>
              <td>₹<?php echo $price ?></td>
              <td><?php echo $product_quantity ?> Pcs</td>
              <td>
                <button onclick="add_cart(<?php echo $id; ?>);" name="Add_To_Cart" class="btn btn-warning">Add to Cart</button>
              </td>
            </tr>
          <?php
          }
          ?>
          <!-- Pagination function added -->
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