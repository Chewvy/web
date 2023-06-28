<!DOCTYPE html>
<html>

<head>
  <style>
    .bold{
        font-weight: bold;
    }
    .gold {
      color: #B3965E;
    }
    
    .blue {
      color: blue;
    }
  </style>
</head>

<body>

  <?php
  echo"<div class='bold'>"; 
  $currentTimestamp = time();//represents the current date and time.
  $month = date('M', $currentTimestamp);//format the current timestamp,using the first 3 letters represent the month
  $dayOfWeek = date('D', $currentTimestamp);//day
  $formattedDate = '<span class="gold">' . $month . '</span> ' . date('d, Y', $currentTimestamp) . ' (<span class="blue">' . $dayOfWeek . '</span>)';
  echo $formattedDate . "<br>";
  echo "</div>";
  ?>

  <?php
  date_default_timezone_set("Asia/Kuala_Lumpur");
  echo date("G:i:s ") . "<br>";
  ?>

</body>
</html>

