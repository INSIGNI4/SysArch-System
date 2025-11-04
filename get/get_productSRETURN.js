document.addEventListener("DOMContentLoaded", function () {
  const productSelect = document.getElementById("Product_IDSRETURN");

  async function loadProductIDs() {
    try {
      const response = await fetch("get_product.php"); // adjust path if needed
      const productIDs = await response.json();

      productSelect.innerHTML = ""; // Clear current options

      if (productIDs.length === 0) {
        const option = document.createElement("option");
        option.text = "No products available";
        option.disabled = true;
        productSelect.add(option);
      } else {
        const defaultOption = document.createElement("option");
        defaultOption.text = "Select Product ID";
        defaultOption.disabled = true;
        defaultOption.selected = true;
        productSelect.add(defaultOption);

        productIDs.forEach(product  => {
          const option = document.createElement("option");
          // option.value = id;
          option.value = product.Product_ID;
          // option.text = id ;
          option.text = `${product.Product_ID} - ${product.ProductName}`;
          productSelect.add(option);
        });
      }
    } catch (error) {
      console.error("Failed to fetch product IDs:", error);
    }
  }

  loadProductIDs();
});
