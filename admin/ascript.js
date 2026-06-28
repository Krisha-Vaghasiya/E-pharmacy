console.log("✅ ascript.js Loaded!");

let isEventAttached = false; // Flag to track event listener attachment

// Attach event listeners for user table actions
function attachUserTableEvents() {
    const contentContainer = document.getElementById("content-container");

    // Avoid reattaching event listeners
    if (!isEventAttached) {
        contentContainer.addEventListener("click", handleUserTableClick);
        isEventAttached = true; // Set the flag to prevent multiple listeners
    }

    // Ensure password form submission works only once
   
}

// Centralized click handler for edit & delete actions
function handleUserTableClick(event) {
   

    if (event.target.classList.contains("delete-user")) {
        let userId = event.target.getAttribute("data-id");
        console.log("🗑 Delete Clicked for User ID:", userId);
        deleteUser(userId);
    }
}

// Open Password Edit Modal
function openModal(userId) {
    document.getElementById("user_id").value = userId;
  
}

// Close Modal




// Delete User via AJAX
function deleteUser(userId) {
    if (confirm("⚠ Are you sure you want to delete this user?")) {
        fetch("delete_user.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `user_id=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            console.log("🗑 Delete Response:", data);
            alert(data.message);
            if (data.status === "success") {
                loadContent("usertbl.php"); // Reload user table (already in adminMain.js)
            }
        })
        .catch(error => console.error("❌ Error deleting user:", error));
    }
}

// Ensure event listeners are attached when the page loads
document.addEventListener("DOMContentLoaded", function () {
    attachUserTableEvents();
});