<?php
session_start(); // Start the session
if (!isset($_SESSION['admin'])) { // If no session value is present, redirect the user:
    header('location: /admin_login.php');
  exit;
}
include 'database.php'; // Include the database connection.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
    include 'navbar.php';
    include 'database.php';     
?>

<body>
<?php
      // product edit
        $id=$_GET['productId'];
        $sql="SELECT * FROM product WHERE product_id=$id";
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_assoc($result);
           
            $name=$row['product_name'];
            $image=$row['product_img'];
            $description=$row['product_description'];
            $category=$row['product_category'];
            $price=$row['product_price'];
            $quantity=$row['product_qu'];
            $sql="SELECT * FROM category WHERE c_id=$category";
            $result=mysqli_query($conn,$sql);
            $row=mysqli_fetch_assoc($result);
            $p_category=$row['c_name'];
        ?>

        <div class="container ">
            <form method="post" enctype="multipart/form-data">
                <div class="px-auto">
                    <h1 class="text-center"  >Edit Product</h1>
                </div>
                <!-- image -->
                <div class="mt-2 d-flex justify-content-center">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <img src="<?php echo $image; ?>" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex mx-auto col-lg-6 col-sm-12 " >
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example1">Product Name</label>
                        <input type="text" name="product_name" id="form4Example1" value="<?=$name?>" class="form-control" />
                    </div>

                   
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example2">Price</label>
                        <input type="text" name="product_price" id="form4Example2" value="<?=$price?>" class="form-control" />
                        
                    </div>

                   
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example3">Description</label>
                        <input class="form-control" name="product_description" id="form4Example3" value="<?=$description?>" rows="4"></input>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example3">Quantity(Pcs)</label>
                        <input class="form-control" name="product_qu" id="form4Example3" value="<?= $quantity?>" rows="4"></input>
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example3">Product Category</label>
                          <select name="ptype" class="form-select" aria-label="select example">
                                <option value="<?=$category?>"><?=$p_category?></option>
                                <?php
                                    // category dropdown
                                    $sql="SELECT * FROM category";
                                    $result=mysqli_query($conn,$sql);
                                    while($row=mysqli_fetch_assoc($result)){
                                        $c_name=$row['c_name'];
                                        $c_id=$row['c_id'];
                                        if ($c_id==$category) {
                                            continue;
                                        }
                                        echo "<option value='$c_id'>$c_name</option>";
                                    }
                                ?>
                          </select>
                    </div>
                </div>
                <!-- file upload -->
                <div class="d-flex pt-1 justify-content-center">
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body ">
                            <label class="form-label d-flex justify-content-center" for="form4Example3">Change Image</label>
                                <input type="file" name="fileToUpload" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit button -->
                <div class="d-flex justify-content-center">
                    <button type="submit" name="update" class="btn btn-primary btn-block  mb-4">Update</button>
                </div>
                
            </form>
        </div>
    
        <?php
            
        ?>

<?php
// Check 
if(isset($_POST["update"])) { 
  $p_cat=$_POST["ptype"];
  $target_dir = "product/"; 
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); // image name
  $uploadOk = 1; 
  $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // image type
  $product_name=$_POST["product_name"];
    $product_price=$_POST["product_price"];
    $product_description=$_POST["product_description"];
    $product_qu=$_POST["product_qu"];
    $product_img=$image;
    
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
        $product_img=$target_file;
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
  $sql= "UPDATE product SET  product_name ='$product_name' , product_price='$product_price',product_description ='$product_description',product_img='$product_img', product_qu='$product_qu', product_category='$p_cat'  WHERE product_id ='$id';";
      mysqli_query($conn,$sql);
      echo'<script>
      window.location.href="product_mgmt.php";
      </script>';
}
?>

</body>
</html>