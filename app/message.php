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
            <div class="col-6 ps-3">
                <form action="message.php" method="POST">
                    <input type="text" name="search" placeholder="Search message by email">
                    <button type="submit" class="btn btn-primary" name="submit-search">Search</button>
                </form>
            </div>
        </div>

        <table class="table" border="1" >
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>View</th>
                <th>Delete</th>
            </tr>
            <?php

                $limit = 5;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $start = ($page -1) * $limit;



                $result1 = $conn->query("SELECT count(product_id) AS product_id FROM product");
                $countAll = $result1->fetch_all(MYSQLI_ASSOC);

                $total = $countAll[0]['product_id'];
                $pages = ceil($total / $limit);

                $previous = $page -1;
                $next = $page +1;

                if($previous==0){
                    $previous=1; 
                }   
                if($next > $pages){
                    $next = $pages; 
                }
            
            if(isset($_POST['submit-search'])){
                $search=mysqli_real_escape_string($conn,$_POST['search']);
                $sql="SELECT * FROM contact WHERE email LIKE '%$search%' LIMIT $start, $limit";
            }
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
        <div class="d-flex">
            <div class="col-6">Showing <b><?php echo $page;?></b> out of <b><?php echo $pages;?></b> Pages</div>
            <div class="col-6 d-flex justify-content-end">
                <div class="">
                    <ul class="pagination">

                    <li class=""><a class="page-link" href="message.php?page=<?php echo $previous;?>">&laquo; Previous</a></li>
                    <?php for($i =1; $i<= $pages; $i++): ?>
                        
                    <li class=""><a class="page-link" href="message.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                    <?php endfor; ?>
                    <li class=""><a class="page-link" href="message.php?page=<?php echo $next;?>">&raquo; Next</a></li>
                    
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