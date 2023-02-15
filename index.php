<?php
    session_start();

    // including database connection
    include 'database.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Home</title>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo1.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
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
  </head>
  <body>
    <!------------------------------- header -------------------------- -->
    <?php
    // including header
      include 'header.php';
    ?>
    <!-------------------------------------------------body----------------------------------------------------------->
    <div class="container">
      <h1>All Products</h1>
      <div class="d-flex">
        <?php
            // pagination
            $limit = 6;
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

            // fetching data from database
            $sql="SELECT * FROM product LIMIT $start, $limit";
            $result=mysqli_query($conn,$sql);

            // showing products in loop
            while($row=mysqli_fetch_assoc($result)){

                // storing data in variables
                $id=$row['product_id'];
                $name=$row['product_name'];
                $image=$row['product_img'];
                $description=$row['product_description'];
                $price=$row['product_price'];
          ?>

          <!-- showing product details -->
          <form action="manage_cart.php" method="post">
            <div class="me-2">
              <div class="">
                <div class="card">
                  <div class="card-body">
                      <img src="<?php echo 'app/'.$image; ?>" alt="" class="" width="150px" height="100px">
                      <h3><?php echo $name; ?></h3>
                      <p><?php echo $description; ?></p>
                      <p>₹ <?php echo $price; ?></p>
                      <button type="submit" name="Add_To_Cart" class="btn btn-warning">Add to Cart</button>
                      <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                  </div>
                </div>
              </div>
            </div>
          </form>    
        <?php
            } // end of while loop
        ?>
      </div>
    
      <!-- pagination -->
      <div class="d-flex">
        <div class="col-6">Showing <b><?php echo $page;?></b> out of <b><?php echo $pages;?></b> Pages</div>
        <div class="col-6 d-flex justify-content-end">
          <div class="">
              <ul class="pagination">
                <!-- previous button -->
                <li class=""><a class="page-link" href="index.php?page=<?php echo $previous;?>">&laquo; Previous</a></li>
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
                  <li class=""><a class="page-link" href="index.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>

                  <?php
                        } // end of if
                      endfor; // end of for loop
                  ?>
                  <!-- next button -->
                <li class=""><a class="page-link" href="index.php?page=<?php echo $next;?>">&raquo; Next</a></li>              
              </ul>
          </div>
        </div>
      </div>
    </div>
    <!-----------------------------footer  ---------------------------->
    <?php
      // including footer
      include 'footer.php';
    ?>
  </body>
</html>
