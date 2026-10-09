

let aiRecommendationItems = [];
let aiSuppliers = [];

async function openAIRecommendationModal() {

    const modal = document.getElementById("aiRecommendationModal");
    const container = document.getElementById("aiSupplierSections");

    if (!modal || !container) {
        console.error("AI Recommendation modal elements not found.");
        return;
    }

    modal.style.display = "flex";
    container.innerHTML = "<p>Loading AI recommendations...</p>";

    try {

        const [recommendationResponse, supplierResponse] =
            await Promise.all([
                // fetch("get_ai_recommendations.php"),
                fetch("get_ai_recommendations.php?t=" + Date.now()),
                fetch("get_supplier.php")
            ]);

        if (!recommendationResponse.ok) {
            throw new Error(
                `AI recommendation request failed: ${recommendationResponse.status}`
            );
        }

        if (!supplierResponse.ok) {
            throw new Error(
                `Supplier request failed: ${supplierResponse.status}`
            );
        }

        const result =
            await recommendationResponse.json();

        const supplierResult =
            await supplierResponse.json();

        if (!result.success) {
            throw new Error(
                result.error ||
                "Failed to load AI recommendations."
            );
        }

        aiSuppliers = supplierResult || [];
        aiRecommendationItems = result.data || [];

        if (aiRecommendationItems.length === 0) {

            container.innerHTML = `
                <p style="
                    padding: 30px;
                    text-align: center;
                    color: #666;
                ">
                    No AI recommendations available.
                </p>
            `;

            return;
        }

        renderAIRecommendations();

    } catch (error) {

        console.error(
            "AI Recommendation Error:",
            error
        );

        container.innerHTML = `
            <p style="color:red; padding:20px;">
                Failed to load AI recommendations.<br>
                ${escapeHTML(error.message)}
            </p>
        `;
    }
}

