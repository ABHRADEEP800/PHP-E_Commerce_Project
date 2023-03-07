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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
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
    <link rel="stylesheet" href="asset/css/main.css" />
</head>
<?php
    // warehouse address check
    include 'navbar_s.php';
    $semail=$_SESSION['seller']; 
    $sql="SELECT * FROM `user` WHERE `user_email`='$semail'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);
    $address=$row['warehouse']; 

    // if warehouse address is not updated then redirect to update warehouse address page
    if($address==""){
        echo '<script>alert("Please Update Your Warehouse Address");</script>';  
        echo '<script>window.location.href ="s_warehouse.php";</script>';
    exit; // Quit the script
    } 
?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Order Management</h1>
            </div>
        </div>
    <div class="pt-5">
        <div class="d-flex pb-3">
            <div class="ps-3">
                <form action="s_omgmt.php" method="POST">
                    <label>
                        Enter Date to Search Order:-
                    </label><br>
                    <div class="d-flex">
                        <input type="date" class="search_bar form-control border-end-0 border rounded-pill" name="order_date" required pattern="\d{4}-\d{2}-\d{2}" />
                        <button type="submit" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill mx-4" name="submit-search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
<!-------------show order by date search or show all orders--->
<div class="table-responsive">
        <table class="table" border="1" >
            <tr>
                <th>User Name</th>
                <th>Ordered Date</th>
                <th>Order Status</th>
                <th>Order Item</th>
                <th>Invoice</th>
                <th>Shipping Label</th>
                <th>Edit</th>
               
            </tr>
            <?php
            
            $semail=$_SESSION['seller']; // get seller email from session
            $sql="SELECT * FROM `user` WHERE `user_email`='$semail'"; // get seller id from user table
            $result=mysqli_query($conn,$sql); 
            $row=mysqli_fetch_assoc($result);   
            $user_id=$row['user_id']; // get seller id from user table


            $limit = 5; // limit of orders per page
            $page = isset($_GET['page']) ? $_GET['page'] : 1; // get page number
            $start = ($page -1) * $limit; // get start of orders

            $result1 = $conn->query("SELECT count(orders.order_id) AS order_id FROM orders INNER JOIN order_p ON orders.order_id=order_p.order_id INNER JOIN product ON product.product_id=order_p.order_item INNER JOIN user ON user.user_id=orders.order_user WHERE product.product_userid ='$user_id'");
            $countAll = $result1->fetch_all(MYSQLI_ASSOC);

            $total = $countAll[0]['order_id']; // get total number of orders
            $pages = ceil($total / $limit); // get total number of pages

            $previous = $page -1; // get previous page number
            $next = $page +1; // get next page number

            // if previous page number is 0 then set it to 1
            if($previous==0){
                $previous=1;
                
            }   
            // if next page number is greater than total number of pages then set it to total number of pages
            if($next > $pages){
                $next = $pages; 
            }
            // if search button is clicked then search order by date
            if(isset($_POST['submit-search'])){
                $order_date = $_POST['order_date'];

                $sql="SELECT * FROM orders
                 INNER JOIN order_p ON orders.order_id=order_p.order_id 
                 INNER JOIN product ON product.product_id=order_p.order_item 
                 INNER JOIN user ON user.user_id=orders.order_user
                  WHERE product.product_userid ='$user_id'AND orders.order_date LIKE '%$order_date%' ORDER BY orders.order_id DESC LIMIT $start, $limit ";
            }
            // if search button is not clicked then show all orders
            else{
                $sql="SELECT * FROM orders
                INNER JOIN order_p ON orders.order_id=order_p.order_id 
                INNER JOIN product ON product.product_id=order_p.order_item 
                INNER JOIN user ON user.user_id=orders.order_user
                 WHERE product.product_userid ='$user_id' ORDER BY orders.order_id DESC LIMIT $start, $limit ";
            }
            // get order details
            $result=mysqli_query($conn,$sql);
            while($row=mysqli_fetch_assoc($result)){
                $id=$row['order_id'];
                $user_name=$row['user_name'];
                $order_date=$row['order_date'];
                $order_status=$row['order_status'];
            ?>
            <tr>
                <td><?php echo $user_name ?></td>
                <td><?php echo $order_date ?></td>
                <td><?php echo $order_status ?></td>
                <td>
                    <table class="table"  >
    
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                        </tr>
                        <?php
                        $sql1="SELECT product.product_name, order_p.order_qu
                        FROM order_p
                        INNER JOIN product ON product.product_id=order_p.order_item
                        WHERE order_p.order_id=$id AND product.product_userid ='$user_id' ";
                        $result1=mysqli_query($conn,$sql1);
                        while($row1=mysqli_fetch_assoc($result1)){
                            $product_name=$row1['product_name'];
                            $product_quantity=$row1['order_qu'];

                       echo" <tr>
                            <td>$product_name</td>
                            <td>$product_quantity Pcs</td>
                        </tr>";
                        }
                        ?>
                    </table>
                </td>
                <td><a href="s_invoice.php?orderId=<?php echo $id ?>" class="btn btn-success">Invoice</a></td>
                <td><a href="s_olabel.php?orderId=<?php echo $id ?>" class="btn btn-warning">Shipping Label</a></td>
                <td> <a href="s_oedit.php?orderId=<?php echo $id ?>" class="btn btn-primary">Edit</a></td>
                
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
        <div class="container d-flex mt-5 pt-5">
        <div class="col-6">Showing <b><?php echo $page;?></b> out of <b><?php echo $pages;?></b> Pages</div>
        <div class="col-6 d-flex justify-content-end">
          <div class="">
              <ul class="pagination">
                <!-- previous button -->
                <li class=""><a class="page-link " href="s_omgmt.php?page=<?php echo $previous;?>">&laquo; &laquo;</a></li>
                  <?php
                    // showing pagination
                    if($page <= 2){
                      $page = 1;
                    }elseif($page >= $pages - 2){
                      $page = $pages - 2;
                    }
                    // showing go to page buttons in loop
                    for($i = $page; $i <= $page + 2; $i++): 
                    if($i <= $pages){
                      
                    ?>
                  <li class=""><a class="page-link" href="s_omgmt.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                  <?php
                        } // end of if
                      endfor; // end of for loop
                  ?>
                  <!-- next button -->
                <li class=""><a class="page-link" href="s_omgmt.php?page=<?php echo $next;?>">&raquo; &raquo;</a></li>              
              </ul>
          </div>
        </div>
      </div>
    </div>

</body>
</html>