<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script></head>

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

        if (isset($_GET['search'])) {
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

        // delete message prompt will be here
        $action = isset($_GET['action']) ?
        $_GET['action'] : "";

        // if it was redirected from delete.php

        if($action=='deleted'){

        echo "<div class='alert
        alert-success'>Record was deleted.</div>";

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

                $total_price = 0.0; // Initialize total price as a float

                while ($order_detail_row = $order_details_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $product_id = $order_detail_row['product_id'];
                    $quantity = $order_detail_row['quantity'];

                    $product_query = "SELECT price, promotion_price FROM products WHERE id = :product_id";
                    $product_stmt = $con->prepare($product_query);
                    $product_stmt->bindParam(':product_id', $product_id);
                    $product_stmt->execute();
                    $product_info = $product_stmt->fetch(PDO::FETCH_ASSOC);

                    if ($product_info) {
                        $product_price = $product_info['price']; // Ensure it's a float
                        $product_promotion_price = $product_info['promotion_price']; // Ensure it's a float
                        $item_price = ($product_promotion_price > 0.0) ? $product_promotion_price : $product_price;
                        $item_total_price = $item_price * $quantity; // Ensure they are floats

                        // Ensure that $item_total_price is a numeric value before adding to $total_price
                        if (is_numeric($item_total_price)) {
                            $total_price += $item_total_price;
                        }
                    } else {
                        $item_total_price = 0.0;
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
                echo "<a href='Order_delete.php?order_id={$order_id}' onclick='return confirm(\"Are you sure you want to delete this order?\");' class='btn btn-danger'>Delete</a>";
                
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

        <!-- Latest compiled and minified Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script type='text/javascript'>
    // Function to handle the delete button click
    function delete_order(order_id) {
        var answer = confirm('Are you sure you want to delete this order?');

        if (answer) {
            // If the user clicked OK, navigate to the delete.php page with the order_id parameter
            window.location = 'Order_delete.php?order_id=' + order_id;
        }

        return false; // Prevent further event propagation
    }
</script>


</body>

</html>