function renderAIRecommendations() {
    // const container = document.getElementById("aiRecommendationContainer");
    const container = document.getElementById("aiSupplierSections");
    if (!container) return;


    const grouped = {};

    aiRecommendationItems.forEach((item, index) => {
        const supplierId = item.supplierId ?? "none";

        const supplier = aiSuppliers.find(
            s => Number(s.Supplier_ID) === Number(item.supplierId)
        );

        if (!grouped[supplierId]) {
            grouped[supplierId] = {
                supplierId: item.supplierId,
                supplierName:
                    supplier?.CompanyName ||
                    supplier?.SupplierName ||
                    item.supplierName ||
                    "No Supplier",

                supplierAddress:
                    supplier?.Address || "",

                supplierCity:
                    supplier?.City || "",

                supplierPhone:
                    supplier?.PhoneNumber ||
                    item.supplierPhone ||
                    "",

                supplierEmail:
                    supplier?.Email ||
                    item.supplierEmail ||
                    "",

                expectedReceiveDate:
                    item.expectedReceiveDate || "",

                items: []
            };
        }

        grouped[supplierId].items.push({ item, index });

        // Preserve the selected date for this supplier group.
        if (
            !grouped[supplierId].expectedReceiveDate &&
            item.expectedReceiveDate
        ) {
            grouped[supplierId].expectedReceiveDate =
                item.expectedReceiveDate;
        }
    });

    let html = "";

    Object.values(grouped).forEach(group => {
        
                    
        html += `
            <div class="ai-supplier-group">

                <div style="
                    padding: 15px;
                    margin-bottom: 15px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    background: #fff;
                    color: #222;
                ">

                    <h3 style="
                        margin: 0 0 15px 0;
                        padding: 10px;
                        background: rgb(255, 203, 203);
                        text-transform: uppercase;
                        border-radius: 5px;
                    ">
                        ${escapeHTML(group.supplierName)}
                    </h3>

                    <div style="
                        display: grid;
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                        gap: 15px 25px;
                    ">

                        <div>
                            <strong>Address</strong>
                            <div style="margin-top: 5px; overflow-wrap: anywhere;">
                                ${escapeHTML(group.supplierAddress || "No address provided")}
                            </div>
                        </div>

                        <div>
                            <strong>City</strong>
                            <div style="margin-top: 5px; overflow-wrap: anywhere;">
                                ${escapeHTML(group.supplierCity || "No city provided")}
                            </div>
                        </div>

                        <div>
                            <strong>Phone Number</strong>
                            <div style="
                                margin-top: 5px;
                                color: rgb(13, 99, 46);
                                overflow-wrap: anywhere;
                            ">
                                ${escapeHTML(group.supplierPhone || "No phone number")}
                            </div>
                        </div>

                        <div>
                            <strong>Email</strong>
                            <div style="
                                margin-top: 5px;
                                color: blue;
                                overflow-wrap: anywhere;
                            ">
                                ${escapeHTML(group.supplierEmail || "No email")}
                            </div>
                        </div>

                        <div style="grid-column: 1 / -1;">
                            <label style="color: green;" for="ai-date-${escapeHTML(group.supplierId ?? "none")}">
                                <strong>Expected Receive Date</strong>
                            </label>

                            <div style="margin-top: 5px;">
                                <input
                                    id="ai-date-${escapeHTML(group.supplierId ?? "none")}"
                                    style="
                                        height: 35px;
                                        max-width: 250px;
                                        width: 100%;
                                        font-weight: bold;
                                    "
                                    type="date"
                                    class="ai-expected-date"
                                    data-supplier-id="${escapeHTML(group.supplierId ?? "none")}"
                                    value="${escapeHTML(group.expectedReceiveDate || "")}"
                                    min="${new Date().toISOString().split("T")[0]}"
                                    required
                                >
                            </div>
                        </div>

                    </div>
                </div>




                <table class="ai-recommendation-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Current Stock</th>
                            <th>Predicted Demand</th>
                            <th>AI Recommendation</th>
                            <th>Order Quantity</th>
                            <th>Supplier</th>
                            </tr>
                            </thead>
                            <tbody>
                            
        `;
                            // <th>Unit Cost</th>
                            // <th>Total</th>
                            
        group.items.forEach(({ item, index }) => {
            const packSize = Number(item.packSize) || 1;
            const orderedQuantity = Number(item.orderedQuantity) || packSize;
            // const unitCost = Number(item.unitCost) || 0;
            // const total = orderedQuantity * unitCost;

            let supplierOptions = "";


            aiSuppliers.forEach(supplier => {

                const selected =
                    Number(supplier.Supplier_ID) === Number(item.supplierId)
                        ? "selected"
                        : "";

                const supplierCity = supplier.City || "";

                supplierOptions += `
                    <option value="${supplier.Supplier_ID}" ${selected}>
                        ${escapeHTML(supplier.CompanyName || supplier.SupplierName)}
                        ${supplierCity ? " -( " + escapeHTML(supplierCity)+" )" : ""}
                    </option>
                `;
            });

            html += `
                <tr data-index="${index}">
                    <td>
                        <strong>${escapeHTML(item.productName)}</strong>
                        <br>
                        <small>ID: ${item.productId}</small>
                    </td>

                    <td>${formatNumber(item.currentStock)}</td>
                    <td>${formatNumber(item.predictedDemand)}</td>

                    <td>
                        ${formatNumber(item.recommendedQuantity)}
                        <br>
                        <small>Pack Size: ${packSize}</small>
                    </td>

                    <td>
                        <input
                            type="number"
                            class="ai-order-quantity"
                            data-index="${index}"
                            value="${orderedQuantity}"
                            min="${packSize}"
                            step="${packSize}"
                        >
                    </td>

                    <td>
                        <select
                            class="ai-supplier-select"
                            data-index="${index}"
                        >
                            ${supplierOptions}
                        </select>
                    </td>

                    </tr>
            `;
                    // <td>
                    //     ₱${formatCurrency(unitCost)}
                    // </td>

                    // <td class="ai-row-total">
                    //     ₱${formatCurrency(total)}
                    // </td>
        });

        html += `       
                    </tbody>
                </table>
            </div>
            <br>
            <br>
        `;
    });

    // html += `
    //     <div class="ai-grand-total-container">
    //         <strong>Grand Total:</strong>
    //         <span id="ai-grand-total">₱0.00</span>
    //     </div>
    // `;

    container.innerHTML = html;

    // Order quantity validation
    document.querySelectorAll(".ai-order-quantity").forEach(input => {
        input.addEventListener("input", function () {
            const index = Number(this.dataset.index);
            const item = aiRecommendationItems[index];
            const packSize = Number(item.packSize) || 1;
            const newQuantity = parseInt(this.value) || 0;

            if (newQuantity < packSize || newQuantity % packSize !== 0) {
                this.setCustomValidity(
                    `Quantity must be a multiple of ${packSize}.`
                );
                this.style.borderColor = "red";
                return;
            }

            this.setCustomValidity("");
            this.style.borderColor = "";

            item.orderedQuantity = newQuantity;

            // const row = this.closest("tr");
            // updateRowTotal(index, row);
            // updateAIGrandTotal();
        });
    });

    // Supplier selection
    document.querySelectorAll(".ai-supplier-select").forEach(select => {
        select.addEventListener("change", function () {
            const index = Number(this.dataset.index);
            const supplierId = Number(this.value);
            const item = aiRecommendationItems[index];

            const supplier = aiSuppliers.find(
                s => Number(s.Supplier_ID) === supplierId
            );

            if (!supplier) return;


            item.supplierId = supplierId;
            item.supplierName =
                supplier.CompanyName || supplier.SupplierName;

            item.supplierLocation = [
                supplier.Address,
                supplier.City
            ].filter(Boolean).join(", ");

            item.supplierPhone = supplier.PhoneNumber || "";
            item.supplierEmail = supplier.Email || "";

            renderAIRecommendations();
        });
    });

    document.querySelectorAll(".ai-expected-date").forEach(input => {

        input.addEventListener("change", function () {

            const supplierId = this.dataset.supplierId;
            const selectedDate = this.value;

            aiRecommendationItems.forEach(item => {

                const itemSupplierId =
                    item.supplierId ?? "none";

                if (String(itemSupplierId) === String(supplierId)) {
                    item.expectedReceiveDate = selectedDate;
                }

            });

        });

    });    




    // updateAIGrandTotal();
}

