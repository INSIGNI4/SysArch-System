document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('updateOrderModal');

    const statusSelect = document.getElementById('update-status');
    const idInput = document.getElementById('update-order-id');

    const orderedQtys = document.getElementById('update-orderedQuantity');
    const totalReceivedInput = document.getElementById('update-received');
    const withIssueInput = document.getElementById('update-issue');

    const proofInput = document.getElementById('edit-image');

    const expirationDateInput =
        document.getElementById('update-expiration-date');

    const nulltoggleBtn =
        document.getElementById('toggleNullBtnOrder_restock');

    const notnulltoggleBtn =
        document.getElementById('toggleNotNullBtnOrder_restock');

    const updateOrderBtn =
        document.getElementById('update-restock-status-btn');

    const deleteproductbtn =
        document.getElementById('delete-restock-btn');

    const updateCancelBtn =
        document.getElementById('updateorder-cancel-btn');


    /* =========================================================
       HIDE ACTION BUTTONS
    ========================================================= */

    function resetActions() {

        document.querySelectorAll(".update-order-btn").forEach(function (btn) {
            btn.style.display = "none";
        });

        document.querySelectorAll(".edit-order-btn").forEach(function (btn) {
            btn.style.display = "none";
        });

        document.querySelectorAll(".delete-product-form").forEach(function (form) {
            form.style.display = "none";
        });
    }


    /* =========================================================
       BIND UPDATE BUTTONS
    ========================================================= */

    function bindUpdateButtons() {

        document.querySelectorAll(".update-order-btn").forEach(function (button) {

            const clone = button.cloneNode(true);

            button.replaceWith(clone);

            clone.addEventListener('click', function () {

                modal.style.display = 'block';


                /* =========================
                   BASIC VALUES
                ========================= */

                idInput.value =
                    this.dataset.id || '';

                orderedQtys.value =
                    this.dataset.quantity || '';

                totalReceivedInput.value =
                    this.dataset.received || 0;

                withIssueInput.value =
                    this.dataset.issue || 0;

                statusSelect.value =
                    this.dataset.status || '';


                /* =========================
                   EXPIRATION DATE
                ========================= */

                let expirationDate =
                    this.dataset.expirationdate || '';

                /*
                 * <input type="date"> needs:
                 * YYYY-MM-DD
                 *
                 * If MySQL somehow gives us a datetime,
                 * take only the first 10 characters.
                 */

                if (expirationDate.length > 10) {
                    expirationDate = expirationDate.substring(0, 10);
                }

                expirationDateInput.value = expirationDate;


                /* =========================
                   RESET EXPIRATION TOGGLE
                ========================= */

                expirationDateInput.disabled = false;

                nulltoggleBtn.style.display = 'inline-block';
                nulltoggleBtn.style.color = 'red';

                notnulltoggleBtn.style.display = 'none';


                /*
                 * Do NOT set:
                 *
                 * proofInput.value = this.dataset.proof;
                 *
                 * because this is a file input.
                 */
            });
        });
    }


    /* =========================================================
       TOOLBAR UPDATE BUTTON
    ========================================================= */

    if (updateOrderBtn) {

        updateOrderBtn.addEventListener("click", function () {

            resetActions();

            document.querySelectorAll(".update-order-btn").forEach(function (btn) {
                btn.style.display = "inline-block";
            });

            bindUpdateButtons();
        });
    }


    /* =========================================================
       CANCEL MODAL
    ========================================================= */

    if (updateCancelBtn) {

        updateCancelBtn.addEventListener('click', function () {

            modal.style.display = 'none';

        });
    }


    /* =========================================================
       CLICK OUTSIDE MODAL
    ========================================================= */

    window.addEventListener('click', function (event) {

        if (event.target === modal) {

            modal.style.display = 'none';

        }

    });


    /* =========================================================
       EXPIRATION = NO
    ========================================================= */

    if (nulltoggleBtn) {

        nulltoggleBtn.addEventListener('click', function () {

            nulltoggleBtn.style.display = 'none';

            notnulltoggleBtn.style.display = 'inline-block';

            notnulltoggleBtn.style.color = 'blue';

            expirationDateInput.disabled = true;

            expirationDateInput.value = '';

        });

    }


    /* =========================================================
       EXPIRATION = YES
    ========================================================= */

    if (notnulltoggleBtn) {

        notnulltoggleBtn.addEventListener('click', function () {

            notnulltoggleBtn.style.display = 'none';

            nulltoggleBtn.style.display = 'inline-block';

            nulltoggleBtn.style.color = 'red';

            expirationDateInput.disabled = false;

        });

    }


    /* =========================================================
       DELETE MODE
    ========================================================= */

    if (deleteproductbtn) {

        deleteproductbtn.addEventListener("click", function () {

            resetActions();

            document.querySelectorAll(".delete-product-form").forEach(function (form) {
                form.style.display = "inline-block";
            });

        });

    }

});