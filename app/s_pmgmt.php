<!------seller login check--->
<?php
session_start();
if (!isset($_SESSION['seller'])) {
    header('location: /login.php');
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
</head>
<?php
    include 'navbar_s.php';
     
?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Product Management</h1>
            </div>
        </div>
    <div class="pt-5">
        <div class="d-flex pb-3">
            <div class="col-6 ps-3">
                <form action="s_pmgmt.php" method="POST">
                    <input type="text" name="search" placeholder="Search Product By Name">
                    <button type="submit" class="btn btn-primary" name="submit-search">Search</button>
                </form>
            </div>
            <div class="col-6 pe-3">
                <a href="s_padd.php" class="btn btn-success float-end">Add Product</a>
            </div>
        </div>
        <!---Show product by search or show all products--->

        <table class="table" border="1" >
            <tr>
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Product Description</th>
                <th>Product Price</th>
                <th>Product Quantity</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            $semail=$_SESSION['seller'];
            $sql="SELECT * FROM `user` WHERE `user_email`='$semail'";
            $result=mysqli_query($conn,$sql);
            $row=mysqli_fetch_assoc($result);
            $user_id=$row['user_id'];

            $limit = 1;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $start = ($page -1) * $limit;



            $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product  WHERE product.product_userid = '$user_id'");
            $countAll = $result1->fetch_all(MYSQLI_ASSOC);

            $total = $countAll[0]['product_id'];
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
            //search sql or show all products for seller
            if(isset($_POST['submit-search'])){
                $search=mysqli_real_escape_string($conn,$_POST['search']);
                $sql="SELECT * FROM `product` WHERE `product_userid`='$user_id' AND `product_name` LIKE '%$search%' LIMIT $start, $limit";
            }
            else{
                $sql="SELECT * FROM `product` WHERE `product_userid`='$user_id' LIMIT $start, $limit";
            }
            $result=mysqli_query($conn,$sql);
            while($row=mysqli_fetch_assoc($result)){
                $id=$row['product_id'];
                $name=$row['product_name'];
                $image=$row['product_img'];
                $description=$row['product_description'];
                $price=$row['product_price'];
                $product_quantity=$row['product_qu'];
            ?>
            <tr>
                <td><?php echo $name ?></td>
                <td><img src="<?php echo $image ?>" width="100px">  </td>
                <td><?php echo $description ?></td>
                <td>₹ <?php echo $price ?></td>
                <td><?php echo $product_quantity ?> Pcs</td>
                <td> <a href="s_pedit.php?productId=<?php echo $id ?>" class="btn btn-primary">Edit</a></td>
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

                    <li class=""><a class="page-link" href="s_pmgmt.php?page=<?php echo $previous;?>">&laquo; Previous</a></li>
                    <?php for($i =1; $i<= $pages; $i++): ?>
                        
                    <li class=""><a class="page-link" href="s_pmgmt.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                    <?php endfor; ?>
                    <li class=""><a class="page-link" href="s_pmgmt.php?page=<?php echo $next;?>">&raquo; Next</a></li>
                    
                    </ul>
                </div>
            </div>
        </div>
    </div>

<script>
    function confirmDelete(){
        var result=confirm("Are you sure you want to delete this product?");
        if(result){
            window.location.href="s_pdelete.php?productId=<?php echo $id ?>";
        }
    }
</script>



</body>
</html>