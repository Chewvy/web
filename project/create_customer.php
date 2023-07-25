<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS (Apply your
Bootstrap here -->
    <style>
        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }
    </style>
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="header">
            <h1>Create Customer</h1>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // include database connection
            include 'config_folder/database.php';
            try {
                // insert query
                $query = "INSERT INTO customer SET username=:username,
                password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, day=:day, month=:month, year=:year, account_status=:account_status";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // posted values
                $username = strip_tags($_POST['Username']);
                $password = strip_tags($_POST['password']);
                $first_name = strip_tags($_POST['FirstName']);
                $last_name = strip_tags($_POST['LastName']);
                $gender = strip_tags($_POST['gender']);
                $day = strip_tags($_POST['DateofBirth']);
                $month = strip_tags($_POST['DateofBirth']);
                $year = strip_tags($_POST['DateofBirth']);

                //bind parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':day', $day);
                $stmt->bindParam(':month', $month);
                $stmt->bindParam(':year', $year);
                $stmt->bindParam(':Account_status', $account_status);

                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);

                // validate input values
                $error_messages = [];
                $flag = true;

                if (empty($username)) {
                    $error_messages[] = "Please enter your username.";
                    $flag = false;
                } elseif (strlen($username) < 6) {
                    echo "<p style='color: red;'>Username must be at least 6 characters long.</p>";
                    $flag = false;
                } elseif (strtolower($username) == $username || strtoupper($username) == $username) {
                    echo "<p style='color: red;'>Username must have at least 1 capital and 1 small cap.</p>";
                    $flag = false;
                } else if (substr($username, -1) == '-' || substr($username, -1) == '_') {
                    echo "<p style='color: red;'>Last character cannot be a symbol.</p>";
                    $flag = false;
                }

                if (empty($password)) {
                    $error_messages[] = "Please enter your password.";
                    $flag = false;
                } elseif (strlen($password) < 8) {
                    echo "<p style='color: red;'>Password must be at least 8 characters long.</p>";
                    $flag = false;
                } elseif (strtolower($password) == $password || strtoupper($password) == $password) {
                    echo "<p style='color: red;'>Password must have at least 1 capital and 1 small letter.</p>";
                    $flag = false;
                } elseif (strpbrk($password, '0123456789') == false) {
                    echo "<p style='color: red;'>Password must contain at least one number.</p>";
                    $flag = false;
                } elseif (strpbrk($password, '+$()%@#') == true) {
                    echo "<p style='color: red;'>Password must not contain +$()%@#.</p>";
                    $flag = false;
                }


                if (empty($first_name)) {
                    $error_messages[] = "Please enter your first name.";
                    $flag = false;
                }
                if (empty($last_name)) {
                    $error_messages[] = "Please enter your last name.";
                    $flag = false;
                }
                if (empty($gender)) {
                    $error_messages[] = "Please select your gender.";
                    $flag = false;
                }
                if (empty($day) || empty($month) || empty($year)) {
                    $error_messages[] = "Please select a valid date of birth.";
                    $valid = false;
                }
                if (empty($account_status)) {
                    $error_message[] = "Please select an account_status.";
                    $flag = false;
                }
                // Execute the query
                if ($flag == true && $stmt->execute()) {
                    echo "<p class='success-message'>Record was saved.</p>";
                } else {
                    echo "<p class='error-message'>Unable to save record.</p>";
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username:</td>
                    <td><input type="text" name="Username" class='form-control'></td>
                </tr>

                <tr>
                    <td>Password:</td>
                    <td>
                        <input type="password" id="password" name="password" class='form-control'>
                    </td>
                </tr>


                <tr>
                    <td>First Name:</td>
                    <td><input type="text" name="FirstName" class='form-control'></td>
                </tr>

                <tr>
                    <td>Last Name:</td>
                    <td><input type="text" name="LastName" class='form-control'></td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td>
                        <input type="radio" id="female" name="gender" value="female">
                        <label for="female">Female</label>

                        <input type="radio" id="male" name="gender" value="male">
                        <label for="male">Male</label>

                        <input type="radio" id="others" name="gender" value="others">
                        <label for="others">Others</label>
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth:</td>
                    <td>
                        <select id="day" name="DateofBirth">
                            <option value="">Day</option>
                            <?php
                            for ($i_day = 1; $i_day <= 31; $i_day++) {
                                echo '<option value="' . $i_day . '">' . $i_day . '</option>' . "\n";
                            }
                            ?>
                        </select>

                        <select id="month" name="DateofBirth">
                            <option value="">Month</option>
                            <?php
                            for ($i_month = 1; $i_month <= 12; $i_month++) {
                                echo '<option value="' . $i_month . '">' . $i_month . '</option>' . "\n";
                            }
                            ?>
                        </select>

                        <select id="year" name="DateofBirth">
                            <option value="">Year</option>
                            <?php
                            $year_start = 1940;
                            $year_end = date('Y');

                            for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
                                echo '<option value="' . $i_year . '">' . $i_year . '</option>' . "\n";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Account Status:</td>
                    <td>
                        <input type="radio" id="active" name="active" value="active">
                        <label for="Active">Active</label>

                        <input type="radio" id="inactive" name="inactive" value="inactive">
                        <label for="Inactive">Inactive</label>

                        <input type="radio" id="pending" name="pending" value="pending">
                        <label for="Pending">Pending</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='database.php' class='btn btn-danger'>Back to read products</a>
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