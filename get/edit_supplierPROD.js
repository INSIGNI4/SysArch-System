document.addEventListener("DOMContentLoaded", function () {
  const supplierSelect = document.getElementById("edit-supplierid");

  async function loadSupplierIDs() {
    try {
      const response = await fetch("get_supplier.php"); // adjust path if needed
      const supplierIDs = await response.json();

      supplierSelect.innerHTML = ""; // Clear current options

      if (supplierIDs.length === 0) {
        const option = document.createElement("option");
        option.text = "No supplier available";
        option.disabled = true;
        supplierSelect.add(option);
      } else {
        const defaultOption = document.createElement("option");
        defaultOption.text = "Select Supplier ID";
        defaultOption.disabled = true;
        defaultOption.selected = true;
        supplierSelect.add(defaultOption);

        supplierIDs.forEach(id => {
          const option = document.createElement("option");
          option.value = id;
          option.text = id;
          supplierSelect.add(option);
        });
      }
    } catch (error) {
      console.error("Failed to fetch supplier IDs:", error);
    }
  }

  loadSupplierIDs();
});
