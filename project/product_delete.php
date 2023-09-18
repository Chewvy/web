<?php
// Include database connection
include 'config_folder/database.php';

try {
    // Get record ID
    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    // Check if the product has orders associated with it
    $check_orders_query = "SELECT COUNT(*) AS order_count FROM order_details WHERE product_id = ?";
    $check_orders_stmt = $con->prepare($check_orders_query);
    $check_orders_stmt->bindParam(1, $id);
    $check_orders_stmt->execute();
    $order_count = $check_orders_stmt->fetch(PDO::FETCH_ASSOC)['order_count'];

    if ($order_count > 0) {
        // If there are orders associated with the product, show a message
        header('Location: product_index.php?action=UnableDelete');    
    
    } else {
        // Delete query
        $delete_query = "DELETE FROM products WHERE id = ?";
        $delete_stmt = $con->prepare($delete_query);
        $delete_stmt->bindParam(1, $id);

        if ($delete_stmt->execute()) {
            // Redirect to the read records page and tell the user the record was deleted
            header('Location: product_index.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>
