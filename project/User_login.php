<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Logout - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<?php
include 'navbar.php';
?>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Login</h1>
        </div>


        <?php
        include 'config_folder/database.php'; // Make sure the database connection is included
        
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $email = $_POST['email']; // Corrected variable name
                $password = $_POST['password'];

                $query = "SELECT * FROM customer WHERE username = ? AND password = ? AND email = ?";
                $stmt = $con->prepare($query);
                $stmt->bindParam(1, $username);
                $stmt->bindParam(2, $password);
                $stmt->bindParam(3, $email);

                $flag = true;

                if (empty($username)) {
                    echo "<div class='alert alert-danger'>Please enter the username</div>";
                    $flag = false;
                } elseif (strlen($username) < 6) {
                    echo "<div class='alert alert-danger'>Username must be at least 6 characters long.</div>";
                    $flag = false;
                }

                if (empty($password)) {
                    echo "<div class='alert alert-danger'>Please enter your password.</div>";
                    $flag = false;
                } elseif (strlen($password) < 8) {
                    echo "<div class='alert alert-danger'>Password must be at least 8 characters long.</div>";
                    $flag = false;
                } elseif (strtolower($password) == $password || strtoupper($password) == $password) {
                    echo "<div class='alert alert-danger'>Password must have at least 1 capital and 1 small letter.</div>";
                    $flag = false;
                } elseif (strpbrk($password, '0123456789') == false) {
                    echo "<div class='alert alert-danger'>Password must contain at least one number.</div>";
                    $flag = false;
                } elseif (strpbrk($password, '+$()%@#') == true) {
                    echo "<div class='alert alert-danger'>Password must not contain +$()%@#.</div>";
                    $flag = false;
                }

                if (empty($email)) {
                    echo "<div class='alert alert-danger'>Please enter your email.</div>";
                    $flag = false;
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<div class='alert alert-danger'>Invalid email format.</div>";
                    $flag = false;
                }

                if ($flag) {
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result) {
                        $account_status = $result['account_status'];
                        if ($account_status == 'active') {
                            echo "<div class='alert alert-success'>Successfully logged in!</div>";
                            header("Location: dashboard.php");
                        } elseif ($account_status == 'inactive') {
                            echo "<div class='alert alert-danger'>Your account is inactive.</div>";
                        } elseif ($account_status == 'pending') {
                            echo "<div class='alert alert-warning'>Your account is pending approval.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Incorrect password.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Username not found.</div>";
                    }
                }
            }
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username:</td>
                    <td><input type="text" id="username" name="username" class='form-control'></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="text" id="email" name="email" class='form-control'></td>
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
    <?php
    if (!empty($warningMessage)) {
        echo "<div class='alert alert-warning'>$warningMessage</div>";
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>