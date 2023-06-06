<?php
session_start(); // Start the session
if (!isset($_SESSION['admin'])) { // Check if the user is logged in
  header('location:  ../admin_login.php'); // If not, redirect to login page
  exit; // Stop the script
}
// Include the database connection.
require('../env/database.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="asset/image/logo-bg.svg">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
  <link rel="stylesheet" href="asset/card.css" />
  <link rel="stylesheet" href="asset/css/main.css" />
  <link rel="stylesheet" href="../assets/css/main.css" />
</head>
<?php
include 'navbar.php';

?>

<body>
  <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
  <div id="loading">
    <img src="asset/svg-logo/LOADER.svg" alt="Loading..." />
  </div>
  <script>
    var loader = document.getElementById("loading");
    window.addEventListener("load", function() {
      loader.style.display = "none";
    })
  </script>
  <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="text-center">User Management</h1>
      </div>
    </div>
    <div class="pt-5">
      <form action="user_mgmt.php" method="GET">
        <div class="d-flex pb-3">

          <div class="col-7 d-flex">
            <div class="col-lg-8 col-sm-10">
              <input class="search_bar form-control border-end-0 border rounded-pill" type="text" name="search" placeholder="Search Email">
            </div>
            <div class="col-lg-4 col-sm-2 ms-2">
              <button type="submit" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" name="submit-search">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>

          <div class="col-5">
            <!-- add product  -->

            <a href="add_user.php" class="btn btn-success float-end">
              <i class="fa fa-plus"></i>
              Add User
            </a>
          </div>
        </div>
      </form>
      <div class="table-responsive">

        <table class="table" border="1">
          <tr>
            <th>User Name</th>
            <th>User Email</th>
            <th>User Type</th>
            <th>Edit</th>
            <th>Reset Password</th>
          </tr>
          <?php

          $grapple = null;

          if (isset($_GET['page']) && $_GET['page'] != "") {
            $page_no = $_GET['page'];
          } else {
            $page_no = 1;
          }

          $total_records_per_page = 10;
          $offset = ($page_no - 1) * $total_records_per_page;
          $previous_page = $page_no - 1;
          $next_page = $page_no + 1;
          $adjacents = "2"; //starting point of each page


          // search
          if (isset($_GET['search'])) {
            $search = mysqli_real_escape_string($conn, $_GET['search']);
            $sql = "SELECT * FROM user WHERE user_email LIKE '%$search%' LIMIT $offset, $total_records_per_page";
            $result1 = $conn->query("SELECT count(user_id) AS user_id FROM user WHERE user_email LIKE '%$search%'");
            $grapple = "&search=$search";
          } else {
            $sql = "SELECT * FROM user LIMIT $offset, $total_records_per_page";
            $result1 = $conn->query("SELECT count(user_id) AS user_id FROM user");
          }

          $countAll = $result1->fetch_all(MYSQLI_ASSOC);

          $total_records = $countAll[0]['user_id']; //total number of records
          $total_no_of_pages = ceil($total_records / $total_records_per_page);
          $second_last = $total_no_of_pages - 1; // total page minus 

          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['user_id'];
            $name = $row['user_name'];
            $type = $row['user_type'];
            $email = $row['user_email'];
          ?>
            <tr>
              <td><?php echo $name ?></td>
              <td><?php echo $email ?></td>
              <td><?php echo $type ?></td>
              <td><a href="edit_user.php?userId=<?php echo $id ?>" class="btn btn-primary">Edit</a></td>
              <td> <a href="reset_upass.php?userId=<?php echo $id ?>" class="btn btn-warning">Reset Password</a></td>

            </tr>
          <?php
          }
          ?>
        </table>
      </div>

      <div class="row mt-3">
        <div class="col-lg-3 col-sm-12" style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
          <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
        </div>
        <nav aria-label="..." class="col-lg-9 col-sm-12 d-flex justify-content-end">
          <ul class="pagination">
            <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } 
            ?>

            <li class="page-item <?php if ($page_no <= 1) {
                                    echo 'disabled';
                                  } ?>">
              <a class="page-link" <?php if ($page_no > 1) {
                                      echo "href='?page=$previous_page $grapple'";
                                    } ?>>Previous</a>
            </li>

            <?php
            if ($total_no_of_pages <= 1) {
              echo "<li class='page-item active'><a class='page-link'>1</a></li>";
            } elseif ($total_no_of_pages <= 7) {
              for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                if ($counter == $page_no) {
                  echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                } else {
                  echo "<li class='page-item'><a class='page-link' href='?page=$counter $grapple'>$counter</a></li>";
                }
              }
            } else {
              if ($page_no <= 2) {
                for ($counter = 1; $counter <= 3; $counter++) {
                  if ($counter == $page_no) {
                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                  } else {
                    echo "<li class='page-item'><a class='page-link' href='?page=$counter $grapple'>$counter</a></li>";
                  }
                }
                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                echo "<li class='page-item'><a class='page-link' href='?page=$total_no_of_pages $grapple'>$total_no_of_pages</a></li>";
              } elseif ($page_no > 2 && $page_no < $total_no_of_pages - 1) {
                echo "<li class='page-item'><a class='page-link' href='?page=1 $grapple'>1</a></li>";
                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                for ($counter = $page_no - 1; $counter <= $page_no + 1; $counter++) {
                  if ($counter == $page_no) {
                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                  } else {
                    echo "<li class='page-item'><a class='page-link' href='?page=$counter $grapple'>$counter</a></li>";
                  }
                }
                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                echo "<li class='page-item'><a class='page-link' href='?page=$total_no_of_pages $grapple'>$total_no_of_pages</a></li>";
              } else {
                echo "<li class='page-item'><a class='page-link' href='?page=1 $grapple'>1</a></li>";
                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                for ($counter = $total_no_of_pages - 2; $counter <= $total_no_of_pages; $counter++) {
                  if ($counter == $page_no) {
                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                  } else {
                    echo "<li class='page-item'><a class='page-link' href='?page=$counter $grapple'>$counter</a></li>";
                  }
                }
              }
            }
            ?>

            <li class="page-item <?php if ($page_no >= $total_no_of_pages) {
                                    echo 'disabled';
                                  } ?>">
              <a class="page-link" <?php if ($page_no < $total_no_of_pages) {
                                      echo "href='?page=$next_page $grapple'";
                                    } ?>>Next</a>
            </li>
          </ul>
        </nav>

      </div>

</body>

</html>