<?php

// Check if the user is not logged in and not on the login page
if (!isset($_SESSION['username']) && basename($_SERVER['PHP_SELF']) !== 'User_login.php') {
    header("Location: Index.php");
    exit();
}
?>