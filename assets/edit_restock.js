// document.addEventListener('DOMContentLoaded', function () {
//     const modal = document.getElementById('editOrderModal');
//     const editProductID = document.getElementById('edit-productid');
//     const editSupplierID = document.getElementById('edit-supplierid');
//     const editQuantity = document.getElementById('edit-quantity');
//     const idInput = document.getElementById('edit-order-id');

//     // const statusSelect = document.getElementById('update-status');
//     // const deliverystatusSelect = document.getElementById('update-delivery-status');

//     // const orderedQtys = document.getElementById('update-orderedQuantity');

//     // const totalReceivedInput = document.getElementById('update-received');
//     // const withIssueInput = document.getElementById('update-issue');
//     // const proofInput = document.getElementById('edit-image');
//     // const dateReceivedInput = document.getElementById('update-datereceived');

//     function resetActions() {
//         document.querySelectorAll(".edit-order-btn").forEach(btn => btn.style.display = "none");
//     }

//     // ⏱️ Dynamically bind update button clicks
//     function bindUpdateButtons() {
//         document.querySelectorAll(".edit-order-btn").forEach(button => {
//             // Remove previous listeners (clone)
//             const clone = button.cloneNode(true);
//             button.replaceWith(clone);

//             clone.addEventListener('click', function () {
//                 modal.style.display = 'block';
//                 idInput.value = this.dataset.id || '';
//                 editQuantity.value = this.dataset.quantity || '';
//                 editProductID.value = this.dataset.productid || '';
//                 editSupplierID.value = this.dataset.supplierid || '';


//                 // statusSelect.value = this.dataset.status || '';
//                 // deliverystatusSelect.value = this.dataset.deliverystatus || '';
//                 // dateReceivedInput.value = this.dataset.datereceived || '';
//                 // proofInput.value = this.dataset.proof || '';
//             });
//         });
//     }

//     // ✅ Toolbar "UPDATE" button click
//     const updateOrderBtn = document.getElementById("edit-restock-btn");
//     if (updateOrderBtn) {
//         updateOrderBtn.addEventListener("click", () => {
//             resetActions();
//             document.querySelectorAll(".edit-order-btn").forEach(btn => {
//                 btn.style.display = "inline-block";
//             });
//             bindUpdateButtons();
//         });
//     }

//     // ✅ Cancel modal
//     const cancelBtn = document.getElementById('editorder-cancel-btn');
//     if (cancelBtn) {
//         cancelBtn.addEventListener('click', function () {
//             modal.style.display = 'none';
//         });
//     }

//     // ✅ Click outside to close
//     window.addEventListener('click', function (event) {
//         if (event.target === modal) {
//             modal.style.display = 'none';
//         }
//     });


// });





document.addEventListener('DOMContentLoaded', function () {
    // const modal = document.getElementById('editOrderModal');
    // const editProductID = document.getElementById('edit-productid');
    // const editSupplierID = document.getElementById('edit-supplierid');
    // const editQuantity = document.getElementById('edit-quantity');
    // const idInput = document.getElementById('edit-order-id');

    const editButtons = document.querySelectorAll('.edit-order-btn');
    const modal = document.getElementById('editRestockModal');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Show modal
            modal.style.display = 'block';

            document.getElementById('edit-order-id').value = this.dataset.id;
            document.getElementById('edit-quantity').value = this.dataset.quantity;
            document.getElementById('edit-supplieridRESTOCK').value = this.dataset.supplierid;
            document.getElementById('edit-productidRESTOCK').value = this.dataset.productid;
            // document.getElementById('edit-productid').value = this.dataset.supplierid;

            
            
        });
    });

    // Cancel button
    document.getElementById('editorder-cancel-btn').addEventListener('click', function () {
        modal.style.display = 'none';
    });

    // Optional: Close modal when clicking outside of it
    window.addEventListener('click', function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

const editproductbtn = document.getElementById("edit-restock-btn");
const deleteproductbtn = document.getElementById("delete-restock-btn");
const displaydeletebtn = document.getElementById("display-delete");


function resetActions() {
	document.querySelectorAll(".edit-order-btn").forEach(btn => btn.style.display = "none");
	document.querySelectorAll(".update-order-btn").forEach(form => form.style.display = "none");
	document.querySelectorAll(".delete-product-form").forEach(form => form.style.display = "none");
    
}

editproductbtn.addEventListener("click", () => {
	resetActions();
	document.querySelectorAll(".edit-order-btn").forEach(btn => btn.style.display = "inline-block");
});
deleteproductbtn.addEventListener("click", () => {
	resetActions();
	document.querySelectorAll(".delete-product-form").forEach(btn => btn.style.display = "inline-block");
    

});



});


    