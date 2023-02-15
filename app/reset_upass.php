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
    include 'database.php';     
?>
<body>
<?php
        $id=$_GET['userId'];
        $sql="SELECT * FROM user WHERE `user_id`='$id'";
        $result=mysqli_query($conn,$sql);
        while($row=mysqli_fetch_assoc($result)){
            $email=$row['user_email'];   
        }
        ?>
        <div class="container ">
            <form method="post" enctype="multipart/form-data">
                <div class="px-auto">
                    <h1 class="text-center">Reset User Password</h1>
                </div>
                <div class="flex mx-auto col-6 " >
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example1">User Email</label>
                        <input type="text" name="product_name" id="form4Example1" value="<?=$email?>" disabled class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example2">Enter New Password</label>
                        <input type="password" name="new_pass" id="form4Example2" placeholder="Enter new Password" class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example3">Confirm Password</label>
                        <input type="password" class="form-control" name="cnf_pass" id="form4Example3" placeholder="Retype Password" rows="4"></input>
                    </div>
                </div>
                <!-- Submit button -->
                <div class="d-flex justify-content-center">
                    <button type="submit" name="update" class="btn btn-primary btn-block col-3 mb-4">Reset Password</button>
                </div>
                
            </form>
        </div>
<?php
if(isset($_POST["update"])) 
{
    $new_pass=$_POST['new_pass'];
    $cnf_pass=$_POST['cnf_pass'];
    if($new_pass==$cnf_pass){
        $new_pass=md5($new_pass);
        $sql="UPDATE user SET user_pass='$new_pass' WHERE user_id='$id'";
        mysqli_query($conn,$sql);
        echo'<script>
        window.location.href="user_mgmt.php";
        </script>';
    }
    else{
        echo'<script>
        alert("Password does not match");
        </script>';
    }
}
?>
</body>
</html>