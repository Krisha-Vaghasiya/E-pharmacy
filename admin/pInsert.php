<?php
require_once('db_connection.php');

// Check DB connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_name'], $_POST['brand'], $_POST['description'], $_POST['category_id'], $_POST['subcategory_id'], $_POST['price'], $_POST['quantity'], $_POST['expiry_date'])) {
        
        $product_name = trim($_POST['product_name']);
        $brand = trim($_POST['brand']);
        $description = trim($_POST['description']);
        $category_id = intval($_POST['category_id']);
        $subcategory_id = intval($_POST['subcategory_id']);
        $price = floatval($_POST['price']);
        $quantity = intval($_POST['quantity']);
        $expiry_date = trim($_POST['expiry_date']);

        // Handle Image Upload
        $image_url = "";
        if (!empty($_FILES['image']['name'])) {
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];  
            $upload_folder = "upload/";  

            $image_name = basename($_FILES['image']['name']);
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            if (!in_array($image_ext, $allowed_extensions)) {
                die("<script>alert('Invalid file type! Only JPG, JPEG, PNG, and GIF allowed.');</script>");
            }

            $new_image_name = uniqid("IMG_", true) . "." . $image_ext;
            $image_path = $upload_folder . $new_image_name;

            if (move_uploaded_file($image_tmp, $image_path)) {
                $image_url = $image_path;
            } else {
                die("<script>alert('Failed to upload image!');</script>");
            }
        }

        // SQL Query
        $sql = "INSERT INTO products (product_name, brand, description, category_id, subcategory_id, price, quantity_in_stock, expiry_date, image_url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("<script>alert('Error preparing SQL statement: " . $conn->error . "');</script>");
        }

        $stmt->bind_param("sssiiidss", $product_name, $brand, $description, $category_id, $subcategory_id, $price, $quantity, $expiry_date, $image_url);

        if ($stmt->execute()) {
            echo "<script>
                alert('Product inserted successfully');
                window.location.href = 'adminUi.php'; 
            </script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } 

    $conn->close();
}
?>
