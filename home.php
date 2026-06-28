<?php
include 'header.php'; // Including the header
?>
<?php
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']); // Remove message after displaying
}
?>


<script>
    document.addEventListener("keydown", function(event) {
        if (event.shiftKey && event.code === "KeyA") {
            window.location.href = "admin/admin_login.php";
        }
    });
</script>

<!-- Slider Section -->
<div class="slider-container">
    <div class="slider">
        <div class="slide"><img src="image/b1.jpg" alt="Slide 1"></div>
        <div class="slide"><img src="image/b2.jpg" alt="Slide 2"></div>
        <div class="slide"><img src="image/b4.jpg" alt="Slide 3"></div>
        <div class="slide"><img src="image/mombaby.png" alt="Slide 4"></div>
        <div class="slide"><img src="image/b1.jpg" alt="Slide 1 Clone"></div>
    </div>
</div>

<script>
    let currentIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;
    const slider = document.querySelector('.slider');

    function moveSlide() {
        currentIndex++;
        slider.style.transition = "transform 1s ease-in-out";
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;

        if (currentIndex === totalSlides - 1) {
            setTimeout(() => {
                slider.style.transition = "none";
                currentIndex = 0;
                slider.style.transform = "translateX(0%)";
            }, 1000);
        }
    }

    function startSlider() {
        setInterval(moveSlide, 3000);
    }

    startSlider();
</script>

<!-- Best Seller Section -->
<section class="best-seller">
    <h2 class="best-seller-title">Best Seller</h2>
    <hr class="title-underline">
    <div class="product-container" style="display: grid; grid-template-columns: repeat(4, 1fr); ">
        <?php
        require_once('db_connection.php');
       
        $sql = "SELECT * FROM products ORDER BY product_id DESC LIMIT 16";


        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card" id="product-' . $row['product_id'] . '" style="text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 10px;">
                    <span class="material-icons wishlist-icon" onclick="toggleWishlist(this)" data-product-id="' . htmlspecialchars($row['product_id']) . '">favorite_border</span>
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
    </div>
</section>

<!-- Special Offers Section -->
<section class="special-offers">
    <h2>Special Offers</h2>
    <hr class="title-underline">
    <div class="offers-container">
        <div class="offer">
            <div class="offer-content">
            <h3>Buy 2 Get 1 Free on all medicine Reducers</h3>
            <p>Use Code: FREE</p>
            <a href="#" class="offer-cta">Shop Now</a>
            </div>
        </div>
        <div class="offer">
            <div class="offer-content">
                <h3>Buy 1 Get 1 Free on selected products!</h3>
                <p>Use Code: FREE</p>
                <a href="#" class="offer-cta">Shop Now</a>
            </div>
        </div>
    </div>
</section>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

.slider-container {
    width: 100%;
    height: 400px;
    overflow: hidden;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
}

.slider {
    display: flex;
    width: 500%;
    transition: transform 1s ease-in-out;
}

.slide {
    width: 100%;
    flex-shrink: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

.slide img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.best-seller {
    text-align: center;
    padding: 40px 0;
    max-width: 1200px; /* Center content */
    margin: auto;
}

.best-seller-title {
    font-size: 28px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 5px;
}

.title-underline {
    width: 80%;
    height: 5px;
    background-color: #8dc641;
    margin: 10px auto 20px auto;
}

.product-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Responsive grid */
    gap: 20px; /* Uniform spacing */
    padding: 0 40px; /* Equal space on left & right */
    justify-content: center; /* Center products */
}

.product-card {
    text-align: center;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.product-card img {
    width: 100%;
    max-width: 170px;
    height: auto;
}

.price {
    font-weight: bold;
    color: green;
    margin: 5px 0;
}

.add-to-cart {
    background-color: #8dc641;
    color: white;
    border: none;
    padding: 8px 5px;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
}

.add-to-cart:hover {
    background-color: #76a82a;
}

.special-offers {
    margin: 40px 0;
    padding: 0 20px;
    text-align: center;
}

.offers-container {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 40px;
}

.offer {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.offer:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.offer-content h3 {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.offer-content p {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
}

.offer-cta {
    background: #8dc641;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s ease;
}

.offer-cta:hover {
    background: #5a8c19;
}


</style>

<?php
include 'footer.html'; // Including the footer
?>