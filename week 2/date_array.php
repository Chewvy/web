<!DOCTYPE html>
<html>
<body>


<select id="month" name="month">
    <option value="">Month</option>
    <?php
    $months = array(
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    );// create an array called $months to match with the respective number with name
    foreach ($months as $monthNumber => $monthName) {
        echo '<option value="' . $monthNumber . '">' . $monthName . '</option>' . "\n";
    }//$monthNumber is a visible text
    ?>
</select>

</body>
</html>