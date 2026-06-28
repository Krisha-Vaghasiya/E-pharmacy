<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mother & Baby Care - E-Pharmacy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="card.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            
            margin:0;
            padding:0;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
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
       .personalcare-container {
            margin-bottom: 80px;
            margin-top: 30px;
        }
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
            color: #2c3e50;
        }
        .shopping-guide {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        }
        .shopping-guide h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .guide-steps {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .step {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            width: 250px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .step:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .step p {
            margin: 0;
            font-size: 1.1rem;
            color: #555;
        }
        .split-layout {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .split-layout img {
            width: 45%;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .split-text {
            width: 50%;
        }
        .split-text h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        .split-text ul {
            list-style: none;
            padding: 0;
            margin:0;
        }
        .split-text ul li {
            display: flex;
    align-items: center;
    font-size: 1.1rem;
    color: #555;
    margin: 10px 0;
        }
        .split-text ul li::before {
            content: "✔";
    margin-right: 10px;
    color: #6A5ACD; /* Adjust color if needed */
    font-size: 1.2rem;
    flex-shrink: 0; /* Ensures the icon doesn't shrink */
    width: 20px; /* Set a fixed width for consistent alignment */
    text-align: center;
        }
        .product-finder {
            text-align: center;
            padding: 20px 10px;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            height: 30px;
        }
      
        .product-finder label {
            font-size: 1.1rem;
            color: #555;
        }
        .product-finder select {
            padding: 12px 20px;
            font-size: 1rem;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background: white;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        .product-finder select:hover {
            border-color: #2c3e50;
        }
       
        @media (max-width: 768px) {
            .split-layout {
                flex-direction: column;
                text-align: center;
                padding: 40px 20px;
            }
            .split-layout img {
                width: 80%;
                margin-bottom: 20px;
            }
            .split-text {
                width: 100%;
            }
            .guide-steps {
                flex-direction: column;
                align-items: center;
            }
        }
           

    </style>
</head>
<body>
    <?php
        include 'headerA.php';

    
include 'medicine/mdsitemap.php';
generateBreadcrumb('mother & babyCare',false);




 ?>
    <!-- Step-by-Step Shopping Guide -->
    <section class="shopping-guide">
        <h2>Find the Best for You & Your Baby</h2>
        <div class="guide-steps">
            <div class="step">
                <p>👶 Step 1: Choose a Category</p>
            </div>
            <div class="step">
                <p>🛍 Step 2: Select Essential Products</p>
            </div>
            <div class="step">
                <p>🛒 Step 3: Checkout Easily</p>
            </div>
        </div>
        <div class="product-finder">

        <label for="product-choice">Select Your Need:</label>
        <select id="product-choice" onchange="scrollToSection()">
    <optgroup label="Mother Care">
        <option value="Maternity Supplements">Maternity Supplements</option>
        <option value="Breastfeeding Essentials">Breastfeeding Essentials</option>
        <option value="Personal Care">Personal Care</option>
    </optgroup>
    <optgroup label="Baby Care">
        <option value="Baby Nutrition">Baby Nutrition</option>
        <option value="Diapers & Wipes">Diapers & Wipes</option>
        <option value="Personal Care">Personal Care</option>
    </optgroup>
</select>

    </section>
    </div>
    
    <!-- Split Layout for Products -->
    <section class="split-layout">
        <img src="image/mother and baby care.webp" alt="Mother and Baby">
        <div class="split-text">
            <h2>Best-Selling Products</h2>
            <p>Trusted by thousands of moms, our products ensure safety and comfort.</p>
            <ul>
                <li>Baby Lotion - Protects sensitive skin</li>
                <li>Feeding Bottle - BPA-free, anti-colic design</li>
                <li>Organic Baby Wipes - Gentle & chemical-free</li>
            </ul>
        </div>
    </section>
    
    <!-- Sections -->
    <?php 
$categories = [
    'Mother Care' => 401,
    'Baby Care' => 402,
];
require_once('db_connection.php');


foreach ($categories as $category => $subcategory_id) {
    $sectionId = strtolower(str_replace(' ', '-', $category)); // Ensure ID matches optgroup label
    echo "<div class='section' id='$sectionId'>";
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
   function scrollToSection() {
    let select = document.getElementById("product-choice");
    let selectedOption = select.options[select.selectedIndex];

    // Find the optgroup of the selected option
    let optgroup = selectedOption.parentElement.label;

    // Convert optgroup name to a valid section ID format
    let sectionId = optgroup.toLowerCase().replace(/\s+/g, '-');

    let section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: "smooth" });
    } else {
        alert("Section not found!");
    }
}


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