                        // document.addEventListener("DOMContentLoaded", () => {
                        //     const modal = document.getElementById("editModal");
                        //     const openBtn = document.getElementById("select-product-btn");
                        //     const editproductcancelbtn = document.getElementById("editprod-cancel-btn");

                        //     document.querySelector(".products-table-container").addEventListener("click", (e) => {
                        //         if (e.target.classList.contains("select-product-btn")) {
                        //             const btn = e.target;
                        //             document.getElementById('edit-id').value = btn.dataset.id;
                        //             document.getElementById('edit-name').value = btn.dataset.name;
                        //             document.getElementById('edit-type').value = btn.dataset.type;
                        //             document.getElementById('edit-reorder').value = btn.dataset.reorder;
                        //             document.getElementById('edit-unitsordered').value = btn.dataset.unitsordered;
                        //             document.getElementById('edit-sold').value = btn.dataset.unitsold;
                        //             document.getElementById('edit-storeprice').value = btn.dataset.storeprice;
                        //             document.getElementById('edit-supplierprice').value = btn.dataset.supplierprice;
                        //             document.getElementById('edit-supplierid').value = btn.dataset.supplierid;
                        //             document.getElementById('edit-expirationdate').value = btn.dataset.expirationdate;
                        //             document.getElementById('edit-barcode').value = btn.dataset.barcode;

                        //             modal.style.display = "flex"; // show modal
                        //         }
                        //     });

                        //     openBtn.addEventListener("click", () => {
                        //         modal.style.display = "block";
                        //     });
                        //     // Close modal when cancel button is clicked
                        //     editproductcancelbtn.addEventListener("click", () => {
                        //         modal.style.display = "none";
                        //     }); 

                        //     // Close modal when clicking outside modal content
                        //     window.addEventListener("click", (event) => {
                        //         if (event.target === modal) {
                        //             modal.style.display = "none";
                        //         }
                        //     });
                        // });

                        //     const editproductbtn = document.getElementById("edit-product-btn");
                        //     const deleteproductbtn = document.getElementById("delete-product-btn");

                        //     function resetActions() {
                        //         document.querySelectorAll(".select-product-btn").forEach(btn => btn.style.display = "none");
                        //         document.querySelectorAll(".delete-product-form").forEach(form => form.style.display = "none");
                        //     }

                        //     editproductbtn.addEventListener("click", () => {
                        //         resetActions();
                        //         document.querySelectorAll(".select-product-btn").forEach(btn => btn.style.display = "inline-block");
                        //     });
                        //     deleteproductbtn.addEventListener("click", () => {
                        //         resetActions();
                        //         document.querySelectorAll(".delete-product-form").forEach(btn => btn.style.display = "inline-block");
                        //     });
                        
                        
// document.addEventListener("DOMContentLoaded", () => {
// 	const modal = document.getElementById("editProductModal");
// 	// const openBtn = document.getElementById("select-product-btn");

// 	document.querySelector(".products-table-container").addEventListener("click", (e) => {
// 		if (e.target.classList.contains("select-product-btn")) {
// 			const btn = e.target;
// 			document.getElementById('edit-id').value = btn.dataset.id;
// 			document.getElementById('edit-name').value = btn.dataset.name;
// 			document.getElementById('edit-type').value = btn.dataset.type;
// 			document.getElementById('edit-reorder').value = btn.dataset.reorder;
// 			document.getElementById('edit-unitsordered').value = btn.dataset.unitsordered;
// 			document.getElementById('edit-sold').value = btn.dataset.unitsold;
// 			document.getElementById('edit-storeprice').value = btn.dataset.storeprice;
// 			document.getElementById('edit-supplierprice').value = btn.dataset.supplierprice;
// 			document.getElementById('edit-supplierid').value = btn.dataset.supplierid;
// 			document.getElementById('edit-expirationdate').value = btn.dataset.expirationdate;
// 			document.getElementById('edit-barcode').value = btn.dataset.barcode;

// 			modal.style.display = "block"; // show modal
// 		}
// 	});

// 	// openBtn.addEventListener("click", () => {
// 	// 	modal.style.display = "block";
// 	// });
// 	// Close modal when cancel button is clicked
//     const editprodcancelbtn = document.getElementById("editprod-cancel-btn");

// 	editprodcancelbtn.addEventListener("click", () => {
// 		modal.style.display = "none";
// 	});

// 	// Close modal when clicking outside modal content
// 	window.addEventListener("click", (event) => {
// 		if (event.target === modal) {
// 			modal.style.display = "none";
// 		}
// 	});
// });

