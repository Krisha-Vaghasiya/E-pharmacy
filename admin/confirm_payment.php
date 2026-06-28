<?php
session_start();
include 'db_connection.php'; 

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$conn = db_connect(); // Ensure the database connection is initialized

if (!$conn) {
    error_log("Database connection failed.");
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log("Unauthorized access attempt: No session user_id.");
    session_destroy();
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized action. Please log in."]);
    exit;
}

$admin_id = $_SESSION['user_id'];

// Check if user is an admin
$role_check_query = "SELECT role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($role_check_query);

if (!$stmt) {
    error_log("Database error: " . $conn->error);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

if (!$user_data || $user_data['role'] !== 'admin') {
    error_log("Unauthorized access: User ID $admin_id attempted admin action.");
    session_destroy();
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Access denied. Only admins can confirm payments."]);
    exit;
}

// Validate order_id
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
if ($order_id <= 0) {
    error_log("Invalid order_id: " . $_POST['order_id']);
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid order ID"]);
    exit;
}

// Update payment status
$sql = "UPDATE payment SET payment_status = 'Confirmed', received_by = ?, received_at = NOW() WHERE order_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Database error: " . $conn->error);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$stmt->bind_param("ii", $admin_id, $order_id);
$success = $stmt->execute();
$stmt->close();

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "DB Update Failed: " . $conn->error]);
}

$conn->close();
?>
