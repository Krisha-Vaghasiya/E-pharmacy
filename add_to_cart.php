<?php
session_start();
include 'db_connection.php'; // ✅ Ensure DB connection works

header('Content-Type: application/json'); // ✅ Set response header

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    if ($product_id <= 0 || $quantity <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid product or quantity."]);
        exit;
    }

    // ✅ Fetch product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    
    if (!$product) {
        echo json_encode(["status" => "error", "message" => "Product not found."]);
        exit;
    }

    // ✅ Check if requested quantity exceeds stock
    if ($quantity > $product['quantity_in_stock']) {
        echo json_encode(["status" => "error", "message" => "Not enough stock available."]);
        exit;
    }

    if (isset($_SESSION['user_id'])) { // ✅ Logged-in User
        $user_id = $_SESSION['user_id'];
        
        // ✅ Check if the product is already in the cart
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // ✅ Update quantity if product already exists
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + $quantity;

            if ($new_quantity > $product['quantity_in_stock']) {
                echo json_encode(["status" => "error", "message" => "Exceeding available stock."]);
                exit;
            }

            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
            $stmt->execute();
        } else {
            // ✅ Insert new item into the cart
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $product_id, $quantity);
            $stmt->execute();
        }

    } else { // ✅ Guest User (Session-Based Cart)
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $new_quantity = $_SESSION['cart'][$product_id]['quantity'] + $quantity;

            if ($new_quantity > $product['quantity_in_stock']) {
                echo json_encode(["status" => "error", "message" => "Exceeding available stock."]);
                exit;
            }
            $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'product_id' => $product['product_id'],
                'product_name' => $product['product_name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'quantity_in_stock' => $product['quantity_in_stock'], // ✅ Store stock to prevent over-ordering
                'expiry_date' => $product['expiry_date'],
                'image_url' => $product['image_url'],
                'brand' => $product['brand']
            ];
        }
    }

    // ✅ Close connections
    $stmt->close();
    $conn->close();

    echo json_encode(["status" => "success", "message" => "Product added to cart."]);
    exit;
}
?>
