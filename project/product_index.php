<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Products - PHP CRUD Tutorial</title>
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
            <h1>Read Products</h1>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
            <?php echo "<a href='create_product.php' class='btn btn-primary m-b-1em'>Create New Product</a>"; ?>
            <input type='search' name="search" class="ml-auto"> <!-- Use ml-auto to push to the right -->
            <input type='submit' value='Search' class='btn btn-primary m-r-1em'>
        </form>

        <?php
        // include database connection
        include 'config_folder/database.php';
        $query = "SELECT p.id, p.name, p.categoryID, p.price, p.promotion_price, c.category_name,p.created 
        FROM products p
        INNER JOIN product_category c ON p.categoryID = c.categoryID 
        ORDER BY id ASC";

        // select data from products and join with product_category on categoryID
        if ($_GET) { // Check if there's a search value
            $search = $_GET['search'];

            if (!empty($search)) { // Make sure search value is not empty
                $query = "SELECT p.id, p.name, p.categoryID, p.price, p.promotion_price, c.category_name,p.created 
                FROM products p
                INNER JOIN product_category c ON p.categoryID = c.categoryID 
                WHERE p.name LIKE '%$search%'
                ORDER BY p.id ASC";
            }
        }
        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get the number of rows returned
        $num = $stmt->rowCount();

        // check if more than 0 record found
        if ($num > 0) {

            // data from the database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; // start table
        
            // creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Category Name</th>";
            echo "<th>Price</th>";
            echo "<th>Created</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$category_name}</td>"; // Add this line to display the Category value
                
                echo "<td>";
                $f_price = "RM" . number_format($price, 2);

                if ($promotion_price > 0) {
                    $f_price = "<span class='text-decoration-line-through'>" . "RM" . number_format($price, 2) . "</span>" . ' ' . "RM" . number_format($promotion_price, 2);
                }
                echo $f_price; // Display the formatted price
                echo "</td>";
                echo "<td>{$created}</td>";

                echo "<td>";
                // read one record
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this link on the next part of this post
                echo "<a href='product_update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this link on the next part of this post
                echo "<a href='#' onclick='delete_user({$id});' class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";
        } else {
            // if no records found
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>
    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->

</body>

</html>
