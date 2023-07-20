<<<<<<< HEAD


<!DOCTYPE html>
<html>
<body>

<form method="get">
    First_Num: <input type="number" name="first_num"><br>
    Second_Num: <input type="number" name="second_num"><br>
    <input type="submit">
</form>

<?php
if ($_GET) {
    $first_num = $_GET["first_num"];
    $second_num = $_GET["second_num"];

    if (empty($first_num) || empty($second_num)) {
        echo "<p style='color: red;'>Please enter both numbers.</p>";
    } else {
        $sum = $first_num + $second_num;

        echo "<p>Sum: " . $sum . "</p>";
    }
}
?>
</body>
</html>
=======
<!DOCTYPE html>
<html>
<body>

<form method="get">
    First_Num: <input type="number" name="first_num"><br>
    Second_Num: <input type="number" name="second_num"><br>
    <input type="submit">
</form>

<?php
if ($_GET) {
    $first_num = $_GET["first_num"];
    $second_num = $_GET["second_num"];

    if (empty($first_num) || empty($second_num)) {
        echo "<p style='color: red;'>Please enter both numbers.</p>";
    } else {
        $sum = $first_num + $second_num;

        echo "<p>Sum: " . $sum . "</p>";
    }
}
?>
</body>
</html>
>>>>>>> 461f7c67cbdee02dfe0c988905ee0fb78fd3201a
