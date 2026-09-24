// let aiRecommendationItems = [];
// let aiSuppliers = [];

// async function openAIRecommendationModal() {
//     const modal = document.getElementById("aiRecommendationModal");
//     const container = document.getElementById("aiRecommendationContainer");

//     if (!modal || !container) return;

//     modal.style.display = "flex";
//     container.innerHTML = "<p>Loading AI recommendations...</p>";

//     try {
//         const [recommendationResponse, supplierResponse] = await Promise.all([
//             fetch("get_ai_recommendations.php"),
//             fetch("get_supplier.php")
//         ]);

//         if (!recommendationResponse.ok) {
//             throw new Error(`AI recommendation request failed: ${recommendationResponse.status}`);
//         }

//         if (!supplierResponse.ok) {
//             throw new Error(`Supplier request failed: ${supplierResponse.status}`);
//         }

//         const result = await recommendationResponse.json();
//         const supplierResult = await supplierResponse.json();

//         if (!result.success) {
//             throw new Error(result.error || "Failed to load AI recommendations.");
//         }

//         aiSuppliers = supplierResult || [];
//         aiRecommendationItems = result.data || [];

//         if (aiRecommendationItems.length === 0) {
//             container.innerHTML = "<p>No AI recommendations available.</p>";
//             return;
//         }

//         renderAIRecommendations();
//     } catch (error) {
//         console.error("AI Recommendation Error:", error);
//         container.innerHTML = `
//             <p style="color:red;">
//                 Failed to load AI recommendations.<br>
//                 ${escapeHTML(error.message)}
//             </p>
//         `;
//     }
// }


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

        if (!grouped[supplierId]) {
            grouped[supplierId] = {
                supplierId: item.supplierId,
                supplierName: item.supplierName || "No Supplier",
                supplierLocation: item.supplierLocation || "",
                items: []
            };
        }

        grouped[supplierId].items.push({ item, index });
    });

    let html = "";

    Object.values(grouped).forEach(group => {
        html += `
            <div class="ai-supplier-group">
                <h3>
                    Supplier:
                    ${escapeHTML(group.supplierName)}
                    ${group.supplierLocation
                        ? ` - ${escapeHTML(group.supplierLocation)}`
                        : ""}
                </h3>

                <table class="ai-recommendation-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Current Stock</th>
                            <th>Predicted Demand</th>
                            <th>AI Recommendation</th>
                            <th>Order Quantity</th>
                            <th>Supplier</th>
                            <th>Unit Cost</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        group.items.forEach(({ item, index }) => {
            const packSize = Number(item.packSize) || 1;
            const orderedQuantity = Number(item.orderedQuantity) || packSize;
            const unitCost = Number(item.unitCost) || 0;
            const total = orderedQuantity * unitCost;

            let supplierOptions = "";

            aiSuppliers.forEach(supplier => {
                const selected =
                    Number(supplier.Supplier_ID) === Number(item.supplierId)
                        ? "selected"
                        : "";

                supplierOptions += `
                    <option value="${supplier.Supplier_ID}" ${selected}>
                        ${escapeHTML(supplier.SupplierName)}
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

                    <td>
                        ₱${formatCurrency(unitCost)}
                    </td>

                    <td class="ai-row-total">
                        ₱${formatCurrency(total)}
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

            const row = this.closest("tr");
            updateRowTotal(index, row);
            updateAIGrandTotal();
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
            item.supplierName = supplier.SupplierName;
            item.supplierLocation = supplier.Location || "";

            renderAIRecommendations();
        });
    });

    updateAIGrandTotal();
}

function updateRowTotal(index, row) {
    if (!row) return;

    const item = aiRecommendationItems[index];
    const quantity = Number(item.orderedQuantity) || 0;
    const unitCost = Number(item.unitCost) || 0;
    const total = quantity * unitCost;

    const totalCell = row.querySelector(".ai-row-total");

    if (totalCell) {
        totalCell.textContent = `₱${formatCurrency(total)}`;
    }
}

function updateAIGrandTotal() {
    // const grandTotalElement = document.getElementById("ai-grand-total");
    const grandTotalElement = document.getElementById("aiOrderGrandTotal");
    if (!grandTotalElement) return;

    let grandTotal = 0;

    aiRecommendationItems.forEach(item => {
        const quantity = Number(item.orderedQuantity) || 0;
        const unitCost = Number(item.unitCost) || 0;

        grandTotal += quantity * unitCost;
    });

    grandTotalElement.textContent = `₱${formatCurrency(grandTotal)}`;
}

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

function formatCurrency(value) {
    return Number(value || 0).toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function escapeHTML(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
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
        const response = await fetch("save_ai_order.php", {
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
            confirmAIOrder
        );
    }

});








// // ============================================================
// // AI RECOMMENDATION
// // ============================================================

// let aiRecommendationItems = [];
// let aiSuppliers = [];


// // ============================================================
// // OPEN AI RECOMMENDATION MODAL
// // ============================================================

// async function openAIRecommendationModal() {

