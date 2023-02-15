<?php
session_start(); // Start the session
if (!isset($_SESSION['admin'])) { // If no session value is present, redirect the user:
    header('location: /admin_login.php');
  exit;
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
        // Get user data
        $id=$_GET['userId'];
        $sql="SELECT * FROM user WHERE user_id=$id";
        $result=mysqli_query($conn,$sql);
         $row=mysqli_fetch_assoc($result); 
            $name=$row['user_name'];
            $type=$row['user_type'];
            $email=$row['user_email'];
        ?>

        <div class="container ">
            <form method="post" enctype="multipart/form-data">
                <div class="px-auto">
                    <h1 class="text-center">Edit User Data</h1>
                </div>
                
                <div class="flex mx-auto col-6 " >
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example1">User Name</label>
                        <input type="text" name="user_name" id="form4Example1" value="<?=$name?>" class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example2">User Email</label>
                        <input type="text" name="user_email" id="form4Example2" value="<?=$email?>" class="form-control" />
                    </div>

                   
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form4Example3">User Type</label>
                          <select name="utype" class="form-select" aria-label="select example">
                                <option value="<?=$type?>"><?=$type?></option>
                                <?php
                                    if($type=="Customer"){
                                        echo "<option value='Seller'>Seller</option>";
                                    }else{
                                        echo "<option value='Customer'>Customer</option>";
                                    }
                                ?>
                          </select>
                          <label class="form-label" for="form4Example3">*Don't change User Type unless you want to Change User Role.*</label>
                    </div>
                </div>
                <!-- Submit button -->
                <div class="d-flex justify-content-center">
                    <button type="submit" name="update" class="btn btn-primary btn-block col-3 mb-4">Update</button>
                </div>
                
            </form>
        </div>
        <?php
   
        ?>
<?php
// Update user data
if(isset($_POST["update"])) {
    $user_name=$_POST['user_name'];
    $user_email=$_POST['user_email'];
    $user_type=$_POST['utype'];
    $sql= "UPDATE user SET  user_name ='$user_name' , user_email='$user_email',user_type ='$user_type' WHERE user_id ='$id';";
    mysqli_query($conn,$sql);
    echo'<script>
    window.location.href="user_mgmt.php";
    </script>';
}
?>
</body>
</html>