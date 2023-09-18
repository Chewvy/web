<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
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
</head>

<body>

    <?php include 'navbar.php'; ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Customer</h1>
        </div>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config_folder/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT username, first_name, last_name, gender, DOB, account_status, image, registration_date_and_time FROM customer WHERE customer_id = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $customer_id);

            // delete message prompt will be here
            $action = isset($_GET['action']) ? $_GET['action'] : "";

            // if it was redirected from delete.php
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }

            if ($action == 'UnableDelete') {
                echo "<div class='alert alert-danger'>Unable to delete the product because it has orders associated with it.</div>";
            }

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // check if the row is found
            if (!$row) {
                die('ERROR: Record not found.');
            }

            // values to fill up our form
            $username = $row['username'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $DOB = $row['DOB'];
            $account_status = $row['account_status'];
            $registration_date_and_time = $row['registration_date_and_time'];
            $image = $row['image'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Customer ID</td>
                <td>
                    <?php echo htmlspecialchars($customer_id, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Username</td>
                <td>
                    <?php echo htmlspecialchars($username, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>First Name</td>
                <td>
                    <?php echo htmlspecialchars($first_name, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td>
                    <?php echo htmlspecialchars($last_name, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Gender</td>
                <td>
                    <?php echo htmlspecialchars($gender, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td>
                    <?php echo htmlspecialchars($DOB, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Account Status</td>
                <td>
                    <?php echo htmlspecialchars($account_status, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Registration Date & Time</td>
                <td>
                    <?php echo htmlspecialchars($registration_date_and_time, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Profile Picture</td>
                <td>
                    <?php
                    if (!empty($image)) {
                        echo "<img src='image/$image' alt='Product Image' style='max-width: 200px;' />";
                    } else {
                        echo "<img src='ProductComingSoon.jpg' alt='Product Coming Soon' style='max-width: 200px;' />";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='customer_update.php?customer_id=<?php echo $customer_id; ?>' class='btn btn-info' style='margin-right: 0.3em;'>Edit</a>
                    <a href='customer_index.php?customer_id=<?php echo $customer_id; ?>' class='btn btn-primary' style='margin-right: 0.3em;'>Back to read customers</a>
                    <a href='#' onclick='delete_user(<?php echo $customer_id; ?>)' class='btn btn-danger'>Delete</a>
                </td>
            </tr>
        </table>

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