//     const modal = document.getElementById('aiRecommendationModal');
//     const supplierContainer = document.getElementById('aiSupplierSections');

//     if (!modal || !supplierContainer) {
//         console.error('AI Recommendation modal elements not found.');
//         return;
//     }

//     // Show modal
//     modal.style.display = 'flex';

//     // Show loading message
//     supplierContainer.innerHTML = `
//         <div style="
//             padding: 30px;
//             text-align: center;
//             color: #666;
//             font-size: 16px;
//         ">
//             Loading AI recommendations...
//         </div>
//     `;

//     try {

//         // ====================================================
//         // GET REAL AI RECOMMENDATIONS FROM PHP
//         // ====================================================

//         // ====================================================
//         // GET AI RECOMMENDATIONS + SUPPLIERS
//         // ====================================================

//         const [recommendationResponse, supplierResponse] =
//             await Promise.all([
//                 fetch('get_ai_recommendations.php'),
//                 fetch('get_supplier.php')
//             ]);


//         if (!recommendationResponse.ok) {
//             throw new Error(
//                 `AI recommendation HTTP error: ${recommendationResponse.status}`
//             );
//         }


//         if (!supplierResponse.ok) {
//             throw new Error(
//                 `Supplier HTTP error: ${supplierResponse.status}`
//             );
//         }


//         const result =
//             await recommendationResponse.json();

//         const supplierResult =
//             await supplierResponse.json();


//         console.log(
//             'AI Recommendation Response:',
//             result
//         );

//         console.log(
//             'Supplier Response:',
//             supplierResult
//         );


//         // AI recommendation response
//         if (!result.success) {

//             throw new Error(
//                 result.error ||
//                 'Failed to load AI recommendations.'
//             );

//         }


//         // Your existing get_supplier.php
//         // returns an array directly.
//         aiSuppliers =
//             supplierResult || [];


//         aiRecommendationItems =
//             result.data || [];

//         // if (!response.ok) {
//         //     throw new Error(
//         //         `HTTP error: ${response.status}`
//         //     );
//         // }

//         // const result = await response.json();

//         // console.log('AI Recommendation Response:', result);

//         // if (!result.success) {
//         //     throw new Error(
//         //         result.error || 'Failed to load AI recommendations.'
//         //     );
//         // }

//         // // Save the real recommendations
//         // aiRecommendationItems = result.data || [];

//         // ====================================================
//         // NO RECOMMENDATIONS
//         // ====================================================

//         if (aiRecommendationItems.length === 0) {

//             supplierContainer.innerHTML = `
//                 <div style="
//                     padding: 30px;
//                     text-align: center;
//                     color: #666;
//                 ">
//                     No products currently need to be reordered.
//                 </div>
//             `;

//             updateAIGrandTotal();
//             return;
//         }

//         // ====================================================
//         // DISPLAY RECOMMENDATIONS
//         // ====================================================

//         renderAIRecommendations();

//     } catch (error) {

//         console.error(
//             'Error loading AI recommendations:',
//             error
//         );

//         supplierContainer.innerHTML = `
//             <div style="
//                 padding: 30px;
//                 text-align: center;
//                 color: #dc3545;
//             ">
//                 <strong>Failed to load AI recommendations.</strong>
//                 <br><br>
//                 ${escapeHTML(error.message)}
//             </div>
//         `;

//     }
// }


// // ============================================================
// // RENDER AI RECOMMENDATIONS
// // ============================================================

// function renderAIRecommendations() {

//     const container =
//         document.getElementById('aiSupplierSections');

//     if (!container) {
//         return;
//     }

//     container.innerHTML = '';

//     // ========================================================
//     // GROUP PRODUCTS BY SUPPLIER
//     // ========================================================

//     const supplierGroups = {};

//     aiRecommendationItems.forEach((item, index) => {

//         const supplierId =
//             item.supplierId !== null
//                 ? item.supplierId
//                 : 'none';

//         if (!supplierGroups[supplierId]) {

//             supplierGroups[supplierId] = {
//                 supplierId: item.supplierId,
//                 supplierName: item.supplierName || 'No Supplier',
//                 supplierLocation: item.supplierLocation || '',
//                 items: []
//             };

//         }

//         // Keep track of original item index
//         item._index = index;

//         supplierGroups[supplierId].items.push(item);

//     });


//     // ========================================================
//     // CREATE SUPPLIER SECTIONS
//     // ========================================================

//     Object.values(supplierGroups).forEach(group => {

//         const section =
//             document.createElement('div');

//         section.className =
//             'ai-supplier-section';


//         // ----------------------------------------------------
//         // SUPPLIER HEADER
//         // ----------------------------------------------------

//         const supplierHeader =
//             document.createElement('div');

//         supplierHeader.className =
//             'ai-supplier-header';


//         const supplierTitle =
//             document.createElement('h3');

//         supplierTitle.textContent =
//             group.supplierName;


//         const supplierLocation =
//             document.createElement('span');

//         supplierLocation.className =
//             'ai-supplier-location';

