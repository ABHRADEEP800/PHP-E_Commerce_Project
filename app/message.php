<?php
session_start(); // Start the session
if (!isset($_SESSION['admin'])) {
    header('location: /admin_login.php'); // Redirect to the login page.
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
    <link rel="stylesheet" href="asset/css/main.css" />
</head>

<?php
    include 'navbar.php';  
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Contact Messages</h1>
            </div>
        </div>
    <div class="pt-5">
        <div class="d-flex pb-3">
            <!-- <div class="col-6 ps-3">
                <form action="message.php" method="POST">
                    <input type="text" name="search" placeholder="Search message by email">
                    <button type="submit" class="btn btn-primary" name="submit-search">Search</button>
                </form>
            </div> -->
            <form action="message.php" method="POST">                    
                <div class="d-flex">
                    <input type="text" class="search_bar form-control border-end-0 border rounded-pill" name="search" required  placeholder="Search message by email"/>
                    <button type="submit" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill mx-4" name="submit-search">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="table-responsive">

        <table class="table" border="1" >
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>View</th>
                <th>Delete</th>
            </tr>
            <?php

                $limit = 5; // Number of entries to show in a page.
                $page = isset($_GET['page']) ? $_GET['page'] : 1; // Get page number
                $start = ($page -1) * $limit; // Starting number of the first record to show on the page

                $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product"); // Get the total number of records
                $countAll = $result1->fetch_all(MYSQLI_ASSOC); 

                $total = $countAll[0]['product_id']; // Get the total number of records
                $pages = ceil($total / $limit); // Calculate total pages

                $previous = $page -1; // For previous page to go to
                $next = $page +1; // For next page to go to

                // If it's the first page, don't show the previous link
                if($previous==0){
                    $previous=1; 
                }   
                // If it's the last page, don't show the next link
                if($next > $pages){
                    $next = $pages; 
                }
            // If the search button is pressed
            if(isset($_POST['submit-search'])){
                $search=mysqli_real_escape_string($conn,$_POST['search']);
                $sql="SELECT * FROM contact WHERE email LIKE '%$search%' LIMIT $start, $limit";
            }
            // If the search button is not pressed
            else{
                $sql="SELECT * FROM contact LIMIT $start, $limit";
            }
            
            $result=mysqli_query($conn,$sql);
            while($row=mysqli_fetch_assoc($result)){
                $id=$row['id'];
                $name=$row['name'];
                $email=$row['email'];
                $date=$row['date'];

            ?>
            <tr>
                <td><?php echo $name ?></td>
                <td><?php echo $email ?></td>
                <td><?php echo $date ?></td>
                <td> <a href="view_message.php?Id=<?php echo $id ?>" class="btn btn-primary">View Message</a></td>
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
                <li class=""><a class="page-link " href="message.php?page=<?php echo $previous;?>">&laquo; &laquo;</a></li>
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
                  <li class=""><a class="page-link" href="message.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                  <?php
                        } // end of if
                      endfor; // end of for loop
                  ?>
                  <!-- next button -->
                <li class=""><a class="page-link" href="message.php?page=<?php echo $next;?>">&raquo; &raquo;</a></li>              
              </ul>
          </div>
        </div>
      </div>
    </div>

<script>
    function confirmDelete(){ // this function is for confirmation of delete
        var result=confirm("Are you sure you want to delete this Message?");
        if(result){
            window.location.href="delete_message.php?Id=<?php echo $id ?>";
        }
    }
</script>
</body>
</html>