<?php
session_start();
?>

<!DOCTYPE HTML>

<html>

<head>
<title>PDO - Read Order Details - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script></head>

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

            // delete message prompt will be here
            $action = isset($_GET['action']) ?
            $_GET['action'] : "";

            // if it was redirected from delete.php

            if($action=='deleted'){
            echo "<div class='alert alert-success'>Record was deleted.</div>";
            }

            if ($action == 'UnableDelete') {
                echo "<div class='alert alert-danger'>Unable to delete the product because it has orders associated with it.</div>";
            }
            
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
                <td>Expire Date</td>
                <td>
                    <?php echo htmlspecialchars($expire_date, ENT_QUOTES); ?>
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
                <td></td>
                <td>
                <a href='product_update.php?id=<?php echo $id; ?>' class='btn btn-warning m-r-1em'>Edit</a>
                <a href='product_index.php' class='btn btn-primary'>Back to read products</a>
                <a href='Order_delete.php?order_id=<?php echo $id; ?>' onclick='return confirm("Are you sure you want to delete this order?");' class='btn btn-danger'>Delete</a>
                </td>
            </tr>
        </table>

    </div> <!-- end .container -->
   <!-- Latest compiled and minified Bootstrap 5 JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

        <!-- confirm delete record will be here -->
    <script type='text/javascript'>

// confirm record deletion

function delete_user( id ){
var answer = confirm('Are you sure?');

if (answer){
// if user clicked ok,
// pass the id to delete.php and execute the delete query
window.location = 'product_delete.php?id=' + id;
}
}

</script>

</body>

</html>
