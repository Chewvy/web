<?php
// Include database connection
include 'config_folder/database.php';

try {
    // Get record ID
    $id = isset($_GET['categoryID']) ? $_GET['categoryID'] : die('ERROR: Record ID not found.');

    // Check if the category has products associated with it
    $check_product_query = "SELECT COUNT(*) AS product_count FROM product WHERE categoryID = ?";
    $check_product_stmt = $con->prepare($check_product_query);
    $check_product_stmt->bindParam(1, $id);
    $check_product_stmt->execute();
    $product_count = $check_product_stmt->fetch(PDO::FETCH_ASSOC)['product_count'];

    if ($product_count > 0) {
        // If there are products associated with the category, set an error message and redirect
        header('Location: product_index.php?action=unableDelete');
    } else {
        // Delete query
        $delete_query = "DELETE FROM product_category WHERE categoryID = ?";
        $delete_stmt = $con->prepare($delete_query);
        $delete_stmt->bindParam(1, $id);

        if ($delete_stmt->execute()) {
            // Redirect to the read records page and tell the user the record was deleted
            header('Location: category_index.php?action=deleted');
        } else {
            // If the delete operation fails, set an error message and redirect
            $_SESSION['error_message'] = 'Unable to delete the category.';
            header('Location: category_index.php?action=UnableDelete');
        }
    }
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>