//         supplierLocation.textContent =
//             group.supplierLocation
//                 ? `— ${group.supplierLocation}`
//                 : '';


//         supplierHeader.appendChild(
//             supplierTitle
//         );

//         supplierHeader.appendChild(
//             supplierLocation
//         );


//         section.appendChild(
//             supplierHeader
//         );


//         // ----------------------------------------------------
//         // TABLE
//         // ----------------------------------------------------

//         const table =
//             document.createElement('table');

//         table.className =
//             'ai-order-table';


//         table.innerHTML = `
//             <thead>
//                 <tr>
//                     <th>Product</th>
//                     <th>Current Stock</th>
//                     <th>Predicted Demand</th>
//                     <th>AI Recommendation</th>
//                     <th>Order Quantity</th>
//                     <th>Supplier</th>
//                     <th>Unit Cost</th>
//                     <th>Total</th>
//                 </tr>
//             </thead>
//         `;


//         const tbody =
//             document.createElement('tbody');


//         // ----------------------------------------------------
//         // PRODUCT ROWS
//         // ----------------------------------------------------

//         group.items.forEach(item => {

//             const row =
//                 document.createElement('tr');

//             const index =
//                 item._index;


//             // Product
//             const productCell =
//                 document.createElement('td');

//             productCell.textContent =
//                 item.productName;


//             // Current Stock
//             const stockCell =
//                 document.createElement('td');

//             stockCell.textContent =
//                 formatNumber(item.currentStock);


//             // Predicted Demand
//             const demandCell =
//                 document.createElement('td');

//             demandCell.textContent =
//                 formatNumber(item.predictedDemand);


//             // AI Recommendation
//             const recommendationCell =
//                 document.createElement('td');

//             recommendationCell.textContent =
//                 formatNumber(item.recommendedQuantity);


//             // Order Quantity
//             const quantityCell =
//                 document.createElement('td');

//             const quantityInput =
//                 document.createElement('input');

//             quantityInput.type =
//                 'number';

//             quantityInput.min =
//                 packSize;
//                 // '0';

//             quantityInput.step =
//                 packSize;
//                 // item.packSize || 1;

//             quantityInput.value =
//                 item.orderedQuantity;

//             quantityInput.className =
//                 'ai-order-quantity';

//             quantityInput.dataset.index =
//                 index;


//             // quantityInput.addEventListener(
//             //     'input',
//             //     function () {

//             //         const newQuantity =
//             //             parseInt(this.value) || 0;

//             //         aiRecommendationItems[index]
//             //             .orderedQuantity =
//             //             newQuantity;

//             //         updateRowTotal(
//             //             index,
//             //             row
//             //         );

//             //         updateAIGrandTotal();

//             //     }
//             // );


//             quantityInput.addEventListener(
//                 'input',
//                 function () {

//                     const newQuantity =
//                         parseInt(this.value) || 0;

//                     const packSize =
//                         Number(aiRecommendationItems[index].packSize) || 1;

//                     // Quantity must be at least one pack
//                     // and must be a complete multiple of the pack size
//                     if (
//                         newQuantity < packSize ||
//                         newQuantity % packSize !== 0
//                     ) {

//                         this.setCustomValidity(
//                             `Quantity must be a multiple of ${packSize}.`
//                         );

//                         this.style.borderColor = 'red';

//                     } else {

//                         this.setCustomValidity('');

//                         this.style.borderColor = '';

//                         aiRecommendationItems[index]
//                             .orderedQuantity =
//                             newQuantity;

//                         updateRowTotal(
//                             index,
//                             row
//                         );

//                         updateAIGrandTotal();
//                     }
//                 }
//             );            


//             quantityCell.appendChild(
//                 quantityInput
//             );


//             // ------------------------------------------------
//             // SUPPLIER DROPDOWN
//             // ------------------------------------------------

//             // ------------------------------------------------
//             // SUPPLIER DROPDOWN
//             // ------------------------------------------------

//             const supplierCell =
//                 document.createElement('td');

//             const supplierSelect =
//                 document.createElement('select');

//             supplierSelect.className =
//                 'ai-order-supplier';

//             supplierSelect.dataset.index =
//                 index;


//             // Add ALL suppliers from your existing
//             // get_supplier.php
//             aiSuppliers.forEach(supplier => {

//                 const option =
//                     document.createElement('option');


//                 option.value =
//                     supplier.Supplier_ID;


//                 option.text =
//                     `${supplier.SupplierName} — ${supplier.Location}`;


//                 // Select the product's CURRENT supplier
//                 // as the default
//                 if (
//                     Number(supplier.Supplier_ID) ===
//                     Number(item.supplierId)
//                 ) {

//                     option.selected = true;

//                 }


//                 supplierSelect.appendChild(
//                     option
//                 );

//             });


//             // When manager changes supplier
                        
//             supplierSelect.addEventListener(
//                 'change',
//                 function () {
                    
//                     const selectedSupplier =
//                         aiSuppliers.find(
//                             supplier =>
//                                 Number(supplier.Supplier_ID) ===
//                                 Number(this.value)
//                         );


