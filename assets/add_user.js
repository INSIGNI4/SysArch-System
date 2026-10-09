document.addEventListener("DOMContentLoaded", function () {

    const addUserButton = document.getElementById("users-add-btn");
    const editUserButton = document.getElementById("edit-user-btn");

    const addUserModal = document.getElementById("addUserModal");
    const editUserModal = document.getElementById("editUserModal");

    const addCancelButton = document.getElementById("user-cancel-btn");
    const editCancelButton = document.getElementById("edit-user-cancel-btn");

    const selectUserButtons = document.querySelectorAll(".select-user-btn");

    const editUserId = document.getElementById("edit-user-id");
    const editUserUsername = document.getElementById("edit-user-username");
    const editUserEmail = document.getElementById("edit-user-email");
    const editUserPhone = document.getElementById("edit-user-phone");

    let selectedUser = null;


    // =========================
    // OPEN ADD USER MODAL
    // =========================
    if (addUserButton && addUserModal) {
        addUserButton.addEventListener("click", function () {
            addUserModal.style.display = "flex";
        });
    }


    // =========================
    // SELECT USER
    // =========================
    selectUserButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            selectedUser = {
                id: this.dataset.id,
                username: this.dataset.username,
                email: this.dataset.email,
                phone: this.dataset.phone
            };

            // Put selected user's data into the Edit form
            if (editUserId) {
                editUserId.value = selectedUser.id;
            }

            if (editUserUsername) {
                editUserUsername.value = selectedUser.username;
            }

            if (editUserEmail) {
                editUserEmail.value = selectedUser.email;
            }

            if (editUserPhone) {
                editUserPhone.value = selectedUser.phone;
            }


            

            // Highlight selected row
            selectUserButtons.forEach(function (item) {
                const row = item.closest("tr");

                if (row) {
                    row.classList.remove("selected-user-row");
                }
            });

            const selectedRow = this.closest("tr");

            if (selectedRow) {
                selectedRow.classList.add("selected-user-row");
            }

        });

    });


    // =========================
    // OPEN EDIT USER MODAL
    // =========================
    if (editUserButton && editUserModal) {
        editUserButton.addEventListener("click", function () {

            if (!selectedUser) {
                alert("Please select a user first.");
                return;
            }

            editUserModal.style.display = "flex";

        });
    }


    // =========================
    // CLOSE ADD MODAL
    // =========================
    if (addCancelButton && addUserModal) {
        addCancelButton.addEventListener("click", function () {
            addUserModal.style.display = "none";
        });
    }


    // =========================
    // CLOSE EDIT MODAL
    // =========================
    if (editCancelButton && editUserModal) {
        editCancelButton.addEventListener("click", function () {
            editUserModal.style.display = "none";
        });
    }


    // =========================
    // CLOSE WHEN CLICKING OUTSIDE
    // =========================
    window.addEventListener("click", function (event) {

        if (event.target === addUserModal) {
            addUserModal.style.display = "none";
        }

        if (event.target === editUserModal) {
            editUserModal.style.display = "none";
        }

    });

});