// function updateRowTotal(index, row) {
//     if (!row) return;

//     const item = aiRecommendationItems[index];
//     const quantity = Number(item.orderedQuantity) || 0;
//     const unitCost = Number(item.unitCost) || 0;
//     const total = quantity * unitCost;

//     const totalCell = row.querySelector(".ai-row-total");

//     if (totalCell) {
//         totalCell.textContent = `₱${formatCurrency(total)}`;
//     }
// }


// function updateAIGrandTotal() {
//     // const grandTotalElement = document.getElementById("ai-grand-total");
//     const grandTotalElement = document.getElementById("aiOrderGrandTotal");
//     if (!grandTotalElement) return;

//     let grandTotal = 0;

//     aiRecommendationItems.forEach(item => {
//         const quantity = Number(item.orderedQuantity) || 0;
//         const unitCost = Number(item.unitCost) || 0;

//         grandTotal += quantity * unitCost;
//     });

//     grandTotalElement.textContent = `₱${formatCurrency(grandTotal)}`;
// }

function closeAIRecommendationModal() {
    const modal = document.getElementById("aiRecommendationModal");

    if (modal) {
        modal.style.display = "none";
    }
}

function formatNumber(value) {
    return Number(value || 0).toLocaleString("en-US", {
        maximumFractionDigits: 2
    });
}

// function formatCurrency(value) {
//     return Number(value || 0).toLocaleString("en-US", {
//         minimumFractionDigits: 2,
//         maximumFractionDigits: 2
//     });
// }

function escapeHTML(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}


// function openAIOrderConfirmation() {

//     if (aiRecommendationItems.length === 0) {
//         alert("There are no AI recommendations to confirm.");
//         return;
//     }

//     const confirmationModal =
//         document.getElementById("aiOrderConfirmationModal");

//     const confirmationContent =
//         document.getElementById("aiOrderConfirmationContent");

//     if (!confirmationModal || !confirmationContent) {
//         console.error(
//             "AI confirmation modal elements not found."
//         );
//         return;
//     }

//     confirmationContent.innerHTML = `
//         <p style="padding: 20px;">
//             Review your AI-generated order before placing it.
//         </p>
//     `;

//     confirmationModal.style.display = "flex";
// }


