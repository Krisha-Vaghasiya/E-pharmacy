<?php
session_start();
require_once 'db_connection.php';

$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    // Fetch cart count from database
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT SUM(quantity) AS cart_count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cart_count = $row['cart_count'] ?? 0;
} else {
    // Get count from session cart
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            if (is_array($item) && isset($item['quantity'])) { // Ensure it's an array
                $cart_count += $item['quantity'];
            }
        }
    }   
} 
?>  
<?php
$user_id = $_SESSION['user_id'] ?? 0;  // Check if user is logged in

$wishlistCount = 0;  // Default count if user is not logged in
if ($user_id) {
    $countQuery = "SELECT COUNT(*) AS total FROM wishlist WHERE user_id = '$user_id'";
    $countResult = $conn->query($countQuery);
    if ($countResult && $countResult->num_rows > 0) {
        $row = $countResult->fetch_assoc();
        $wishlistCount = $row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Pharmacy</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="search.css">
    <link rel="stylesheet" href="card.css">
    <script src="wishlist.js"></script>
    <style>
      body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:#fff;
        } 
        header {
            background-color: #8dc641;
            padding: 10px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
           padding: 0px 40px;
            background-color: #ffffff;
            width: 100%;
            

        }
        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
            
        }
        .logo img {
            height: 80px;
        }
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: #1996b2;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-right: 90px;
        }
        .header-right a {
            color: #1996b2;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 5px;
 }
  
 
 .search-section {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #8dc641;
    padding: 10px;
    gap: 50px; /* Reduced gap for better alignment */
    height:50px;
}

.search-bar {
    position: relative;
    display: flex;
    align-items: center;
    gap: 6px;
    background: #8dc641;
    padding: 3px;
    border-radius: 5px;
    margin-bottom: -10px;
    width: 100%;
    max-width: 500px; /* Reduced max width for search bar */
}

.search-bar input {
    padding: 6px;
    border: 1px solid #1996b2;
    border-radius: 5px;
    font-size: 13px;
    outline: none;
    flex: 1;
    height: 30px;
    background-color:white;
    color:black;
}

.search-bar button {
    padding: 5px 10px;
    background-color: #1996b2;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 13px;
    height: 30px;
    white-space: nowrap;
    width:15%;
    margin-bottom:10px;
}

.search-bar button:hover {
    background-color: #157a92;
}
input::placeholder {
    color: #666; /* Dark gray for better visibility */
    font-weight: normal;
}


/* Adjusted upload prescription section */
.upload-prescription {
    color: white;
    font-size: 16px;
    margin-left: 20px; /* Added margin to create gap */
} 

  
        .navbar-section {
            background-color: #ffffff;
            padding:10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items:center;
            height:50px;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin:0;
            display: flex;
            gap: 30px;
        }
        nav ul li a {
            color: #8dc641;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            padding: 5px 15px;    /* Add padding for better spacing */
            transition: all 0.3s ease;
        }
        nav ul li a:hover {
         background-color:#e0e0e0;
             border-radius: 5px;   /* Rounded corners on hover */
        }
        .categories {
            display: flex;
            justify-content: center;
            flex-wrap: nowrap;
            gap: 20px;
            padding: 10px;
            height:110px;
          
        }
        .category img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }
        .category p {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            margin-top: 5px;
            text-decoration: none;
            color: #1996b2;
        }
        a {
    text-decoration: none !important; /* Force remove underline */
}
        
        .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        min-width: 200px;
        border-radius: 5px;
        top: 100%; /* Ensure it appears below the "Categories" */
        left: 0;
        padding: 5px 0;
    }

    .dropdown-content a {
        color: #1996b2;
        padding: 10px 20px;
        text-decoration: none;
        display: block;
        font-size: 16px;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    /* Show dropdown on hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }
    /* Hamburger icon style */
/* Add this to your existing CSS at the bottom */

/* Menu Toggle Hidden by Default */
.menu-toggle {
    display: none;
    font-size: 28px;
    cursor: pointer;
    color: #1996b2;
}

/* Small Screen Adjustments */
@media (max-width: 768px) {
    .header-section {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 20px;
    }

    .menu-toggle {
        display: block;
        margin-left: auto;
        margin-bottom: 10px;
    }

    .header-right {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        margin-right: 0;
        width: 100%;
        display: none;
    }

    .header-right a {
        font-size: 14px;
    }

    .header-section.active .header-right {
        display: flex;
    }

    .search-section {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }

    .upload-prescription {
        margin-left: 0;
        font-size: 14px;
    }

    .navbar-section {
        flex-direction: column;
        padding: 10px;
    }

    nav ul {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }

    nav ul li a {
        font-size: 16px;
    }
}

  

      /* Modal Styles */
      .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: #fff; /* Simple white background */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    border-radius: 15px;
    padding: 30px; /* Increased padding for better spacing */
    text-align: center;
    width: 380px; /* Increased width */
    position: relative;
    color: black;
}

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
    color: black;
}

