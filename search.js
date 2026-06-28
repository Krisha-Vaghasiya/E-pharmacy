function searchMedicine() { 
    let query = document.getElementById("searchBox").value.trim();
    let searchResults = document.getElementById("searchResults");

    if (query.length < 2) {
        searchResults.innerHTML = "";
        searchResults.style.display = "none";
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "search.php?query=" + encodeURIComponent(query), true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                let response = xhr.responseText.trim();
                searchResults.innerHTML = response;
                
                // Ensure dropdown appears when results exist
                if (response) {
                    searchResults.style.display = "block";
                } else {
                    searchResults.style.display = "none";
                }
            } else {
                console.error("Error loading search results");
            }
        }
    };
    
    xhr.send();
}

// Function to handle click on a suggestion (dropdown item)
function selectMedicine(name) {
    document.getElementById("searchBox").value = name;
    document.getElementById("searchResults").style.display = "none";

    // Redirect user to search results page when clicking on a suggestion
    window.location.href = "search-results.php?query=" + encodeURIComponent(name);
}

// Function to redirect when clicking on Search button
function redirectToSearch() {
    let query = document.getElementById("searchBox").value.trim();
    if (query !== "") {
        window.location.href = "search-results.php?query=" + encodeURIComponent(query);
    }
}

// Close dropdown when clicking outside
document.addEventListener("click", function (event) {
    let searchBox = document.getElementById("searchBox");
    let searchResults = document.getElementById("searchResults");
    if (!searchBox.contains(event.target) && !searchResults.contains(event.target)) {
        searchResults.style.display = "none";
    }
});
