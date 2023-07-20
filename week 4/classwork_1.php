<!DOCTYPE html>
<html>

<body>

    <form method="get">
        First Name: <input type="text" name="first_name"><br>
        Last Name: <input type="text" name="last_name"><br>

        <select id="day" name="day">//same name doesn't matter
            <option value="">Day</option>
            <?php
            for ($i_day = 1; $i_day <= 31; $i_day++) {
                $selected = $selected_day == $i_day ? ' selected' : '';
                echo '<option value="' . $i_day . '"' . $selected . '>' . $i_day . '</option>' . "\n";
            }
            ?>
        </select>

        <select id="month" name="month">
            <option value="">Month</option>
            <?php
            $selected_month = date('F');
            for ($i_month = 1; $i_month <= 12; $i_month++) {
                $selected = $selected_month == $i_month ? ' selected' : '';
                echo '<option value="' . $i_month . '"' . $selected . '>' . $i_month . '</option>' . "\n";
            }
            ?>
        </select>

        <select id="year" name="year">
            <option value="">Year</option>
            <?php
            $year_start = 1940;
            $year_end = date('Y');

            for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
                $selected = $selected_year == $i_year ? ' selected' : '';
                echo '<option value="' . $i_year . '"' . $selected . '>' . $i_year . '</option>' . "\n";
            }
            ?>
        </select>
        <br>
        <input type="submit" value="Submit" />
    </form>

    <?php
    function starSign($day, $month)
    {

        $star = "";

        if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
            $star = "Aries";
        } else if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
            $star = "Taurus";
        } else if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 20)) {
            $star = "Gemini";
        } else if (($month == 6 && $day >= 21) || ($month == 7 && $day <= 22)) {
            $star = "Cancer";
        } else if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
            $star = "Leo";
        } else if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
            $star = "Virgo";
        } else if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 22)) {
            $star = "Libra";
        } else if (($month == 10 && $day >= 23) || ($month == 11 && $day <= 21)) {
            $star = "Scorpio";
        } else if (($month == 11 && $day >= 23) || ($month == 12 && $day <= 21)) {
            $star = "Sagittarius";
        } else if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) {
            $star = "Capricorn";
        } else if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
            $star = "Aquarius";
        } else if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {
            $star = "Pisces";
        }

        return $star;
    }


    function chineseZodiac($year)
    {
        $zodiac = array(
            "Rat",
            "Ox",
            "Tiger",
            "Rabbit",
            "Dragon",
            "Snake",
            "Horse",
            "Sheep",
            "Monkey",
            "Rooster",
            "Dog",
            "Pig"
        );

        $remainder = ($year - 1900) % 12;
        return $zodiac[$remainder];
    }


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
                $starSign = starSign($month, $day);
                $chineseZodiac = chineseZodiac($year);

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

    ?>

</body>

</html>