<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--
    - favicon
  -->
  <link rel="shortcut icon" href="./assets/images/logo/favicon.ico" type="image/x-icon">
  <!--
    - custom css link
  -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <!--
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


</head>
<!--
    - HEADER
  -->
<div class="overlay" data-overlay></div>
<header class="mb-2" id="header">

  <div class="header-top">

    <div class="container">

      <ul class="header-social-container">

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-facebook"></ion-icon>
          </a>
        </li>

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-twitter"></ion-icon>
          </a>
        </li>

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-instagram"></ion-icon>
          </a>
        </li>

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-linkedin"></ion-icon>
          </a>
        </li>

      </ul>

      <div class="header-alert-news">
        <p>
          <b>Free Shipping</b>
          - On All Orders
        </p>
      </div>

      <div class="header-top-actions">

        <select name="currency">

          <option value="inr">INR ₹</option>


        </select>

        <select name="language">

          <option value="en-US">English</option>


        </select>

      </div>

    </div>

  </div>

  <div class="header-main">

    <div class="container">

      <a href="index.php" class="header-logo">
        <img src="assets/svg-logo/logo-bg.svg" alt="Grapple logo" width="100" height="100">
      </a>

      <div class="">


        <nav class="desktop-navigation-menu">

          <div class="container">

            <ul class="desktop-menu-category-list">

              <li class="menu-category">
                <a href="index.php" class="menu-title">Home</a>
              </li>

              <li class="menu-category">
                <a href="products.php" class="menu-title">Products</a>

                <ul class="dropdown-list">
                  <?php
                  $sql = "SELECT * FROM category";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                    <li class="dropdown-item">
                      <a href="products.php?c=<?php echo $row['c_name']; ?>"><?php echo $row['c_name']; ?></a>
                    </li>
                  <?php } ?>
                </ul>
              </li>
              <li class="menu-category">
                <a href="privacy_policy.php" class="menu-title">Privacy Policy</a>
              </li>

              <li class="menu-category">
                <a href="contact_us.php" class="menu-title">Contact Us</a>
              </li>

            </ul>

          </div>

        </nav>
        <?php

        // count cart items
        $count = 0;

        // if cart session variable is set
        if (isset($_SESSION['cart'])) {
          $count = count($_SESSION['cart']);
        }
        ?>


      </div>

      <div class="header-user-actions">
        <nav class="desktop-navigation-menu">

          <div class="container">

            <ul class="desktop-menu-category-list">
              <li class="menu-category">

                <?php
                if (isset($_SESSION['customer'])) {
                ?>

                  <a href="account.php" class="action-btn">
                    <ion-icon name="person-outline"></ion-icon>
                  </a>
                  <ul class="dropdown-list">

                    <li class="dropdown-item">
                      <a href="account.php">My Account</a>
                    </li>
                    <li class="dropdown-item">
                      <a href="logout.php">Logout</a>
                    </li>
                  <?php
                } else {
                  ?>
                    <a href="login.php" class="action-btn">
                      <ion-icon name="person-outline"></ion-icon>
                    </a>
                    <ul class="dropdown-list">
                      <li class="dropdown-item">
                        <a href="login.php">Login</a>
                      </li>
                      <li class="dropdown-item">
                        <a href="signup.php">Register</a>
                      </li>
                    <?php } ?>


                    </ul>
              </li>



              <a href="cart.php" class="action-btn">
                <ion-icon name="bag-handle-outline"></ion-icon>
                <span class="count" id="count"><?= $count ?></span>
              </a>

          </div>

      </div>


    </div>


    <div class="container px-5 ">
      <div class="header-search-container">
        <form action="products.php" method="GET">

          <input type="search" name="search" class="search-field" placeholder="Enter product name...">

          <button type="submit" name='submit-search' class="search-btn">
            <ion-icon name="search-outline"></ion-icon>
          </button>
        </form>

      </div>
    </div>

    <div class="mobile-bottom-navigation">

      <button class="action-btn" onclick="openNav();" data-mobile-menu-open-btn>
        <ion-icon name="menu-outline"></ion-icon>
      </button>

      <a href="products.php" class="action-btn">
        <ion-icon name="cube-outline"></ion-icon>
      </a>

      <a href="index.php" class="action-btn">
        <ion-icon name="home-outline"></ion-icon>
      </a>



      <a href="cart.php" class="action-btn">
        <ion-icon name="bag-handle-outline"></ion-icon>

        <span class="count"><?= $count ?></span>
      </a>


