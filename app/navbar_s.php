<style>
  :root {
    --header-height: 3rem;
    --nav-width: 68px;
    --first-color: #4723d9;
    --first-color-light: #afa5d9;
    --white-color: #f7f6fb;
    --normal-font-size: 1rem;
    --z-fixed: 100;
  }

  *,
  ::before,
  ::after {
    box-sizing: border-box;
  }

  body {
    position: relative;
    margin: var(--header-height) 0 0 0;
    padding: 0 1rem;
    font-size: var(--normal-font-size);
    transition: 0.5s;
  }

  a {
    text-decoration: none;
  }

  .header {
    width: 100%;
    height: var(--header-height);
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
    background-color: var(--white-color);
    z-index: var(--z-fixed);
    transition: 0.5s;
  }

  .header_toggle {
    color: var(--first-color);
    font-size: 1.5rem;
    cursor: pointer;
  }

  .header_img {
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    border-radius: 50%;
    overflow: hidden;
  }

  .header_img img {
    width: 45px;
  }

  .l-navbar {
    position: fixed;
    top: 0;
    left: -30%;
    width: var(--nav-width);
    height: 100vh;
    background-color: var(--first-color);
    padding: 0.5rem 1rem 0 0;
    transition: 0.5s;
    z-index: var(--z-fixed);
  }

  .nav {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
  }

  .nav_logo,
  .nav_link {
    display: grid;
    grid-template-columns: max-content max-content;
    align-items: center;
    column-gap: 1rem;
    padding: 0.5rem 0 0.5rem 1.5rem;
  }

  .nav_logo {
    margin-bottom: 2rem;
  }

  .nav_logo-icon {
    font-size: 1.25rem;
    color: var(--white-color);
  }

  .nav_logo-name {
    color: var(--white-color);
    font-weight: 700;
  }

  .nav_link {
    position: relative;
    color: var(--first-color-light);
    margin-bottom: 1.5rem;
    transition: 0.3s;
  }

  .nav_link:hover {
    color: var(--white-color);
  }

  .nav_icon {
    font-size: 1.25rem;
  }

  .show {
    left: 0;
  }

  .body-pd {
    padding-left: calc(var(--nav-width) + 1rem);
  }

  .active {
    color: var(--white-color);
  }

  .active::before {
    content: "";
    position: absolute;
    left: 0;
    width: 2px;
    height: 32px;
    background-color: var(--white-color);
  }

  .height-100 {
    height: 100vh;
  }

  @media screen and (min-width: 768px) {
    body {
      margin: calc(var(--header-height) + 1rem) 0 0 0;
      padding-left: calc(var(--nav-width) + 2rem);
    }

    .header {
      height: calc(var(--header-height) + 1rem);
      padding: 0 2rem 0 calc(var(--nav-width) + 2rem);
    }

    .header_img {
      width: 45px;
      height: 45px;
    }

    .header_img img {
      width: 50px;
    }

    .l-navbar {
      left: 0;
      padding: 1rem 1rem 0 0;
    }

    .show {
      width: calc(var(--nav-width) + 156px);
    }

    .body-pd {
      padding-left: calc(var(--nav-width) + 188px);
    }
  }
</style>

<body id="body-pd">
  <header class="header" id="header">
    <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
    <div class="header_img"> <img src="asset/image/logo-bg.svg" alt=""> </div>
  </header>
  <div class="l-navbar" id="nav-bar">
    <nav class="nav">
      <div> <a href="seller_index.php" class="nav_logo bg-dark"> <i class="fa-solid fa-g"></i><span class="nav_logo-name">Grapple</span> </a>
        <div class="nav_list"> <a href="seller_index.php" class="nav_link active"> <i class='bx bx-grid-alt nav_icon'></i>

            <span class="nav_name">Dashboard</span> </a><a href="s_pmgmt.php" class="nav_link"> <i class='bx bx-message-square-detail nav_icon'></i> <span class="nav_name">Product Managment</span> </a> <a href="s_omgmt.php" class="nav_link"> <i class="fa-solid fa-cart-shopping"></i> <span class="nav_name">Order Management</span> </a> <a href="s_warehouse.php" class="nav_link"> <i class="fa-solid fa-warehouse"></i> <span class="nav_name">Warehouse Location</span> </a><a href="s_cpass.php" class="nav_link"> <i class="fa-solid fa-key"></i> <span class="nav_name">Change Password</span> </a> <a href="s_twoFa.php" class="nav_link"> <i class="fa-solid fa-user-shield"></i> <span class="nav_name">Two Step Verification</span> </a> </div>
      </div> <a href="s_logout.php" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">SignOut</span> </a>
    </nav>
  </div>
  <!----------------------------------------------- Javascript -->
  <script>
    document.addEventListener("DOMContentLoaded", function(event) {

      const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
          nav = document.getElementById(navId),
          bodypd = document.getElementById(bodyId),
          headerpd = document.getElementById(headerId)

        // Validate that all variables exist
        if (toggle && nav && bodypd && headerpd) {
          toggle.addEventListener('click', () => {
            // show navbar
            nav.classList.toggle('show')
            // change icon
            toggle.classList.toggle('bx-x')
            // add padding to body
            bodypd.classList.toggle('body-pd')
            // add padding to header
            headerpd.classList.toggle('body-pd')
          })
        }
      }

      showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

      /*===== LINK ACTIVE =====*/
      const linkColor = document.querySelectorAll('.nav_link')

      function colorLink() {
        if (linkColor) {
          linkColor.forEach(l => l.classList.remove('active'))
          this.classList.add('active')
        }
      }
      linkColor.forEach(l => l.addEventListener('click', colorLink))

      // code to run since DOM is loaded and ready
    });
    //javascript to chcek what is url and match it with nav-link if match add class active to the nav-link
    var url = window.location.href;
    //REMOVE GET DATA FROM URL
    url = url.split('?')[0];
    var activePage = url;
    $('.nav_link').each(function() {
      var linkPage = this.href;
      if (activePage == linkPage) {
        $(".nav_link").removeClass("active");
        $(this).addClass("active");
      } else {
        $(this).removeClass("active");
      }
    });
  </script>

  <style>
    .l-navbar {
      height: 100%;
    }
  </style>