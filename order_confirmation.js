document.addEventListener("DOMContentLoaded", () => {

    const restockForm =
        document.getElementById("restockForm");

    const confirmationModal =
        document.getElementById("orderConfirmationModal");

    const confirmationSummary =
        document.getElementById("order-confirmation-summary");

    const closeConfirmation =
        document.getElementById("closeOrderConfirmation");

    const backToOrderBtn =
        document.getElementById("backToOrderBtn");

    const confirmFinalOrderBtn =
        document.getElementById("confirmFinalOrderBtn");


    /*
     * Open confirmation modal
     */
    restockForm.addEventListener("submit", function (event) {

        /*
         * Stop the form from submitting immediately.
         */
        event.preventDefault();

        /*
         * Build the confirmation summary.
         */
        buildOrderConfirmation();

        /*
         * Show confirmation modal.
         */
        confirmationModal.style.display = "flex";

    });


    /*
     * Close confirmation modal
     */
    closeConfirmation.addEventListener("click", function () {

        confirmationModal.style.display = "none";

    });


    /*
     * Back button
     *
     * This closes the confirmation screen
     * and returns to the order form.
     */
    backToOrderBtn.addEventListener("click", function () {

        confirmationModal.style.display = "none";

    });


    /*
     * Close when clicking outside the confirmation box
     */
    confirmationModal.addEventListener("click", function (event) {

        if (event.target === confirmationModal) {

            confirmationModal.style.display = "none";

        }

    });


    /*
     * Build the order summary
     */
    function buildOrderConfirmation() {

        confirmationSummary.innerHTML = "";

        const rows =
            Array.from(
                document.querySelectorAll(
                    "#restock-items-container .restock-item-row"
                )
            );

        const supplierGroups = {};

        rows.forEach(function (row) {

            const productID =
                row.querySelector(".restock-product-id").value;

            const productName =
                row.querySelector(".restock-product-name").value;

            const quantity =
                row.querySelector(".restock-quantity").value;

            const supplierSelect =
                row.querySelector(".restock-supplier");

            const supplierID =
                supplierSelect.value;

            if (!productID || !supplierID) {
                return;
            }

            // const supplierName =
            //     supplierSelect.options[
            //         supplierSelect.selectedIndex
            //     ].text;




            const selectedOption =
                supplierSelect.options[
                    supplierSelect.selectedIndex
                ];


            const supplierCompany =
                selectedOption.dataset.company || "Not provided";

            const supplierName =
                selectedOption.dataset.name || "Not provided";                

            const supplierPhone =
                selectedOption.dataset.phone || "Not provided";

            const supplierEmail =
                selectedOption.dataset.email || "Not provided";

            const supplierLocation =
                selectedOption.dataset.location || "Not provided";



            const packSize =
                row.querySelector(".restock-pack-size").value;

            // if (!supplierGroups[supplierID]) {

            //     supplierGroups[supplierID] = {

            //         name: supplierName,

            //         items: []

            //     };

            // }




            const expectedDateInput =
                row.querySelector(".restock-expected-date");

            const expectedDate =
                expectedDateInput
                    ? expectedDateInput.value
                    : "";

            if (!supplierGroups[supplierID]) {
                supplierGroups[supplierID] = {
                    company: supplierCompany,
                    name: supplierName,
                    location: supplierLocation,
                    phone: supplierPhone,
                    email: supplierEmail,
                    expectedDate: expectedDate,
                    items: []
                };
            }




            supplierGroups[supplierID].items.push({

                productName: productName,

                quantity: quantity,

                packSize: packSize

            });

        });


        const supplierIDs =
            Object.keys(supplierGroups);


        if (supplierIDs.length === 0) {

            confirmationSummary.innerHTML = `
                <p>
                    No valid order items were found.
                </p>
            `;

            return;

        }


        /*
         * Create each supplier section.
         */
        supplierIDs.forEach(function (supplierID) {

            const group =
                supplierGroups[supplierID];


            const supplierDiv =
                document.createElement("div");

            supplierDiv.className =
                "confirmation-supplier-group";


            // supplierDiv.innerHTML = `

            //     <div class="confirmation-supplier-name">

            //         ${group.name}

            //     </div>

            //     <div class="confirmation-supplier-contact">

            //         <span>
            //             📞 ${group.phone}
            //         </span>

            //         <span>
            //             ✉️ ${group.email}
            //         </span>

            //     </div>

            // `;









            

            supplierDiv.innerHTML = `
                <div class="confirmation-supplier-info" style="background-color:  rgba(214, 208, 208, 0.92);">



                    <div class="supplier-info-row">
                        <div class="supplier-info-item">
                            <strong>Company Name: </strong>
                            <span style="font-weight: bolder;">
                                ${group.company}
                            </span>
                        </div>

                        <div class="supplier-info-item">
                            <strong>Contact Person: </strong>
                            <span style="font-weight: bolder;">
                                ${group.name}
                            </span>
                        </div>
                    </div>

                    
                    <div class="supplier-info-row supplier-location-row">
                        <div class="supplier-info-item">
                            <strong>Location: </strong>
                            <span style="font-weight: bolder;">
                                ${group.location}
                            </span>
                        </div>
                    </div>

                    <div class="supplier-info-row">
                        <div class="supplier-info-item">
                            <strong>Phone Number: </strong>
                            <span style="font-weight: bolder; color:rgba(1, 46, 130, 0.92);">${group.phone || "Not provided"}</span>
                        </div>

                        <div class="supplier-info-item">
                            <strong>Email: </strong>
                            <span style="font-weight: bolder; color:rgba(3, 95, 34, 0.92);">${group.email || "Not provided"}</span>
                        </div>
                    </div>















                </div>

                <div class="confirmation-supplier-expected-date" style="margin-top: -20px">
                    <label style="font-weight: bolder; font-size: 15px;">
                        Expected Receive Date:
                    </label>

                    <strong 
                    style="font-weight: bolder;"
                    >
                        ${group.expectedDate || "Not selected"}
                    </strong>
                </div>
            `;

            /*
             * Add products under supplier.
             */
            group.items.forEach(function (item) {

                const itemDiv =
                    document.createElement("div");

                itemDiv.className =
                    "confirmation-order-item";


                itemDiv.innerHTML = `

                    <span class="confirmation-product-name">

                        ${item.productName}

                    </span>

                    <span class="confirmation-product-details">

                        Quantity:
                        <strong>${item.quantity}</strong>

                        &nbsp; | &nbsp;

                        Pack Size:
                        ${item.packSize}

                    </span>

                `;


                supplierDiv.appendChild(itemDiv);

            });


            confirmationSummary.appendChild(
                supplierDiv
            );

        });


        /*
         * Overall summary
         */
        const totalProducts =
            rows.filter(function (row) {

                return row.querySelector(
                    ".restock-product-id"
                ).value;

            }).length;


        const totalSuppliers =
            supplierIDs.length;


        const totalDiv =
            document.createElement("div");

        totalDiv.className =
            "confirmation-total";


        totalDiv.innerHTML = `

            ${totalProducts} product(s)
            from
            ${totalSuppliers} supplier(s)

        `;


        confirmationSummary.appendChild(
            totalDiv
        );

    }


    /*
     * Final confirmation
     *
     * This is where the form is finally submitted.
     */
    // confirmFinalOrderBtn.addEventListener(
    //     "click",
    //     function () {

    //         /*
    //          * Remove the submit listener temporarily
    //          * so the confirmation does not open again.
    //          */
    //         restockForm.removeEventListener(
    //             "submit",
    //             arguments.callee
    //         );

    //         /*
    //          * Submit the form normally.
    //          */
    //         restockForm.submit();

    //     }
    // );



    let finalSubmission = false;

    restockForm.addEventListener("submit", function (event) {

        if (finalSubmission) {
            return;
        }

        event.preventDefault();

        buildOrderConfirmation();

        confirmationModal.style.display = "flex";

    });


    confirmFinalOrderBtn.addEventListener(
        "click",
        function () {

            finalSubmission = true;

            restockForm.submit();

        }
    );    

});