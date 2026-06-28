// Function to toggle submenu visibility
function toggleSubmenu(element) {
    let submenu = element.nextElementSibling;
    if (submenu && submenu.classList.contains('submenu')) {
        submenu.style.display = (submenu.style.display === 'block') ? 'none' : 'block';
    }
}

// Function to load external HTML content dynamically
// Function to load external HTML content dynamically


// Function to handle form submission via AJAX
function handleFormSubmit(event) {
    event.preventDefault(); 

    let formData = new FormData(this);
    if (!formData.get("subcategory_id") || formData.get("subcategory_id") === "") {
        formData.set("subcategory_id", "0"); // Default to 0 if null
    }


    fetch("pInsert.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data.trim()); 
        closeForm(); 
        loadContent('pContent.php'); 
    })
    .catch(error => console.error("Error inserting product:", error));
}

// Function to handle edit form submission via AJAX

// Function to handle edit form submission via AJAX
function handleEditFormSubmit(event) {
    event.preventDefault(); 
    let formData = new FormData(this);
    if (!formData.get("subcategory_id") || formData.get("subcategory_id") === "") {
        formData.set("subcategory_id", "0"); // Default to 0 if null
    }
    console.log("Submitting Form Data:", Object.fromEntries(formData.entries())); // Debugging

    fetch("pUpdate.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert(" Product updated successfully!");
            updateTableRow(formData.get("product_id"), formData);

            let productImage = document.getElementById("product-image-" + formData.get("product_id"));
            if (productImage) {
                productImage.src = data.image_url + "?" + new Date().getTime();
            }
            
            closeEditForm();
        } else {
            alert("Error updating product: " + data.message);
        }
    })
    .catch(error => console.error(" Error updating product:", error));
}


function updateTableRow(productId, formData) {
    let row = document.querySelector(`tr[data-id='${productId}']`);
    if (!row) return; // If row not found, do nothing

    row.querySelector(".product-name").textContent = formData.get("product_name");
    row.querySelector(".product-brand").textContent = formData.get("brand");
    row.querySelector(".product-description").textContent = formData.get("description");
    row.querySelector(".product-category").textContent = formData.get("category_id");
    row.querySelector(".product-subcategory").textContent = formData.get("subcategory_id");
    row.querySelector(".product-price").textContent = formData.get("price");
    row.querySelector(".product-quantity").textContent = formData.get("quantity");
    row.querySelector(".product-expiry").textContent = formData.get("expiry_date");
}

function handleDeleteClick(event) {
    event.preventDefault();
    let productId = event.currentTarget.dataset.id;
    deleteProduct(productId, event.currentTarget.closest("tr"));
}

function rebindEventListeners() {
    document.querySelector(".insert-btn")?.addEventListener("click", openForm);

    document.getElementById("popupForm")?.querySelector("form")?.addEventListener("submit", handleFormSubmit);
    document.getElementById("editPopupForm")?.querySelector("form")?.addEventListener("submit", handleEditFormSubmit);

    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.removeEventListener("click", openEditForm);
        btn.addEventListener("click", openEditForm);
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.removeEventListener("click", handleDeleteClick);
        btn.addEventListener("click", handleDeleteClick);
    });
}


// Function to delete the product
function deleteProduct(productId, row) {
    if (!confirm("Are you sure you want to delete this product?")) return;

    fetch(`pDelete.php?id=${productId}`, { method: 'GET' })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            alert(" Product deleted successfully!");
            row?.remove();
        } else {
            alert(" Error deleting product: " + data);
        }
    })
    .catch(error => console.error(" Error deleting product:", error));

}

// Function to open "Add Product" popup
function openForm() {
    let popup = document.getElementById('popupForm');
    if (popup) {
        popup.style.display = 'block';
    } else {
        console.error(" Error: popupForm element not found!");
    }
}

// Function to open "Edit Product" popup
// Function to open "Edit Product" popup
function openEditForm(event) {
    event.preventDefault();
    let btn = event.currentTarget;

    console.log(" Button Clicked:", btn);  // Debugging
    console.log(" Dataset Values:", btn.dataset); // Debugging

    document.getElementById("edit_product_id").value = btn.dataset.id || '';  // Ensure this is set
    document.getElementById("edit_product_name").value = btn.dataset.name || '';
    document.getElementById("edit_product_brand").value = btn.dataset.brand || '';
    document.getElementById("edit_description").value = btn.dataset.description || '';
    document.getElementById("edit_category_id").value = btn.dataset.category || '';
    document.getElementById("edit_subcategory_id").value = btn.dataset.subcategory || '0'; // Default to 0 if null
    document.getElementById("edit_price").value = btn.dataset.price || '';
    document.getElementById("edit_quantity").value = btn.dataset.quantity || '';
    document.getElementById("edit_expiry_date").value = btn.dataset.expiry || '';

    document.getElementById("editPopupForm").style.display = "block";
}



// Bind event listeners dynamically
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", openEditForm);
    });
});

// Function to close popups
function closeForm() { document.getElementById('popupForm').style.display = 'none'; }
function closeEditForm() { document.getElementById('editPopupForm').style.display = 'none'; }

// Function to rebind event listeners for dynamically loaded content
/*function rebindEventListeners() {
    document.querySelector(".insert-btn")?.addEventListener("click", openForm);
    
    document.getElementById("popupForm")?.querySelector("form")?.addEventListener("submit", handleFormSubmit);
    document.getElementById("editPopupForm")?.querySelector("form")?.addEventListener("submit", handleEditFormSubmit);

    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.removeEventListener("click", openEditForm);
        btn.addEventListener("click", openEditForm);
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.removeEventListener("click", (event) => {
            event.preventDefault();
            let productId = btn.dataset.id;
            deleteProduct(productId, btn.closest("tr"));
        });
        btn.addEventListener("click", (event) => {
            event.preventDefault();
            let productId = btn.dataset.id;
            deleteProduct(productId, btn.closest("tr"));
        });
    });
}*/

// Initialize event listeners
document.addEventListener("DOMContentLoaded", rebindEventListeners);
console.log("pScript.js is executing!");

if (typeof rebindEventListeners !== "function") {
    console.error(" ERROR: `rebindEventListeners` is NOT defined!");
} else {
    console.log(" `rebindEventListeners` is ready.");
    rebindEventListeners();
}
