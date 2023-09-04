<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Update Record - PHP CRUD Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<?php
include 'navbar.php';
?>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>

        <?php
        // Initialize the $gender variable
        $gender = "";

        // Get the customer_id from the URL
        $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : die('ERROR: Record ID not found.');

        // Include the database connection
        include 'config_folder/database.php';

        // Read the current record's data
        try {
            $query = "SELECT customer_id, username, email, password, first_name, last_name, gender, account_status, image FROM customer WHERE customer_id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            $stmt->bindParam(1, $customer_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $customer_id = $row['customer_id'];
            $username = $row['username'];
            $email = $row['email'];
            $password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $account_status = $row['account_status'];
            $image = $row['image'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        // Check if the form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $username = strip_tags($_POST['username']);
                $email = strip_tags($_POST['email']);
                $first_name = strip_tags($_POST['first_name']);
                $last_name = strip_tags($_POST['last_name']);
                $gender = strip_tags($_POST['gender']);
                $account_status = strip_tags($_POST['account_status']);
                // Handle image upload
                $image = $_FILES['image']['name'];
                $image_temp = $_FILES['image']['tmp_name'];
                $defaultImage = 'default.jpg';

                $flag = true;

                if (empty($username)) {
                    echo "<div class='alert alert-danger'>Please enter the username</div>";
                    $flag = false;
                }

                if (empty($email)) {
                    echo "<div class='alert alert-danger'>Please enter your email.</div>";
                    $flag = false;
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<div class='alert alert-danger'>Invalid email format.</div>";
                    $flag = false;
                }

                // Check if a new password is provided
                if (!empty($_POST['new_password'])) {
                    // Check the old password
                    if (empty($_POST['old_password']) || md5($_POST['old_password']) !== $password) {
                        echo "<div class='alert alert-danger'>Incorrect old password.</div>";
                        $flag = false;
                    } elseif (empty($_POST['confirm_password'])) {
                        echo "<div class='alert alert-danger'>Please enter both new password and confirm password.</div>";
                        $flag = false;
                    } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
                        echo "<div class='alert alert-danger'>New password and confirm password do not match.</div>";
                        $flag = false;
                    } else {
                        // Hash the new password with MD5
                        $password = md5($_POST['new_password']);
                    }
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

                if (empty($account_status)) {
                    echo "<div class='alert alert-danger'>Please select your account status</div>";
                    $flag = false;
                }

               // Check if a new image is uploaded
                if (!empty($_FILES['image']['name'])) {
                    $image_temp = $_FILES['image']['tmp_name'];
                    $image_info = getimagesize($image_temp);
                    
                    // Check if the uploaded image is square (same width and height)
                    if ($image_info[0] != $image_info[1]) {
                        echo "<div class='alert alert-danger'>Please upload a square image.</div>";
                        $flag = false;
                    }
                    
                    // Check if the file size is within the allowed limit (512KB)
                    if ($_FILES['image']['size'] > 512 * 1024) {
                        echo "<div class='alert alert-danger'>Image size exceeds the allowed limit (512KB).</div>";
                        $flag = false;
                    }
                    
                    if ($flag) {
                        // Move the new image to the destination folder
                        move_uploaded_file($image_temp, "image/$image");
                    }
                } elseif (isset($_POST['delete_image']) && $_POST['delete_image'] == 1) {
                    // User wants to delete the current image
                    if (!empty($row['image']) && file_exists("image/" . $row['image'])) {
                        unlink("image/" . $row['image']);
                    }
                    $image = $defaultImage; // Set the image to the default image filename
                } else {
                    // If no new image is uploaded and no deletion requested, retain the old image filename
                    $image = $row['image'];

                    // Check if the image is empty (no new image and no deletion)
                    if (empty($image)) {
                        // Set the image to the default image filename
                        $image = $defaultImage;
                    }
                }


                if ($flag) {
                    $query = "UPDATE customer
                    SET username = :username, email = :email, password=:password, first_name = :first_name, last_name = :last_name, gender = :gender, account_status = :account_status,image=:image
                    WHERE customer_id = :customer_id";

                    
                    // Prepare query for execution
                    $stmt = $con->prepare($query);
                    
                    // Bind parameters
                    $stmt->bindParam(':customer_id', $customer_id);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':first_name', $first_name);
                    $stmt->bindParam(':last_name', $last_name);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':account_status', $account_status);
                    $stmt->bindParam(':image', $image);
                    
                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update the record.</div>";
                    }
                }
            } catch (PDOException $exception) {
                echo "<div class='alert alert-danger'>Error: " . $exception->getMessage() . "</div>";
            }
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?' . $_SERVER['QUERY_STRING']); ?>"
                    method="post" enctype="multipart/form-data">
                    <div class="table-responsive">
                        <table class='table table-hover table-responsive table-bordered'>
                            <tr>
                                <td>Username</td>
                                <td><input type='text' name='username'
                                        value="<?php echo htmlspecialchars($username, ENT_QUOTES); ?>"
                                        class='form-control' />
                                </td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><input type='text' name='email'
                                        value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>"
                                        class='form-control' />
                                </td>
                            </tr>
                            <tr>
                                <td>Old Password</td>
                                <td><input type='password' name='old_password' class='form-control' /></td>
                            </tr>
                            <tr>
                                <td>New Password</td>
                                <td><input type='password' name='new_password' class='form-control' /></td>
                            </tr>
                            <tr>
                                <td>Confirm Password</td>
                                <td><input type='password' name='confirm_password' class='form-control' /></td>
                            </tr>
                            <tr>
                                <td>First Name</td>
                                <td><input type='text' name='first_name'
                                        value="<?php echo htmlspecialchars($first_name, ENT_QUOTES); ?>"
                                        class='form-control' />
                                </td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td><input type='text' name='last_name'
                                        value="<?php echo htmlspecialchars($last_name, ENT_QUOTES); ?>"
                                        class='form-control' />
                                </td>
                            </tr>
                            <tr>
                                <td>Gender:</td>
                                <td>
                                    <input type="radio" class="gender" name="gender" value="female"
                                        <?php echo ($gender === "female") ? "checked" : ""; ?>>
                                    <label for="female">Female</label>

                                    <input type="radio" class="gender" name="gender" value="male"
                                        <?php echo ($gender === "male") ? "checked" : ""; ?>>
                                    <label for="male">Male</label>

                                    <input type="radio" class="gender" name="gender" value="others"
                                        <?php echo ($gender === "others") ? "checked" : ""; ?>>
                                    <label for="others">Others</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Account Status</td>
                                <td>
                                    <input type="radio" class="account_status" name="account_status" value="active"
                                        <?php echo (isset($account_status) && $account_status === "active") ? "checked" : ""; ?>>
                                    <label for="active">Active</label>

                                    <input type="radio" class="account_status" name="account_status" value="inactive"
                                        <?php echo (isset($account_status) && $account_status === "inactive") ? "checked" : ""; ?>>
                                    <label for="inactive">Inactive</label>

                                    <input type="radio" class="account_status" name="account_status" value="pending"
                                        <?php echo (isset($account_status) && $account_status === "pending") ? "checked" : ""; ?>>
                                    <label for="pending">Pending</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Delete Current Image</td>
                                <td><input type="checkbox" name="delete_image" value="1" /> Check this to delete the current image</td>
                            </tr>
                            <tr>
                                <td>Photo</td>
                                <td><input type="file" name="image" /></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                                    <a href='customer_index.php' class='btn btn-danger'>Back to read customer</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>