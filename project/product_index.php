<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
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
        $query = "SELECT id, name, categoryID, description, price FROM products ORDER BY id ASC";

        // delete message prompt will be here
        
        // select data from products and join with product_category on categoryID
        if ($_GET) { //拿到value先
            $search = $_GET['search'];

            if (empty($search)) { //才知道里面是不是空的，所以才在这里check
                echo "<div class='alert alert-danger'>Search by Keyword</div>";
            }

            $query = "SELECT id, name, categoryID, description, price FROM products WHERE 
            name LIKE '%$search%'
            ORDER BY id ASC";
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
            echo "<th>Description</th>";
            echo "<th>Category</th>"; // Add this line to include the Category column
            echo "<th>Price</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$description}</td>";
                echo "<td>{$categoryID}</td>"; // Add this line to display the Category value
                echo "<td>{$price}</td>";
                echo "<td>";
                // read one record
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this link on the next part of this post
                echo "<a href='update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this link on the next part of this post
                echo "<a href='#' onclick='delete_user({$id});' class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>
    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->

</body>

</html>