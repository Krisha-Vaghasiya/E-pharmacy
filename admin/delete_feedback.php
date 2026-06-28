<?php
include 'db_connection.php'; // Ensure correct database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $feedbackId = intval($_POST['id']); // Convert ID to integer for security

    if ($feedbackId <= 0) {
        echo "error: Invalid feedback ID received - " . $_POST['id']; // Debugging message
        exit;
    }

    // Check if feedback exists before deleting
    $checkQuery = "SELECT * FROM reviews WHERE review_id = $feedbackId";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Execute the DELETE query
        $deleteQuery = "DELETE FROM reviews WHERE review_id = $feedbackId";
        if (mysqli_query($conn, $deleteQuery)) {
            echo "success";
        } else {
            echo "error: " . mysqli_error($conn); // Debugging SQL error
        }
    } else {
        echo "error: Feedback not found for ID " . $feedbackId;
    }
} else {
    echo "error: Invalid request";
}
?>
