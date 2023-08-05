<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Category</title>
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
            <h1>Create Product Category</h1>
        </div>

        <?php

        if ($_POST) {
            // include database connection
            include 'config_folder/database.php';
            ;
            try {
                // insert query
                $query = "INSERT INTO product_category SET category_name=:category_name,description=:description";

                // prepare query for execution
                $stmt2 = $con->prepare($query);
                // posted values
                $category_name = strip_tags($_POST['category_name']);
                $description = strip_tags($_POST['description']);

                // bind the parameters
                $stmt2->bindParam(':category_name', $category_name);
                $stmt2->bindParam(':description', $description);

                // specify when this record was inserted to the database
        
                // Execute the query
                $flag = true;

                if (empty($category_name)) {
                    echo "<div class='alert alert-danger'>Please enter a category name.</div>";
                    $flag = false;
                }

                if (empty($description)) {
                    echo "<div class='alert alert-danger'>Please enter a description.</div>";
                    $flag = false;
                }

                if ($flag = true) {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
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
                    <td>Category Name</td>
                    <td><input type='text' name='category_name' class='form-control'
                            value="<?php echo isset($category_name) ? $category_name : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><input type='text' name='description' class='form-control'
                            value="<?php echo isset($description) ? $description : ''; ?>"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='categoy_index.php' class='btn btn-danger'>Back to read category</a>
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