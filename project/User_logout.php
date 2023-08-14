<?php
session_start();

// Destroy the session and unset session variables
session_unset();
session_destroy();
?>

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
            <h1>Logout</h1>
        </div>
        <p>Are you sure you want to logout?</p>
        <a class="btn btn-outline-danger" href="User_login.php">Yes</a>
        <a class="btn btn-outline-primary" href="dashboard.php">No</a>
    </div>
</body>

</html>