//                     if (!selectedSupplier) {
//                         return;
//                     }


//                     // Update the item's selected supplier
//                     aiRecommendationItems[index]
//                         .supplierId =
//                         Number(selectedSupplier.Supplier_ID);

//                     aiRecommendationItems[index]
//                         .supplierName =
//                         selectedSupplier.SupplierName;

//                     aiRecommendationItems[index]
//                         .supplierLocation =
//                         selectedSupplier.Location;


//                     // Re-render the entire supplier grouping
//                     renderAIRecommendations();

//                 }
//             );

//             // supplierSelect.addEventListener(
//             //     'change',
//             //     function () {

//             //         const selectedSupplier =
//             //             aiSuppliers.find(
//             //                 supplier =>
//             //                     Number(supplier.Supplier_ID) ===
//             //                     Number(this.value)
//             //             );


//             //         if (selectedSupplier) {

//             //             aiRecommendationItems[index]
//             //                 .supplierId =
//             //                 Number(selectedSupplier.Supplier_ID);

//             //             aiRecommendationItems[index]
//             //                 .supplierName =
//             //                 selectedSupplier.SupplierName;

//             //             aiRecommendationItems[index]
//             //                 .supplierLocation =
//             //                 selectedSupplier.Location;

//             //         }

//             //     }
//             // );


//             supplierCell.appendChild(
//                 supplierSelect
//             );

            
//             // const supplierCell =
//             //     document.createElement('td');

//             // const supplierSelect =
//             //     document.createElement('select');

//             // supplierSelect.className =
//             //     'ai-order-supplier';

//             // supplierSelect.dataset.index =
//             //     index;


//             // // For now, the dropdown contains
//             // // the current supplier.
//             // const option =
//             //     document.createElement('option');

//             // option.value =
//             //     item.supplierId ?? '';

//             // option.textContent =
//             //     item.supplierLocation
//             //         ? `${item.supplierName} — ${item.supplierLocation}`
//             //         : item.supplierName;

//             // supplierSelect.appendChild(
//             //     option
//             // );


//             // supplierSelect.addEventListener(
//             //     'change',
//             //     function () {

//             //         aiRecommendationItems[index]
//             //             .supplierId =
//             //             this.value;

//             //     }
//             // );


//             // supplierCell.appendChild(
//             //     supplierSelect
//             // );


//             // Unit Cost
//             const costCell =
//                 document.createElement('td');

//             costCell.textContent =
//                 formatCurrency(item.unitCost);


//             // Total
//             const totalCell =
//                 document.createElement('td');

//             totalCell.className =
//                 'ai-row-total';

//             totalCell.textContent =
//                 formatCurrency(
//                     item.orderedQuantity *
//                     item.unitCost
//                 );


//             // ------------------------------------------------
//             // ADD CELLS
//             // ------------------------------------------------

//             row.appendChild(
//                 productCell
//             );

//             row.appendChild(
//                 stockCell
//             );

//             row.appendChild(
//                 demandCell
//             );

//             row.appendChild(
//                 recommendationCell
//             );

//             row.appendChild(
//                 quantityCell
//             );

//             row.appendChild(
//                 supplierCell
//             );

//             row.appendChild(
//                 costCell
//             );

//             row.appendChild(
//                 totalCell
//             );


//             tbody.appendChild(
//                 row
//             );

//         });


//         table.appendChild(
//             tbody
//         );

//         section.appendChild(
//             table
//         );

//         container.appendChild(
//             section
//         );

//     });


//     // Calculate initial total
//     updateAIGrandTotal();
// }


// // ============================================================
// // UPDATE ROW TOTAL
// // ============================================================

// function updateRowTotal(index, row) {

//     const item =
//         aiRecommendationItems[index];

//     const total =
//         item.orderedQuantity *
//         item.unitCost;


//     const totalCell =
//         row.querySelector('.ai-row-total');

//     if (totalCell) {

//         totalCell.textContent =
//             formatCurrency(total);

//     }
// }


// // ============================================================
// // UPDATE GRAND TOTAL
// // ============================================================

// function updateAIGrandTotal() {

//     const totalElement =
//         document.getElementById(
//             'aiOrderGrandTotal'
//         );

//     if (!totalElement) {
//         return;
//     }


//     let grandTotal = 0;


//     aiRecommendationItems.forEach(item => {

//         const quantity =
//             Number(item.orderedQuantity) || 0;

//         const unitCost =
//             Number(item.unitCost) || 0;

//         grandTotal +=
//             quantity * unitCost;

//     });


//     totalElement.textContent =
//         formatCurrency(grandTotal);
// }


// // ============================================================
// // CLOSE MODAL
// // ============================================================

// function closeAIRecommendationModal() {

//     const modal =
//         document.getElementById(
//             'aiRecommendationModal'
//         );

//     if (modal) {

//         modal.style.display =
//             'none';

//     }
// }


// // ============================================================
// // FORMAT NUMBER
// // ============================================================

// function formatNumber(value) {

//     const number =
//         Number(value) || 0;

