<?php
session_start();

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
    $discount = $_POST['discount'];
    $adress=$_SESSION['address'];
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
        $p_price = $row['product_price'];
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
    $query1="INSERT INTO `orders`(`order_user`, `order_status`, `discount`,`shipping_address`) VALUES ('$u_id','Placed', '$discount','$adress')";
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

        // Unset Session cart
        unset($_SESSION['cart']);
        unset($_SESSION['address']);
        echo"<script>
          window.location.href='order_cnf.php?order_id=$Order_Id';
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