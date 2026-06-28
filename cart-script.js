document.addEventListener("DOMContentLoaded", function () {
    fetchCart(); // Fetch cart data on page load
});

// Fetch Cart Data and Update Badge & Order Summary
function fetchCart() {
    fetch('/projectC/fetch_cart.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Update cart count
                let cartCount = document.getElementById("cart-count");
                if (cartCount) {
                    cartCount.textContent = data.cart_count || 0;
                    cartCount.style.display = data.cart_count > 0 ? "inline-block" : "none";
                }

                // Update order summary
                updateOrderSummary(data);
            } else {
                console.warn(" Cart is empty or API error:", data.message);
            }
        })
        .catch(error => console.error(" Fetch cart error:", error));
}

// Update Order Summary
function updateOrderSummary(data) {
    if (data.status === "success") {
        document.getElementById("total-items").textContent = data.total_items || 0;
        document.getElementById("total-price").textContent = data.total_price.toFixed(2);
        document.getElementById("total-discount").textContent = data.total_discount.toFixed(2);
        document.getElementById("final-price").textContent = (data.total_price - data.total_discount).toFixed(2);
    }
}

// Add to Cart
function addToCart(productId) {
    fetch("/projectC/add_to_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            fetchCart(); // Refresh cart data
            showMessage("Product added to cart!");
        } else {
            alert(" Failed to add product: " + data.message);
        }
    })
    .catch(error => console.error(" Add to cart error:", error));
}

// Remove from Cart
// Remove from Cart
function removeFromCart(productId) {
    fetch("/projectC/remove_from_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            showMessage(" Item removed from cart.");

            // Immediately remove the item from the UI
            const cartItem = document.querySelector(`.remove-item[data-id="${productId}"]`);
            if (cartItem) {
                cartItem.closest(".cart-item").remove();
            }

            // Update cart count and order summary
            fetchCart();
        } else {
            showMessage(` Failed to remove item: ${data.message}`, true);
        }
    })
    .catch(error => {
        console.error(" Remove from cart error:", error);
        showMessage(" An error occurred. Please try again.", true);
    });
}

// Update Quantity
function updateQuantity(productId, quantity) {
    if (quantity < 1) quantity = 1; // Prevent invalid input
    fetch("/projectC/cart_update.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            showMessage(" Cart updated.");
            fetchCart(); // Refresh cart data and update order summary
        } else {
            alert(`Failed to update quantity: ${data.message}`);
        }
    })
    .catch(error => console.error(" Update cart error:", error));
}

// Event Listeners
document.addEventListener("click", (event) => {
    if (event.target.classList.contains("add-to-cart")) {
        let productId = event.target.dataset.id;
        if (productId) addToCart(productId);
    }

    if (event.target.matches(".remove-item")) {
        let productId = event.target.getAttribute("data-id");
        if (productId) removeFromCart(productId);
    }

    if (event.target.classList.contains("increase") || event.target.classList.contains("decrease")) {
        let productId = event.target.getAttribute("data-id");
        let inputField = document.querySelector(`.cart-quantity[data-id="${productId}"]`);
        if (inputField) {
            let currentValue = parseInt(inputField.value) || 1;
            if (event.target.classList.contains("increase")) {
                inputField.value = Math.min(currentValue + 1, inputField.max);
            } else if (event.target.classList.contains("decrease")) {
                inputField.value = Math.max(currentValue - 1, inputField.min);
            }
            updateQuantity(productId, inputField.value); // Update quantity and refresh cart
        }
    }
});

// Proceed to Checkout
// Proceed to Checkout
function proceedToCheckout() {
    fetch('/projectC/check_login_status.php') // Check if the user is logged in
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json(); // Parse the response as JSON
        })
        .then(data => {
            if (data.loggedIn) {
                // User is logged in, redirect to checkout page
                window.location.href = "checkout.php";
            } else {
                // User is not logged in, show a message
                showMessage("Please log in or create an account to proceed to checkout.", true);
            }
        })
        .catch(error => {
            console.error(" Check login status error:", error);
            
        });
}

// Show Floating Message
function showMessage(message, isError = false) {
    const floatingMessage = document.getElementById("floating-message");
    if (!floatingMessage) return;

    floatingMessage.innerHTML = message; // Allow HTML content
    floatingMessage.className = "floating-message"; // Reset class
    if (isError) {
        floatingMessage.classList.add("error"); // Add error class for red background
    }
    floatingMessage.style.display = "block"; // Show the message

    // Hide the message after 3 seconds
    setTimeout(() => {
        floatingMessage.style.display = "none";
    }, 3000);
}