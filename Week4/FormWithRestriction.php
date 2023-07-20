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
        <select id="gender" name="gender">
            <option value="">Select gender</option>
            <?php
            $genders = array(
                1 => 'Male',
                2 => 'Female',
            );

            foreach ($genders as $value => $label) {
                echo '<option value="' . $value . '">' . $label . '</option>' . "\n";
            }
            ?>
        </select>

        <br>
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        Confirm Password: <input type="password" name="confirm_password"><br>
        Email: <input type="text" name="email"><br>

        <input type="submit" value="Submit" />
    </form>

    <?php
    if ($_GET) {
        $first_name = $_GET["first_name"];
        $last_name = $_GET["last_name"];
        $day = $_GET["day"];
        $month = $_GET["month"];
        $year = $_GET["year"];
        $username = $_GET["username"];
        $password = $_GET["password"];
        $confirm_password = $_GET["confirm_password"];
        $email = $_GET["email"];

        $valid = true;

        if (empty($first_name)) {
            echo "<p style='color: red;'>Please enter your first name.</p>";
            $valid = false;
        }

        if (empty($last_name)) {
            echo "<p style='color: red;'>Please enter your last name.</p>";
            $valid = false;
        }

        if (empty($day) || empty($month) || empty($year)) {
            echo "<p style='color: red;'>Please select a valid date of birth.</p>";
            $valid = false;
        }

        if (empty($username)) {
            echo "<p style='color: red;'>Please enter your username.</p>";
            $valid = false;
        } elseif (!preg_match('/^[a-zA-Z_\-][a-zA-Z0-9_\-]{6,}$/', $username)) { //!preg_match use to check the string,username is the variable that we want to check it's pattern
            //there are 2 condition we want to check,the first character must be one of this [a-zA-Z_\-],the rest of it can be [a-zA-Z0-9_\-]
            echo "<p style='color: red;'>Username must be at least 6 characters long, start with a letter, and only contain letters, numbers, underscores, or hyphens.</p>";
            $valid = false; //if..elseif如过是true的话就执行一些代码，如果是false的话就执行2另一行代码
        }

        if (empty($password)) {
            echo "<p style='color: red;'>Please enter your password.</p>";
            $valid = false;
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*[+$()%@#])[A-Za-z\d]{8,}$/', $password)) {
            //^$ is to make sure it match the requirement betwween it.确保有1个小字母，1各大字母，1个号码，确认他没有+$()%@#这些符号，最后[A-Za-z\d]{8,}就是那个password里面要有这些才算valid
            echo "<p style='color: red;'>Password must be at least 8 characters long, contain at least 1 uppercase letter, 1 lowercase letter, and 1 number. Symbols like +$()% (@#) are not allowed.</p>";
            $valid = false;
        }

        if (empty($confirm_password)) {
            echo "<p style='color: red;'>Please confirm your password.</p>";
            $valid = false;
        } elseif ($password !== $confirm_password) {
            echo "<p style='color: red;'>Passwords do not match.</p>";
            $valid = false;
        }

        if (empty($email)) {
            echo "<p style='color: red;'>Please enter your email.</p>";
            $valid = false;
        } elseif (!preg_match('/^(?=.*@)$/', $email)) { //use to check whether the email is valid or correct if not it will show error,like if it has @ or not
            echo "<p style='color: red;'>Invalid email format.</p>";
            $valid = false;
        }

        if ($valid) {
            // Perform further processing or validation if needed
            echo "<p style='color: green;'>Form submitted successfully.</p>";
        }
    }
    ?>

</body>
</html>