.input-container {
    position: relative;
    width: 85%; /* Increased width */
    margin: 15px auto; /* More spacing */
    display: flex;
    justify-content: center;
}

.input-container input {
    width: 100%;
    padding: 12px 12px 12px 40px; /* Increased padding */
    border: 1px solid #ccc;
    border-radius: 5px;
    background: #f9f9f9;
    color: black;
    outline: none;
    font-size: 16px;
}

.input-container .material-icons {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.input-container input:focus {
    border-color: #127fd7;
}

.input-container input:focus::placeholder {
    color: transparent;
}

button {
    width: 50%; /* Increased width */
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #8dc641;
    color: white;
    cursor: pointer;
    margin-top: 15px; /* More spacing */
    margin-bottom: 15px;
    font-size: 16px;
}

button:hover {
    background:rgb(128, 192, 45);
}




.cart-container {
position: relative;
display: inline-block;
}

.cart-count {
    position: absolute;
    top: -10px;
    
    left: 12px;
    background: #8dc641;
    color: white;
    font-size: 10px;
    padding: 1px 6px;
    border-radius: 50%;
    display: none;
}

.cart-message {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: green;
    color: white;
    padding: 10px;
    border-radius: 5px;
    z-index: 1000;
}
/* Wishlist Icon */
.wishlist-icon {
        position: absolute;
        top: 6px;
        right: 8px;
        font-size: 22px;
        color: gray;
        cursor: pointer;
        transition: color 0.3s;
    }
    .wishlist-icon:hover {
    color: #5a8c19; /* Change text/icon color on hover */
}
 

.wishlist-icon.filled {
    color: #167b99;         /* Red color for filled */
}
.order-link {
    display: flex;
    align-items: center;
    gap: 5px; /* Space between icon and text */
    text-decoration: none;
    color: #333;
    font-size: 16px;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 6px;
    transition: background 0.3s ease, color 0.3s ease;
}

.order-link .material-icons {
    font-size: 16px; /* Match other icons */
    vertical-align: middle;
    color: #1996b2; /* Same as primary site color */
}

.order-link:hover {
    background: #007bff;
    color: white;
}

.order-link:hover .material-icons {
    color: white;
}

      
/* Fix SweetAlert2 confirm button */
.swal2-confirm {
    background-color: #28a745 !important;  /* Green color */
    color: white !important;              /* White text */
    border: none !important;
    border-radius: 8px !important;        /* Rounded corners */
    padding: 12px 25px !important;        /* Bigger button */
    font-size: 18px !important;           /* Bigger text */
    cursor: pointer !important;
    box-shadow: none !important;
    min-width: 120px !important;          /* Ensures width */
    min-height: 45px !important;          /* Ensures height */
}

/* Ensure button styling applies */
.swal2-confirm:hover {
    background-color: #218838 !important;  /* Darker green on hover */
}
.error-message {
     color: #fff;
    font-size: 12px;
    margin-top: 3px;
    display: block;
    text-align: left;
    width: 100%;
    padding-left: 5px;
}

.is-invalid {
    border: 2px solid #8dc641 !important;  /* Changed to red for better visibility */
}

.input-container {
    position: relative;
    width: 80%;
    margin: 10px auto;
    display: flex;
    flex-direction: column;  /* Ensures error message appears below */
    align-items: flex-start;  /* Align items to the left */
}




    </style>
   <!-- Load jQuery -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load jQuery Validation Plugin (Latest Version) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
$(document).ready(function () {
    // Custom validation for phone number (must start with 6-9 and be 10 digits)
    $.validator.addMethod("phoneStart", function (value, element) {
        return this.optional(element) || /^[6-9]\d{9}$/.test(value);
    }, "Phone number must start with 6, 7, 8, or 9 and be 10 digits long");

    // Custom validation for allowed email domains
    $.validator.addMethod("validDomain", function (value, element) {
        let allowedDomains = ["gmail.com", "yahoo.com", "outlook.com", "hotmail.com", "icloud.com", "rediffmail.com"];
        let emailDomain = value.split("@")[1]; // Extract domain
        return this.optional(element) || (emailDomain && allowedDomains.includes(emailDomain));
    }, "Allowed domains: Gmail, Yahoo, Outlook, etc.");

    $("#registerForm").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2
            },
            last_name: {
                required: true,
                minlength: 2
            },
            email: {
                required: true,
                email: true,
                validDomain: true
            },
            phoneno: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
                phoneStart: true
            },
            city: {
                required: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            first_name: {
                required: "Please enter your first name",
                minlength: "At least 2 characters required"
            },
            last_name: {
                required: "Please enter your last name",
                minlength: "At least 2 characters required"
            },
            email: {
                required: "Please enter your email",
                email: "Enter a valid email",
                validDomain: "Allowed domains: Gmail, Yahoo, Outlook, etc."
            },
            phoneno: {
                required: "Enter your phone number",
                digits: "Only numbers allowed",
                minlength: "Must be 10 digits",
                maxlength: "Must be 10 digits",
                phoneStart: "Must start with 6, 7, 8, or 9"
            },
            city: {
                required: "Enter your city"
            },
            password: {
                required: "Enter a password",
                minlength: "At least 6 characters required"
            }
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("error-message");
            error.insertAfter(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
            $(element).removeClass("valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
            $(element).addClass("valid");
        },
        submitHandler: function (form) {
            event.preventDefault(); // Prevent default form submission

            console.log("Register button clicked"); // Debugging

            $.ajax({
                type: "POST",
                url: "/projectC/user_reg.php",
                data: $("#registerForm").serialize(),
                success: function (response) {
                    console.log("Server Response:", response); // Debug response
                    if (response.trim() === "success") {
                        alert("Registration successful!");
                        $("#registerModal").hide();
                        $("#registerForm")[0].reset();
                    } else {
                        alert("Registration failed: " + response);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert("AJAX Error: " + error);
                }
            });
        }
    });
});
</script>
<!-- Toast Notification -->
<!-- Include SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var message = sessionStorage.getItem("loginMessage");
        var messageType = sessionStorage.getItem("messageType");

        if (message) {
            Swal.fire({
                text: message,
                icon: messageType === "success" ? "success" : "error",
                confirmButtonColor: messageType === "success" ? "#28a745" : "#dc3545",
                confirmButtonText: "OK"
            });

            sessionStorage.removeItem("loginMessage");
            sessionStorage.removeItem("messageType");
        }
    });
