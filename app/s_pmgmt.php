<!------seller login check--->
<?php
session_start(); // Starting Session
if (!isset($_SESSION['seller'])) { //if not logged in
    header('location: /login.php'); // Redirecting To Home Page
  exit; // stop further executing, very important
}

// database connection
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
                <h1 class="text-center">Product Management</h1>
            </div>
        </div>
    <div class="pt-5">
    <form action="s_pmgmt.php" method="POST">
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
                
                <a href="s_padd.php" class="btn btn-success float-end">
                    <i class="fa fa-plus"></i>
                    Add Product
                </a>
            </div>
        </div>
        </form>
        <!---Show product by search or show all products--->
        <div class="table-responsive">

        <table class="table" border="1" >
            <tr>
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Product Category</th>
                <th>Product Price</th>
                <th>Product Quantity</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            // php for search product
            $semail=$_SESSION['seller'];
            $sql="SELECT * FROM `user` WHERE `user_email`='$semail'";
            $result=mysqli_query($conn,$sql);
            $row=mysqli_fetch_assoc($result);
            $user_id=$row['user_id'];

            $limit = 5; //number of records to show per page
            $page = isset($_GET['page']) ? $_GET['page'] : 1; //current page number
            $start = ($page -1) * $limit; //starting point of each page



            $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product  WHERE product.product_userid = '$user_id'");
            $countAll = $result1->fetch_all(MYSQLI_ASSOC);

            $total = $countAll[0]['product_id']; //total number of records
            $pages = ceil($total / $limit); //total number of pages

            $previous = $page -1; //previous page number
            $next = $page +1; //next page number
            
            if($previous==0){ // if previous page is 0 then set it to 1
                $previous=1;
                
            }   

            if($next > $pages){ //if next page is greater than total number of pages then set it to last page
                $next = $pages; 
            }
            //search sql or show all products for seller
            if(isset($_POST['submit-search'])){
                $search=mysqli_real_escape_string($conn,$_POST['search']);
                $sql="SELECT * FROM `product` WHERE `product_userid`='$user_id' AND `product_name` LIKE '%$search%' LIMIT $start, $limit";
            }
            // if search is not set then show all products
            else{
                $sql="SELECT * FROM `product` WHERE `product_userid`='$user_id' LIMIT $start, $limit";
            }
            $result=mysqli_query($conn,$sql);
            while($row=mysqli_fetch_assoc($result)){
                $id=$row['product_id'];
                $name=$row['product_name'];
                $image=$row['product_img'];
                $category=$row['product_category'];
                $price=$row['product_price'];
                $product_quantity=$row['product_qu'];

                $sql = "SELECT * FROM category WHERE c_id = $category";
                $result1 = mysqli_query($conn, $sql);
                $row1 = mysqli_fetch_assoc($result1);
                $category_name = $row1['c_name'];
            ?>
            <tr>
                <td><?php echo $name ?></td>
                <td><img src="<?php echo $image ?>" width="100px">  </td>
                <td><?php echo $category_name ?></td>
                <td>₹<?php echo $price ?></td>
                <td><?php echo $product_quantity ?> Pcs</td>
                <td> <a href="s_pedit.php?productId=<?php echo $id ?>" class="btn btn-primary">Edit</a></td>
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
                <li class=""><a class="page-link " href="s_pmgmt.php?page=<?php echo $previous;?>">&laquo; &laquo;</a></li>
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
                  <li class=""><a class="page-link" href="s_pmgmt.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                  <?php
                        } // end of if
                      endfor; // end of for loop
                  ?>
                  <!-- next button -->
                <li class=""><a class="page-link" href="s_pmgmt.php?page=<?php echo $next;?>">&raquo; &raquo;</a></li>              
              </ul>
          </div>
        </div>
      </div>
    </div>

<script>
    // confirm delete product
    function confirmDelete(){
        var result=confirm("Are you sure you want to delete this product?");
        if(result){
            window.location.href="s_pdelete.php?productId=<?php echo $id ?>";
        }
    }
</script>



</body>
</html>