// Initialize prescription management when DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded. Initializing prescription management...");
    initializePrescriptionManagement();
});

// Event delegation for dynamically added buttons
document.addEventListener("click", function (event) {
    // Approve button click
    if (event.target.classList.contains("approve")) {
        const prescriptionId = event.target.getAttribute("data-prescription-id");
        console.log(`Approve button clicked for prescription ID: ${prescriptionId}`);
        approvePrescription(prescriptionId);
    }

    // Reject button click
    if (event.target.classList.contains("reject")) {
        const prescriptionId = event.target.getAttribute("data-prescription-id");
        console.log(`Reject button clicked for prescription ID: ${prescriptionId}`);
        rejectPrescription(prescriptionId);
    }

    // Select Medicine button click
    if (event.target.classList.contains("select-medicine")) {
        const prescriptionId = event.target.getAttribute("data-prescription-id");
        console.log(`Select Medicine button clicked for prescription ID: ${prescriptionId}`);
        openMedicineModal(prescriptionId);
    }

    // View Prescription File button click
    if (event.target.classList.contains("view-file")) {
        event.preventDefault(); // Prevent default link behavior
        const filePath = event.target.getAttribute("data-file");
        console.log(`View button clicked for file: ${filePath}`);
        openFileModal(filePath);
    }
});

// Function to open the file modal and display the prescription file
function openFileModal(filePath) {
    console.log(`Opening file modal for file: ${filePath}`);
    const fileModal = document.getElementById("fileModal");
    const fileContent = document.getElementById("fileContent");

    if (fileModal && fileContent) {
        // Clear previous content
        fileContent.innerHTML = "";

        // Check if the file is an image (e.g., JPG, PNG)
        if (/\.(jpe?g|png|gif)$/i.test(filePath)) {
            fileContent.innerHTML = `<img src="${filePath}" alt="Prescription File" style="max-width: 100%; max-height: 80vh;">`;
        }
        // Check if the file is a PDF
        else if (/\.pdf$/i.test(filePath)) {
            fileContent.innerHTML = `<embed src="${filePath}" type="application/pdf" width="100%" height="600px" />`;
        }
        // Handle other file types (e.g., display a download link)
        else {
            fileContent.innerHTML = `<p>File type not supported. <a href="${filePath}" download>Download File</a></p>`;
        }

        // Show the modal
        fileModal.style.display = "block";
    } else {
        console.error(" fileModal or fileContent not found in DOM!");
    }
}

// Function to close the file modal
function closeFileModal() {
    console.log("Closing file modal.");
    const fileModal = document.getElementById("fileModal");
    if (fileModal) {
        fileModal.style.display = "none";
    } else {
        console.error(" fileModal not found in DOM!");
    }
}

// Function to approve a prescription only if medicines are selected
function approvePrescription(prescriptionId) {
    fetch(`check_medicine_selection.php?prescription_id=${prescriptionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.medicines_selected) {
                if (confirm("Are you sure you want to approve this prescription?")) {
                    fetch(`process_approval.php`, {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `prescription_id=${prescriptionId}&action=approve`
                    })
                    .then(response => response.json())
                    .then(responseData => {
                        alert(responseData.message);
                        if (responseData.success) {
                            updatePrescriptionStatus(prescriptionId, "Approved", "status-approved");
                        }
                    });
                }
            } else {
                alert(" You must select medicines before approving this prescription.");
            }
        });
}

// Function to reject a prescription
function rejectPrescription(prescriptionId) {
    console.log("Rejecting prescription with ID:", prescriptionId); // Debugging log

    if (!prescriptionId) {
        alert("Error: Prescription ID is missing!");
        return;
    }

    if (confirm("Are you sure you want to reject this prescription?")) {
        fetch(`process_approval.php`, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `prescription_id=${prescriptionId}&action=reject`
        })
        .then(response => response.json())
        .then(data => {
            console.log("Server Response:", data); //  Debugging response
            alert(data.message);
            if (data.success) {
                updatePrescriptionStatus(prescriptionId, "Rejected", "status-rejected");
            }
        })
        .catch(error => console.error(" Fetch Error:", error)); //  Log fetch errors
    }
}


// Function to update prescription status in the table
function updatePrescriptionStatus(prescriptionId, statusText, statusClass) {
    console.log(`Updating status for prescription ID: ${prescriptionId}`);
    const statusCell = document.querySelector(`#status-${prescriptionId}`);
    const actionCell = document.querySelector(`#action-buttons-${prescriptionId}`);

    if (statusCell) {
        console.log("Status cell found:", statusCell);
        statusCell.textContent = statusText;
        statusCell.className = statusClass;
    } else {
        console.error("Status cell not found!");
    }

    if (actionCell) {
        console.log("Action cell found:", actionCell);
        actionCell.innerHTML = ""; // Clear all buttons (including "Select Medicine")
    } else {
        console.error("Action cell not found!");
    }
}

// Function to open medicine selection modal
function openMedicineModal(prescriptionId) {
    console.log(`Opening medicine modal for prescription ID: ${prescriptionId}`);
    const prescriptionIdInput = document.getElementById("prescriptionId");
    const medicineModal = document.getElementById("medicineModal");

    if (prescriptionIdInput && medicineModal) {
        prescriptionIdInput.value = prescriptionId;
        medicineModal.style.display = "block";
    } else {
        console.error(" prescriptionId input or medicineModal not found in DOM!");
    }
}

// Function to close medicine modal
function closeMedicineModal() {
    console.log("Closing medicine modal.");
    const medicineModal = document.getElementById("medicineModal");
    if (medicineModal) {
        medicineModal.style.display = "none";
    } else {
        console.error(" medicineModal not found in DOM!");
    }
}

// Function to submit selected medicines
function submitMedicineSelection(event) {
    event.preventDefault();
    console.log(" Submitting medicine selection...");

    const formData = new FormData();
    const selectedMedicines = [];
    const selectedQuantities = [];

    document.querySelectorAll('input[name="medicines[]"]:checked').forEach(checkbox => {
        selectedMedicines.push(checkbox.value);
        const quantityInput = checkbox.closest("tr").querySelector('input[name="quantity[]"]');
        selectedQuantities.push(quantityInput.value);
    });

    if (selectedMedicines.length === 0) {
        alert(" Please select at least one medicine.");
        return;
    }

    selectedMedicines.forEach((med, index) => {
        formData.append("medicines[]", med);
        formData.append("quantity[]", selectedQuantities[index]);
    });

    formData.append("prescription_id", document.getElementById("prescriptionId").value);

    fetch("process_medicine_selection.php", {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Medicine selection response:", data);
        if (data.success) {
            alert(data.message); // Show alert only once
            closeMedicineModal();

            // Update the table status
            const prescriptionId = document.getElementById("prescriptionId").value;
            updatePrescriptionStatus(prescriptionId, "Medicines Selected", "status-selected");
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error(" Fetch Error:", error);
        alert("An error occurred while submitting the medicine selection.");
    });
}

// Initialize prescription management
function initializePrescriptionManagement() {
    console.log(" Initializing prescription management...");

    // Attach event listener to medicine selection form
    const medicineForm = document.getElementById("medicineForm");
    if (medicineForm) {
        console.log(" Medicine form found!");
        medicineForm.addEventListener("submit", submitMedicineSelection);
    } else {
        console.error(" medicineForm not found in DOM!");
    }
}