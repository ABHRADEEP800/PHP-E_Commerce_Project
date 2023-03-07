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
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Home</title>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo1.svg">

    <meta name="viewport" content="width=device-width, initial-scale=1" />
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

  // update account name
  // checking if update button is clicked
  if(isset($_POST['update'])){
    $user_name = $_POST['u_name'];

    // updating user name in database
    $sql = "UPDATE user SET user_name = '$user_name' WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $sql);
    if($result){
      echo "<script>alert('Account Updated Successfully')</script>";
      echo "<script>window.location.href = 'account.php'</script>";
    }
    else{
      echo "<script>alert('Account Updation Failed')</script>";
    }
  } // end of update button

  // become seller
  // checking if become seller button is clicked
  if(isset($_POST['b_seller'])){

    // updating user type in database
    $sql = "UPDATE user SET user_type = 'Seller' WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $sql);
    if($result){
      echo "<script>alert('Account Updated Successfully')</script>";
      echo "<script>window.location.href = 'logout.php'</script>";
    }
    else{
      echo "<script>alert('Account Updation Failed')</script>";
    }
  } // end of become seller button


  // reset password
  // checking if reset password button is clicked
  if(isset($_POST["r_pass"])) {

    // getting user details from database
    $new_pass=$_POST['new_pass'];
    $cnf_pass=$_POST['cnf_pass'];

    // checking if password and confirm password matches
    if($new_pass==$cnf_pass){
        // updating password in database
        $new_pass=md5($new_pass);
        $sql="UPDATE user SET user_pass='$new_pass' WHERE user_id='$user_id'";
        mysqli_query($conn,$sql);
        echo'<script>
        alert("Password Changed Successfully");
        window.location.href="account.php";
        </script>';
    }
    else{
        echo'<script>
        alert("Password does not match");
        </script>';
    }
}// end of reset password button


// delete account
// checking if delete account button is clicked
if(isset($_POST['d_account'])){

  // deleting orders of user from database
  $sql = "DELETE FROM orders WHERE order_user = '$user_id'";
  mysqli_query($conn, $sql);

  // deleting user from database
  $sql = "DELETE FROM user WHERE user_id = '$user_id'";
  $result = mysqli_query($conn, $sql);
  if($result){
    echo "<script>alert('Account Deleted Successfully')</script>";
    echo "<script>window.location.href = 'logout.php'</script>";
  }
  else{
    echo "<script>alert('Account Deletion Failed')</script>";
  }
}
?>

