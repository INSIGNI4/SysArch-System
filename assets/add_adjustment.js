document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addAdjustmentModal");
    const openBtn = document.getElementById("adjustments-add-btn");
    const forcancelbtn = document.getElementById("adjustment-cancel-btn");

    // Open modal when ADD button is clicked
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    }); 

    // Close modal when cancel button is clicked
    forcancelbtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside modal content
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

});