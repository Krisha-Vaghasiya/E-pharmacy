<?php
include 'db_connection.php'; // Your database connection file
$conn = db_connect();

// Define admin credentials
$fn = "krina";
$ln = "dhola";
$email = "kdhola@gmail.com";
$password = "krina123"; // Change this to a strong password
$pno = "9087563421";

$city = "bhavnagar";
$role = "admin";

// Securely hash the password using password_hash()
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// SQL query
$sql =  "INSERT INTO users (first_name, last_name, email, password, phone_number,  city, role)  VALUES ('$fn', '$ln','$email', '$hashedPassword','$pno','$city','$role' )";

// Execute the query
if (mysqli_query($conn, $sql)) {
    echo "Admin user created successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
