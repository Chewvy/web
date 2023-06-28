<!DOCTYPE html>
<html>
    <head>
        <style>
            
             span {color:red;}
            
            </style>
</head>
<body>

<?php
echo "My first <span><b>PHP</b></span>script!";
?>

<?php
echo"<br>";
echo date("F j , Y  (l)"). "<br>";
?>

<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
echo date("h:i:s A"). "<br>";
?>

</body>
</html>