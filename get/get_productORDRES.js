// document.addEventListener("DOMContentLoaded", function () {
//   const productSelect = document.getElementById("Product_IDORDRES");

//   async function loadProductIDs() {
//     try {
//       const response = await fetch("get_product.php"); // adjust path if needed
//       const productIDs = await response.json();

//       productSelect.innerHTML = ""; // Clear current options

//       if (productIDs.length === 0) {
//         const option = document.createElement("option");
//         option.text = "No products available";
//         option.disabled = true;
//         productSelect.add(option);
//       } else {
//         const defaultOption = document.createElement("option");
//         defaultOption.text = "Select Product ID";
//         defaultOption.disabled = true;
//         defaultOption.selected = true;
//         productSelect.add(defaultOption);

//         productIDs.forEach(product => {
//           const option = document.createElement("option");
//           // option.value = id;
//           option.value = product.Product_ID;
//           // option.text = id ;
//           option.text = `${product.Product_ID} - ${product.ProductName}`;
//           productSelect.add(option);
//         });
//       }
//     } catch (error) {
//       console.error("Failed to fetch product IDs:", error);
//     }
//   }

//   loadProductIDs();
// });



// document.addEventListener("DOMContentLoaded", function () {

//     const productSelect = document.getElementById("Product_IDORDRES");
//     const packSizeInput = document.getElementById("restock-pack-size");

//     const quantityInput = document.getElementById("restock-quantity");

//     async function loadProductIDs() {

//         try {

//             const response = await fetch("get_product.php");
//             const productIDs = await response.json();

//             productSelect.innerHTML = "";

//             if (productIDs.length === 0) {

//                 const option = document.createElement("option");
//                 option.text = "No products available";
//                 option.disabled = true;

//                 productSelect.add(option);

//             } else {

//                 const defaultOption = document.createElement("option");

//                 defaultOption.text = "Select Product";
//                 defaultOption.disabled = true;
//                 defaultOption.selected = true;

//                 productSelect.add(defaultOption);


//                 productIDs.forEach(product => {

//                     const option = document.createElement("option");

//                     // Value submitted to PHP
//                     option.value = product.Product_ID;

//                     // What the user sees
//                     option.text = `${product.Product_ID} - ${product.ProductName}`;

//                     // Store Pack Size inside the option
//                     option.dataset.packSize = product.Pack_Size;

//                     productSelect.add(option);

//                 });

//             }

//         } catch (error) {

//             console.error("Failed to fetch product IDs:", error);

//         }

//     }


//     // When the manager selects a product
//     productSelect.addEventListener("change", function () {

//         const selectedOption = this.options[this.selectedIndex];

//         const packSize = selectedOption.dataset.packSize;

//         if (packSize) {

//             packSizeInput.value = packSize;

//         } else {

//             packSizeInput.value = "";

//         }

//     });


//     loadProductIDs();

// });


document.addEventListener("DOMContentLoaded", function () {

    const productSelect = document.getElementById("Product_IDORDRES");
    const packSizeInput = document.getElementById("restock-pack-size");
    const quantityInput = document.getElementById("restock-quantity");


    async function loadProductIDs() {

        try {

            const response = await fetch("get_product.php");
            const productIDs = await response.json();

            productSelect.innerHTML = "";

            if (productIDs.length === 0) {

                const option = document.createElement("option");
                option.text = "No products available";
                option.disabled = true;

                productSelect.add(option);

            } else {

                const defaultOption = document.createElement("option");

                defaultOption.value = "";
                defaultOption.text = "Select Product";
                defaultOption.disabled = true;
                defaultOption.selected = true;

                productSelect.add(defaultOption);


                productIDs.forEach(product => {

                    const option = document.createElement("option");

                    option.value = product.Product_ID;

                    option.text =
                        `${product.Product_ID} - ${product.ProductName}`;

                    option.dataset.packSize = product.Pack_Size;

                    productSelect.add(option);

                });

            }

        } catch (error) {

            console.error("Failed to fetch product IDs:", error);

        }

    }


    // ==============================
    // PRODUCT SELECTED
    // ==============================

    productSelect.addEventListener("change", function () {

        const selectedOption =
            this.options[this.selectedIndex];

        const packSize =
            selectedOption.dataset.packSize;

        if (packSize) {

            packSizeInput.value = packSize;

        } else {

            packSizeInput.value = "";

        }

        // Clear old quantity
        quantityInput.value = "";

        // Remove previous validation error
        quantityInput.setCustomValidity("");

    });


    // ==============================
    // QUANTITY VALIDATION
    // ==============================

    quantityInput.addEventListener("input", function () {

        const selectedOption =
            productSelect.options[productSelect.selectedIndex];

        if (
            !selectedOption ||
            !selectedOption.dataset.packSize
        ) {
            return;
        }

        const packSize =
            Number(selectedOption.dataset.packSize);

        const quantity =
            Number(this.value);


        // No validation yet
        if (
            !packSize ||
            packSize <= 0 ||
            !quantity
        ) {

            this.setCustomValidity("");

            return;
        }


        // Check if quantity is a multiple
        if (quantity % packSize !== 0) {

            this.setCustomValidity(
                `Quantity must be a multiple of the pack size (${packSize}).`
            );

        } else {

            this.setCustomValidity("");

        }

    });


    loadProductIDs();

});
