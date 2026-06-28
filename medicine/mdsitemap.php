
<?php
// Breadcrumbs with Custom Styling
function generateBreadcrumb($currentPage, $showMedicine = false, $isMainMedicine = false) {
    // Detect if the page is inside the medicine folder
    $isInMedicineFolder = (strpos($_SERVER['PHP_SELF'], '/medicine/') !== false);

    // Set correct paths for Home and Medicine links
    $basePath = $isInMedicineFolder ? '../home.php' : 'home.php';
    $medicinePath = $isInMedicineFolder ? 'mainmedicine.php' : 'medicine/mainmedicine.php';

    echo '<style>
        .breadcrumb-container {
            margin: 10px 0 10px 50px;
            font-size: 16px;
        }
        .breadcrumb-container a {
            color: #1996b2; /* Blue for links */
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .breadcrumb-container a:hover {
            color: #8dc641; /* Green on hover */
        }
        .breadcrumb-container span {
            margin: 0 5px; /* Space between items */
            color: #555; /* Gray separator */
        }
        .breadcrumb-container .current-page {
            color: #8dc641; /* Green for current page */
        }
    </style>';

    echo '<div class="breadcrumb-container">';
    echo '<a href="' . $basePath . '">Home</a>';

    // Show "Medicine" link unless it's the main medicine page
    if ($showMedicine && !$isMainMedicine) {
        echo '<span>&gt;</span>';
        echo '<a href="' . $medicinePath . '">Medicine</a>';
    }

    // Display the current page
    if ($currentPage) {
        echo '<span>&gt;</span>';
        echo '<span class="current-page">' . htmlspecialchars($currentPage) . '</span>';
    }

    echo '</div>';
}
?>

 