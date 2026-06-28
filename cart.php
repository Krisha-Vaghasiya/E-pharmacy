<?php
session_start();
include 'db_connection.php';


$cart_items = [];
$total_price = 0;
$total_discount = 0;
$total_items = 0;

if (isset($_SESSION['user_id'])) {
    // Fetch cart from database for logged-in users
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.product_name, p.brand, p.description, 
                            p.price, p.image_url, p.expiry_date 
                            FROM cart c JOIN products p ON c.product_id = p.product_id 
                            WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['quantity'];
        $total_discount += ($row['price'] * 1.15 - $row['price']) * $row['quantity'];
        $total_items += $row['quantity'];
    }
} else {
    // Fetch cart from session for guest users
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            if (is_array($item)) {
                $cart_items[] = $item;
                $total_price += $item['price'] * $item['quantity'];
                $total_discount += ($item['price'] * 1.15 - $item['price']) * $item['quantity'];
                $total_items += $item['quantity'];
            }
        }
    } else {
        $_SESSION['cart'] = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cart.css"> <!-- Include your CSS file -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
</head>
<body>
<nav aria-label="breadcrumb">
    <ul class="breadcrumb">
        <li><a href="home.php">Home</a></li>
        <li> > </li>
        <li class="active">Cart</li>
    </ul>
</nav>

<div class="container">
<div class="cart-container">
    <h2> Items in your Cart</h2>
    
    <!-- Floating Message Box -->
    <div id="floating-message" class="floating-message"></div>

    <?php if (empty($cart_items)) { ?>
        <p class="empty-cart">Your cart is empty.</p>
    <?php } else { ?>
        <?php foreach ($cart_items as $item) { ?>
            <div class="cart-item">
                <img src="admin/<?php echo $item['image_url']; ?>" alt="<?php echo $item['product_name']; ?>" class="product-img">
                <div class="cart-details">
                    <h3><?php echo $item['product_name']; ?></h3>
                    <p class="brand">By <?php echo $item['brand']; ?></p>
                    <p class="description"><?php echo $item['description']; ?></p>
                    <p class="price">MRP <del>₹<?php echo number_format($item['price'] * 1.15, 2); ?></del> 
                       <strong>₹<?php echo number_format($item['price'], 2); ?></strong> 15% OFF</p>
                    <p class="delivery">Delivery by Today, 6:00 pm - 10:00 pm</p>
                </div>
                <div class="cart-actions">
                    <div class="quantity-container">
                        <button class="quantity-btn decrease" data-id="<?php echo $item['product_id']; ?>">-</button>
                        <input type="number" class="cart-quantity" data-id="<?php echo $item['product_id']; ?>" 
                               value="<?php echo $item['quantity']; ?>" min="1" max="50">
                        <button class="quantity-btn increase" data-id="<?php echo $item['product_id']; ?>">+</button>
                    </div>
                    <button class="remove-item material-icons" data-id="<?php echo $item['product_id']; ?>">delete</button>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

    <!-- Order Summary Section -->
    <div class="order-summary">
        <h3>Order Summary</h3>
        <div class="summary-details">
            <p>Total Items: <span id="total-items"><?php echo $total_items; ?></span></p>
            <p>Total Price: ₹<span id="total-price"><?php echo number_format($total_price, 2); ?></span></p>
            <p>Total Discount: ₹<span id="total-discount"><?php echo number_format($total_discount, 2); ?></span></p>
            <p class="final-price">Final Price: ₹<span id="final-price"><?php echo number_format($total_price - $total_discount, 2); ?></span></p>
        </div>
       <!-- Checkout Button -->
       <button id="checkout-btn" onclick="proceedToCheckout()">Proceed to Checkout</button>


    </div>
</div>

<script src="/projectC/cart-script.js"></script>
</body>
</html>