//     return number.toLocaleString(
//         'en-US',
//         {
//             maximumFractionDigits: 2
//         }
//     );
// }


// // ============================================================
// // FORMAT CURRENCY
// // ============================================================

// function formatCurrency(value) {

//     const number =
//         Number(value) || 0;

//     return '₱' +
//         number.toLocaleString(
//             'en-PH',
//             {
//                 minimumFractionDigits: 2,
//                 maximumFractionDigits: 2
//             }
//         );
// }


// // ============================================================
// // BASIC HTML ESCAPE
// // ============================================================

// function escapeHTML(value) {

//     return String(value)
//         .replace(/&/g, '&amp;')
//         .replace(/</g, '&lt;')
//         .replace(/>/g, '&gt;')
//         .replace(/"/g, '&quot;')
//         .replace(/'/g, '&#039;');

// }


// // ============================================================
// // BUTTON EVENTS
// // ============================================================

// // ============================================================
// // CONFIRM AI ORDER
// // ============================================================

// async function confirmAIOrder() {

//     // --------------------------------------------------------
//     // Make sure there are items
//     // --------------------------------------------------------

//     if (
//         !aiRecommendationItems ||
//         aiRecommendationItems.length === 0
//     ) {

//         alert(
//             'There are no items to order.'
//         );

//         return;

//     }


//     // --------------------------------------------------------
//     // Confirm with manager
//     // --------------------------------------------------------

//     const confirmOrder =
//         confirm(
//             'Are you sure you want to confirm this order?'
//         );


//     if (!confirmOrder) {
//         return;
//     }


//     // --------------------------------------------------------
//     // Disable button while saving
//     // --------------------------------------------------------

//     const confirmButton =
//         document.getElementById(
//             'aiOrderConfirmBtn'
//         );

        
//     if (confirmButton) {

//         confirmButton.disabled =
//             true;

//         confirmButton.textContent =
//             'Saving Order...';

//     }


//     try {

//         // ====================================================
//         // SEND AI ORDER TO PHP
//         // ====================================================

//         const response =
//             await fetch(
//                 'save_ai_order.php',
//                 {
//                     method: 'POST',

//                     headers: {
//                         'Content-Type':
//                             'application/json'
//                     },

//                     body: JSON.stringify({
//                         items:
//                             aiRecommendationItems
//                     })
//                 }
//             );


//         // ====================================================
//         // READ PHP RESPONSE
//         // ====================================================

//         // const result =
//         //     await response.json();


//         // console.log(
//         //     'Save AI Order Response:',
//         //     result
//         // );


//         const responseText =
//             await response.text();

//         console.log(
//             'RAW SAVE AI ORDER RESPONSE:',
//             responseText
//         );

//         let result;

//         try {

//             result =
//                 JSON.parse(responseText);

//         // } catch (jsonError) {

//         //     console.error(
//         //         'SAVE AI ORDER RETURNED INVALID JSON:',
//         //         jsonError
//         //     );

//         //     throw new Error(
//         //         'save_ai_order.php returned an error instead of JSON. Check the browser console for the RAW response.'
//         //     );
//         // }
//         } catch (jsonError) {

//             console.error(
//                 'SAVE AI ORDER RETURNED INVALID JSON:',
//                 jsonError
//             );

//             console.error(
//                 'ACTUAL PHP RESPONSE:',
//                 responseText
//             );

//             alert(
//                 'PHP ERROR RESPONSE:\n\n' +
//                 responseText
//             );

//             throw new Error(
//                 'save_ai_order.php returned an error instead of JSON.'
//             );
//         }

//         console.log(
//             'Save AI Order Response:',
//             result
//         );


//         // ====================================================
//         // PHP ERROR
//         // ====================================================

//         if (!response.ok || !result.success) {

//             throw new Error(
//                 result.error ||
//                 'Failed to save AI order.'
//             );

//         }


//         // ====================================================
//         // SUCCESS
//         // ====================================================

//         alert(
//             'AI order successfully confirmed!'
//         );


//         // Close modal
//         closeAIRecommendationModal();


//         console.log(
//             'Created ListToOrder IDs:',
//             result.listToOrderIds
//         );


//     } catch (error) {

//         console.error(
//             'Error confirming AI order:',
//             error
//         );


//         alert(
//             'Failed to confirm AI order.\n\n' +
//             error.message
//         );


//     } finally {

//         // ----------------------------------------------------
//         // Restore button
//         // ----------------------------------------------------

//         if (confirmButton) {

//             confirmButton.disabled =
//                 false;

//             confirmButton.textContent =
//                 'Confirm Order';

//         }

//     }

// }


// // document.addEventListener(
// //     'DOMContentLoaded',
// //     function () {

// //         const closeButton =
// //             document.getElementById(
// //                 'aiOrderCloseBtn'
// //             );

// //         const cancelButton =
// //             document.getElementById(
// //                 'aiOrderCancelBtn'
// //             );


// //         if (closeButton) {

// //             closeButton.addEventListener(
// //                 'click',
// //                 closeAIRecommendationModal
// //             );

// //         }