<section style="background-color: #eee;">
  <div class="container py-5">
    <div class="row">
      <!-- Profile Details -->
      <div class="col-lg-4 ">
        <div class="card mb-4">
          <div class="card-body pb-5 text-center" >
            <img src="assets/svg-logo/user.svg" alt="avatar"
              class="rounded-circle img-fluid" style="width: 150px;">
            <h5 class="my-3"><?=$user_name?></h5>
            <h6 class="text-muted my-3"><?=$_SESSION['customer']?></h6>
            <div class="d-flex justify-content-center mb-2">
            </div>
          </div>
        </div>
      </div>

      <!-- all details of user -->
      <div class="col-lg-8 ">
        <div class="card mb-4 tab-hide">
          <div class="card-body">
            <div class = "tab-titles" >
                <p class = "h6 tab-links" id="accountlink"onclick="opentab('account')" > Account </p >
                <p class = "h6 tab-links"id="orderlink" onclick="opentab('orders')" > My orders </p >
                <p class = "h6 tab-links " id="sellerlink" onclick="opentab('seller')"> Become A Seller </p >
                <p class = "h6 tab-links " id="clink"onclick="opentab('c_pass')"> Change Password </p >
                <p class = "h6 tab-links "  id="dlink"onclick="opentab('d_account')"> Delete Account </p >
            </div>
          </div>
        </div>
        
        <form method="post">
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
                    while($row = mysqli_fetch_assoc($result)){
                      $order_id = $row['order_id'];
                      $order_date = date("d M Y ", strtotime($row['order_date']));
                      $order_status = $row['order_status'];
                        // getting order items of user from database
                        $sql1="SELECT product.product_name, order_p.order_qu, product.product_img, product.product_id 
                        FROM order_p
                        INNER JOIN product ON product.product_id=order_p.order_item
                        WHERE order_p.order_id=$order_id LIMIT 2";
                        $result1=mysqli_query($conn,$sql1);

                        // displaying order items of user
                        while($row1=mysqli_fetch_assoc($result1)){
                            $product_name=$row1['product_name'];
                            $product_quantity=$row1['order_qu'];
                            $product_img=$row1['product_img'];
                            $product_id=$row1['product_id'];
                      
                  ?>
                    <!-- showing order details -->
                    <div class="row mb-3 bb-1 pt-0">
                      <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                        <img class="thumb_img" src="<?php echo "app/".$product_img; ?>">
                      </div>
                      <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                        <a class="d-flex" href="order_view.php?order_id=<?php echo $order_id;?>">
                          <div class="col-6 me-3">
                            <p class="h4"><?php echo $product_name; ?></p>
                          </div>
                          <div class="col-4">
                            <small><?php echo $product_quantity." Pcs"; ?></small>
                          </div>
                          <div class="col-2">
                            <i class="fa fa-angle-right"></i>
                          </div>
                        </a>
                        <div class="d-flex">
                          <div class="col-6">
                            <small class="text-muted "><?php echo $order_status." on ".$order_date; ?></small>
                        </div>                                     
                      
                        <?php
                          // checking if order is delivered or not
                          if($order_status == "Delivered" ){
                            $sql3= "SELECT * FROM review WHERE product_id = '$product_id' AND order_id = '$order_id'";
                            $result22 = mysqli_query($conn, $sql3);
                            $row_count= mysqli_num_rows($result22);
                            // checking if user has already rated the product or not
                            if($row_count==0){
                        ?>
                        <div class='col-6 text-end'>
                          <a class='text-end' href='rating.php?order_id=<?php echo $order_id;?>&product_id=<?=$product_id?>'>Rate This product >></a>                                     
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
        <div class="tab-contents "  id="account">
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-3">
                    <p class="mb-0">Full Name</p>
                  </div>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="u_name" value="<?=$user_name?>">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <p class="mb-0">Email</p>
                  </div>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" value="<?=$user_email?>" disabled>
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
          </div> 

        <!------------------------- Become Seller ----------------------->        
        <div class="tab-contents" id="seller">
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
        </div>

        <!---------------------------------------- change Password --------------------------->
        <div class="tab-contents" id="c_pass">
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
        </div>

        <!-------------------- delete account --------------------->
        <div class="tab-contents" id="d_account">
        <div class="card mb-4">
              <div class="card-body">
                <p class="h5">Delete your Account</p>
                <p class="h6">Please Confirm if You Want delete your account permanently</p>
                <hr>
                <div class="row d-flex justify-content-center">
                  <div class=" col-sm-3">
                    <input type="submit" name="d_account" onclick="return confirm('Do you really want to delete your account permanently?');" class="btn btn-danger form-control" value="Delete Account">
                  </div>
              </div>
              </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
        // tab script
        var tablinks = document.getElementsByClassName("tab-links");
        var tabcontents = document.getElementsByClassName("tab-contents");

        // function to open tab
        function opentab(tabname) {
            for(tablink of tablinks){
                tablink.classList.remove("active-link")
            }
            for(tabcontent of tabcontents){
                tabcontent.classList.remove("active-tab")
                
            }
            event.currentTarget.classList.add("active-link")
            document.getElementById(tabname).classList.add("active-tab") 
        }
    </script>
    <script>
        // side menu script
        var sidemenu= document.getElementById("sidemenu");
        function openmenu(){
            sidemenu.style.right ="0";
        }
        function closemenu(){
            sidemenu.style.right ="-400px";
        }
    </script>
    <script>
      const queryString = window.location.search;
			const urlParams = new URLSearchParams(queryString);
      if(urlParams.get('tab') == 'seller')
      {
        const seller=document.querySelector('#seller');
        seller.classList.add('active-tab');
        const sellerlink=document.querySelector('#sellerlink');
        sellerlink.classList.add('active-link');
      }
      else if(urlParams.get('tab') == 'orders'){
        const seller=document.querySelector('#orders');
        seller.classList.add('active-tab');
        const sellerlink=document.querySelector('#orderlink');
        sellerlink.classList.add('active-link');
      }
      else if(urlParams.get('tab') == 'd_account'){
        const seller=document.querySelector('#d_account');
        seller.classList.add('active-tab');
        const sellerlink=document.querySelector('#dlink');
        sellerlink.classList.add('active-link');
        
      }
      else if(urlParams.get('tab') == 'c_pass'){
        const seller=document.querySelector('#c_pass');
        seller.classList.add('active-tab');
        const sellerlink=document.querySelector('#clink');
        sellerlink.classList.add('active-link');
      }
      else{
        const seller=document.querySelector('#account');
        seller.classList.add('active-tab');
        const sellerlink=document.querySelector('#accountlink');
        sellerlink.classList.add('active-link');
      }
    </script>

<!-- css for tab menu -->
<style>
  .tab-titles {
    /* display: flex; */
    margin: 20px 0 40px;
  }
  .tab-links {
    margin-right: 30px;
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

  .card{
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
