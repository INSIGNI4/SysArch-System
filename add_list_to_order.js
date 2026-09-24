document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("listToOrderForm");
    const modal = document.getElementById("addListToOrderModal");

    if (!form) return;

    form.addEventListener("submit", async function (event) {
        event.preventDefault();

        const formData = new FormData(form);

        try {
            const response = await fetch("add_list_to_order.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            console.log("ADD LIST RESPONSE:", result);

            if (result.success) {
                alert(result.message);
                modal.style.display = "none";
                form.reset();

                document.getElementById("list-forecast-start-date").disabled = true;
                document.getElementById("list-forecast-end-date").disabled = true;

                document.getElementById("toggleNullBtnListOrder").style.display = "inline-block";
                document.getElementById("toggleNotNullBtnListOrder").style.display = "none";

                location.reload();
            } else {
                alert("Failed to add List to Order:\n\n" + result.message);
            }
        } catch (error) {
            console.error("Error adding List to Order:", error);
            alert("An error occurred while saving the List to Order.");
        }
    });
});