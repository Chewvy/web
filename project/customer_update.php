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
            $query = "SELECT customer_id, username, email, first_name, last_name, gender, account_status, image FROM customer WHERE customer_id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            $stmt->bindParam(1, $customer_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $customer_id = $row['customer_id'];
            $username = $row['username'];
            $email = $row['email'];
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
                $query = "UPDATE customer
                          SET username = :username, email = :email, first_name = :first_name, last_name = :last_name, gender = :gender, account_status = :account_status";

                // If a new password is provided, update the password field
                if (!empty($_POST['new_password'])) {
                    $query .= ", password = :password";
                    $password = md5($_POST['new_password']);
                }

                if (!empty($_FILES['image']['name'])) {
                    $query .= ", image = :image";
                    $image = $_FILES['image']['name'];
                    $image_temp = $_FILES['image']['tmp_name'];
                }

                $query .= " WHERE customer_id = :customer_id";
                $stmt = $con->prepare($query);

                $username = strip_tags($_POST['username']);
                $email = strip_tags($_POST['email']);
                $first_name = strip_tags($_POST['first_name']);
                $last_name = strip_tags($_POST['last_name']);
                $gender = strip_tags($_POST['gender']);
                $account_status = strip_tags($_POST['account_status']);
                // Handle image upload
                $image = $_FILES['image']['name'];
                $image_temp = $_FILES['image']['tmp_name'];

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

                if ($flag) {
                    // Bind parameters
                    $stmt->bindParam(':customer_id', $customer_id);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':first_name', $first_name);
                    $stmt->bindParam(':last_name', $last_name);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':account_status', $account_status);
                    
                    // If a new password is provided, bind it
                    if (!empty($_POST['new_password'])) {
                        $stmt->bindParam(':password', $password);
                    }

                    // If an image is uploaded, move it to the target directory and bind the image parameter
                    if (!empty($_FILES['image']['name'])) {
                        // Define the target directory where you want to store the uploaded images
                        $targetDir = "image/";

                        // Get the original file name
                        $originalFileName = $_FILES['image']['name'];

                        // Generate a unique name for the uploaded file to avoid overwriting
                        $uniqueFileName = time() . '_' . $originalFileName;

                        // Define the path where the image will be saved
                        $targetPath = $targetDir . $uniqueFileName;

                        // Move the uploaded file to the target directory
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                            echo "<div class='alert alert-success'>Image uploaded successfully.</div>";
                            $stmt->bindParam(':image', $uniqueFileName);
                        } else {
                            echo "<div class='alert alert-danger'>Failed to upload image.</div>";
                        }
                    }

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
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
                                    <input type="radio" class="gender" name="gender" value="female" <?php echo ($gender === "female") ? "checked" : ""; ?>>
                                    <label for="female">Female</label>

                                    <input type="radio" class="gender" name="gender" value="male" <?php echo ($gender === "male") ? "checked" : ""; ?>>
                                    <label for="male">Male</label>

                                    <input type="radio" class="gender" name="gender" value="others" <?php echo ($gender === "others") ? "checked" : ""; ?>>
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
