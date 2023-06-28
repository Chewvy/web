<!DOCTYPE html>
<html>

<body> 

<!--DAY-->
  <select id="day" name="day"> //dropdown select element
    <option value="">Day</option>//empty value attribute ,serves as a default or placeholder value.
<?php
    $selected_day = date('d'); //current day
    for ($i_day = 1; $i_day <= 31; $i_day++) { 
        $selected = $selected_day == $i_day ? ' selected' : '';
        echo '<option value="'.$i_day.'"'.$selected.'>'.$i_day.'</option>'."\n";
    } //everytime plus one(day is 1+1)
?>
</select>
 
<!--MONTH-->
<select id="month" name="month">
    <option value="">Month</option>
    <?php
    $selected_month = date('n'); // current month (numeric representation)
    for ($i_month = 1; $i_month <= 12; $i_month++) { 
        $selected = $selected_month == $i_month ? ' selected' : '';
        echo '<option value="'.$i_month.'"'.$selected.'>'.$i_month.'</option>'."\n";
    }
    ?>
</select>

<!--YEAR-->
<select id="year" name="year">
    <option value="">Year</option>
<?php 
    $year_start  = 1940;
    $year_end = date('Y'); // current Year
    $selected_year = date('Y'); // user date of birth year

    for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
        $selected = $selected_year == $i_year ? ' selected' : '';
        echo '<option value="'.$i_year.'"'.$selected.'>'.$i_year.'</option>'."\n";
    }
?>
</select>

</body>
</html>