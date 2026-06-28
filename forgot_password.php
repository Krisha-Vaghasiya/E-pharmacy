<?php
session_start();
include 'db_connection.php'; // Ensure you have a database connection file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Load PHPMailer for sending emails

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        
        // Generate token and expiry time
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime('+30 minutes'));
        
        // Store token in database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $token, $expiry, $user_id);
        $stmt->execute();
        
        // Send email with reset link
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp-relay.brevo.com'; // Brevo SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = '88b117001@smtp-brevo.com';
            $mail->Password = 'ZdskrR3Y0OGUt1Q2';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            $mail->setFrom('kvaghasiya08@gmail.com', 'E-Pharmacy');
            $mail->addAddress($email);
            
            $reset_link = "http://localhost/projectC/reset_password.php?token=" . urlencode($token);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link below to reset your password:<br><a href='$reset_link'>$reset_link</a>";
            
            $mail->send();
            $_SESSION['message'] = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error'] = "Email not found.";
    }
    header("Location: /projectC/forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | E-Pharmacy</title>
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            background:  white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container Styling */
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }

        h2 {
            margin-bottom: 15px;
            color: #333;
        }

        /* Input Field */
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Button */
        button {
            width: 100%;
            padding: 10px;
            background: #8dc641;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            transition: 0.3s;
        }

        button:hover {
            background:rgb(132, 191, 55);
        }

        /* Message Box */
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Forgot Password</h2>

        <?php if(isset($_SESSION['message'])) { ?>
            <div class="message success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php } ?>

        <?php if(isset($_SESSION['error'])) { ?>
            <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php } ?>

        <form method="post" action="">
            <input type="email" name="email" required placeholder="Enter your email">
            <button type="submit">Send Reset Link</button>
        </form>
    </div>

</body>
</html>
