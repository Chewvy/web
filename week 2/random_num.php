<!DOCTYPE html>
<html>
    <head>
        <style>
                #line_1 {color:green;}
                #line_2{color:blue;}
                #line_3{color:red;}
</style>
</head>
<body>

<?php
$x=rand(100,200);//random number between 100 to 200
$y=rand(100,200);//random number between 100 to 200
$sum=$x+$y;//the sum of x number and y number
$multiply=$x*$y;//the multiplication of x number and y number
?>

<?php
echo ("<div id=line_1><i>$x</i></div>");//write out the x number
echo ("<div id=line_2><i>$y</i></div>");//write out the y number
echo ("<div id=line_3><b>$sum<b></div>");//write out the sum of x number and y number
echo ("<div id=line_4><b><i>$multiply<i><b></div>");//write out the multiplication of x number and y number
?>

</body>
</html>