// //         if (cancelButton) {

// //             cancelButton.addEventListener(
// //                 'click',
// //                 closeAIRecommendationModal
// //             );

// //         }

// //     }
// // );

// document.addEventListener(
//     'DOMContentLoaded',
//     function () {

//         const closeButton =
//             document.getElementById(
//                 'aiOrderCloseBtn'
//             );

//         const cancelButton =
//             document.getElementById(
//                 'aiOrderCancelBtn'
//             );

//         const confirmButton =
//             document.getElementById(
//                 'aiOrderConfirmBtn'
//             );


//         // ====================================================
//         // CLOSE BUTTON
//         // ====================================================

//         if (closeButton) {

//             closeButton.addEventListener(
//                 'click',
//                 closeAIRecommendationModal
//             );

//         }


//         // ====================================================
//         // CANCEL BUTTON
//         // ====================================================

//         if (cancelButton) {

//             cancelButton.addEventListener(
//                 'click',
//                 closeAIRecommendationModal
//             );

//         }


//         // ====================================================
//         // CONFIRM ORDER BUTTON
//         // ====================================================

//         if (confirmButton) {

//             confirmButton.addEventListener(
//                 'click',
//                 confirmAIOrder
//             );

//         }

//     }
// );


























































// // /* =========================================================
// //    AI RECOMMENDATION
// //    Separate script - does not modify homepagescript.js
// //    ========================================================= */

// // let aiRecommendationItems = [
// //     {
// //         productId: 24,
// //         productName: "Toblerone",
// //         supplierId: 1,
// //         supplierName: "James",
// //         currentStock: 47,
// //         predictedDemand: 311,
// //         netDemand: 264,
// //         packSize: 1,
// //         recommendedQuantity: 264,
// //         orderedQuantity: 264,
// //         unitCost: 0
// //     },

// //     {
// //         productId: 58,
// //         productName: "MILO SULIT PACK",
// //         supplierId: 1,
// //         supplierName: "James",
// //         currentStock: 7,
// //         predictedDemand: 262,
// //         netDemand: 255,
// //         packSize: 1,
// //         recommendedQuantity: 255,
// //         orderedQuantity: 255,
// //         unitCost: 200
// //     },

// //     {
// //         productId: 21,
// //         productName: "nmax 155 left or right motorcycle Brake Maste",
// //         supplierId: 1,
// //         supplierName: "James",
// //         currentStock: -1,
// //         predictedDemand: 240,
// //         netDemand: 241,
// //         packSize: 1,
// //         recommendedQuantity: 241,
// //         orderedQuantity: 241,
// //         unitCost: 0
// //     },

// //     {
// //         productId: 23,
// //         productName: "Russi",
// //         supplierId: 1,
// //         supplierName: "James",
// //         currentStock: 0,
// //         predictedDemand: 73,
// //         netDemand: 73,
// //         packSize: 1,
// //         recommendedQuantity: 73,
// //         orderedQuantity: 73,
// //         unitCost: 0
// //     },

// //     {
// //         productId: 13,
// //         productName: "LEO RAPTOR TIRE 4",
// //         supplierId: 2,
// //         supplierName: "Clark",
// //         currentStock: 3,
// //         predictedDemand: 245,
// //         netDemand: 242,
// //         packSize: 1,
// //         recommendedQuantity: 242,
// //         orderedQuantity: 242,
// //         unitCost: 0
// //     },

// //     {
// //         productId: 22,
// //         productName: "Russi Exhaust 302",
// //         supplierId: 2,
// //         supplierName: "Clark",
// //         currentStock: 118,
// //         predictedDemand: 303,
// //         netDemand: 185,
// //         packSize: 1,
// //         recommendedQuantity: 185,
// //         orderedQuantity: 185,
// //         unitCost: 0
// //     },

// //     {
// //         productId: 57,
// //         productName: "Milo",
// //         supplierId: 6,
// //         supplierName: "ANTONIO",
// //         currentStock: 6,
// //         predictedDemand: 202,
// //         netDemand: 196,
// //         packSize: 12,
// //         recommendedQuantity: 204,
// //         orderedQuantity: 204,
// //         unitCost: 0
// //     }
// // ];


// // /* =========================================================
// //    OPEN / CLOSE
// //    ========================================================= */

// // function openAIRecommendationModal() {

// //     const modal = document.getElementById(
// //         "aiRecommendationModal"
// //     );

// //     if (!modal) {
// //         console.error(
// //             "AI Recommendation modal not found."
// //         );
// //         return;
// //     }

// //     modal.style.display = "block";

// //     renderAIRecommendation();
// // }


// // function closeAIRecommendationModal() {

// //     const modal = document.getElementById(
// //         "aiRecommendationModal"
// //     );

// //     if (!modal) {
// //         return;
// //     }

// //     modal.style.display = "none";
// // }


// // /* =========================================================
// //    RENDER AI RECOMMENDATION
// //    ========================================================= */

// // function renderAIRecommendation() {

// //     const container = document.getElementById(
// //         "aiSupplierSections"
// //     );

