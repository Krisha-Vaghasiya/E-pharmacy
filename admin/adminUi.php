<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="adminstyle.css">
   
    <script src="adminMain.js"></script>
   <script src="ascript.js" defer ></script>
   <script src="pScript.js" defer ></script>
   <script src="admin_prescription.js" defer ></script>
   <script src="fscript.js" defer></script>
   <script src="order.js" defer></script>
   <script src="admin_payment.js" defer></script>

   <style>
    /* Loader Overlay */
    .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none; /* Hidden by default */
        }

        /* Spinner Animation */
        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #8dc641; /* Green spinner */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Welcome Message */
        .welcome-message {
            margin-top: 20px;
            padding: 15px;
            background: #8dc641;
            color: white;
            border-radius: 8px;
            font-size: 18px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li onclick="loadContent('dashboard.php')">
                <i class="fas fa-tachometer-alt"></i><a href="#">Dashboard</a>
            </li>
            <li onclick="loadContent('pContent.php'); toggleSubmenu(this);">
                <i class="fas fa-box"></i>
                <a href="#">Products</a>
                <i class="fas fa-chevron-down arrow" style="margin-left: auto;"></i>
            </li>
            <ul class="submenu">
                <li onclick="loadContent('medicine.php')"><i class="fas fa-pills"></i><a href="#">Medicine</a></li>
                <li onclick="loadContent('oralcare.php')"><i class="fas fa-home"></i><a href="#">Daily Care</a></li>
                <li onclick="loadContent('babycare.php')"><i class="fas fa-baby"></i><a href="#">Mother & Baby Care</a></li>
                <li onclick="loadContent('pcare.php')"><i class="fas fa-user"></i><a href="#">Personal Care</a></li>
                <li onclick="loadContent('ayurveda.php')"><i class="fas fa-leaf"></i><a href="#">Ayurveda</a></li>
            </ul>
            <li onclick="loadContent('admin_orders.php')"><i class="fas fa-shopping-cart"></i><a href="#">Orders</a></li>
            <li onclick="loadContent('usertbl.php')"><i class="fas fa-users"></i><a href="#" id="user-nav">Users</a></li>
            <li onclick="loadContent('feedback.php')"><i class="fas fa-comments"></i><a href="#">Customer Review</a></li>
            <li onclick="loadContent('admin_prescription.php')"><i class="fas fa-file-medical"></i><a href="#">Prescription</a></li>
            <li onclick="loadContent('admin_payment.php')"><i class="fas fa-money-bill-wave"></i><a href="#">Payments</a></li>
            <li onclick="logoutAdmin()"><i class="fas fa-sign-out-alt"></i><a href="#">Logout</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="content" id="content">
        <div class="content-container" id="content-container">
            <p>Select a menu option to display content here.</p>

            <!-- Welcome Message -->
            <div class="welcome-message" id="welcomeMessage">Welcome to Admin Panel!</div>
        </div>
    </div>

    <!-- Loader Overlay (for Logout Animation) -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="loader"></div>
    </div>

    <!-- Logout Script -->
    <script>
        function logoutAdmin() {
            // Show loader animation
            document.getElementById('loaderOverlay').style.display = 'flex';

            // Redirect after 800ms
            setTimeout(function () {
                window.location.href = 'admin_logout.php';
            }, 800);
        }

        // Ensure Welcome Message stays visible
        window.addEventListener('DOMContentLoaded', () => {
            const message = document.getElementById('welcomeMessage');
            if (message) message.style.display = 'block';
        });
    </script>

</body>

</html>
