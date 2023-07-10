<!DOCTYPE html>
<html>

<body>

    <form method="get">
        First Name: <input type="text" name="first_name"><br>
        Last Name: <input type="text" name="last_name"><br>

        <select id="day" name="day">
            <option value="">Day</option>
            <?php
            for ($i_day = 1; $i_day <= 31; $i_day++) {
                $selected = isset($_GET['day']) && $_GET['day'] == $i_day ? ' selected' : '';
                echo '<option value="' . $i_day . '"' . $selected . '>' . $i_day . '</option>' . "\n";
            }
            ?>
        </select>

        <select id="month" name="month">
            <option value="">Month</option>
            <?php
            $selected_month = date('n');
            for ($i_month = 1; $i_month <= 12; $i_month++) {
                $selected = isset($_GET['month']) && $_GET['month'] == $i_month ? ' selected' : '';
                echo '<option value="' . $i_month . '"' . $selected . '>' . $i_month . '</option>' . "\n";
            }
            ?>
        </select>

        <select id="year" name="year">
            <option value="">Year</option>
            <?php
            $year_start = 1900;
            $year_end = date('Y');

            for ($i_year = $year_end; $i_year >= $year_start; $i_year--) {
                $selected = isset($_GET['year']) && $_GET['year'] == $i_year ? ' selected' : '';
                echo '<option value="' . $i_year . '"' . $selected . '>' . $i_year . '</option>' . "\n";
            }
            ?>
        </select>
        <br>
        <input type="submit" value="Submit" />
    </form>

    <?php
    if ($_GET) {
        $first_name = $_GET["first_name"];
        $last_name = $_GET["last_name"];
        $day = $_GET["day"];
        $month = $_GET["month"];
        $year = $_GET["year"];

        $valid = true; //如果有空的，或者没有type进去的就会出error message,填了才是true
    
        if (empty($first_name)) {
            echo "<p style='color: red;'>Please enter your first name.</p>";
            $valid = false; //空的就会显示false,或者是没有填的
        }

        if (empty($last_name)) {
            echo "<p style='color: red;'>Please enter your last name.</p>";
            $valid = false; //空的就会显示false,或者是没有填的
        }

        if (empty($day) || empty($month) || empty($year)) {
            echo "<p style='color: red;'>Please select a valid date of birth.</p>";
            $valid = false; //空的就会显示false,或者是没有填的
        } else {
            $dob = DateTime::createFromFormat('Y-n-j', "$year-$month-$day"); //用 DateTime::createFromFormat来写出那个日期,Y-n-j是那个日期的format,在我create的variables里面拿出那个日期，然后塞进那个format里面
            if (!$dob || $dob->format('Y-n-j') !== "$year-$month-$day") { //！用来check$dob是对还是错的，用来看他们是不是equal的，如果不是就会有error
                echo "<p style='color: red;'>Invalid date of birth.</p>";
                $valid = false;
            }
        }

        if ($valid) {
            $age = calculateAge($dob);
            if ($age < 18) {
                echo "<p style='color: red;'>You must be 18 years old or above.</p>";
            } else {
                $starSign = getStarSign($month, $day);
                $chineseZodiac = getChineseZodiac($year);

                echo "Name: " . ucwords(strtolower($first_name . " " . $last_name)) . "<br>";
                echo "Date of Birth: " . $dob->format('Y-n-j') . "<br>";
                echo "Star Sign: " . $starSign . "<br>";
                echo "Chinese Zodiac: " . $chineseZodiac . "<br>";
            }
        }
    }
    ?>

    <?php

    function calculateAge($dob)
    {
        $today = new DateTime(); //current date and time
        $diff = $today->diff($dob); //calculate the difference between the current date and dob
        $age = $diff->y; //the calculated year
        return $age; //print out the year
    }

    function getStarSign($month, $day)
    {
        // Define the start dates for each star sign
        $starSigns = array(
            array("name" => "Capricornus", "start" => "12-22", "end" => "01-19"),
            array("name" => "Aquarius", "start" => "01-20", "end" => "02-18"),
            array("name" => "Pisces", "start" => "02-19", "end" => "03-20"),
            array("name" => "Aries", "start" => "03-21", "end" => "04-19"),
            array("name" => "Taurus", "start" => "04-20", "end" => "05-20"),
            array("name" => "Gemini", "start" => "05-21", "end" => "06-20"),
            array("name" => "Cancer", "start" => "06-21", "end" => "07-22"),
            array("name" => "Leo", "start" => "07-23", "end" => "08-22"),
            array("name" => "Virgo", "start" => "08-23", "end" => "09-22"),
            array("name" => "Libra", "start" => "09-23", "end" => "10-22"),
            array("name" => "Scorpius", "start" => "10-23", "end" => "11-21"),
            array("name" => "Sagittarius", "start" => "11-22", "end" => "12-21")
        );

        // Convert the month and day to a comparable format
        $formattedDate = $month . "-" . $day; //月份-日期
    
        // Find the matching star sign based on the date
        foreach ($starSigns as $sign) {
            if ($formattedDate >= $sign['start'] && $formattedDate <= $sign['end']) {
                return $sign['name'];
            }
        }
    }

    function getChineseZodiac($year)
    {
        $zodiacs = array(
            "Rat",
            "Ox",
            "Tiger",
            "Rabbit",
            "Dragon",
            "Snake",
            "Horse",
            "Goat",
            "Monkey",
            "Rooster",
            "Dog",
            "Pig"
        );

        $startYear = 1900; // Start year for Chinese Zodiac cycle
        $zodiacIndex = ($year - $startYear) % 12; //%除出来的余数，就找到是哪一个生肖
    
        return $zodiacs[$zodiacIndex];
    }
    ?>

</body>

</html>