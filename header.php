<!-------------------------------------------------header----------------------------------------------------------->
<div class="">
  <header
    class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom"
  >
    <!-- Logo -->
    <div class="col-md-4">
      <a
        href="index.php"
        class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none"
      >
      
        <!-- Logo -->
        <img
          src="assets/svg-logo/logo.svg"
          class="mx-3"
          width="100"
          height="100"
        />
      </a>
    </div>

    <!-- Navigation buttons -->
    <div class="col-md-4">
      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="index.php" class="nav-link px-2 link-dark">Home</a></li>
        <li><a href="index.php" class="nav-link px-2 link-secondary">Products</a></li>
        <li><a href="contact_us.php" class="nav-link px-2 link-secondary">Contact Us</a></li>
        <li><a href="privacy_policy.php" class="nav-link px-2 link-secondary">Privacy Policy</a></li>
      </ul>
    </div>

    <!-- user info -->
    <div class="col-md-4 d-flex justify-content-end">
      <?php

        // count cart items
        $count=0;

        // if cart session variable is set
        if(isset($_SESSION['cart']))
        {
          $count=count($_SESSION['cart']);
        }
      ?>
      <?php

        // if user is logged in show "My Account" and "Logout" options
        if(isset($_SESSION['customer'])){
          $email = $_SESSION['customer'];
          // sql query to select user details
          $sql = "SELECT * FROM user WHERE user_email = '$email'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $user_name = $row['user_name'];
          $first_name = substr($user_name, 0, strpos($user_name, " "));          

        ?>

          <div class="px-5">
            <a href='cart.php' class='btn btn-warning me-2'><i class='fa-solid fa-cart-shopping'></i> (<?=$count?>)</a>          
            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <?=$first_name?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="account.php">My Account</a></li>
              <div class="link-show">
                <li><a class="dropdown-item" href="account.php?tab=orders">My Orders</a></li>
                <li><a class="dropdown-item" href="account.php?tab=c_pass">Change Password</a></li>
                <li><a class="dropdown-item" href="account.php?tab=seller">Become Seller</a></li>
                <li><a class="dropdown-item" href="account.php?tab=d_account">Delete Account</a></li>
              </div>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </div>

        <?php
          }

          // if user is not logged in show "Signup" and "Login" options
          else{

        ?>

          <div class="">
            <a href='cart.php' class='btn btn-warning me-2'><i class='fa-solid fa-cart-shopping'></i> (<?=$count?>)</a>
            <a class='button d-inline-flex btn btn-outline-primary my-auto font-weight-bolder me-4' href='signup.php'>
              <span class='my-auto pe-2'>Signup</span>
              <i class='my-auto fa-solid fa-user-plus'></i>
            </a>
            <a class='button d-inline-flex btn btn-outline-primary my-auto font-weight-bolder me-4' href='login.php'>
              <span class='my-auto pe-2'>Login</span>
              <i class='my-auto fa-solid fa-right-to-bracket'></i>
            </a>
          </div>

        <?php
          }
        ?>
    </div>
  </header>
</div>