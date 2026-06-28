<?php
include 'db_connection.php'; // Ensure database connection is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : '';
    $last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : '';
    $email = isset($_POST["email"]) ? $_POST["email"] : '';
    $phoneno = isset($_POST["phoneno"]) ? $_POST["phoneno"] : '';
    $city = isset($_POST["city"]) ? $_POST["city"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    if (empty($first_name) || empty($last_name) || empty($email) || empty($phoneno) || empty($city) || empty($password)) {
        die("Error: Some fields are empty.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Secure password

// Check if the email already exists in the database
$check_email_query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $check_email_query);

if (mysqli_num_rows($result) > 0) {
    // Email already exists
    echo "Error: This email is already registered.";
} else {
    // Insert the new user into the database




    $sql = "INSERT INTO users (first_name, last_name, email, phone_number, city, password) 
            VALUES ('$first_name', '$last_name', '$email', '$phoneno', '$city', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conn); // Print MySQL error
    }
}
}

?>
