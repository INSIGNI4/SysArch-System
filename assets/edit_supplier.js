

document.addEventListener("DOMContentLoaded", function () {
    const toolbarEditButton =
        document.getElementById("edit-supplier-btn");

    const modal =
        document.getElementById("editSupplierModal");

    const cancelButton =
        document.getElementById("cancel-edit-supplier-btn");

    const actionHeader =
        document.querySelector(".supplier-action-header");

    const actionCells =
        document.querySelectorAll(".supplier-action-cell");

    if (!toolbarEditButton || !modal || !cancelButton) {
        return;
    }

    const fields = {
        id: document.getElementById("edit-supplier-id"),
        company: document.getElementById("edit-supplier-company"),
        contact: document.getElementById("edit-supplier-contact"),
        address: document.getElementById("edit-supplier-address"),
        city: document.getElementById("edit-supplier-city"),
        email: document.getElementById("edit-supplier-email"),
        phone: document.getElementById("edit-supplier-phone"),
        category: document.getElementById("edit-supplier-category")
    };

    // Check that every required modal field exists.
    if (Object.values(fields).some(field => !field)) {
        console.error("One or more supplier edit fields are missing.");
        return;
    }

    // Reveal the Edit buttons at the end of the supplier rows.
    toolbarEditButton.addEventListener("click", function () {
        if (actionHeader) {
            actionHeader.style.display = "table-cell";
        }

        actionCells.forEach(function (cell) {
            cell.style.display = "table-cell";
        });
    });

    // Open the modal with the selected supplier's information.
    document.querySelectorAll(".supplier-row-edit-btn")
        .forEach(function (button) {
            button.addEventListener("click", function () {
                fields.id.value = button.dataset.id || "";
                fields.company.value = button.dataset.company || "";
                fields.contact.value = button.dataset.contact || "";
                fields.address.value = button.dataset.address || "";
                fields.city.value = button.dataset.city || "";
                fields.email.value = button.dataset.email || "";
                fields.phone.value = button.dataset.phone || "";

                const category = button.dataset.category || "NONE";

                // Preserve the saved category, including a custom value.
                if (
                    !Array.from(fields.category.options)
                        .some(option => option.value === category)
                ) {
                    fields.category.add(
                        new Option(category, category)
                    );
                }

                fields.category.value = category;
                modal.style.display = "flex";
            });
        });

    // Close without saving.
    cancelButton.addEventListener("click", function () {
        modal.style.display = "none";
    });
});







































// document.addEventListener("DOMContentLoaded", function () {
//     const toolbarEditButton = document.getElementById("edit-supplier-btn");
//     const modal = document.getElementById("editSupplierModal");
//     const cancelButton = document.getElementById("cancel-edit-supplier-btn");

//     const actionHeader = document.querySelector(".supplier-action-header");
//     const actionCells = document.querySelectorAll(".supplier-action-cell");

//     if (!toolbarEditButton || !modal || !cancelButton) {
//         return;
//     }

//     const fields = {
//         id: document.getElementById("edit-supplier-id"),
//         fname: document.getElementById("edit-supplier-fname"),
//         lname: document.getElementById("edit-supplier-lname"),
//         location: document.getElementById("edit-supplier-location"),
//         email: document.getElementById("edit-supplier-email"),
//         phone: document.getElementById("edit-supplier-phone"),
//         offered: document.getElementById("edit-supplier-offered")
//     };

//     // Toolbar Edit button: reveal Edit buttons at the end of each row.
//     toolbarEditButton.addEventListener("click", function () {
//         if (actionHeader) {
//             actionHeader.style.display = "table-cell";
//         }

//         actionCells.forEach(function (cell) {
//             cell.style.display = "table-cell";
//         });
//     });

//     // Each row's Edit button opens the modal for that supplier.
//     document.querySelectorAll(".supplier-row-edit-btn").forEach(function (button) {
//         button.addEventListener("click", function () {
//             fields.id.value = button.dataset.id || "";
//             fields.fname.value = button.dataset.fname || "";
//             fields.lname.value = button.dataset.lname || "";
//             fields.location.value = button.dataset.location || "";
//             fields.email.value = button.dataset.email || "";
//             fields.phone.value = button.dataset.phone || "";

//             const offeredValue = button.dataset.offered || "NONE";

//             // Select the supplier's current category.
//             fields.offered.value = offeredValue;

//             // Open the existing edit modal.
//             modal.style.display = "flex";
//         });
//     });

//     // Cancel closes the modal without saving.
//     cancelButton.addEventListener("click", function () {
//         modal.style.display = "none";
//     });
// });