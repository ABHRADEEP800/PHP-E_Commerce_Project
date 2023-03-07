<?php
  session_start();
  // checking if user is logged in or not
  if(!isset($_SESSION['customer'])){
    header("Location: login.php");
  }
  // including database connection
  include('database.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Confirmed</title>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo1.svg" />
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
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />

  </head>
  <body>
    <!-------------------- header ------------------------>
    <?php
      // including header
     include 'header.php';
    ?>
    <!-- Body --------------------------------------------------------------->
    <?php 
      // getting user details from session
      if(isset($_SESSION['customer'])){
        $email = $_SESSION['customer'];

        // getting user details from database
        $sql = "SELECT * FROM user WHERE user_email = '$email'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $user_name = $row['user_name'];
        $user_id=$row['user_id'];
        $first_name = substr($user_name, 0, strpos($user_name, " "));
      }
        // getting order id from url
        $order_id = $_GET['order_id'];

        // getting order details from database
        $sql = "SELECT * FROM orders WHERE order_id = '$order_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $order_status = $row['order_status'];

        // getting product id from url
        $product_id=$_GET['product_id'];

        // getting product details from database
        $sql = "SELECT * FROM product WHERE product_id = '$product_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $product_name = $row['product_name'];
        $product_image = $row['product_img'];

    ?>
    
    <?php
      // checking if order is delivered or not
      if($order_status=="Delivered"){

        // checking if user has already rated the product or not
        $sql = "SELECT * FROM review WHERE product_id = '$product_id' AND order_id = '$order_id'";
        $result1 = mysqli_query($conn, $sql);
        $row_count= mysqli_num_rows($result1);
        if($row_count>0){
          echo"
          <script>alert('Rating Already Submitted');
          window.location.href='order_view.php?order_id=$order_id';
          </script>
          
          ";  
        }

        // if rating is not submited
        else{
      ?>
                      
        <div class="container-fluid px-5 my-5">
          <div class="row justify-content-center">
            <div class="col-xl-6">
              <div class="card border-0 rounded-3 shadow-lg overflow-hidden">
                <div class="card-body p-0">
                  <div class="row g-0 d-flex justify-content-center">
                    <div class="col-sm-10 p-4">
                      <div class="text-center">
                        <div class="h3 fw-light">Rate This Product</div>
                        <p class="mb-4 text-muted">Your Rating is Very importent For us.</p>
                      </div>
                      <div class="">
                        <form method="post">
                          <div class=" d-flex justify-content-center">
                            <img src="app/<?php echo $product_image; ?>" alt="" class="img-fluid thumb_img" >
                          </div>
                          <div class="d-flex justify-content-center my-3">
                            <label class='text-muted'>Product:- <?=$product_name?></label>
                          </div>
                          <div class="d-flex justify-content-center">
                            <div class='rate ' >
                            
                              <input type='radio' id='star5' name='rate' value='5'  />  
                              <label for='star5' title='5 Star'>5 stars</label>
                              <input type='radio' id='star4' name='rate' value='4' />
                              <label for='star4' title='4 Star'>4 stars</label>
                              <input type='radio' id='star3' name='rate' value='3' />
                              <label for='star3' title='3 Star'>3 stars</label>
                              <input type='radio' id='star2' name='rate' value='2' />
                              <label for='star2' title='2 Star'>2 stars</label>
                              <input type='radio' id='star1' name='rate' value='1'  />
                              <label for='star1' title='1 Star'>1 star</label>
                            </div>
                          </div>

                          <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                          <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                          <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                          <br>
                          <div class='d-flex justify-content-center'>
                            <button type="submit" name="review" class="btn btn-primary">Submit</button>
                          </div>
              
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>                        

      <?php
        }
      }
      ?>
                 
    <!-- Footer --------------------------------------------------------------->
    <?php  
      // including footer  
      include 'footer.php';    
    ?>
    
    <style>
      .rating {
        /* float: left; */
      }
      .rate {
      float: left;      
      height: 46px;
      padding: 0 10px;
      }
      .rate:not(:checked) > input {
          position:absolute;
          top:-9999px;
      }
      .rate:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:30px;
        color:#ccc;
      }
      .rate:not(:checked) > label:before {
          content: '★ ';
      }
      .rate > input:checked ~ label {
          color: #ffc700;    
      }
      .rate:not(:checked) > label:hover,
      .rate:not(:checked) > label:hover ~ label {
          color: #deb217;  
      }
      .rate > input:checked + label:hover,
      .rate > input:checked + label:hover ~ label,
      .rate > input:checked ~ label:hover,
      .rate > input:checked ~ label:hover ~ label,
      .rate > label:hover ~ input:checked ~ label {
          color: #c59b08;
      }      
    </style>
  </body>
</html>

<?php

  // if review is submitted
  if(isset($_POST['review'])){

    // getting data from form
    $rate=$_POST['rate'];
    $product_id=$_POST['product_id'];
    $order_id=$_POST['order_id'];
    $user_id=$_POST['user_id'];

    // inserting rating into database
    $sql="INSERT INTO `review`(`product_id`, `user_id`,`order_id`, `rating`) VALUES ('$product_id','$user_id','$order_id','$rate')";
    $result=mysqli_query($conn,$sql);
    if($result){
      echo"<script>alert('Review Submitted')
        window.location.href='order_view.php?order_id=$order_id';
      </script>";
    }
    else{
      echo"<script>alert('Review Not Submitted')</script>";
    }
  }
?>
