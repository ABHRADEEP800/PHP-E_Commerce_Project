<?php
session_start();
// checking if user is logged in or not
if (!isset($_SESSION['customer'])) {
  header("Location: login.php");
}
// including database connection
require('env/database.php');
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Account</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">

  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />


</head>

<body>
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
  // including header
  include 'header.php';
  ?>
  <!----------------------------------------------Body---------------------------------------------------------->
  <?php
  // getting user details from database
  $user_email = $_SESSION['customer'];
  $sql = "SELECT * FROM user WHERE user_email = '$user_email'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $user_name = $row['user_name'];
  $user_id = $row['user_id'];
  $tfa = $row['2fa'];
  $fname = explode(" ", $user_name)[0];
  $lname = explode(" ", $user_name)[1];
  // update account name
  // checking if update button is clicked
  if (isset($_POST['update'])) {
    $user_name = $_POST['fname'] . " " . $_POST['lname'];
    $twfa = 'OFF';
    if (isset($_POST['2fa'])) {
      $twfa = 'ON';
    }

    // updating user name in database
    $sql = "UPDATE user SET user_name = '$user_name' , 2fa = '$twfa' WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      echo "<script>
      toastr.options.closeButton = true;
      toastr.options.progressBar = true;
      toastr['success']('Account Updated Successfully');
      setTimeout(function(){ window.location.href='account.php'; }, 5000);
      </script>";
    } else {
      echo "<script>
      toastr.options.closeButton = true;
      toastr.options.progressBar = true;
      toastr['error']('Account Updation Failed!'); 
      </script>";
    }
  } // end of update button

  // become seller
  // checking if become seller button is clicked
  if (isset($_POST['b_seller'])) {

    // updating user type in database
    $sql = "UPDATE user SET user_type = 'Seller' WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      echo "<script>
      toastr.options.closeButton = true;
      toastr.options.progressBar = true;
      toastr['success']('Account Updated Successfully');
      setTimeout(function(){ window.location.href='logout.php'; }, 5000);
      </script>";
    } else {
      echo "<script>
      toastr.options.closeButton = true;
      toastr.options.progressBar = true;
      toastr['error']('Account Updation Failed!'); 
      </script>";
    }
  } // end of become seller button


  // reset password
  // checking if reset password button is clicked
  if (isset($_POST["r_pass"])) {

    // getting user details from database
    $new_pass = $_POST['new_pass'];
    $cnf_pass = $_POST['cnf_pass'];
    $old_pass = $_POST['old_pass'];
    $old_pass = md5($old_pass);
    $sql = "SELECT * FROM user WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['user_pass'] == $old_pass) {
      // checking if password and confirm password matches
      if ($new_pass == $cnf_pass) {
        if (!preg_match('/^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $new_pass)) {
          echo "<script>
     toastr.options.closeButton = true;
     toastr.options.progressBar = true;
     toastr['error']('Password must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters'); 
     </script>";
          exit;
        }
        // updating password in database
        $new_pass = md5($new_pass);
        $sql = "UPDATE user SET user_pass='$new_pass' WHERE user_id='$user_id'";
        mysqli_query($conn, $sql);
        echo "<script>
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr['success']('Password Changed Successfully');
        setTimeout(function(){ window.location.href='account.php?tab=c_pass'; }, 5000);
        </script>";
      } else {
        echo "<script>
      toastr.options.closeButton = true;
      toastr.options.progressBar = true;
      toastr['error']('Password does not match!'); 
      </script>";
      }
    } else {
      echo "<script>
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr['error']('Old Password is incorrect!'); 
    </script>";
    }
  } // end of reset password button

  // a_membership
  if (isset($_POST['a_membership'])) {
    $condi1 = $_POST['cond1'];
    $condi2 = $_POST['cond2'];
    if ($condi1 == '1' && $condi2 == '1') {
      $end_date = date("Y-m-d", strtotime('+365 day'));
      $sql = "UPDATE user SET membership = 'Yes', membership_end_date = '$end_date' WHERE user_id = '$user_id'";
      $result = mysqli_query($conn, $sql);
      if ($result) {
        echo "<script>
              toastr.options.closeButton = true;
              toastr.options.progressBar = true;
              toastr['success']('Membership Activated Successfully');
              setTimeout(function(){ window.location.href='account.php?tab=membership'; }, 5000);
              </script>";
      } else {
        echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['error']('Membership Activation Failed!'); 
            </script>";
      }
    } else {
      echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['error']('Membership Activation Failed!'); 
            </script>";
    }
  }

  ?>

  <section>
    <div class="container py-5">
      <div class="row">
        <!-- Profile Details -->
        <div class="col-lg-4 ">
          <div class="card mb-4">
            <div class="card-body pb-5 text-center">
              <div class="d-flex justify-content-center">
                <img src="assets/svg-logo/user.svg" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
              </div>
              <h5 class="my-3"><?= $user_name ?></h5>
              <h6 class="text-muted my-3"><?= $_SESSION['customer'] ?></h6>
              <?php
              $sql = "SELECT * FROM `user` WHERE `user_id` = $user_id";
              $result = mysqli_query($conn, $sql);
              $row = mysqli_fetch_assoc($result);
              $membership = $row['membership'];
              if ($membership == 'Yes') {
                echo '<p class="text-success">Premium Member</p>';
              }
              ?>
              <div class="d-flex justify-content-center mb-2">
              </div>
            </div>
          </div>
        </div>

        <!-- all details of user -->
        <div class="col-lg-8 ">
          <div class="card mb-4 tab-hide">
            <div class="card-body">
              <div class="tab-titles">
                <p class="h6 tab-links" id="accountlink" onclick="opentab('account')"> Account </p>
                <p class="h6 tab-links" id="orderlink" onclick="opentab('orders')"> My orders </p>
                <p class="h6 tab-links" id="mlink" onclick="opentab('membership')"> Membership </p>
                <p class="h6 tab-links " id="sellerlink" onclick="opentab('seller')"> Become A Seller </p>
                <p class="h6 tab-links " id="clink" onclick="opentab('c_pass')"> Change Password </p>
              </div>
            </div>
          </div>

          <!------------------ order Details --------------------->
          <div class="tab-contents" id="orders">
            <div class="card mb-4">
              <div class="card-body">
                <div class="mb-4">
                  <div class="section-title">
                    <p class="h3">Recent Orders</p>
                  </div>
                  <hr>
                  <?php
                  // getting orders of user from database
                  $sql = "SELECT * FROM orders WHERE order_user = '$user_id' ORDER BY order_id DESC LIMIT 1";
                  $result = mysqli_query($conn, $sql);

                  // displaying orders of user
                  while ($row = mysqli_fetch_assoc($result)) {
                    $order_id = $row['order_id'];
                    $order_date = date("d M Y ", strtotime($row['order_date']));
                    $order_status = $row['order_status'];
                    // getting order items of user from database
                    $sql1 = "SELECT product.product_name, order_p.order_qu, product.product_img, product.product_id 
                        FROM order_p
                        INNER JOIN product ON product.product_id=order_p.order_item
                        WHERE order_p.order_id=$order_id LIMIT 2";
                    $result1 = mysqli_query($conn, $sql1);

                    // displaying order items of user
                    while ($row1 = mysqli_fetch_assoc($result1)) {
                      $product_name = $row1['product_name'];
                      $product_quantity = $row1['order_qu'];
                      $product_img = $row1['product_img'];
                      $product_id = $row1['product_id'];

                  ?>
                      <!-- showing order details -->
                      <div class="row mb-3 bb-1 pt-0">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                          <img class="thumb_img" src="<?php echo $product_img; ?>">
                        </div>
                        <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                          <a class="d-flex" href="order_view.php?order_id=<?php echo $order_id; ?>">
                            <div class="col-6 mt-2 me-3">
                              <p class="h4"><?php echo $product_name; ?></p>
                            </div>
                            <div class="col-4 mt-2">
                              <small><?php echo $product_quantity . " Pcs"; ?></small>
                            </div>
                            <div class="col-2 mt-2">
                              <i class="fa fa-angle-right"></i>
                            </div>
                          </a>
                          <div class="d-flex">
                            <div class="col-6">
                              <small class="text-muted "><?php echo $order_status . " on " . $order_date; ?></small>
                            </div>

                            <?php
                            // checking if order is delivered or not
                            if ($order_status == "Delivered") {
                              $sql3 = "SELECT * FROM review WHERE product_id = '$product_id' AND order_id = '$order_id'";
                              $result22 = mysqli_query($conn, $sql3);
                              $row_count = mysqli_num_rows($result22);
                              // checking if user has already rated the product or not
                              if ($row_count == 0) {
                            ?>
                                <div class='col-6 text-end'>
                                  <a class='text-end' href='rating.php?order_id=<?php echo $order_id; ?>&product_id=<?= $product_id ?>'>Rate This product >></a>
                                </div>
                            <?php
                              }
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <!-- bottomm line -->
                      <hr>
                  <?php
                    }
                  }
                  ?>
                  <!-- see all orders -->
                  <div class="d-flex justify-content-end">
                    <a href="all_orders.php" class=""><u>See All Orders</u></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- --------------Account Details ---------------->

          <div class="tab-contents " id="account">
            <form method="post">
              <div class="card mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <p class="mb-0">Full Name</p>
                    </div>
                    <div class="col-sm-9">
                      <div class="row">
                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="fname" value="<?= $fname ?>" placeholder="First Name" required>
                        </div>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="lname" value="<?= $lname ?>" placeholder="Last Name" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <p class="mb-0">Email</p>
                    </div>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" value="<?= $user_email ?>" disabled>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">Two Step Verification(2FA)</p>
                      <p>*Email Otp</P>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="2fa" role="switch" id="flexSwitchCheckDefault" <?php
                                                                                                                              if ($tfa == 'ON') {
                                                                                                                                echo "checked";
                                                                                                                              }

                                                                                                                              ?> />
                      </div>
                    </div>

                  </div>
                  <hr>
                  <div class="row d-flex justify-content-center">
                    <div class=" col-sm-3">
                      <input type="submit" name="update" class="btn btn-primary form-control" value="Change Details">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>



          <!-- --------------Membership Details ---------------->
          <?php
          $sql = "SELECT * FROM `user` WHERE `user_id` = $user_id";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $membership = $row['membership'];
          if ($membership == 'Yes') {
            $end_date = $row['membership_end_date'];

          ?>
            <div class="tab-contents " id="membership">
              <div class="card mb-4">
                <div class="card-header text-center ">
                  <h3>Premium Membership</h3>
                </div>
                <div class="card-body">
                  <div class="row text-center">
                    <h5> Benefits</h5>
                  </div>
                  <div class="row mt-2">
                    <div class="col-sm-2">
                      <i class="fa-solid fa-circle fa-xl" style="color: #00ff00;"></i>

                    </div>
                    <div class="col-sm-10">
                      <p>
                        5% discount on all orders from Grapple online store.
                      </p>
                    </div>
                  </div>
                  <?php
                  $t_date = date('y-m-d');
                  $l_date = strtotime($end_date) - strtotime($t_date);
                  $l_date = round($l_date / 86400);
                  if ($l_date >= '0') {


                  ?>
                    <p>
                      Membership Ends in <?= $l_date ?> Days.
                    </p>
                  <?php
                  } else {
                    $sql = "UPDATE user SET membership = 'No' WHERE user_id = '$user_id'";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                      echo "<script>
                    toastr.options.closeButton = true;
                     toastr.options.progressBar = true;
                     toastr['error']('Your Membership Expired!');
                     setTimeout(function(){ window.location.href='account.php?tab=membership'; }, 5000);
                    </script>";
                    }
                  }
                  ?>
                </div>
              </div>
            </div>
          <?php

          } else {


            $btn_disable = 'disabled';
            $check1 = '';
            $check2 = '';
            $total = '0';
            $cond1 = '0';
            $cond2 = '0';
            //get 30 days prior day from current day
            $date = date('Y-m-d', strtotime('-30 days'));

            $sql = "SELECT * FROM `orders` WHERE `order_user` = $user_id &&  order_date > current_date - interval 30 day";
            $result = mysqli_query($conn, $sql);
            $row_count = mysqli_num_rows($result);
            if ($row_count >= 1) {
              $check1 = 'style="color: #00ff00;"';
              $cond1 = '1';
            }
            $sql = "SELECT  orders.order_id , order_p.order_qu * order_p.price  AS value FROM `orders`
        INNER JOIN order_p ON order_p.order_id = orders.order_id
        WHERE orders.order_user = $user_id  &&  order_date > current_date - interval 30 day";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
              $total += $row['value'];
            }
            if ($total >= 1000000) {
              $check2 = 'style="color: #00ff00;"';
              $cond2 = '1';
            }
            if ($cond1 === '1' && $cond2 === '1') {
              $btn_disable = '';
            }

          ?>
            <div class="tab-contents " id="membership">
              <div class="card mb-4">
                <div class="card-header text-center ">
                  <h3>Premium Membership</h3>
                </div>
                <div class="card-body">
                  <div class="row text-center">
                    <h5> Eligibility</h5>
                  </div>
                  <div class="row mt-2">
                    <div class="col-sm-2">
                      <i class="fa-solid fa-circle-check fa-xl" <?= $check1 ?>></i>

                    </div>
                    <div class="col-sm-10">
                      <p>
                        Minimum 10 Order's In Last 30 Days.

                      </p>
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2">
                    <div class="col-sm-2">
                      <i class="fa-solid fa-circle-check fa-xl" <?= $check2 ?>></i>
                    </div>
                    <div class="col-sm-10">
                      <p>
                        Rs 1000000 Total Order Value in Last 30 Days.

                      </p>
                    </div>
                  </div>
                  <hr>
                  <div class="d-flex justify-content-center">
                    <form method="post">
                      <input type="hidden" name="cond1" value="<?= $cond1 ?>">
                      <input type="hidden" name="cond2" value="<?= $cond2 ?>">
                      <button type="submit" name="a_membership" <?= $btn_disable ?> class="btn btn-primary btn-block mb-4">Apply Membership</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
          <!------------------------- Become Seller ----------------------->
          <div class="tab-contents" id="seller">
            <form method="post">
              <div class="card mb-4">
                <div class="card-body">
                  <p class="h5">Become A Seller</p>
                  <p class="h6">If Your account become seller, it can't be reverse as normal user account</p>
                  <hr>
                  <div class="row d-flex justify-content-center">
                    <div class=" col-sm-3">
                      <input type="submit" name="b_seller" onclick="return confirm('Do you really want to become a seller?');" class="btn btn-primary form-control" value="Become Seller">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <!---------------------------------------- change Password --------------------------->
          <div class="tab-contents" id="c_pass">
            <form method="post">
              <div class="card mb-4">
                <div class="card-body">
                  <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example2">Enter Old Password</label>
                    <input type="password" name="old_pass" id="form4Example2" placeholder="Enter Old Password" class="form-control" />
                  </div>
                  <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example2">Enter New Password</label>
                    <input type="password" name="new_pass" id="form4Example2" placeholder="Enter new Password" class="form-control" />
                  </div>
                  <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example3">Confirm Password</label>
                    <input type="password" class="form-control" name="cnf_pass" id="form4Example3" placeholder="Retype Password" rows="4"></input>
                  </div>
                  <div class="d-flex justify-content-center">
                    <button type="submit" name="r_pass" class="btn btn-primary btn-block mb-4">Reset Password</button>
                  </div>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
  </section>

  <script>
    // tab script
    var tablinks = document.getElementsByClassName("tab-links");
    var tabcontents = document.getElementsByClassName("tab-contents");

    // function to open tab
    function opentab(tabname) {
      for (tablink of tablinks) {
        tablink.classList.remove("active-link")
      }
      for (tabcontent of tabcontents) {
        tabcontent.classList.remove("active-tab")

      }
      event.currentTarget.classList.add("active-link")
      document.getElementById(tabname).classList.add("active-tab")
    }
  </script>
  <script>
    // side menu script
    var sidemenu = document.getElementById("sidemenu");

    function openmenu() {
      sidemenu.style.right = "0";
    }

    function closemenu() {
      sidemenu.style.right = "-400px";
    }
  </script>
  <script>
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    if (urlParams.get('tab') == 'seller') {
      const seller = document.querySelector('#seller');
      seller.classList.add('active-tab');
      const sellerlink = document.querySelector('#sellerlink');
      sellerlink.classList.add('active-link');
    } else if (urlParams.get('tab') == 'orders') {
      const tab = document.querySelector('#orders');
      tab.classList.add('active-tab');
      const tablink = document.querySelector('#orderlink');
      tablink.classList.add('active-link');
    } else if (urlParams.get('tab') == 'membership') {
      const tab = document.querySelector('#membership');
      tab.classList.add('active-tab');
      const tablink = document.querySelector('#mlink');
      tablink.classList.add('active-link');
    } else if (urlParams.get('tab') == 'd_account') {
      const tab = document.querySelector('#d_account');
      tab.classList.add('active-tab');
      const tablink = document.querySelector('#dlink');
      tablink.classList.add('active-link');

    } else if (urlParams.get('tab') == 'c_pass') {
      const tab = document.querySelector('#c_pass');
      tab.classList.add('active-tab');
      const tablink = document.querySelector('#clink');
      tablink.classList.add('active-link');
    } else {
      const tab = document.querySelector('#account');
      tab.classList.add('active-tab');
      const tablink = document.querySelector('#accountlink');
      tablink.classList.add('active-link');
    }
  </script>

  <!-- css for tab menu -->
  <style>
    .tab-titles {
      /* display: flex; */
      margin: 20px 0 40px;

    }

    .tab-links {
      margin-right: 35px;
      font-size: 20px;
      cursor: pointer;
      position: relative;

    }

    .tab-links::after {
      content: " ";
      width: 0;
      height: 5px;

      background: #0000ff;
      position: absolute;
      left: 0;
      bottom: -8px;
      transition: 0.5s;
    }

    .tab-links.active-link::after {
      width: 60%;
    }

    .tab-contents ul li {
      list-style: none;
      margin: 10px 0;
    }

    .tab-contents ul li span {
      color: #000;
      font-style: 14px;
    }

    .tab-contents {
      display: none;
    }

    .tab-contents.active-tab {
      display: block;
    }

    .card {
      border-radius: 1em;
    }
  </style>
  <!-------------------- footer ------------------->
  <?php
  // including footer
  include("footer.php");
  ?>
</body>

</html>