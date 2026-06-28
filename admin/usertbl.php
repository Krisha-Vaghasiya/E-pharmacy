<?php
// Fetch all users from the database
require_once('db_connection.php');
$sql_fetch = "SELECT user_id,first_name, last_name,email,password,phone_number,city,role,created_at FROM Users";
$result = $conn->query($sql_fetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users Table</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        .table-container{
            max-height: 500px; /* Adjust the height as needed */
            overflow-y: auto;
        }
        .container {
            width: 90%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        } 
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #1996b2 ;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .action-icons {
            display: flex;
            gap: 15px;
        }
        .action-icons i {
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
            color: black;
        }
        .action-icons i:hover {
            transform: scale(1.2);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            text-align: center;
        }
        .close {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h2> Users Management</h2>
    <div class="table-container">
    <table>
        <thead>
            <tr>
                <?php
                if ($result->num_rows > 0) {
                    $columns = array_keys($result->fetch_assoc());
                    foreach ($columns as $column) {
                        echo "<th>" . ucfirst(str_replace('_', ' ', $column)) . "</th>";
                    }
                    echo "<th>Actions</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $result->data_seek(0); // Reset pointer to fetch rows again
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key === 'password') {
                            echo "<td>......</td>"; // Masking password
                        } else {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                    }
                    echo "<td>
                    
                     <i class='fas fa-trash delete-user' data-id='{$row['user_id']}'></i>
                </td>";
                
            
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='100%'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div>
</div>




<script>
document.querySelectorAll(".delete-user").forEach(icon => {
    icon.addEventListener("click", function () {
        let userId = this.getAttribute("data-id");
        if (confirm("Are you sure you want to delete this user?")) {
            fetch("delete_user.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "user_id=" + userId
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    location.reload();
                }
            })
            .catch(error => console.error("Error:", error));
        }
    });
});

</script>
</body>
</html>