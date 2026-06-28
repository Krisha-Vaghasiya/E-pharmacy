<?php include 'headerA.php'; ?>

<?php
$conn = new mysqli("localhost", "root", "", "e_pharmacy");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$query = isset($_GET['query']) ? trim($_GET['query']) : '';

$sql = "SELECT product_id AS product_id, product_name, brand, price, description, image_url 
        FROM products 
        WHERE product_name LIKE '%$query%' 
        OR brand LIKE '%$query%' 
        OR description LIKE '%$query%'";

$result = $conn->query($sql);

echo "<h2 style='margin-top:30px;margin-bottom:30px;color: #8dc641'>Search Results for '$query'</h2>";

if ($result->num_rows > 0) {
    echo "<div class='product-container' style='display: flex; flex-wrap: wrap; gap: 20px;'>";

    while ($row = $result->fetch_assoc()) {
        echo '<div class="product-card" id="product-' . htmlspecialchars($row['product_id']) . '">
                <!-- Wishlist Icon -->
                <span class="material-icons wishlist-icon" onclick="toggleWishlist(this)" data-product-id="' . htmlspecialchars($row['product_id']) . '">favorite_border</span>
                
                <!-- Product Image (Clickable) -->
                <a href="product_details.php?id=' . htmlspecialchars($row['product_id']) . '">
                    <img src="admin/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['product_name']) . '" width="170" height="170">
                </a>

                <h3>' . htmlspecialchars($row['product_name']) . '</h3>
                <p class="price">₹ ' . number_format($row['price'], 2) . '</p>

                <button class="add-to-cart" data-id="' . htmlspecialchars($row['product_id']) . '">Add to Cart</button>
              </div>';
    }

    echo "</div>"; // Close product container
} else {
    echo "<p >No products found matching your search.</p>";
}
?>
