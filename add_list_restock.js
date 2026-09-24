document.addEventListener("DOMContentLoaded", function () {

    const modal = document.getElementById("addListToOrderModal");

    const addButton = document.getElementById("order-restock-add-btn");

    const cancelButton = document.getElementById("list-order-cancel-btn");

    const supplierSelect =
        document.getElementById("Supplier_IDLISTORDER");

    const toggleNullButton =
        document.getElementById("toggleNullBtnListOrder");

    const toggleNotNullButton =
        document.getElementById("toggleNotNullBtnListOrder");

    const forecastStart =
        document.getElementById("list-forecast-start-date");

    const forecastEnd =
        document.getElementById("list-forecast-end-date");


    // =========================
    // OPEN MODAL
    // =========================

    addButton.addEventListener("click", function () {

        if (activeRestockTable === "listToOrder") {
            modal.style.display = "flex";
        }

    });


    // =========================
    // CLOSE MODAL
    // =========================

    cancelButton.addEventListener("click", function () {

        modal.style.display = "none";

    });


    // =========================
    // FORECAST DATES TOGGLE
    // =========================

    toggleNullButton.addEventListener("click", function () {

        toggleNullButton.style.display = "none";

        toggleNotNullButton.style.display = "inline-block";

        toggleNotNullButton.style.color = "blue";

        forecastStart.disabled = false;

        forecastEnd.disabled = false;

    });


    toggleNotNullButton.addEventListener("click", function () {

        toggleNotNullButton.style.display = "none";

        toggleNullButton.style.display = "inline-block";

        toggleNullButton.style.color = "red";

        forecastStart.disabled = true;

        forecastEnd.disabled = true;

        forecastStart.value = "";
        forecastEnd.value = "";

    });


    // =========================
    // LOAD SUPPLIERS
    // =========================

    async function loadSuppliers() {

        try {

            const response = await fetch("get_supplier.php");

            const suppliers = await response.json();

            supplierSelect.innerHTML = "";


            if (suppliers.length === 0) {

                const option = document.createElement("option");

                option.text = "No supplier available";

                option.disabled = true;

                supplierSelect.add(option);

                return;

            }


            const defaultOption =
                document.createElement("option");

            defaultOption.text = "Select Supplier ID";

            defaultOption.disabled = true;

            defaultOption.selected = true;

            supplierSelect.add(defaultOption);


            suppliers.forEach(supplier => {

                const option =
                    document.createElement("option");

                option.value = supplier.Supplier_ID;

                option.text =
                    `${supplier.Supplier_ID} - ${supplier.SupplierName} - ${supplier.Location}`;

                supplierSelect.add(option);

            });

        } catch (error) {

            console.error(
                "Failed to fetch suppliers:",
                error
            );

        }

    }


    loadSuppliers();

});