<?php include '../headerA.php'; 
include 'mdsitemap.php'; 
generateBreadcrumb('Eye and Ear Medicine',true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eye and Ear Medicines</title>
<link rel="stylesheet" href="mdcstyle.css">
<link rel="stylesheet" href="../card.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
<style>
    .care-tips {
        background-color: #e0f7fa;
        padding: 30px;
        border-radius: 10px;
        margin: 40px 0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    .care-tips h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #00796b;
    }
    .care-tips ul {
        list-style-type: none;
        padding: 0;
        text-align: center;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap:0px;
        justify-content: center;
    }
    .care-tips ul li {
        background-color: #fff;
        padding: 10px 20px 0px 20px;
        width: 270px;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        font-size: 14px;
        letter-spacing: 0.5px;
        line-height: 1.8;
        text-align: center;
        margin-left:40px;
       

    }
</style>
</head>
<body>



<!-- Hero Section -->
<section class="hero">
<div class="hero-content">
    <h1 class="hero-title">Eye and Ear Medicines</h1>
    <p class="hero-subtitle">Discover a variety of treatments for eye and ear health, including drops for infections, dryness, and allergies.</p>
    <button class="cta-button" onclick="scrollToSection('top-picks')">Shop Now</button>
</div>
</section>
<section id="top-picks" class="popular-medicines">

    <h2 class="section-title"> Explore the Best in Eye & Ear Care</h2>
    <div class="medicine-grid">
    <?php
        require_once '../db_connection.php';
        $sql = "SELECT * FROM products where subcategory_id = 105";
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


<!-- Care Tips Section (Updated to Block) -->
<section class="care-tips">
<h2>Recommended Products for Eye and Ear Care</h2>
    <ul>
    <li><strong>Lubricating Eye Drops:</strong> Ideal for relieving dryness and irritation caused by screens or contact lenses.</li>
    <li><strong>Antibiotic Eye Ointment:</strong> Effective for treating bacterial eye infections with soothing relief.</li>
    <li><strong>Ear Wax Removal Drops:</strong> Helps dissolve excess wax safely without irritation.</li>
    <li><strong>Anti-fungal Ear Drops:</strong> Suitable for managing infections caused by fungi, reducing itching and discomfort.</li>
</ul>
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

<?php include '../footer.html'; ?>


</html>
