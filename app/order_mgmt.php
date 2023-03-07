<?php
// start session
session_start(); // Start the session
if (!isset($_SESSION['admin'])) { // If no session value is present, redirect the user:
    header('location: /admin_login.php'); // Redirect the user
  exit; // Quit the script
}
include 'database.php'; // Include the database connection.

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
    ></script>  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
    <link rel="stylesheet" href="asset/card.css" />
    <link rel="stylesheet" href="asset/css/main.css" />
</head>
<?php
    include 'navbar.php';
     
?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Order Management</h1>
            </div>
        </div>
    <div class="pt-5">
        <form action="order_mgmt.php" method="POST">
        <div class="d-flex pb-3">
            
                <div class="col-7 d-flex">
                    <div class="col-lg-8 col-sm-10">
                        <input class="search_bar form-control border-end-0 border rounded-pill" type="date" name="order_date"  required pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-lg-4 col-sm-2 ms-2">
                        <button type="submit" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" name="submit-search">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            
            <div class="col-5 d-flex justify-content-end">
                <div class="px-4 ">
                    <button class="btn btn-outline-primary dropdown-toggle float-end drop_btn" type="button"  id="dropdownMenuButton1" data-bs-toggle="dropdown"  aria-expanded="false">
                        <i class="fas fa-plus pe-2"></i>Add
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="coupon.php">Coupon</a></li>                
                        <li><a class="dropdown-item" href="add_order.php">Create Order</a></li>
                    </ul>
                </div>
            </div>
        </div>
        </form>
        <div class="table-responsive">

        <table class="table" border="1" >
            <tr>
                <th>User Name</th>
                <th>Ordered Date</th>
                <th>Order Status</th>
                <th>Order Item</th>
                <th>Invoice</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>

            <?php
            
            $limit = 3; // Number of entries to show in a page.
            $page = isset($_GET['page']) ? $_GET['page'] : 1; // Get page number
            $start = ($page -1) * $limit; // Starting number of the first record on the page

            $result1 = $conn->query("SELECT count(order_id) AS order_id FROM orders"); // Get the total number of records
            $countAll = $result1->fetch_all(MYSQLI_ASSOC); 

            $total = $countAll[0]['order_id']; // Get the total number of records
            $pages = ceil($total / $limit); // Calculate total pages

            $previous = $page -1; // For previous page to go to
            $next = $page +1; // For next page to go to

            if($previous==0){ // If the current page is the first page, then there is no previous page
                $previous=1;   
            }   
            // If the current page is the last page, then there is no next page
            if($next > $pages){
                $next = $pages; 
            }
            // 
            if(isset($_POST['submit-search'])){ // If the user clicks the search button
                $order_date = $_POST['order_date'];
                // Search the database for the user's input
            $sql="SELECT * FROM `orders` 
            INNER JOIN  user ON user.user_id= orders.order_user
            WHERE orders.order_date LIKE '%$order_date%'
            ORDER BY orders.order_id DESC LIMIT $start, $limit";
           
            }
            // If the user does not click the search button, then display all the records
            else{
                $sql="SELECT * FROM `orders` 
            INNER JOIN  user ON user.user_id= orders.order_user
            ORDER BY orders.order_id DESC LIMIT $start, $limit";
            }

            
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
                        // sql for getting the product name and quantity of the order
                        $sql1="SELECT product.product_name, order_p.order_qu
                        FROM order_p
                        INNER JOIN product ON product.product_id=order_p.order_item
                        WHERE order_p.order_id=$id";
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
                <td><a href="invoice.php?orderId=<?php echo $id ?>" class="btn btn-success">Invoice</a></td>
                <td> <a href="edit_order.php?orderId=<?php echo $id ?>" class="btn btn-primary">Edit</a></td>
                <td><button onClick="confirmDelete()" class="btn btn-danger">Delete</button></td>
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
                <li class=""><a class="page-link " href="order_mgmt.php?page=<?php echo $previous;?>">&laquo; &laquo;</a></li>
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
                  <li class=""><a class="page-link" href="order_mgmt.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                  <?php
                        } // end of if
                      endfor; // end of for loop
                  ?>
                  <!-- next button -->
                <li class=""><a class="page-link" href="order_mgmt.php?page=<?php echo $next;?>">&raquo; &raquo;</a></li>              
              </ul>
          </div>
        </div>
      </div>
    </div>

<script>
    // confirm delete function
    function confirmDelete(){
        var result=confirm("Are you sure you want to delete this product?");
        if(result){
            window.location.href="delete_order.php?orderId=<?php echo $id ?>";
        }
    }
</script>
</body>
</html>