</script>
<script>
        function openUploadModal() {
            document.getElementById("uploadModal").style.display = "block";
        }

        function closeUploadModal() {
            document.getElementById("uploadModal").style.display = "none";
        }

        // Ensure the DOM is fully loaded before running the script
        document.addEventListener('DOMContentLoaded', function () {
            const prescriptionForm = document.getElementById('prescriptionForm');

            if (prescriptionForm) {
                prescriptionForm.addEventListener('submit', function (e) {
                    e.preventDefault(); // Prevent the default form submission

                    const formData = new FormData(this); // Create FormData object from the form

                    fetch('upload_prescription.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok.");
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message); // Show success message
                            closeUploadModal(); // Close the modal
                        } else {
                            alert(data.message); // Show error message
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert("An error occurred while uploading the prescription.");
                    });
                });
            } else {
                console.error("Form with ID 'prescriptionForm' not found.");
            }
        });
    </script>
  
</head>

    <header>
        <div class="header-section">
            <div class="logo-container">
                <div class="logo">
                    <img src="image/logo.jpg" alt="E-Pharmacy Logo">
                </div>
                <div class="logo-text">E-Pharmacy</div>
            </div>
            <span class="menu-toggle" onclick="toggleMenu()">☰</span>

            <div class="header-right">
                <a href="#login" id="loginButton">
                    <span class="material-icons">person</span> Hello, Log in
                </a>
                
                <a href="wishlist_page.php" class="wishlist-link">
                <span class="material-icons">favorite</span>Wishlist
                </a>

                <a href="cart.php" class="cart-container">
                 <span class="material-icons">shopping_cart</span> Cart 
                <span id="cart-count" class="cart-count" style="display: none;">0</span>
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>
               
                 <a href="my_order.php" class="order-link">
                <span class="material-icons">shopping_bag</span> My Orders
                 </a>
                <?php endif; ?>


            </div>
        </div>
        <div class="search-section"> 
        <div class="search-bar">
    <input type="text" id="searchBox" placeholder="What are you looking for?" autocomplete="off" onkeyup="searchMedicine()">
    <button type="button" onclick="redirectToSearch()">Search</button>
    <div id="searchResults" class="dropdown-content"></div>
