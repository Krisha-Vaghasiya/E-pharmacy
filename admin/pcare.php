<?php
require_once('db_connection.php');
$sql_fetch = "SELECT * FROM products WHERE category_id = 2";
$result = $conn->query($sql_fetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Table</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #1996b2;
            color: #fff;
        }
        td img {
            border-radius: 5px;
        }
        .action-icons {
            cursor: pointer;
            margin-right: 10px;
            color: black;
            position: relative;
        }
        .action-icons:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            background: #333;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }
        .edit {
            color: black;
        }
        .delete {
            color: black;
        }
    </style>
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
                    <td class="product-price">₹<?= htmlspecialchars($row['price']) ?></td>
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