function openAIOrderConfirmation() {

    if (aiRecommendationItems.length === 0) {
        alert("There are no AI recommendations to confirm.");
        return;
    }

    const confirmationModal =
        document.getElementById("aiOrderConfirmationModal");

    const confirmationContent =
        document.getElementById("aiOrderConfirmationContent");

    if (!confirmationModal || !confirmationContent) {
        console.error(
            "AI confirmation modal elements not found."
        );
        return;
    }

    // Validate expected receive dates
    const supplierDates = {};

    aiRecommendationItems.forEach(item => {

        const supplierId =
            item.supplierId ?? "none";

        const date =
            item.expectedReceiveDate || "";

        if (!supplierDates[supplierId]) {
            supplierDates[supplierId] = date;
        }
    });

    for (const item of aiRecommendationItems) {

        const supplierId =
            item.supplierId ?? "none";

        if (!supplierDates[supplierId]) {

            alert(
                `Please select an Expected Receive Date for supplier:\n\n` +
                `${item.supplierName || "No Supplier"}`
            );

            return;
        }
    }


    // Group items by supplier
    const grouped = {};

    aiRecommendationItems.forEach(item => {
        const supplierId = item.supplierId ?? "none";

        const supplier = aiSuppliers.find(
            s => Number(s.Supplier_ID) === Number(item.supplierId)
        );

        if (!grouped[supplierId]) {
            grouped[supplierId] = {
                supplierId: supplierId,

                supplierName:
                    supplier?.CompanyName ||
                    supplier?.SupplierName ||
                    item.supplierName ||
                    "No Supplier",

                supplierAddress:
                    supplier?.Address ||
                    item.supplierAddress ||
                    "",

                supplierCity:
                    supplier?.City ||
                    item.supplierCity ||
                    "",

                supplierPhone:
                    supplier?.PhoneNumber ||
                    item.supplierPhone ||
                    "",

                supplierEmail:
                    supplier?.Email ||
                    item.supplierEmail ||
                    "",

                expectedReceiveDate:
                    item.expectedReceiveDate || "",

                items: []
            };
        }

        grouped[supplierId].items.push(item);

        if (
            !grouped[supplierId].expectedReceiveDate &&
            item.expectedReceiveDate
        ) {
            grouped[supplierId].expectedReceiveDate =
                item.expectedReceiveDate;
        }
    });

    let html = "";

    Object.values(grouped).forEach(group => {


        html += `
            <div
                class="ai-confirmation-supplier"
                style="
                    margin: 15px;
                    padding: 15px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    background: #fff;
                    color: #222;
                "
            >
                <h3
                    style="
                        margin-top: 0;
                        margin-bottom: 15px;
                        text-transform: uppercase;
                        background-color: rgb(255, 203, 203);
                        padding: 12px;
                        border-radius: 5px;
                    "
                >
                    ${escapeHTML(group.supplierName)}
                </h3>

                <div
                    style="
                        display: grid;
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                        gap: 15px 25px;
                        margin-bottom: 15px;
                    "
                >
                    <div>
                        <strong>Address</strong>
                        <div style="margin-top: 5px; overflow-wrap: anywhere;">
                            ${escapeHTML(group.supplierAddress || "No address provided")}
                        </div>
                    </div>

                    <div>
                        <strong>City</strong>
                        <div style="margin-top: 5px; overflow-wrap: anywhere;">
                            ${escapeHTML(group.supplierCity || "No city provided")}
                        </div>
                    </div>

                    <div>
                        <strong>Phone Number</strong>
                        <div
                            style="
                                margin-top: 5px;
                                color: rgb(13, 99, 46);
                                overflow-wrap: anywhere;
                            "
                        >
                            ${escapeHTML(group.supplierPhone || "No phone number")}
                        </div>
                    </div>

                    <div>
                        <strong>Email</strong>
                        <div
                            style="
                                margin-top: 5px;
                                color: blue;
                                overflow-wrap: anywhere;
                            "
                        >
                            ${escapeHTML(group.supplierEmail || "No email")}
                        </div>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <strong>Expected Receive Date</strong>
                        <div style="margin-top: 5px; font-weight: bold;">
                            ${escapeHTML(group.expectedReceiveDate || "No date selected")}
                        </div>
                    </div>
                </div>

                <table
                    style="
                        width: 100%;
                        border-collapse: collapse;
                    "
                >


                    <thead>
                        <tr>
                            <th
                                style="
                                    text-align: left;
                                    padding: 8px;
                                    border-bottom: 1px solid #ddd;
                                "
                            >
                                Product
                            </th>

                            <th
                                style="
                                    text-align: center;
                                    padding: 8px;
                                    border-bottom: 1px solid #ddd;
                                "
                            >
                                Order Quantity
                            </th>

                            <th
                                style="
                                    text-align: center;
                                    padding: 8px;
                                    border-bottom: 1px solid #ddd;
                                "
                            >
                                Pack Size
                            </th>
                        </tr>
                    </thead>

                    <tbody>
        `;

        group.items.forEach(item => {

            const quantity =
                Number(item.orderedQuantity) || 0;

            const packSize =
                Number(item.packSize) || 1;

            html += `
                <tr>

                    <td
                        style="
                            padding: 8px;
                            border-bottom: 1px solid #eee;
                        "
                    >
                        <strong>
                            ${escapeHTML(item.productName)}
                        </strong>

                        <br>

                        <small>
                            Product ID: ${item.productId}
                        </small>
                    </td>

                    <td
                        style="
                            text-align: center;
                            padding: 8px;
                            border-bottom: 1px solid #eee;
                        "
                    >
                        ${formatNumber(quantity)}
                    </td>

                    <td
                        style="
                            text-align: center;
                            padding: 8px;
                            border-bottom: 1px solid #eee;
                        "
                    >
                        ${formatNumber(packSize)}
                    </td>

                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>

            </div>
        `;
    });

    confirmationContent.innerHTML = html;

    confirmationModal.style.display = "flex";
}



async function confirmAIOrder() {
    if (aiRecommendationItems.length === 0) {
        alert("There are no AI recommendations to confirm.");
        return;
    }

    // Final pack-size validation before saving
    for (const item of aiRecommendationItems) {
        const quantity = Number(item.orderedQuantity) || 0;
        const packSize = Number(item.packSize) || 1;

        if (quantity < packSize || quantity % packSize !== 0) {
            alert(
                `Invalid order quantity for ${item.productName}.\n\n` +
                `Quantity must be a multiple of ${packSize}.`
            );
            return;
        }
    }

    try {
        // const response = await fetch("save_ai_order.php", {
        const response = await fetch("save_ai_restock.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                items: aiRecommendationItems
            })
        });

        const rawResponse = await response.text();
        console.log("SAVE AI ORDER RAW RESPONSE:", rawResponse);

        let result;

        try {
            result = JSON.parse(rawResponse);
        } catch (error) {
            console.error("Invalid JSON response:", rawResponse);
            alert("Server returned an invalid response.");
            return;
        }

        if (!result.success) {
            alert(
                "Failed to save AI order:\n\n" +
                (result.message || result.error || "Unknown error.")
            );
            return;
        }

        alert(result.message || "AI order saved successfully.");

        closeAIRecommendationModal();
        location.reload();
    } catch (error) {
        console.error("Error confirming AI order:", error);
        alert("An error occurred while saving the AI order.");
    }
}


