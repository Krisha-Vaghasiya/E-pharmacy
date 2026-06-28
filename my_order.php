

<?php
session_start();
require_once 'db_connection.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

// Display order success message
if (isset($_SESSION['order_success'])) {
    echo "<p id='order-message' style='color: green; text-align: center; font-weight: bold;'>" . $_SESSION['order_success'] . "</p>";
    unset($_SESSION['order_success']); // Remove message after displaying it

    // Auto-refresh the page after 3 seconds to remove the message
    echo "<script>
        setTimeout(function() {
            document.getElementById('order-message').style.display = 'none';
        }, 3000);
    </script>";
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    order_id, 
    created_at, 
    CASE 
        WHEN order_type = 'Cart Order' THEN cart_order_status
        WHEN order_type = 'Prescription Order' THEN 
            COALESCE(
                NULLIF(prescription_order_status, ''),
                prescription_status,
                'Pending'
            )
    END AS order_status,
    total_price, 
    order_type, 
    prescription_id, 
    prescription_file 
FROM (
    -- Cart Orders
    SELECT 
        o.order_id, 
        o.created_at, 
        o.order_status AS cart_order_status,
        NULL AS prescription_order_status,
        NULL AS prescription_status,
        IFNULL(SUM(oi.quantity * oi.price), 0) AS total_price, 
        'Cart Order' AS order_type, 
        NULL AS prescription_id, 
        NULL AS prescription_file
    FROM orders o
    LEFT JOIN orderItem oi ON o.order_id = oi.order_id
    WHERE o.user_id = ? AND o.order_status != 'Cancelled'
    GROUP BY o.order_id, o.created_at, o.order_status
    HAVING total_price > 0

    UNION ALL

    -- Prescription Orders
    SELECT 
        p.prescription_id AS order_id, 
        p.created_at, 
        NULL AS cart_order_status,
        o.order_status AS prescription_order_status,
        p.status AS prescription_status,
        IFNULL(SUM(pm.quantity * pr.price), 0) AS total_price, 
        'Prescription Order' AS order_type, 
        p.prescription_id, 
        p.prescription_file
    FROM prescription p
    LEFT JOIN prescription_medicine pm ON p.prescription_id = pm.prescription_id
    LEFT JOIN products pr ON pm.product_id = pr.product_id
    LEFT JOIN orders o ON p.prescription_id = o.prescription_id AND o.user_id = ?
    WHERE p.user_id = ? 
      AND (o.order_status IS NULL OR o.order_status != 'Cancelled')
    GROUP BY p.prescription_id, p.created_at, p.status, o.order_status, p.prescription_file
    HAVING total_price > 0
) AS combined_orders
ORDER BY created_at DESC;
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id); // ✅ Corrected parameter binding
$stmt->execute();
$result = $stmt->get_result();


  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - E-Pharmacy</title>
    <link rel="stylesheet" href="order.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <style>
        .header {
    background-color: #2c3e50;
    color: #fff;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.header a {
    color: #fff;
    font-weight: bold;
    margin-right: 15px;
    transition: color 0.3s ease;
}

.header a:hover {
    color: white;
}
        </style>
</head>
<body>
<div class="header">
    <a href="/projectC/home.php">Home</a>
    <span>My Order</span>
</div>
<div class="container">
    <h2 class="page-title">My Orders</h2>

    <table class="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total Price</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr id="order_<?php echo $row['order_id']; ?>">
                    <td>#<?php echo $row['order_id']; ?></td>
                    <td><?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                    <td>
    <?php
    $status = $row['order_status'] ?? 'Pending'; // Default to 'Pending' if NULL
    $statusClass = strtolower(str_replace(' ', '', $status));
    ?>
    <span class="status <?php echo $statusClass; ?>">
        <?php echo htmlspecialchars($status); ?>
    </span>
</td>

                    <td>₹<?php echo number_format($row['total_price'], 2); ?></td>
             
                    <td><?php echo $row['order_type']; ?></td>

                    <td>
    <button class="view-btn" onclick="viewOrder(
        <?php echo $row['order_id']; ?>, 
        '<?php echo $row['order_type']; ?>', 
        '<?php echo $row['order_status']; ?>'
    )">View Details</button>
</td>


    
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal for Viewing Order Details -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Product Details</h2>
        <div id="orderDetails"></div>
    </div>
</div>
<!-- Modal for Viewing Prescription File -->
<div id="prescriptionModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePrescriptionModal()">&times;</span>
        <h3>Prescription File</h3>
        <div id="prescriptionContent"></div>
    </div>
</div>

<script src="order.js"></script>
</body>
</html>

<?php $stmt->close(); ?>