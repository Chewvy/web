<?php
session_start();
$_SESSION;
?>

<!DOCTYPE HTML>

<html>

<head>
<title>Category Read One</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script></head>

<?php
include 'navbar.php';
?>

<body>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Category</h1>
        </div>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['categoryID']) ? $_GET['categoryID'] : die('ERROR: Record ID not found.');

        // include database connection
        include 'config_folder/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT categoryID, category_name, description FROM product_category WHERE categoryID = ?";
            $stmt = $con->prepare($query);

            // delete message prompt will be here
            $action = isset($_GET['action']) ?
            $_GET['action'] : "";

            // if it was redirected from delete.php

            if($action=='deleted'){
            echo "<div class='alert alert-success'>Record was deleted.</div>";
            }

            if ($action == 'UnableDelete') {
                echo "<div class='alert alert-danger'>Unable to delete the category because it has products associated with it.</div>";
            }
            
            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $categoryID = $row['categoryID'];
            $category_name = $row['category_name'];
            $description = $row['description'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!--we have our HTML table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Category ID</td>
                <td><?php echo htmlspecialchars($categoryID, ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <td>Category Name</td>
                <td><?php echo htmlspecialchars($category_name, ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                <a href='category_update.php?categoryID=<?php echo $categoryID; ?>' class='btn btn-info' style='margin-right: 0.3em;'>Edit</a>
                <a href='category_index.php?categoryID=<?php echo $categoryID; ?>' class='btn btn-primary' style='margin-right: 0.3em;'>Back to read Categories</a>
                <a href='#' onclick='delete_user({$categoryID});' class='btn btn-danger'>Delete</a>
                </td>
            </tr>
        </table>

    </div> <!-- end .container -->
   <!-- Latest compiled and minified Bootstrap 5 JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

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
</body>

</html>
