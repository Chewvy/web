<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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

<body>
    <div class="container">
        <div class="page-header">
            <h1>Update Customers</h1>
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
            $query = "SELECT customer_id,username,email, password, first_name, last_name, gender,account_status,registration_date_and_time  FROM customer WHERE customer_id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $customer_id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $customer_id = $row['customer_id'];
            $username = $row['username'];
            $email = $row['email'];
            $password = $row['password'];
            $new_password = $row['password'];
            $confirm_password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $account_status = $row['account_status'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        // check if form was submitted
        if ($_POST) {
            try {
                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                $query = "UPDATE customer
                  SET customer_id=:customer_id, username=:username, email=:email, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender,account_status=:account_status WHERE customer_id=:customer_id";

                // prepare query for excecution
                $stmt = $con->prepare($query);

                // posted values
                $password = htmlspecialchars(strip_tags($_POST['old_password']));
                $new_password = htmlspecialchars(strip_tags($_POST['new_password']));
                $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));
                $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
                $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $account_status = htmlspecialchars(strip_tags($_POST['account_status']));

                $flag = true;

                if (empty($password) && empty($new_password) && empty($confirm_password)) {
                    // No password change requested
                    $flag = true;
                } else if (!empty($new_password) || !empty($confirm_password)) {
                    // At least one password field is filled, indicating a password change request
                    if ($password !== $new_password) {
                        // New password is different from old password
                        if ($new_password == $confirm_password) {
                            // New password matches the confirmed password
                            // Now you can proceed with password change logic
                            $flag = true;
                        } else {
                            echo "<div class='alert alert-danger'>New password and confirm password do not match.</div>";
                            $flag = false;
                        }
                    } else {
                        echo "<div class='alert alert-danger'>New password must not be the same as the old password.</div>";
                        $flag = false;
                    }
                } else {
                    echo "<div class='alert alert-danger'>If you want to change the password, please fill in all password fields.</div>";
                    $flag = false;
                }

                // bind the parameters
                $stmt->bindParam(':customer_id', $customer_id);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':account_status', $account_status);

                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>


        <!--we have our html form here where new record information can be updated-->
        <div class="row">
            <div class="col-md-12">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?' . $_SERVER['QUERY_STRING']); ?>"
                    method="post">

                    <div class="table-responsive">
                        <table class='table table-hover table-responsive table-bordered'>
                            <tr>
                                <td>Username</td>
                                <td><input type='text' name='username'
                                        value="<?php echo htmlspecialchars($username, ENT_QUOTES); ?>"
                                        class='form-control' />
                                </td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><input type='text' name='email'
                                        value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>"
                                        class='form-control' />
                                </td>
                            </tr>
                            <tr>
                                <td>Old Password</td>
                                <td><input type='password' name='old_password' class='form-control' /></td>
                            </tr>
                            <tr>
                                <td>New Password</td>
                                <td><input type='password' name='new_password' class='form-control' /></td>
                            </tr>
                            <tr>
                                <td>Confirm Password</td>
                                <td><input type='password' name='confirm_password' class='form-control' /></td>
                            </tr>
                            <tr>
                                <td>First Name</td>
                                <td><input type='text' name='first_name'
                                        value="<?php echo htmlspecialchars($first_name, ENT_QUOTES); ?>"
                                        class='form-control' />
                                </td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td><input type='text' name='last_name'
                                        value="<?php echo htmlspecialchars($last_name, ENT_QUOTES); ?>"
                                        class='form-control' />
                                </td>
                            </tr>
                            <tr>
                                <td>Gender:</td>
                                <td>
                                    <input type="radio" class="gender" name="gender" value="female" <?php echo (isset($gender) && $gender === "female") ? "checked" : ""; ?>>
                                    <label for="female">Female</label>

                                    <input type="radio" class="gender" name="gender" value="male" <?php echo (isset($gender) && $gender === "male") ? "checked" : ""; ?>>
                                    <label for="male">Male</label>

                                    <input type="radio" class="gender" name="gender" value="others" <?php echo (isset($gender) && $gender === "others") ? "checked" : ""; ?>>
                                    <label for="others">Others</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Account Status</td>
                                <td>
                                    <input type="radio" class="account_status" name="account_status" value="active"
                                        <?php echo (isset($account_status) && $account_status === "active") ? "checked" : ""; ?>>
                                    <label for="active">Active</label>

                                    <input type="radio" class="account_status" name="account_status" value="inactive"
                                        <?php echo (isset($account_status) && $account_status === "inactive") ? "checked" : ""; ?>>
                                    <label for="inactive">Inactive</label>

                                    <input type="radio" class="account_status" name="account_status" value="pending"
                                        <?php echo (isset($account_status) && $account_status === "pending") ? "checked" : ""; ?>>
                                    <label for="pending">Pending</label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                                    <a href='customer_index.php' class='btn btn-danger'>Back to read customer</a>
                                </td>
                            </tr>
                </form>
            </div>
        </div>
    </div>
</body>
<!-- end .container -->
</body>

</html>