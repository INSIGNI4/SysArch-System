document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addSalesModal");
    const openBtn = document.getElementById("sales-add-btn");
    const salescancelbtn = document.getElementById("sales-cancel-btn");

    // Open modal when ADD button is clicked
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    }); 

    // Close modal when cancel button is clicked
    salescancelbtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside modal content
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

});