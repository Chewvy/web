<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customers</h1>
        </div>

        <?php
        if ($_POST) {
            include 'config_folder/database.php';
            try {
                $query = "INSERT INTO customer SET username=:username, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, DOB=:DOB, account_status=:account_status, registration_date_and_time=:registration_date_and_time";
                $stmt = $con->prepare($query);

                $username = strip_tags(ucwords(strtolower($_POST['username'])));
                $password = strip_tags($_POST['password']);
                $confirm_password = strip_tags($_POST['confirm_password']);
                $first_name = strip_tags(ucwords(strtolower($_POST['first_name'])));
                $last_name = strip_tags(ucwords(strtolower($_POST['last_name'])));
                $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
                $DOB = $_POST['DOB'];
                $account_status = isset($_POST['account_status']) ? $_POST['account_status'] : "";

                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':DOB', $DOB);
                $stmt->bindParam(':account_status', $account_status);

                $registration_date_and_time = date('Y-m-d H:i:s');
                $stmt->bindParam(':registration_date_and_time', $registration_date_and_time);

                $flag = true;

                if (empty($username)) {
                    echo "<div class='alert alert-danger'>Please enter the username</div>";
                    $flag = false;
                } elseif (strlen($username) < 6) {
                    echo "<p style='color: red;'>Username must be at least 6 characters long.</p>";
                    $valid = false;
                }

                if (empty($password)) {
                    echo "<p style='color: red;'>Please enter your password.</p>";
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

                if (empty($confirm_password)) {
                    echo "<div class='alert alert-danger'>Please confirm your password</div>";
                    $flag = false;
                } else if ($password !== $confirm_password) {
                    echo "<div class='alert alert-danger'>Confirm password does not match with your password</div>";
                    $flag = false;
                }

                if (empty($first_name)) {
                    echo "<div class='alert alert-danger'>Please enter your first name</div>";
                    $flag = false;
                }

                if (empty($last_name)) {
                    echo "<div class='alert alert-danger'>Please enter your last name</div>";
                    $flag = false;
                }

                if (empty($gender)) {
                    echo "<div class='alert alert-danger'>Please select a gender</div>";
                    $flag = false;
                }

                if (empty($DOB)) {
                    echo "<div class='alert alert-danger'>Please select your date of birth</div>";
                    $flag = false;
                }

                if (empty($account_status)) {
                    echo "<div class='alert alert-danger'>Please select your account status</div>";
                    $flag = false;
                }

                if ($flag) {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
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
                    <td><input type="text" id="username" name="username" class='form-control'></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" id="password" name="password" class='form-control'></td>
                </tr>
                <tr>
                    <td>Confirm Password:</td>
                    <td><input type="password" id="confirm_password" name="confirm_password" class='form-control'></td>
                </tr>
                <tr>
                    <td>First Name:</td>
                    <td><input type="text" id="first_name" name="first_name" class='form-control'></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><input type="text" id="last_name" name="last_name" class='form-control'></td>
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
                    <td>Date of Birth:</td>
                    <td><input type='date' name='DOB' class='form-control'></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <input type="radio" class="account_status" name="account_status" value="active" <?php echo (isset($account_status) && $account_status === "active") ? "checked" : ""; ?>>
                        <label for="active">Active</label>

                        <input type="radio" class="account_status" name="account_status" value="inactive" <?php echo (isset($account_status) && $account_status === "inactive") ? "checked" : ""; ?>>
                        <label for="inactive">Inactive</label>

                        <input type="radio" class="account_status" name="account_status" value="pending" <?php echo (isset($account_status) && $account_status === "pending") ? "checked" : ""; ?>>
                        <label for="pending">Pending</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary'>
                        <a href='customer_index.php' class='btn btn-danger'>Back to read customer</a>
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