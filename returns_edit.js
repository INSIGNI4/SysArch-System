
document.addEventListener("DOMContentLoaded", function () {
    const customerEditButton =
        document.getElementById("edit-cus-return-btn");

    const supplierEditButton =
        document.getElementById("edit-sup-return-btn");

    const customerTab =
        document.getElementById("customer-returns-btn");

    const supplierTab =
        document.getElementById("supplier-returns-btn");

    const customerContainer =
        document.getElementById("customer-returns-table-container");

    const supplierContainer =
        document.getElementById("supplier-returns-table-container");

    const customerModal =
        document.getElementById("editCustomerReturnModal");

    const supplierModal =
        document.getElementById("editSupplierReturnModal");

    // --------------------------------------------------
    // FETCH DROPDOWN DATA
    // --------------------------------------------------

    async function fetchJSON(url) {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`Request failed: ${url} (${response.status})`);
        }

        const data = await response.json();

        if (!Array.isArray(data)) {
            throw new Error(`Expected a JSON array from ${url}`);
        }

        return data;
    }

    function populateSelect(select, items, valueKey, labelFunction, placeholder) {
        if (!select) return;

        select.innerHTML = "";

        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = placeholder;
        defaultOption.disabled = true;
        defaultOption.selected = true;
        select.add(defaultOption);

        if (items.length === 0) {
            defaultOption.textContent = "No records available";
            return;
        }

        items.forEach(function (item) {
            const option = document.createElement("option");

            option.value = String(item[valueKey]);
            option.textContent = labelFunction(item);

            select.add(option);
        });
    }

    const customerProductSelect =
        document.getElementById("edit_customer_product");

    const supplierProductSelect =
        document.getElementById("edit_supplier_product");

    const supplierSelect =
        document.getElementById("edit_supplier_id");

    // Load products once for both Edit modals.
    const productsLoaded = fetchJSON("get/get_return_products.php")
        .then(function (products) {
            const label = product =>
                `${product.Product_ID} - ${product.ProductName}`;

            populateSelect(
                customerProductSelect,
                products,
                "Product_ID",
                label,
                "Select Product"
            );

            populateSelect(
                supplierProductSelect,
                products,
                "Product_ID",
                label,
                "Select Product"
            );

            return products;
        })
        .catch(function (error) {
            console.error("Failed to load return products:", error);

            [customerProductSelect, supplierProductSelect].forEach(select => {
                if (select) {
                    select.innerHTML =
                        '<option value="">Failed to load products</option>';
                }
            });

            throw error;
        });

    // Load suppliers once for the Supplier Return Edit modal.
    const suppliersLoaded = fetchJSON("get/get_return_suppliers.php")
        .then(function (suppliers) {
            populateSelect(
                supplierSelect,
                suppliers,
                "Supplier_ID",
                supplier =>
                    `${supplier.Supplier_ID} - ${supplier.CompanyName}` +
                    (supplier.ContactPerson
                        ? ` - ${supplier.ContactPerson}`
                        : ""),
                "Select Supplier"
            );

            return suppliers;
        })
        .catch(function (error) {
            console.error("Failed to load return suppliers:", error);

            if (supplierSelect) {
                supplierSelect.innerHTML =
                    '<option value="">Failed to load suppliers</option>';
            }

            throw error;
        });

    // Avoid unhandled rejection warnings if an endpoint fails before
    // the user opens a modal. The original promises still reject when awaited.
    productsLoaded.catch(() => {});
    suppliersLoaded.catch(() => {});

    // --------------------------------------------------
    // FIELD VALUES
    // --------------------------------------------------

    function setFieldValue(id, value) {
        const field = document.getElementById(id);

        if (!field) {
            console.error("Returns Edit: element not found:", id);
            return;
        }

        let safeValue = value == null ? "" : String(value).trim();

        // HTML date inputs require YYYY-MM-DD.
        if (field.type === "date" && safeValue !== "") {
            safeValue = safeValue.substring(0, 10);
        }

        // Preserve existing values that are missing from a dropdown.
        if (
            field.tagName === "SELECT" &&
            safeValue !== "" &&
            !Array.from(field.options).some(
                option => option.value === safeValue
            )
        ) {
            const option = document.createElement("option");
            option.value = safeValue;
            option.textContent = safeValue;
            field.add(option);
        }

        field.value = safeValue;

        // Debugging: check what value is assigned to the date input.
        if (field.type === "date") {
            console.log("Date field:", id, {
                originalValue: value,
                assignedValue: safeValue,
                displayedValue: field.value
            });
        }
    }
    // --------------------------------------------------
    // ACTIVE RETURNS TABLE AND TOOLBAR
    // --------------------------------------------------

    function getActiveReturnType() {
        if (customerTab && customerTab.classList.contains("active")) {
            return "customer";
        }

        if (supplierTab && supplierTab.classList.contains("active")) {
            return "supplier";
        }

        if (
            customerContainer &&
            getComputedStyle(customerContainer).display !== "none"
        ) {
            return "customer";
        }

        return "supplier";
    }

    function setActionColumn(type, show) {
        const container =
            type === "customer" ? customerContainer : supplierContainer;

        if (!container) return;

        const selectors =
            type === "customer"
                ? ".customer-return-action-header, .customer-return-action-cell"
                : ".supplier-return-action-header, .supplier-return-action-cell";

        container.querySelectorAll(selectors).forEach(function (element) {
            element.style.display = show ? "" : "none";
        });

        const toolbarButton =
            type === "customer" ? customerEditButton : supplierEditButton;

        if (toolbarButton) {
            toolbarButton.dataset.editing = show ? "true" : "false";
        }
    }

    function updateToolbar() {
        const activeType = getActiveReturnType();

        if (customerEditButton) {
            customerEditButton.style.display =
                activeType === "customer" ? "inline-block" : "none";
        }

        if (supplierEditButton) {
            supplierEditButton.style.display =
                activeType === "supplier" ? "inline-block" : "none";
        }

        setActionColumn("customer", false);
        setActionColumn("supplier", false);
    }

    if (customerEditButton) {
        customerEditButton.addEventListener("click", function () {
            if (getActiveReturnType() !== "customer") return;

            const currentlyEditing = this.dataset.editing === "true";
            setActionColumn("customer", !currentlyEditing);
        });
    }

    if (supplierEditButton) {
        supplierEditButton.addEventListener("click", function () {
            if (getActiveReturnType() !== "supplier") return;

            const currentlyEditing = this.dataset.editing === "true";
            setActionColumn("supplier", !currentlyEditing);
        });
    }

    // Your main JavaScript remains responsible for changing tabs.
    [customerTab, supplierTab].forEach(function (tab) {
        if (tab) {
            tab.addEventListener("click", function () {
                setTimeout(updateToolbar, 0);
            });
        }
    });

    // --------------------------------------------------
    // OPEN CUSTOMER RETURN EDIT MODAL
    // --------------------------------------------------

    document.addEventListener("click", async function (event) {
        const button = event.target.closest(".select-customer-return-btn");

        if (!button) return;

        try {
            await productsLoaded;

            setFieldValue("edit_CReturn_ID", button.dataset.id);
            setFieldValue("edit_customer_reference", button.dataset.reference);
            setFieldValue("edit_customer_product", button.dataset.productid);
            setFieldValue("edit_customer_quantity", button.dataset.quantity);
            setFieldValue("edit_customer_date", button.dataset.date);
            setFieldValue("edit_customer_reason", button.dataset.reason);

            if (customerModal) {
                customerModal.style.display = "block";
            }
        } catch (error) {
            console.error("Unable to open Customer Return Edit:", error);
            alert("Unable to load products. Please check the product endpoint.");
        }
    });

    // --------------------------------------------------
    // OPEN SUPPLIER RETURN EDIT MODAL
    // --------------------------------------------------

    document.addEventListener("click", async function (event) {
        const button = event.target.closest(".select-supplier-return-btn");

        if (!button) return;

        try {
            await Promise.all([productsLoaded, suppliersLoaded]);

            setFieldValue("edit_SReturns_ID", button.dataset.id);
            setFieldValue("edit_supplier_id", button.dataset.supplierid);
            setFieldValue("edit_supplier_product", button.dataset.productid);
            setFieldValue("edit_supplier_quantity", button.dataset.quantity);
            setFieldValue("edit_supplier_date", button.dataset.date);
            setFieldValue("edit_supplier_status", button.dataset.status);
            setFieldValue("edit_supplier_reason", button.dataset.reason);

            if (supplierModal) {
                supplierModal.style.display = "block";
            }
        } catch (error) {
            console.error("Unable to open Supplier Return Edit:", error);
            alert("Unable to load suppliers or products. Please check the endpoints.");
        }
    });

    // --------------------------------------------------
    // CLOSE MODALS
    // --------------------------------------------------

    const customerCancel =
        document.getElementById("cancel-edit-customer-return");

    const supplierCancel =
        document.getElementById("cancel-edit-supplier-return");

    if (customerCancel && customerModal) {
        customerCancel.addEventListener("click", function () {
            customerModal.style.display = "none";
        });
    }

    if (supplierCancel && supplierModal) {
        supplierCancel.addEventListener("click", function () {
            supplierModal.style.display = "none";
        });
    }

    [customerModal, supplierModal].forEach(function (modal) {
        if (!modal) return;

        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });

    updateToolbar();
});