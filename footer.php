<!--
  - FOOTER
  -->

<footer class="mt-3">



  <div class="footer-nav">

    <div class="container">
      <div class="footer-nav-list">
        <li class="footer-nav-item">
          <div class="rounded-circle bg-brown shadow-1-strong d-flex align-items-center justify-content-center  mx-auto" style="width: 150px; height: 150px; margin-top: -55px; ">
            <img src="assets/svg-logo/logo-bg-foot.svg" height="105" width="150" alt="" loading="lazy" />
          </div>
        </li>
        <li class="footer-nav-item">
          <p class=" copyright text-center">Grapple Inc</p>
        </li>
        <ul class="list-unstyled d-flex flex-row justify-content-center">
          <li>
            <a class="text-white footer-nav-item  px-2" href="#!">
              <i class="fab fa-facebook-square"></i>
            </a>
          </li>
          <li>
            <a class="text-white footer-nav-item px-2" href="#!">
              <i class="fab fa-instagram"></i>
            </a>
          </li>
          <li>
            <a class="text-white footer-nav-item ps-2" href="#!">
              <i class="fab fa-youtube"></i>
            </a>
          </li>
        </ul>
      </div>

      <ul class="footer-nav-list">

        <li class="footer-nav-item">
          <h2 class="nav-title">Sitemap</h2>
        </li>

        <li class="footer-nav-item">
          <a href="index.php" class="footer-nav-link">Home</a>
        </li>

        <li class="footer-nav-item">
          <a href="products.php" class="footer-nav-link">Products</a>
        </li>

        <li class="footer-nav-item">
          <a href="privacy_policy.php" class="footer-nav-link">Privacy Policy</a>
        </li>

        <li class="footer-nav-item">
          <a href="contact_us.php" class="footer-nav-link">Contact Us</a>
        </li>



      </ul>

      <ul class="footer-nav-list">

        <li class="footer-nav-item">
          <h2 class="nav-title">Categories</h2>
        </li>
        <?php
        $sql = "SELECT * FROM category";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
          $category_name = $row['c_name'];
        ?>
          <li class="footer-nav-item">
            <a href="products.php?c=<?= $category_name ?>" class="footer-nav-link"><?= $category_name ?></a>
          </li>
        <?php
        }
        ?>



      </ul>


      <ul class="footer-nav-list pl-5">

        <li class="footer-nav-item">
          <h2 class="nav-title">Contact</h2>
        </li>

        <li class="footer-nav-item flex">
          <div class="icon-box">
            <ion-icon name="location-outline"></ion-icon>
          </div>

          <address class="content">
            Barasat, North 24 Parganas, Kolkata - 700 125
          </address>
        </li>

        <li class="footer-nav-item flex">
          <div class="icon-box">
            <ion-icon name="call-outline"></ion-icon>
          </div>

          <a href="tel:+911234567890" class="footer-nav-link">(91) 1234567890</a>
        </li>

        <li class="footer-nav-item flex">
          <div class="icon-box">
            <ion-icon name="mail-outline"></ion-icon>
          </div>

          <a href="mailto:hello@abhradeep.com" class="footer-nav-link">hello@abhradeep.com</a>
        </li>

      </ul>

      <div class="footer-nav-list">
        <div class="footer-nav-item">

          <?php
          if (isset($_POST['subscribe'])) {
            $email = $_POST['email'];
            $sql = "SELECT * FROM `subscribe` WHERE `email` = '$email'";
            $result = mysqli_query($conn, $sql);
            $rownum = mysqli_num_rows($result);
            if ($rownum == 0) {
              $sql = "INSERT INTO `subscribe` (`email`) VALUES ('$email')";
              $result = mysqli_query($conn, $sql);
              if ($result) {
                echo "<script>
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['success']('You have subscribed to our newsletter');
                </script>";
              }
            } else {
              echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['error']('You have already subscribed to our newsletter.'); 
            </script>";
            }
          }
          ?>
          <form method="post">
            <h5 class="nav-title">Subscribe to our newsletter</h5>
            <p class="footer-nav-link">Monthly digest of what's new<br> and exciting from us.</p>
            <div class="d-flex flex-column flex-sm-row w-100 gap-1">
              <label for="newsletter1" class="visually-hidden">Email address</label>
              <input id="newsletter1" name="email" type="email" class="form-control" placeholder="Email ">
              <button class="btn btn-primary" name="subscribe" type="submit">Subscribe</button>
            </div>
          </form>
        </div>

      </div>


      <ul class="footer-nav-list">

        <li class="footer-nav-item">
          <h2 class="nav-title">Follow Us</h2>
        </li>

        <li>
          <ul class="social-link">

            <li class="footer-nav-item">
              <a href="#" class="footer-nav-link">
                <ion-icon name="logo-facebook"></ion-icon>
              </a>
            </li>

            <li class="footer-nav-item">
              <a href="#" class="footer-nav-link">
                <ion-icon name="logo-twitter"></ion-icon>
              </a>
            </li>

            <li class="footer-nav-item">
              <a href="#" class="footer-nav-link">
                <ion-icon name="logo-linkedin"></ion-icon>
              </a>
            </li>

            <li class="footer-nav-item">
              <a href="#" class="footer-nav-link">
                <ion-icon name="logo-instagram"></ion-icon>
              </a>
            </li>

          </ul>
        </li>

      </ul>

    </div>

  </div>

  <div class="footer-bottom">

    <div class="container">


      <p class="copyright">
        Copyright &copy; Grapple Inc. all rights reserved.
      </p>

    </div>

  </div>

</footer>
<!--
    - custom js link
  -->
<script src="assets/js/script.js"></script>


<!--
    - ionicon link
  -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>