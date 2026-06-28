<?php
session_start();
require_once 'db_connection.php'; // Ensure correct path

// Check if admin is logged in

// Fetch all orders
$sql = "SELECT orders.order_id, 
               CONCAT(users.first_name, ' ', users.last_name) AS customer_name, 
               orders.created_at, 
               orders.order_status,  
               SUM(orderItem.quantity * orderItem.price) AS total_price 
        FROM orders 
        JOIN users ON orders.user_id = users.user_id 
        JOIN orderItem ON orders.order_id = orderItem.order_id 
        GROUP BY orders.order_id, orders.created_at, orders.order_status 
        ORDER BY orders.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Management</title>
    <link rel="stylesheet" href="admin_order.css">
</head>
<body>
    <div class="container">
        <h2 class="page-title">Order Management</h2>
        <table class="order-table" >
            <thead style="background-color: #1996b2">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                        <td><span class="status <?php echo strtolower($row['order_status']); ?>"><?php echo $row['order_status']; ?></span></td>
                        <td>₹<?php echo number_format($row['total_price'], 2); ?></td>
                        <td>
                        
                        <?php
$orderId = $row['order_id'];
// Check if 'prescription_id' exists and is not empty
if (isset($row['prescription_id']) && !empty($row['prescription_id'])) {
    $orderType = 'Prescription Order';
} else {
    $orderType = 'Cart Order'; // Default if no prescription_id
}
?>
<button class="view-btn" onclick='viewOrderDetails(<?php echo $orderId; ?>, "<?php echo $orderType; ?>")'>View</button>




                            
                            <?php if ($row['order_status'] === 'Pending'): ?>
                                <button class="confirm-btn" onclick="updateOrderStatus(<?php echo $row['order_id']; ?>, 'Confirmed')">Confirm</button>
                                <button class="reject-btn" onclick="updateOrderStatus(<?php echo $row['order_id']; ?>, 'Rejected')">Reject</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Order Details -->
    <!-- Order Modal -->
<div id="orderModal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Order Details</h2>
        <div id="orderDetailsContent"></div>
    </div>
</div>

    
    <script src="admin_orders.js"></script>
</body>
</html>
<?php $stmt->close(); ?>
