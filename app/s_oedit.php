<!---check seller login--->
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

<!--- order data fetch--->
<?php
 $semail=$_SESSION['seller'];
 $sql="SELECT * FROM `user` WHERE `user_email`='$semail'";
 $result=mysqli_query($conn,$sql);
 $row=mysqli_fetch_assoc($result);
 $user_id=$row['user_id'];

 $id=$_GET['orderId'];
 $sql="SELECT  user.user_name, product.product_name, order_p.order_qu, orders.order_date, orders.order_status, orders.order_id
         FROM orders
         INNER JOIN user ON user.user_id=orders.order_user
         INNER JOIN order_p ON order_p.order_id=orders.order_id
         INNER JOIN product ON product.product_id=order_p.order_item
         WHERE orders.order_id='$id' AND product.product_userid ='$user_id' ";
     $result=mysqli_query($conn,$sql);
while ($row = mysqli_fetch_assoc($result)) {
$id = $row['order_id'];
$user_name = $row['user_name'];
$order_date = $row['order_date'];
$order_status = $row['order_status'];
?>
<!----order data show--->
 <div class="container ">
     <form method="post" enctype="multipart/form-data">
         <div class="px-auto">
             <h1 class="text-center">Edit Ordered Data</h1>
         </div>
         
         <div class="flex mx-auto col-6 " >
             <div class="form-outline mb-4">
                 <label class="form-label h3" for="form4Example1">User Name</label>
                 <input type="text" disabled id="form4Example1" value="<?= $user_name ?>" class="form-control" />
             </div>
             <div class="form-outline mb-4">
                 <label class="form-label h3" for="form4Example2">Ordered Date</label>
                 <input type="text" disabled id="form4Example2" value="<?= $order_date ?>" class="form-control" />
             </div>
             <div class="form-outline mb-4">
             <table class="table"  >

             <tr>
              <th>Product Name</th>
               <th>Quantity</th>
                  </tr>
                  <?php
                 $sql="SELECT product.product_name, order_p.order_qu
                  FROM order_p
                   INNER JOIN product ON product.product_id=order_p.order_item
                    WHERE order_p.order_id='$id' AND product.product_userid ='$user_id'";
                    $result=mysqli_query($conn,$sql);
                    while($row=mysqli_fetch_assoc($result)){
                    $product_name=$row['product_name'];
                    $product_quantity=$row['order_qu'];

                    echo" <tr>
                   <td>$product_name</td>
                  <td>$product_quantity Pcs</td>
                     </tr>";
                    }
                    ?>
                  </table>
        
         <div class="form-outline mb-4">
                 <label class="form-label h3" for="form4Example3">Order Status</label>
                   <select name="order_status" class="form-select" aria-label="select example">
                         <option value="<?= $order_status ?>"><?= $order_status ?></option>
                         <?php
                         if ($order_status == "Placed" || $order_status != "Delivered") {
                             echo "<option value='Shipped'>Shipped</option>";
                             echo "<option value='Delivered'>Delivered</option>";
                         } else {
                             echo "<option value='Delivered'>Delivered</option>";
                         }
}
                         ?>
                   </select>
             </div>
         </div>
                <!-- Submit button -->
                <div class="d-flex justify-content-center">
                    <button type="submit" name="update" class="btn btn-primary btn-block col-4 mb-4">Update Order Status</button>
                </div>
            </form>
        </div>
        <?php
   
        ?>
        <!----order data update--->
<?php
if(isset($_POST["update"])) {
    $order_status=$_POST['order_status'];
    $sql= "UPDATE orders SET  order_status ='$order_status' WHERE order_id ='$id';";
    mysqli_query($conn,$sql);
    echo'<script>
    window.location.href="s_omgmt.php";
    </script>';
}
?>
</body>
</html>