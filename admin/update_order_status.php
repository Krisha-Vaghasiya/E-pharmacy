<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Order status updated", "status" => $status]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating order status"]);
    }

    $stmt->close();
}
?>
