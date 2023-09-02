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
            <h1>Read Category</h1>
        </div>
     
        <?php
// include database connection
include 'config_folder/database.php';

// delete message prompt will be here

// select all data
$query = "SELECT categoryID, category_name, description FROM product_category ORDER BY categoryID ASC";

try {
    $stmt = $con->prepare($query);
    $stmt->execute();

    // this is how to get the number of rows returned
    $num = $stmt->rowCount();

    // link to create record form
    echo "<a href='product_category.php' class='btn btn-primary m-b-1em'>Create New Category</a>";

    // check if more than 0 records found
    if ($num > 0) {
        echo "<table class='table table-hover table-responsive table-bordered'>";//start table

        // creating our table heading
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Category Name</th>";
        echo "<th>Description</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);
            // creating a new table row per record
            echo "<tr>";
            echo "<td>{$categoryID}</td>";
            echo "<td>{$category_name}</td>";
            echo "<td>{$description}</td>";
            echo "<td>";
            // read one record
            echo "<a href='product_read_one.php?id={$categoryID}' class='btn btn-info m-r-1em'>Read</a>";
            // we will use these links on the next part of this post
            echo "<a href='update.php?id={$categoryID}' class='btn btn-primary m-r-1em'>Edit</a>";
            // we will use these links on the next part of this post
            echo "<a href='#' onclick='delete_user({$categoryID});'  class='btn btn-danger'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }

        // end table
        echo "</table>";
    } else {
        echo "<div class='alert alert-danger'>No records found.</div>";
    }
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
