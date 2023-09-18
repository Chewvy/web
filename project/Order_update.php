<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Order Update - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<?php
include 'navbar.php';
?>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Order Update</h1>
        </div>

        <?php
        include 'config_folder/database.php';

        $customer_IDEr = $customer_Id = "";
        $product_IDEr = $quantityEr = array();

        $flag = true; // Initialize a flag to track form validation
        $resultFlag = false; // Initialize a flag to track the order placement result

        // Initialize customer_id and selected_num_products
        $customer_id = $selected_num_products = "";

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the order_id from the form
            $order_id = isset($_POST["order_id"]) ? $_POST["order_id"] : '';

            // Get the selected customer and product data from the form
            $customer_id = isset($_POST["customer_id"]) ? $_POST["customer_id"] : '';
            $selected_num_products = isset($_POST["selected_num_products"]) ? $_POST["selected_num_products"] : '';

            // Check if the selected customer is empty
            if (empty($customer_id)) {
                $customer_IDEr = "Please select a customer.";
                $flag = false;
            }

            // Check if the selected products are empty
            if (empty($selected_products)) {
                $product_IDEr[] = "Please select products.";
                $flag = false;
            }

            // Check if the selected quantities are empty or not numeric
            foreach ($selected_quantities as $quantity) {
                if (empty($quantity) || !is_numeric($quantity) || $quantity < 1) {
                    $quantityEr[] = "Invalid quantity. Quantity must be a positive number.";
                    $flag = false;
                }
            }

            // If all validation passes, update the order details
            if ($flag) {
                try {
                    // First, delete existing order details for the given order_id
                    $delete_order_query = "DELETE FROM order_details WHERE order_id = :order_id";
                    $delete_order_stmt = $con->prepare($delete_order_query);
                    $delete_order_stmt->bindParam(':order_id', $order_id);
                    $delete_order_stmt->execute();

                    // Then, insert the updated order details
                    for ($i = 0; $i < count($selected_products); $i++) {
                        $product_id = $selected_products[$i];
                        $quantity = $selected_quantities[$i];

                        $insert_order_query = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
                        $insert_order_stmt = $con->prepare($insert_order_query);
                        $insert_order_stmt->bindParam(':order_id', $order_id);
                        $insert_order_stmt->bindParam(':product_id', $product_id);
                        $insert_order_stmt->bindParam(':quantity', $quantity);
                        $insert_order_stmt->execute();
                    }

                    // Set the result flag to true
                    $resultFlag = true;
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            }
        }

        // Fetch order details from the database based on the provided order_id
        $order_id = isset($_GET["order_id"]) ? $_GET["order_id"] : '';
        $order_details = [];

        try {
            $order_detail_query = "SELECT od.product_id, p.name, od.quantity
                                   FROM order_details od
                                   INNER JOIN products p ON od.product_id = p.id
                                   WHERE od.order_id = :order_id";

            $order_detail_stmt = $con->prepare($order_detail_query);
            $order_detail_stmt->bindParam(':order_id', $order_id);
            $order_detail_stmt->execute();
            $order_details = $order_detail_stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php if ($resultFlag): ?>
            <div class='alert alert-success'>Order details updated successfully.</div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer</td>
                    <td>
                        <select class="form-select" name="customer_id">
                            <option value="">Select customer</option>
                            <?php
                            // Retrieve customer records from the database
                            $customer_query = "SELECT customer_id, username, first_name, last_name FROM customer";
                            $customer_stmt = $con->prepare($customer_query);
                            $customer_stmt->execute();

                            while ($row = $customer_stmt->fetch(PDO::FETCH_ASSOC)) {
                                $option = $row['username']; // Include 'username' in the query
                                $id = $row['customer_id'];
                                $selected = ($customer_id == $id) ? 'selected' : '';
                                echo "<option value='$id' $selected>$option</option>";
                            }

                            ?>
                        </select>
                        <div class='text-danger'>
                            <?php echo $customer_IDEr; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Order Details</td>
                    <td>
                        <table class="table table-bordered">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                            </tr>
                            <?php foreach ($order_details as $order_item): ?>
                                <tr>
                                    <td><?php echo $order_item['name']; ?></td>
                                    <td><?php echo $order_item['quantity']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>Number of Products</td>
                    <td>
                        <select class="form-select" name="selected_num_products">
                            <?php
                            for ($num = 1; $num <= 10; $num++) {
                                $selected = ($selected_num_products == $num) ? 'selected' : '';
                                echo "<option value='$num' $selected>$num</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <?php
                for ($productIndex = 0; $productIndex < $selected_num_products; $productIndex++) {
                    $selected_product_id = isset($selected_products[$productIndex]) ? $selected_products[$productIndex] : "";
                    $selected_quantity = isset($selected_quantities[$productIndex]) ? $selected_quantities[$productIndex] : "";

                    ?>
                    <tr>
                        <td>Product <?php echo $productIndex + 1 ?></td>

                        <td>
                            <select class="form-select" name="product_ID[]">
                                <option value="">Select product</option>
                                <?php
                                foreach ($productData as $product) {
                                    $product_id = $product['id'];
                                    $product_name = $product['name'];
                                    $product_price = $product['price'];
                                    $product_promotion_price = $product['promotion_price'];

                                    $selected = ($selected_product_id == $product_id) ? 'selected' : '';

                                    if ($product_promotion_price > 0) {
                                        echo "<option value='$product_id' $selected>$product_name - Price: $product_price, *Promotion Price: $product_promotion_price</option>";
                                    } else {
                                        echo "<option value='$product_id' $selected>$product_name - Price: $product_price</option>";
                                    }
                                }
                                ?>
                            </select>
                            <div class='text-danger'>
                                <?php if (!empty($product_IDEr[$productIndex])) {
                                    echo $product_IDEr[$productIndex];
                                } ?>
                            </div>
                        </td>

                        <td>Quantity</td>
                        <td><input type='number' name='quantity[]' class='form-control'
                                value="<?php echo $selected_quantity; ?>" />
                            <div class='text-danger'>
                                <?php if (!empty($quantityEr[$productIndex])) {
                                    echo $quantityEr[$productIndex];
                                } ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save changes' class='btn btn-primary' />
                        <a href='Order_listing.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>
