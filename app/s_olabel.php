<!----seller login check ---->
<?php
session_start(); // Start the session
if (!isset($_SESSION['seller'])) { // Check if the user is logged in
    header('location: /login.php'); // If user is not logged in then redirect him/her to login page
  exit; // Quit the script
}
// Include the database config file
include 'database.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'>
	<meta http-equiv='X-UA-Compatible' content='IE=edge'>
	<title>Shipping Label</title>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' type='text/css' media='screen' href='main.css'>
	<script src='main.js'></script>
	<link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script
      src="https://kit.fontawesome.com/db79afedbd.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"/>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
    ></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
    ></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
    <link rel="stylesheet" href="asset/card.css" />

	<!-- html to pdf script -->

	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
		<script>
			function generatePDF() {
			const element = document.getElementById('container_content');
			var opt = {
				  margin:       0,
				  filename:     'ShippingLabel.pdf',
				  image:        { type: 'jpeg', quality: 0.98 },
				  html2canvas:  { scale: 2 },
				  jsPDF:        { unit: 'in', format: 'A3', orientation: 'portrait' }
				};
				// Choose the element that our invoice is rendered in.
				html2pdf().set(opt).from(element).save();
			}
		</script>
</head>
<body>
	<div class="container text-center d-flex justify-content-end" style="padding:20px;">
			<button class=" btn btn-primary" onclick="generatePDF()">Download Shipping Label</button>
	</div>

<!---php for data fetch for shipping label--->
<?php 
        include('database.php');
        $id = $_GET['orderId'];
        $semail=$_SESSION['seller'];
        $sql="SELECT * FROM `user` WHERE `user_email`='$semail'";
        $result=mysqli_query($conn,$sql);
        $row0=mysqli_fetch_assoc($result);
        $user_n=$row0['user_name'];
        $sid=$row0['user_id'];
        $warehouse=$row0['warehouse'];
        // warehouse address fetch
        $sql = "SELECT  user.user_name, user.user_email, product.product_name, product.product_id, order_p.order_qu, product.product_price, orders.order_date, orders.order_status, orders.order_id,orders.shipping_address
        FROM orders
        INNER JOIN user ON user.user_id=orders.order_user
        INNER JOIN order_p ON orders.order_id=order_p.order_id 
        INNER JOIN product ON product.product_id=order_p.order_item
        WHERE orders.order_id = '$id' AND product.product_userid='$sid' ";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
?>

<!----shipping label---->
<div class="card" id="container_content">
<main role="main" class="container">
        <div class="row">
            <div class="col-8">
                <div class ="d-flex justify-content-center">
                <img  src="asset/image/logo1.svg" />
                </div>
                <h1 class="text-center">Grapple Inc</h1>
                <h5 class="text-left" style="padding-top:10px;">Seller :</h5>
                <strong class="text-left"><?=$user_n?>
               </strong>
                <p class="text-left"><?=$warehouse?>
                </p>
            </div>
            <div class="col-4 mt-5 border border-dark">
                
                <h4>Order Information</h4>
                <p class="lead">Order Number:<?=$row['order_id']?></p>
                <p>Purchased: <?=$row['order_date']?></p>
                <h4>Shipping To</h4>
                <strong><?=$row['user_name']?></strong>
                <p><?=$row['shipping_address']?></p>
               
            </div>
        </div>
        <hr/>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Item Code</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Quantity</th>
                </tr>
            </thead>
            <tbody>

                <?php
                // fetch order item
                        $sql="SELECT product.product_name, order_p.order_qu,product.product_img,product.product_id
                        FROM order_p
                        INNER JOIN product ON product.product_id=order_p.order_item
                        WHERE order_p.order_id=$id AND product.product_userid=$sid";
                        $result=mysqli_query($conn,$sql);
                        while($row1=mysqli_fetch_assoc($result)){
                ?>

                <tr>
                    <td scope="row"><?=$row1['product_id']?></td>
                    <td><?=$row1['product_name']?></td>
                    <td><?=$row1['order_qu']?></td>
                </tr>
                <?php
                        }
                        ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-12">
                <div class="card bg-faded">
                    <div class="card-header">
                        Return Policy
                    </div>
                    <div class="card-body">
                            <p>Return Period :15 Days.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>

</div>
</body>
</html>