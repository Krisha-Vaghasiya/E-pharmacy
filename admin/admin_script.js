$(document).ready(function () {
    $(".process-btn").click(function () {
        let prescriptionId = $(this).data("id");
        $("#prescription_id").val(prescriptionId);
        $("#prescriptionModal").fadeIn();
    });

    $(".close").click(function () {
        $("#prescriptionModal").fadeOut();
    });

    $("#medicineForm").submit(function (e) {
        e.preventDefault();
        let prescriptionId = $("#prescription_id").val();
        let medicine = $("#medicine").val();
        let quantity = $("#quantity").val();

        $.ajax({
            url: "process_prescription.php",
            type: "POST",
            data: { prescription_id: prescriptionId, medicine: medicine, quantity: quantity },
            success: function (response) {
                alert("Order placed successfully!");
                $("#prescriptionModal").fadeOut();
                location.reload();
            }
        });
    });
});
