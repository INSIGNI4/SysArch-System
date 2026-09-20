// ============================================================
// AI RECOMMENDATION
// ============================================================

let aiRecommendationItems = [];
let aiSuppliers = [];


// ============================================================
// OPEN AI RECOMMENDATION MODAL
// ============================================================

async function openAIRecommendationModal() {

    const modal = document.getElementById('aiRecommendationModal');
    const supplierContainer = document.getElementById('aiSupplierSections');

    if (!modal || !supplierContainer) {
        console.error('AI Recommendation modal elements not found.');
        return;
    }

    // Show modal
    modal.style.display = 'flex';

    // Show loading message
    supplierContainer.innerHTML = `
        <div style="
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 16px;
        ">
            Loading AI recommendations...
        </div>
    `;

    try {

        // ====================================================
        // GET REAL AI RECOMMENDATIONS FROM PHP
        // ====================================================

        // ====================================================
        // GET AI RECOMMENDATIONS + SUPPLIERS
        // ====================================================

        const [recommendationResponse, supplierResponse] =
            await Promise.all([
                fetch('get_ai_recommendations.php'),
                fetch('get_supplier.php')
            ]);


        if (!recommendationResponse.ok) {
            throw new Error(
                `AI recommendation HTTP error: ${recommendationResponse.status}`
            );
        }


        if (!supplierResponse.ok) {
            throw new Error(
                `Supplier HTTP error: ${supplierResponse.status}`
            );
        }


        const result =
            await recommendationResponse.json();

        const supplierResult =
            await supplierResponse.json();


        console.log(
            'AI Recommendation Response:',
            result
        );

        console.log(
            'Supplier Response:',
            supplierResult
        );


        // AI recommendation response
        if (!result.success) {

            throw new Error(
                result.error ||
                'Failed to load AI recommendations.'
            );

        }


        // Your existing get_supplier.php
        // returns an array directly.
        aiSuppliers =
            supplierResult || [];


        aiRecommendationItems =
            result.data || [];

        // if (!response.ok) {
        //     throw new Error(
        //         `HTTP error: ${response.status}`
        //     );
        // }

        // const result = await response.json();

        // console.log('AI Recommendation Response:', result);

        // if (!result.success) {
        //     throw new Error(
        //         result.error || 'Failed to load AI recommendations.'
        //     );
        // }

        // // Save the real recommendations
        // aiRecommendationItems = result.data || [];

        // ====================================================
        // NO RECOMMENDATIONS
        // ====================================================

        if (aiRecommendationItems.length === 0) {

            supplierContainer.innerHTML = `
                <div style="
                    padding: 30px;
                    text-align: center;
                    color: #666;
                ">
                    No products currently need to be reordered.
                </div>
            `;

            updateAIGrandTotal();
            return;
        }

        // ====================================================
        // DISPLAY RECOMMENDATIONS
        // ====================================================

        renderAIRecommendations();

    } catch (error) {

        console.error(
            'Error loading AI recommendations:',
            error
        );

        supplierContainer.innerHTML = `
            <div style="
                padding: 30px;
                text-align: center;
                color: #dc3545;
            ">
                <strong>Failed to load AI recommendations.</strong>
                <br><br>
                ${escapeHTML(error.message)}
            </div>
        `;

    }
}


// ============================================================
// RENDER AI RECOMMENDATIONS
// ============================================================

