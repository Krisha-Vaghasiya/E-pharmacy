<?php
session_start();
require_once 'db_connection.php';

// Fetch all prescriptions
$sql = "SELECT p.prescription_id, u.first_name, u.last_name, p.prescription_file, p.status, p.created_at 
        FROM prescription p
        JOIN users u ON p.user_id = u.user_id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Management</title>
    <link rel="stylesheet" href="admin_prescription.css">
</head>
<body>
    <div class="container">
        <h2 align="center">Prescription Orders Management</h2>
        <table>
            <thead>
                <tr>
                    <th>Prescription ID</th>
                    <th>Customer</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['prescription_id']; ?></td>
                        <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                        <td>
                            <a href="#" class="view-file" data-file="../<?php echo $row['prescription_file']; ?>">View</a>
                        </td>
                        <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td class="actions">
                            <?php if ($row['status'] === 'Pending'): ?>
                                <button class="approve" data-prescription-id="<?php echo $row['prescription_id']; ?>">Approve</button>
                                <button class="reject" data-prescription-id="<?php echo $row['prescription_id']; ?>">Reject</button>
                                <button class="select-medicine" data-prescription-id="<?php echo $row['prescription_id']; ?>">Select Medicine</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Viewing Prescription File -->
    <div id="fileModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); z-index: 1000;">
        <h3>View Prescription</h3>
        <div id="fileContent"></div> <!-- Content will be dynamically inserted here -->
        <button onclick="closeFileModal()" style="margin-top: 10px;">Close</button>
    </div>

    <!-- Modal for Selecting Medicines -->
    <!-- Modal for Selecting Medicines -->
<!-- Modal for Selecting Medicines -->
<div id="medicineModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); z-index: 1000;
    max-height: 70vh; 
    overflow-y: auto

">
        <span style="position: absolute; top: 10px; right: 10px; cursor: pointer;" onclick="closeMedicineModal()">×</span>
        <h3>Select Medicines</h3>
        <form id="medicineForm" onsubmit="submitMedicineSelection(event); return false;">

            <table>
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Medicine Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all medicines (products)
                    $medicine_query = "SELECT * FROM products";
                    $medicine_result = $conn->query($medicine_query);
                    while ($medicine = $medicine_result->fetch_assoc()):
                    ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="medicines[]" value="<?php echo $medicine['product_id']; ?>">
                            </td>
                            <td><?php echo $medicine['product_name']; ?></td>
                            <td><?php echo $medicine['price']; ?></td>
                            <td>
                          <input type="number" name="quantity[]" min="1" value="1">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <input type="hidden" id="prescriptionId" name="prescription_id">
            <div class="button-container">
                <button type="button" class="submit-btn" onclick="submitMedicineSelection(event)">Submit</button>
                <button type="button" class="cancel-btn" onclick="closeMedicineModal()">Cancel</button>
            </div>
        </form>
    </div>
    <!-- Link to the JavaScript file -->
    <script src="admin_prescription.js"></script>
</body>
</html>

<?php
$conn->close();
?>