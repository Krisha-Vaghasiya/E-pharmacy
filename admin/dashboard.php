<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .card {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 150px;
        }
        .card i {
            font-size: 28px;
            margin-bottom: 8px;
        }
        .card h3 {
            font-size: 14px;
            margin: 4px 0;
        }
        .card p {
            font-size: 18px;
            font-weight: bold;
        }
    </style>




</head>
<body>
    <h1>Welcome to Admin Dashboard</h1>
    <div class="dashboard-cards">
        <div class="card total-orders"><i class="fas fa-shopping-cart"></i><p>Loading...</p><h3>Total Orders</h3></div>
        <div class="card pending-orders"><i class="fas fa-clock"></i><p>Loading...</p><h3>Pending Orders</h3></div>
        <div class="card total-users"><i class="fas fa-users"></i><p>Loading...</p><h3>Total Users</h3></div>
        <div class="card revenue"><i class="fas fa-dollar-sign"></i><p>Loading...</p><h3>Revenue</h3></div>
        <div class="card total-products"><i class="fas fa-tags"></i><p>Loading...</p><h3>Total Products</h3></div>
        <div class="card low-stock"><i class="fas fa-exclamation-triangle"></i><p>Loading...</p><h3>Low Stock Alerts</h3></div>
    </div>

    <script src=dashboard.js></script>
</body>
</html>