document.addEventListener("DOMContentLoaded", function () {
  const productSelect = document.getElementById("Customer_IDTRANS");

  async function loadCustomerIDs() {
    try {
      const response = await fetch("get_customer.php"); // adjust path if needed
      const CustomerIDs = await response.json();

      productSelect.innerHTML = ""; // Clear current options

      if (CustomerIDs.length === 0) {
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

        CustomerIDs.forEach(customers => {
          const option = document.createElement("option");
          option.value = customers.Customer_ID;
        
          option.text = `${customers.Customer_ID} - ${customers.CustomerName} - ${customers.Location} `;
          productSelect.add(option);
        });
      }
    } catch (error) {
      console.error("Failed to fetch customer IDs:", error);
    }
  }

  loadCustomerIDs();
});
