<?php
require_once 'db_connection.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order_type = isset($_GET['order_type']) ? $_GET['order_type'] : '';

$response = ['success' => false, 'items' => []];

if (!$order_id || !$order_type) {
    echo json_encode(['success' => false, 'message' => 'Order ID and Order Type are required.']);
    exit();
}

// 🚚 1. CART-based Order
if ($order_type === 'Cart Order') {
    $sql = "SELECT oi.product_id, p.product_name, p.image_url, oi.quantity, oi.price, 
                   (oi.quantity * oi.price) AS subtotal, NULL AS prescription_file, 
                   o.order_status 
            FROM orderItem oi
            JOIN products p ON oi.product_id = p.product_id
            JOIN orders o ON oi.order_id = o.order_id
            WHERE oi.order_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response['items'][] = $row;
        $response['order_status'] = $row['order_status'];
    }
    $stmt->close();
}

// 🧾 2. PRESCRIPTION-based Order
elseif ($order_type === 'Prescription Order') {
    $sql = "SELECT pm.product_id, p.product_name, p.image_url, pm.quantity, pr.price, 
                   (pm.quantity * pr.price) AS subtotal, pres.prescription_file, 
                   pres.status AS order_status
            FROM prescription_medicine pm
            JOIN products p ON pm.product_id = p.product_id
            JOIN prescription pres ON pm.prescription_id = pres.prescription_id
            JOIN products pr ON pm.product_id = pr.product_id
            WHERE pm.prescription_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response['items'][] = $row;
        $response['order_status'] = $row['order_status'];
    }
    $stmt->close();
}
else {
    echo json_encode(['success' => false, 'message' => 'Invalid order type.']);
    exit();
}

// ✅ Response
if (!empty($response['items'])) {
    $response['success'] = true;
} else {
    $response['message'] = "No order details found.";
}

echo json_encode($response);
?>