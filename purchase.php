<?php
session_start();
// checking if user is logged in or not
if(!isset($_SESSION['customer'])){
  header("Location: login.php");
}
// including database connection file
include('database.php');

// taking user email from session
$u_email = $_SESSION['customer'];

// taking user id from database
$sql = "SELECT * FROM `user` WHERE `user_email` = '$u_email'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$u_id = $row['user_id'];

// checking if form is submitted
if($_SERVER["REQUEST_METHOD"]=="POST")
{
  // checking if purchase button is clicked
  if(isset($_POST['purchase']))
  {
    // Quantity Check
    foreach($_SESSION['cart'] as $key => $value)
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
            window.location.href='cart.php';
          </script>";
          exit();
        }
      }

    // Order Insert
    $query1="INSERT INTO `orders`(`order_user`, `order_status`) VALUES ('$u_id','Placed')";
    if(mysqli_query($conn,$query1))
    {
      $Order_Id=mysqli_insert_id($conn);
      $query2="INSERT INTO `order_p`(`order_id`, `order_item`, `order_qu`) VALUES (?,?,?)";
      $stmt=mysqli_prepare($conn,$query2);
      if($stmt)
      {
        mysqli_stmt_bind_param($stmt,"iii",$Order_Id,$order_item,$order_qu);
        foreach($_SESSION['cart'] as $key => $value)
        {
          $order_item=$value['product_id'];
          $order_qu=$value['Quantity'];
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

        // Unset Session cart
        unset($_SESSION['cart']);
        echo"<script>
          alert('Order Placed');
          window.location.href='index.php';
        </script>";
      }
      else
      {
        echo"<script>
          alert('SQL Query Prepare Error');
          window.location.href='cart.php';
        </script>";
      }
    }
    else
    {
      echo"<script>
        alert('SQL Error');
        window.location.href='cart.php';
      </script>";
    }
  }
}


?>