<?php
session_start();
include 'db_connection.php';

$response = ['status' => 'error', 'message' => 'Cart is empty'];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.price 
                            FROM cart c JOIN products p ON c.product_id = p.product_id 
                            WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_price = 0;
    $total_discount = 0;
    $total_items = 0;

    while ($row = $result->fetch_assoc()) {
        $total_price += $row['price'] * $row['quantity'];
        $total_discount += ($row['price'] * 1.15 - $row['price']) * $row['quantity'];
        $total_items += $row['quantity'];
    }

    $response = [
        'status' => 'success',
        'cart_count' => $total_items,
        'total_price' => $total_price,
        'total_discount' => $total_discount,
        'total_items' => $total_items
    ];
} else if (isset($_SESSION['cart'])) {
    $total_price = 0;
    $total_discount = 0;
    $total_items = 0;

    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
        $total_discount += ($item['price'] * 1.15 - $item['price']) * $item['quantity'];
        $total_items += $item['quantity'];
    }

    $response = [
        'status' => 'success',
        'cart_count' => $total_items,
        'total_price' => $total_price,
        'total_discount' => $total_discount,
        'total_items' => $total_items
    ];
}

echo json_encode($response);
?>