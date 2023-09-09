<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
<title>PDO - Read Order Details - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script></head>


<body>
    <?php
    include 'navbar.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Read Customers</h1>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="create_customer.php" class="btn btn-primary m-b-1em">Create New Customer</a>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
                <input type="search" id="search" name="search" class="form-control me-2" />
                <input type="submit" value="Search" class="btn btn-warning" />
            </form>
        </div>


        <?php
        // include database connection
        include 'config_folder/database.php';

        $query = "SELECT customer_id, username, image, email, first_name, last_name, gender, DOB, registration_date_and_time, account_status FROM customer
            ORDER BY customer_id ASC";

$search = isset($_GET['search']) ? $_GET['search'] : "";

if (!empty($search)) {
    $query = "SELECT customer_id, image, username, email, first_name, last_name, gender, DOB, registration_date_and_time, account_status FROM customer WHERE 
    username LIKE '%$search%' OR
    first_name LIKE '%$search%' OR
    last_name LIKE '%$search%' OR
    customer_id LIKE '%$search%'
    ORDER BY username ASC";
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

        // this is how to get number of rows returned
        $num = $stmt->rowCount();


        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here >

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Username</th>";
            echo "<th>Email</th>";
            echo "<th>First Name</th>";
            echo "<th>Last Name</th>";
            echo "<th>Gender</th>";
            echo "<th>Date of Birth</th>";
            echo "<th>Registration Date and Time</th>";
            echo "<th>Account Status</th>";
            echo "<th>Profile Picture</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$customer_id}</td>";
                echo "<td>{$username}</td>";
                echo "<td>{$email}</td>";
                echo "<td>{$first_name}</td>";
                echo "<td>{$last_name}</td>";
                echo "<td>{$gender}</td>";
                echo "<td>{$DOB}</td>";
                echo "<td>{$registration_date_and_time}</td>";
                echo "<td>{$account_status}</td>";
                echo "<td><img src='image/" . (!empty($image) ? htmlspecialchars($image, ENT_QUOTES) : 'default.jpg') . "' alt='{$username}' class='img-thumbnail' style='max-width: 100px; max-height: 100px;'></td>";                echo "<td>";
                // read one record
                echo "<a href='customer_read_one.php?customer_id={$customer_id}' class='btn btn-info' style='margin-right: 1em;'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='customer_update.php?customer_id={$customer_id}' class='btn btn-primary' style='margin-right: 1em;'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='customer_delete.php?customer_id={$customer_id}' class='btn btn-danger'>Delete</a>";

                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>
    </div> <!-- end .container -->

    <!-- Latest compiled and minified Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
    // Function to handle the delete button click
    function delete_user(customer_id) {
    var answer = confirm('Are you sure?');

    if (answer) {
        // If the user clicked OK, prevent the default link behavior
        event.preventDefault();

        // Pass the customer_id to delete.php and execute the delete query
        window.location = 'customer_delete.php?customer_id=' + customer_id;

        return false; // Prevent further event propagation
    }
}
</script>

</body>

</html>