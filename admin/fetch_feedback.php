<?php
// fetch_feedback.php - Handles AJAX requests for feedback filtering
include 'db_connection.php';

// Capture filter inputs
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$brand = isset($_GET['brand']) ? mysqli_real_escape_string($conn, $_GET['brand']) : '';
$startDate = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : '';
$endDate = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : '';
$rating = isset($_GET['rating']) ? intval($_GET['rating']) : '';

// Build the dynamic WHERE clause
$whereClauses = [];
if (!empty($search)) {
    $whereClauses[] = "(p.product_id LIKE '%$search%' OR p.product_name LIKE '%$search%' OR p.brand LIKE '%$search%' OR r.review_date LIKE '%$search%')";
}
if (!empty($category)) {
    $whereClauses[] = "c.category_id = '$category'";
}
if (!empty($brand)) {
    $whereClauses[] = "p.brand = '$brand'";
}
if (!empty($startDate) && !empty($endDate)) {
    $whereClauses[] = "r.review_date BETWEEN '$startDate' AND '$endDate'";
}
if (!empty($rating)) {
    $whereClauses[] = "r.rating = $rating";
}

$whereSQL = (count($whereClauses) > 0) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

// Query with dynamic filters
$query = "SELECT r.review_id, CONCAT(u.first_name, ' ', u.last_name) AS username, 
                 p.product_id, p.product_name, c.category_name, p.brand, 
                 r.rating, r.comment, r.review_date 
          FROM reviews r 
          JOIN users u ON r.user_id = u.user_id 
          JOIN products p ON r.product_id = p.product_id 
          JOIN categories c ON p.category_id = c.category_id 
          $whereSQL
          ORDER BY r.review_date DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

// Output feedback rows
if (mysqli_num_rows($result) > 0) {
    echo '<table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['review_id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['product_id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['category_name']}</td>
                <td>{$row['brand']}</td>
                <td>{$row['rating']}/5</td>
                <td>{$row['comment']}</td>
                <td>{$row['review_date']}</td>
                <td><button class='delete-feedback' data-id='{$row['review_id']}'>Delete</button></td>
              </tr>";
    }
    echo '</tbody></table>';
} else {
    echo "<p>No feedback found.</p>";
}