// //     if (!container) {
// //         console.error(
// //             "AI supplier sections container not found."
// //         );
// //         return;
// //     }

// //     container.innerHTML = "";

// //     const supplierGroups = {};


// //     /* Group products by supplier */

// //     aiRecommendationItems.forEach(function (item) {

// //         const supplierId = item.supplierId;

// //         if (!supplierGroups[supplierId]) {

// //             supplierGroups[supplierId] = {
// //                 supplierId: supplierId,
// //                 supplierName: item.supplierName,
// //                 items: []
// //             };

// //         }

// //         supplierGroups[supplierId].items.push(item);

// //     });


// //     /* Create supplier sections */

// //     Object.values(supplierGroups).forEach(
// //         function (supplierGroup) {

// //             const section =
// //                 createSupplierSection(
// //                     supplierGroup
// //                 );

// //             container.appendChild(section);

// //         }
// //     );


// //     updateAIGrandTotal();
// // }


// // /* =========================================================
// //    SUPPLIER SECTION
// //    ========================================================= */

// // function createSupplierSection(supplierGroup) {

// //     const section =
// //         document.createElement("div");

// //     section.className =
// //         "ai-supplier-section";


// //     const header =
// //         document.createElement("div");

// //     header.className =
// //         "ai-supplier-header";

// //     header.textContent =
// //         "Supplier " +
// //         supplierGroup.supplierId +
// //         " — " +
// //         supplierGroup.supplierName;

// //     section.appendChild(header);


// //     const table =
// //         document.createElement("table");

// //     table.className =
// //         "ai-order-table";


// //     table.innerHTML = `
// //         <thead>
// //             <tr>
// //                 <th>Product ID / Name</th>
// //                 <th>Current Stock</th>
// //                 <th>Predicted Demand</th>
// //                 <th>Net Demand</th>
// //                 <th>Pack Size</th>
// //                 <th>AI Recommended</th>
// //                 <th>Adjusted Order Quantity</th>
// //                 <th>Unit Price</th>
// //                 <th>Total Price</th>
// //                 <th>Change Supplier?</th>
// //             </tr>
// //         </thead>

// //         <tbody></tbody>
// //     `;


// //     const tbody =
// //         table.querySelector("tbody");


// //     supplierGroup.items.forEach(
// //         function (item) {

// //             const row =
// //                 createAIProductRow(item);

// //             tbody.appendChild(row);

// //         }
// //     );


// //     section.appendChild(table);

// //     return section;
// // }


// // /* =========================================================
// //    PRODUCT ROW
// //    ========================================================= */

// // function createAIProductRow(item) {

// //     const row =
// //         document.createElement("tr");

// //     row.dataset.productId =
// //         item.productId;


// //     row.innerHTML = `
// //         <td class="ai-product-name">
// //             #${item.productId} / ${item.productName}
// //         </td>

// //         <td class="ai-number-cell">
// //             ${item.currentStock}
// //         </td>

// //         <td class="ai-number-cell">
// //             ${item.predictedDemand}
// //         </td>

// //         <td class="ai-number-cell">
// //             ${item.netDemand}
// //         </td>

// //         <td class="ai-number-cell">
// //             ${item.packSize}
// //         </td>

// //         <td class="ai-number-cell">
// //             ${item.recommendedQuantity}
// //         </td>

// //         <td>
// //             <input
// //                 type="number"
// //                 class="ai-order-quantity"
// //                 value="${item.orderedQuantity}"
// //                 min="0"
// //                 step="${item.packSize}"
// //                 data-product-id="${item.productId}"
// //             >
// //         </td>

// //         <td>
// //             <input
// //                 type="number"
// //                 class="ai-unit-cost"
// //                 value="${item.unitCost}"
// //                 min="0"
// //                 step="0.01"
// //                 data-product-id="${item.productId}"
// //             >
// //         </td>

// //         <td class="ai-line-total">
// //             ₱0.00
// //         </td>

// //         <td>
// //             <select
// //                 class="ai-supplier-select"
// //                 data-product-id="${item.productId}"
// //             >
// //                 ${buildSupplierOptions(item.supplierId)}
// //             </select>
// //         </td>
// //     `;


// //     updateAIProductTotal(row, item);


// //     /* Quantity */

// //     const quantityInput =
// //         row.querySelector(
// //             ".ai-order-quantity"
// //         );

// //     quantityInput.addEventListener(
// //         "input",
// //         function () {

// //             const productId =
// //                 Number(this.dataset.productId);

// //             const currentItem =
// //                 aiRecommendationItems.find(
// //                     function (x) {
// //                         return x.productId === productId;
// //                     }
// //                 );

// //             if (!currentItem) {
// //                 return;
// //             }

// //             currentItem.orderedQuantity =
// //                 Number(this.value) || 0;

// //             updateAIProductTotal(
// //                 row,
// //                 currentItem
// //             );

// //             updateAIGrandTotal();
// //         }
// //     );


// //     /* Unit cost */

// //     const costInput =
// //         row.querySelector(
// //             ".ai-unit-cost"
// //         );

