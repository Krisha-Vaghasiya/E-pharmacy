<?php
session_start();  // Ensure session is started
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        // Redirect back to product details page with a message
        $product_id = $_POST['product_id'];
        header("Location: product_details.php?id=$product_id&error=not_logged_in");

        exit;
    }

    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = htmlspecialchars($_POST['comment']);

    // Check if user exists
    $user_check = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $user_check->bind_param("i", $user_id);
    $user_check->execute();
    $user_check_result = $user_check->get_result();

    if ($user_check_result->num_rows == 0) {
        echo "Invalid user. Cannot submit review.";
        exit;
    }

    // Insert review using prepared statement
    $insert_review = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $insert_review->bind_param("iiis", $product_id, $user_id, $rating, $comment);

    if ($insert_review->execute()) {
        header("Location: product_details.php?id=$product_id&success=review_added");
exit;

    } else {
        echo "Error submitting review.";
    }
}
?>