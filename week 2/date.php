<!DOCTYPE html>
<html>

<head>
  <style>
    #day, #day option {
      color: blue;
    }
    #month, #month option{
     color : yellow;
    }
    #year, #year option{
     color : red;
    }
  </style>
</head>
<body> 

  <select id="day" name="day">//same name doesn't matter
    <option value="">Day</option>
    <?php
    for ($i_day = 1; $i_day <= 31; $i_day++) { 
        $selected = $selected_day == $i_day ? ' selected' : '';
        echo '<option value="'.$i_day.'"'.$selected.'>'.$i_day.'</option>'."\n";
    }
    ?>
  </select>
 
  <select id="month" name="month">
    <option value="">Month</option>
    <?php
    $selected_month = date('F');
    for ($i_month = 1; $i_month <= 12; $i_month++) { 
        $selected = $selected_month == $i_month ? ' selected' : '';
        echo '<option value="'.$i_month.'"'.$selected.'>'.$i_month.'</option>'."\n";
    }
    ?>
  </select>

  <select id="year" name="year">
    <option value="">Year</option>
    <?php 
    $year_start  = 1940;
    $year_end = date('Y');

    for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
        $selected = $selected_year == $i_year ? ' selected' : '';
        echo '<option value="'.$i_year.'"'.$selected.'>'.$i_year.'</option>'."\n";
    }
    ?>
  </select>

</body>
</html>