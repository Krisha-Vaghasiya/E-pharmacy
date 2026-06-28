<?php
session_start();
include_once 'db_connection.php'; // Ensure database connection

$conn = db_connect(); // Make sure this function exists

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('Both fields are required!'); window.location.href='admin_login.php';</script>";
        exit();
    }

    // Fetch user_id, password, and role from users table
    $sql = "SELECT user_id, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $hashed_password = $row['password'];
        $role = $row['role'];

        // Verify hashed password
        if (password_verify($password, $hashed_password)) {
            if ($role === 'admin') {
                //  Store correct session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;

                header("Location: adminUi.php");
                exit();
            } else {
                echo "<script>alert('Access denied! You are not an admin.'); window.location.href='admin_login.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='admin_login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid email!'); window.location.href='admin_login.php';</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-icon {
            font-size: 40px;
            color: black;
            margin-bottom: 5px;
        }
        h2 {
            margin: 5px 0 20px;
        }
        .input-box {
            position: relative;
            margin: 15px 0;
        }
        .input-box input {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding-left: 40px;
            font-size: 14px;
        }
        .input-box .icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #777;
            visibility: hidden;
        }
        .input-box input:focus::placeholder {
            color: transparent;
        }
        .input-box input:focus + .icon {
            visibility: visible;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            border: none;
            background: #8dc641;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 14px;
            position: relative;
        }
        .login-btn .spinner {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
    <script>
        function validateForm() {
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();

            if (email === "" || password === "") {
                alert("Both fields are required!");
                return false;
            }

            // Show loading spinner
            const submitButton = document.querySelector(".login-btn");
            submitButton.disabled = true;
            submitButton.innerHTML = '<div class="spinner"></div>';

            return true;
        }
    </script>
</head>
<body>
    <div class="login-container">
        <span class="login-icon material-icons">lock</span>
        <h2>Admin Login</h2>
        <div class="form-container">
            <form action="admin_login.php" method="POST" onsubmit="return validateForm()">
                <div class="input-box">
                    <input type="text" id="email" name="email" placeholder="Email">
                    <span class="icon material-icons">person</span>
                </div>
                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder="Password">
                    <span class="icon material-icons">vpn_key</span>
                </div>
                <button type="submit" class="login-btn">LOGIN</button>
            </form>
        </div>
    </div>
</body>
</html>