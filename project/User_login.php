<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Login - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Login</h1>
        </div>

        <?php
        include 'config_folder/database.php'; // Make sure the database connection is included
        

        if ($_POST) {
            try {
                $username = $_POST['username']; //use to check username in database and username that type by user
                $password = $_POST['password']; //use to check password in database and password that type by user
        
                $query = "SELECT * FROM customer WHERE username = ? AND password = ?"; //check for this 2 thing from the table that user typed in
                $stmt = $con->prepare($query);
                $stmt->bindParam(1, $username); //use to bind value
                $stmt->bindParam(2, $password); //use to bind value
        
                $flag = true;

                if (empty($username)) {
                    echo "<div class='alert alert-danger'>Please enter the username</div>";
                    $flag = false;
                } elseif (strlen($username) < 6) {
                    echo "<p style='color: red;'>Username must be at least 6 characters long.</p>";
                    $flag = false;
                }

                if (empty($password)) {
                    echo "<p style='color: red;'>Please enter your password.</p>";
                    $flag = false;
                } elseif (strlen($password) < 8) {
                    echo "<p style='color: red;'>Password must be at least 8 characters long.</p>";
                    $flag = false;
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

                if ($flag) {
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result) {
                        // Username exists in the database, so check the password.
                        if ($password == $result['password']) {
                            $account_status = $result['account_status'];
                            if ($account_status == 'active') {
                                echo "<div class='alert alert-success'>Successfully logged in!</div>";
                                header("Location: dashboard.php");
                            } elseif ($account_status == 'inactive') {
                                echo "<div class='alert alert-danger'>Your account is inactive.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Your account is pending approval.</div>";
                            }
                        } else {
                            // Incorrect password.
                            echo "<div class='alert alert-danger'>Incorrect password.</div>";
                        }
                    } else {
                        // Username not found in the database.
                        echo "<div class='alert alert-danger'>Username not found.</div>";
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
                <td></td>
                <td>
                    <input type='submit' value='Login' class='btn btn-primary'>
                </td>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>