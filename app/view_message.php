<?php
session_start(); //start session
if (!isset($_SESSION['admin'])) { //if admin is not logged in
    header('location:  ../admin_login.php'); // Redirecting To Home Page
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
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="asset/css/main.css" />
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
    <?php
    // php for view message
    $id = $_GET['Id'];
    $sql = "SELECT * FROM contact WHERE `id`='$id'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $email = $row['email'];
        $name = $row['name'];
        $date = $row['date'];
        $message = $row['message'];
    }
    ?>
    <div class="container ">
        <form method="post" enctype="multipart/form-data">
            <div class="px-auto">
                <h1 class="text-center">View Message</h1>
            </div>
            <div class="flex mx-auto col-lg-6 col-sm-12 ">
                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example1">Email</label>
                    <input type="text" name="product_name" id="form4Example1" value="<?= $email ?>" disabled class="form-control" />
                </div>
                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example1">Name</label>
                    <input type="text" name="product_name" id="form4Example1" value="<?= $name ?>" disabled class="form-control" />
                </div>
                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example1">Submission Date</label>
                    <input type="text" name="product_name" id="form4Example1" value="<?= $date ?>" disabled class="form-control" />
                </div>
                <div class="form-outline mb-4">
                    <label class="form-label" for="form4Example1">Message</label>
                    <textarea type="text" name="product_name" id="form4Example1" style="height: 15rem;" disabled class="form-control"><?= $message ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-center mx-auto col-6">
                <a href="message.php" class="btn btn-primary me-5">Back</a>
                <a onClick="confirmDelete()" class="btn btn-danger">Delete</a>
            </div>

        </form>
    </div>

    <script>
        // javascript for delete message
        function confirmDelete() {
            var result = confirm("Are you sure you want to delete this Message?");
            if (result) {
                window.location.href = "delete_message.php?Id=<?php echo $id ?>";
            }
        }
    </script>
</body>

</html>