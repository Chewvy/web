<?php
session_start();
?>

<!DOCTYPE HTML>

<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS -->
</head>

<?php
include 'navbar.php';
?>

<body>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
        </div>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        // include database connection
        include 'config_folder/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT p.id, p.name, p.description, p.categoryID, c.category_name, p.price, p.promotion_price, p.image, p.manufacture_date, p.expire_date
                      FROM products p
                      INNER JOIN product_category c ON p.categoryID = c.categoryID
                      WHERE p.id = ?";

            // create a new PDO statement
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

            // Convert and format price and promotion_price
            $price = floatval(str_replace("RM", "", $row['price']));
            $promotion_price = floatval(str_replace("RM", "", $row['promotion_price']));
            $f_price = "RM" . number_format($price, 2);
            if ($promotion_price > 0) {
                $f_price = "<span class='text-decoration-line-through'>" . "RM" . number_format($price, 2) . "</span>" . ' ' . "RM" . number_format($promotion_price, 2);
            }

            $image = $row['image'];
            $categoryID = $row['categoryID'];
            $category_name = $row['category_name'];
            $manufacture_date = $row['manufacture_date'];
            $expire_date = $row['expire_date'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!--we have our HTML table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td>
                    <?php echo htmlspecialchars($name, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td>
                    <?php echo htmlspecialchars($description, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Category ID</td>
                <td>
                    <?php echo htmlspecialchars($categoryID, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Category</td>
                <td>
                    <?php echo htmlspecialchars($category_name, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Price</td>
                <td>
                    <?php echo $f_price; ?>
                </td>
            </tr>
            <tr>
                <td>Manufacture Date</td>
                <td>
                    <?php echo htmlspecialchars($manufacture_date, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Image</td>
                <td>
                    <?php
                    if (!empty($image)) {
                        echo "<img src='image/$image' alt='Product Image' style='max-width: 200px;' />";
                    } else {
                        echo "<img src='ProductComingSoon.jpg/$image' alt='Product Coming Soon' style='max-width: 200px;' />";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Expire Date</td>
                <td>
                    <?php echo htmlspecialchars($expire_date, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='product_index.php' class='btn btn-danger'>Back to read products</a>
                </td>
            </tr>
        </table>

    </div> <!-- end .container -->

</body>

</html>
