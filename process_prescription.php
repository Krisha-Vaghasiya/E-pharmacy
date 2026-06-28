<?php
include "db_connection.php";
$prescription_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $total_amount = $_POST["total_amount"];
    
    // Insert order with prescription_id
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_status, prescription_id) VALUES (?, ?, 'Pending', ?)");
    $stmt->bind_param("idi", $user_id, $total_amount, $prescription_id);
    $stmt->execute();
    
    // Update prescription status
    $conn->query("UPDATE prescription SET status='Processed' WHERE id=$prescription_id");

    echo "<script>alert('Order created successfully!'); window.location.href='admin_orders.php';</script>";
}
?>
