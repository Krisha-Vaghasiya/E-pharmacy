<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID.']);
    exit;
}

// Check if the order exists and belongs to the user
$sql = "SELECT order_status FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order not found or unauthorized access.']);
    exit;
}

// Check if order is already confirmed or canceled
if ($order['order_status'] !== 'Pending') {
    echo json_encode(['success' => false, 'message' => 'Order cannot be canceled.']);
    exit;
}

// Update order status to "Cancelled"
$update_sql = "UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("i", $order_id);
$update_stmt->execute();

if ($update_stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Order canceled successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to cancel order.']);
}

$stmt->close();
$update_stmt->close();
$conn->close();
?>