function renderAIRecommendations() {

    const container =
        document.getElementById('aiSupplierSections');

    if (!container) {
        return;
    }

    container.innerHTML = '';

    // ========================================================
    // GROUP PRODUCTS BY SUPPLIER
    // ========================================================

    const supplierGroups = {};

    aiRecommendationItems.forEach((item, index) => {

        const supplierId =
            item.supplierId !== null
                ? item.supplierId
                : 'none';

        if (!supplierGroups[supplierId]) {

            supplierGroups[supplierId] = {
                supplierId: item.supplierId,
                supplierName: item.supplierName || 'No Supplier',
                supplierLocation: item.supplierLocation || '',
                items: []
            };

        }

        // Keep track of original item index
        item._index = index;

        supplierGroups[supplierId].items.push(item);

    });


    // ========================================================
    // CREATE SUPPLIER SECTIONS
    // ========================================================

    Object.values(supplierGroups).forEach(group => {

        const section =
            document.createElement('div');

        section.className =
            'ai-supplier-section';


        // ----------------------------------------------------
        // SUPPLIER HEADER
        // ----------------------------------------------------

        const supplierHeader =
            document.createElement('div');

        supplierHeader.className =
            'ai-supplier-header';


        const supplierTitle =
            document.createElement('h3');

        supplierTitle.textContent =
            group.supplierName;


        const supplierLocation =
            document.createElement('span');

        supplierLocation.className =
            'ai-supplier-location';

        supplierLocation.textContent =
            group.supplierLocation
                ? `— ${group.supplierLocation}`
                : '';


        supplierHeader.appendChild(
            supplierTitle
        );

        supplierHeader.appendChild(
            supplierLocation
        );


        section.appendChild(
            supplierHeader
        );


        // ----------------------------------------------------
        // TABLE
        // ----------------------------------------------------

        const table =
            document.createElement('table');

        table.className =
            'ai-order-table';


        table.innerHTML = `
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
        `;


        const tbody =
            document.createElement('tbody');


        // ----------------------------------------------------
        // PRODUCT ROWS
        // ----------------------------------------------------

        group.items.forEach(item => {

            const row =
                document.createElement('tr');

            const index =
                item._index;


            // Product
            const productCell =
                document.createElement('td');

            productCell.textContent =
                item.productName;


            // Current Stock
            const stockCell =
                document.createElement('td');

            stockCell.textContent =
                formatNumber(item.currentStock);


            // Predicted Demand
            const demandCell =
                document.createElement('td');

            demandCell.textContent =
                formatNumber(item.predictedDemand);


            // AI Recommendation
            const recommendationCell =
                document.createElement('td');

            recommendationCell.textContent =
                formatNumber(item.recommendedQuantity);


            // Order Quantity
            const quantityCell =
                document.createElement('td');

            const quantityInput =
                document.createElement('input');

            quantityInput.type =
                'number';

            quantityInput.min =
                '0';

            quantityInput.step =
                item.packSize || 1;

            quantityInput.value =
                item.orderedQuantity;

            quantityInput.className =
                'ai-order-quantity';

            quantityInput.dataset.index =
                index;


            quantityInput.addEventListener(
                'input',
                function () {

                    const newQuantity =
                        parseInt(this.value) || 0;

                    aiRecommendationItems[index]
                        .orderedQuantity =
                        newQuantity;

                    updateRowTotal(
                        index,
                        row
                    );

                    updateAIGrandTotal();

                }
            );


            quantityCell.appendChild(
                quantityInput
            );


            // ------------------------------------------------
            // SUPPLIER DROPDOWN
            // ------------------------------------------------

            // ------------------------------------------------
            // SUPPLIER DROPDOWN
            // ------------------------------------------------

            const supplierCell =
                document.createElement('td');

            const supplierSelect =
                document.createElement('select');

            supplierSelect.className =
                'ai-order-supplier';

            supplierSelect.dataset.index =
                index;


            // Add ALL suppliers from your existing
            // get_supplier.php
            aiSuppliers.forEach(supplier => {

                const option =
                    document.createElement('option');


                option.value =
                    supplier.Supplier_ID;


                option.text =
                    `${supplier.SupplierName} — ${supplier.Location}`;


                // Select the product's CURRENT supplier
                // as the default
                if (
                    Number(supplier.Supplier_ID) ===
                    Number(item.supplierId)
                ) {

                    option.selected = true;

                }


                supplierSelect.appendChild(
                    option
                );

            });


            // When manager changes supplier
                        
            supplierSelect.addEventListener(
                'change',
                function () {

                    const selectedSupplier =
                        aiSuppliers.find(
                            supplier =>
                                Number(supplier.Supplier_ID) ===
                                Number(this.value)
                        );


                    if (!selectedSupplier) {
                        return;
                    }


                    // Update the item's selected supplier
                    aiRecommendationItems[index]
                        .supplierId =
                        Number(selectedSupplier.Supplier_ID);

                    aiRecommendationItems[index]
                        .supplierName =
                        selectedSupplier.SupplierName;

                    aiRecommendationItems[index]
                        .supplierLocation =
                        selectedSupplier.Location;


                    // Re-render the entire supplier grouping
                    renderAIRecommendations();

                }
            );

            // supplierSelect.addEventListener(
            //     'change',
            //     function () {

            //         const selectedSupplier =
            //             aiSuppliers.find(
            //                 supplier =>
            //                     Number(supplier.Supplier_ID) ===
            //                     Number(this.value)
            //             );


            //         if (selectedSupplier) {

            //             aiRecommendationItems[index]
            //                 .supplierId =
            //                 Number(selectedSupplier.Supplier_ID);

            //             aiRecommendationItems[index]
            //                 .supplierName =
            //                 selectedSupplier.SupplierName;

            //             aiRecommendationItems[index]
            //                 .supplierLocation =
            //                 selectedSupplier.Location;

            //         }

            //     }
            // );


            supplierCell.appendChild(
                supplierSelect
            );

            
            // const supplierCell =
            //     document.createElement('td');

            // const supplierSelect =
            //     document.createElement('select');

            // supplierSelect.className =
            //     'ai-order-supplier';

            // supplierSelect.dataset.index =
            //     index;


            // // For now, the dropdown contains
            // // the current supplier.
            // const option =
            //     document.createElement('option');

            // option.value =
            //     item.supplierId ?? '';

            // option.textContent =
            //     item.supplierLocation
            //         ? `${item.supplierName} — ${item.supplierLocation}`
            //         : item.supplierName;

            // supplierSelect.appendChild(
            //     option
            // );


            // supplierSelect.addEventListener(
            //     'change',
            //     function () {

            //         aiRecommendationItems[index]
            //             .supplierId =
            //             this.value;

            //     }
            // );


            // supplierCell.appendChild(
            //     supplierSelect
            // );


            // Unit Cost
            const costCell =
                document.createElement('td');

            costCell.textContent =
                formatCurrency(item.unitCost);


            // Total
            const totalCell =
                document.createElement('td');

            totalCell.className =
                'ai-row-total';

            totalCell.textContent =
                formatCurrency(
                    item.orderedQuantity *
                    item.unitCost
                );


            // ------------------------------------------------
            // ADD CELLS
            // ------------------------------------------------

            row.appendChild(
                productCell
            );

            row.appendChild(
                stockCell
            );

            row.appendChild(
                demandCell
            );

            row.appendChild(
                recommendationCell
            );

            row.appendChild(
                quantityCell
            );

            row.appendChild(
                supplierCell
            );

            row.appendChild(
                costCell
            );

            row.appendChild(
                totalCell
            );


            tbody.appendChild(
                row
            );

        });


        table.appendChild(
            tbody
        );

        section.appendChild(
            table
        );

        container.appendChild(
            section
        );

    });


    // Calculate initial total
    updateAIGrandTotal();
}