// //     costInput.addEventListener(
// //         "input",
// //         function () {

// //             const productId =
// //                 Number(this.dataset.productId);

// //             const currentItem =
// //                 aiRecommendationItems.find(
// //                     function (x) {
// //                         return x.productId === productId;
// //                     }
// //                 );

// //             if (!currentItem) {
// //                 return;
// //             }

// //             currentItem.unitCost =
// //                 Number(this.value) || 0;

// //             updateAIProductTotal(
// //                 row,
// //                 currentItem
// //             );

// //             updateAIGrandTotal();
// //         }
// //     );


// //     /* Supplier */

// //     const supplierSelect =
// //         row.querySelector(
// //             ".ai-supplier-select"
// //         );

// //     supplierSelect.addEventListener(
// //         "change",
// //         function () {

// //             const productId =
// //                 Number(this.dataset.productId);

// //             const newSupplierId =
// //                 Number(this.value);

// //             changeAIItemSupplier(
// //                 productId,
// //                 newSupplierId
// //             );
// //         }
// //     );


// //     return row;
// // }


// // /* =========================================================
// //    SUPPLIERS
// //    ========================================================= */

// // function buildSupplierOptions(selectedSupplierId) {

// //     const suppliers = [
// //         {
// //             id: 1,
// //             name: "James"
// //         },
// //         {
// //             id: 2,
// //             name: "Clark"
// //         },
// //         {
// //             id: 6,
// //             name: "ANTONIO"
// //         }
// //     ];


// //     return suppliers.map(
// //         function (supplier) {

// //             const selected =
// //                 Number(supplier.id) ===
// //                 Number(selectedSupplierId)
// //                     ? "selected"
// //                     : "";


// //             return `
// //                 <option
// //                     value="${supplier.id}"
// //                     ${selected}
// //                 >
// //                     ${supplier.name}
// //                 </option>
// //             `;

// //         }
// //     ).join("");
// // }


// // /* =========================================================
// //    CHANGE SUPPLIER
// //    ========================================================= */

// // function changeAIItemSupplier(
// //     productId,
// //     newSupplierId
// // ) {

// //     const item =
// //         aiRecommendationItems.find(
// //             function (x) {
// //                 return x.productId === productId;
// //             }
// //         );

// //     if (!item) {
// //         return;
// //     }


// //     const supplierNames = {
// //         1: "James",
// //         2: "Clark",
// //         6: "ANTONIO"
// //     };


// //     item.supplierId =
// //         newSupplierId;

// //     item.supplierName =
// //         supplierNames[newSupplierId] ||
// //         "Unknown Supplier";


// //     renderAIRecommendation();
// // }


// // /* =========================================================
// //    TOTALS
// //    ========================================================= */

// // function updateAIProductTotal(row, item) {

// //     const total =
// //         Number(item.orderedQuantity || 0) *
// //         Number(item.unitCost || 0);


// //     const totalCell =
// //         row.querySelector(
// //             ".ai-line-total"
// //         );


// //     totalCell.textContent =
// //         "₱" +
// //         total.toLocaleString(
// //             "en-PH",
// //             {
// //                 minimumFractionDigits: 2,
// //                 maximumFractionDigits: 2
// //             }
// //         );
// // }


// // function updateAIGrandTotal() {

// //     let grandTotal = 0;


// //     aiRecommendationItems.forEach(
// //         function (item) {

// //             grandTotal +=
// //                 Number(item.orderedQuantity || 0) *
// //                 Number(item.unitCost || 0);

// //         }
// //     );


// //     const totalElement =
// //         document.getElementById(
// //             "aiOrderGrandTotal"
// //         );


// //     if (!totalElement) {
// //         return;
// //     }


// //     totalElement.textContent =
// //         "₱" +
// //         grandTotal.toLocaleString(
// //             "en-PH",
// //             {
// //                 minimumFractionDigits: 2,
// //                 maximumFractionDigits: 2
// //             }
// //         );
// // }


// // /* =========================================================
// //    BUTTONS
// //    ========================================================= */

// // document.addEventListener(
// //     "click",
// //     function (event) {

// //         const closeButton =
// //             event.target.closest(
// //                 "#aiOrderCloseBtn"
// //             );

// //         if (closeButton) {

// //             event.preventDefault();

// //             closeAIRecommendationModal();

// //             return;
// //         }


// //         const cancelButton =
// //             event.target.closest(
// //                 "#aiOrderCancelBtn"
// //             );

// //         if (cancelButton) {

// //             event.preventDefault();

// //             closeAIRecommendationModal();

// //             return;
// //         }


// //         const confirmButton =
// //             event.target.closest(
// //                 "#aiOrderConfirmBtn"
// //             );

// //         if (confirmButton) {

// //             event.preventDefault();

// //             console.log(
// //                 "FINAL AI ORDER:",
// //                 aiRecommendationItems
// //             );

// //             alert(
// //                 "AI recommendation is working. Database saving comes next."
// //             );

// //             return;
// //         }

// //     }
// // );