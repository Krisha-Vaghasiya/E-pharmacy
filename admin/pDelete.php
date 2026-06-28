<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']); // Secure input

    // Delete query
    $sql = "DELETE FROM products WHERE product_id = '$product_id'";

    if (mysqli_query($conn, $sql)) {
        echo "success"; // Send success response for AJAX handling
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}

mysqli_close($conn);
?>
