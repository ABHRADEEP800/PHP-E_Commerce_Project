<?php
session_start();
// including database connection
include('database.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Cart</title>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo1.svg">

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
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
    <script src="jquery.js"></script>
 
    <script src="main.js"></script>
  </head>
  <body>
    <!-- including header -->
    <?php
        include 'header.php';
    ?>
    <!------------------------------------------------- main body----------------------------------------------------------->
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center border rounded bg-light my-5">
        <h1> CART </h1>
      </div>

      <div class="col-lg-9">
        <table class="table">
          <!-- table head -->
          <thead class="text-center">
            <tr>
              <th scope="col">Serial No.</th>
              <th scope="col">Product Name</th>
              <th scope="col">Product Price</th>
              <th scope="col">Quantity</th>
              <th scope="col">Total</th>              
              <th scope="col"></th>
            </tr>
          </thead>
          <!-- table body -->
          <tbody class="text-center">
            <?php 
              // checking if cart is empty or not
              if(isset($_SESSION['cart']))
              {
                // if cart is not empty then we will show all the products in cart
                foreach($_SESSION['cart'] as $key => $value)
                {
                  // fetching product details from database
                  $product_id=$value['product_id'];
                  $sql="SELECT * FROM product WHERE product_id = '$product_id'";
                  $result=mysqli_query($conn,$sql);
                  
                  // checking if product is available in database or not
                  while($row=mysqli_fetch_assoc($result))
                  {
                      // storing product details in variables
                      $p_name=$row['product_name'];
                      $p_price=$row['product_price'];

                  } // end of while loop

                  // displaying product details in table
                  $sr=$key+1;
                  echo"
                    <tr>
                      <td>$sr</td>
                      <td>$p_name</td>
                      <td>$p_price<input type='hidden' class='iprice' value='$p_price'></td>
                      <td>
                        <form action='manage_cart.php' method='POST'>
                          <input class='text-center iquantity' name='Mod_Quantity' onchange='this.form.submit();' type='number' value='$value[Quantity]' min='1' max='10'>
                          <input type='hidden' name='product_id' value='$product_id'>
                        </form>
                      </td>
                      <td class='itotal'></td>
                      <td>
                        <form action='manage_cart.php' method='POST'>
                          <button name='Remove_Item' class='btn btn-sm btn-outline-danger'>REMOVE</button>
                          <input type='hidden' name='product_id' value='$product_id'>
                        </form>
                      </td>
                    </tr>
                  ";
                } // end of foreach loop
              } // end of if condition
            ?>
          </tbody>
        </table>
      </div>
      <!-- Make purches section -->
      <div class="col-lg-3">
        <div class="border bg-light rounded p-4">
          <h4>Grand Total:</h4>
          <h5 class="text-right" id="gtotal"></h5>
          <br>
          
          <?php 
          // checking if cart is empty or not
            if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0)
            {
          ?>
          <form action="purchase.php" method="POST">
            <div class="form-group">              
              <div class="form-check">
                <input class="form-check-input" type="radio"  value="COD" id="flexRadioDefault2" checked>
                <label class="form-check-label" for="flexRadioDefault2">
                  Cash On Delivery
                </label>
              </div>
              <br>
              <button class="btn btn-primary btn-block" name="purchase">Make Purchase</button>
            </div>
          </form>
          <?php
            } // end of if condition
          ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    // calculating total price of each product
    var gt=0;
    var iprice=document.getElementsByClassName('iprice');
    var iquantity=document.getElementsByClassName('iquantity');
    var itotal=document.getElementsByClassName('itotal');
    var gtotal=document.getElementById('gtotal');
    // function to calculate total price of each product
    function subTotal()
    {
      gt=0;
      for(i=0;i<iprice.length;i++)
      {
        itotal[i].innerText=(iprice[i].value)*(iquantity[i].value);

        gt=gt+(iprice[i].value)*(iquantity[i].value);

      }
      gtotal.innerText=gt;
    }
    
    // calling subTotal function
    subTotal();

  </script>
    <!----------- including footer --------------->
    <?php
        include 'footer.php';
    ?>
  </body>
</html>