</div>

<div class="upload-prescription" style="color: white; font-size: 18px;">
            📄 Order with prescription. 
            <a href="#" onclick="openUploadModal()" style="color: #1996b2; font-size: 18px; font-weight: 600; background: white; padding: 5px 10px; border-radius: 5px; text-decoration: none;">UPLOAD NOW</a>
        </div>
    </header>

    <!-- Modal for Upload Prescription -->
    <div id="uploadModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
        background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); z-index: 1000;">
        <h3>Upload Prescription</h3>
        <form id="prescriptionForm" enctype="multipart/form-data">
            <input type="file" name="prescription" required accept=".jpg, .jpeg, .png, .pdf">
            <button type="submit">Upload</button>
            <button type="button" onclick="closeUploadModal()">Cancel</button>
        </form>
    </div>



        </div>
    </header>
    
    <div class="navbar-section">
        <nav>
            <ul>
                <li><a href="/projectC/home.php">Home</a></li>
                <li><a href="aboutUs.php">About</a></li>
                <li><a href="contactUs.php">Contact Us</a></li>
                
                <li class="dropdown">
                    <a href="#categories">Categories ▾</a>
                    <div class="dropdown-content">
                        <a href="medicine/mainmedicine.php">Medicine</a>
                        <a href="maindailycare.php">Daily Care</a>
                        <a href="mother-care.php">Mother & Baby Care</a>
                        <a href="ayurveda.php">Ayurveda</a>
                        <a href="dpc.php">Personal Care</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
    
    <section class="categories">
    <a href="medicine/mainmedicine.php" class="category">
        <img src="image/medicineN.jpg" alt="Medicine">
        <p>Medicine</p>
    </a>
    <a href="dpc.php" class="category">
        <img src="image/personal care.jpg" alt="Health & Beauty">
        <p>Personal Care</p>
    </a>
    <a href="maindailycare.php" class="category">
        <img src="image/homecare.png" alt="Home Care">
        <p>Daily Care</p>
    </a>
    <a href="mother-care.php" class="category">
        <img src="image/baby.png" alt="Mother & Baby Care">
        <p>Mother & Baby Care</p>
    </a>
    <a href="ayurveda.php" class="category">
        <img src="image/aayurveda.jpg" alt="Ayurvedic">
        <p>Ayurvedic</p>
    </a>
