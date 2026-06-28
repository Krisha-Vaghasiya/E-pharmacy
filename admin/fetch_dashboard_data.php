<?php
include 'db_connection.php';

$response = [
    'totalOrders' => 0,
    'pendingOrders' => 0,
    'totalUsers' => 0,
    'totalRevenue' => 0.00,
    'totalProducts' => 0,
    'lowStock' => 0
];

// Fetch total orders
$orderQuery = "SELECT COUNT(*) AS totalOrders FROM orders";
$orderResult = mysqli_query($conn, $orderQuery);
if ($orderRow = mysqli_fetch_assoc($orderResult)) {
    $response['totalOrders'] = $orderRow['totalOrders'];
}

// Fetch pending orders
$pendingQuery = "SELECT COUNT(*) AS pendingOrders FROM orders WHERE order_status = 'Pending'";
$pendingResult = mysqli_query($conn, $pendingQuery);
if ($pendingRow = mysqli_fetch_assoc($pendingResult)) {
    $response['pendingOrders'] = $pendingRow['pendingOrders'];
}

// Fetch total users
$userQuery = "SELECT COUNT(*) AS totalUsers FROM users";
$userResult = mysqli_query($conn, $userQuery);
if ($userRow = mysqli_fetch_assoc($userResult)) {
    $response['totalUsers'] = $userRow['totalUsers'];
}

// Fetch total revenue
$revenueQuery = "SELECT SUM(total_amount) AS totalRevenue FROM orders WHERE order_status = 'Confirmed'";
$revenueResult = mysqli_query($conn, $revenueQuery);
if ($revenueRow = mysqli_fetch_assoc($revenueResult)) {
    $response['totalRevenue'] = number_format($revenueRow['totalRevenue'], 2);
}

// Fetch total products
$productQuery = "SELECT COUNT(*) AS totalProducts FROM products";
$productResult = mysqli_query($conn, $productQuery);
if ($productRow = mysqli_fetch_assoc($productResult)) {
    $response['totalProducts'] = $productRow['totalProducts'];
}

// Fetch low-stock products (assuming low stock is < 10)
$lowStockQuery = "SELECT COUNT(*) AS lowStock FROM products WHERE quantity_in_stock < 10";
$lowStockResult = mysqli_query($conn, $lowStockQuery);
if ($lowStockRow = mysqli_fetch_assoc($lowStockResult)) {
    $response['lowStock'] = $lowStockRow['lowStock'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
