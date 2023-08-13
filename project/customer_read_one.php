<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS -->
</head>

<?php
include 'navbar.php';
?>

<body>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Customer</h1>
        </div>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config_folder/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT username, password, first_name, last_name,gender,DOB,account_status,registration_date_and_time FROM customer WHERE username = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

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
            $password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $DOB = $row['DOB'];
            $account_status = $row['account_status'];
            $registration_date_and_time = $row['registration_date_and_time'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>


        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>username</td>
                <td>
                    <?php echo htmlspecialchars($username, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>password</td>
                <td>
                    <?php echo htmlspecialchars($password, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>first_name</td>
                <td>
                    <?php echo htmlspecialchars($first_name, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>last_name</td>
                <td>
                    <?php echo htmlspecialchars($last_name, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>gender</td>
                <td>
                    <?php echo htmlspecialchars($gender, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>DOB</td>
                <td>
                    <?php echo htmlspecialchars($DOB, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>account_status</td>
                <td>
                    <?php echo htmlspecialchars($account_status, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>registration_date_and_time</td>
                <td>
                    <?php echo htmlspecialchars($registration_date_and_time, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='customer_index.php' class='btn btn-danger'>Back to read customer</a>
                </td>
            </tr>
        </table>

    </div> <!-- end .container -->

</body>

</html>