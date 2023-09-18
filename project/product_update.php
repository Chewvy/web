<?php
session_start();

$defaultImage = 'ProductComingSoon.jpg';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Update Product - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
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

        .image {
            margin-bottom: 1em;
        }
    </style>
</head>

<?php
include 'navbar.php';
?>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Update Product</h1>
        </div>
        <?php
        // get passed parameter value, in this case, the record ID
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        // include database connection
        include 'config_folder/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT p.id, p.name, p.description, p.categoryID, c.category_name, p.price, p.promotion_price, p.image, p.manufacture_date, p.expire_date
            FROM products p
            INNER JOIN product_category c ON p.categoryID = c.categoryID
            WHERE p.id = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $categoryID = $row['categoryID'];
            $category_name = $row['category_name'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
            $image = $row['image'];
            $manufacture_date = $row['manufacture_date'];
            $expire_date = $row['expire_date'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        // check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // posted values
                $name = strip_tags($_POST['name']);
                $description = strip_tags($_POST['description']);
                $price = floatval(str_replace("RM", "", $_POST['price']));
                $promotion_price = ($_POST['promotion_price'] !== '') ? strip_tags($_POST['promotion_price']) : null;
                $manufacture_date = strip_tags($_POST['manufacture_date']);
                $expire_date = !empty($_POST['expire_date']) ? strip_tags($_POST['expire_date']) : NULL;
                $submitted_categoryID = $_POST['category']; // Get categoryID from the form

                $flag = true;

                // Handle image upload
                $image = $_FILES['image']['name'];
                $image_temp = $_FILES['image']['tmp_name'];
                // Define a default image filename
                $defaultImage = 'ProductComingSoon.jpg';

                // Check if a new image is uploaded
                if (!empty($_FILES['image']['name'])) {
                    $image_temp = $_FILES['image']['tmp_name'];
                    $image_info = getimagesize($image_temp); //image temporary store
                    $image_width = $image_info[0];
                    $image_height = $image_info[1];

                    // Check if the uploaded image is square (same width and height)
                    if ($image_width == $image_height) {
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
                } else {
                    if (empty($row['image'])) {
                        // If no new image is uploaded and no previous image exists, set the image to the default image filename
                        $image = $defaultImage;
                    } else {
                        // If no new image is uploaded but a previous image exists, retain the old image filename
                        $image = $row['image'];
                    }
                }
                // Check if the "delete_image" checkbox is checked
                if (isset($_POST['delete_image']) && $_POST['delete_image'] == 1) {
                    // Delete the current image file
                    if (!empty($image) && $image !== $defaultImage) {
                        $image_path = 'image/' . $image;
                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                        
                        // Set the image to the default image filename
                        $image = $defaultImage;
                    }
                }
       
                if (empty($name)) {
                    echo "<div class='alert alert-danger'>Please enter the product name</div>";
                    $flag = false;
                }
                if (empty($description)) {
                    echo "<div class='alert alert-danger'>Please enter a description</div>";
                    $flag = false;
                }
                if (empty($categoryID)) {
                    echo "<div class='alert alert-danger'>Please select a category</div>";
                    $flag = false;
                }
                if (empty($price)) {
                    echo "<div class='alert alert-danger'>Please enter a price</div>";
                    $flag = false;
                } else {
                    $formatted_price = sprintf("%.2f", $price);
                }

                // Check if promotion_price is not empty and is a number
                if (!empty($promotion_price)) {
                    // Extract and store the numeric part without "RM"
                    $promotion_price_numeric = preg_replace('/[^0-9.]/', '', $promotion_price);

                    if (!is_numeric($promotion_price_numeric)) {
                        echo "<div class='alert alert-danger'>Promotion price must be a number</div>";
                        $flag = false;
                    } elseif ($promotion_price_numeric > $price) {
                        echo "<div class='alert alert-danger'>Promotion price must be lower than the original price</div>";
                        $flag = false;
                    }
                } else {
                    $promotion_price = '0.00'; // Set a default value with "RM" prefix
                }

                if (empty($manufacture_date)) {
                    echo "<div class='alert alert-danger'>Please select a manufacture date</div>";
                    $flag = false;
                } elseif (!empty($expire_date) && $expire_date < $manufacture_date) {
                    echo "<div class='alert alert-danger'>Expire date must be later than the manufacture date</div>";
                    $flag = false;
                }

                if ($flag) {
                    // Update query
                    $query = "UPDATE products SET name=:name, description=:description, price=:price, promotion_price=:promotion_price, image=:image, manufacture_date=:manufacture_date, expire_date=:expire_date, categoryID=:categoryID WHERE id = :id";

                    // Prepare query for execution
                    $stmt = $con->prepare($query);

                    // Bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $formatted_price);
                    $stmt->bindParam(':promotion_price', $promotion_price, PDO::PARAM_STR);
                    $stmt->bindParam(':image', $image);
                    $stmt->bindParam(':manufacture_date', $manufacture_date);
                    $stmt->bindParam(':expire_date', $expire_date);
                    $stmt->bindParam(':categoryID', $submitted_categoryID);
                    $stmt->bindParam(':id', $id);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update the record.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- Display the existing data and allow updates -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post"
            enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name'
                            value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>"
                            class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description'
                            class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select class="form-control" aria-label="Default select example" name="category">
                            <option value="">Select a Category</option>

                            <?php
                            // Fetch product categories from the database and populate the dropdown
                            $categoryQuery = "SELECT categoryID, category_name FROM product_category";
                            $categoryStmt = $con->prepare($categoryQuery);
                            $categoryStmt->execute();

                            while ($row1 = $categoryStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row1);
                                $selected = ($categoryID == (isset($submitted_categoryID) ? $submitted_categoryID : $row['categoryID'])) ? 'selected' : '';
                                echo "<option value='$categoryID' $selected>$category_name</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Price (RM)</td>
                    <td><input type='text' name='price'
                            value="<?php echo htmlspecialchars("RM" . number_format($price, 2), ENT_QUOTES); ?>"
                            class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price (RM)</td>
                    <td><input type='text' name='promotion_price'
                            value="<?php echo htmlspecialchars($promotion_price, ENT_QUOTES); ?>"
                            class='form-control' /></td>
                </tr>
                <tr>
                    <td>Delete Current Image</td>
                    <td>
                        <?php
                        if (!empty($image) && $image !== $defaultImage) {
                            echo "<input type='checkbox' name='delete_image' value='1' /> Check this to delete the current image";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Photo</td>
                    <td>
                        <div class="image">
                            <input type="file" name="image" id="image" /><br>
                            <?php
                            if (!empty($image)) {
                                echo "<img src='image/$image' alt='Product Image' style='max-width: 100px;' />";
                            } else {
                                echo "<img src='$defaultImage' alt='Product Coming Soon' style='max-width: 100px;' />";
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date'
                            class='form-control'
                            value="<?php echo isset($manufacture_date) ? $manufacture_date : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Expire Date</td>
                    <td><input type='date' name='expire_date'
                            class='form-control'
                            value="<?php echo isset($expire_date) ? $expire_date : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type='submit' value='Save Changes' class='btn btn-primary' /> <a
                            href='product_index.php' class='btn btn-danger'>Back to read products</a></td>
                </tr>
            </table>
        </form>
    </div>
    <!-- end .container -->
    
     <!-- Latest compiled and minified Bootstrap 5 JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>
