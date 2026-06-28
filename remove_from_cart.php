<?php
session_start();
include 'db_connection.php';

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = $data['product_id'];

    if (isset($_SESSION['user_id'])) {
        // Remove item from the database for logged-in users
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Item removed from cart'];
        } else {
            $response['message'] = 'Failed to remove item from cart';
        }
    } else {
        // Remove item from the session for guest users
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $response = ['status' => 'success', 'message' => 'Item removed from cart'];
        } else {
            $response['message'] = 'Product not found in cart';
        }
    }
}

echo json_encode($response);
?>