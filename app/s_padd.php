<!---seller login check--->
<?php
session_start(); // Starting Session
if (!isset($_SESSION['seller'])) { //if not logged in
  header('location:  ../login.php'); // Redirecting To Home Page
  exit; // stop further executing, very important
}
// database connection
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
  <link rel="stylesheet" href="../assets/css/main.css" />
  <link rel="stylesheet" href="asset/css/main.css" />
  <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<?php
include 'navbar_s.php';
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

  <!----product add form--->
  <div class="container ">
    <form method="post" enctype="multipart/form-data">
      <div class="px-auto">
        <h1 class="text-center">Add New Product</h1>
      </div>

      <div class="flex mx-auto col-lg-6 col-sm-12">
        <div class="form-outline mb-4">
          <label class="form-label" for="form4Example1">Product Name</label>
          <input type="text" name="product_name" id="form4Example1" placeholder="Enter Product Name" class="form-control" />
        </div>


        <div class="form-outline mb-4">
          <label class="form-label" for="form4Example2">Price</label>
          <input type="text" name="product_price" id="form4Example2" placeholder="Enter Product Price" class="form-control" />

        </div>


        <div class="form-outline mb-4">
          <label class="form-label" for="form4Example3">Description</label>
          <input class="form-control" name="product_description" id="form4Example3" placeholder="Enter Product Description" rows="4"></input>
        </div>
        <div class="form-outline mb-4">
          <label class="form-label" for="form4Example3">Product Category</label>
          <select name="ptype" class="form-select" aria-label="select example">
            <option selected value="select">Select Category</option>
            <?php
            $sql = "SELECT * FROM category";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
              $c_name = $row['c_name'];
              $c_id = $row['c_id'];
              echo "<option value='$c_id'>$c_name</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-outline mb-4">
          <label class="form-label" for="form4Example3">Quantity(Pcs)</label>
          <input class="form-control" name="product_qu" id="form4Example3" placeholder="Enter Product Quantity" rows="4"></input>
        </div>
      </div>
      <!-- file upload -->
      <div class="d-flex pt-1 justify-content-center">

        <div class="col-md-4">
          <div class="card">
            <div class="card-body ">
              <label class="form-label d-flex justify-content-center" for="form4Example3">Add Product Image</label>
              <input type="file" name="fileToUpload" class="form-control">
            </div>
          </div>
        </div>
      </div>

      <!-- Submit button -->
      <div class="d-flex justify-content-center">
        <button type="submit" name="add_product" class="btn btn-primary btn-block mb-4">Add Product</button>
      </div>
    </form>
  </div>

  <?php

  ?>
  <!--php to add new product--->
  <?php
  if (isset($_POST["add_product"])) {
    if ($_POST["ptype"] == "select") {
      echo "<script>alert('Please Select Category')</script>";
      exit;
    }
    $p_cat = $_POST["ptype"];

    $file_name = $_FILES['fileToUpload']['name']; // Get the file name
    $target_dir = "product/"; // Set the destination folder
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION); // Get the file extension
    $target_file = $target_dir . RandomString(20) . "_" . date("jmYHis") . "." . $file_ext; // Set the file path & name
    $uploadOk = 1; // Set the flag
    $fileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // Get the file extension
    $product_name = $_POST["product_name"]; // Get the product name
    $product_price = $_POST["product_price"];
    $product_description = $_POST["product_description"]; // Get the product Description
    $product_qu = $_POST["product_qu"]; // Get the product Quantity


    // Check if file already exists
    if (file_exists($target_file)) {
      echo "<script>
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr['error']('Sorry, file already exists!'); 
    </script>";
      $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
      echo "<script>
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr['error']('Sorry, your file is too large!'); 
    </script>";
      $uploadOk = 0;
    }
    // Allow certain file formats
    if ($fileType != "png" && $fileType != "jpeg" && $fileType != "jpg") {
      echo "<script>
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr['error']('Sorry, only PNG, JPEG & JPG files are allowed!'); 
    </script>";
      $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "<script>
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr['error']('Sorry, your file was not uploaded!'); 
    </script>";
      // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../" . $target_file)) {
        $semail = $_SESSION['seller'];
        $sql = "SELECT * FROM `user` WHERE `user_email`='$semail'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];
        $product_img = $target_file;
        $sql = "INSERT INTO `product`(`product_name`, `product_price`, `product_description`, `product_img`, `product_userid`, `product_qu`,`product_category`) VALUES ('$product_name','$product_price','$product_description','$product_img','$user_id', '$product_qu', '$p_cat')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
          echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['success']('Product Added Successfully');
            setTimeout(function(){ window.location.href='s_pmgmt.php'; }, 5000);
            </script>";
        } else {
          echo "<script>
          toastr.options.closeButton = true;
          toastr.options.progressBar = true;
          toastr['error']('Product Not Added!'); 
          </script>";
        }
      } else {
        echo "<script>
      toastr.options.closeButton = true;
      toastr.options.progressBar = true;
      toastr['error']('Sorry, there was an error uploading your file!'); 
      </script>";
      }
    }
    //   
  }
  // random string function
  function RandomString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  ?>

</body>

</html>