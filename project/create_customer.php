<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
<title>PDO - Read Order Details - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script></head>

<?php
include 'navbar.php';
?>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customers</h1>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'config_folder/database.php';

            try {
                $query = "INSERT INTO customer SET username=:username, email=:email, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, DOB=:DOB, account_status=:account_status, registration_date_and_time=:registration_date_and_time, image=:image";
                $stmt = $con->prepare($query);

                $username = strip_tags(ucwords(strtolower($_POST['username'])));
                $email = strip_tags(ucwords(strtolower($_POST['email'])));
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $first_name = strip_tags(ucwords(strtolower($_POST['first_name'])));
                $last_name = strip_tags(ucwords(strtolower($_POST['last_name'])));
                $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
                $DOB = $_POST['DOB'];
                $account_status = isset($_POST['account_status']) ? $_POST['account_status'] : "";

                // Hash the password before storing it in the database
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':DOB', $DOB);
                $stmt->bindParam(':account_status', $account_status);
                $stmt->bindParam(':image', $image_name);

                $registration_date_and_time = date('Y-m-d H:i:s');
                $stmt->bindParam(':registration_date_and_time', $registration_date_and_time);

                $flag = true;

                if (empty($username)) {
                    echo "<div class='alert alert-danger'>Please enter the username</div>";
                    $flag = false;
                } elseif (strlen($username) < 6) {
                    echo "<div class='alert alert-danger'>Username must be at least 6 characters long.</div>";
                    $flag = false;
                }

                if (empty($email)) {
                    echo "<div class='alert alert-danger'>Please enter your email.</div>";
                    $flag = false;
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<div class='alert alert-danger'>Invalid email format.</div>";
                    $flag = false;
                }

                if (empty($password)) {
                    echo "<div class='alert alert-danger'>Please enter your password.</div>";
                    $flag = false;
                } elseif (strlen($password) < 8) {
                    echo "<div class='alert alert-danger'>Password must be at least 8 characters long.</div>";
                    $flag = false;
                } elseif ($password !== $confirm_password) {
                    echo "<div class='alert alert-danger'>Confirm password does not match with your password</div>";
                    $flag = false;
                }

                if (empty($first_name)) {
                    echo "<div class='alert alert-danger'>Please enter your first name</div>";
                    $flag = false;
                }

                if (empty($last_name)) {
                    echo "<div class='alert alert-danger'>Please enter your last name</div>";
                    $flag = false;
                }

                if (empty($gender)) {
                    echo "<div class='alert alert-danger'>Please select a gender</div>";
                    $flag = false;
                }

                if (empty($DOB)) {
                    echo "<div class='alert alert-danger'>Please select your date of birth</div>";
                    $flag = false;
                }

                if (empty($account_status)) {
                    echo "<div class='alert alert-danger'>Please select your account status</div>";
                    $flag = false;
                }

                // Check if the "image" file input is set
                if (isset($_FILES["image"]["name"]) && !empty($_FILES["image"]["name"])) {
                    $image_name = $_FILES["image"]["name"];
                    $image_tmp_name = $_FILES["image"]["tmp_name"];
                    $image_size = $_FILES["image"]["size"];
                    $image_type = $_FILES["image"]["type"];

                    // Check if it's a JPEG or JPG image
                    if ($image_type == "image/jpeg" || $image_type == "image/jpg") {
                        list($image_width, $image_height) = getimagesize($image_tmp_name); // Calculate image dimensions

                        if ($image_width == $image_height) {
                            if ($image_size <= 512 * 1024) {
                                $upload_dir = "image/";
                                $target_file = $upload_dir . $image_name;

                                if (move_uploaded_file($image_tmp_name, $target_file)) {
                                    // Image uploaded successfully
                                } else {
                                    echo "<div class='alert alert-danger'>Unable to upload the image.</div>";
                                    $flag = false;
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Image size must be less than or equal to 512KB.</div>";
                                $flag = false;
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Image must be square (same width and height).</div>";
                            $flag = false;
                        }
                    } elseif ($image_type == "image/png") {
                        // Check for PNG image type and perform necessary checks
                    } elseif ($image_type == "image/gif") {
                        // Check for GIF image type and perform necessary checks
                    } else {
                        echo "<div class='alert alert-danger'>Invalid image format. Supported formats: JPG, JPEG, PNG, GIF.</div>";
                        $flag = false;
                    }
                } else {
                    // No image was uploaded, use the default image
                    $defaultImage = 'default.jpg'; // Replace with the actual URL of your default image.
                    $image_name = $defaultImage;
                    $image_type = ''; // Define a default value for image_type
                }

                if ($flag) {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username:</td>
                    <td><input type="text" id="username" name="username" class='form-control'></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="text" id="email" name="email" class='form-control'></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" id="password" name="password" class='form-control'></td>
                </tr>
                <tr>
                    <td>Confirm Password:</td>
                    <td><input type="password" id="confirm_password" name="confirm_password" class='form-control'></td>
                </tr>
                <tr>
                    <td>First Name:</td>
                    <td><input type="text" id="first_name" name="first_name" class='form-control'></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><input type="text" id="last_name" name="last_name" class='form-control'></td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td>
                        <input type="radio" class="gender" name="gender" value="female">
                        <label for="female">Female</label>

                        <input type="radio" class="gender" name="gender" value="male">
                        <label for="male">Male</label>

                        <input type="radio" class="gender" name="gender" value="others">
                        <label for="others">Others</label>
                    </td>
                </tr>

                <tr>
                    <td>Date of Birth:</td>
                    <td><input type='date' name='DOB' class='form-control'></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <input type="radio" class="account_status" name="account_status" value="active">
                        <label for="active">Active</label>

                        <input type="radio" class="account_status" name="account_status" value="inactive">
                        <label for="inactive">Inactive</label>

                        <input type="radio" class="account_status" name="account_status" value="pending">
                        <label for="pending">Pending</label>
                    </td>
                </tr>
                <tr>
                    <td>Photo</td>
                    <td><input type="file" name="image" />
                    <?php
                        if (!empty($image)) {
                            echo "<img src='image/$image' alt='Customer Image' style='max-width: 100px;' />";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary'>
                        <a href='customer_index.php' class='btn btn-danger'>Back to read customer</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>
