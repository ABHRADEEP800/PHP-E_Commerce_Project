<?php
session_start(); // Start the session.
if (!isset($_SESSION['admin'])) { // If no session value is present, redirect the user:
  header('location:  ../admin_login.php'); // Use relative path.
  exit; // Quit the script.
}
require('../env/database.php'); // Include the database connection.
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="asset/image/logo-bg.svg">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
  <link rel="stylesheet" href="asset/card.css" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <link rel="stylesheet" href="../assets/css/main.css" />
  <link rel="stylesheet" href="asset/css/main.css" />
  <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<?php
include 'navbar.php';
?>

<body>
  <script>
    function remove_item(id) {
      $.ajax({
        url: "manage_cart.php",
        data: "product_id=" + id + "&Remove_Item=",
        method: "post",
        success: function(response) {
          if (response == "item_removed") {
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['warning']('Product removed from cart.');
            $("#container").load("cnf_order.php #container", function() {
              subTotal()
              autocomplt()
            });
          }
        }
      });
    }

    function mod_quantity(id, quantity) {
      $.ajax({
        url: "manage_cart.php",
        data: "product_id=" + id + "&Mod_Quantity=" + quantity,
        method: "post",
        success: function(response) {
          if (response == "item_modified") {
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['success']('Product Quantity Modified.');
            $("#container").load("cnf_order.php #container", function() {
              subTotal()
              autocomplt()
            });


          }
        }
      });
    }
  </script>
  <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
  <div id="loading">
    <img src="asset/svg-logo/LOADER.svg" alt="Loading..." />
  </div>
  <script>
    var loader = document.getElementById("loading");
    window.addEventListener("load", function() {
      loader.style.display = "none";
    })
  </script>

  <!-------------------------------------------------body----------------------------------------------------------->
  <?php
  $total = 0; // Set the initial total value.
  ?>
  <div class="container" id="container">
    <div class="row">
      <div class="col-lg-12 text-center border rounded bg-light my-5">
        <h1> CART </h1>
      </div>

      <div class="col-lg-9 table-responsive">
        <table class="table">
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
          <tbody class="text-center">
            <?php
            $gtotal = 0;
            if (isset($_SESSION['cart'])) // If the cart session variable is set
            {
              foreach ($_SESSION['cart'] as $key => $value) // Iterate through the cart session variable
              {
                $product_id = $value['product_id']; // Set the Product_id
                $sql = "SELECT * FROM product WHERE product_id = '$product_id'"; // Make the SQL Query to get the product details
                $result = mysqli_query($conn, $sql); // Execute the SQL Query
                while ($row = mysqli_fetch_assoc($result)) // Run a while loop to get all the product details
                {
                  $p_name = $row['product_name']; // Get the product name from the database and store in variable
                  $p_price = $row['product_price']; // Get the product price from the database and store in variable

                }
                $sr = $key + 1; // Set the serial number
                echo "
                    <tr>
                      <td>$sr</td>
                      <td>$p_name</td>
                      <td>$p_price<input type='hidden' class='iprice' value='$p_price'></td>
                      <td>
                      
                          <input class='text-center iquantity' name='Mod_Quantity' onchange='mod_quantity($product_id,this.value);' type='number' value='$value[Quantity]' min='1' max='10'>
                          
                      </td>
                      <td class='itotal'></td>
                      <td>
                          <button onclick='remove_item($product_id)' name='Remove_Item' class='btn btn-sm btn-outline-danger'>REMOVE</button>
                      </td>
                    </tr>
                  ";
                $total = $p_price * $value['Quantity'];
                $gtotal = $gtotal + $total;
              }
            }
            ?>
          </tbody>
        </table>
      </div>

      <div class="col-lg-3">
        <div class="border bg-light rounded p-4">
          <h4>Grand Total:</h4>
          <h5 class="text-right" id="gtotal"><?= $gtotal ?></h5>
          <br>
          <?php
          $sql = "SELECT user_email FROM user WHERE user_type='Customer' "; // Make the SQL Query to get the product details
          $result = mysqli_query($conn, $sql); // Execute the SQL Query
          $user_email = array(); // Create an array
          while ($row = mysqli_fetch_assoc($result)) { // Run a while loop to get all the user email
            $user_email[] = $row['user_email']; // Get the user email from the database and store in array
          }
          $user_email = implode("','", $user_email); // Convert the array into string
          echo "<script>
          function autocomplt() 
           {
            $( function() {
            var availableTags = [

                '$user_email',
            
            ];
            $( '#etags' ).autocomplete({
            source: availableTags
            });
           } );
       }
       autocomplt();
        </script>"; // Autocomplete the user email


          if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) // If the cart session variable is set and the cart is not empty
          {
          ?>

            <form action="purchase.php" method="POST">
              <div class="form-group">
                <label class="form-label" for="form4Example1">Select user E-mail</label>
                <input type="text" name="user_email" id="etags" placeholder="Enter User Email" class="form-control" />
                <div class="mb-3">
                  <label for="address">Address</label>
                  <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required>
                  <div class="invalid-feedback">
                    Please enter your shipping address.
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="country">Country</label>
                    <select class="custom-select d-block w-100" name="country" id="country" required>
                      <option value="">Choose...</option>
                      <option value="India">India</option>
                    </select>
                    <div class="invalid-feedback">
                      Please select a valid country.
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="state">State</label>
                    <select class="custom-select d-block w-100" name="state" id="state" required>
                      <option value="">Choose...</option>
                      <option value="West Bengal">West Bengal</option>
                    </select>
                    <div class="invalid-feedback">
                      Please provide a valid state.
                    </div>
                  </div>

                  <div class=" mb-3">
                    <label for="zip">Zip</label>
                    <input type="number" class="form-control" id="zip" name="zip" placeholder="123456" required>
                    <div class="invalid-feedback">
                      Zip code required.
                    </div>
                  </div>
                  <div class=" mb-3">
                    <label for="disc">Discount (%)</label>
                    <input type="number" class="form-control" id="disc" name="disc" placeholder="Enter dicount in Percentage" required>

                  </div>


                  <div class="form-check">
                    <input class="form-check-input" type="radio" value="COD" id="flexRadioDefault2" checked>
                    <label class="form-check-label" for="flexRadioDefault2">
                      Cash On Delivery
                    </label>
                  </div>
                  <br>
                  <button class="btn btn-primary btn-block" name="purchase">Confirm Order</button>
            </form>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  <script>
    // Set the initial grand total value
    var gt = 0;
    var iprice = document.getElementsByClassName('iprice');
    var iquantity = document.getElementsByClassName('iquantity');
    var itotal = document.getElementsByClassName('itotal');
    var gtotal = document.getElementById('gtotal');

    function subTotal() {
      gt = 0;
      for (i = 0; i < iprice.length; i++) {
        itotal[i].innerText = (iprice[i].value) * (iquantity[i].value); // Set the total price of each product

        gt = gt + (iprice[i].value) * (iquantity[i].value); // Set the grand total

      }
      gtotal.innerText = gt; // Set the grand total
    }

    subTotal(); // Calling the function
  </script>
</body>

</html>