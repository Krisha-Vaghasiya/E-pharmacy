<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: projectC/reset_password.php?token=" . urlencode($token));
        exit();
    }

    // Password validation (server-side)
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        $_SESSION['error'] = "Password must be at least 8 characters long, include an uppercase letter, a number, and a special character.";
        header("Location: /projectC/reset_password.php?token=" . urlencode($token));
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Verify token
    $stmt = $conn->prepare("SELECT user_id, token_expiry FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $token_expiry = $row['token_expiry'];

        // Check if token is expired
        if (strtotime($token_expiry) < time()) {
            $_SESSION['error'] = "This reset link has expired. Please request a new one.";
            header("Location: /projectC/forgot_password.php");
            exit();
        }

        // Update password and remove token
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE user_id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();

        $_SESSION['message'] = "Your password has been reset successfully.";
        header("Location: /projectC/home.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: /projectC/forgot_password.php");
        exit();
    }
}

if (!isset($_GET['token'])) {
    die("Invalid request.");
}
$token = $_GET['token'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background:  white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            margin-bottom: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #8dc641;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: rgb(126, 182, 53);
        }
        .error {
            color: red;
            font-size: 14px;
        }
        .password-instructions {
            text-align: left;
            font-size: 12px;
            color: #555;
            margin-top: 5px;
        }
        .password-instructions span {
            display: block;
            font-size: 12px;
            margin: 3px 0;
            color: red;
        }
        .valid {
            color: green !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if(isset($_SESSION['error'])) { echo "<p class='error'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); } ?>
        <form method="post" action="" onsubmit="return validatePassword();">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <input type="password" id="new_password" name="new_password" required placeholder="Enter new password" onkeyup="checkPassword()">
            <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm new password" onkeyup="matchPassword()">
            
            <div class="password-instructions">
                <span id="length">❌ At least 8 characters</span>
                <span id="uppercase">❌ At least 1 uppercase letter</span>
                <span id="number">❌ At least 1 number</span>
                <span id="special">❌ At least 1 special character (@$!%*?&)</span>
                <span id="match">❌ Passwords do not match</span>
            </div>
            
            <button type="submit">Reset Password</button>
        </form>
    </div>

    <script>
        function checkPassword() {
            let password = document.getElementById("new_password").value;
            let length = document.getElementById("length");
            let uppercase = document.getElementById("uppercase");
            let number = document.getElementById("number");
            let special = document.getElementById("special");

            // Check length
            if (password.length >= 8) {
                length.innerHTML = "✔ At least 8 characters";
                length.classList.add("valid");
            } else {
                length.innerHTML = "❌ At least 8 characters";
                length.classList.remove("valid");
            }

            // Check uppercase letter
            if (/[A-Z]/.test(password)) {
                uppercase.innerHTML = "✔ At least 1 uppercase letter";
                uppercase.classList.add("valid");
            } else {
                uppercase.innerHTML = "❌ At least 1 uppercase letter";
                uppercase.classList.remove("valid");
            }

            // Check number
            if (/\d/.test(password)) {
                number.innerHTML = "✔ At least 1 number";
                number.classList.add("valid");
            } else {
                number.innerHTML = "❌ At least 1 number";
                number.classList.remove("valid");
            }

            // Check special character
            if (/[@$!%*?&]/.test(password)) {
                special.innerHTML = "✔ At least 1 special character (@$!%*?&)";
                special.classList.add("valid");
            } else {
                special.innerHTML = "❌ At least 1 special character (@$!%*?&)";
                special.classList.remove("valid");
            }

            matchPassword();
        }

        function matchPassword() {
            let password = document.getElementById("new_password").value;
            let confirm_password = document.getElementById("confirm_password").value;
            let match = document.getElementById("match");

            if (password === confirm_password && password.length > 0) {
                match.innerHTML = "✔ Passwords match";
                match.classList.add("valid");
            } else {
                match.innerHTML = "❌ Passwords do not match";
                match.classList.remove("valid");
            }
        }

        function validatePassword() {
            if (document.getElementById("match").classList.contains("valid")) {
                return true;
            } else {
                alert("Passwords do not match!");
                return false;
            }
        }
    </script>
</body>
</html>
