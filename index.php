<?php
    session_start();
    // checking if user is logged in cookie is set
  
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
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
  </head>
  <body>
    <!------------------------------- header -------------------------- -->
    <?php
    // including header
      include 'header.php';
    ?>
    <!-------------------------------------------------body----------------------------------------------------------->
    <div class="container ">

      <!-- search bar -->
      <div class='d-flex justify-content-end'>
        <form method="POST" action="index.php">
          <div class="row">
            <div class="col-12">
              <div class="input-group">
                <input class="form-control border-end-0 border rounded-pill" type="search" name="search_query" placeholder="Search Product" id="example-search-input" required>
                <span class="input-group-append">
                  <button class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" name="search-p" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </span>
              </div>
            </div>
          </div>
        </form>
      </div>

      <h1>Latest Products</h1>
      <?php 
        if(isset($_POST['search-p']) && $_POST['search_query'] != ''){
          // storing search query in variable
          $search_query = $_POST['search_query'];
      ?>
        <p>Showing results for <b>"<?php echo $search_query; ?>"</b></p>
      <?php
        }
      ?>
      <div class="products px-auto">
        
        <?php
            // pagination
            $limit = 4;
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

            // fetching search details from database
            if(isset($_POST['search-p'])){
                $search = $_POST['search_query'];
                $sql="SELECT * FROM product WHERE product_name LIKE '%$search%' LIMIT $start, $limit";
                $result=mysqli_query($conn,$sql); 
            }else{

            // fetching data from database
            $sql="SELECT * FROM product LIMIT $start, $limit";
            $result=mysqli_query($conn,$sql);
            }

            // showing products in loop
            while($row=mysqli_fetch_assoc($result)){

                // storing data in variables
                $id=$row['product_id'];
                $name=$row['product_name'];
                $image=$row['product_img'];
                $description=$row['product_description'];
                $price=$row['product_price'];
                $category=$row['product_category'];
                $sql = "SELECT * FROM category WHERE c_id = $category";
                $result1 = mysqli_query($conn, $sql);
                $row1 = mysqli_fetch_assoc($result1);
                $category_name = $row1['c_name'];

          ?>

          <!-- showing product details -->
          
            <div class="mx-1 my-1 card-size ">
              <form action="manage_cart.php" method="post">
                <div class="">
                  <div class="card main_card">
                    <div class="card-body">
                      <div class="img_div">
                        <img src="<?php echo 'app/'.$image; ?>" alt="" class="p_image">
                      </div>
                      <p class="p-title"><?php echo $name; ?></p>
                      <?php
                          // fetching average rating from database 
                          $sql = "SELECT  AVG(`rating`) as rating FROM `review` WHERE`product_id`= $id";
                          $result1 = mysqli_query($conn, $sql);
                          $row1 = mysqli_fetch_assoc($result1);
                          $rating = $row1['rating'];
                          $rating = round($rating, 1);
                          // showing average rating
                          echo $rating . " <i class='fas fa-star text-warning'></i> <br>";
                      ?>
                      <small class="text-muted" ><?php echo $category_name; ?></small>
                      <p>₹ <?php echo $price; ?></p>
                      <button type="submit" name="Add_To_Cart" class="btn btn-warning">Add to Cart</button>
                      <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    </div>
                  </div>
                </div>
              </form>
            </div>
        <?php
            } // end of while loop
        ?>
        <style>
          
        </style>
      </div>
    
      <!-- pagination -->
      <div class="container d-flex mt-5 pt-5">
        <div class="col-6">Showing <b><?php echo $page;?></b> out of <b><?php echo $pages;?></b> Pages</div>
        <div class="col-6 d-flex justify-content-end">
          <div class="">
              <ul class="pagination">
                <!-- previous button -->
                <li class=""><a class="page-link " href="index.php?page=<?php echo $previous;?>">&laquo; &laquo;</a></li>
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
                <li class=""><a class="page-link" href="index.php?page=<?php echo $next;?>">&raquo; &raquo;</a></li>              
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
