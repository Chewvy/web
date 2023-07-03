<!DOCTYPE html>
<html>
<body>

<form method="get">
    Number: <input type="text" name="number"><br>
    <input type="submit">
</form>

<?php
if ($_GET) {
    $number = $_GET["number"];

    if (empty($number) || !is_numeric($number)) {
        echo "<p style='color: red;'>Please fill in a number.</p>";
    } else {
        $number = intval($number);//return the integer value of a variable
        $sum = 0;

        for ($i = $number; $i >= 1; $i--) {
            $sum += $i;
        }

        echo "<p>" . implode("+", range(1, $number)) . " = " . $sum . "</p>";
    }//implode =  returns a string from the elements of an array.
}
?>
</body>
</html>