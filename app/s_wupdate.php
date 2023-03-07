<?php
session_start(); // Start the session
if (!isset($_SESSION['seller'])) {
    header('location: /login.php');
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
    <link rel="stylesheet" href="asset/css/main.css" />
</head>
<?php
    include 'navbar_s.php';
    include 'database.php';     
?>
<body>


<?php
        // Get user data
        $id=$_GET['userId'];
         
            
          
        ?>

        <div class="container ">
            <form method="post" enctype="multipart/form-data">
                <div class="px-auto">
                    <h1 class="text-center">Update Warehouse Data</h1>
                </div>
                
                <div class="flex mx-auto col-lg-6 col-sm-12" >
                <div class="mb-3">
              <label for="address">Address</label>
              <input type="text" class="form-control" id="address"name="address"  placeholder="1234 Main St" required>
              <div class="invalid-feedback">
                Please enter your shipping address.
              </div>
            </div>


            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="country">Country</label>
                <select class="custom-select d-block w-100" name="country" id="country" required>
                  <option value="">Choose...</option>
                  <option value="United States">United States</option>
                </select>
                <div class="invalid-feedback">
                  Please select a valid country.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="state">State</label>
                <select class="custom-select d-block w-100"name="state" id="state" required>
                  <option value="">Choose...</option>
                  <option value="California" >California</option>
                </select>
                <div class="invalid-feedback">
                  Please provide a valid state.
                </div>
              </div>
              
              <div class=" mb-3">
                <label for="zip">Zip</label>
                <input type="text" class="form-control" id="zip" name="zip" placeholder="" required>
                <div class="invalid-feedback">
                  Zip code required.
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
// Update user data and warehouse address
if(isset($_POST["update"])) {
    $caddress=$_POST['address'].", ".$_POST['state'].", ".$_POST['country']." -".$_POST['zip'];
    $sql = "UPDATE user SET warehouse='$caddress' WHERE user_id='$id'";
    // If Warehouse data updated successfully
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Warehouse Updated Successfully!')</script>";
        echo "<script>window.location.href='s_warehouse.php'</script>";
    }
    // If Warehouse data not updated 
    else {
        echo "Error updating record: " . mysqli_error($conn);
}
}
?>
</body>
</html>