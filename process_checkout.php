<?php
session_start();
include "db_connection.php";

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];
$prescription_id = isset($_POST['prescription_id']) && !empty($_POST['prescription_id']) ? (int)$_POST['prescription_id'] : null;
$order_type = $prescription_id ? 'prescription' : 'cart';

// Fetch User Inputs from Checkout Form
$full_name = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
$mobile_number = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$city = isset($_POST['city']) ? trim($_POST['city']) : '';
$pincode = isset($_POST['postal_code']) ? trim($_POST['postal_code']) : '';
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'COD'; // Default to COD

// Validate Prescription Order (if applicable)
if ($order_type === 'prescription') {
    $stmt = $conn->prepare("SELECT prescription_id FROM prescription WHERE prescription_id = ? AND user_id = ? AND status = 'Approved'");
    $stmt->bind_param("ii", $prescription_id, $user_id);
    $stmt->execute();
    if (!$stmt->get_result()->num_rows) {
        die("Invalid prescription or not approved");
    }
    $stmt->close();
}

// Fetch Order Items based on Order Type
if ($order_type === 'prescription') {
    $stmt = $conn->prepare("SELECT pm.product_id, pm.quantity, p.price FROM prescription_medicine pm 
                            JOIN products p ON pm.product_id = p.product_id WHERE pm.prescription_id = ?");
    $stmt->bind_param("i", $prescription_id);
} else {
    $stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c 
                            JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($order_items)) {
    die($order_type === 'prescription' ? "Prescription contains no items" : "Your cart is empty");
}

// Calculate Total Price
$total = 0;
foreach ($order_items as $item) {
    $total += $item['quantity'] * $item['price'];
}

$total_with_delivery = ($total > 0) ? ($total + 50) : 0;

$status = 'shipped'; // Order status stored only in `orders` table

// Ensure NULL for prescription_id if it's a cart order
if ($order_type === 'cart') {
    $prescription_id = null;
}

// Insert Order
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, order_status, full_name, mobile_number, address, city, pincode, prescription_id) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("idsssssssi", $user_id, $total_with_delivery, $payment_method, $status, $full_name, $mobile_number, $address, $city, $pincode, $prescription_id);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Insert Order Items
$stmt = $conn->prepare("INSERT INTO orderItem (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");

foreach ($order_items as $item) {
    $subtotal = $item['quantity'] * $item['price']; // ✅ Correct subtotal calculation
    $stmt->bind_param("iiidd", $order_id, $item['product_id'], $item['quantity'], $item['price'], $subtotal);
    $stmt->execute();
}
$stmt->close();


// ✅ Insert Payment Record (Fixed: Now Includes Total Amount)
$stmt = $conn->prepare("INSERT INTO payment (order_id, total_amount, payment_status, received_by, received_at) VALUES (?, ?, 'Pending', NULL, NULL)");
$stmt->bind_param("id", $order_id, $total_with_delivery);
$stmt->execute();
$stmt->close();


// ✅ Clear Cart after successful checkout (only for cart orders)
if ($order_type === 'cart') {
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");
}

// ✅ Redirect to My Orders Page
$_SESSION['order_success'] = "Order placed successfully!";
header("Location: my_order.php");
exit;
?>
