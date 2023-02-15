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
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class = "tab-titles" >
                <p class = "h6 tab-links active-link" onclick="opentab('orders')" > My orders </p >
                <p class = "h6 tab-links" onclick="opentab('account')" > Account </p >
                <p class = "h6 tab-links " onclick="opentab('seller')"> Become A Seller </p >
                <p class = "h6 tab-links " onclick="opentab('c_pass')"> Change Password </p >
                <p class = "h6 tab-links " onclick="opentab('d_account')"> Delete Account </p >
            </div>
          </div>
        </div>
        
        <form method="post">
        <!------------------ order Details --------------------->
        <div class="tab-contents active-tab" id="orders">
          <div class="card mb-4">
            <div class="card-body">
              <!-- outer table -->
              <table class="table">
                <tr>
                  <th>Order Date</th>
                  <th>Status</th>
                  <th>Order Item</th>
                  <th>Invoice</th>
                </tr>

                <?php

                    // pagination for orders
                    $limit = 1;
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $start = ($page -1) * $limit;
        
        
                    $result1 = $conn->query("SELECT count(order_id) AS order_id FROM orders  WHERE order_user = '$user_id'");
                    $countAll = $result1->fetch_all(MYSQLI_ASSOC);
        
                    $total = $countAll[0]['order_id'];
                    $pages = ceil($total / $limit);
        
                    $previous = $page -1;
                    $next = $page +1;
        
                    if($previous==0){
                        $previous=1;
                        
                    }   
        
                    if($next > $pages){
                        $next = $pages; 
                    }

                    // getting orders of user from database
                    $sql = "SELECT * FROM orders WHERE order_user = '$user_id' LIMIT $start, $limit;";
                    $result = mysqli_query($conn, $sql);

                    // displaying orders of user
                    while($row = mysqli_fetch_assoc($result)){
                      $order_id = $row['order_id'];
                      $order_date = $row['order_date'];
                      $order_status = $row['order_status'];
                      echo "<tr>
                              <td>$order_date</td>
                              <td>$order_status</td>
                              <td>
                              <table class='table table-bordered '>
                              <tbody>
                               <tr>
                              <th>Product Name</th>
                              <th>Quantity</th>
                              </tr>";

                            // getting order items of user from database
                            $sql1="SELECT product.product_name, order_p.order_qu
                            FROM order_p
                            INNER JOIN product ON product.product_id=order_p.order_item
                            WHERE order_p.order_id=$order_id";
                            $result1=mysqli_query($conn,$sql1);

                            // displaying order items of user
                            while($row1=mysqli_fetch_assoc($result1)){
                                $product_name=$row1['product_name'];
                                $product_quantity=$row1['order_qu'];
                          ?>

                              <tr>
                                <td><?php echo $product_name ?></td>
                                <td><?php echo $product_quantity?> Pcs</td>
                              </tr>
                           
                            
                            <?php
                            }
                            ?>

                            
                            </tbody>
                            </table> <!-- end of inner table -->
                          </td>
                          <td><a href='invoice.php?orderId=<?php echo $order_id?>' class='btn btn-primary'>Invoice</a></td>
                        </tr>
                            
                  <?php 
                            }
                ?>
              </table> <!-- end of outer table -->
              <div class="d-flex">
                <!-- showing current page and number of all pages -->
                <div class="col-6">Showing <b><?php echo $page;?></b> out of <b><?php echo $pages;?></b> Pages</div>
                <div class="col-6 d-flex justify-content-end">
                    <div class="">
                      <!-- showing go to page buttons -->
                        <ul class="pagination">

                          <li class=""><a class="page-link" href="account.php?page=<?php echo $previous;?>">&laquo; Previous</a></li>
                          <?php

                            // pagination for orders
                            if($page <= 2){
                              $page = 1;
                            }elseif($page >= $pages - 2){
                              $page = $pages - 2;
                            }

                            // displaying pagination for orders
                            for($i = $page; $i <= $page + 2; $i++): 
                            if($i <= $pages){?>
                              
                            <li class=""><a class="page-link" href="account.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                          <?php } endfor; ?>
                          <li class=""><a class="page-link" href="account.php?page=<?php echo $next;?>">&raquo; Next</a></li>
                        
                        </ul>
                    </div>
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
                  <button type="submit" name="r_pass" class="btn btn-primary btn-block col-3 mb-4">Reset Password</button>
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

<!-- css for tab menu -->
<style>
  .tab-titles {
    display: flex;
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
