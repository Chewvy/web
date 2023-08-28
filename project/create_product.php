<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS (Apply your
Bootstrap here -->
</head>


<?php
include 'navbar.php';
?>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
        </div>

        <?php
        // include database connection
        include 'config_folder/database.php';

        if ($_POST) {
            try {
                // posted values
                $name = strip_tags($_POST['name']);
                $description = strip_tags($_POST['description']);
                $price = strip_tags($_POST['price']);
                $promotion_price = strip_tags($_POST['promotion_price']);
                $manufacture_date = strip_tags($_POST['manufacture_date']);
                $expire_date = strip_tags($_POST['expire_date']);
                $categoryID = $_POST['categoryID']; // Get categoryID from the form
        
                // Execute the query
                $flag = true;

                if (empty($name)) {
                    echo "<div class='alert alert-danger'>Please enter the username</div>";
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
                    echo "<div class='alert alert-danger'>Please type a price</div>";
                    $flag = false;
                }
                if (!empty($promotion_price)) {
                    if (is_numeric($promotion_price))
                        echo "<div class='alert alert-danger'>Promotion price must be number</div>";
                    $flag = false;
                } else if ($promotion_price > $price) {
                    echo "<div class='alert alert-danger'>Promotion price must be lower than original price</div>";
                    $flag = false;
                } else {
                    $promotion_price = 0;
                }

                if (empty($manufacture_date)) {
                    echo "<div class='alert alert-danger'>Please select a manufacture date</div>";
                    $flag = false;
                } else if ($manufacture_date > $expire_date) {
                    echo "<div class='alert alert-danger'>Manufacture date must be earlier than expire date</div>";
                    $flag = false;
                }
                if ($flag) { // No need to use === true, just use $flag directly
                    // insert query
                    $query = "INSERT INTO products SET name=:name, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expire_date=:expire_date, created=:created, categoryID=:categoryID";

                    // prepare query for execution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':promotion_price', $promotion_price);
                    $stmt->bindParam(':manufacture_date', $manufacture_date);
                    $stmt->bindParam(':expire_date', $expire_date);
                    $stmt->bindParam(':categoryID', $categoryID); // Bind the categoryID
        
                    // specify when this record was inserted to the database
                    $created = date('Y-m-d H:i:s');
                    $stmt->bindParam(':created', $created);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        $category = "SELECT categoryID, category_name FROM product_category";
        $stmt2 = $con->prepare($category);
        $stmt2->execute();
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control'
                            value="<?php echo isset($name) ? $name : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'
                            value="<?php echo isset($description) ? $description : ''; ?>"></textarea></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="categoryID" id="category">
                            <option value="">Select Category</option>

                            <?php
                            while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $selected = isset($_POST['categoryID']) && $_POST['categoryID'] == $categoryID ? 'selected' : '';
                                echo "<option value='$categoryID' $selected>$category_name</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control'
                            value="<?php echo isset($price) ? $price : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control'
                            value="<?php echo isset($promotion_price) ? $promotion_price : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control'
                            value="<?php echo isset($manufacture_date) ? $manufacture_date : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expire_date' class='form-control'
                            value="<?php echo isset($expire_date) ? $expire_date : ''; ?>"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>