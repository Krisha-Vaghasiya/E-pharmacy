<?php
require_once 'db_connection.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['order_id']) || !isset($data['order_type'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit();
}

$order_id = $data['order_id'];
$order_type = $data['order_type'];
$response = ['success' => false];

if ($order_type === 'Cart Order') {
    // Cancel cart-based order
    $stmt = $conn->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Cart order cancelled successfully.";
    } else {
        $response['message'] = "Failed to cancel cart order.";
    }
    $stmt->close();

} elseif ($order_type === 'Prescription Order') {
    // Cancel prescription-based order
    $stmt = $conn->prepare("UPDATE prescription SET status = 'Cancelled' WHERE prescription_id = ?");
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Prescription order cancelled successfully.";
    } else {
        $response['message'] = "Failed to cancel prescription order.";
    }
    $stmt->close();

} else {
    $response['message'] = "Invalid order type.";
}

echo json_encode($response);
?>
