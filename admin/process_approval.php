selected medicine not stored in prescription_medicine table
<?php
session_start();
require_once 'db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json'); // ✅ Ensure JSON response

// ✅ Debug incoming request
error_log("DEBUG: Received POST params: " . json_encode($_POST));

// ✅ Check required parameters
if (empty($_POST['prescription_id']) || empty($_POST['action'])) {
    error_log("❌ Missing required parameters.");
    echo json_encode(['success' => false, 'message' => 'Missing required parameters.']);
    exit;
}

$prescriptionId = (int) $_POST['prescription_id'];
$action = $_POST['action'];

// ✅ Validate action
if (!in_array($action, ['approve', 'reject'])) {
    error_log("❌ Invalid action: " . $action);
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    exit;
}

// ✅ Define status correctly
$newStatus = ($action === 'approve') ? 'Approved' : 'Rejected';

// Fetch user email and order details
$sql = "SELECT u.email, u.user_id, o.order_id 
        FROM prescription p
        LEFT JOIN orders o ON p.prescription_id = o.prescription_id
        LEFT JOIN users u ON COALESCE(o.user_id, p.user_id) = u.user_id 
        WHERE p.prescription_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $prescriptionId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    error_log("❌ No matching data found for prescription ID: " . $prescriptionId);
    echo json_encode(['success' => false, 'message' => 'No matching data found for this prescription.']);
    exit;
}

$userEmail = $row['email'];
$userId = $row['user_id'];
$orderId = $row['order_id'];

// ✅ Update prescription status
// Assign the new status before updating
$newStatus = ($action === 'approve') ? 'Approved' : 'Rejected';

// Update the prescription status
$updateSql = "UPDATE prescription SET status = ? WHERE prescription_id = ?";
$updateStmt = $conn->prepare($updateSql);
if ($updateStmt) {
    $updateStmt->bind_param("si", $newStatus, $prescriptionId);
    $updateStmt->execute();
    $updateStmt->close();
    error_log("✅ Prescription updated successfully. New Status: " . $newStatus);
} else {
    error_log("❌ SQL error (prescription update): " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database error while updating prescription.']);
    exit;
}


// ✅ If order doesn't exist, create it
/*if (!$orderId && $action === 'approve') {
    $insertOrderSQL = "INSERT INTO orders (user_id, prescription_id, order_status, created_at)
                       VALUES (?, ?, 'Medicines Selected', NOW())";
    $insertOrderStmt = $conn->prepare($insertOrderSQL);
    if ($insertOrderStmt) {
        $insertOrderStmt->bind_param("ii", $userId, $prescriptionId);
        $insertOrderStmt->execute();
        $orderId = $insertOrderStmt->insert_id; // Get new order ID
        error_log("✅ Order created successfully with Order ID: " . $orderId);
        $insertOrderStmt->close();
    } else {
        error_log("❌ SQL error (order creation): " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Database error while creating order.']);
        exit;
    }
}*/

// ✅ Send email notification
if ($userEmail && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';
        $mail->SMTPAuth = true;
        $mail->Username = '88b117001@smtp-brevo.com'; // Replace with your SMTP username
        $mail->Password = 'ZdskrR3Y0OGUt1Q2'; // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kvaghasiya08@gmail.com', 'E-Pharmacy Team');
        $mail->addAddress($userEmail);
        $mail->isHTML(true);

        $mail->Subject = "Your Order Has Been " . ucfirst(strtolower($newStatus));
        $mail->Body = "<p>Dear User,</p>
        <p>Your order <strong>#$orderId</strong> has been <strong>$newStatus</strong>.</p>";

        if ($action === 'approve') {
            $mail->Body .= "<p>Please log in to review the selected medicines.</p>";
        } else {
            $mail->Body .= "<p>Unfortunately, your prescription order was rejected.</p>";
        }
        $mail->Body .= "<p>Thank you, <br><strong>E-Pharmacy Team</strong></p>";

        $mail->send();
        error_log("✅ Email sent successfully.");
    } catch (Exception $e) {
        error_log("⚠️ Email failed: " . $mail->ErrorInfo);
        echo json_encode(['success' => false, 'message' => 'Email failed: ' . $mail->ErrorInfo]);
        exit;
    }
} else {
    error_log("❌ Invalid email address: " . $userEmail);
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

// ✅ Send valid JSON response
echo json_encode(['success' => true, 'message' => "Prescription $newStatus successfully."]);
$conn->close();
?>
