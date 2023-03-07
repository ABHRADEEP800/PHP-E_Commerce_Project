<?php
// Start the session
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: /admin_login.php');
  exit;
}
// Connect to the database
include 'database.php';
$count=0;
if(isset($_SESSION['cart']))
{
    $count=count($_SESSION['cart']);
}
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
// include the navbar
    include 'navbar.php';
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Add New Order</h1>
            </div>
        </div>
    <div class="pt-5">
    <form  method="POST">
        <div class="d-flex pb-3">
            
                <div class="col-7 d-flex">
                    <div class="col-lg-8 col-sm-10">
                        <input class="search_bar form-control border-end-0 border rounded-pill" type="text" name="search" placeholder="Search Product By Name">
                    </div>
                    <div class="col-lg-4 col-sm-2 ms-2">
                        <button type="submit" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" name="submit-search">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            
            <div class="col-5">
                <!-- add product  -->
                
                <a href="cnf_order.php" class="btn btn-warning float-end">
                    <i class='fa-solid fa-cart-shopping'></i>
                    <?=$count?>
                </a>
            </div>
        </div>
        </form>

    <div class="table-responsive">
        <table class="table" border="1" >
            <tr>
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Product Description</th>
                <th>Product Price</th>
                <th>Product Quantity</th>
                <th>Add To Cart</th>
            </tr>
            
            <?php
            // product functionalities in admin dashboard
            $limit = 5; // Number of entries to show in a page.
            $page = isset($_GET['page']) ? $_GET['page'] : 1; // Get page number
            $start = ($page -1) * $limit; // Starting number of the first record on the page

            $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product"); // Get the total number of records
            $countAll = $result1->fetch_all(MYSQLI_ASSOC); // Get the total number of records

            $total = $countAll[0]['product_id']; // Get the total number of records
            $pages = ceil($total / $limit); // Calculate total pages
 
            $previous = $page -1; // For previous page to go to
            $next = $page +1; // For next page to go to

            if($previous==0){ // If the current page is first page
                $previous=1;   
            }    

            if($next > $pages){ // If the current page is last page
                $next = $pages; 
            }
            if(isset($_POST['submit-search'])){ // If the user clicks the search button
                $query = $_POST['search']; // Get the search query
                $sql="SELECT * FROM product WHERE  product_name LIKE '%$query%' LIMIT $start, $limit"; // Search the product by name
               
                }
            else{ // If the user doesn't click the search button

              $sql="SELECT * FROM product LIMIT $start, $limit"; // Get all the products from the database
            }
            $result=mysqli_query($conn,$sql); 
            while($row=mysqli_fetch_assoc($result)){  // Fetch all the products from the database
                $id=$row['product_id'];
                $name=$row['product_name'];
                $image=$row['product_img'];
                $description=$row['product_description'];
                $price=$row['product_price'];
                $product_quantity=$row['product_qu'];
            ?>
            <form action="manage_cart.php" method="POST"> 
            <tr>
                <td><?php echo $name ?></td>
                <td><img src="<?php echo $image ?>" width="100px">  </td>
                <td><?php echo $description ?></td>
                <td>₹<?php echo $price ?></td>
                <td><?php echo $product_quantity ?> Pcs</td>
                <td> 
                    <button type="submit" name="Add_To_Cart" class="btn btn-warning">Add to Cart</button>
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                </td>
            </tr>
            </form>
            <?php
            }
            ?>
            <!-- Pagination function added -->
        </table>
        </div>
        <div class="d-flex">
            <div class="col-6">Showing <b><?php echo $page;?></b> out of <b><?php echo $pages;?></b> Pages</div>
            <div class="col-6 d-flex justify-content-end">
                <div class="">
                    <ul class="pagination">

                    <li class=""><a class="page-link" href="add_order.php?page=<?php echo $previous;?>">&laquo; Previous</a></li>
                    <?php for($i =1; $i<= $pages; $i++): ?>
                        
                    <li class=""><a class="page-link" href="add_order.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                    <?php endfor; ?>
                    <li class=""><a class="page-link" href="add_order.php?page=<?php echo $next;?>">&raquo; Next</a></li>
                    
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>