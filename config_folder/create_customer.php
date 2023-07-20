<!DOCTYPE html>
<html>

<head>
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
            include 'database.php';
            try {
                // insert query
                $query = "INSERT INTO customer SET username=:username,
                password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, day=:day, month=:month, year=:year, account_status=:account_status";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // posted values
                $username = $_POST['Username'];
                $password = $_POST['password'];
                $first_name = $_POST['FirstName'];
                $last_name = $_POST['LastName'];
                $gender = $_POST['gender'];
                $day = $_POST['DateofBirth'];
                $month = $_POST['DateofBirth'];
                $year = $_POST['DateofBirth'];

                // validate input values
                $valid = true;
                $error_messages = [];

                if (empty($username)) {
                    $error_messages[] = "Please enter your username.";
                    $valid = false;
                }
                if (empty($password)) {
                    $error_messages[] = "Please enter your password.";
                    $valid = false;
                }
                if (empty($first_name)) {
                    $error_messages[] = "Please enter your first name.";
                    $valid = false;
                }
                if (empty($last_name)) {
                    $error_messages[] = "Please enter your last name.";
                    $valid = false;
                }
                if (empty($gender)) {
                    $error_messages[] = "Please select your gender.";
                    $valid = false;
                }
                if (empty($day) || empty($month) || empty($year)) {
                    $error_messages[] = "Please select a valid date of birth.";
                    $valid = false;
                }

                if ($valid) {
                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':first_name', $first_name);
                    $stmt->bindParam(':last_name', $last_name);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':day', $day);
                    $stmt->bindParam(':month', $month);
                    $stmt->bindParam(':year', $year);
                    $stmt->bindParam(':account_status', $account_status);

                    // Set the account status to a default value
                    $account_status = 'Active';

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<p class='success-message'>Record was saved.</p>";
                    } else {
                        echo "<p class='error-message'>Unable to save record.</p>";
                    }
                } else {
                    foreach ($error_messages as $error) {
                        echo "<p class='error-message'>$error</p>";
                    }
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
                    <td><input type="text" name="Username"></td>
                </tr>

                <tr>
                    <td>Password:</td>
                    <td>
                        <input type="password" id="password" name="password"
                            pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$">
                    </td>
                </tr>

                <tr>
                    <td>First Name:</td>
                    <td><input type="text" name="FirstName"></td>
                </tr>

                <tr>
                    <td>Last Name:</td>
                    <td><input type="text" name="LastName"></td>
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
                    <td><input type="submit" value="Submit" /></td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>