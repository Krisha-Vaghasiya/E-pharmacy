<?php
session_start();
require_once('db_connection.php');

$user_id = $_SESSION['user_id'] ?? 0;  // Check if user is logged in
$product_id = $_POST['product_id'];
$action = $_POST['action'];

// Initialize session for guest wishlist if not set
if (!isset($_SESSION['guest_wishlist'])) {
    $_SESSION['guest_wishlist'] = [];
}

// Check if product ID exists in products table
$product_check = "SELECT product_id FROM products WHERE product_id = '$product_id'";
$product_result = mysqli_query($conn, $product_check);

if (mysqli_num_rows($product_result) > 0) {
    if ($user_id) {
        // 🟢 Logged-in user: Store in database
        if ($action === 'add') {
            $check_sql = "SELECT * FROM wishlist WHERE user_id = '$user_id' AND product_id = '$product_id'";
            $check_result = mysqli_query($conn, $check_sql);
            if (mysqli_num_rows($check_result) == 0) {
                $sql = "INSERT INTO wishlist (user_id, product_id) VALUES ('$user_id', '$product_id')";
                if (mysqli_query($conn, $sql)) {
                    echo "Added to wishlist";
                } else {
                    echo "Error adding to wishlist";
                }
            } else {
                echo "Already in wishlist";
            }
        } elseif ($action === 'remove') {
            $sql = "DELETE FROM wishlist WHERE user_id = '$user_id' AND product_id = '$product_id'";
            if (mysqli_query($conn, $sql)) {
                echo "Removed from wishlist";
            } else {
                echo "Error removing from wishlist";
            }
        }
    } else {
        // 🟠 Guest user: Store in session
        if ($action === 'add') {
            if (!in_array($product_id, $_SESSION['guest_wishlist'])) {
                $_SESSION['guest_wishlist'][] = $product_id;  // Add to session
                echo "Added to guest wishlist";
            } else {
                echo "Already in guest wishlist";
            }
        } elseif ($action === 'remove') {
            $_SESSION['guest_wishlist'] = array_diff($_SESSION['guest_wishlist'], [$product_id]);
            echo "Removed from guest wishlist";
        }
    }
} else {
    echo "Invalid product ID";
}
