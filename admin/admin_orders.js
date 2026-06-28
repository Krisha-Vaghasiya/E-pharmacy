

    function markAsDelivered(orderId) {
        if (confirm("Mark this order as Delivered?")) {
            updateOrderStatus(orderId, 'Delivered');
        }
    }

    function updateOrderStatus(orderId, status) {
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `order_id=${orderId}&status=${status}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        });
    }
    function viewOrderDetails(order_id, order_type) {
        console.log('Order ID:', order_id, 'Order Type:', order_type); // Check values
    
        fetch(`../fetch_order_details.php?order_id=${order_id}&order_type=${order_type}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.getElementById('orderModal');
                    const content = document.getElementById('orderDetailsContent');
    
                    content.innerHTML = "";
    
                    let tableHtml = `
                        <table class="order-details-table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Image</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                    ${data.items.some(item => item.prescription_file) ? '<th>Prescription</th>' : ''}
                                </tr>
                            </thead>
                            <tbody>
                    `;
    
                    data.items.forEach(item => {
                        tableHtml += `
                            <tr>
                                <td>${item.product_name}</td>
                                <td><img src="${item.image_url}" alt="${item.product_name}" width="50"></td>
                                <td>${item.quantity}</td>
                                <td>₹${parseFloat(item.price).toFixed(2)}</td>
                                <td>₹${parseFloat(item.subtotal).toFixed(2)}</td>
                                ${item.prescription_file ? `<td><a href="${item.prescription_file}" target="_blank">View</a></td>` : ''}
                            </tr>
                        `;
                    });
    
                    tableHtml += `</tbody></table>`;
                    content.innerHTML = tableHtml;
                    modal.style.display = 'block';
                } else {
                    alert(data.message || 'Failed to load order details');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching order details');
            });
    }
    
    // Attach event listeners for dynamically loaded buttons
    document.body.addEventListener("click", function(event) {
        if (event.target.classList.contains("view-order-btn")) {
            let orderId = event.target.dataset.orderId;  // Get order ID
            let orderType = event.target.dataset.orderType;  // Get order type
            viewOrderDetails(orderId, orderType);  // Pass both to the function
        }
    });
    
    

    // Close modal function
    function closeModal() {
        document.getElementById("orderModal").style.display = "none";
    }



    // Function to initialize event listeners for order management
    function initializeOrderManagement() {
        document.addEventListener("DOMContentLoaded", () => {
            // Close modal when clicking outside
            window.onclick = function(event) {
                let orderModal = document.getElementById("orderModal");
                if (event.target === orderModal) {
                    closeModal();
                }
            };

        });
    }

    // Initialize order management when the script loads
    initializeOrderManagement();
