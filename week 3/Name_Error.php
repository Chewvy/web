<!DOCTYPE html>
<html>
<body>

<form method="get">
    First Name: <input type="text" name="first_name"><br>
    Last Name: <input type="text" name="last_name"><br>
    <input type="submit">
</form>

<?php
if ($_GET) {
    $first_name = $_GET["first_name"];
    $last_name = $_GET["last_name"];

    if (empty($first_name) || empty($last_name)) {
        echo "<p style='color: red;'>Please enter your name.</p>";
    } else {
        $formatted_first_name = ucwords(strtolower($first_name));//modified version
        $formatted_last_name = ucwords(strtolower($last_name));
    }
    echo ucwords(strtolower($_GET["first_name" ]));
    echo ucwords(strtolower($_GET["last_name"]));
}
?>
</body>
</html>
