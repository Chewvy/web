<!DOCTYPE html>
<html>

<body> 
    
<?php
for ($i = 10; $i >= 1; $i--) {
    for ($star = 1; $star <= $i; $star++) {
        echo "*";
    }//make i=10 and slowly minus until 1, j means * symbol
    echo "<br>";//make everyline have a break
}
?>

</body>
</html>