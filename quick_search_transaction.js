document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.getElementById("quickSearch");

  if (!searchInput) return;

  searchInput.addEventListener("keyup", function() {
    const query = this.value.toLowerCase().trim();

    const salesContainer = document.getElementById("sales-view-container");
    const transactionContainer = document.getElementById("transaction-view-container");

    // Determine which one is visible
    let activeContainer = null;
    if (window.getComputedStyle(salesContainer).display !== "none") {
      activeContainer = salesContainer;
    } else if (window.getComputedStyle(transactionContainer).display !== "none") {
      activeContainer = transactionContainer;
    }

    if (!activeContainer) return;

    const rows = activeContainer.querySelectorAll("tbody tr");
    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(query) ? "" : "none";
    });
  });
});
