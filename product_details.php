<?php
session_start();  // Ensure session is started at the very top
include 'db_connection.php';


// Check if 'id' parameter is set in URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Fetch product details based on ID
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid product ID.";
    exit;
}



$isInWishlist = false;

// Check if product ID is set and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];
} else {
    echo "Invalid product ID!";
    exit;
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Use prepared statements to check if product is in wishlist
    $wishlist_check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
    $wishlist_check->bind_param("ii", $user_id, $product_id);
    $wishlist_check->execute();
    $wishlist_result = $wishlist_check->get_result();

    if (mysqli_num_rows($wishlist_result) > 0) {
        $isInWishlist = true;
    }
}

$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    // Fetch cart count from database
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT SUM(quantity) AS cart_count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cart_count = $row['cart_count'] ?? 0;
} else {
    // Get count from session cart
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            if (is_array($item) && isset($item['quantity'])) { // Ensure it's an array
                $cart_count += $item['quantity'];
            }
        }
    }   
}


// Fetch average rating for the product
$rating_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = ?");
$rating_stmt->bind_param("i", $product_id);
$rating_stmt->execute();
$rating_result = $rating_stmt->get_result();
$avg_rating = $rating_result->fetch_assoc()['avg_rating'] ?? 0;





// Use prepared statements to prevent SQL injection for product query
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// If product is not found, show a message
if (!$product) {
    echo "<p>Product not found!</p>";
    exit;
}



// Fetch reviews for the product
$review_query = "SELECT r.rating, r.comment, r.review_date, CONCAT(u.first_name, ' ', u.last_name) AS full_name
                 FROM reviews r 
                 JOIN users u ON r.user_id = u.user_id 
                 WHERE r.product_id = ?";
$review_stmt = $conn->prepare($review_query);
$review_stmt->bind_param("i", $product_id);
$review_stmt->execute();
$review_result = $review_stmt->get_result();



?>

<!-- Floating message for not logged in users -->
<?php if (isset($_GET['error']) && $_GET['error'] == 'not_logged_in'):?>
    <div id="login-message" style="position: fixed; bottom: 20px; right: -300px; background-color: #f44336; color: #fff; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); z-index: 1000; transition: right 0.5s ease-in-out;">
    Please log in to submit a review.
</div>
<script>
   /* setTimeout(() => document.getElementById('login-message').style.display = 'none', 3000);*/
   const message = document.getElementById('login-message');
    setTimeout(() => message.style.right = '20px', 100);
    setTimeout(() => message.style.right = '-300px', 2500);
    setTimeout(() => message.style.display = 'none', 3000);
</script>
<?php endif; ?>


<!-- Floating message for successful review submission -->
<?php if (isset($_GET['success']) && $_GET['success'] == 'review_added'): ?>
<div id="success-message" style="position: fixed; bottom: 20px; right: -300px; background-color: #4CAF50; color: #fff; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); z-index: 1000; transition: right 0.5s ease-in-out;">
    Review submitted successfully!
</div>
<script>
    const successMessage = document.getElementById('success-message');
    setTimeout(() => successMessage.style.right = '20px', 100);
    setTimeout(() => successMessage.style.right = '-300px', 2000);
    setTimeout(() => successMessage.style.display = 'none', 2500);
</script>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['product_name']); ?> - E-Pharmacy</title>


    <style>

         /* General Page Styles */
         body {
            font-family: Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

       .product-details-container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .image-section img {
            width: 200px;
            height:200px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin-top:20px;
        }

        .image-section img:hover {
            transform: scale(1.05);
        }

        .details-section h1 {
            color: #1996b2;
            /*color: #fff;*/
        }

        
        .related-products,.reviews-section {
            margin-top: 30px;
        }



        .product-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 35px;
    justify-content: center;
}
.product-container {
    display: flex;
    gap: 20px;
    padding: 10px;
    overflow-x: auto;  /* Enables smooth horizontal scrolling */
    scroll-behavior: smooth;  /* Adds smooth scrolling effect */
    white-space: nowrap;
}

/* Custom scrollbar for WebKit browsers (Chrome, Safari) */
.product-container::-webkit-scrollbar {
    height: 8px;  /* Adjust scrollbar height */
}

.product-container::-webkit-scrollbar-thumb {
    background-color: #1996b2;  /* Scrollbar thumb color */
    border-radius: 4px;
}

.product-container::-webkit-scrollbar-track {
    background-color: #f1f5f9;  /* Scrollbar track color */
}


