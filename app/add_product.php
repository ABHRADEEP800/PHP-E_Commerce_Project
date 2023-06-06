<?php
// start session
session_start();
if (!isset($_SESSION['admin'])) { // if admin is not logged in
  header('location:  ../admin_login.php');
  exit;
}
// include database connection file
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
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <link rel="stylesheet" href="../assets/css/main.css" />
  <link rel="stylesheet" href="asset/css/main.css" />
  <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<?php
$sql = "SELECT user_email FROM user WHERE user_type='Seller' "; // get all seller email
$result = mysqli_query($conn, $sql);
$user_email = array();
while ($row = mysqli_fetch_assoc($result)) { // store all seller email in array
  $user_email[] = $row['user_email'];
}
$user_email = implode("','", $user_email); // convert array to string
echo "<script>   
  $( function() {
    var availableTags = [

        '$user_email',
      
    ];
    $( '#etags' ).autocomplete({
      source: availableTags
    });
  } );
  </script>";

?>
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

  <div class="container ">
    <form method="post" enctype="multipart/form-data">
      <div class="px-auto">
        <h1 class="text-center">Add New Product</h1>
      </div>
      <div class="flex mx-auto col-lg-6 col-sm-12">
        <div class="form-outline mb-4">
          <label class="form-label" for="form4Example1">Enter Seller Email</label>
          <input type="text" name="user_email" id="etags" placeholder="Enter Seller Email" class="form-control" required />
        </div>
        <!-- Submit button -->
        <div class="d-flex justify-content-center">
          <button type="submit" name="nextt" class="btn btn-primary btn-block  mb-4">Next</button>
        </div>
      </div>
    </form>
  </div>
  <?php
  if (isset($_POST["nextt"])) { // if next button is clicked
    if (empty($_POST["user_email"])) {
      echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['error']('Invalid Email!'); 
            </script>";
      exit;
    } else {
      $user_email = $_POST['user_email']; // get seller email
      $sql = "SELECT user_id FROM user WHERE user_email = '$user_email'"; // get seller id
      $result = mysqli_query($conn, $sql);  // execute query
      $row = mysqli_fetch_assoc($result); // fetch data
      $user_id = $row['user_id']; // store seller id
      echo "<script>window.location.href='cnf_product.php?uId=$user_id'</script>"; // redirect to cnf_product.php page
    }
  }
  ?>

</body>

</html>