// ============================================================
// UPDATE ROW TOTAL
// ============================================================

function updateRowTotal(index, row) {

    const item =
        aiRecommendationItems[index];

    const total =
        item.orderedQuantity *
        item.unitCost;


    const totalCell =
        row.querySelector('.ai-row-total');

    if (totalCell) {

        totalCell.textContent =
            formatCurrency(total);

    }
}


// ============================================================
// UPDATE GRAND TOTAL
// ============================================================

function updateAIGrandTotal() {

    const totalElement =
        document.getElementById(
            'aiOrderGrandTotal'
        );

    if (!totalElement) {
        return;
    }


    let grandTotal = 0;


    aiRecommendationItems.forEach(item => {

        const quantity =
            Number(item.orderedQuantity) || 0;

        const unitCost =
            Number(item.unitCost) || 0;

        grandTotal +=
            quantity * unitCost;

    });


    totalElement.textContent =
        formatCurrency(grandTotal);
}


// ============================================================
// CLOSE MODAL
// ============================================================

function closeAIRecommendationModal() {

    const modal =
        document.getElementById(
            'aiRecommendationModal'
        );

    if (modal) {

        modal.style.display =
            'none';

    }
}


// ============================================================
// FORMAT NUMBER
// ============================================================

function formatNumber(value) {

    const number =
        Number(value) || 0;

    return number.toLocaleString(
        'en-US',
        {
            maximumFractionDigits: 2
        }
    );
}


// ============================================================
// FORMAT CURRENCY
// ============================================================

