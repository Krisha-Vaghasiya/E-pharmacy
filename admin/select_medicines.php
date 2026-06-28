<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $medicines = $_POST['medicines'];

    $stmt = $conn->prepare("UPDATE prescription SET selected_medicines=? WHERE prescription_id=?");
    $stmt->bind_param("si", $medicines, $id);
    
    if ($stmt->execute()) {
        echo "Medicines selected successfully!";
    } else {
        echo "Error selecting medicines.";
    }
}
?>
