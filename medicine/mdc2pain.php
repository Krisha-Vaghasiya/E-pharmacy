<?php
    include '../headerA.php';
    include 'mdsitemap.php'; 
 generateBreadcrumb('Pain Relief & Fever Medicines',true);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pain Relief & Fever Medicines</title>
<link rel="stylesheet" href="mdcstyle.css">
<link rel="stylesheet" href="../card.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

</head>
<body>

<!-- Hero Section -->
<section class="hero">
<div class="hero-content">
    <h1 class="hero-title">Pain Relief & Fever Medicines</h1>
    <p class="hero-subtitle">Explore a variety of pain relievers reducers and trusted medicines for pain relief  including tablets and sprays for body pain, and inflammation management</p>
    <button class="cta-button" onclick="scrollToSection('top-picks')">Shop Now</button>
</section>

<!-- Product Grid Section -->
<section id="top-picks" class="popular-medicines">
    <h2 class="section-title">Our Top Picks</h2>
    <div class="medicine-grid">
    <?php
        require_once '../db_connection.php';
        $sql = "SELECT * FROM products where subcategory_id = 102";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card" id="product-' . $row['product_id'] . '">
         <!-- Wishlist Icon (Unfilled State) -->
        <span class="material-icons wishlist-icon" onclick="toggleWishlist(this)" data-product-id="' . htmlspecialchars($row['product_id']) . '">favorite_border</span>
        <!-- Product Image (Clickable) -->
                            <a href="/projectC/product_details.php?id=' . htmlspecialchars($row['product_id']) . '">
                                <img src="../admin/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['product_name']) . '" width="170" height="170">
                            </a>

        <h3>' . htmlspecialchars($row['product_name']) . '</h3>
        <p class="price">₹ ' . number_format($row['price'], 2) . '</p>
         <button class="add-to-cart" data-id="' . htmlspecialchars($row['product_id']) . '">Add to Cart</button>

      </div>';

             
            }
        } else {
            echo "<p>No products available.</p>";
        }
        $conn->close();
        ?>
        </div>
    </div>
</section>


<!-- Offer Section -->
<section class="offers-section">
    <h2 class="section-title" style="color:#00796b;">Exclusive Offers on Pain Relief</h2>
    <div class="offer-grid">
        <div class="offer-card">
            <h3>Flat 25% Off on Pain Relievers</h3>
            <p>Use Code: PAIN25</p>
            <button class="offer-btn">Shop Now</button>
        </div>
        <div class="offer-card">
            <h3>Buy 2 Get 1 Free on Fever Reducers</h3>
            <p>Use Code: FEVERFREE</p>
            <button class="offer-btn">Shop Now</button>
        </div>
    </div>
</section>


<!-- Smooth Scroll Script -->
<script>
    function scrollToSection(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    }
</script>

</body>

<?php
        include '../footer.html';
    ?>
    
</html>








