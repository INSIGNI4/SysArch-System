document.addEventListener("DOMContentLoaded", function () {
  const supplierSelect = document.getElementById("Transaction_IDSALES");

  async function loadTransactionIDs() {
    try {
      const response = await fetch("get_transaction.php"); // adjust path if needed
      const TransactionIDs = await response.json();

      supplierSelect.innerHTML = ""; // Clear current options

      if (TransactionIDs.length === 0) {
        const option = document.createElement("option");
        option.text = "No transaction available";
        option.disabled = true;
        supplierSelect.add(option);
      } else {
        const defaultOption = document.createElement("option");
        defaultOption.text = "Select Transaction ID";
        defaultOption.disabled = true;
        defaultOption.selected = true;
        supplierSelect.add(defaultOption);

        TransactionIDs.forEach(transactions => {
          const option = document.createElement("option");
          // option.value = id;
          option.value = transactions.Transaction_ID;
          option.text = `${transactions.Transaction_ID} - ${transactions.Transaction_Date}`;
          supplierSelect.add(option);
        });
      }
    } catch (error) {
      console.error("Failed to fetch transaction IDs:", error);
    }
  }

  loadTransactionIDs();
});
