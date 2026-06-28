<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$prescription_id = isset($_POST['prescription_id']) ? intval($_POST['prescription_id']) : 0;

if ($prescription_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid prescription ID.']);
    exit;
}

// Check if prescription exists and belongs to the user
$sql = "SELECT * FROM prescription WHERE prescription_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $prescription_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Prescription not found or unauthorized access.']);
    exit;
}

// Change status to "User Approved"
$update_sql = "UPDATE prescription SET status = 'User Approved' WHERE prescription_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("i", $prescription_id);
$update_stmt->execute();

if ($update_stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Prescription approved successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to approve prescription.']);
}

$stmt->close();
$update_stmt->close();
$conn->close();
?>
