<?php
session_start();
require_once 'db_connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"], $_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Fetch user_id and hashed password from the users table
$query = $conn->prepare("SELECT user_id, password, role FROM users WHERE email = ?");    if (!$query) {
        die("Query failed: " . $conn->error);
    }

    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
       $user_id = $row["user_id"];
        $hashed_password = $row["password"];
        $role = $row["role"];

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["role"] = $role;

            // ✅ Insert login info into the login table
            $insert_login = $conn->prepare("INSERT INTO login (user_id, email, psw) VALUES (?, ?, ?)");
            if (!$insert_login) {
                die("Insert Query failed: " . $conn->error);
            }

            $insert_login->bind_param("iss", $user_id, $email, $hashed_password);
            $insert_login->execute();

            // ✅ Store message in sessionStorage (for toast notification)
            echo "<script>
                sessionStorage.setItem('loginMessage', 'Login successful!');
                sessionStorage.setItem('messageType', 'success');
                window.location.href = 'home.php';
            </script>";
            exit();
        } else {
            echo "<script>
                sessionStorage.setItem('loginMessage', 'Incorrect password!');
                sessionStorage.setItem('messageType', 'error');
                window.location.href = 'home.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            sessionStorage.setItem('loginMessage', 'User not found!');
            sessionStorage.setItem('messageType', 'error');
            window.location.href = 'home.php';
        </script>";
        exit();
    }
}
?>
