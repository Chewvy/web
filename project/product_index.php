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
            <h1>Read Products</h1>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="create_product.php" class="btn btn-primary m-b-1em">Create New Product</a>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
                <input type="search" id="search" name="search" class="form-control me-2" />
                <input type="submit" value="Search" class="btn btn-warning" />
            </form>
        </div>

        <?php
        // include database connection
        include 'config_folder/database.php';
        $query = "SELECT p.id, p.name, p.categoryID, p.price, p.image, p.promotion_price, c.category_name, p.created 
        FROM products p
        INNER JOIN product_category c ON p.categoryID = c.categoryID 
        ORDER BY id ASC";

        // select data from products and join with product_category on categoryID
        // Check if there's a search value in the URL
        if (isset($_GET['search'])) {
            $search = $_GET['search'];

            if (!empty($search)) { // Make sure search value is not empty
                $query = "SELECT p.id, p.image, p.name, p.categoryID, p.price, p.promotion_price, c.category_name, p.created 
                    FROM products p
                    INNER JOIN product_category c ON p.categoryID = c.categoryID 
                    WHERE p.name LIKE '%$search%'
                    ORDER BY p.id ASC";
            }
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

        // this is how to get the number of rows returned
        $num = $stmt->rowCount();

        // check if more than 0 record found
        if ($num > 0) {

            // data from the database will be here
            echo "<div class='table-responsive'>"; // Add a div for table responsiveness
            echo "<table class='table table-hover table-bordered'>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Category Name</th>";
            echo "<th>Price</th>";
            echo "<th>Image</th>";
            echo "<th>Created</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$category_name}</td>";

                echo "<td>";
                $priceWithoutCurrency = floatval(str_replace("RM", "", $price));
                $promotionPriceWithoutCurrency = floatval(str_replace("RM", "", $promotion_price));

                $formattedPrice = "RM" . number_format($priceWithoutCurrency, 2);

                if ($promotionPriceWithoutCurrency > 0) {
                    $formattedPrice = "<span class='text-decoration-line-through'>" . "RM" . number_format($priceWithoutCurrency, 2) . "</span>" . ' ' . "RM" . number_format($promotionPriceWithoutCurrency, 2);
                }
                echo $formattedPrice;
                echo "</td>";
                echo "<td><img src='image/{$image}' alt='{$name}' class='img-thumbnail' style='max-width: 100px; max-height: 100px;'></td>";

                echo "<td>{$created}</td>";

                echo "<td>";
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";
                echo "<a href='product_update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";
                echo "<a href='#' onclick='delete_user({$id});' class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>
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
