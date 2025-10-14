document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('updateOrderModal');
    const statusSelect = document.getElementById('update-status');
    const idInput = document.getElementById('update-order-id');
    const deliverystatusSelect = document.getElementById('update-delivery-status');
    const totalReceivedInput = document.getElementById('update-received');
    const withIssueInput = document.getElementById('update-issue');
    const proofInput = document.getElementById('edit-image');
    const dateReceivedInput = document.getElementById('update-datereceived');

    function resetActions() {
        document.querySelectorAll(".update-order-btn").forEach(btn => btn.style.display = "none");
    }

    // ⏱️ Dynamically bind update button clicks
    function bindUpdateButtons() {
        document.querySelectorAll(".update-order-btn").forEach(button => {
            // Remove previous listeners (clone)
            const clone = button.cloneNode(true);
            button.replaceWith(clone);

            clone.addEventListener('click', function () {
                modal.style.display = 'block';
                idInput.value = this.dataset.id || '';
                totalReceivedInput.value = this.dataset.received || '';
                withIssueInput.value = this.dataset.issue || '';
                statusSelect.value = this.dataset.status || '';
                deliverystatusSelect.value = this.dataset.deliverystatus || '';
                dateReceivedInput.value = this.dataset.datereceived || '';
                proofInput.value = this.dataset.proof || '';
            });
        });
    }

    // ✅ Toolbar "UPDATE" button click
    const updateOrderBtn = document.getElementById("update-restock-status-btn");
    if (updateOrderBtn) {
        updateOrderBtn.addEventListener("click", () => {
            resetActions();
            document.querySelectorAll(".update-order-btn").forEach(btn => {
                btn.style.display = "inline-block";
            });
            bindUpdateButtons();
        });
    }

    // ✅ Cancel modal
    const cancelBtn = document.getElementById('updateorder-cancel-btn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }

    // ✅ Click outside to close
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

const nulltoggleBtn = document.getElementById('toggleNullBtnOrder_restock');
const notnulltoggleBtn = document.getElementById('toggleNotNullBtnOrder_restock');
const expInput = document.getElementById('update-datereceived');

nulltoggleBtn.addEventListener('click', () => {
    nulltoggleBtn.style.display = 'none';
    notnulltoggleBtn.style.display = 'inline-block';
    notnulltoggleBtn.style.color = 'blue';
    expInput.disabled = true;
});

notnulltoggleBtn.addEventListener('click', () => {
    notnulltoggleBtn.style.display = 'none';
    nulltoggleBtn.style.display = 'inline-block';
    nulltoggleBtn.style.color = 'red';
    expInput.disabled = false;
});

});
    
    // // document.addEventListener('click', function () {
    //     const notnulltoggleBtn = document.getElementById('toggleNotNullBtn1');
    //     const nulltoggleBtn = document.getElementById('toggleNullBtn1');
    //     const expInput = document.getElementById('update-datereceived');

    //     // if (e.target.id === 'toggleNullBtn1') {
    //     //     nulltoggleBtn.style.display = 'none';
    //     //     notnulltoggleBtn.style.display = 'inline-block';
    //     //     notnulltoggleBtn.style.color = 'blue';
    //     //     expInput.disabled = true;
    //     // }

    //     // if (e.target.id === 'toggleNotNullBtn1') {
    //     //     notnulltoggleBtn.style.display = 'none';
    //     //     nulltoggleBtn.style.display = 'inline-block';
    //     //     nulltoggleBtn.style.color = 'red';
    //     //     expInput.disabled = false;
    //     // }
            
    //     nulltoggleBtn.addEventListener('click', function () {
    //         nulltoggleBtn.style.display = 'none';
    //         notnulltoggleBtn.style.display = 'inline-block';
    //         notnulltoggleBtn.style.color = 'blue';
    //         expInput.disabled = true;
            
    //     })

    //     notnulltoggleBtn.addEventListener('click', function () {
    //         notnulltoggleBtn.style.display = 'none';
    //         nulltoggleBtn.style.display = 'inline-block';
    //         nulltoggleBtn.style.color = 'red';
    //         expInput.disabled = false;
    //     })
    // // });