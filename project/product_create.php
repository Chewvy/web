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

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
        </div>

        <?php

        if ($_POST) {
            // include database connection
            include 'config_folder/database.php';
            ;
            try {
                // insert query
                $query = "INSERT INTO products (name, description, price, created) VALUES (:name, :description, :price, :created)";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // posted values
                $name = strip_tags($_POST['name']);
                $description = strip_tags($_POST['description']);
                $price = strip_tags($_POST['price']);
                $promotion_price = strip_tags($_POST['promotion_price']);
                $manufacture_date = strip_tags($_POST['manufacture_date']);
                $promotion_price = strip_tags($_POST['promotion_price']);
                // bind the parameters
        
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotion_price', $promotion_price);
                $stmt->bindParam(':manufacture_date', $manufacture_date);
                $stmt->bindParam(':expire_date', $expire_date);
                // specify when this record was inserted to the database
        
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);

                // Execute the query
                $flag = true;

                if (empty($name)) {
                    echo "<div class='alert alert-danger'>Please enter a name.</div>";
                    $flag = false;
                }
                if (empty($description)) {
                    echo "<div class='alert alert-danger'>Please enter a description.</div>";
                    $flag = false;
                }
                if (empty($price)) {
                    echo "<div class='alert alert-danger'>Please enter a price.</div>";
                    $flag = false;
                }
                if (empty($promotion_price)) {
                    echo "<div class='alert alert-danger'>Please enter promotion price.</div>";
                    $flag = false;
                }
                if (empty($manufacture_date)) {
                    echo "<div class='alert alert-danger'>Please enter manufacture date.</div>";
                    $flag = false;
                }
                if (empty($expire_date)) {
                    echo "<div class='alert alert-danger'>Please enter expire date.</div>";
                    $flag = false;
                }

                if ($price < $promotion_price) {
                    echo "<div class='alert alert-danger'>Promotion price must be cheaper than original price.</p>";
                } else if (strtotime($manufacture_date) < strtotime($expire_date)) {
                    echo "<div class='alert alert-danger'>expired date must later than manufacture date.</p>";
                }

                if ($flag == true && $stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>
        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expire_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='config/database.php' class='btn btn-danger'>Back to read products</a>
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