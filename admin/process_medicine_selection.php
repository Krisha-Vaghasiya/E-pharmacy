<?php
// Ensure no output before this point (no whitespace, no BOM)
header('Content-Type: application/json');
session_start();
include "db_connection.php";

// Debugging: Log received data
error_log("Received data: " . print_r($_POST, true));

// Initialize response array
$response = ['success' => false, 'message' => ''];

try {
    // Get prescription ID and medicines from the form
    $prescription_id = isset($_POST['prescription_id']) ? (int)$_POST['prescription_id'] : 0;
    $medicines = isset($_POST['medicines']) ? $_POST['medicines'] : [];
    $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];

    // Validate inputs
    if ($prescription_id <= 0 || empty($medicines)) {
        throw new Exception('Invalid request.');
    }

    // Check if the prescription exists
    $sql = "SELECT prescription_id FROM prescription WHERE prescription_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $prescription_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        throw new Exception('Prescription not found.');
    }
    $stmt->close();

    // Save selected medicines
    $sql = "INSERT INTO prescription_medicine (prescription_id, product_id, quantity) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    foreach ($medicines as $index => $product_id) {
        $quantity = isset($quantities[$index]) ? (int)$quantities[$index] : 1;

        if ($quantity <= 0) {
            throw new Exception('Invalid quantity for product ID ' . $product_id);
        }

        $stmt->bind_param("iii", $prescription_id, $product_id, $quantity);
        if (!$stmt->execute()) {
            error_log("MySQL Error: " . $stmt->error);
            throw new Exception('Failed to insert medicine into prescription.');
        }
    }

    $response['success'] = true;
    $response['message'] = 'Medicines selected successfully.';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Error in process_medicine_selection.php: " . $e->getMessage());
}

$stmt->close();
$conn->close();

// Ensure only JSON is output
die(json_encode($response));