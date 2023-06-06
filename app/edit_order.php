<?php
session_start(); // Start the session
if (!isset($_SESSION['admin'])) { // If no session value is present, redirect the user:
    header('location:  ../admin_login.php');
    exit;
}
require('../env/database.php'); // Include the database connection.
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
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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

    <?php
    $id = $_GET['orderId']; // get the order id from the url
    $sql = "SELECT user.user_email, user.user_name, product.product_name, order_p.order_qu, orders.order_date, orders.order_status, orders.order_id
                FROM orders
                INNER JOIN user ON user.user_id=orders.order_user
                INNER JOIN order_p ON order_p.order_id=orders.order_id
                INNER JOIN product ON product.product_id=order_p.order_item
                WHERE orders.order_id='$id'"; // get the order details from the database
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['order_id'];
        $user_name = $row['user_name'];
        $order_date = $row['order_date'];
        $order_status = $row['order_status'];
        $email = $row['user_email'];
    ?>

        <div class="container ">
            <form method="post" enctype="multipart/form-data">
                <div class="px-auto">
                    <h1 class="text-center">Edit Order Data</h1>
                </div>

                <div class="flex mx-auto col-lg-6 col-sm-12">
                    <div class="form-outline mb-4">
                        <label class="form-label h3" for="form4Example1">User Name</label>
                        <input type="text" disabled id="form4Example1" value="<?= $user_name ?>" class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label h3" for="form4Example2">Ordered Date</label>
                        <input type="text" disabled id="form4Example2" value="<?= $order_date ?>" class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <table class="table">

                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                            </tr>
                            <?php
                            // get the order id from the url
                            $sql = "SELECT product.product_name, order_p.order_qu
                         FROM order_p
                          INNER JOIN product ON product.product_id=order_p.order_item
                           WHERE order_p.order_id=$id"; // get the order details from the database
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $product_name = $row['product_name'];
                                $product_quantity = $row['order_qu'];
                                echo " <tr>
                          <td>$product_name</td>
                         <td>$product_quantity Pcs</td>
                            </tr>";
                            }
                            ?>
                        </table>

                        <div class="form-outline mb-4">
                            <label class="form-label h3" for="form4Example3">Order Status</label>
                            <select name="order_status" class="form-select" aria-label="select example">
                                <option value="<?= $order_status ?>"><?= $order_status ?></option>
                            <?php
                            // If the order status is placed, show the shipped option
                            if ($order_status == "Placed") {
                                echo "<option value='Shipped'>Shipped</option>";
                            }
                            // If the order status is shipped, show the delivered option
                            if ($order_status == "Shipped") {
                                echo "<option value='Delivered'>Delivered</option>";
                            }
                        }
                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- Submit button -->
                    <div class="d-flex justify-content-center">
                        <button type="submit" name="update" class="btn btn-primary btn-block  mb-4">Update Order Status</button>
                    </div>

            </form>
        </div>

        <?php
        ?>

        <?php
        // update the order status
        if (isset($_POST["update"])) {
            $order_status = $_POST['order_status'];
            $sql = "UPDATE orders SET  order_status ='$order_status' WHERE order_id ='$id';";
            if (mysqli_query($conn, $sql)) {
                require '../env/order_smtp.php';
                $mail->addAddress($email);     //Add a recipient

                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Order ' . $order_status . ' .';
                $mail->Body    = 'Your order has been ' . $order_status . '. <br>Thank you for shopping with us.<br>Order No.' . $id . '<br><br>Regards,<br>Team Grapple Inc. ';

                if ($mail->send()) {
                    echo "<script>
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['success']('Order status updated Successfully');
                setTimeout(function(){ window.location.href='order_mgmt.php'; }, 5000);
                </script>";
                } else {
                    echo "<script>
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['error']('Something Went wrong!');
                </script>";
                }
            } else {
                echo "<script>
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['error']('Something Went wrong!');
                </script>";
            }
        }
        ?>
</body>

</html>