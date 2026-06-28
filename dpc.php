<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Care Categories</title>
    <link rel="stylesheet" href="card.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            scroll-behavior: smooth;
            margin: 0;
            padding: 0;
        }

        /* Section Titles */
        .personalcare-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin: 20px;
            color: #71B02F;
            text-transform: capitalize;
            letter-spacing: 1px;
            background-color: #fff;
            padding: 10px 0;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Subcategory List */
        .subcategory-list {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .subcategory-item {
            width: 250px;
            height: 100px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background-color 0.3s ease;
            text-decoration: none;
            color: #333;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .subcategory-item:hover {
            background-color: #e0f7fa;
            transform: translateY(-3px);
        }

        .personalcare-container {
            margin-bottom: 80px;
            margin-top: 30px;
        }
        
    </style>
</head>
<body>

<?php include 'headerA.php';
// Correct path to breadcrumb.php
include 'medicine/mdsitemap.php';

// Generate breadcrumb for Personal Care (hide "Medicine" link)
generateBreadcrumb('Personal Care', false);
?>

<h2 class="personalcare-title">Popular Medicines</h2>

<div class="personalcare-container">
    <div class="subcategory-list">
        <div class="subcategory-item" onclick="scrollToSection('face-care')">
            <h3>Face Care</h3>
            <p>Includes cleansers, moisturizers, serums, face masks, and anti-aging products.</p>
        </div>
        <div class="subcategory-item" onclick="scrollToSection('body-care')">
            <h3>Body Care</h3>
            <p>Offers body lotions, washes, scrubs, and treatments for smooth skin.</p>
        </div>
        <div class="subcategory-item" onclick="scrollToSection('hair-care')">
            <h3>Hair Care</h3>
            <p>Features shampoos, conditioners, oils, and styling products.</p>
        </div>
    </div>
</div>

<!-- Sections -->
<?php 
$categories = [
    'Face Care' => 201,
    'Body Care' => 202,
    'Hair Care' => 203
];

require_once('db_connection.php');

foreach ($categories as $category => $subcategory_id) {
    echo "<div class='section' id='" . strtolower(str_replace(' ', '-', $category)) . "'>";
    echo "<h2 class='personalcare-title'>$category</h2>";

    echo "<div class='product-container'>";

    $stmt = $conn->prepare("SELECT * FROM products WHERE subcategory_id = ?");
    $stmt->bind_param("i", $subcategory_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-card" id="product-' . htmlspecialchars($row['product_id']) . '">
                    <!-- Wishlist Icon (Unfilled State) -->
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
    } else {
        echo "<p>No products available.</p>";
    }
    

    echo "</div></div>";
    $stmt->close();
}
?>



<script>
    function scrollToSection(sectionId) {
        document.getElementById(sectionId).scrollIntoView({ behavior: "smooth" });
    }

    document.addEventListener("scroll", function() {
        let sections = document.querySelectorAll(".section");
        let activeSection = "";
        
        sections.forEach(section => {
            let rect = section.getBoundingClientRect();
            if (rect.top >= 0 && rect.top < window.innerHeight / 2) {
                activeSection = section.id;
            }
        });

        console.log("Current section:", activeSection);
    });

    function addToCart(productId) {
        alert("Product " + productId + " added to cart!");
    }
</script>

</body>
<?php include 'footer.html'; ?>
</html>
