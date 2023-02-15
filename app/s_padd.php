<!---seller login check--->
<?php
session_start();
if (!isset($_SESSION['seller'])) {
    header('location: /login.php');
  exit;
}


include 'database.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"/>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
    ></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
    ></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
    <link rel="stylesheet" href="asset/card.css" />
</head>
<?php
    include 'navbar_s.php';
    include 'database.php';     
?>
<body>
  <!----product add form--->
        <div class="container ">
            <form method="post" enctype="multipart/form-data">
                <div class="px-auto">
                    <h1 class="text-center"  >Add New Product</h1>
                </div>
                
                <div class="flex mx-auto col-6 " >
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
                    <!--  -->
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
                    <button type="submit" name="add_product" class="btn btn-primary btn-block col-3 mb-4">Add Product</button>
                </div>
            </form>
        </div>
    
        <?php
            
        ?>
<!--php to add new product--->
<?php
if(isset($_POST["add_product"])) {
    $target_dir = "product/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $product_name=$_POST["product_name"];
    $product_price=$_POST["product_price"];
    $product_description=$_POST["product_description"];
    $product_qu=$_POST["product_qu"];
    
  // Check if file already exists
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }
  // Allow certain file formats
  if($fileType != "png" && $fileType != "jpeg" && $fileType != "jpg") {
    echo "Sorry, only PNG, JPEG & JPG files are allowed.";
    $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $semail=$_SESSION['seller'];
        $sql="SELECT * FROM `user` WHERE `user_email`='$semail'";
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_assoc($result);
        $user_id=$row['user_id'];
        $product_img=$target_file;
        $sql="INSERT INTO `product`(`product_name`, `product_price`, `product_description`, `product_img`, `product_userid`, `product_qu`) VALUES ('$product_name','$product_price','$product_description','$product_img','$user_id', '$product_qu')";
        $result=mysqli_query($conn,$sql);
        if($result){
            echo "<script>location.href='s_pmgmt.php'</script>";
        }
        else{
            echo "Product Not Added";
        }
        
      
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
//   
}
?>

</body>
</html>