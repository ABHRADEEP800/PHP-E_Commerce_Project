<?php
session_start();
require('env/database.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>FAQ</title>
    <link rel="icon" type="image/x-icon" href="assets/svg-logo/logo-bg.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/db79afedbd.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
</head>

<body>
    <!-- ----------------------------------------------------Loading Screen-------------------------------------------------------- -->
    <div id="loading">
        <img src="assets/svg-logo/LOADER.svg" alt="Loading..." />
    </div>
    <script>
        var loader = document.getElementById("loading");
        window.addEventListener("load", function() {
            loader.style.display = "none";
        })
    </script>
    <!-----------------------header ------------------->
    <?php
    // including header
    include 'header.php';
    ?>
    <div class="container ">
        <div class="card">
            <div class="card-body">
                <h1>FAQ</h1>
                <main>
                    <section class="faq">
                        <h2>Shipping</h2>
                        <ul>
                            <li>
                                <h3>How long does shipping take?</h3>
                                <p>Shipping typically takes 5-7 business days.</p>
                            </li>
                            <li>
                                <h3>Can I track my shipment?</h3>
                                <p>Yes, you will receive a tracking number once your order has shipped.</p>
                            </li>
                            <li>
                                <h3>Do you offer international shipping?</h3>
                                <p>Yes, we offer international shipping to most countries.</p>
                            </li>
                        </ul>
                    </section>
                    <section class="faq">
                        <h2>Returns</h2>
                        <ul>
                            <li>
                                <h3>What is your return policy?</h3>
                                <p>We accept returns within 15 days of purchase.</p>
                            </li>
                            <li>
                                <h3>Do I have to pay for return shipping?</h3>
                                <p>Yes, the customer is responsible for return shipping costs.</p>
                            </li>
                            <li>
                                <h3>Can I exchange an item?</h3>
                                <p>Yes, we offer exchanges for items of equal or lesser value.</p>
                            </li>
                        </ul>
                    </section>
                </main>
            </div>
        </div>
    </div>

    <!------------------------------ footer -------------------->
    <?php
    // including footer
    include 'footer.php';
    ?>
</body>

<style>
    /* css for faq page */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }


    h1 {
        margin: 0;
    }

    .faq {
        margin: 50px auto;
        max-width: 600px;
    }

    h2 {
        font-weight: bolder;
        font-size: 24px;
        margin-bottom: 20px;
    }

    ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    li {
        margin-bottom: 20px;
    }

    h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    p {
        margin: 0;
    }
</style>

</html>