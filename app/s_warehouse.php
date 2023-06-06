<!------seller login check--->
<?php
session_start();    // Starting Session
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
</head>

<?php
include 'navbar_s.php';
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Warehouse Address</h1>
            </div>
        </div>

        <!---Show product by search or show all products--->

        <table class="table" border="1">
            <tr>
                <th>Warehouse Address</th>

                <th>Update</th>

            </tr>
            <?php
            // php for warehouse address
            $semail = $_SESSION['seller'];
            $sql = "SELECT * FROM `user` WHERE `user_email`='$semail'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['user_id'];
            $address = $row['warehouse'];
            // if address is empty
            if ($address == "") {
                $address = "No Address Found , Please Update Your Warehouse adress.";
            }
            ?>
            <tr>
                <td><?php echo $address ?></td>
                <td> <a href="s_wupdate.php?userId=<?php echo $user_id ?>" class="btn btn-success">Update</a></td>
            </tr>
        </table>
</body>

</html>