function formatCurrency(value) {

    const number =
        Number(value) || 0;

    return '₱' +
        number.toLocaleString(
            'en-PH',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
}


// ============================================================
// BASIC HTML ESCAPE
// ============================================================

function escapeHTML(value) {

    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}


// ============================================================
// BUTTON EVENTS
// ============================================================

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const closeButton =
            document.getElementById(
                'aiOrderCloseBtn'
            );

        const cancelButton =
            document.getElementById(
                'aiOrderCancelBtn'
            );


        if (closeButton) {

            closeButton.addEventListener(
                'click',
                closeAIRecommendationModal
            );

        }


        if (cancelButton) {

            cancelButton.addEventListener(
                'click',
                closeAIRecommendationModal
            );

        }

    }
);




























































// /* =========================================================
//    AI RECOMMENDATION
//    Separate script - does not modify homepagescript.js
//    ========================================================= */

// let aiRecommendationItems = [
//     {
//         productId: 24,
//         productName: "Toblerone",
//         supplierId: 1,
//         supplierName: "James",
//         currentStock: 47,
//         predictedDemand: 311,
//         netDemand: 264,
//         packSize: 1,
//         recommendedQuantity: 264,
//         orderedQuantity: 264,
//         unitCost: 0
//     },

//     {
//         productId: 58,
//         productName: "MILO SULIT PACK",
//         supplierId: 1,
//         supplierName: "James",
//         currentStock: 7,
//         predictedDemand: 262,
//         netDemand: 255,
//         packSize: 1,
//         recommendedQuantity: 255,
//         orderedQuantity: 255,
//         unitCost: 200
//     },

//     {
//         productId: 21,
//         productName: "nmax 155 left or right motorcycle Brake Maste",
//         supplierId: 1,
//         supplierName: "James",
//         currentStock: -1,
//         predictedDemand: 240,
//         netDemand: 241,
//         packSize: 1,
//         recommendedQuantity: 241,
//         orderedQuantity: 241,
//         unitCost: 0
//     },

//     {
//         productId: 23,
//         productName: "Russi",
//         supplierId: 1,
//         supplierName: "James",
//         currentStock: 0,
//         predictedDemand: 73,
//         netDemand: 73,
//         packSize: 1,
//         recommendedQuantity: 73,
//         orderedQuantity: 73,
//         unitCost: 0
//     },

//     {
//         productId: 13,
//         productName: "LEO RAPTOR TIRE 4",
//         supplierId: 2,
//         supplierName: "Clark",
//         currentStock: 3,
//         predictedDemand: 245,
//         netDemand: 242,
//         packSize: 1,
//         recommendedQuantity: 242,
//         orderedQuantity: 242,
//         unitCost: 0
//     },

//     {
//         productId: 22,
//         productName: "Russi Exhaust 302",
//         supplierId: 2,
//         supplierName: "Clark",
//         currentStock: 118,
//         predictedDemand: 303,
//         netDemand: 185,
//         packSize: 1,
//         recommendedQuantity: 185,
//         orderedQuantity: 185,
//         unitCost: 0
//     },

//     {
//         productId: 57,
//         productName: "Milo",
//         supplierId: 6,
//         supplierName: "ANTONIO",
//         currentStock: 6,
//         predictedDemand: 202,
//         netDemand: 196,
//         packSize: 12,
//         recommendedQuantity: 204,
//         orderedQuantity: 204,
//         unitCost: 0
//     }
// ];


// /* =========================================================
//    OPEN / CLOSE
//    ========================================================= */

// function openAIRecommendationModal() {

//     const modal = document.getElementById(
//         "aiRecommendationModal"
//     );

//     if (!modal) {
//         console.error(
//             "AI Recommendation modal not found."
//         );
//         return;
//     }

//     modal.style.display = "block";

//     renderAIRecommendation();
// }


// function closeAIRecommendationModal() {

//     const modal = document.getElementById(
//         "aiRecommendationModal"
//     );

//     if (!modal) {
//         return;
//     }

//     modal.style.display = "none";
// }


// /* =========================================================
//    RENDER AI RECOMMENDATION
//    ========================================================= */

// function renderAIRecommendation() {

//     const container = document.getElementById(
//         "aiSupplierSections"
//     );

//     if (!container) {
//         console.error(
//             "AI supplier sections container not found."
//         );
//         return;
//     }

//     container.innerHTML = "";

//     const supplierGroups = {};


//     /* Group products by supplier */

//     aiRecommendationItems.forEach(function (item) {

//         const supplierId = item.supplierId;

//         if (!supplierGroups[supplierId]) {

//             supplierGroups[supplierId] = {
//                 supplierId: supplierId,
//                 supplierName: item.supplierName,
//                 items: []
//             };

