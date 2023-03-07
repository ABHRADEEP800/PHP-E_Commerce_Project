<?php
// Start the session
session_start(); 
if (!isset($_SESSION['admin'])) {  // if admin is not logged in
    header('location: /admin_login.php');
  exit;
}
// Include database connection file
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
    <link rel="stylesheet" href="asset/css/main.css" />
</head>
<?php
    include 'navbar.php';
     
?>
<body>
    <div class="container " >
    <div class="pt-5  ">
    <div class="col-12">
        <h1 class="text-center">Coupon Management</h1>
    </div>
        <form action="coupon.php" method="POST">
        <div class="d-flex pb-3">
            
                <div class="col-7 d-flex">
                    <div class="col-lg-8 col-sm-10">
                        <input class="search_bar form-control border-end-0 border rounded-pill" type="text" name="search" placeholder="Search coupon By Name">
                    </div>
                    <div class="col-lg-4 col-sm-2 ms-2">
                        <button type="submit" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" name="submit-search">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            
                <div class="col-5">
                <!-- add coupon  -->
                
                <a href="add_coupon.php" class="btn btn-success float-end">
                    <i class="fa fa-plus"></i>
                    Add Coupon
                </a>
            </div>
        </div>
        </form>
        <div class="table-responsive">
                <table class="table" border="1" >
                    <tr>
                        <th>Coupon Code</th>
                        <th>Discount</th>
                        <th>Expiry Date</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        
                    </tr>
                    <?php

                        $limit = 10; // Number of entries to show in a page.
                        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Get page number
                        $start = ($page -1) * $limit; // Starting point of the entries

                        $result1 = $conn->query("SELECT count(id) AS id FROM Coupon"); // Get the total number of entries
                        $countAll = $result1->fetch_all(MYSQLI_ASSOC); 

                        $total = $countAll[0]['id'];    // Total number of entries
                        $pages = ceil($total / $limit); // Total number of pages

                        $previous = $page -1; // Previous page
                        $next = $page +1; // Next page

                        // If previous page is less than 1, make it 1
                        if($previous==0){ 
                            $previous=1; 
                        }   
                        // If next page is greater than total number of pages, make it equal to total number of pages
                        if($next > $pages){
                            $next = $pages; 
                        }

                    // If search button is pressed
                    if(isset($_POST['submit-search'])){
                        $search=mysqli_real_escape_string($conn,$_POST['search']);
                        $sql="SELECT * FROM Coupon WHERE code LIKE '%$search%' 
                        LIMIT $start, $limit";
                    }
                    // If search button is not pressed
                    else{
                        $sql="SELECT * FROM Coupon LIMIT $start, $limit"; 
                    }

                    // Execute the query
                    $result=mysqli_query($conn,$sql);
                    // Fetch the data
                    while($row=mysqli_fetch_assoc($result)){
                        $id=$row['id'];
                        $code=$row['code'];
                        $discount=$row['discount'];
                        $exp_date=$row['exp_date'];
                    ?>
                    <tr>
                        <!-- Coupon expiration -->
                        <td><?php echo $code ?></td>
                        <td><?php echo $discount ?> %</td>
                        <td><?php echo $exp_date ?></td>
                        <td> <a href="edit_coupon.php?cId=<?php echo $id ?>" class="btn btn-primary">Edit</a></td>
                        <td> <a     onclick="return confirm('are you sure you want to Delete?') " href="delete_coupon.php?cId=<?php echo $id ?>" class="btn btn-danger">Delete</a></td>
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
                <li class=""><a class="page-link " href="coupon.php?page=<?php echo $previous;?>">&laquo; &laquo;</a></li>
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
                  <li class=""><a class="page-link" href="coupon.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                  <?php
                        } // end of if
                      endfor; // end of for loop
                  ?>
                  <!-- next button -->
                <li class=""><a class="page-link" href="coupon.php?page=<?php echo $next;?>">&raquo; &raquo;</a></li>              
              </ul>
          </div>
        </div>
      </div>
    </div>
    </div>
</body>
</html>