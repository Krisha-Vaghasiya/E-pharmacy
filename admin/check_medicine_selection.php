<?php
session_start();
include "db_connection.php";

// Get the prescription ID from the query string
$prescriptionId = isset($_GET['prescription_id']) ? (int)$_GET['prescription_id'] : 0;

// Validate the prescription ID
if ($prescriptionId <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['medicines_selected' => false]);
    exit;
}

// Check if medicines are selected for the prescription
$sql = "SELECT COUNT(*) AS medicine_count FROM prescription_medicine WHERE prescription_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    header('Content-Type: application/json');
    echo json_encode(['medicines_selected' => false]);
    exit;
}

$stmt->bind_param("i", $prescriptionId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Return JSON response
header('Content-Type: application/json');
if ($row['medicine_count'] > 0) {
    echo json_encode(['medicines_selected' => true]);
} else {
    echo json_encode(['medicines_selected' => false]);
}

$stmt->close();
$conn->close();
?>