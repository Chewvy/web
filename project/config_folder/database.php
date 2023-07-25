<?php

// used to connect to the database
$host = "localhost";
$db_name = "chewvy";
$username = "Chewvy";
$password = "hyiTV0A9ExGmVIaS";
try {
    $con = new
        PDO /*phpdatabaseobject*/(
        "mysql:host={$host};dbname={$db_name}",
        $username,
        $password
    );
}
// show error
catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
?>