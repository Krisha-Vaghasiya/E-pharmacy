<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daily Care</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Material+Icons+Outlined&display=swap" rel="stylesheet">
<link rel="stylesheet" href="card.css">
<style>
    body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0; }
   /* header { color: #fff; text-align: center; padding:0px 0px; box-shadow: 0 2px 5px rgba(221, 225, 214, 0.1); }*/
   .hero { background: url('/projectC/image/bgdaily.png') no-repeat center center/cover;  height:400px; padding: 80px 20px; text-align: center; position: relative; color: #fff; animation: fadeIn 2s ease-in-out; }
   /* .hero { background-image: url('image/bgdaily.png'); background-size: cover; background-position: center; }*/
    .hero::after { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;   background: rgba(127, 190, 204, 0.5); z-index: 0; }
    .hero h1, .hero p, .hero a { position: relative; z-index: 1; opacity: 0.9; }
    .hero h1 { font-size: 2.5rem; margin: 0; padding:20px;color:#8dc641;  animation: slideDown 1s ease-out; }
    .hero p { font-size: 16px; margin: 10px 0 100px; animation: fadeIn 2s ease-in-out; color:rgb(15, 84, 105); }
    .hero a { background-color:  #8dc641; color: #fff; padding: 5px 8px; border-radius: 5px; text-decoration: none; transition: background-color 0.3s; animation: fadeIn 2s ease-in-out; }
    .hero a:hover { background-color: #167b99; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .grid { display: grid; grid-template-columns: 1fr; gap: 20px; margin-top: 20px; width: 50%; margin-left: auto; margin-right: auto; }
    .grid-item { background-color: #fff; padding: 15px; border: 1px solid #1996b2; border-radius: 10px; transition: transform 0.3s; }
    .grid-item:hover { transform: translateY(-5px); }
    .grid-item h3 { margin: 0 0 10px; color: #1996b2; text-align: center; }
    .grid-item p { color: #555; text-align:center; font-size: 14px; }
    .product-section { display: flex; justify-content: center; gap: 20px; margin: 40px 0; margin-right:110px; margin-left:110px }
       




    /* Limited-Time Offer Section Styles */
.offer-section {
    background-color: #f0f8ff;
    padding: 30px 20px;
    text-align: center;
    border-top: 2px solid #167b99;
}

.offer-section h2 {
    color: #167b99;
    margin-bottom: 10px;
}

.offer-section p {
    color: #333;
    margin-bottom: 20px;
}

.countdown-timer {
    font-size: 24px;
    color: #167b99;
    margin-bottom: 20px;
}

.shop-now-btn {
    background-color: #8dc641;
    color: #fff;
    padding: 5px 5px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.shop-now-btn:hover {
    background-color: #167b99;
}
  

</style>
</head>
<body>
<header>
   <?php
    include 'headerA.php';

    ?>
</header>


<?php
include 'medicine/mdsitemap.php';
generateBreadcrumb('Daily Care',false);
?>




<section class="hero">
    <h1>Welcome to Daily Care</h1>
    <p>Your one-stop solution for all personal & Daily care needs.</p>
    <a href="#products">Explore Now</a>
</section>
<div class="container" id="products">
    <h2 style="text-align: center; margin-bottom: 30px; margin-top:30px; color: #8dc641;">Our Daily Essentials Collection</h2>
    <div class="grid">
        <div class="grid-item">
            <h3>Discover the Best for You</h3>
            <p>Our daily essentials include a wide variety of products to cater to your everyday needs. From essential grooming items to wellness products, we offer a carefully curated selection to help you feel your best every day.</p>
        </div>
    </div>
</div>
<section id="products" class="product-section">
<?php
    require_once('db_connection.php');
    $sql = "SELECT * FROM products where category_id = 5";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-card" id="product-' . $row['product_id'] . '">
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
    $conn->close();
    ?>


</section>


<!-- Limited-Time Offer Section -->
<section class="offer-section">
    <h2>Limited-Time Offer!</h2>
    <p>Get up to 30% off on selected Daily Care products. Hurry up!</p>
    <div id="countdown" class="countdown-timer"></div>
    <a href="#products" class="shop-now-btn">Shop Now</a>
</section>

<script>

            // Countdown Timer Script
const countdown = document.getElementById('countdown');
const endTime = new Date(new Date().getTime() + 48 * 60 * 60 * 1000); // 48 hours from now

function updateCountdown() {
    const now = new Date();
    const timeLeft = endTime - now;
    if (timeLeft <= 0) {
        countdown.textContent = "Offer Expired!";
    } else {
        const hours = Math.floor((timeLeft / (1000 * 60 * 60)) % 24);
        const minutes = Math.floor((timeLeft / (1000 * 60)) % 60);
        const seconds = Math.floor((timeLeft / 1000) % 60);
        countdown.textContent = Hurry! ${hours}h ${minutes}m ${seconds}s left;
    }
}

setInterval(updateCountdown, 1000);


// Smooth Scroll for Shop Now Button
document.querySelector('.shop-now-btn').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default anchor click behavior
    const target = document.querySelector('#products');
    target.scrollIntoView({ behavior: 'smooth' }); // Smooth scroll to products section
});



</script>

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