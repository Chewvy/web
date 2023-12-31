<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php
// Include database connection
include 'config_folder/database.php';

try {
    // Get record ID
    $id = isset($_GET['customer_id']) ? $_GET['customer_id'] : die('ERROR: Record ID not found.');

    // Check if the product has orders associated with it
    $check_orders_query = "SELECT COUNT(*) AS order_count FROM order_summary WHERE customer_id = ?";
    $check_orders_stmt = $con->prepare($check_orders_query);
    $check_orders_stmt->bindParam(1, $id);
    $check_orders_stmt->execute();
    $order_count = $check_orders_stmt->fetch(PDO::FETCH_ASSOC)['order_count'];

    if ($order_count > 0) {
        // If there are orders associated with the product, show a message
        header('Location: product_index.php?action=UnableDelete');  
        } else {
        // Delete query
        $delete_query = "DELETE FROM customer WHERE customer_id = ?";
        $delete_stmt = $con->prepare($delete_query);
        $delete_stmt->bindParam(1, $id);

        if ($delete_stmt->execute()) {
            // Redirect to the read records page and tell the user the record was deleted
            header('Location:customer_index.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>
