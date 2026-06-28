<?php
        include '../headerA.php';
        include 'mdsitemap.php'; 
    
generateBreadcrumb('mdc medicine', true); // Show Medicine link
//
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chronic Disease Care</title>
<!-- Link to CSS -->
<link rel="stylesheet" href="mdcstyle.css">
<link rel="stylesheet" href="../card.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">


</head>

<body>
    
<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">Manage Chronic Diseases with Trusted Medicines</h1>
        <p class="hero-subtitle">Explore our range of medicines for diabetes, hypertension, asthma, and more.</p>
        <button class="cta-button" onclick="scrollToSection('top-picks')">Shop Now</button>

    </div>
</section>


<!-- Popular Medicines Section -->
<section id="top-picks" class="popular-medicines">

    <h2 class="section-title">Our Top Picks</h2>
    <div class="medicine-grid">
    <?php
        require_once '../db_connection.php';
        $sql = "SELECT * FROM products where subcategory_id = 101";
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
</section>




<!-- Offers & Discounts for Chronic Diseases -->
<section class="offers-section">
    <h2 class="section-title" style="color:#00796b;">Special Offers for Chronic Disease Care</h2>
    <div class="offer-grid">
        <!-- Offer for Diabetes Care -->
        <div class="offer-card">
            <h3>20% Off on Diabetes Care Medicines</h3>
            <p>Use Code: DIAB20</p>
            <button class="offer-btn" onclick="scrollToSection('top-picks')">Shop Now</button>
        </div>
        <!-- Offer for Hypertension Care -->
        <div class="offer-card">
            <h3>15% Off on Hypertension Control</h3>
            <p>Use Code: HYPER15</p>
            <button class="offer-btn" onclick="scrollToSection('top-picks')">Shop Now</button>
        </div>
        <!-- Offer for Asthma Care -->
        <div class="offer-card">
            <h3>Buy 1 Get 1 Free on Asthma Inhalers</h3>
            <p>Use Code: ASTHMAFREE</p>
            <button class="offer-btn" onclick="scrollToSection('top-picks')">Shop Now</button>
        </div>
    </div>
</section>




<!-- Link to JavaScript -->
 <script>// Smooth scroll to the categories section
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
