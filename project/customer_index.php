<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Customers - PHP CRUD Tutorial</title>
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
            <h1>Read Customers</h1>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="d-flex">
            <?php echo "<a href='create_customer.php' class='btn btn-primary m-b-1em'>Create New Customer</a>"; ?>
            <input type='search' name="search" class="ml-auto"> <!-- Use ml-auto to push to the right -->
            <input type='submit' value='Search' class='btn btn-primary m-r-1em'>

        </form>

        </form>

        <?php
        // include database connection
        include 'config_folder/database.php';

        $query = "SELECT username, email,password, first_name, last_name, gender, DOB, account_status,registration_date_and_time FROM customer ORDER BY username ASC";

        // delete message prompt will be here
        
        // select all data
        if ($_GET) {
            $search = $_GET['search'];

            if (empty($search)) { //才知道里面是不是空的，所以才在这里check
                echo "<div class='alert alert-danger'>Search by Keyword</div>";
            }

            $query = "SELECT username,email, password, first_name, last_name, gender, DOB, account_status,registration_date_and_time  FROM customer WHERE 
            username LIKE '%$search%';
            first_name LIKE '%$search%';
            last_name LIKE '%$search%';
            email LIKE '%$search%';
            ORDER BY username ASC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        // link to create record form
        

        // check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>";
            // creating our table heading
            echo "<tr>";
            echo "<th>Username</th>";
            echo "<th>Email</th>";
            echo "<th>Password</th>";
            echo "<th>First Name</th>";
            echo "<th>Last Name</th>";
            echo "<th>Gender</th>";
            echo "<th>Date of Birth</th>";
            echo "<th>Account Status</th>";
            echo "<th>Registration Date and Time</th>";
            echo "<th>Actions</th>";
            echo "</tr>";

            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$username}</td>";
                echo "<td>{$email}</td>";
                echo "<td>{$password}</td>";
                echo "<td>{$first_name}</td>";
                echo "<td>{$last_name}</td>";
                echo "<td>{$gender}</td>";
                echo "<td>{$DOB}</td>";
                echo "<td>{$account_status}</td>";
                echo "<td>{$registration_date_and_time}</td>";
                echo "<td>";
                // read one record
                echo "<a href='customer_read_one.php?id={$username}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on the next part of this post
                echo "<a href='update.php?id={$username}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on the next part of this post
                echo "<a href='#' onclick='delete_user(\"{$username}\");' class='btn btn-danger'>Delete</a>";
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

</body>

</html>