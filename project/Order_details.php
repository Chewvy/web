<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Order Details - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script></head>
</head>

<?php
include 'navbar.php';
?>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Order Details</h1>
        </div>

        <?php
        // Get the order_id from the URL parameter
        $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: Order ID not found.');

        // Include database connection
        include 'config_folder/database.php';

        try {
            $Detail_query = "SELECT os.order_id, os.order_date, os.customer_id, c.username, c.first_name, c.last_name FROM order_summary os
            LEFT JOIN customer c ON c.customer_id = os.customer_id 
            WHERE order_id = ?";

            $Detail_stmt = $con->prepare($Detail_query);
            $Detail_stmt->bindParam(1, $order_id);
            $Detail_stmt->execute();
            $Detail_num = $Detail_stmt->rowCount();

            // Check if there are any details for this order ID
            if ($Detail_num > 0) {
                $Detail_row = $Detail_stmt->fetch(PDO::FETCH_ASSOC);
                extract($Detail_row);

                echo "<table class='table table-hover table-responsive table-bordered'>";
                echo "<tr>";
                echo "<th>Order ID</th>";
                echo "<td colspan=5>{$order_id}</td>";
                echo "</tr><tr>";
                echo "<th>Order Date & Time</th>";
                echo "<td colspan=5>{$order_date}</td>";
                echo "</tr><tr>";
                echo "<th>Customer ID</th>";
                echo "<td colspan=5>{$customer_id}</td>";
                echo "</tr><tr>";
                echo "<th>Customer Name</th>";
                echo "<td colspan=5>{$first_name} {$last_name} ({$username})</td>";
                echo "</tr>";
                echo "</table>";

                echo "<table class='table table-hover table-responsive table-bordered'>";
                echo "<tr>
                <th>Name</th>
                <th>Price</th>
                <th>Total</th>
                </tr>";

                // Prepare select query to fetch order details
                $order_details_query = "SELECT order_id, product_id, quantity FROM order_details WHERE order_id = ?";
                $order_details_stmt = $con->prepare($order_details_query);
                $order_details_stmt->bindParam(1, $order_id);
                $order_details_stmt->execute();

                $total_price = 0;

                while ($order_detail_row = $order_details_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $product_id = $order_detail_row['product_id'];
                    $quantity = $order_detail_row['quantity'];

                    // Fetch product information using a separate query
                    $product_query = "SELECT name, price, promotion_price FROM products WHERE id = ?";
                    $product_stmt = $con->prepare($product_query);
                    $product_stmt->bindParam(1, $product_id);
                    $product_stmt->execute();
                    $product_info = $product_stmt->fetch(PDO::FETCH_ASSOC);

                    $name = $product_info['name'];
                    $price = $product_info['price'];
                    $promotion_price = $product_info['promotion_price'];

                    // Calculate the total price based on promotion price if available, else use normal price
                    if ($promotion_price > 0) {
                        $total = $promotion_price * $quantity;
                        $formatted_price = "RM " . number_format($promotion_price, 2);
                        $normal_price = "<s>RM " . number_format($price, 2) . "</s>";
                    } else {
                        $total = $price * $quantity;
                        $formatted_price = "RM " . number_format($price, 2);
                        $normal_price = "";
                    }

                    $total_price += $total;

                    echo "<tr>";
                    echo "<td>{$name}</td>";
                    echo "<td class='text-end'>{$normal_price} {$formatted_price} x {$quantity}</td>"; // Align price values to the right
                    echo "<td class='text-end'>RM " . number_format($total, 2) . "</td>"; // Align the total to the right
                    echo "</tr>";
                }

                echo "<tr>";
                echo "<td colspan='2' class='text-end'>Total:</td>";
                echo "<td class='text-end'>RM " . number_format($total_price, 2) . "</td>"; // Align "Total" to the right
                echo "</tr>";

                echo "</table>";

                echo "<div class='text-end'>";
                echo "<a href='Order_listing.php' class='btn btn-danger'>Back to Order List</a>";
                echo "</div>";
            } else {
                echo "No details found for this order ID.";
            }
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>
    </div> <!-- end .container -->
</body>

</html>
