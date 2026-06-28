<?php
require_once('db_connection.php');
$sql_fetch = "SELECT * FROM products WHERE category_id = 3";
$result = $conn->query($sql_fetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Table</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="cStyle.css">
</head>
<body>
<div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Category ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr data-id="<?= $row['product_id'] ?>">
                    <td class="product-id"><?= $row['product_id'] ?></td>
                    <td class="category-id"><?= htmlspecialchars($row['category_id']) ?></td>
                    <td class="product-name"><?= htmlspecialchars($row['product_name']) ?></td>
                    <td class="product-description"><?= htmlspecialchars($row['description']) ?></td>
                    <td class="product-price"> ₹<?= htmlspecialchars($row['price']) ?></td>
                    <td class="product-quantity"><?= htmlspecialchars($row['quantity_in_stock']) ?></td>
                    <td class="product-expiry"><?= htmlspecialchars($row['expiry_date']) ?></td>
                    <td> <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Product Image" width="50" onerror="this.src='default.jpg';"></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
