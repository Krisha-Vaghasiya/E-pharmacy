<?php
// feedback.php (without form processing logic)

include 'db_connection.php';

// Fetch categories and brands for dropdowns
$categories = mysqli_query($conn, "SELECT category_id, category_name FROM categories");
$brands = mysqli_query($conn, "SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link rel="stylesheet" href="fstyle.css"> <!-- Your CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <h2 align="center">Feedback Management</h2>

    <!-- Filter Form (AJAX Powered) -->
    <form id="filter-form" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">

        <!-- Search Input -->
        <input type="text" id="search-feedback" name="search" placeholder="Search Product ID, Name, Brand, or Date" style="padding: 5px; width: 200px;">

        <!-- Category Dropdown -->
        <select id="category-filter" name="category" style="padding: 5px;">
            <option value="">All Categories</option>
            <?php while ($row = mysqli_fetch_assoc($categories)) : ?>
                <option value="<?= $row['category_id']; ?>"><?= htmlspecialchars($row['category_name']); ?></option>
            <?php endwhile; ?>
        </select>

        <!-- Brand Dropdown -->
        <select id="brand-filter" name="brand" style="padding: 5px;">
            <option value="">All Brands</option>
            <?php while ($row = mysqli_fetch_assoc($brands)) : ?>
                <option value="<?= htmlspecialchars($row['brand']); ?>"><?= htmlspecialchars($row['brand']); ?></option>
            <?php endwhile; ?>
        </select>


        <!-- Rating Filter -->
<select id="rating-filter" name="rating" style="padding: 5px;">
    <option value="">All Ratings</option>
    <option value="5">5 Stars</option>
    <option value="4">4 Stars</option>
    <option value="3">3 Stars</option>
    <option value="2">2 Stars</option>
    <option value="1">1 Star</option>
</select>


        <!-- Date Range -->
        <input type="date" id="start-date" name="start_date" style="padding: 5px;">
        <input type="date" id="end-date" name="end_date" style="padding: 5px;">

        <!-- Buttons -->
        <button type="submit" id="apply-filter" style="padding: 5px 5px; background-color: #1996b2; color: #fff; border: none; cursor: pointer;">Apply Filters</button>
        <button type="button" id="clear-filter" style="padding: 5px 5px; background-color: #8dc641; color: #fff; border: none; cursor: pointer;">Clear Filters</button>

    </form>

    <!-- Feedback Table -->
    <div id="feedback-container">
        <!-- Feedback rows will be dynamically loaded here -->
    </div>

    <!-- Load the feedback script -->
    <script src="fscript.js"></script>

</body>

</html>
