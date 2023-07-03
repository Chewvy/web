<!DOCTYPE html>
<html>

<body>

    <?php
    $mark = 30;
    if (is_numeric($mark)) {

        switch ($mark) {
            case 100:
                echo "Well Done";
                break;
            case $mark >= 60;
                echo "pass";
                break;
            case $mark < 60 && $mark <= 0;
                echo "fail";
                break;
            default:
                echo "invalid";
        }
    }

    ?>

</body>

</html>