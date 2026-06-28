function viewOrder(orderId, orderType, orderStatus) {
    fetch(`fetch_order_details.php?order_id=${orderId}&order_type=${orderType}`)
        .then(response => response.json())
        .then(data => {
            const orderDetails = document.getElementById('orderDetails');
            orderDetails.innerHTML = '';

            if (data.success) {
                // 🔹 Show Order Status
                let tableHTML = `<h4 style="margin-bottom: 10px;">Order Status: <span style="color: ${
                    orderStatus === 'Shipped' ? 'green' : 'orange'
                }">${orderStatus}</span></h4>`;

                tableHTML += `
                    <table class="order-details-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                                <th>Prescription File</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.items.forEach(item => {
                    const imageUrl = `admin/${item.image_url}`;
                    const prescriptionFile = item.prescription_file
                        ? `<a href="#" onclick="openPrescriptionModal('${item.prescription_file}')">View</a>`
                        : 'N/A';

                    // Action Buttons with Confirmation
                    let actionButtons = '';
                    if (orderStatus !== 'Shipped') {
                        if (orderType === 'Prescription Order') {
                            actionButtons += `<button class="approve-btn" onclick="approveOrder(${orderId})">Approve</button>`;
                        }
                        actionButtons += `<button class="cancel-btn" onclick="confirmCancel(${orderId}, '${orderType}')">Cancel</button>`;
                    }

                    tableHTML += `
                        <tr>
                            <td><img src="${imageUrl}" alt="${item.product_name}" style="width: 50px; height: 50px;"></td>
                            <td>${item.product_name}</td>
                            <td>${item.quantity}</td>
                            <td>₹${item.price}</td>
                            <td>₹${item.subtotal}</td>
                            <td>${prescriptionFile}</td>
                            <td>${actionButtons}</td>
                        </tr>
                    `;
                });

                tableHTML += `</tbody></table>`;
                orderDetails.innerHTML = tableHTML;
            } else {
                orderDetails.innerHTML = `<p>${data.message}</p>`;
            }

            document.getElementById('orderModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching order details.');
        });
}
function confirmCancel(orderId, orderType) {
    if (confirm('Are you sure you want to cancel this order?')) {
        cancelOrder(orderId, orderType);
    }
}




// Function to approve the order and redirect to checkout.php
function approveOrder(prescriptionId) {
    if (confirm("Are you sure you want to approve this prescription order?")) {
        window.location.href = `checkout.php?prescription_id=${prescriptionId}`;
    }
}

// Function to open the prescription modal
function openPrescriptionModal(prescriptionUrl) {
    const prescriptionContent = document.getElementById('prescriptionContent');
    prescriptionContent.innerHTML = `<img src="${prescriptionUrl}" alt="Prescription" style="max-width: 100%; height: auto;">`;
    document.getElementById('prescriptionModal').style.display = 'block';
}

// Function to close the prescription modal
function closePrescriptionModal() {
    document.getElementById('prescriptionModal').style.display = 'none';
}
// Function to close the order details modal
function closeModal() {
    document.getElementById('orderModal').style.display = 'none';
}

function cancelOrder(orderId, orderType) {
    fetch('cancel_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            order_id: orderId,
            order_type: orderType
        })
        
    })
    .then(response => response.text()) // Temporarily use text to debug
    .then(data => {
        console.log(" Raw response from cancel_order.php:", data);
        try {
            const json = JSON.parse(data);
            console.log(" Parsed JSON:", json);
            if (json.success) {
                alert(json.message);
                location.reload();
            } else {
                alert("Error: " + json.message);
            }
        } catch (e) {
            console.error(" JSON parsing error:", e);
            alert("Something went wrong. Check console.");
        }
    })
    .catch(error => {
        console.error("Fetch error:", error);
        alert("Fetch failed: " + error);
    });
}




