<?php
    include 'headerA.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - E-Pharmacy</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body { font-family: 'Poppins', sans-serif;  background-color: #f7f7f7; }
    h2 { color: #1996b2; text-align: center; }
   

.about-section {
            position: relative;
            width: 100%;
            height: 350px;
            background: url('/image/mb.jpg') no-repeat top center/cover;
        }

        .about-section::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(141, 198, 65, 0.5); /* Lighter green overlay with 50% opacity */
        }

        .about-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: left;
            padding: 100px;
            max-width: 600px;
        }

        .about-content h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .about-content p {
            font-size: 12px;
        }

        .sitemap {
            margin-top: 50px;
            font-size: 16px;
        }

        .sitemap a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .sitemap a:hover {
            color: white; /* Light green hover effect */
            font-size: 18px;
            transform: scale(1.1); /* Slightly increase size */
            font-weight: bold;
            text-decoration: underline;
        }
        
    .sections-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 0 20px;
        margin-bottom: 5px;
    }
    
    .section { 
        flex: 1;
        min-width: 300px;
        padding: 40px 20px; 
        animation: slideUp 1.5s ease-out; 
        background-color: #fff; 
        border-radius: 10px; 
        box-shadow: 0 0 10px rgba(0,0,0,0.1); 
    }
    
    .card { 
        background-color: #fff; 
        border-radius: 10px; 
        padding: 10px; 
        box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        margin-bottom: 15px;
        height:100px; 
        max-width: 180px; 
        border: 2px solid #8dc641; 
        font-size: 13px; 
        text-align: center; 
    }
    .card h3 { margin-bottom: 3px; margin-top: 5px; }
    .card p { margin-top: 3px; }
    .offer-container { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; }
    .center-content { text-align: center; }
    ul { list-style: none; padding: 0; text-align: center; }
    li { display: flex; align-items: center; gap: 10px; justify-content: center; margin-bottom: 5px; }
    .material-icons { color: #1996b2; }
    @keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
    @keyframes slideUp { 0% { transform: translateY(20px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; }}
    
    @media (max-width: 768px) {
        .sections-container {
            flex-direction: column;
        }
        .section {
            width: 100%;
        }
    }
</style>
</head>
<body>

<section class="about-section">
        <div class="about-content">
            <h1>About Us</h1>
            <p>Our digital healthcare platform is committed to making medicine and healthcare essentials easily accessible online. Our seamless service ensures safe, fast, and reliable delivery, empowering you with convenient healthcare solutions at your fingertips.</p>
            <div class="sitemap">
                <a href="home.php">Home</a>|  
                <a href="abus.php"> About Us</a>
            </div>
        </div>
    </section>

<!-- Sections in side-by-side layout -->
<div class="sections-container">
    <section class="section" id="story">
        <h2>Our Story</h2>
        <p style="text-align: center;">Founded with a vision to simplify healthcare access, we began our journey to bridge the gap between quality healthcare and accessibility. With a commitment to authenticity and convenience, we ensure that every product reaches you with utmost care.</p>
    </section>

    <section class="section" id="mission">
        <h2>Mission & Vision</h2>
        <ul>
            <li><span class="material-icons">flag</span> <strong>Mission:</strong> To enhance healthcare accessibility with genuine products and timely delivery.</li>
            <li><span class="material-icons">visibility</span> <strong>Vision:</strong> To be the most trusted online medical store ensuring health and well-being for all.</li>
        </ul>
    </section>
</div>

<section class="section" id="offer">
    <h2>What We Offer</h2>
    <div class="offer-container">
        <div class="card"><h3><span class="material-icons">medication</span> Medicines</h3><p>A comprehensive range of authentic medicines for every need.</p></div>
        <div class="card"><h3><span class="material-icons">spa</span> Personal Care</h3><p>Quality products for skincare, haircare, and hygiene.</p></div>
        <div class="card"><h3><span class="material-icons">child_friendly</span> Mother and Baby Care</h3><p>Safe and trusted products for mothers and babies.</p></div>
        <div class="card"><h3><span class="material-icons">local_hospital</span> Daily Care</h3><p>Essentials for your everyday health and wellness.</p></div>
        <div class="card"><h3><span class="material-icons">nature</span> Ayurveda</h3><p>Traditional and herbal solutions for holistic health.</p></div>
    </div>
</section>

<section class="section" id="values">
    <h2>Our Values</h2>
    <ul class="center-content">
        <li><span class="material-icons">verified</span> Trust and Authenticity</li>
        <li><span class="material-icons">local_shipping</span> Timely Delivery</li>
        <li><span class="material-icons">support</span> Customer Support</li>
        <li><span class="material-icons">health_and_safety</span> Quality Assurance</li>
    </ul>
</section>

</body>


<?php include 'footer.html';?>
</html>