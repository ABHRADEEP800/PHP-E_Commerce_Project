<?php
session_start(); // Start the session
include('database.php');


$u_email = $_POST['user_email'];
$sql = "SELECT * FROM `user` WHERE `user_email` = '$u_email'"; // Check if email exists
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$u_id = $row['user_id'];


if($_SERVER["REQUEST_METHOD"]=="POST") // Check if form is submitted
{
  if(isset($_POST['purchase']))
  {
    $caddress=$_POST['address'].", ".$_POST['state'].", ".$_POST['country']." -".$_POST['zip'];

    // Quantity Check
    foreach($_SESSION['cart'] as $key => $value) // Loop through the cart
      {
        $order_item=$value['product_id'];
        $order_qu=$value['Quantity'];
        $sql = "SELECT * FROM `product` WHERE `product_id` = '$order_item'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $p_qu = $row['product_qu'];
        $p_name = $row['product_name'];
        if($p_qu < $order_qu)
        {
          echo"<script>
            alert('Quantity Exceeded for: $p_name, Unit available: $p_qu Pcs.');
            window.location.href='cnf_order.php';
          </script>";
          exit();
        }
      }
      // Order Insert
    $query1="INSERT INTO `orders`(`order_user`, `order_status`,`shipping_address`) VALUES ('$u_id','Placed','$caddress')";
    if(mysqli_query($conn,$query1))
    {
      $Order_Id=mysqli_insert_id($conn);
      $query2="INSERT INTO `order_p`(`order_id`, `order_item`, `order_qu`, `price`) VALUES (?,?,?,?)";
      $stmt=mysqli_prepare($conn,$query2);
      if($stmt)
      {
        mysqli_stmt_bind_param($stmt,"iiii",$Order_Id,$order_item,$order_qu,$price);
        foreach($_SESSION['cart'] as $key => $value)
        {
          $order_item=$value['product_id'];
          $order_qu=$value['Quantity'];
          $sql = "SELECT * FROM `product` WHERE `product_id` = '$order_item'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $price = $row['product_price'];
          mysqli_stmt_execute($stmt);
        }
        // Quantity Update
        foreach($_SESSION['cart'] as $key => $value)
        {
          $order_item=$value['product_id'];
          $order_qu=$value['Quantity'];
          $sql = "SELECT * FROM `product` WHERE `product_id` = '$order_item'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $p_qu = $row['product_qu'];
          $p_qu = $p_qu - $order_qu;
          $query3="UPDATE `product` SET `product_qu`='$p_qu' WHERE `product_id` = '$order_item'";
          mysqli_query($conn,$query3);
        }
        unset($_SESSION['cart']); // Empty the cart
        echo"<script>
          alert('Order Placed');
          window.location.href='order_mgmt.php';
        </script>";
      }
      // SQL Query Prepare Error
      else
      {
        echo"<script>
          alert('SQL Query Prepare Error');
          window.location.href='cnf_order.php';
        </script>";
      }
    }
    // SQL Error
    else
    {
      echo"<script>
        alert('SQL Error');
        window.location.href='cnf_order.php';
      </script>";
    }
  }
}


?>