.product-card {
    display: inline-block;
    width: 230px;
    text-align: center;
    padding: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background: #fff;
    position: relative;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.3s ease-in-out;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.product-card img {
    width: 150px;
    max-height: 140px;
    object-fit: contain;
    border-radius: 2px;
    margin-top: 12px;
    transition: transform 0.3s ease;
}

.product-card img:hover {
    transform: scale(1.05);
}

.product-card h3 {
    
    color: #fff;
    font-size: 14px;
    margin: 2px 0;
    font-weight: bold;
}

.price {
    font-size: 14px;
    color: green;
    font-weight: bold;
    margin: 3px 0;
    
}

.add-to-cart {
    background: #8dc641;
    color: #fff;
    
    border: none;
    padding: 6px 20px 6px 20px ;
    border-radius: 5px;
    cursor: pointer; 
    font-size: 12px;
}/*
    margin-top: auto;
    width: 100px;
    height: 30px;
    transition: background-color 0.3s;
}

.add-to-cart:hover {
    background-color:rgb(123, 189, 36);
}
*/
.add-to-wishlist-btn {
            background-color:#8dc641;
    color: #fff;
    
    border: none;
    padding: 6px 10px;
    border-radius: 5px;
    cursor: pointer; 
    font-size: 12px;
}
        /* Container Styles */
.review-form-container {
    margin: 30px auto;
    max-width: 500px;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Heading Styles */
.review-form-container h4 {
    color: #8dc641;
    
    text-align: center;
    margin-bottom: 20px;
}

/* Label Styles */
.review-form-container label {
    font-weight: bold;
    color: #1996b2;
    margin-top: 10px;
    display: block;
}

/* Input, Textarea, and Select Styles */
.review-form-container select,
.review-form-container textarea,
.review-form-container button {
    width: 100%;
    margin-top: 5px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    font-size: 14px;
}

/* Placeholder Styles */
.review-form-container textarea::placeholder {
    color: #aaa;
    font-style: italic;
}

/* Submit Button Styles */
.review-form-container button {
    background-color: #8dc641;
    color: #fff;
    transition: background-color 0.3s ease, transform 0.3s ease;
    margin-top: 15px;
    cursor: pointer;
    border: none;
}

.review-form-container button:hover {
    background-color: #6fa52a;
    transform: scale(1.05);
}

/* Focus Effect */
.review-form-container select:focus,
.review-form-container textarea:focus {
    border-color: #1996b2;
    outline: none;
    box-shadow: 0 0 5px rgba(25, 150, 178, 0.5);
}

        .rating-stars { color: #FFD700; }
.review { margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
.review strong { color: #1996b2; }
.review small { color: #888; }
.show-more { color: #1996b2; cursor: pointer; text-decoration: underline; }
.hidden { display: none; }
.star { color: #FFD700; }



.toggle-btn {
        background-color: #1996b2;
        color: #fff;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
        margin-top: 10px;
    }

    .toggle-btn:hover {
        background-color: #177fa0;
        transform: scale(1.05);
    }


    




    </style>

</head>

<body>

<div class="container">


 

<div class="product-details-container">
    <div class="image-section">
        <img src="admin/<?= htmlspecialchars($product['image_url']); ?>" alt="<?= htmlspecialchars($product['product_name']); ?>">
    </div>


   


    <div class="details-section">
        <h1><?= htmlspecialchars($product['product_name']); ?></h1>
        <p>Price: ₹<?= number_format($product['price'], 2); ?></p>
        <p>Description: <?= nl2br(htmlspecialchars($product['description'])); ?></p>
      
<button class="add-to-cart" data-id="<?= htmlspecialchars($product['product_id']); ?>">Add to Cart</button>
 
        <button class="add-to-wishlist-btn <?= $isInWishlist ? 'filled' : ''; ?>" onclick="toggleWishlistDetail(this)" data-product-id="<?= $product['product_id']; ?>">

        <span class="material-icons">
        <?= $isInWishlist ? 'favorite' : 'favorite_border'; ?>
        </span> <?= $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>
        </button>
    </div>

    <div style="position: absolute; top: 15%; right:20%;">
  <a href="javascript:history.back()" style="text-decoration: underline; color:#1996b2; margin-right: 15px;">&laquo;Previous</a>
  |
  <a href="home.php" style="text-decoration: underline; color:#1996b2; margin-right: 50px;">Home</a>
  
</div>
    


    <!--  <a href="javascript:history.forward()" style="text-decoration: none; color: #1996b2;">Next &raquo;</a>
</div>-->

</div>


<script>
function toggleReviews() {
    const reviews = document.querySelectorAll('.review');
    const btn = document.getElementById('toggle-btn');

    if (btn.textContent === 'Show All Reviews') {
        reviews.forEach((review, index) => {
            if (index >= 3) review.classList.remove('hidden');
        });
        btn.textContent = 'Show Less';
    } else {
        reviews.forEach((review, index) => {
            if (index >= 3) review.classList.add('hidden');
        });
        btn.textContent = 'Show All Reviews';
    }
}
</script>

<script>
function toggleWishlistDetail(button) {
    const productId = button.getAttribute('data-product-id');
    const icon = button.querySelector('.material-icons');

    if (icon.textContent === 'favorite_border') {
        icon.textContent = 'favorite';
        button.classList.add('filled');
        addToWishlist(productId);
    } else {
        icon.textContent = 'favorite_border';
        button.classList.remove('filled');
        removeFromWishlist(productId);
    }
}
</script>

<?php
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ensure product ID is valid
if ($product_id > 0) {
    // Fetch reviews with full name for the specific product
    $review_query = "SELECT r.rating, r.comment, r.review_date, CONCAT(u.first_name, ' ', u.last_name) AS full_name
                     FROM reviews r 
                     JOIN users u ON r.user_id = u.user_id 
                     WHERE r.product_id = ?";
    $review_stmt = $conn->prepare($review_query);
    $review_stmt->bind_param("i", $product_id);
    $review_stmt->execute();
    $review_result = $review_stmt->get_result();
} else {
    echo "Invalid product ID.";
}
?>


<?php
// Fetch category_id and subcategory_id from the current product
$category_id = $product['category_id'];
$subcategory_id = $product['subcategory_id'];  // Assuming you have subcategory_id in the product array

// Prepare statement to fetch related products from the same category and subcategory
$related_stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND subcategory_id = ? AND product_id != ? LIMIT 4");
$related_stmt->bind_param("iii", $category_id, $subcategory_id, $product_id);
$related_stmt->execute();
$related_result = $related_stmt->get_result();
?>



<div class="reviews-section">
    <h3>Customer Reviews</h3>
    <?php $count = 0; ?>
    <?php if (mysqli_num_rows($review_result) > 0): ?>
        <?php while ($review = mysqli_fetch_assoc($review_result)): ?>
            <div class="review <?= $count >= 3 ? 'hidden' : ''; ?>">
                <strong><?= htmlspecialchars($review['full_name']); ?></strong> -
                <?php
                    $rating = (int)$review['rating'];
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $rating 
                            ? '<span class="star">★</span>' 
                            : '<span class="star">☆</span>';
                    }
                ?>
                <p><?= htmlspecialchars($review['comment']); ?></p>
                <small><?= htmlspecialchars($review['review_date']); ?></small>
            </div>
            <?php $count++; ?>
        <?php endwhile; ?>
        <?php if ($count > 3): ?>
            <p id="toggle-btn" class="show-more" onclick="toggleReviews()">Show All Reviews</p>
        <?php endif; ?>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>
</div>

<button class="toggle-btn" type="button" onclick="toggleForm()">Submit Your Review</button> <!-- Toggle Button with type="button" -->
<div class="review-form-container" id="review-form" style="display: none;">
<h4>submit your review</h4> 
    
    <form action="submit_review.php" method="post">
        <input type="hidden" name="product_id" value="<?= $product_id; ?>">

        <label for="rating">Rating:</label>
        <select name="rating" id="rating" required>
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Good</option>
            <option value="3">3 - Average</option>
            <option value="2">2 - Poor</option>
            <option value="1">1 - Terrible</option>
        </select>

        <label for="comment">Comment:</label>
        <textarea name="comment" rows="3" required></textarea>

        <button type="submit">Submit Review</button> <!-- Form Submit Button -->
    </form>
</div>


  
<!-- JavaScript for Toggle Functionality -->
<script>
    function toggleForm() {
        const formContainer = document.getElementById('review-form');
        const toggleBtn = document.querySelector('.toggle-btn');
        
        // Toggle visibility
        if (formContainer.style.display === 'none' || formContainer.style.display === '') {
            formContainer.style.display = 'block';
            toggleBtn.textContent = 'Hide Review Form'; // Change button text
        } else {
            formContainer.style.display = 'none';
            toggleBtn.textContent = 'Submit Your Review'; // Revert button text
        }
    }
</script>




<div class="related-products">
    <h2>Related Products</h2>
    <!-- Added ID to this div for scrolling to work -->
    <div class="product-container" id="related-products-container"> 
        <?php while ($related = $related_result->fetch_assoc()) { ?>
            <div class="product-card">
                <a href="product_details.php?id=<?= htmlspecialchars($related['product_id']); ?>">
                    <img src="admin/<?= htmlspecialchars($related['image_url']); ?>" alt="<?= htmlspecialchars($related['product_name']); ?>">
                </a>
                <h3><?= htmlspecialchars($related['product_name']); ?></h3>
                <p class="price">₹<?= number_format($related['price'], 2); ?></p>
                <button class="add-to-cart" data-id="<?= htmlspecialchars($product['product_id']); ?>">Add to Cart</button>
                <!-- Wishlist Icon -->
                <!--<span class="wishlist">&#9825;</span>  Heart symbol for wishlist -->
            </div>
        <?php } ?>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is included -->
<script src="wishlist.js"></script>

<script src="/projectC/cart-script.js" defer></script>


</body>
</html>