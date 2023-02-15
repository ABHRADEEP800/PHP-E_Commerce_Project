<!-------------------------------------------------header----------------------------------------------------------->
<div class="">
  <header
    class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom"
  >
    <a
      href="index.php"
      class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none"
    >
    
      <!-- Logo -->
      <img
        src="assets/svg-logo/logo.svg"
        class="offset-3"
        width="100"
        height="100"
      />
    </a>

    <!-- Navigation buttons -->
    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
      <li><a href="index.php" class="nav-link px-2 link-dark">Home</a></li>
      <li><a href="index.php" class="nav-link px-2 link-secondary">Products</a></li>
      <li><a href="contact_us.php" class="nav-link px-2 link-secondary">Contact Us</a></li>
    </ul>
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
        echo "<div class='col-md-3 text-end'>"
        ."<a href='cart.php' class='btn btn-warning me-2'><i class='fa-solid fa-cart-shopping'></i> ($count)</a>"
        ."<a class='button d-inline-flex btn btn-outline-primary my-auto font-weight-bolder me-4' href='account.php'>"
        ."<span class='my-auto pe-2'>My Account</span>"
        ."<i class='my-auto fa-solid fa-user'></i>"
        ."</a>"
        ."<a class='button d-inline-flex btn btn-outline-primary my-auto font-weight-bolder me-4' href='logout.php'>"
        ."<span class='my-auto pe-2'>Logout</span>"
        ."<i class='my-auto fa-solid fa-right-from-bracket'></i>"
        ."</a>"
        ."</div>";
      }

      // if user is not logged in show "Signup" and "Login" options
      else{
        echo "<div class='col-md-3 text-end'>"
        ."<a href='cart.php' class='btn btn-warning me-2'><i class='fa-solid fa-cart-shopping'></i> ($count)</a>"
        ."<a class='button d-inline-flex btn btn-outline-primary my-auto font-weight-bolder me-4' href='signup.php'>"
        ."<span class='my-auto pe-2'>Signup</span>"
        ."<i class='my-auto fa-solid fa-user-plus'></i>"
        ."</a>"
        ."<a class='button d-inline-flex btn btn-outline-primary my-auto font-weight-bolder me-4' href='login.php'>"
        ."<span class='my-auto pe-2'>Login</span>"
        ."<i class='my-auto fa-solid fa-right-to-bracket'></i>"
        ."</a>"
        ."</div>";
      }
    ?>
  </header>
</div>