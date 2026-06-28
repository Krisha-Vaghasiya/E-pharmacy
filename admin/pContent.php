<?php
require_once('db_connection.php');

// Fetch product counts for each category
$counts = [];
$categories = [1 => 'Medicine', 2 => 'Personal Care', 3 => 'Ayurveda', 4 => 'Mother & Baby Care', 5 => 'Oral Care'];
foreach ($categories as $category_id => $category_name) {
    $sql_count = "SELECT COUNT(*) as count FROM products WHERE category_id = $category_id";
    $result_count = $conn->query($sql_count);
    $row_count = $result_count->fetch_assoc();
    $counts[$category_id] = $row_count['count'] ?? 0;
}

$sql_fetch = "SELECT * FROM products";
$result = $conn->query($sql_fetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="pStyle.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    

</head>
<body>
    

<h1 style="text-align: center;">Welcome to Product Management</h1>


<!-- First Row: 3 Compact Cards -->
<div class="dashboard-cards">
    <div class="card"><i class="fas fa-pills"></i><p><?= $counts[1] ?></p><h3>Medicine</h3></div>
    <div class="card"><i class="fas fa-user"></i><p><?= $counts[2] ?></p><h3>Personal Care</h3></div>
    <div class="card"><i class="fas fa-leaf"></i><p><?= $counts[3] ?></p><h3>Ayurveda</h3></div>
</div>

<!-- Second Row: 2 Compact Wider Cards -->
<div class="second-row">
    <div class="card"><i class="fas fa-baby"></i><p><?= $counts[4] ?></p><h3>Mother & Baby Care</h3></div>
    <div class="card"><i class="fas fa-home"></i><p><?= $counts[5] ?></p><h3>Oral Care</h3></div>
</div>


<button class="insert-btn" onclick="openForm()">
    <i class="material-icons">post_add</i> Add New Product
</button>


<h2 align='center'>Products List</h2>
<div class="table-container">
<table>

    <thead>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Brand</th>
           
            <th>Category ID</th>
            <th>Subcategory ID</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Expiry Date</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
       
        <tr data-id="<?= $row['product_id'] ?>">
    <td class="product-id"><?= $row['product_id'] ?></td>
    <td class="product-name"><?= htmlspecialchars($row['product_name']) ?></td>
    <td class="product-brand"><?= htmlspecialchars($row['brand']) ?></td>
    
    <td class="product-category"><?= htmlspecialchars($row['category_id']) ?></td>
    <td class="product-subcategory"><?= htmlspecialchars($row['subcategory_id']) ?></td>
    <td class="product-price">$<?= htmlspecialchars($row['price']) ?></td>
    <td class="product-quantity"><?= htmlspecialchars($row['quantity_in_stock']) ?></td>
    <td class="product-expiry"><?= htmlspecialchars($row['expiry_date']) ?></td>
    <td><img id="product-image-<?= $row['product_id'] ?>" 
     src="<?= htmlspecialchars($row['image_url']) ?>" 
     alt="Product Image" width="50" 
     onerror="this.src='default.jpg';"></td>
    
    <!-- Actions -->
    <td class="action-icons">
    <!-- Edit Button -->
    <button class="edit-btn"
        data-id="<?= $row['product_id'] ?>"
        data-name="<?= htmlspecialchars($row['product_name']) ?>"
         data-brand="<?= htmlspecialchars($row['brand']) ?>"
        data-description="<?= htmlspecialchars($row['description']) ?>"
        data-category="<?= htmlspecialchars($row['category_id']) ?>"
          data-subcategory="<?= htmlspecialchars($row['subcategory_id']) ?>"
        data-price="<?= htmlspecialchars($row['price']) ?>"
        data-quantity="<?= htmlspecialchars($row['quantity_in_stock']) ?>"
        data-expiry="<?= htmlspecialchars($row['expiry_date']) ?>">
       
        ✏ Edit
    </button>

    <!-- Delete Button (Fixed) -->
    <i class="material-icons delete-btn"
       data-id="<?= $row['product_id']; ?>" 
       title="Delete">delete</i>
</td>

        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>

<div id="popupForm" class="popup-form">
    <h3>Add New Product</h3>
    <form action="insert_product.php" method="post" enctype="multipart/form-data">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <input type="text" name="brand" placeholder="Brand" required>
        <input type="text" name="description" placeholder="Description" required>
        <select name="category_id" required>
    <option value="1">1-Medicine</option>
    <option value="2">2-Personal Care</option>
    <option value="3">3-Ayurveda</option>
    <option value="4">4-Mother & Baby Care</option>
    <option value="5">5-Oral Care</option>
</select>

<select name="subcategory_id" >
<option value="">null</option>
    <optgroup label="Medicine">
        <option value="101">Chronic Disease</option>
        <option value="102">Pain Relief</option>
        <option value="103">Cold, Cough & Allergy</option>
        <option value="104">Gastrointestinal</option>
        <option value="105">Eye & Ear Medicines</option>
    </optgroup>
    <optgroup label="Personal Care">
        <option value="201">Face Care</option>
        <option value="202">Body Care</option>
        <option value="203">Hair Care</option>
    </optgroup>
    <optgroup label="Mother & Baby Care">
        <option value="201">Mother Care</option>
        <option value="202">Baby Care</option>
        
    </optgroup>
</select>

        <input type="number" name="price" placeholder="Price" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="date" name="expiry_date" required>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Add Product</button>
        <button type="button" class="close-btn" onclick="closeForm()">Cancel</button>
    </form>
</div>

<div id="editPopupForm" class="popup-form">
    <h3>Edit Product</h3>
    <form id="editForm" action="update_product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" id="edit_product_id" name="product_id">
        <input type="text" id="edit_product_name" name="product_name" placeholder="Product Name" >
        <input type="text" id="edit_product_brand" name="brand" placeholder="Brand" required>
        <input type="text" id="edit_description" name="description" placeholder="Description" >
        <select id="edit_category_id" name="category_id" >
    <option value="1">1-Medicine</option>
    <option value="2">2-Personal Care</option>
    <option value="3">3-Ayurveda</option>
    <option value="4">4-Mother & Baby Care</option>
    <option value="5">5-Oral Care</option>
</select>

<select id="edit_subcategory_id" name="subcategory_id" >
<option value="">null</option>
    <optgroup label="Medicine">
        <option value="101">101-Chronic Disease</option>
        <option value="102">102-Pain Relief</option>
        <option value="103">103-Cold, Cough & Allergy</option>
        <option value="104">104-Gastrointestinal</option>
        <option value="105">105-Eye & Ear Medicines</option>
    </optgroup>
    <optgroup label="Personal Care">
        <option value="201">201-Face Care</option>
        <option value="202">202-Body Care</option>
        <option value="203">203-Hair Care</option>
    </optgroup>
    <optgroup label="Mother & Baby Care">
        <option value="401">Mother Care</option>
        <option value="402">Baby Care</option>
        
    </optgroup>
</select>

        <input type="number" id="edit_price" name="price" placeholder="Price" >
        <input type="number" id="edit_quantity" name="quantity" placeholder="Quantity" >
        <input type="date" id="edit_expiry_date" name="expiry_date" >
        <input type="file" id="edit_image" name="image" accept="image/*">
        <button type="submit">Update Product</button>
        <button type="button" class="close-btn" onclick="closeEditForm()">Cancel</button>
    </form>
</div>



<!--<div id="editPopupForm" class="popup-form">
    <h3>Edit Product</h3>
    <form action="update_product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" id="edit_product_id" name="product_id">
        <input type="text" id="edit_product_name" name="product_name" placeholder="Product Name" required>
        <input type="text" id="edit_description" name="description" placeholder="Description" required>
        <input type="number" id="edit_category_id" name="category_id" placeholder="Category ID" required>
        <input type="number" id="edit_price" name="price" placeholder="Price" required>
        <input type="number" id="edit_quantity" name="quantity" placeholder="Quantity" required>
        <input type="date" id="edit_expiry_date" name="expiry_date" required>
        <input type="file" id="edit_image" name="image" accept="image/*">
        <button type="submit">Update Product</button>
        <button type="button" class="close-btn" onclick="closeEditForm()">Cancel</button>
    </form>
</div>-->

</body>
</html>
