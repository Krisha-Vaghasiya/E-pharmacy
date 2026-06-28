$(document).ready(function () {
    loadFeedback();

    // Load feedback data dynamically
    function loadFeedback() {
        $.ajax({
            url: "fetch_feedback.php",
            method: "GET",
            data: $("#filter-form").serialize(),
            success: function (response) {
                $("#feedback-container").html(response);
                attachDeleteEvent(); // Ensure delete button works after reload
            },
            error: function () {
                alert("Error loading feedback.");
            }
        });
    }

    // Apply filters without reloading
    $("#filter-form").on("submit", function (e) {
        e.preventDefault();
        loadFeedback();
    });

    // Clear filters
    $("#clear-filter").on("click", function () {
        $("#filter-form")[0].reset(); // Clear form
        loadFeedback();
    });

    // Delete Feedback
    function attachDeleteEvent() {
        $(".delete-feedback").on("click", function () {
            if (!confirm("Are you sure?")) return;
            let id = $(this).data("id");
            $.post("delete_feedback.php", { id: id }, function () {
                alert("Feedback deleted!");
                loadFeedback();
            });
        });
    }
});
