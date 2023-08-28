<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<?php
include 'navbar.php';
?>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Order Listing</h1>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
            <?php echo "<a href='create_product.php' class='btn btn-primary m-b-1em'>Create New Product</a>"; ?>
            <input type='search' name="search" class="ml-auto"> <!-- Use ml-auto to push to the right -->
            <input type='submit' value='Search' class='btn btn-primary m-r-1em'>
        </form>

        <?php
        // Include database connection
        include 'config_folder/database.php';
        $query = "SELECT order_id, customer_id, order_date FROM order_summary ORDER BY order_id ASC";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) { //6 columns
            echo "<table class='table table-hover table-responsive table-bordered'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Order ID</th>";
            echo "<th>Username</th>";
            echo "<th>Customer Name</th>";
            echo "<th>Total Price</th>"; // Add Total Price column
            echo "<th>Order Date</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                // Fetch customer information separately
                $customer_query = "SELECT username, first_name, last_name FROM customer WHERE customer_id = :customer_id";
                $customer_stmt = $con->prepare($customer_query);
                $customer_stmt->bindParam(':customer_id', $customer_id);
                $customer_stmt->execute();
                $customer_info = $customer_stmt->fetch(PDO::FETCH_ASSOC);

                // Calculate total price and other order details
                $order_details_query = "SELECT product_id, quantity FROM order_details WHERE order_id = :order_id";
                $order_details_stmt = $con->prepare($order_details_query);
                $order_details_stmt->bindParam(':order_id', $order_id);
                $order_details_stmt->execute();

                $total_price = 0; //default is 0
        
                while ($order_detail_row = $order_details_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $product_id = $order_detail_row['product_id'];
                    $quantity = $order_detail_row['quantity'];

                    $product_query = "SELECT price, promotion_price FROM products WHERE id = :product_id";
                    $product_stmt = $con->prepare($product_query);
                    $product_stmt->bindParam(':product_id', $product_id);
                    $product_stmt->execute();
                    $product_info = $product_stmt->fetch(PDO::FETCH_ASSOC);

                    $product_price = $product_info['price'];
                    $product_promotion_price = $product_info['promotion_price'];
                    $item_price = ($product_promotion_price > 0) ? $product_promotion_price : $product_price;
                    //如果有promotion price就用，else就用原价
                    $item_total_price = $item_price * $quantity;

                    $total_price += $item_total_price; //原本是0,加了total price就等于他的总数
                }
                $total_price = number_format($total_price, 2, '.', ''); //把价钱的后面放2个0
        
                echo "<tr>";
                echo "<td>{$order_id}</td>";
                echo "<td>{$customer_info['username']}</td>"; // Display username from customer_info
                echo "<td>{$customer_info['first_name']} {$customer_info['last_name']}</td>"; // Display combined name from customer_info
                echo "<td>{$total_price}</td>"; // Display total_price
                echo "<td>{$order_date}</td>";
                echo "<td>";
                echo "<a href='order_details.php?order_id={$order_id}' class='btn btn-info m-r-1em'>Read</a>";
                echo "<a href='# ?id={$order_id}' class='btn btn-primary m-r-1em'>Edit</a>";
                echo "<a href='#' onclick='delete_user({$order_id});' class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>
    </div> <!-- end .container -->
</body>

</html>