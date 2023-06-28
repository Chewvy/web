<!DOCTYPE html>
<html>
<body>

<?php
$totalSum = 0; // Variable to store the total sum

echo "<div class='container'>";
echo "<div class='number-list'>";//This element will contain the list of numbers.

for ($i = 1; $i <= 100; $i++) {//list all the numbers from 1 to 100
    $totalSum += $i; // Add the current number to the total sum
    $numberDisplay = $i;

    if ($i % 2 === 0) {//% 2 (modulo) indicates even number
        $numberDisplay = "<strong>$i</strong>"; // Highlight even numbers in bold
    }

    if ($i > 1) {
        echo " + ";
    }//i greater than 1 ,then add + sign//

    echo $numberDisplay;//f the number is even, it will be displayed in bold due to the previous line of code.
}

echo "</div>";//closing number list
echo "<h4>Total Sum: $totalSum</h4>";//output total sum
echo "</div>";//closing container
?>

</body>
</html>