</header>

<div id="main">
</div>
<style>
  .sidebar1 {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 30;
    top: 0;
    left: -2px;
    background-color: #ffffff;
    overflow-x: hidden;
    transition: 0.3s;
    border-right: 1px solid black;
  }

  .sidebar1 a:hover {
    color: #f1f1f1;
  }


  .sidebar1 .closebtn {
    position: absolute;
    top: 0;
    right: 10px;
    font-size: 36px;

  }

  #main {
    transition: margin-left .5s;
    padding: 16px;
  }

  .nav_link_btn {
    padding: 8px 8px 8px 32px;
    text-decoration: none;
    font-size: 20px;
    color: #000000;
    display: block;
    transition: 0.3s;
    border-bottom: 1px solid black;
  }

  .nav_link_btn_dropdown {
    display: none;
  }

  .nav_link_btn i {
    color: #5d93fd;
  }

  .nav_link_btn_my_acc {
    width: 80%;
  }

  /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
  @media screen and (max-height: 450px) {
    .sidebar1 {
      padding-top: 15px;
    }

    .sidebar1 a {
      font-size: 18px;
    }
  }
</style>

<!---------Sidebar 1--->
<div id="mySidebar" class="sidebar1">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fa-sharp fa-solid fa-xmark fa-xs" style="color: #000000;"></i></a>
  <img class="p-2" src="assets/svg-logo/logo-bg.svg" alt="logo" width="70px" height="70">
  <a class="nav_link_btn" href="index.php"><i class="fa-solid fa-house"></i> Home</a>
  <?php
  // if user is logged in show "My Account" and "Logout" options
  if (isset($_SESSION['customer'])) {

  ?>
    <div class="d-flex">

      <a class="nav_link_btn nav_link_btn_my_acc" href="account.php"><i class="fa-solid fa-user"></i> My Account</a>
      <div>
        <!-- dropdown arrow button -->
        <button class="nav_link_btn nav_link_btn_dropdown_toggle">
          <i class="fa-solid fa-chevron-down"></i>
        </button>
      </div>
    </div>
    <div class="nav_link_btn nav_link_btn_dropdown ">
      <li><a class="dropdown-item" href="account.php?tab=orders">My Orders</a></li>
      <li><a class="dropdown-item" href="account.php?tab=membership">Membership</a></li>
      <li><a class="dropdown-item" href="account.php?tab=c_pass">Change Password</a></li>
      <li><a class="dropdown-item" href="account.php?tab=seller">Become Seller</a></li>
    </div>
  <?php
  }

  // if user is not logged in show "Signup" and "Login" options
  else {

  ?>
    <!--  -->
    <a class="nav_link_btn" href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
    <a class="nav_link_btn" href="signup.php"><i class="fa-solid fa-user-plus"></i> Register</a>
  <?php
  }
  ?>
  <a class="nav_link_btn" href="contact_us.php"><i class="fa-solid fa-envelope"></i> Contact Us</a>
  <?php
  // if user is logged in show "My Account" and "Logout" options
  if (isset($_SESSION['customer'])) {

  ?>
    <a class="nav_link_btn" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  <?php
  }
  ?>


</div>



</div>
<script>
  var status = 0;

  function openNav() {
    if (status == 0) {
      document.getElementById("mySidebar").style.width = "250px";
      document.getElementById("main").style.marginLeft = "250px";
      status = 1;
      document.querySelector('[data-overlay]').classList.add('active');
    } else {
      closeNav();
      status = 0;
    }
  }

  function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
    document.querySelector('[data-overlay]').classList.remove('active');
  }


  $(document).ready(function() {
    $(".nav_link_btn_dropdown_toggle").click(function() {
      $(".nav_link_btn_dropdown").toggle();
      // $(".fa-chevron-down").css('transform', 'rotate(180deg) scaleX(-1)');
    });
  });
</script>