// document.addEventListener("DOMContentLoaded", function () {
//     const openButton = document.getElementById("openAIRecommendationBtn");
//     const closeButton = document.getElementById("closeAIRecommendationBtn");
//     const confirmButton = document.getElementById("confirmAIOrderBtn");

//     if (openButton) {
//         openButton.addEventListener("click", openAIRecommendationModal);
//     }

//     if (closeButton) {
//         closeButton.addEventListener("click", closeAIRecommendationModal);
//     }

//     if (confirmButton) {
//         confirmButton.addEventListener("click", confirmAIOrder);
//     }
// });

document.addEventListener("DOMContentLoaded", function () {

    const closeButton =
        document.getElementById("aiOrderCloseBtn");

    const cancelButton =
        document.getElementById("aiOrderCancelBtn");

    const confirmButton =
        document.getElementById("aiOrderConfirmBtn");




    const confirmationCloseButton =
        document.getElementById("aiConfirmationCloseBtn");

    const confirmationCancelButton =
        document.getElementById("aiConfirmationCancelBtn");



    const confirmationConfirmButton =
        document.getElementById("aiConfirmationConfirmBtn");


    if (confirmationCloseButton) {
        confirmationCloseButton.addEventListener(
            "click",
            function () {
                document.getElementById(
                    "aiOrderConfirmationModal"
                ).style.display = "none";
            }
        );
    }

    if (confirmationCancelButton) {
        confirmationCancelButton.addEventListener(
            "click",
            function () {
                document.getElementById(
                    "aiOrderConfirmationModal"
                ).style.display = "none";
            }
        );
    }



    if (confirmationConfirmButton) {
        confirmationConfirmButton.addEventListener(
            "click",
            confirmAIOrder
        );
    }



    if (closeButton) {
        closeButton.addEventListener(
            "click",
            closeAIRecommendationModal
        );
    }

    if (cancelButton) {
        cancelButton.addEventListener(
            "click",
            closeAIRecommendationModal
        );
    }

    if (confirmButton) {
        confirmButton.addEventListener(
            "click",
            openAIOrderConfirmation
        );
    }

    

});




