<?php 
session_start();

// check if form is submitted
if($_SERVER["REQUEST_METHOD"]=="POST")
{
  // if user is not logged in, 
if(isset($_SESSION['customer']))
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
        $data[]=
        [
          'status'=>'already',
          'cart_count'=>count($_SESSION['cart'])
        ];
        echo json_encode($data);


      }
      // if item is not added to cart
      else
      {
        // count number of items in cart
        $count=count($_SESSION['cart']);
        $_SESSION['cart'][$count]=array('product_id'=>$_POST['product_id'],'Quantity'=>1);
        $data[]=
        [
          'status'=>'added',
          'cart_count'=>count($_SESSION['cart'])
        ];
        echo json_encode($data);
      }
    }
    else
    {
      // add item to cart
      $_SESSION['cart'][0]=array('product_id'=>$_POST['product_id'],'Quantity'=>1);
      $data[]=
        [
          'status'=>'added',
          'cart_count'=>count($_SESSION['cart'])
        ];
        echo json_encode($data);
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
        echo "item_removed";
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
        echo "item_modified";
      }
    }
  }
}
else
{
  $data[]=
  [
    'status'=>'not_login',
    'cart_count'=>'no'
  ];
  echo json_encode($data);
}
}
?>