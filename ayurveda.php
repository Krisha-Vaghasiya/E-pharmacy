<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayurveda Medicines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Material+Icons+Outlined&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="card.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .intro-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 50px;
            background-color: #e6f4ea;
        }
        .intro-text {
            width: 50%;
        }
        .intro-text h1 {
            font-size: 2.5rem;
            color: #2d6a4f;
        }
        .intro-text p {
            font-size: 1.2rem;
            color: #555;
        }
        .intro-image img {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .benefits {
            text-align: center;
            padding: 40px 20px;
            background-color: #f0fff4;
        }
        .benefit-list {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top:15px;
        }
        .benefit-item {
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            text-align: center;
            width: 200px;
        }
        .benefit-item .material-icons-outlined {
            font-size: 30px;
            color: #2d6a4f;
            margin-bottom: 5px;
        }
        .benefit-item h3 {
            font-size: 1.1rem;
            margin: 5px 0;
        }
        .benefit-item p {
            font-size: 0.9rem;
            color: #555;
        }
       
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .testimonials {
            text-align: center;
            padding: 20px 5px;
            background-color: #e0f7fa;
            font-style: italic;
            font-size: 1rem;
            color: #2d6a4f;
            font-weight: 500;
            
        }
    .popular-medicines {
    padding: 20px 20px;
    text-align: center;
    background-color:#fff;
}

.section-title {
    font-size: 2rem;
    color:  #8dc641;
    margin-bottom: 30px;
    
    
}
.ayurveda-grid{
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
    margin-left: 110px;
    margin-right: 110px;
}

   

        
    </style>
</head>
<body>
    <?php
        include 'headerA.php';
        include 'medicine/mdsitemap.php';
generateBreadcrumb('Aayurvaida',false);
    ?>
    <!-- Intro Section -->
    <section class="intro-section">
        <div class="intro-text">
            <h1>Discover the Power of Ayurveda</h1>
            <p>Experience natural healing with Ayurveda, using herbal remedies for a healthier life.</p>
            <a href="#products" class="btn" onclick="scrollToSection('featured')">Explore Now</a>
        </div>
        <div class="intro-image">
            <img src="image/a.jpg" alt="Ayurvedic Ingredients">
        </div>
    </section>
    
    <!-- Benefits Section -->
    <section class="benefits">
        <h2>Why Choose Ayurveda?</h2>
        <div class="benefit-list">
            <div class="benefit-item">
                <span class="material-icons-outlined">spa</span>
                <h3>100% Natural</h3>
                <p>No chemicals, only herbs.</p>
            </div>
            <div class="benefit-item">
                <span class="material-icons-outlined">self_improvement</span>
                <h3>Holistic Healing</h3>
                <p>Treats the root cause, not just symptoms.</p>
            </div>
            <div class="benefit-item">
                <span class="material-icons-outlined">science</span>
                <h3>Scientifically Proven</h3>
                <p>Backed by research & Ayurveda principles.</p>
            </div>
        </div>
    </section>
    
    <!-- Products Section -->
      
  <!-- Popular Medicines Section -->
<section id="featured" class="popular-medicines">
    <h2 class="section-title">Featured Ayurvedic Medicines</h2>
    <div class="ayurveda-grid">
        <?php
        require_once('db_connection.php');
        $sql = "SELECT * FROM products WHERE category_id = 3";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Product Card
                echo '<div class="product-card" id="product-' . htmlspecialchars($row['product_id']) . '">
                            <!-- Wishlist Icon -->
                            <span class="material-icons wishlist-icon" onclick="toggleWishlist(this)" data-product-id="' . htmlspecialchars($row['product_id']) . '">
                                favorite_border
                            </span>

                           <!-- Product Image (Clickable) -->
                            <a href="product_details.php?id=' . htmlspecialchars($row['product_id']) . '">
                                <img src="admin/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['product_name']) . '" width="170" height="170">
                            </a>

                            <!-- Product Name (Non-clickable) -->
                            <h3>' . htmlspecialchars($row['product_name']) . '</h3>

                            <!-- Product Price (Non-clickable) -->
                            <p class="price">₹ ' . number_format($row['price'], 2) . '</p>
                            
                            <!-- Add to Cart Button (Non-clickable) -->
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
  
    <!-- Testimonials Section -->
    <section class="testimonials">
        <h2>What Our Customers Say</h2>
        <p style="margin-top:10px">"Ayurvedic medicines transformed my health! Highly recommended." - Rahul S.</p>
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
            include 'footer.html';
?>
 
   
</html>
