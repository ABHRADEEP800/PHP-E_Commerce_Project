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
                <h1 class="text-center">User Management</h1>
            </div>
        </div>
    <div class="pt-5">
        <div class="d-flex pb-3">
            <div class="col-6 ps-3">
                <form action="user_mgmt.php" method="POST">
                    <input type="text" name="search" placeholder="Search user By E-mail">
                    <button type="submit" class="btn btn-primary" name="submit-search">Search</button>
                </form>
            </div>
            <div class="col-6 pe-3">
                <a href="add_user.php" class="btn btn-success float-end">Add User</a>
            </div>
        </div>

        <table class="table" border="1" >
            <tr>
                <th>User Name</th>
                <th>User Email</th>
                <th>User Type</th>
                <th>Edit</th>
                <th>Reset Password</th>
                <th>Delete</th>
            </tr>
            <?php
            $limit = 10;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $start = ($page -1) * $limit;

        

            $result1 = $conn->query("SELECT count(user_id) AS user_id FROM user");
            $countAll = $result1->fetch_all(MYSQLI_ASSOC);

            $total = $countAll[0]['user_id'];
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
                $search=mysqli_real_escape_string($conn,$_POST['search']);
                $sql="SELECT * FROM user WHERE user_email LIKE '%$search%' LIMIT $start, $limit";
            }else{
                $sql="SELECT * FROM user LIMIT $start, $limit";
            }

            
            
            $result=mysqli_query($conn,$sql);
            while($row=mysqli_fetch_assoc($result)){
                $id=$row['user_id'];
                $name=$row['user_name'];
                $type=$row['user_type'];
                $email=$row['user_email'];
            ?>
            <tr>
                <td><?php echo $name ?></td>
                <td><?php echo $email ?></td>
                <td><?php echo $type ?></td>
                <td><a href="edit_user.php?userId=<?php echo $id ?>" class="btn btn-primary">Edit</a></td>
                <td> <a href="reset_upass.php?userId=<?php echo $id ?>" class="btn btn-warning">Reset Password</a></td>
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

                    <li class=""><a class="page-link" href="user_mgmt.php?page=<?php echo $previous;?>">&laquo; Previous</a></li>
                    <?php for($i =1; $i<= $pages; $i++): ?>
                        
                    <li class=""><a class="page-link" href="user_mgmt.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                    <?php endfor; ?>
                    <li class=""><a class="page-link" href="user_mgmt.php?page=<?php echo $next;?>">&raquo; Next</a></li>
                    
                    </ul>
                </div>
            </div>
        </div>
    </div>

<script>
    function confirmDelete(){
        var result=confirm("Are you sure you want to delete this user?");
        if(result){
            window.location.href="delete_user.php?userId=<?php echo $id ?>";
        }
    }
</script>
</body>
</html>