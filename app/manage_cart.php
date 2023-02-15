<?php 
session_start(); // Start the session
if(!isset($_SESSION['admin'])){
  header("Location: /admin_login.php");
}


if($_SERVER["REQUEST_METHOD"]=="POST") // check if form is submitted
{
  if(isset($_POST['Add_To_Cart']))
  {
    if(isset($_SESSION['cart']))
    {
      $myitems=array_column($_SESSION['cart'],'product_id');
      if(in_array($_POST['product_id'],$myitems))
      {
        echo"<script>
          alert('Item Already Added');
          window.location.href='add_order.php';
        </script>";
      }
      else
      {
        $count=count($_SESSION['cart']);
        $_SESSION['cart'][$count]=array('product_id'=>$_POST['product_id'],'Quantity'=>1); // Add the product to the cart.
        echo"<script>
          alert('Item Added to Cart');
          window.location.href='add_order.php';
        </script>";
      }
    }
    else
    {
      $_SESSION['cart'][0]=array('product_id'=>$_POST['product_id'],'Quantity'=>1); // Add the product to the cart.
      echo"<script>
        alert('Item Added to Cart');
        window.location.href='add_order.php';
      </script>";
    }
  }
  if(isset($_POST['Remove_Item'])) // Remove the product from the cart.
  {
    foreach($_SESSION['cart'] as $key => $value)
    {
      if($value['product_id']==$_POST['product_id'])
      {
        unset($_SESSION['cart'][$key]); // Remove the product from the cart.
        $_SESSION['cart']=array_values($_SESSION['cart']); // Re-arrange the array.
        echo"<script>
          alert('Item Removed');
          window.location.href='cnf_order.php';
        </script>";
      }
    }
  }
  if(isset($_POST['Mod_Quantity'])) // Modify the quantity of the product in the cart.
  {
    foreach($_SESSION['cart'] as $key => $value)
    {
      if($value['product_id']==$_POST['product_id'])
      {
        $_SESSION['cart'][$key]['Quantity']=$_POST['Mod_Quantity']; // Modify the quantity of the product in the cart.
        echo"<script>
          window.location.href='cnf_order.php';
        </script>";
      }
    }
  }
}

?>