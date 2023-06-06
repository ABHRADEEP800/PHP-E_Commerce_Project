<!------seller login check--->
<?php
session_start(); // Starting Session
if (!isset($_SESSION['seller'])) { //if not logged in
    header('location:  ../login.php'); // Redirecting To Home Page
    exit; // stop further executing, very important
}

// database connection
require('../env/database.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
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
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="asset/css/main.css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<?php
include 'navbar_s.php';
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
    <!--------------------------------body ---------------------- -->
    <div class="container d-flex justify-content-center" id="container">
        <div class="card mt-3">
            <div class="card-header text-center">
                <h3>Two Factor Authentication</h3>
                <div>
                    <div class="card-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-10">
                                    <p class="mb-0">Two Step Verification(2FA)</p>
                                    <p>*Email Otp</p>
                                </div>
                                <div class="col-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="2fa" role="switch" id="flexSwitchCheckDefault" <?php
                                                                                                                                                $sql = "SELECT * FROM user WHERE user_email = '" . $_SESSION['seller'] . "'";
                                                                                                                                                $result = mysqli_query($conn, $sql);
                                                                                                                                                $row = mysqli_fetch_assoc($result);
                                                                                                                                                $tfa = $row['2fa'];
                                                                                                                                                if ($tfa == 'ON') {
                                                                                                                                                    echo "checked";
                                                                                                                                                }

                                                                                                                                                ?> />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <input type="submit" name="update" class="btn btn-primary form-control mx-auto" value="Update">
                        </form>
                    </div>
                </div>
            </div>
</body>

</html>
<?php

if (isset($_POST['update'])) {
    $twfa = 'OFF';
    if (isset($_POST['2fa'])) {
        $twfa = 'ON';
    }
    $sql = "UPDATE user SET 2fa = '$twfa' WHERE user_email = '" . $_SESSION['seller'] . "'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['success']('Updated Successfully'); 
            $('#container').load('s_twoFa.php #container');
            </script>";
    } else {
        echo "<script>
            toastr.options.closeButton = true;
            toastr.options.progressBar = true;
            toastr['error']('Something Went Wrong!'); 
            </script>";
    }
}

?>