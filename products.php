<?php
session_start();
// checking if user is logged in cookie is set

// including database connection
require('env/database.php');
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Product</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
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
  <!------------------------------- header -------------------------- -->
  <?php
  // including header
  include 'header.php';
  if (isset($_SESSION['customer'])) {
    //get user email from session 
    $email = $_SESSION['customer'];
    //sql query to select user details
    $sql = "SELECT * FROM user WHERE user_email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    //get user id
    $user_id = $user['user_id'];
  }




  $sql = 'SELECT `c_id`,`c_name`  FROM `category` 
      INNER JOIN product ON category.c_id =product.product_category;';
  $result = mysqli_query($conn, $sql);
  $category = mysqli_fetch_all($result, MYSQLI_ASSOC);
  //check in array where c_id matches and count it with its c_name
  $category_count = array_count_values(array_column($category, 'c_name'));



  ?>
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-4 ">
        <div class="make-me-sticky pt-3">
          <div class="card shadow">
            <div class="card-body">
              <h3 class="card-title h3">Product Category</h3>
              <?php
              foreach ($category_count as $key => $value) {
              ?>
                <ul class="list-group text-gray">
                  <li class="list-group-item d-flex justify-content-between align-items-center border-0 py-1 px-0 small">
                    <a href="products.php?c=<?= $key ?>" class="text-black h6 text-decoration-none"><?= $key ?></a>
                    <span class="badge bg-secondary rounded-pill"><?= $value ?></span>
                  </li>

                </ul>
              <?php
              }
              ?>
            </div>
          </div>

          <div class="card shadow mt-2 mb-2">
            <div class="card-body">
              <h3 class="h5 card-title">Price Range</h3>
              <form action="products.php" method="get">
                <div class="d-flex mb-3">
                  <div class="col-md-6 me-2">
                    <label for="priceRangeMin1" class="form-label">Min</label>
                    <input class="form-control" id="priceRangeMin1" name="min" required placeholder="₹0" type="number">
                  </div>
                  <div class="col-md-6 text-md-end">
                    <label for="priceRangeMax1" class="form-label">Max</label>
                    <input class="form-control" id="priceRangeMax1" name='max' placeholder="₹1,0000" required type="number">
                  </div>
                </div>
                <div class="d-grid">
                  <button type="submit" name="pricer" class="btn btn-outline-tertiary">Apply</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-9 pt-3 ">

        <?php
        $grapple = null;
        $msg = null;

        if (isset($_GET['page']) && $_GET['page'] != "") {
          $page_no = $_GET['page'];
        } else {
          $page_no = 1;
        }

        $total_records_per_page = 10;
        $offset = ($page_no - 1) * $total_records_per_page;
        $previous_page = $page_no - 1;
        $next_page = $page_no + 1;
        $adjacents = "2";

        if (isset($_GET['c'])) {
          $category = $_GET['c'];
          $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product WHERE product_category = (SELECT c_id FROM category WHERE c_name = '$category')");
          $sql = "SELECT * FROM `product` 
      INNER JOIN category ON category.c_id = product.product_category
      WHERE category.c_name = '$category' LIMIT $offset, $total_records_per_page;";
          $msg = "Showing Results For :- '" . $category . " ' Category.";
          $grapple = "&c=$category";
        } elseif (isset($_GET['pricer'])) {
          $min = $_GET['min'];
          $max = $_GET['max'];
          $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product WHERE product_price BETWEEN $min AND $max");
          $sql = "SELECT * FROM `product` WHERE product_price BETWEEN $min AND $max LIMIT $offset, $total_records_per_page;";
          $msg = "Showing Results For :- '₹" . $min . " - ₹" . $max . " ' Price Range.";
          $grapple = "&pricer&min=$min&max=$max";
        } elseif (isset($_GET['search'])) {
          $search = $_GET['search'];
          if (isset($_SESSION['customer'])) {
            //get user email from session
            $email = $_SESSION['customer'];
            //sql query to select user details
            $sql = "SELECT * FROM user WHERE user_email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_assoc($result);
            //get user id
            $user_id = $user['user_id'];
            $sql = "SELECT * FROM search  WHERE `user_id`='$user_id' ";
            $result = mysqli_query($conn, $sql);
            //get number of rows
            $rowcount = mysqli_num_rows($result);
            if ($rowcount === 0) {
              $sql = "INSERT INTO `search`(`id`, `user_id`, `query`) VALUES (null,'$user_id','$search') ";
              mysqli_query($conn, $sql);
            } else {
              $sql = "UPDATE `search` SET `query`='$search' WHERE `user_id`='$user_id' ";
              mysqli_query($conn, $sql);
            }
          }
          $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product INNER JOIN category ON category.c_id = product.product_category
      WHERE (product_name LIKE '%$search%' OR product_description LIKE '%$search%'OR category.c_name LIKE '%$search%')AND product_status != 'Disable'");
          $sql = "SELECT * FROM product 
      INNER JOIN category ON category.c_id = product.product_category
      WHERE (product_name LIKE '%$search%' OR product_description LIKE '%$search%'OR category.c_name LIKE '%$search%')
      AND product_status != 'Disable' LIMIT $offset, $total_records_per_page";
          $msg = "Showing Results For :- '$search' ";
          $grapple = "&search=$search";
        } else {

          $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product");

          $sql = "SELECT * FROM `product` LIMIT $offset, $total_records_per_page";
          $msg = '';
        }
        $countAll = $result1->fetch_all(MYSQLI_ASSOC);
        $total_records = $countAll[0]['product_id'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
        $second_last = $total_no_of_pages - 1; // total page minus 1
        $result = mysqli_query($conn, $sql);
        if ($msg !== '') {
          echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
    $msg
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  </div>
  <script>
  setTimeout(function(){
    $('.alert').alert('close');
  }, 5000);
  </script>

     
    ";
        }
        while ($row = mysqli_fetch_assoc($result)) {
          $id = $row['product_id'];
          $product_qu = $row['product_qu']
        ?>


          <div class="card shadow p-4 mb-2">
            <div class="row align-items-center">
              <div class="col-md-3">
                <a href="product_details.php?id=<?= $id ?>">
                  <img src="<?= $row['product_img'] ?>" alt="" class="img-fluid">
                </a>
              </div>
              <div class="col-md-6">
                <div class="info-main">
                  <a href="product_details.php?id=<?= $id ?>" class="h5 text-black title"><?= $row['product_name'] ?></a>
                  <div class="d-flex my-3">
                    <?php
                    // fetching average rating from database 
                    $sql = "SELECT  AVG(`rating`) as rating FROM `review` WHERE`product_id`= $id";
                    $result1 = mysqli_query($conn, $sql);
                    $row1 = mysqli_fetch_assoc($result1);
                    $rating = $row1['rating'];
                    $rating = round($rating, 1);
                    // showing average rating
                    // echo $rating . " <i class='fas fa-star text-warning'></i> <br>";
                    ?>
                    <?php
                    if ($rating >= 3.5) {
                    ?>
                      <div class="rate_icon bg-success">
                        <p class=""><?php echo $rating; ?> <i class="fas fa-star"></i></p>
                      </div>
                    <?php
                    } elseif ($rating >= 2) {
                    ?>
                      <div class="rate_icon bg-warning">
                        <p class=""><?php echo $rating; ?> <i class="fas fa-star"></i></p>
                      </div>
                    <?php
                    } elseif ($rating >= 1) {
                    ?>
                      <div class="rate_icon bg-danger">
                        <p class=""><?php echo $rating; ?> <i class="fas fa-star"></i></p>
                      </div>
                    <?php
                    } else {
                      echo "<p class='text-muted'>No reviews yet</p>";
                    }
                    ?>

                  </div>
                  <p><?= $row['product_description'] ?></p>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="d-flex align-items-center">
                  <span class="h5 mb-0 text-gray  me-2">₹<?= $row['product_price'] ?></span>

                </div>
                <span class="text-success small"><span class="fas fa-shipping-fast me-1"></span>Free shipping</span>
                <div class="d-grid gap-2 mt-4">

                  <?php

                  if ($product_qu == 0) {
                    echo "<strong class='text-danger  '>Sorry! This product is out of stock </strong>";
                  } elseif ($product_qu <= 5) {
                    echo "<strong class='text-danger '>Hurry! Only $product_qu Pcs Left. </strong>";
                  }
                  ?>

                  <?php
                  if ($product_qu == 0) {
                    echo "<button type='submit' name='Add_To_Cart' class='btn btn-warning mt-1' ' disabled>Add to Cart <i class='fa-solid fa-cart-plus fa-lg'></i></button>
                          ";
                  } else {
                    echo "<button onclick='add_cart($id);' name='Add_To_Cart' class='btn btn-warning mt-1 ' '>Add to Cart <i class='fa-solid fa-cart-plus fa-lg'></i></button>
                          ";
                  }
                  ?>

                </div>
              </div>
            </div>
          </div>
        <?php
        }
        ?>


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


    </div>
  </div>


  <!-----------------------------footer  ---------------------------->
  <?php
  // including footer
  include 'footer.php';
  ?>
</body>

</html>