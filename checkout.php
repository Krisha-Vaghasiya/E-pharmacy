<?php
session_start();
include "db_connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$prescription_id = isset($_GET['prescription_id']) ? (int)$_GET['prescription_id'] : null;
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : null;
$total_amount = 0;
$order_type = $prescription_id ? 'prescription' : ($order_id ? 'order' : 'cart');

// Fetch order details
if ($order_type === 'prescription') {
    // Prescription Order (Before Checkout)
    $stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.price, pm.quantity 
                          FROM prescription_medicine pm
                          JOIN products p ON pm.product_id = p.product_id
                          WHERE pm.prescription_id = ?");
    $stmt->bind_param("i", $prescription_id);
} elseif ($order_type === 'order') {
    // Already Placed Order
    $stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.price, oi.quantity 
                          FROM orderItem oi
                          JOIN products p ON oi.product_id = p.product_id
                          WHERE oi.order_id = ?");
    $stmt->bind_param("i", $order_id);
} else {
    // Cart Order
    $stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.price, c.quantity 
                          FROM cart c
                          JOIN products p ON c.product_id = p.product_id
                          WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$order_details = $result->fetch_all(MYSQLI_ASSOC);

if (empty($order_details)) {
    die($order_type === 'prescription' ? "❌ Prescription contains no items" : "❌ Your cart is empty");
}

// Calculate total
foreach ($order_details as $row) {
    $total_amount += $row['price'] * $row['quantity'];
}

$delivery_charge = 50;
$total_delivery_charge = $total_amount + $delivery_charge;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="checkout.css"> <!-- Include your CSS file -->
</head>
<body>

<div class="header">
    <a href="home.php">Home</a>
    <span>Checkout</span>
</div>

<div class="checkout-container">
    <div>
        <h2>Billing Details</h2>
        <form action="process_checkout.php" method="POST">
            <input type="hidden" name="prescription_id" value="<?php echo htmlspecialchars($prescription_id ?? ''); ?>">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id ?? ''); ?>">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="number" name="phone" required>
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" required>
            </div>

            <div class="form-group">
                <label>Postal Code</label>
                <input type="text" name="postal_code" required>
            </div>
    </div>

    <div>
        <h2>Order Summary</h2>
        <div class="order-summary">
            <h3>Your Order</h3>
            <?php if (!empty($order_details)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_details as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2">Delivery Charge</td>
                            <td>₹<?php echo number_format($delivery_charge, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                <p class="total-price">Total: ₹<?php echo number_format($total_delivery_charge, 2); ?></p>
            <?php else: ?>
                <p>No items in the order.</p>
            <?php endif; ?>
        </div>

        <div class="payment-method">
            <label><input type="radio" name="payment_method" value="COD" checked> Cash on Delivery</label>
        </div>

        <button type="submit" class="btn-place-order">Place Order</button>
        </form>
    </div>
</div>

</body>
</html>