// const editproductbtn = document.getElementById("edit-product-btn");
// const deleteproductbtn = document.getElementById("delete-product-btn");

// function resetActions() {
// 	document.querySelectorAll(".select-product-btn").forEach(btn => btn.style.display = "none");
// 	document.querySelectorAll(".delete-product-form").forEach(form => form.style.display = "none");
// }

// editproductbtn.addEventListener("click", () => {
// 	resetActions();
// 	document.querySelectorAll(".select-product-btn").forEach(btn => btn.style.display = "inline-block");
// });
// deleteproductbtn.addEventListener("click", () => {
// 	resetActions();
// 	document.querySelectorAll(".delete-product-form").forEach(btn => btn.style.display = "inline-block");
// });






// document.addEventListener('DOMContentLoaded', function () {
//     const editButtons = document.querySelectorAll('.select-product-btn');
//     const modal = document.getElementById('editProductModal');


//     editButtons.forEach(button => {
//         button.addEventListener('click', function () {
//             // Show modal
//             modal.style.display = 'block';

//             // // Fill form fields with data attributes
//             // document.getElementById('edit-id').value = this.dataset.id;
//             // document.getElementById('edit-name').value = this.dataset.name;
//             // document.getElementById('edit-type').value = this.dataset.type;
//             // document.getElementById('edit-reorder').value = this.dataset.reorder;
//             // document.getElementById('edit-unitsordered').value = this.dataset.unitsordered;
//             // document.getElementById('edit-storeprice').value = this.dataset.storeprice;
//             // document.getElementById('edit-supplierprice').value = this.dataset.supplierprice;
//             // document.getElementById('edit-supplierid').value = this.dataset.supplierid;
//             // document.getElementById('edit-expirationdate').value = this.dataset.expirationdate;
//             // document.getElementById('edit-barcode').value = this.dataset.barcode;

//             // Fill form fields with data attributes
//             document.getElementById('edit-id').value = this.dataset.id;
//             document.getElementById('edit-name').value = this.dataset.name;
//             document.getElementById('edit-type').value = this.dataset.type;
//             // document.getElementById('edit-reorder').value = this.dataset.reorder;
//             // document.getElementById('edit-unitsordered').value = this.dataset.unitsordered;
//             document.getElementById('edit-storeprice').value = this.dataset.storeprice;
//             document.getElementById('edit-supplierprice').value = this.dataset.supplierprice;
            
//             document.getElementById('edit-supplierid').value = this.dataset.supplierid;
//             // document.getElementById('edit-expirationdate').value = this.dataset.expirationdate;
//             document.getElementById('edit-packsize').value = this.dataset.packsize;
//             document.getElementById('edit-barcode').value = this.dataset.barcode;
            
            
//         });
//     });

//     // Cancel button
//     document.getElementById('editprod-cancel-btn').addEventListener('click', function () {
//         modal.style.display = 'none';
//     });

//     // Optional: Close modal when clicking outside of it
//     window.addEventListener('click', function (event) {
//         if (event.target == modal) {
//             modal.style.display = 'none';
//         }
//     });


    
// const nulltoggleBtn = document.getElementById('toggleNullBtn1');
// const expInput = document.getElementById('edit-expirationdate');
// // const expInputClass = document.getElementById('edit-expirationdate');

// const notnulltoggleBtn = document.getElementById('toggleNotNullBtn1');

// nulltoggleBtn.addEventListener('click',function(){

//     nulltoggleBtn.style.display = 'none';
//     notnulltoggleBtn.style.display = 'inline-block';
//     notnulltoggleBtn.style.color = 'blue'; 
//     expInput.disabled = true;
//     expInput.value = "";
    
//     // expInputClass.disabled = true;

// })

// notnulltoggleBtn.addEventListener('click',function(){
//     // expInput.type = 'date';
//     notnulltoggleBtn.style.display = 'none';
//     nulltoggleBtn.style.display = 'inline-block';
//     nulltoggleBtn.style.color = 'red'; 
//     expInput.disabled = false;
//     // expInputClass.disabled = false;

// })




// });



// const editproductbtn = document.getElementById("edit-product-btn");
// const deleteproductbtn = document.getElementById("delete-product-btn");
// const displaydeletebtn = document.getElementById("display-delete");


// function resetActions() {
// 	document.querySelectorAll(".select-product-btn").forEach(btn => btn.style.display = "none");
// 	document.querySelectorAll(".delete-product-form").forEach(form => form.style.display = "none");
// }

