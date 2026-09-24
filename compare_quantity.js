// document.addEventListener("DOMContentLoaded", function () {

//     const orderedInput = document.getElementById("update-orderedQuantity");
//     const receivedInput = document.getElementById("update-received");
//     const issueInput = document.getElementById("update-issue");

//     if (orderedInput && receivedInput && issueInput) {

//         function validateQuantities() {

//             const ordered = parseInt(orderedInput.value) || 0;
//             const received = parseInt(receivedInput.value) || 0;
//             const issue = parseInt(issueInput.value) || 0;

//             const total = received + issue;

//             // Cannot receive/issue more than what was ordered
//             if (total > ordered) {

//                 alert("Invalid Quantity: Without Issue + With Issue cannot exceed Ordered Quantity.");

//                 receivedInput.value = "";
//                 issueInput.value = 0;

//                 receivedInput.focus();
//             }
//         }

//         receivedInput.addEventListener("change", validateQuantities);
//         issueInput.addEventListener("change", validateQuantities);

//         // Final validation before saving
//         const form = receivedInput.closest("form");

//         if (form) {
//             form.addEventListener("submit", function (e) {

//                 const ordered = parseInt(orderedInput.value) || 0;
//                 const received = parseInt(receivedInput.value) || 0;
//                 const issue = parseInt(issueInput.value) || 0;

//                 const total = received + issue;

//                 if (total > ordered) {
//                     e.preventDefault();

//                     alert("Please fix the quantities. The total cannot exceed the Ordered Quantity.");

//                     receivedInput.focus();
//                 }
//             });
//         }
//     }
// });

document.addEventListener("DOMContentLoaded", function () {

    const orderedInput = document.getElementById("update-orderedQuantity");
    const receivedInput = document.getElementById("update-received");
    const issueInput = document.getElementById("update-issue");

    if (orderedInput && receivedInput && issueInput) {

        function validateQuantities() {

            const ordered = parseInt(orderedInput.value) || 0;
            const received = parseInt(receivedInput.value) || 0;
            const issue = parseInt(issueInput.value) || 0;

            const total = received + issue;

            // Partial delivery is allowed.
            // But delivered quantity + issue cannot exceed the quantity for this restock row.
            if (total > ordered) {

                alert(
                    "Invalid Quantity: Without Issue + With Issue cannot exceed Ordered Quantity."
                );

                receivedInput.value = "";
                issueInput.value = 0;

                receivedInput.focus();
            }
        }

        receivedInput.addEventListener("change", validateQuantities);
        issueInput.addEventListener("change", validateQuantities);

        const form = receivedInput.closest("form");

        if (form) {
            form.addEventListener("submit", function (e) {

                const ordered = parseInt(orderedInput.value) || 0;
                const received = parseInt(receivedInput.value) || 0;
                const issue = parseInt(issueInput.value) || 0;

                const total = received + issue;

                if (total > ordered) {

                    e.preventDefault();

                    alert(
                        "Please fix the quantities. " +
                        "Without Issue + With Issue cannot exceed Ordered Quantity."
                    );

                    receivedInput.focus();
                }
            });
        }
    }
});