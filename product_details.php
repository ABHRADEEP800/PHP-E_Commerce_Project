<?php
// checking if user is logged in cookie is set
session_start();
// including database connection
require "env/database.php";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Product Details</title>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
    <script src="assets/js/script.js" type></script>
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
    <!------------------------------- header ------------------------------------------------------------------------->
    <?php // including header
    include "header.php"; ?>
    <!------------------------------------BODY------------------------------------------------------------------------->

    <?php
    $abhradeep = "";
    // checking if product id is set
    if (isset($_GET["id"])) {
        // storing product id in variable
        $product_id = $_GET["id"];
        // fetching product details from database
        $sql = "SELECT * FROM product
        INNER JOIN category ON product.product_category = category.c_id
         WHERE product.product_id = '$product_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        // storing product details in variables
        $product_name = $row["product_name"];
        $category_id = $row["product_category"];
        $product_price = $row["product_price"];
        $product_image = $row["product_img"];
        $product_description = $row["product_description"];
        $product_category = $row["c_name"];
        $product_qu = $row["product_qu"];
        // fetching average rating from database
        $sql2 = "SELECT  AVG(`rating`) as rating FROM `review` WHERE`product_id`= $product_id";
        $result1 = mysqli_query($conn, $sql2);
        $row1 = mysqli_fetch_assoc($result1);
        $rating = $row1["rating"];
        $rating = round($rating, 1);
    }
    ?>
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12 p_detl_div">
                        <img src="<?php echo $product_image; ?>" alt="" class="p_image">
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="px-4">
                            <h3 class="text-black"><?= $product_name ?></h3>
                            <div class="mb-2">
                                <small class="text-muted me-3"><?php echo $product_category; ?></small>
                                <?php if ($rating >= 3.5) { ?>
                                    <div class="rate_icon bg-success">
                                        <p class=""><?php echo $rating; ?> <i class="fas fa-star"></i></p>
                                    </div>
                                <?php
                                } elseif ($rating >= 2) { ?>
                                    <div class="rate_icon bg-warning">
                                        <p class=""><?php echo $rating; ?> <i class="fas fa-star"></i></p>
                                    </div>
                                <?php
                                } elseif ($rating >= 1) { ?>
                                    <div class="rate_icon bg-danger">
                                        <p class=""><?php echo $rating; ?> <i class="fas fa-star"></i></p>
                                    </div>
                                <?php
                                } else {
                                    echo "<p class='text-muted'>No reviews yet</p>";
                                } ?>
                                <p class="h5 mt-2">₹ <?php echo $product_price; ?>/-</p>
                            </div>
                            <div class="mb-2">
                                <?php if ($product_qu == 0) {
                                    echo "<strong class='text-danger'>Sorry! This product is out of stock </strong>";
                                    $abhradeep = "disabled";
                                } elseif ($product_qu <= 5) {
                                    echo "<strong class='text-danger'>Hurry! Only $product_qu Pcs Left. </strong>";
                                } ?>
                            </div>
                            <div class="mb-3">

                                <button onclick="add_cart(<?php echo $product_id; ?>);" name="Add_To_Cart" class="btn btn-warning" <?= $abhradeep ?>>Add to Cart <i class='fa-solid fa-cart-plus'></i></button>

                            </div>
                            <nav>
                                <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                    <button class="nav-link active text-black" id="nav-desc-tab" data-bs-toggle="tab" data-bs-target="#nav-desc" type="button" role="tab" aria-controls="nav-desc" aria-selected="true">Product Description</button>
                                    <button class="nav-link text-black" id="nav-rev-tab" data-bs-toggle="tab" data-bs-target="#nav-rev" type="button" role="tab" aria-controls="nav-rev" aria-selected="false">Product Reviews</button>
                                </div>
                            </nav>
                            <div class="tab-content p-3 border bg-light" id="nav-tabContent">
                                <div class="tab-pane fade active show" id="nav-desc" role="tabpanel" aria-labelledby="nav-desc-tab">
                                    <p><?= $product_description ?></p>
                                </div>
                                <div class="tab-pane fade" id="nav-rev" role="tabpanel" aria-labelledby="nav-rev-tab">
                                    <div style="overflow-y: scroll; overflow-x:hidden; height: 120px;">
                                        <?php
                                        $sql = "SELECT * FROM review 
                                                  INNER JOIN user ON review.user_id = user.user_id
                                                  WHERE review.product_id = $product_id";
                                        $result = mysqli_query($conn, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <div class="review mb-2">
                                                <div class="mb-2 row">
                                                    <div class="col-2 user_logo">
                                                        <i class="fa-solid fa-user fa-2xl" style="color: #c2c2c2;"></i>
                                                    </div>
                                                    <div class="col-10">
                                                        <strong class="h6 mt-2"><?php echo $row["user_name"]; ?></strong><br>
                                                        <small><?= $row["timestamp"] ?></small>
                                                    </div>

                                                </div>

                                                <div class="mb-2">
                                                    <?php
                                                    $rating = $row["rating"];
                                                    if ($rating == 5) {
                                                        echo "<p class='h6 mt-2'>$rating<i class='ps-1 fas fa-star text-success'></i> Excellent</p>";
                                                    } elseif ($rating == 4) {
                                                        echo "<p class='h6 mt-2'>$rating<i class='ps-1 fas fa-star text-success'></i> Good</p>";
                                                    } elseif ($rating == 3) {
                                                        echo "<p class='h6 mt-2'>$rating<i class='ps-1 fas fa-star text-warning'></i> Nice</p>";
                                                    } elseif ($rating == 2) {
                                                        echo "<p class='h6 mt-2'>$rating<i class='ps-1 fas fa-star text-warning'></i> Ok</p>";
                                                    } elseif ($rating == 1) {
                                                        echo "<p class='h6 mt-2'>$rating<i class='ps-1 fas fa-star text-danger'></i> Bad</p>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-3 mb-2">
        <div class="card shadow">
            <div class="card-body">
                <div class="card-title text-black text-center mb-3">
                    <h3>Related Products</h3>
                </div>
                <div class="overflow-auto">
                    <div class="row">

                        <?php
                        $sql = "SELECT * FROM `product` WHERE `product_category` = $category_id && NOT `product_id` = $product_id && product_status!='Disable'";
                        $result = mysqli_query($conn, $sql);
                        // showing products in loop
                        while ($row = mysqli_fetch_assoc($result)) {
                            // storing data in variables
                            $id = $row["product_id"];
                            $name = $row["product_name"];
                            $image = $row["product_img"];
                            $description = $row["product_description"];
                            $price = $row["product_price"];
                            $category = $row["product_category"];
                            $product_qu = $row["product_qu"];
                            $sql = "SELECT * FROM category WHERE c_id = $category";
                            $result1 = mysqli_query($conn, $sql);
                            $row1 = mysqli_fetch_assoc($result1);
                            $category_name = $row1["c_name"];
                        ?>
                            <div class="my-1 col-lg-3 col-md-4 col-sm-6">

                                <div class="">
                                    <div class="card main_card shadow">
                                        <a class="text-dark" href="product_details.php?id=<?php echo $id; ?>">
                                            <div class="card-body">
                                                <div class="img_div ">
                                                    <img src="<?php echo $image; ?>" alt="" class="p_image">
                                                </div>
                                                <p class="p-title"><?php echo $name; ?></p>
                                        </a>
                                        <div class="row">
                                            <div class="col-5">
                                                <?php
                                                // fetching average rating from database
                                                $sql = "SELECT  AVG(`rating`) as rating FROM `review` WHERE`product_id`= $id";
                                                $result1 = mysqli_query($conn, $sql);
                                                $row1 = mysqli_fetch_assoc($result1);
                                                $rating = $row1["rating"];
                                                $rating = round($rating, 1);
                                                // showing average rating
                                                // echo $rating . " <i class='fas fa-star text-warning'></i> <br>";

                                                ?>
                                                <?php if ($rating >= 3.5) { ?>
                                                    <div class="rate_icon bg-success">
                                                        <p class=""><?php echo $rating; ?> <i class="fas fa-star"></i></p>
                                                    </div>
                                                <?php
                                                } elseif ($rating >= 2) { ?>
                                                    <div class="rate_icon bg-warning">
                                                        <p class=""><?php echo $rating; ?> <i class="fas fa-star"></i></p>
                                                    </div>
                                                <?php
                                                } elseif ($rating >= 1) { ?>
                                                    <div class="rate_icon bg-danger">
                                                        <p class=""><?php echo $rating; ?> <i class="fas fa-star"></i></p>
                                                    </div>
                                                <?php
                                                } else {
                                                    echo "<p class='text-muted'>No reviews yet</p>";
                                                } ?>
                                                <small class="text-muted"><?php echo $category_name; ?></small>
                                                <p>₹ <?php echo $price; ?></p>
                                            </div>
                                            <div class="col-7">
                                                <?php if ($product_qu == 0) {
                                                    echo "<strong class='text-danger'>Sorry! This product is out of stock </strong>";
                                                } elseif ($product_qu <= 5) {
                                                    echo "<strong class='text-danger'>Hurry! Only $product_qu Pcs Left. </strong>";
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-center">
                                                <?php if ($product_qu == 0) {
                                                    echo "<button type='submit' name='Add_To_Cart' class='btn btn-warning ' style='width: 300px;' disabled>Add to Cart <i class='fa-solid fa-cart-plus fa-lg'></i></button>
                                                                          <input type='hidden' name='product_id' value='$id'>
                                                                          <input type='hidden' name='page' value='product_details'>
                                                                          <input type='hidden' name='product_id_get' value='?id=$product_id'>";
                                                } else {
                                                    echo "<button onclick='add_cart($id);' name='Add_To_Cart'
                                                            class='btn btn-warning ' style='width: 300px;'>Add to Cart
                                                            <i class='fa-solid fa-cart-plus fa-lg'></i></button>
                                                        ";
                                                } ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                    </div>
                <?php
                        }
                        // end of while loop
                ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-----------------------------footer  ---------------------------->
    <?php // including footer
    include "footer.php";
    ?>
</body>

</html>