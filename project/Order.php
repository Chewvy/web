<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<?php
include 'navbar.php';
?>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Order Form</h1>
        </div>

        <?php
        include 'config_folder/database.php';

        $customer_IDEr = $customer_Id = "";
        $product_IDEr = $quantityEr = array();

        if (isset($_POST['confirm'])) {
            $selected_num_products = intval($_POST["selected_num_products"]);

            if ($selected_num_products < 1) {
                $selected_num_productsEr = "The total product must be at least one.";
            }
        } else {
            $selected_num_products = 3;
        }

        if (isset($_POST['Submit_Order'])) {
            try {
                // Insert into order_summary
                $summary_query = "INSERT INTO order_summary SET customer_id=:customer_id, order_date=:order_date";
                $summary_stmt = $con->prepare($summary_query);

                $customer_id = $_POST['customer_id'];
                if (!empty($customer_id)) {
                    $summary_stmt->bindParam(':customer_id', $customer_id);
                    $order_date = date('Y-m-d H:i:s');
                    $summary_stmt->bindParam(':order_date', $order_date);
                    $summary_stmt->execute();
                }

                $order_id = $con->lastInsertId();

                $flag = true;
                $resultFlag = false;

                if ($_POST["customer_id"] == "") {
                    $customer_IDEr = "Please select a customer.";
                    $flag = false;
                }

                for ($l = 0; $l < count($_POST["product_ID"]); $l++) {
                    $quantityEr[$l] = "";

                    if ($_POST["quantity"][$l] == "" || $_POST["quantity"][$l] < 1) {
                        $quantityEr[$l] = "Please fill in a valid quantity greater than zero.";
                        $flag = false;
                    }
                }

                $productID_array = array_unique($_POST["product_ID"]);

                if (isset($_POST['selected_num_products']) && is_numeric($_POST['selected_num_products'])) {
                    $selected_num_products = intval($_POST['selected_num_products']);
                } else {
                    $selected_num_products = 3;
                }

                $selected_products = $_POST["product_ID"];
                $selected_quantities = $_POST["quantity"];

                for ($k = 0; $k < $selected_num_products; $k++) {
                    $quantityEr[$k] = "";

                    if ($selected_products[$k] != "" && $selected_quantities[$k] != "" && $selected_quantities[$k] >= 1) {
                        try {
                            // Insert the order item into the database
                            $insert_order_item_query = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                            $insert_order_item_stmt = $con->prepare($insert_order_item_query);

                            $product_id = $_POST['product_ID'][$k];
                            $quantity = $_POST['quantity'][$k];

                            $insert_order_item_stmt->bindParam(':order_id', $order_id);
                            $insert_order_item_stmt->bindParam(':product_id', $product_id);
                            $insert_order_item_stmt->bindParam(':quantity', $quantity);
                            $insert_order_item_stmt->execute();

                            $resultFlag = true;
                        } catch (PDOException $exception) {
                            die('ERROR: ' . $exception->getMessage());
                        }
                    } else {
                        $flag = false;
                        $quantityEr[$k] = "Invalid quantity.";
                    }
                }

                if ($flag && $resultFlag) {
                    echo "<div class='alert alert-success'>Order placed successfully.</div>";
                } elseif (!$flag) {
                    echo "<div class='alert alert-danger'>Please select all products and provide valid quantities before placing the order.</div>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            $selected_products = [];
            $selected_quantities = [];
        }

        $customerQuery = "SELECT customer_id, username, first_name, last_name FROM customer";
        $customerStmt = $con->prepare($customerQuery);
        $customerStmt->execute();

        $selectProduct_query = "SELECT id, name, price, promotion_price FROM products";
        $selectProduct_stmt = $con->prepare($selectProduct_query);
        $selectProduct_stmt->execute();

        $productID_array = array();
        $productDetails_array = array();

        foreach ($selectProduct_stmt as $row) {
            $productID_array[] = $row['id'];
            $productDetails_array[] = $row;
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer</td>
                    <td colspan=3>
                        <select class="form-select" name="customer_id" id="customer_id">
                            <option value="">Select username</option>
                            <?php
                            while ($row = $customerStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $selected = ($customer_id == (isset($_POST["customer_id"]) ? $_POST["customer_id"] : '')) ? 'selected' : '';
                                echo "<option value='$customer_id' $selected>$username ($first_name $last_name)</option>";
                                $customer_id = $customer_id;
                            }
                            ?>
                        </select>
                        <div class='text-danger'>
                            <?php echo $customer_IDEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Number of Products</td>
                    <td colspan="3">
                        <select class="form-select" name="selected_num_products" id="selected_num_products">
                            <?php
                            for ($num = 1; $num <= 10; $num++) {
                                $selected = ($selected_num_products == $num) ? 'selected' : '';
                                echo "<option value='$num' $selected>$num</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan=3>
                        <input type='submit' name='confirm' value='Confirm' class='btn btn-primary' />
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
                                for ($j = 0; $j < count($productID_array); $j++) {
                                    $product_id = $productID_array[$j];
                                    $product_name = $productDetails_array[$j]['name'];
                                    $product_price = $productDetails_array[$j]['price'];
                                    $product_promotion_price = $productDetails_array[$j]['promotion_price'];

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
                    <td colspan=3>
                        <input type='submit' name='Submit_Order' value='Submit Order' class='btn btn-primary' />
                    </td>
                </tr>
            </table>
        </form>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>
