<?php
$conn = new mysqli("localhost", "root", "", "e_pharmacy");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT product_name, brand, description FROM products 
        WHERE product_name LIKE '%$query%' 
        OR brand LIKE '%$query%' 
        OR description LIKE '%$query%' 
        LIMIT 5";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div onclick=\"selectMedicine('".htmlspecialchars($row['product_name'])."')\">
                <strong>" . $row['product_name'] . "</strong> 
                <br><small>" . $row['brand'] . " | " . substr($row['description'], 0, 50) . "...</small>
              </div>";
    }
} else {
    echo "<div>No results found</div>";
}
?>
