<?php
include 'db_connection.php'; // Ensure this file correctly connects to the database

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    
    <!-- Link Google Icons for Wishlist -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Link External CSS File -->
    <link rel="stylesheet" href="card.css"> 

</head>
<body>

    <div class="product-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card" id="product-' . $row['product_id'] . '">
                        <span class="material-icons wishlist">favorite_border</span>
                        <img src="' . $row['image_url'] . '" alt="' . htmlspecialchars($row['product_name']) . '">
                        <h3>' . htmlspecialchars($row['product_name']) . '</h3>
                        <p class="price">₹ ' . number_format($row['price'], 2) . '</p>
                        <button class="add-to-cart" onclick="addToCart(' . $row['product_id'] . ')">ADD TO CART</button>
                      </div>';
            }
        } else {
            echo "<p>No products available.</p>";
        }
        $conn->close();
        ?>
    </div>

</body>
</html>
