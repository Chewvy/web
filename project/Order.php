<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Customers - PHP CRUD Tutorial</title>
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
            <h1>Order</h1>
        </div>

        <?php
        include 'config_folder/database.php';

        if ($_POST) {
            try {
                // Convert username to CUSTOMER ID
                $username = strip_tags($_POST['username']); //check whetheris there any username in the database
                $customerQuery = "SELECT customer_id FROM customer WHERE username = :username"; //with the inserted username convert it to customer_id
                $customerStmt = $con->prepare($customerQuery);
                $customerStmt->bindParam(':username', $username); //bind username 
                $customerStmt->execute();
                $customerRow = $customerStmt->fetch(PDO::FETCH_ASSOC);

                if ($customerRow) {
                    $customer_id = $customerRow['customer_id'];
                    //f customerRow is empty then it will skipped the step,if it is not empty then it will continue th following steps
        
                    // Insert query for ORDER SUMMARY
                    $orderQuery = "INSERT INTO order_summary SET customer_id=:customer_id, order_date=:order_date";
                    $orderStmt = $con->prepare($orderQuery);

                    // Get current date as order date
                    $order_date = date('Y-m-d H:i:s');

                    $orderStmt->bindParam(':customer_id', $customer_id); //Bind the information that tye by user one and the variable
                    $orderStmt->bindParam(':order_date', $order_date);

                    // Validate product selections
                    $product_1_id = $_POST['product_1_id']; //match the selected item with the variables
                    $product_1_quantity = $_POST['product_1_quantity']; //match the selected item with the variables
                    $product_2_id = $_POST['product_2_id'];
                    $product_2_quantity = $_POST['product_2_quantity'];
                    $product_3_id = $_POST['product_3_id'];
                    $product_3_quantity = $_POST['product_3_quantity'];

                    if (empty($username)) {
                        echo "<div class='alert alert-danger'>Please enter the username</div>";
                    } elseif (empty($product_1_id) || empty($product_2_id) || empty($product_3_id)) {
                        echo "<div class='alert alert-danger'>Please select all three products</div>";
                    } else {
                        if ($orderStmt->execute()) {
                            $order_id = $con->lastInsertId();

                            // Insert query for ORDER DETAILS
                            $orderDetailQuery = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                            $orderDetailStmt = $con->prepare($orderDetailQuery);

                            $orderDetailStmt->bindParam(':order_id', $order_id);
                            $orderDetailStmt->bindParam(':product_id', $product_id);
                            $orderDetailStmt->bindParam(':quantity', $quantity);

                            // Insert the selected product IDs and quantities into order_details table
                            $orderDetailStmt->bindParam(':product_id', $product_1_id);
                            $orderDetailStmt->bindParam(':quantity', $product_1_quantity);
                            $orderDetailStmt->execute();

                            $orderDetailStmt->bindParam(':product_id', $product_2_id);
                            $orderDetailStmt->bindParam(':quantity', $product_2_quantity);
                            $orderDetailStmt->execute();

                            $orderDetailStmt->bindParam(':product_id', $product_3_id);
                            $orderDetailStmt->bindParam(':quantity', $product_3_quantity);
                            $orderDetailStmt->execute();

                            echo "<div class='alert alert-success'>Order placed successfully.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Failed to place order.</div>";
                        }
                    }
                } else {
                    echo "<div class='alert alert-danger'>Username not found.</div>";
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        $category = "SELECT id, name FROM products";
        $stmt2 = $con->prepare($category);
        $stmt2->execute();
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username:</td>
                    <td><input type="text" id="username" name="username" class='form-control'
                            value="<?php echo isset($name) ? $name : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Product 1:</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="product_1_id"
                            id="product_1_id">
                            <option value="">Select Product</option>

                            <?php
                            $stmt2->execute();
                            while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<option value='$id'>$name</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>Quantity:</td>
                    <td><input type="number" name="product_1_quantity" min="1" max="5"></td>
                </tr>
                <tr>
                    <td>Product 2:</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="product_2_id"
                            id="product_2_id">
                            <option value="">Select Product</option>

                            <?php
                            $stmt2->execute();
                            while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<option value='$id'>$name</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>Quantity:</td>
                    <td><input type="number" name="product_2_quantity" min="1" max="5"></td>
                </tr>
                <tr>
                    <td>Product 3:</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="product_3_id"
                            id="product_3_id">
                            <option value="">Select Product</option>

                            <?php
                            $stmt2->execute();
                            while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<option value='$id'>$name</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>Quantity:</td>
                    <td><input type="number" name="product_3_quantity" min="1" max="5"></td>
                </tr>
            </table>

            <input type="submit" class="btn btn-primary" value="Place Order">
        </form>
    </div>
</body>

</html>