<?php
        include '../headerA.php';
        include 'mdsitemap.php'; 
generateBreadcrumb('Gastrointestinal Medicines',true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gastrointestinal Medicines</title>
<link rel="stylesheet" href="mdcstyle.css">
<link rel="stylesheet" href="../card.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

<style>
    .suggested-medicines {
        background-color: #e0f7fa;
        padding: 20px;
        border-radius: 10px;
        margin: 30px 0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    .suggestions-list {
        list-style-type: none;
        padding: 0;
        display: inline-block;
        text-align: left;
    }
    .suggestions-list li {
        background-color:#ffff;
        margin-bottom: 10px;
        padding: 5px;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        font-size: 14px;
        letter-spacing: 0.5px;
        line-height: 1.5;
        margin-bottom: 20px;
    }
    .section-title {
    font-size: 2rem;
    color: #80c328;
    
}


    .suggestions-list li:before {
        content: "💊";
        margin-right: 10px;
    }

    #suggetion{
        color:#00796b;

    }
</style>
</head>
<body>

<!-- Hero Section -->
<section class="hero">
<div class="hero-content">
    <h1 class="hero-title">Gastrointestinal Medicines</h1>
    <p class="hero-subtitle">Explore a range of antacids, laxatives, probiotics, and anti-diarrheal medicines to manage digestive discomfort effectively.</p>
    <button class="cta-button" onclick="scrollToSection('top-picks')">Shop Now</button>
</div>
</section>

<!-- Product Grid Section -->
<section id="top-picks" class="popular-medicines">
    <h2 class="section-title">Popular Gastrointestinal Medicines</h2>
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

<!-- Suggested Medicines for Conditions Section -->
<section class="suggested-medicines">
    <h2 class="section-title" style="color:#00796b;">Suggested Medicines for Common Conditions</h2>
    <ul class="suggestions-list">
        <li><strong>For Acid Reflux : </strong> Antacids and proton pump inhibitors can provide quick relief.</li>
        <li><strong>For Constipation : </strong> Laxatives and fiber supplements help regulate bowel movements.</li>
        <li><strong>For Diarrhea : </strong> Anti-diarrheal medicines like loperamide are effective.</li>
        <li><strong>For Bloating : </strong> Probiotics and digestive enzymes can ease symptoms.</li>
    </ul>
</section>

<?php include '../footer.html'; ?>

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
</html>