<?php
include 'db_connection.php';

$sql = "SELECT o.order_id, o.user_id, o.total_amount, o.payment_status, 
               p.payment_status AS payment_status, p.received_at
        FROM orders o
        JOIN payment p ON o.order_id = p.order_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <link rel="stylesheet" href="admin_payment.css">
</head>
<body>
    <div class="container">
        <h2> Payment Management</h2>
        
        <table>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total Amount</th>
                <th>Payment Status</th>
                <th>Received At</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['user_id']; ?></td>
                    <td>₹<?php echo $row['total_amount']; ?></td>
                    <td class="payment-status <?php echo ($row['payment_status'] === 'Pending') ? 'status-pending' : 'status-confirmed'; ?>">
                        <?php echo $row['payment_status']; ?>
                    </td>
                    <td>
                        <?php echo ($row['received_at']) ? date("Y-m-d H:i:s", strtotime($row['received_at'])) : '-'; ?>
                    </td>
                    <td>
                        <?php if ($row['payment_status'] === 'Pending') { ?>
                            <button class="confirm-btn" data-order-id="<?php echo $row['order_id']; ?>">Confirm Payment</button>
                        <?php } else { ?>
                            <button class="confirm-btn disabled-btn" disabled>Confirmed</button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script src="admin_payment.js"></script>
</body>
</html>

<?php
$conn->close();
?>
