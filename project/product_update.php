<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Update Product - PHP CRUD Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Update Product</h1>
        </div>
        <?php
        // get passed parameter value, in this case, the record ID
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        // include database connection
        include 'config_folder/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT p.id, p.name, p.description, p.categoryID, c.category_name, p.price, p.promotion_price, p.manufacture_date, p.expire_date
            FROM products p
            INNER JOIN product_category c ON p.categoryID = c.categoryID
            WHERE p.id = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $categoryID = $row['categoryID'];
            $category_name = $row['category_name'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
            $manufacture_date = $row['manufacture_date'];
            $expire_date = $row['expire_date'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        // check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // posted values
                $name = strip_tags($_POST['name']);
                $description = strip_tags($_POST['description']);
                $price = strip_tags($_POST['price']);
                $promotion_price = ($_POST['promotion_price'] !== '') ? strip_tags($_POST['promotion_price']) : null;
                $manufacture_date = strip_tags($_POST['manufacture_date']);
                $expire_date = strip_tags($_POST['expire_date']);
                $categoryID = $_POST['category']; // Get categoryID from the form

                // Execute the query
                $flag = true;

                if (empty($name)) {
                    echo "<div class='alert alert-danger'>Please enter the product name</div>";
                    $flag = false;
                }
                if (empty($description)) {
                    echo "<div class='alert alert-danger'>Please enter a description</div>";
                    $flag = false;
                }
                if (empty($categoryID)) {
                    echo "<div class='alert alert-danger'>Please select a category</div>";
                    $flag = false;
                }
                if (empty($price)) {
                    echo "<div class='alert alert-danger'>Please enter a price</div>";
                    $flag = false;
                }

                if (!empty($promotion_price)) {
                    if (!is_numeric($promotion_price)) {
                        echo "<div class='alert alert-danger'>Promotion price must be a number</div>";
                        $flag = false;
                    } elseif ($promotion_price > $price) {
                        echo "<div class='alert alert-danger'>Promotion price must be lower than the original price</div>";
                        $flag = false;
                    }
                } else {
                    $promotion_price = 0;
                }
                
                // Add this code before using $category
                $category = isset($_POST['category']) ? $_POST['category'] : $categoryID; // Use $categoryID as a fallback value

                if (empty($manufacture_date)) {
                    echo "<div class='alert alert-danger'>Please select a manufacture date</div>";
                    $flag = false;
                } elseif ($manufacture_date > $expire_date) {
                    echo "<div class='alert alert-danger'>Manufacture date must be earlier than the expire date</div>";
                    $flag = false;
                }

                if (empty($expire_date)) {
                    echo "<div class='alert alert-danger'>Please select a manufacture date</div>";
                    $flag = false;
                } elseif ($expire_date < $manufacture_date) {
                    echo "<div class='alert alert-danger'>Expire date must be later than the manufacture date</div>";
                    $flag = false;
                }

                if ($flag) {
                    // Update query
                    $query = "UPDATE products SET name=:name, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expire_date=:expire_date, categoryID=:categoryID WHERE id = :id";

                    // Prepare query for execution
                    $stmt = $con->prepare($query);

                    // Bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':promotion_price', $promotion_price, PDO::PARAM_STR); // Use PDO::PARAM_STR to handle null values
                    $stmt->bindParam(':manufacture_date', $manufacture_date);
                    $stmt->bindParam(':expire_date', $expire_date);
                    $stmt->bindParam(':categoryID', $categoryID);
                    $stmt->bindParam(':id', $id);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update the record.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- Display the existing data and allow updates -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>" class='form-control' />
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES); ?></textarea></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select class="form-control" aria-label="Default select example" name="category">
                            <option value="">Select a Category</option>

                            <?php
                            // Fetch product categories from database and populate the dropdown
                            $categoryQuery = "SELECT categoryID, category_name FROM product_category";
                            $categoryStmt = $con->prepare($categoryQuery);
                            $categoryStmt->execute();

                            while ($categoryRow = $categoryStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($categoryRow);
                                $selected = ($categoryID == (isset($categoryID) ? $categoryID : $categoryID)) ? 'selected' : '';
                                echo "<option value='$categoryID' $selected>$category_name</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td colspan="2">
                        <input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES); ?>" class='form-control' />
                    </td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td colspan="2">
                        <input type='text' name='promotion_price' value="<?php echo htmlspecialchars($promotion_price, ENT_QUOTES); ?>" class='form-control' />
                    </td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td colspan="2">
                        <input type='date' name='manufacture_date' class='form-control' value="<?php echo isset($manufacture_date) ? $manufacture_date : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td colspan="2">
                        <input type='date' name='expire_date' class='form-control' value="<?php echo isset($expire_date) ? $expire_date : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='product_index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
</body>

</html>
