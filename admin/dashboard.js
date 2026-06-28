function initializeDashboard() {
    console.log(" Fetching Dashboard Data...");

    fetch('fetch_dashboard_data.php')
        .then(response => response.json())
        .then(data => {
            console.log(" Data received:", data);

            // Update dashboard values
            document.querySelector(".total-orders p").innerText = data.totalOrders;
            document.querySelector(".pending-orders p").innerText = data.pendingOrders;
            document.querySelector(".total-users p").innerText = data.totalUsers;
            document.querySelector(".revenue p").innerText = "₹" + data.totalRevenue;
            document.querySelector(".total-products p").innerText = data.totalProducts;
            document.querySelector(".low-stock p").innerText = data.lowStock;
        })
        .catch(error => console.error(" Fetch Error:", error));
}

// Call the function
initializeDashboard();
