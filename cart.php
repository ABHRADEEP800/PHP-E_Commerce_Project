<?php
session_start();
// including database connection
require('env/database.php');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Cart</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">

  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <script src="jquery.js"></script>

  <script src="main.js"></script>
  <script src="main.js"></script>
  <script src="assets/js/script.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
</head>

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
            $("#header").load("cart.php #header");
            $("#container").load("cart.php #container", function() {
              subTotal()
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
            $("#container").load("cart.php #container", function() {
              subTotal()
            });


          }
        }
      });
    }
  </script>
  <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
  <div id="loading">
    <img src="assets/svg-logo/LOADER.svg" alt="Loading..." />
  </div>
  <script>
    var loader = document.getElementById("loading");
    window.addEventListener("load", function() {
      loader.style.display = "none";
    })
  </script>
  <!-- including header -->
  <?php
  include 'header.php';
  ?>
  <!------------------------------------------------- main body----------------------------------------------------------->
  <div class="container mb-3" id="container">
    <div class="row">
      <div class="col-lg-12 text-center border rounded bg-light my-5">
        <h1> CART </h1>
      </div>

      <div class="col-lg-9 table-responsive">
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
            $gtotal = 0;
            // checking if cart is empty or not
            if (isset($_SESSION['cart'])) {
              // if cart is not empty then we will show all the products in cart
              foreach ($_SESSION['cart'] as $key => $value) {
                // fetching product details from database
                $product_id = $value['product_id'];
                $sql = "SELECT * FROM product WHERE product_id = '$product_id'";
                $result = mysqli_query($conn, $sql);


                // checking if product is available in database or not
                while ($row = mysqli_fetch_assoc($result)) {
                  // storing product details in variables
                  $p_name = $row['product_name'];
                  $p_price = $row['product_price'];
                } // end of while loop

                // displaying product details in table
                $sr = $key + 1;
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
                          <button onclick='remove_item($product_id)' class='btn btn-sm btn-outline-danger'>REMOVE</button>
                      </td>
                    </tr>
                  ";
                $total = $p_price * $value['Quantity'];
                $gtotal = $gtotal + $total;
              } // end of foreach loop
            } // end of if condition
            else {
              // if cart is empty then we will show a message
              echo "
                  <tr>
                    <td colspan='6' class='text-center'>Cart is Empty!</td>
                  </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <!-- Make purches section -->
      <div class="col-lg-3">
        <div class="border bg-light rounded p-4">
          <h4>Grand Total:</h4>
          <h5 class="text-right" id="gtotal"><?= $gtotal ?></h5>

          <?php
          // checking if cart is empty or not
          if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
          ?>
            <!-- taking user address -->
            <form action="payment.php" method="POST">
              <div class="mb-3">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required>
                <div class="invalid-feedback">
                  Please enter your shipping address.
                </div>
              </div>

              <!-- taking user city -->
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
                  <input type="text" class="form-control" id="zip" name="zip" placeholder="" required>
                  <div class="invalid-feedback">
                    Zip code required.
                  </div>
                </div>

              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block" name="continue">Continue</button>
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

  <!----------- including footer --------------->
  <?php
  include 'footer.php';
  ?>
</body>

</html>