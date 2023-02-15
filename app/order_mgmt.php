<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: /admin_login.php');
  exit;
}

include 'database.php';

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
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
    ></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
    <link rel="stylesheet" href="asset/card.css" />
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
        <div class="d-flex pb-3">
            <div class="col-6 ps-3">
                <form action="order_mgmt.php" method="POST">
                    <label>
                        Enter Date to Search Order:-
                    </label><br>
                    <input type="date" name="order_date" required pattern="\d{4}-\d{2}-\d{2}" />
                    <button type="submit" class="btn btn-primary" name="submit-search">Search</button>
                </form>
            </div>
            <div class="col-6 pe-3">
                <a href="add_order.php" class="btn btn-success float-end">Create Order</a>
            </div>
        </div>

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
            $limit = 3;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $start = ($page -1) * $limit;



            $result1 = $conn->query("SELECT count(order_id) AS order_id FROM orders");
            $countAll = $result1->fetch_all(MYSQLI_ASSOC);

            $total = $countAll[0]['order_id'];
            $pages = ceil($total / $limit);

            $previous = $page -1;
            $next = $page +1;

            if($previous==0){
                // header("location:user_mgmt.php?page=1");
                $previous=1;
                
            }   

            if($next > $pages){
                $next = $pages; 
            }
            
            if(isset($_POST['submit-search'])){
                $order_date = $_POST['order_date'];
            $sql="SELECT * FROM `orders` 
            INNER JOIN  user ON user.user_id= orders.order_user
            WHERE orders.order_date LIKE '%$order_date%'
            ORDER BY orders.order_id DESC LIMIT $start, $limit";
           
            }
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
        <div class="d-flex">
            <div class="col-6">Showing <b><?php echo $page;?></b> out of <b><?php echo $pages;?></b> Pages</div>
            <div class="col-6 d-flex justify-content-end">
                <div class="">
                    <ul class="pagination">

                    <li class=""><a class="page-link" href="order_mgmt.php?page=<?php echo $previous;?>">&laquo; Previous</a></li>
                    <?php for($i =1; $i<= $pages; $i++): ?>
                        
                    <li class=""><a class="page-link" href="order_mgmt.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                    <?php endfor; ?>
                    <li class=""><a class="page-link" href="order_mgmt.php?page=<?php echo $next;?>">&raquo; Next</a></li>
                    
                    </ul>
                </div>
            </div>
        </div>
    </div>

<script>
    function confirmDelete(){
        var result=confirm("Are you sure you want to delete this product?");
        if(result){
            window.location.href="delete_order.php?orderId=<?php echo $id ?>";
        }
    }
</script>



</body>
</html>