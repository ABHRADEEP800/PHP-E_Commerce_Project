<?php
session_start();
// checking if user is logged in cookie is set

// including database connection
require('env/database.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">

  <link rel="shortcut icon" href="./assets/images/logo/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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

  <!--
<?php
include 'header.php';
?>

  <!--
    - MAIN
  -->

  <main>

    <!--
      - BANNER
    -->
    <div class="banner">

      <div class="container ">

        <div class="slider-container has-scrollbar">

          <div class="slider-item">

            <img src="assets/image/header2.jpg" alt="" class="banner-img">

            <div class="banner-content">

              <p class="banner-subtitle">New items</p>

              <h2 class="banner-title">New Laptop & Computer accessories </h2>

              <p class="banner-text">
                Use Coupon to get more Discount.
              </p>

              <a href="products.php" class="banner-btn">Shop now</a>

            </div>

          </div>

          <div class="slider-item">

            <img src="assets/image/header1.jpg" alt="" class="banner-img">

            <div class="banner-content">

              <p class="banner-subtitle">New Items.</p>

              <h2 class="banner-title">Modern Furniture and Many more .</h2>

              <p class="banner-text">
                Use Coupon to get more Discount.
              </p>

              <a href="products.php" class="banner-btn">Shop now</a>

            </div>

          </div>
        </div>

      </div>

    </div>
    <!--
      - PRODUCT
    -->
    <div class="product-container">

      <div class="container">


        <!--
          - SIDEBAR
        -->

        <div class="sidebar  has-scrollbar" data-mobile-menu>

          <div class="sidebar-category">

            <div class="sidebar-top">
              <h2 class="sidebar-title">Category</h2>

              <button class="sidebar-close-btn" data-mobile-menu-close-btn>
                <ion-icon name="close-outline"></ion-icon>
              </button>
            </div>

            <ul class="sidebar-menu-category-list">

              <li class="sidebar-menu-category">


                <?php
                $sql = 'SELECT `c_id`,`c_name`  FROM `category` 
           INNER JOIN product ON category.c_id =product.product_category;';
                $result = mysqli_query($conn, $sql);
                $category = mysqli_fetch_all($result, MYSQLI_ASSOC);
                //check in array where c_id matches and count it with its c_name
                $category_count = array_count_values(array_column($category, 'c_name'));
                foreach ($category_count as $key => $value) {
                ?>
                  <a href="products.php?c=<?= $key ?>" class="sidebar-accordion-menu">

                    <p class="menu-title text-black"><?= $key ?></p>

                    <span class="badge bg-secondary rounded-pill"><?= $value ?></span>
                  </a>


                <?php } ?>

              </li>

            </ul>

          </div>

        </div>

        <div class="product-box">

          <!--
            - PRODUCT MINIMAL
          -->

          <div class="product-minimal">

            <div class="product-showcase">

              <h2 class="title">Recently Added</h2>

              <div class="showcase-wrapper ">

                <div class="showcase-container">

                  <?php
                  $sql = "SELECT * FROM product WHERE product_status!='Disable' ORDER BY `product_id` DESC LIMIT 4";
                  $result = mysqli_query($conn, $sql);
                  $product = mysqli_fetch_all($result, MYSQLI_ASSOC);
                  foreach ($product as $key => $value) {
                    $category = $value['product_category'];
                    $sql = "SELECT * FROM category WHERE c_id = $category";
                    $result1 = mysqli_query($conn, $sql);
                    $row1 = mysqli_fetch_assoc($result1);
                    $category_name = $row1['c_name'];
                  ?>


                    <div class="showcase">

                      <a href="product_details.php?id=<?= $value['product_id'] ?>" class="showcase-img-box">
                        <img src="<?= $value['product_img'] ?>" alt="<?= $value['product_name'] ?>" width="70" class="showcase-img">
                      </a>

                      <div class="showcase-content">

                        <a href="product_details.php?id=<?= $value['product_id'] ?>">
                          <h4 class="showcase-title text-bold"><?= $value['product_name'] ?></h4>
                        </a>

                        <span class="showcase-category"><?= $category_name ?></span>

                        <div class="price-box">

                          <p class="price">₹ <?= $value['product_price'] ?></p>
                        </div>

                      </div>

                    </div>
                  <?php } ?>


                  <!-- Trending -->

                </div>

              </div>

            </div>

            <div class="product-showcase">

              <h2 class="title">Trending</h2>

              <div class="showcase-wrapper  ">

                <div class="showcase-container">
                  <?php
                  $sql = "SELECT *, COUNT(*) as COUNT  FROM `order_p` 
                INNER JOIN product ON order_p.order_item =product.product_id
                GROUP BY `order_item` ORDER BY COUNT DESC LIMIT 4
                ";
                  $result = mysqli_query($conn, $sql);
                  $product = mysqli_fetch_all($result, MYSQLI_ASSOC);
                  foreach ($product as $key => $value) {
                    $category = $value['product_category'];
                    $sql = "SELECT * FROM category WHERE c_id = $category";
                    $result1 = mysqli_query($conn, $sql);
                    $row1 = mysqli_fetch_assoc($result1);
                    $category_name1 = $row1['c_name'];
                    $num = $key;
                    $num++;
                  ?>

                    <div class="showcase">
                      <a href="product_details.php?id=<?= $value['product_id'] ?>" class="showcase-img-box">
                        <img src="<?= $value['product_img'] ?>" alt="<?= $value['product_name'] ?>" width="70" class="showcase-img" width="70">
                      </a>

                      <div class="showcase-content">
                        <div class="d-flex justify-content-center">
                          <div class="col-9">
                            <a href="product_details.php?id=<?= $value['product_id'] ?>">
                              <h4 class="showcase-title"><?= $value['product_name'] ?></h4>
                            </a>
                          </div>
                          <div class="col-3">
                            <div class="fw-lighter">Top <?= $num; ?></div>
                          </div>
                        </div>
                        <!-- <div style="width: 20px; border: 1px solid black; height: 20px; position: relative; right: 0px;"></div> -->
                        <span class="showcase-category"><?= $category_name1 ?></span>

                        <div class=" price-box">
                          <p class="price">₹ <?= $value['product_price'] ?></p>

                        </div>


                      </div>

                    </div>

                  <?php } ?>

                </div>



              </div>

            </div>

          </div>



          <!--
            - PRODUCT FEATURED
          -->
          <?php
          if (isset($_SESSION['customer'])) {
            $customer = $_SESSION['customer'];
            $sql = "SELECT * FROM user WHERE user_email = '$customer'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $customer_id = $row['user_id'];

            $sql = "SELECT * FROM search WHERE user_id = $customer_id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            if (isset($row['query'])) {
              $search = $row['query'];
              $sql = "SELECT * FROM product INNER JOIN category ON category.c_id = product.product_category WHERE (product_name LIKE '%$search%' OR product_description LIKE '%$search%'OR category.c_name LIKE '%$search%') AND product_status != 'Disable' LIMIT 4 ";
              $result = mysqli_query($conn, $sql);
              $product = mysqli_fetch_all($result, MYSQLI_ASSOC);




          ?>

              <div class="product-main">

                <h2 class="title">Recommended Products</h2>

                <div class="product-grid">
                  <?php foreach ($product as $value) { ?>


                    <div class="showcase shadow">
                      <a href="product_details.php?id=<?= $value['product_id'] ?>">
                        <div class="showcase-banner">

                          <img src="<?= $value['product_img'] ?>" alt="" class=" product-img default">
                          <img src="<?= $value['product_img'] ?>" alt="" class="product-img hover">

                        </div>
                      </a>
                      <div class="showcase-content">

                        <a href="product_details.php?id=<?= $value['product_id'] ?>" class="showcase-category"><?= $value['c_name'] ?></a>

                        <a href="product_details.php?id=<?= $value['product_id'] ?>">
                          <h3 class="showcase-title"><?= $value['product_name'] ?></h3>
                        </a>

                        <!-- <div class="showcase-rating"> -->
                        <?php
                        // fetching average rating from database 
                        $sql = "SELECT  AVG(`rating`) as rating FROM `review` WHERE`product_id`= {$value['product_id']}";
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
                        <!-- </div> -->

                        <div class="price-box mt-1">
                          <p class="price">₹ <?= $value['product_price'] ?></p>

                        </div>

                      </div>

                    </div>
                  <?php
                  }
                } else {
                  ?>
                  <div class="product-main">
                    <div class="product-main-content">
                      <h2 class="title">Recommended Products</h2>
                      <p class="text-center">Search for products to see Recommended Products</p>
                    </div>
                  </div>
                <?php
                } ?>
                </div>

              </div>
            <?php } else { ?>
              <div class="product-main">
                <div class="product-main-content">
                  <h2 class="title">Recommended Products</h2>
                  <p class="text-center">Login to see Recommended Products</p>
                </div>
              </div>
            <?php } ?>
        </div>
      </div>
    </div>

    <!--
            - PRODUCT GRID
          -->
    </div>

    </div>

    </div>

    <!--
      - TESTIMONIALS, CTA & SERVICE
    -->

    <div>
      <div class="container">

        <div class="testimonials-box">

          <!--
            - TESTIMONIALS
          -->

          <div class="testimonial">

            <h2 class="title">testimonial</h2>

            <div class="testimonial-card">

              <img src="assets/image/abhra.jpg" alt="abhradeep Biswas" class="testimonial-banner" width="80" height="80">

              <p class="testimonial-name">Abhradeep Biswas</p>

              <p class="testimonial-title">CEO & Founder</p>



              <p class="testimonial-desc">
                This Is Our E commerce Website.
              </p>

            </div>

          </div>
          <!--
            - CTA
          -->

          <div class="cta-container">

            <img src="assets/image/down1.jpg" alt="Offer" class="cta-banner">

            <a href="products.php" class="cta-content">

              <p class="discount">5% Discount</p>

              <h2 class="cta-title">Get Extra 5% Discount</h2>

              <p class="cta-text text-black">Use Coupon :- GET5</p>
              <p class="cta-text">Validity :- 31/12/2023</p>

              <button class="cta-btn">Shop now</button>

            </a>

          </div>
          <!--
            - SERVICE
          -->

          <div class="service">

            <h2 class="title">Our Services</h2>

            <div class="service-container">

              <a href="#" class="service-item">

                <div class="service-icon">
                  <ion-icon name="boat-outline"></ion-icon>
                </div>

                <div class="service-content">

                  <h3 class="service-title">Worldwide Delivery</h3>
                  <p class="service-desc">For Order Over ₹50000</p>

                </div>

              </a>

              <a href="#" class="service-item">

                <div class="service-icon">
                  <ion-icon name="rocket-outline"></ion-icon>
                </div>

                <div class="service-content">

                  <h3 class="service-title">Next Day delivery</h3>
                  <p class="service-desc">IN Orders Only</p>

                </div>

              </a>

              <a href="#" class="service-item">

                <div class="service-icon">
                  <ion-icon name="call-outline"></ion-icon>
                </div>

                <div class="service-content">

                  <h3 class="service-title">Best Online Support</h3>
                  <p class="service-desc">Hours: 8AM - 11PM</p>

                </div>

              </a>

              <a href="#" class="service-item">

                <div class="service-icon">
                  <ion-icon name="arrow-undo-outline"></ion-icon>
                </div>

                <div class="service-content">

                  <h3 class="service-title">Return Policy</h3>
                  <p class="service-desc">Easy & Free Return</p>

                </div>
              </a>
            </div>

          </div>

        </div>

      </div>

    </div>
  </main>

  <?php
  include 'footer.php';
  ?>

</body>

</html>