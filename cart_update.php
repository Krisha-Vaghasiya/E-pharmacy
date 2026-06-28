<?php
session_start();
include 'db_connection.php';

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];

    if (isset($_SESSION['user_id'])) {
        // Update quantity in the database for logged-in users
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Quantity updated'];
        } else {
            $response['message'] = 'Failed to update quantity';
        }
    } else {
        // Update quantity in the session for guest users
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            $response = ['status' => 'success', 'message' => 'Quantity updated'];
        } else {
            $response['message'] = 'Product not found in cart';
        }
    }
}

echo json_encode($response);
?>