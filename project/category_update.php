<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Update Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
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

<?php
include 'navbar.php';

// Initialize variables for category_name and description
$categoryID = null;
$category_name = "";
$description = "";

// Check if categoryID is provided in the URL
if (isset($_GET['categoryID'])) {
    $categoryID = $_GET['categoryID'];

    // include database connection
    include 'config_folder/database.php';

    // read current record's data
    try {
        // prepare select query
        $query = "SELECT categoryID, category_name, description FROM product_category WHERE categoryID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $categoryID);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // values to fill up our form
        $category_name = $row['category_name'];
        $description = $row['description'];

    } catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // posted values
        $category_name = strip_tags($_POST['category_name']);
        $description = strip_tags($_POST['description']);

        // include database connection
        include 'config_folder/database.php';

        // Update query
        $query = "UPDATE product_category SET category_name=:category_name, description=:description WHERE categoryID=:categoryID";
        $stmt = $con->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categoryID', $categoryID);

        // Validate form input
        $flag = true;

        if (empty($category_name)) {
            echo "<div class='alert alert-danger'>Please enter a category name.</div>";
            $flag = false;
        }

        if (empty($description)) {
            echo "<div class='alert alert-danger'>Please enter a description.</div>";
            $flag = false;
        }

        // Execute the query if form is valid
        if ($flag === true) {
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

<body>
    <div class="container">
        <div class="page-header">
            <h1>Update Category</h1>
        </div>

        <!-- Display the existing data and allow updates -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?categoryID={$categoryID}"); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Category Name</td>
                    <td><input type='text' name='category_name' value="<?php echo htmlspecialchars($category_name, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><input type='text' name='description' value="<?php echo htmlspecialchars($description, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type='submit' value='Save Changes' class='btn btn-primary' /> <a href='category_index.php' class='btn btn-danger'>Back to read categories</a></td>
                </tr>
            </table>
        </form>
    </div>
    <!-- end .container -->
</body>

</html>
