<?php
include 'db_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); // Ensure JSON response

$response = ["status" => "error", "message" => "Something went wrong."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $product_id = intval($_POST['product_id']);
    $product_name = trim($_POST['product_name']);
    $brand = trim($_POST['brand']);
    $description = trim($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $expiry_date = trim($_POST['expiry_date']);
    
    // Check if product exists before updating
    $check_query = "SELECT image_url FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo json_encode(["status" => "error", "message" => "Product ID not found!"]);
        exit;
    }
    
    $row = $result->fetch_assoc();
    $image_url = $row['image_url']; // Keep the existing image by default
    $stmt->close();

    // Handle image upload if a new image is provided
    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $upload_folder = "upload/";
        $image_path = $upload_folder . $image_name;

        if (move_uploaded_file($image_tmp, $image_path)) {
            $image_url = $image_path; // Use the new image URL
        } else {
            echo json_encode(["status" => "error", "message" => "Image upload failed!"]);
            exit;
        }
    }

    // Prepare the SQL query to update the product
    $sql = "UPDATE products SET product_name=?, brand=?, description=?, category_id=?, subcategory_id=?, price=?, quantity_in_stock=?, expiry_date=?, image_url=? WHERE product_id=?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("sssiiidssi", $product_name, $brand, $description, $category_id, $subcategory_id, $price, $quantity, $expiry_date, $image_url, $product_id);

    // Execute the query and check if successful
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Product updated successfully!", "image_url" => $image_url . "?" . time()]); // Cache busting
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }

    // Cleanup
    $stmt->close();
    $conn->close();
    exit;
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}
?>