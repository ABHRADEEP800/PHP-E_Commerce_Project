<?php
session_start(); // start session
if (!isset($_SESSION['admin'])) { // if admin is not logged in
    header('location: /admin_login.php'); // redirect to admin login page
  exit; // stop executing the script
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<?php
    include 'navbar.php';
    include('database.php');  
?>

<body>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
<div class="col-md-10 mx-auto">
    <div class="row ">

        <?php
            $sql = "SELECT * FROM orders WHERE order_date = CURDATE() ORDER BY order_date DESC;"; // query to get all orders
            $result = mysqli_query($conn, $sql); // execute the query
            $count = mysqli_num_rows($result); // get the number of rows
            
            $sql = "SELECT * FROM orders;"; // query to get all orders
            $result = mysqli_query($conn, $sql); // execute the query
            $tCount = mysqli_num_rows($result); // get the number of rows

            $sql = "SELECT * FROM user;";   // query to get all users
            $result = mysqli_query($conn, $sql); // execute the query
            $uCount = mysqli_num_rows($result); // get the number of rows

            $sql = "SELECT  order_p.order_qu, product.product_price 
            FROM order_p 
            INNER JOIN product ON product.product_id=order_p.order_item
            INNER JOIN orders ON orders.order_id=order_p.order_id
            WHERE orders.order_date = CURDATE();"; // query to get all orders
            $result = mysqli_query($conn, $sql); // execute the query
            $rev = 0; // get the number of rows
            while($row = mysqli_fetch_array($result)){ // loop through the rows
                $sum =  $row['product_price'] * $row['order_qu']; // get the sum of the price and quantity
            $rev += $sum; // add the sum to the total revenue
            }
            $sql = "WITH Calendar AS ( 
                SELECT 1 AS Month UNION ALL
                SELECT 2 UNION ALL
                SELECT 3 UNION ALL
                SELECT 4 UNION ALL
                SELECT 5 UNION ALL
                SELECT 6 UNION ALL
                SELECT 7 UNION ALL
                SELECT 8 UNION ALL
                SELECT 9 UNION ALL
                SELECT 10 UNION ALL
                SELECT 11 UNION ALL
                SELECT 12
              ),
              OrdersByMonth AS (
                SELECT COUNT(*) AS Total_Orders, MONTH(order_date) AS Month
                FROM orders
                GROUP BY MONTH(order_date)
              )
              SELECT Calendar.Month, COALESCE(OrdersByMonth.Total_Orders, 0) AS Total_Orders
              FROM Calendar
              LEFT JOIN OrdersByMonth ON Calendar.Month = OrdersByMonth.Month
              ORDER BY Calendar.Month;
              "; // query to get all orders by month 
            $result = mysqli_query($conn, $sql); 
            $data = array();
            while($row = mysqli_fetch_array($result)){ 
                $data[] = $row['Total_Orders'];
            }
            $data = implode(", ", $data); // convert the array to a string
            $data = "[".$data."]"; // add square brackets to the string
        ?>

        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-cherry">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon pe-2 card-icon-large"><i class="fas fa-cart-plus"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Today's Orders</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                <?php echo $count; // display the number of orders ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-orange-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon pe-2 card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Orders</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                <?php echo $tCount; ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon pe-2 card-icon-large"><i class="fas fa-users"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Customers</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                            <?php echo $uCount; ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-green-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon pe-2 card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Totay's Total Sales</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h3 class="d-flex align-items-center mb-0">
                                ₹ <?php echo $rev; ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-10 mx-auto d-flex" >
    <div class="col-7 mx-auto">
        <div>
            <canvas id="myChart"></canvas>
        </div>
    </div>
        <div class="col-4 mx-auto offset-1">

            <?php
                include 'calender.php';
            ?>

    </div>
</div>
                                            <!-- function for chart js -->
    <script>
        var ctx = document.getElementById('myChart').getContext('2d'); // get the canvas element
        var myChart = new Chart(ctx, { // create a new chart
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // set the labels
                datasets: [{
                    label: 'Total Orders By Month',
                    data: <?php echo $data;?>,
                    backgroundColor: [ // set the background color
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [ // set the border color
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1 // set the border width
                }]
            },
            options: { // set the options
                scales: {
                    y: {
                        beginAtZero: true // set the minimum value of the y-axis to 0
                    }
                }
            }
        }); 
    </script>
    </body>
</html>