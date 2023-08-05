<?php

// used to connect to the database
$host = "localhost";
$db_name = "chewvy"; // corrected variable name here
$username = "Chewvy";
$password = "hyiTV0A9ExGmVIaS";
try {
    $con = new PDO("mysql:host=$host;dbname=$db_name", $username, $password); // corrected variable name here
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>