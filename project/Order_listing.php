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

<body>
<?php
include 'navbar.php';
?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Order Listing</h1>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="Order.php" class="btn btn-primary m-b-1em">Place Order</a>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
                <input type="search" id="search" name="search" class="form-control me-2" />
                <input type="submit" value="Search" class="btn btn-warning" />
            </form>
        </div>

        <?php
        // Include database connection
        include 'config_folder/database.php';

        if ($_GET) { // Check if there's a search value
            $search = $_GET['search'];

            if (!empty($search)) { // Make sure search value is not empty
                $query = "SELECT os.order_id, c.username, c.first_name, c.last_name
                FROM order_summary os
                INNER JOIN customer c ON c.customer_id = os.customer_id 
                WHERE os.order_id LIKE '%$search%' OR c.username LIKE '%$search%'
                ORDER BY os.order_id ASC";
            } else {
                $query = "SELECT os.order_id, c.username, c.first_name, c.last_name
                FROM order_summary os
                INNER JOIN customer c ON c.customer_id = os.customer_id 
                ORDER BY os.order_id ASC";
            }
        } else {
            $query = "SELECT os.order_id, c.username, c.first_name, c.last_name
                FROM order_summary os
                INNER JOIN customer c ON c.customer_id = os.customer_id 
                ORDER BY os.order_id ASC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();
        

        $query = "SELECT order_id, customer_id, order_date FROM order_summary ORDER BY order_id ASC";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            echo "<div class='table-responsive'>"; // Add a div for table responsiveness
            echo "<table class='table table-hover table-bordered'>";
            echo "<tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Customer Name</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                  </tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $customer_query = "SELECT username, first_name, last_name FROM customer WHERE customer_id = :customer_id";
                $customer_stmt = $con->prepare($customer_query);
                $customer_stmt->bindParam(':customer_id', $customer_id);
                $customer_stmt->execute();
                $customer_info = $customer_stmt->fetch(PDO::FETCH_ASSOC);

                $order_details_query = "SELECT product_id, quantity FROM order_details WHERE order_id = :order_id";
                $order_details_stmt = $con->prepare($order_details_query);
                $order_details_stmt->bindParam(':order_id', $order_id);
                $order_details_stmt->execute();

                $total_price = 0;

                while ($order_detail_row = $order_details_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $product_id = $order_detail_row['product_id'];
                    $quantity = $order_detail_row['quantity'];

                    $product_query = "SELECT price, promotion_price FROM products WHERE id = :product_id";
                    $product_stmt = $con->prepare($product_query);
                    $product_stmt->bindParam(':product_id', $product_id);
                    $product_stmt->execute();
                    $product_info = $product_stmt->fetch(PDO::FETCH_ASSOC);

                    if ($product_info) {
                        $product_price = $product_info['price'];
                        $product_promotion_price = $product_info['promotion_price'];
                        $item_price = ($product_promotion_price > 0) ? $product_promotion_price : $product_price;
                        $item_total_price = $item_price * $quantity;

                        $total_price += $item_total_price;
                    } else {
                        $item_total_price = 0;
                    }
                }
                $total_price = number_format($total_price, 2);

                echo "<tr>";
                echo "<td>{$order_id}</td>";
                echo "<td>{$customer_info['username']}</td>";
                echo "<td>{$customer_info['first_name']} {$customer_info['last_name']}</td>";
                echo "<td class='text-right'>RM{$total_price}</td>";
                echo "<td>{$order_date}</td>";
                echo "<td>";
                echo "<a href='order_details.php?order_id={$order_id}' class='btn btn-info m-r-1em'>Read</a>";
                echo "<a href='#' class='btn btn-primary m-r-1em'>Edit</a>";
                echo "<a href='#' onclick='delete_order({$order_id});' class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>"; // Close the table-responsive div
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>
    </div> <!-- end .container -->
</body>

</html>
