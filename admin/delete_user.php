<?php
require_once('db_connection.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
        echo json_encode(["status" => "error", "message" => "Invalid user ID received: " . json_encode($_POST)]);
        exit;
    }

    $user_id = intval($_POST['user_id']); // Convert to integer

    // Debugging log
    error_log("Received user_id: " . $user_id); 

    // Prepare and execute delete query
    $sql_delete = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting user"]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
 