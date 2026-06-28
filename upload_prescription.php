<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "You must be logged in to upload a prescription."]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $target_dir = "uploads/";

    // Create the uploads directory if it doesn't exist
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0755, true)) {
            echo json_encode(["success" => false, "message" => "Failed to create uploads directory."]);
            exit;
        }
    }

    // Create a unique filename to avoid overwriting existing files
    $uniqueFileName = time() . "_" . basename($_FILES["prescription"]["name"]);
    $target_file = $target_dir . $uniqueFileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file type
    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "pdf"])) {
        echo json_encode(["success" => false, "message" => "Sorry, only JPG, JPEG, PNG & PDF files are allowed."]);
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["prescription"]["tmp_name"], $target_file)) {
            // Securely insert into database
            $stmt = $conn->prepare("INSERT INTO prescription (user_id, prescription_file, status, created_at) VALUES (?, ?, 'Pending', NOW())");
            $stmt->bind_param("is", $user_id, $target_file);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Prescription uploaded successfully.wait for admin's approval"]);
            } else {
                error_log("SQL Error: " . $stmt->error);
                echo json_encode(["success" => false, "message" => "Database error."]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Sorry, there was an error uploading your file."]);
        }
    }
}

$conn->close();
?>
