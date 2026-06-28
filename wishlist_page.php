

<?php
session_start();
require_once('db_connection.php');


$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    // Fetch cart count from database
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT SUM(quantity) AS cart_count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cart_count = $row['cart_count'] ?? 0;
} else {
    // Get count from session cart
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            if (is_array($item) && isset($item['quantity'])) { // Ensure it's an array
                $cart_count += $item['quantity'];
            }
        }
   }   
}


$user_id = $_SESSION['user_id'] ?? 0;  // Get logged-in user ID

// Initialize guest wishlist if not set
if (!isset($_SESSION['guest_wishlist'])) {
    $_SESSION['guest_wishlist'] = [];
}

// 🟢 Fetch wishlist items for logged-in users
if ($user_id) {
    $sql = "SELECT p.product_id, p.product_name, p.price, p.image_url 
            FROM products p 
            INNER JOIN wishlist w ON p.product_id = w.product_id 
            WHERE w.user_id = '$user_id'";
    $result = $conn->query($sql);
} else {
    // 🟠 Fetch wishlist items for guest users from session
    $guest_wishlist = $_SESSION['guest_wishlist'] ?? [];
    if (!empty($guest_wishlist)) {
        $ids = implode(",", array_map('intval', $guest_wishlist));
        $sql = "SELECT product_id, product_name, price, image_url FROM products WHERE product_id IN ($ids)";
        $result = $conn->query($sql);
    } else {
        $result = false;  // No items for guest users
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Wishlist</title>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>

body { font-family: Arial, sans-serif; background-color: #f1f5f9; }
    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
        background-color: #fff;
    }
    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #8dc641;
        color: #fff;
    }
    tr:hover { background-color: #f8f9fa; }
    td img {
        margin-right: 15px;
    }
    .add-to-cart, .remove-from-wishlist {
        border: none;
        padding: 6px 12px;
        border-radius: 50px;
        cursor: pointer;
        transition: 0.3s;
    }
    .add-to-cart { background-color: #1996b2; color: #fff; }
    .add-to-cart:hover { background-color:rgb(40, 140, 164); }
    .remove-from-wishlist { background-color:  #8dc641; color: #fff}
    .remove-from-wishlist:hover { background-color: #8dc641; }

   </style>
</head>

<body>
<div style="position: absolute; top: 5%; left:11%; margin-top:10px">
  <a href="javascript:history.back()" style="text-decoration: underline; color:#1996b2; margin-top: 35px;">&laquo;Previous</a>
  |
  <a href="home.php" style="text-decoration: underline; color:#1996b2; margin-right: 50px;">Home</a>
  
</div>

<h2 style="text-align:center; color: #1996b2;">My Wishlist</h2>

<table>
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Action</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>

            <td style="display: flex; align-items: center; justify-content: center;">
                    <img src="admin/<?= htmlspecialchars($row['image_url']); ?>" style="width:40px; height:40px; border-radius:5px; margin-right:15px;">
                    <span><?= htmlspecialchars($row['product_name']); ?></span>
                </td>
                    <td>₹<?= number_format($row['price'], 2); ?></td>
                <td>
                <button class="add-to-cart" data-id="<?= htmlspecialchars($row['product_id']) ?>">Add to Cart</button>

                    <button class="remove-from-wishlist" onclick="removeFromWishlist(<?= $row['product_id'] ?>)">Remove</button>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="3" style="text-align:center;">Your wishlist is empty!</td></tr>
    <?php endif; ?>
</table>

<script>
function removeFromWishlist(productId) {
    $.ajax({
        url: '/projectC/wishlist.php',
        type: 'POST',
        data: { product_id: productId, action: 'remove' },
        success: function() { location.reload(); }
    });
}
</script>
<script src="/projectC/cart-script.js" defer></script>
</body>
</html>







