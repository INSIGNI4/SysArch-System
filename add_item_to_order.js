document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("addItemToOrderModal");
    const form = document.getElementById("itemToOrderForm");
    const cancelButton = document.getElementById("item-order-cancel-btn");
    const addButton = document.getElementById("order-restock-add-btn");
    
    const productSelect = document.getElementById("Item_ProductID");
    const currentStockInput = document.getElementById("item-current-stock");
    const packSizeInput = document.getElementById("item-pack-size");
    const orderedQuantityInput = document.getElementById("item-ordered-quantity");
    const unitCostInput = document.getElementById("item-unit-cost");

    if (!form || !modal || !addButton) return;

    addButton.addEventListener("click", function () {
        if (activeRestockTable === "itemToOrder") {
            modal.style.display = "flex";
            loadLists();
            loadProducts();
            // loadSuppliers();
        }
    });

    cancelButton.addEventListener("click", function () {
        modal.style.display = "none";
        form.reset();
    });

    // async function loadLists() {
    //     const select = document.getElementById("Item_ListToOrder");
    //     select.innerHTML = '<option disabled selected>Loading...</option>';

    //     try {
    //         const response = await fetch("get_list_to_order.php");
    //         const lists = await response.json();

    //         select.innerHTML = "";

    //         if (!lists.length) {
    //             select.innerHTML = '<option disabled>No List to Order available</option>';
    //             return;
    //         }

    //         select.innerHTML = '<option disabled selected>Select List to Order</option>';

    //         lists.forEach(list => {
    //             const option = document.createElement("option");
    //             option.value = list.ListToOrder_ID;
    //             option.textContent =
    //                 `#${list.ListToOrder_ID} - Supplier ${list.Supplier_ID} - ${list.Order_Status}`;
    //             select.appendChild(option);
    //         });
    //     } catch (error) {
    //         console.error("Failed to load lists:", error);
    //         select.innerHTML = '<option disabled>Failed to load lists</option>';
    //     }
    // }

    async function loadLists() {
        const select = document.getElementById("Item_ListToOrder");
        select.innerHTML = '<option disabled selected>Loading...</option>';

        try {
            const response = await fetch("get_list_to_order.php");
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            const lists = result.data;

            select.innerHTML = "";

            if (lists.length === 0) {
                select.innerHTML = '<option disabled>No List to Order available</option>';
                return;
            }

            const defaultOption = document.createElement("option");
            defaultOption.text = "Select List to Order";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            select.add(defaultOption);

            lists.forEach(list => {
                const option = document.createElement("option");

                option.value = list.ListToOrder_ID;
                option.textContent =
                    `#${list.ListToOrder_ID} - Supplier ${list.Supplier_ID} - ${list.Order_Status}`;

                option.dataset.supplierId = list.Supplier_ID;

                select.appendChild(option);
            });
        } catch (error) {
            console.error("Failed to load lists:", error);
            select.innerHTML = '<option disabled>Failed to load lists</option>';
        }
    }    

    async function loadProducts() {
        const select = document.getElementById("Item_ProductID");
        select.innerHTML = '<option disabled selected>Loading...</option>';

        try {
            const response = await fetch("get_item_order_products.php");
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            const products = result.data;

            select.innerHTML = "";

            if (products.length === 0) {
                select.innerHTML = '<option disabled>No products available</option>';
                return;
            }

            const defaultOption = document.createElement("option");
            defaultOption.text = "Select Product";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            select.add(defaultOption);

            products.forEach(product => {
                const option = document.createElement("option");

                option.value = product.Product_ID;
                option.text = `${product.Product_ID} - ${product.ProductName}`;

                option.dataset.stock = product.CurrentStock || 0;
                option.dataset.packSize = product.Pack_Size || 1;
                option.dataset.unitCost = product.SupplierPrice || 0;
                // option.dataset.supplierId = product.Supplier_ID || "";

                select.add(option);
            });
        } catch (error) {
            console.error("Failed to fetch products:", error);
            select.innerHTML = '<option disabled>Failed to load products</option>';
        }
    }

    // async function loadSuppliers() {
    //     const select = document.getElementById("Item_SupplierID");
    //     select.innerHTML = '<option disabled selected>Loading...</option>';

    //     try {
    //         const response = await fetch("get_supplier.php");
    //         const suppliers = await response.json();

    //         select.innerHTML = "";

    //         if (!suppliers.length) {
    //             select.innerHTML = '<option disabled>No suppliers available</option>';
    //             return;
    //         }

    //         select.innerHTML = '<option disabled selected>Select Supplier</option>';

    //         suppliers.forEach(supplier => {
    //             const option = document.createElement("option");
    //             option.value = supplier.Supplier_ID;
    //             option.textContent =
    //                 `${supplier.Supplier_ID} - ${supplier.SupplierName} - ${supplier.Location}`;
    //             select.appendChild(option);
    //         });
    //     } catch (error) {
    //         console.error("Failed to load suppliers:", error);
    //         select.innerHTML = '<option disabled>Failed to load suppliers</option>';
    //     }
    // }





    // document.getElementById("Item_ProductID").addEventListener("change", function () {
    //     const option = this.options[this.selectedIndex];

    //     document.getElementById("item-current-stock").value =
    //         option.dataset.stock || 0;

    //     document.getElementById("item-pack-size").value =
    //         option.dataset.packSize || 1;

    //     document.getElementById("item-unit-cost").value =
    //         option.dataset.unitCost || 0;

    //     // const supplierSelect = document.getElementById("Item_SupplierID");
    //     // const supplierId = option.dataset.supplierId;

    //     // if (supplierId) {
    //     //     supplierSelect.value = supplierId;
    //     // }
    // });

    productSelect.addEventListener("change", function () {

        const option = this.options[this.selectedIndex];

        // Get product information
        const currentStock = parseInt(option.dataset.stock || 0);
        const packSize = parseInt(option.dataset.packSize || 1);
        const unitCost = parseFloat(option.dataset.unitCost || 0);

        // Display current stock
        currentStockInput.value = currentStock;

        // Display pack size
        packSizeInput.value = packSize;

        // Display unit cost
        unitCostInput.value = unitCost;

        // Make Ordered Quantity follow Pack Size
        orderedQuantityInput.min = packSize;
        orderedQuantityInput.step = packSize;

        // Automatically start with one full pack
        orderedQuantityInput.value = packSize;

        // Clear any previous validation error
        orderedQuantityInput.setCustomValidity("");
        orderedQuantityInput.style.borderColor = "";

    });    

    orderedQuantityInput.addEventListener("input", function () {

        const packSize = parseInt(packSizeInput.value || 1);
        const quantity = parseInt(this.value || 0);

        if (quantity < packSize || quantity % packSize !== 0) {

            this.setCustomValidity(
                `Ordered quantity must be a multiple of the pack size (${packSize}).`
            );

            this.style.borderColor = "red";

        } else {

            this.setCustomValidity("");

            this.style.borderColor = "";

        }

    });    

    form.addEventListener("submit", async function (event) {
        event.preventDefault();

        try {
            const response = await fetch("add_item_to_order.php", {
                method: "POST",
                body: new FormData(form)
            });

            const result = await response.json();

            console.log("ADD ITEM RESPONSE:", result);

            if (result.success) {
                alert(result.message);
                modal.style.display = "none";
                form.reset();
                location.reload();
            } else {
                alert("Failed to add Item to Order:\n\n" + result.message);
            }
        } catch (error) {
            console.error("Error adding Item to Order:", error);
            alert("An error occurred while saving the Item to Order.");
        }
    });
});