// editproductbtn.addEventListener("click", () => {
// 	resetActions();
// 	document.querySelectorAll(".select-product-btn").forEach(btn => btn.style.display = "inline-block");
// });
// deleteproductbtn.addEventListener("click", () => {
// 	resetActions();
// 	document.querySelectorAll(".delete-product-form").forEach(btn => btn.style.display = "inline-block");
    

// });








document.addEventListener('DOMContentLoaded', function () {

    console.log("EDIT_PRODUCT_NEW_VERSION_LOADED");

    const editButtons =
        document.querySelectorAll('.select-product-btn');

    const modal =
        document.getElementById('editProductModal');


    editButtons.forEach(button => {

        button.addEventListener('click', function () {

            // Show modal
            if (modal) {
                modal.style.display = 'block';
            }

            // Fill form fields with data attributes
            const editId =
                document.getElementById('edit-id');

            const editName =
                document.getElementById('edit-name');

            const editType =
                document.getElementById('edit-type');

            const editStorePrice =
                document.getElementById('edit-storeprice');

            const editSupplierPrice =
                document.getElementById('edit-supplierprice');

            const editSupplierId =
                document.getElementById('edit-supplierid');

            const editPackSize =
                document.getElementById('edit-packsize');

            const editBarcode =
                document.getElementById('edit-barcode');


            if (editId) {
                editId.value = this.dataset.id;
            }

            if (editName) {
                editName.value = this.dataset.name;
            }

            if (editType) {
                editType.value = this.dataset.type;
            }

            if (editStorePrice) {
                editStorePrice.value = this.dataset.storeprice;
            }

            if (editSupplierPrice) {
                editSupplierPrice.value =
                    this.dataset.supplierprice;
            }

            if (editSupplierId) {
                editSupplierId.value =
                    this.dataset.supplierid;
            }

            if (editPackSize) {
                editPackSize.value =
                    this.dataset.packsize;
            }

            if (editBarcode) {
                editBarcode.value =
                    this.dataset.barcode;
            }

        });

    });


    // Cancel button
    const cancelButton =
        document.getElementById('editprod-cancel-btn');

    if (cancelButton && modal) {

        cancelButton.addEventListener('click', function () {

            modal.style.display = 'none';

        });

    }


    // Optional: Close modal when clicking outside of it
    if (modal) {

        window.addEventListener('click', function (event) {

            if (event.target === modal) {

                modal.style.display = 'none';

            }

        });

    }


    // Expiration toggle
    const nulltoggleBtn =
        document.getElementById('toggleNullBtn1');

    const expInput =
        document.getElementById('edit-expirationdate');

    const notnulltoggleBtn =
        document.getElementById('toggleNotNullBtn1');


    if (
        nulltoggleBtn &&
        notnulltoggleBtn &&
        expInput
    ) {

        nulltoggleBtn.addEventListener(
            'click',
            function () {

                nulltoggleBtn.style.display = 'none';

                notnulltoggleBtn.style.display =
                    'inline-block';

                notnulltoggleBtn.style.color =
                    'blue';

                expInput.disabled = true;

                expInput.value = "";

            }
        );


        notnulltoggleBtn.addEventListener(
            'click',
            function () {

                notnulltoggleBtn.style.display =
                    'none';

                nulltoggleBtn.style.display =
                    'inline-block';

                nulltoggleBtn.style.color =
                    'red';

                expInput.disabled = false;

            }
        );

    }

});


const editproductbtn =
    document.getElementById("edit-product-btn");

const deleteproductbtn =
    document.getElementById("delete-product-btn");

const displaydeletebtn =
    document.getElementById("display-delete");


function resetActions() {

    document
        .querySelectorAll(".select-product-btn")
        .forEach(btn => {
            btn.style.display = "none";
        });

    document
        .querySelectorAll(".delete-product-form")
        .forEach(form => {
            form.style.display = "none";
        });

}


if (editproductbtn) {

    editproductbtn.addEventListener("click", () => {

        resetActions();

        document
            .querySelectorAll(".select-product-btn")
            .forEach(btn => {
                btn.style.display = "inline-block";
            });

    });

}


if (deleteproductbtn) {

    deleteproductbtn.addEventListener("click", () => {

        resetActions();

        document
            .querySelectorAll(".delete-product-form")
            .forEach(form => {
                form.style.display = "inline-block";
            });

    });

}



