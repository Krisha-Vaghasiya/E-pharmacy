<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = isset($_POST['approve']) ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE prescription SET status=? WHERE prescription_id=?");
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        echo "Prescription status updated!";
    } else {
        echo "Error updating status.";
    }
}
?>

 
