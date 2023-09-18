<?php
// Include database connection
include 'config_folder/database.php';

try {
    // Get record ID
    $id = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: Record ID not found.');

        // Delete query
        $delete_query = "DELETE FROM order_summary WHERE order_id = ?";
        $delete_stmt = $con->prepare($delete_query);
        $delete_stmt->bindParam(1, $id);

        if ($delete_stmt->execute()) {
            // Redirect to the read records page and tell the user the record was deleted
            header('Location:Order_listing.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
 catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>
