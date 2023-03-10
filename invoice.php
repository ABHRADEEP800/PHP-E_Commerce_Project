<?php
session_start();

// if user is not logged in, redirect to login page
if (!isset($_SESSION['customer'])) {
    header('location: login.php');
  exit;
}

// including database connection file
include('database.php');

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'>
	<meta http-equiv='X-UA-Compatible' content='IE=edge'>
	<title>Order Invoice</title>
  <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo1.svg">

	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' type='text/css' media='screen' href='main.css'>
	<script src='main.js'></script>
	<link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    />
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
        filename:     'invoice.pdf',
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

<!-- download invoice button -->
	<div class="container text-center d-flex justify-content-end" style="padding:20px;">
			<button class=" btn btn-primary" onclick="generatePDF()">Download Invoice</button>
	</div>


<?php 
  // taking order id from url
  $id = $_GET['orderId'];

  // fetching order details from database
  $sql = "SELECT  user.user_name, user.user_email, product.product_name, product.product_img, order_p.order_qu, product.product_price, orders.order_date, orders.order_status, orders.order_id, orders.discount
  FROM orders
  INNER JOIN user ON user.user_id=orders.order_user
  INNER JOIN order_p ON order_p.order_id=orders.order_id
  INNER JOIN product ON product.product_id=order_p.order_item
  WHERE orders.order_id = '$id'";

  // executing query
  $result = mysqli_query($conn, $sql);

  // fetching result in variable
  $row = mysqli_fetch_assoc($result);

?>

<!------------- main invoice for pdf  -->
<div class="card" id="container_content">
  <div class="card-body">
    <div class="container mb-5 mt-3">
      <div class="row d-flex align-items-baseline">
        <div class="col-xl-9">
          <p style="color: #7e8d9f;font-size: 20px;">Invoice No &gt;&gt; <strong><?=$row['order_id']?></strong></p>
        </div>
      </div>
      <div class="container">
        <div class="col-md-12">
          <div class="text-center">
		  <img src="app/asset/image/logo1.svg" width="150px" alt="">
            <p class="h4">Grapple Inc.</p>
          </div>
        </div>
        <div class="row d-flex align-items-baseline">
          <div class="col-xl-8">
            <ul class="list-unstyled">
              <li class="text-muted">To: <span style="color:#8f8061 ;"><?=$row['user_name']?></span></li>
              <li class="text-muted"><i class="fas fa-envelope"></i> <?=$row['user_email']?></li>
            </ul>
          </div>
          <div class="col-xl-4">
            <p class="text-muted">Invoice</p>
            <ul class="list-unstyled">
              <li class="text-muted"><i class="fas fa-circle" style="color:#8f8061 ;"></i> <span
                  class="fw-bold">ID: </span>INV<?=$row['order_id']?></li>
              <li class="text-muted"><i class="fas fa-circle" style="color:#8f8061 ;"></i> <span
                  class="fw-bold">Ordered Date: </span><?=$row['order_date']?></li>
            </ul>
          </div>
        </div>
        <?php
          // declaring total variable
          $mtotal=0;

          // fetching order details from database
          $sql="SELECT product.product_name, order_p.order_qu,product.product_img,order_p.price
          FROM order_p
          INNER JOIN product ON product.product_id=order_p.order_item
          WHERE order_p.order_id=$id";
          $result=mysqli_query($conn,$sql);

          // looping through all the order details
          while($row1=mysqli_fetch_assoc($result)){
        ?>
        <div class="row my-2 mx-1 justify-content-center">
          <div class="col-md-2 mb-4 mb-md-0">
            <div class="
                        bg-image
                        ripple
                        rounded-5
                        mb-4
                        overflow-hidden
                        d-block
                        " data-ripple-color="light">
                       
              <img src="app/<?=$row1['product_img']?>"
                width="150px" height="100px" alt="" />
              <a href="#!">
                <div class="hover-overlay">
                  <div class="mask" style="background-color: hsla(0, 0%, 98.4%, 0.2)"></div>
                </div>
              </a>
            </div>
          </div>
          <div class="col-md-7 mb-4 mb-md-0">
            <p class="fw-bold"><?=$row1['product_name']?></p>
            <p class="mb-1">
              <span class="text-muted me-2">Quantity:</span><span><?=$row1['order_qu']?> Pcs</span>
            </p>
          </div>
          <div class="col-md-3 mb-4 mb-md-0">
            <h5 class="mb-2">
              <span class="align-middle">??? <?=$row1['price']?>/ Per Product</span>
            </h5>
          </div>
        </div>
        <?php
          
          // calculating total
          $total=$row1['price']*$row1['order_qu'];
          
          // adding total to main total
          $mtotal=$mtotal+$total;
          
          } 
        ?>
        <hr>
        <div class="row">
          <div class="col-xl-8">
            <p class="ms-3">Thank You ! </p>
          </div>
          <div class="col-xl-3">
            <ul class="list-unstyled">

              <!-- Showing grand total -->
              <li class="text-muted ms-3"><span class="text-black me-4">Sub Total</span>??? <?=$mtotal?></li>
              <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Shipping</span>???0 </li>
              <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Discount</span><?=$row['discount']?> % </li>
            </ul>
            <p class="text-black float-start"><span class="text-black me-3"> Total Amount</span><span
                style="font-size: 25px;">??? <?=$mtotal-($mtotal*$row['discount']/100)?></span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>