</section>

     <!-- Login Modal -->
      <form action="/projectC/user_login.php" method="post">
     <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 style="color: #1996b2; padding-bottom:10px" >Login</h2>
            <div class="input-container">
                <input type="email" id="loginEmail" name="email" placeholder="Email" required>
                <span class="material-icons">email</span>
            </div>
            <div class="input-container">
                <input type="password" id="loginPassword" name="password"  placeholder="Password" required>
                <span class="material-icons">lock</span>
                
            </div>
            <button onclick="validateLogin()">Login</button>
            <p>Don't have an account? <a href="#" id="showRegister" name="login" style="color: #1996b2; font-weight: bold;">Register</a></p>
            <p><a href="forgot_password.php" style="color: #1996b2; font-weight: bold;">Forgot Password?</a></p>

        </div>
    </div>
</form>

    <!-- Register Modal -->
    <form id="registerForm">
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 style="color: #1996b2">Register</h2>

            <div class="input-container">
                <input type="text" id="firstName" name="first_name" placeholder="First Name" required>
                <span class="material-icons">person</span>
                <div class="error-message"></div> <!-- Error message placeholder -->
            </div>

            <div class="input-container">
                <input type="text" id="lastName" name="last_name" placeholder="Last Name" required>
                <span class="material-icons" >person</span>
                <div class="error-message"></div>
            </div>

            <div class="input-container">
                <input type="email" id="registerEmail" name="email" placeholder="Email" required>
                <span class="material-icons">email</span>
                <div class="error-message"></div>
            </div>

            <div class="input-container">
                <input type="tel" id="phoneNumber" name="phoneno" placeholder="Phone No" required>
                <span class="material-icons">phone</span>
                <div class="error-message"></div>
            </div>

            <div class="input-container">
                <input type="text" id="city" name="city" placeholder="City" required>
                <span class="material-icons">location_city</span>
                <div class="error-message"></div>
            </div>

            <div class="input-container">
                <input type="password" id="registerPassword" name="password" placeholder="Password" required>
                <span class="material-icons" style="position: absolute;transform: translateY(-50%);">lock</span>
                <div class="error-message"></div>
            </div>

            <button type="submit ">Register</button>
            <p>Already have an account? <a href="#" id="showLogin" style="color: #1996b2; font-weight: bold;">Login</a></p>
        </div>
    </div>
</form>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let loginModal = document.getElementById("loginModal");
            let registerModal = document.getElementById("registerModal");
            let loginButton = document.getElementById("loginButton");
            let showRegister = document.getElementById("showRegister");
            let showLogin = document.getElementById("showLogin");
            let closeButtons = document.querySelectorAll(".close");

            loginButton.addEventListener("click", function () {
                loginModal.style.display = "flex";
            });

            showRegister.addEventListener("click", function (event) {
                event.preventDefault();
                loginModal.style.display = "none";
                registerModal.style.display = "flex";
            });

            showLogin.addEventListener("click", function (event) {
                event.preventDefault();
                registerModal.style.display = "none";
                loginModal.style.display = "flex";
            });

            closeButtons.forEach(button => {
                button.addEventListener("click", function () {
                    loginModal.style.display = "none";
                    registerModal.style.display = "none";
                });
            });
        });
     // validation for form field

     function validateLogin() {
    let email = document.getElementById("loginEmail");
    let password = document.getElementById("loginPassword");
    
    clearErrors();

    let valid = true;
    if (!email.value.trim()) {
        showError(email, "Email is required");
        valid = false;
    } else if (!isValidEmail(email.value)) {
        showError(email, "Enter a valid email");
        valid = false;
    }

    if (!password.value.trim()) {
        showError(password, "Password is required");
        valid = false;
    }
    
    return valid;
}

function validateLogin() {
    let email = document.getElementById("loginEmail");
    let password = document.getElementById("loginPassword");
    
    clearErrors();

    let valid = true;
    if (!email.value.trim()) {
        showError(email, "Email is required");
        valid = false;
    } else if (!isValidEmail(email.value)) {
        showError(email, "Enter a valid email");
        valid = false;
    }

    if (!password.value.trim()) {
        showError(password, "Password is required");
        valid = false;
    }
    
    return valid;
}
</script>
<script>
    function toggleMenu() {
        const header = document.querySelector('.header-section');
        header.classList.toggle('active');
    }
</script>


<script src="/projectC/cart-script.js" defer></script>
<script src="search.js" defer></script>
</body>
</html>
