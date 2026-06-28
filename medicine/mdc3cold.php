

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cold, Cough & Allergy Relief</title>
<link rel="stylesheet" href="mdcstyle.css">
<link rel="stylesheet" href="../card.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
</head>
<body>
<?php
        include '../headerA.php';
        include 'mdsitemap.php'; 
 generateBreadcrumb('Cold, Cough & Allergy Relief',true);
    ?>
    
<!-- Hero Section -->
<section class="hero">
<div class="hero-content">
    <h1 class="hero-title">Cold, Cough & Allergy Relief</h1>
    <p class="hero-subtitle"><p class="hero-subtitle">Discover effective solutions for cold, cough, and allergy relief, including syrups, tablets, and nasal sprays for congestion, sore throat, and sneezing management.</p>
    .</p>

    <button class="cta-button" onclick="scrollToSection('top-picks')">Shop Now</button>
</section>

<!-- Product Grid Section -->
<section id="top-picks" class="popular-medicines">
    <h2 class="section-title">Popular Remedies</h2>
    <div class="medicine-grid">
    <?php
        require_once '../db_connection.php';
        $sql = "SELECT * FROM products where subcategory_id = 103";
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

<!-- New Section: Tips for Managing Cold & Allergy -->
<section class="offers-section">
    <h2 class="section-title" style="color:#00796b;">Tips for Managing Cold & Allergy</h2>
    <div class="offer-grid">
        <div class="offer-card">
            <h3>Stay Hydrated</h3>
            <p>Drink warm fluids to soothe your throat and prevent dehydration.</p>
        </div>
        <div class="offer-card">
            <h3>Use Saline Nasal Sprays</h3>
            <p>Clear nasal congestion effectively with natural saline sprays.</p>
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

    <?php
    include '../footer.html';
    ?>

</body>
</html>
