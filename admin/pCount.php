<?php
// Database connection
require_once('db_connection.php');

$conn = db_connect(); // Ensure connection is established

// Fetch category-wise product count
$sql = "SELECT c.category_id, COUNT(p.product_id) AS product_count
        FROM categories c
        LEFT JOIN products p ON c.category_id = p.category_id
        GROUP BY c.category_id;";

$result = $conn->query($sql);

// Check if the query executed successfully
if (!$result) {
    die("Query failed: " . $conn->error); // Debugging output
}

// Initialize an array for category-wise counts
$categoryCounts = [];

// Fetch results
while ($row = $result->fetch_assoc()) {
    $categoryCounts[$row['category_id']] = $row['product_count']; // Store count based on category_id
}

// Print data for debugging
print_r($categoryCounts);
?>