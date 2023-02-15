<?php 
session_start();

// if user is not logged in, redirect to login page
if(!isset($_SESSION['customer'])){
  header("Location: login.php");
  exit;
}

// check if form is submitted
if($_SERVER["REQUEST_METHOD"]=="POST")
{
  // check if add to cart button is clicked
  if(isset($_POST['Add_To_Cart']))
  {
    // check if cart is empty
    if(isset($_SESSION['cart']))
    {
      // check if item is already added to cart
      $myitems=array_column($_SESSION['cart'],'product_id');
      if(in_array($_POST['product_id'],$myitems))
      {
        echo"<script>
          alert('Item Already Added');
          window.location.href='index.php';
        </script>";
      }
      // if item is not added to cart
      else
      {
        // count number of items in cart
        $count=count($_SESSION['cart']);
        $_SESSION['cart'][$count]=array('product_id'=>$_POST['product_id'],'Quantity'=>1);
        echo"<script>
          alert('Item Added to Cart');
          window.location.href='index.php';
        </script>";
      }
    }
    else
    {
      // add item to cart
      $_SESSION['cart'][0]=array('product_id'=>$_POST['product_id'],'Quantity'=>1);
      echo"<script>
        alert('Item Added to Cart');
        window.location.href='index.php';
      </script>";
    }
  }

  // check if remove item button is clicked
  if(isset($_POST['Remove_Item']))
  {

    // check if cart is empty
    foreach($_SESSION['cart'] as $key => $value)
    {
      if($value['product_id']==$_POST['product_id'])
      {
        unset($_SESSION['cart'][$key]);
        $_SESSION['cart']=array_values($_SESSION['cart']);
        echo"<script>
          alert('Item Removed');
          window.location.href='cart.php';
        </script>";
      }
    }
  }

  // check if modify quantity button is clicked
  if(isset($_POST['Mod_Quantity']))
  {
    foreach($_SESSION['cart'] as $key => $value)
    {
      if($value['product_id']==$_POST['product_id'])
      {
        $_SESSION['cart'][$key]['Quantity']=$_POST['Mod_Quantity'];
        echo"<script>
          window.location.href='cart.php';
        </script>";
      }
    }
  }
}

?>