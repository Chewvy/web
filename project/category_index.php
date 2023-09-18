<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Products - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<?php
include 'navbar.php';
?>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Categories</h1>
        </div>
        <div class="d-flex justify-content-between mb-4">
            <a href='product_category.php' class='btn btn-primary m-b-1em'>Create New Category</a>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
                <input type="search" id="search" name="search" class="form-control me-2" />
                <input type="submit" value="Search" class='btn btn-warning' />
            </form>
        </div>
        <?php
        // include database connection
        include 'config_folder/database.php';
        try {

            $query = "SELECT categoryID, category_name, description FROM product_category ORDER BY categoryID DESC";

            $action = isset($_GET['action']) ? $_GET['action'] : "";
            // if it was redirected from delete.php
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }
            if ($action == 'UnableDelete') {
                echo "<div class='alert alert-danger'>Unable to delete the category because it has products associated with it.</div>";
            }

            if (isset($_GET['search'])) {
                $search = $_GET['search'];
    
                if (!empty($search)) {
                    $query = "SELECT categoryID, category_name, description
                              FROM product_category 
                              WHERE 
                              categoryID LIKE '%$search%' OR
                              category_name LIKE '%$search%' 
                              ORDER BY categoryID ASC";
                } else {
                    echo "<div class='alert alert-danger'>Please fill in keywords to search.</div>";
                }
                
            }

            $stmt = $con->prepare($query);
            $stmt->execute();

            // this is how to get the number of rows returned
            $num = $stmt->rowCount();

            // check if more than 0 records found
            if ($num > 0) {
                echo "<table class='table table-hover table-bordered'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Category Name</th>";
                echo "<th colspan='2'>Action</th>"; // Add colspan here
                echo "</tr>";
                echo "</thead>";

                // Table body
                echo "<tbody>";

                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<tr>";
                    echo "<td>{$categoryID}</td>";
                    echo "<td>{$category_name}</td>";
                    echo "<td>";
                    echo "<a href='category_read_one.php?categoryID={$categoryID}' class='btn btn-info' style='margin-right: 0.3em;'>Read</a>";
                    echo "<a href='category_update.php?categoryID={$categoryID}' class='btn btn-primary' style='margin-right: 0.3em;'>Edit</a>";
                    echo "<a href='#' onclick='delete_user({$categoryID});' class='btn btn-danger'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }

                // End table body
                echo "</tbody>";

                // End table
                echo "</table>";

                echo "</div>";
            } else if (empty($search) && $_SERVER["REQUEST_METHOD"] === "GET" && $action !== 'deleted') {
                // If the form was submitted without a search query, and it was a GET request, show an error message
                echo "<div class='alert alert-danger'>No records found.</div>";
            }
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>

        <!-- confirm delete record will be here -->
        <script type='text/javascript'>

            // confirm record deletion

            function delete_user(id) {
                var answer = confirm('Are you sure?');

                if (answer) {
                    // if user clicked ok,
                    // pass the id to delete.php and execute the delete query
                    window.location = 'product_delete.php?id=' + id;
                }
            }

        </script>
    </div>

</body>

</html>
