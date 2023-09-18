<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Order Details - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <style>
        /* Add CSS styles to align tables */
        .custom-table {
            margin-bottom: 20px;
        }
        .custom-table th {
            background-color: #337ab7; /* Change the color as needed */
            color: white; /* Text color for table headers */
        }
    </style>
</head>

<?php
include 'navbar.php';
?>

<body>

<?php
include 'config_folder/database.php';
?>

<div class="container">
    <div id="Best_selling_product" class="custom-table">
        <div class="page-header">
            <h1>Top 5 Best Selling Products</h1>
        </div>
<?php

        $query = "SELECT p.id, p.name, SUM(od.quantity) as quantity 
        FROM products p
        INNER JOIN order_details od ON p.id = od.product_id
        GROUP BY quantity 
        ORDER BY quantity DESC
        LIMIT 5";

        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

         if ($num > 0) {

            // data from database will be here >

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>No.</th>";
            echo "<th>Product Name</th>";
            echo "<th>Sold Quantity</th>";
            echo "</tr>";

            $counter = 1;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$counter}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$quantity}</td>";
                $counter++;
                echo "</tr>";
            }
        }
        echo "</table>";
        ?>
        </div>

        <div id="Least_selling_product" class="custom-table">
        <div class="page-header">
            <h1>Top 5 Least Selling Products</h1>
        </div>
<?php

        $query = "SELECT p.id, p.name, SUM(od.quantity) as quantity 
        FROM products p
        INNER JOIN order_details od ON p.id = od.product_id
        GROUP BY quantity 
        ORDER BY quantity ASC
        LIMIT 5";

        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

         if ($num > 0) {

            // data from database will be here >

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>No.</th>";
            echo "<th>Product Name</th>";
            echo "<th>Sold Quantity</th>";
            echo "</tr>";

            $counter = 1;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$counter}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$quantity}</td>";
                $counter++;
                echo "</tr>";
            }
        }
        echo "</table>";
        ?>
        </div>

        <div id="Least_selling_product"  class="custom-table">
        <div class="page-header">
            <h1>Top 5 Most Purchase Customer</h1>
        </div>
<?php

        $query = "SELECT c.customer_id, c.username, COUNT(os.order_id) as total 
        FROM customer c
        INNER JOIN order_summary os ON c.customer_id = os.customer_id
        GROUP BY c.customer_id
        ORDER BY total DESC
        LIMIT 5";


        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

         if ($num > 0) {

            // data from database will be here >

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>Customer ID</th>";
            echo "<th>Username</th>";
            echo "<th>Total Order</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$customer_id}</td>";
                echo "<td>{$username}</td>";
                echo "<td>{$total}</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
        ?>
        </div>
    
        <div id="Highest_Amount_of_Order"  class="custom-table">
    <div class="page-header">
        <h1>Top 5 Highest Amount of Order</h1>
    </div>

    <?php
    $query = "SELECT od.order_id, COUNT(od.product_id) as total_product, SUM(p.price * od.quantity) as total_amount
              FROM order_details od
              INNER JOIN products p ON p.id = od.product_id
              GROUP BY od.order_id
              ORDER BY total_amount DESC
              LIMIT 5";

    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();

    if ($num > 0) {
        echo "<div class='container'>";
        echo "<table class='table table-hover table-responsive table-bordered'>";
        echo "<tr>";
        echo "<th>Order ID</th>";
        echo "<th>Number of Products</th>";
        echo "<th style='text-align: right;'>Total Amount</th>";
        echo "</tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<tr>";
            echo "<td>{$order_id}</td>";
            echo "<td>{$total_product}</td>";
            echo "<td style='text-align: right;'>RM " . number_format($total_amount, 2) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } 
    ?>

     <div id="Category_of_product_in_the_store"  class="custom-table">
    <div class="page-header">
        <h1>Top 5 Category of product in the store</h1>
    </div>

    <?php
    $query = "SELECT c.category_name, c.categoryID, COUNT(p.categoryID) as total
              FROM product_category c
              INNER JOIN products p ON p.categoryID = c.categoryID
              GROUP BY c.categoryID
              ORDER BY total DESC
              LIMIT 5";

    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();

    if ($num > 0) {
        echo "<div class='container'>";
        echo "<table class='table table-hover table-responsive table-bordered'>";
        echo "<tr>";
        echo "<th>Category ID</th>";
        echo "<th>Category Name</th>";
        echo "<th>Total Product</th>";
        echo "</tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<tr>";
            echo "<td>{$categoryID}</td>";
            echo "<td>{$category_name}</td>";
            echo "<td>{$total}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
    ?>
</div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>