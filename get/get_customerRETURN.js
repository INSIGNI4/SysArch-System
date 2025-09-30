document.addEventListener("DOMContentLoaded", function () {
  const productSelect = document.getElementById("Customer_IDRETURN");

  async function loadProductIDs() {
    try {
      const response = await fetch("get_customer.php"); // adjust path if needed
      const productIDs = await response.json();

      productSelect.innerHTML = ""; // Clear current options

      if (productIDs.length === 0) {
        const option = document.createElement("option");
        option.text = "No customer available";
        option.disabled = true;
        productSelect.add(option);
      } else {
        const defaultOption = document.createElement("option");
        defaultOption.text = "Select Customer ID";
        defaultOption.disabled = true;
        defaultOption.selected = true;
        productSelect.add(defaultOption);

        productIDs.forEach(id => {
          const option = document.createElement("option");
          option.value = id;
          option.text = id;
          productSelect.add(option);
        });
      }
    } catch (error) {
      console.error("Failed to fetch customer IDs:", error);
    }
  }

  loadProductIDs();
});
