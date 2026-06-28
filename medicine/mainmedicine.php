<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Categories</title>

    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }

        .medicine-title {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin: 10px 0;
            color: #333;
            text-transform: uppercase;
        }

        .medicine-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 20px auto;
            padding: 25px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height:50%;
        }

        .subcategory-item {
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, background-color 0.3s;
            text-decoration: none;
            color: #333;
            position: relative;
        }

        .subcategory-item:hover {
            background-color: #e0f7fa;
            transform: translateY(-3px);
        }

        .subcategory-item h3 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: bold;
        }

        .subcategory-description {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        /* Custom Tooltip Styles */
        .subcategory-item[title] {
            position: relative;
        }
        .subcategory-item[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            background-color:#8dc641;
            color: #fff;
            padding: 5px 10px;
            border: 1px solid #8dc641;
            border-radius: 10px;
            white-space: nowrap;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

<?php include '../headerA.php';
    include 'mdsitemap.php';
    generateBreadcrumb('All Medicine', true,true);
?>

<h2 class="medicine-title">Medicine Categories</h2>
<div class="medicine-container">
    <a href="mdc.php" class="subcategory-item" title="Click to explore Chronic Disease medicines!">
        <h3>Chronic Disease</h3>
        <p class="subcategory-description">Includes medicines for diabetes, hypertension, and other long-term conditions.</p>
    </a>
    <a href="mdc2pain.php" class="subcategory-item" title="Click to explore Pain Relief & Fever medicines!">
        <h3>Pain Relief & Fever</h3>
        <p class="subcategory-description">Painkillers, antipyretics, and inflammation relief solutions.</p>
    </a>
    <a href="mdc3cold.php" class="subcategory-item" title="Click to explore Cold, Cough & Allergy medicines!">
        <h3>Cold, Cough & Allergy</h3>
        <p class="subcategory-description">Antihistamines, syrups, and nasal sprays for quick relief.</p>
    </a>
    <a href="mdc4gast.php" class="subcategory-item" title="Click to explore Gastrointestinal medicines!">
        <h3>Gastrointestinal Medicines</h3>
        <p class="subcategory-description">Antacids, laxatives, and digestive aids for stomach care.</p>
    </a>
    <a href="mdc5.php" class="subcategory-item" title="Click to explore Eye & Ear medicines!">
        <h3>Eye & Ear Medicines</h3>
        <p class="subcategory-description">Eye drops, ear drops, and infection control treatments.</p>
    </a>
</div>

<?php include '../footer.html'; ?>
</body>
</html>