//         }

//         supplierGroups[supplierId].items.push(item);

//     });


//     /* Create supplier sections */

//     Object.values(supplierGroups).forEach(
//         function (supplierGroup) {

//             const section =
//                 createSupplierSection(
//                     supplierGroup
//                 );

//             container.appendChild(section);

//         }
//     );


//     updateAIGrandTotal();
// }


// /* =========================================================
//    SUPPLIER SECTION
//    ========================================================= */

// function createSupplierSection(supplierGroup) {

//     const section =
//         document.createElement("div");

//     section.className =
//         "ai-supplier-section";


//     const header =
//         document.createElement("div");

//     header.className =
//         "ai-supplier-header";

//     header.textContent =
//         "Supplier " +
//         supplierGroup.supplierId +
//         " — " +
//         supplierGroup.supplierName;

//     section.appendChild(header);


//     const table =
//         document.createElement("table");

//     table.className =
//         "ai-order-table";


//     table.innerHTML = `
//         <thead>
//             <tr>
//                 <th>Product ID / Name</th>
//                 <th>Current Stock</th>
//                 <th>Predicted Demand</th>
//                 <th>Net Demand</th>
//                 <th>Pack Size</th>
//                 <th>AI Recommended</th>
//                 <th>Adjusted Order Quantity</th>
//                 <th>Unit Price</th>
//                 <th>Total Price</th>
//                 <th>Change Supplier?</th>
//             </tr>
//         </thead>

//         <tbody></tbody>
//     `;


//     const tbody =
//         table.querySelector("tbody");


//     supplierGroup.items.forEach(
//         function (item) {

//             const row =
//                 createAIProductRow(item);

//             tbody.appendChild(row);

//         }
//     );


//     section.appendChild(table);

//     return section;
// }


// /* =========================================================
//    PRODUCT ROW
//    ========================================================= */

// function createAIProductRow(item) {

//     const row =
//         document.createElement("tr");

//     row.dataset.productId =
//         item.productId;


//     row.innerHTML = `
//         <td class="ai-product-name">
//             #${item.productId} / ${item.productName}
//         </td>

//         <td class="ai-number-cell">
//             ${item.currentStock}
//         </td>

//         <td class="ai-number-cell">
//             ${item.predictedDemand}
//         </td>

//         <td class="ai-number-cell">
//             ${item.netDemand}
//         </td>

//         <td class="ai-number-cell">
//             ${item.packSize}
//         </td>

//         <td class="ai-number-cell">
//             ${item.recommendedQuantity}
//         </td>

//         <td>
//             <input
//                 type="number"
//                 class="ai-order-quantity"
//                 value="${item.orderedQuantity}"
//                 min="0"
//                 step="${item.packSize}"
//                 data-product-id="${item.productId}"
//             >
//         </td>

//         <td>
//             <input
//                 type="number"
//                 class="ai-unit-cost"
//                 value="${item.unitCost}"
//                 min="0"
//                 step="0.01"
//                 data-product-id="${item.productId}"
//             >
//         </td>

//         <td class="ai-line-total">
//             ₱0.00
//         </td>

//         <td>
//             <select
//                 class="ai-supplier-select"
//                 data-product-id="${item.productId}"
//             >
//                 ${buildSupplierOptions(item.supplierId)}
//             </select>
//         </td>
//     `;


//     updateAIProductTotal(row, item);


//     /* Quantity */

//     const quantityInput =
//         row.querySelector(
//             ".ai-order-quantity"
//         );

//     quantityInput.addEventListener(
//         "input",
//         function () {

//             const productId =
//                 Number(this.dataset.productId);

//             const currentItem =
//                 aiRecommendationItems.find(
//                     function (x) {
//                         return x.productId === productId;
//                     }
//                 );

//             if (!currentItem) {
//                 return;
//             }

//             currentItem.orderedQuantity =
//                 Number(this.value) || 0;

//             updateAIProductTotal(
//                 row,
//                 currentItem
//             );

//             updateAIGrandTotal();
//         }
//     );


//     /* Unit cost */

//     const costInput =
//         row.querySelector(
//             ".ai-unit-cost"
//         );

//     costInput.addEventListener(
//         "input",
//         function () {

//             const productId =
//                 Number(this.dataset.productId);

