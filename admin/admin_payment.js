function initializePaymentModule() {
    console.log("Initializing Payment Module...");

    // Manually call the event listener logic
    document.querySelectorAll(".confirm-btn").forEach((button) => {
        button.addEventListener("click", function () {
            const orderId = this.getAttribute("data-order-id");

            if (!orderId) {
                console.error(" Order ID is missing");
                return;
            }

            // Send AJAX request to confirm payment
            fetch("confirm_payment.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `order_id=${orderId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    const statusCell = document.querySelector(`td[data-order-id="${orderId}"]`);
                    if (statusCell) {
                        statusCell.textContent = "Confirmed";
                        statusCell.classList.remove("status-pending");
                        statusCell.classList.add("status-confirmed");
                    }

                    const button = document.querySelector(`button[data-order-id="${orderId}"]`);
                    if (button) {
                        button.textContent = "Confirmed";
                        button.classList.add("disabled-btn");
                        button.disabled = true;
                    }
                } else {
                    alert(" Error: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    });
}