//             const currentItem =
//                 aiRecommendationItems.find(
//                     function (x) {
//                         return x.productId === productId;
//                     }
//                 );

//             if (!currentItem) {
//                 return;
//             }

//             currentItem.unitCost =
//                 Number(this.value) || 0;

//             updateAIProductTotal(
//                 row,
//                 currentItem
//             );

//             updateAIGrandTotal();
//         }
//     );


//     /* Supplier */

//     const supplierSelect =
//         row.querySelector(
//             ".ai-supplier-select"
//         );

//     supplierSelect.addEventListener(
//         "change",
//         function () {

//             const productId =
//                 Number(this.dataset.productId);

//             const newSupplierId =
//                 Number(this.value);

//             changeAIItemSupplier(
//                 productId,
//                 newSupplierId
//             );
//         }
//     );


//     return row;
// }


// /* =========================================================
//    SUPPLIERS
//    ========================================================= */

// function buildSupplierOptions(selectedSupplierId) {

//     const suppliers = [
//         {
//             id: 1,
//             name: "James"
//         },
//         {
//             id: 2,
//             name: "Clark"
//         },
//         {
//             id: 6,
//             name: "ANTONIO"
//         }
//     ];


//     return suppliers.map(
//         function (supplier) {

//             const selected =
//                 Number(supplier.id) ===
//                 Number(selectedSupplierId)
//                     ? "selected"
//                     : "";


//             return `
//                 <option
//                     value="${supplier.id}"
//                     ${selected}
//                 >
//                     ${supplier.name}
//                 </option>
//             `;

//         }
//     ).join("");
// }


// /* =========================================================
//    CHANGE SUPPLIER
//    ========================================================= */

// function changeAIItemSupplier(
//     productId,
//     newSupplierId
// ) {

//     const item =
//         aiRecommendationItems.find(
//             function (x) {
//                 return x.productId === productId;
//             }
//         );

//     if (!item) {
//         return;
//     }


//     const supplierNames = {
//         1: "James",
//         2: "Clark",
//         6: "ANTONIO"
//     };


//     item.supplierId =
//         newSupplierId;

//     item.supplierName =
//         supplierNames[newSupplierId] ||
//         "Unknown Supplier";


//     renderAIRecommendation();
// }


// /* =========================================================
//    TOTALS
//    ========================================================= */

// function updateAIProductTotal(row, item) {

//     const total =
//         Number(item.orderedQuantity || 0) *
//         Number(item.unitCost || 0);


//     const totalCell =
//         row.querySelector(
//             ".ai-line-total"
//         );


//     totalCell.textContent =
//         "₱" +
//         total.toLocaleString(
//             "en-PH",
//             {
//                 minimumFractionDigits: 2,
//                 maximumFractionDigits: 2
//             }
//         );
// }


// function updateAIGrandTotal() {

//     let grandTotal = 0;


//     aiRecommendationItems.forEach(
//         function (item) {

//             grandTotal +=
//                 Number(item.orderedQuantity || 0) *
//                 Number(item.unitCost || 0);

//         }
//     );


//     const totalElement =
//         document.getElementById(
//             "aiOrderGrandTotal"
//         );


//     if (!totalElement) {
//         return;
//     }


//     totalElement.textContent =
//         "₱" +
//         grandTotal.toLocaleString(
//             "en-PH",
//             {
//                 minimumFractionDigits: 2,
//                 maximumFractionDigits: 2
//             }
//         );
// }


// /* =========================================================
//    BUTTONS
//    ========================================================= */

// document.addEventListener(
//     "click",
//     function (event) {

//         const closeButton =
//             event.target.closest(
//                 "#aiOrderCloseBtn"
//             );

//         if (closeButton) {

//             event.preventDefault();

//             closeAIRecommendationModal();

//             return;
//         }


//         const cancelButton =
//             event.target.closest(
//                 "#aiOrderCancelBtn"
//             );

//         if (cancelButton) {

//             event.preventDefault();

//             closeAIRecommendationModal();

//             return;
//         }


//         const confirmButton =
//             event.target.closest(
//                 "#aiOrderConfirmBtn"
//             );

//         if (confirmButton) {

//             event.preventDefault();

//             console.log(
//                 "FINAL AI ORDER:",
//                 aiRecommendationItems
//             );

//             alert(
//                 "AI recommendation is working. Database saving comes next."
//             );

//             return;
//         }

//     }
// );