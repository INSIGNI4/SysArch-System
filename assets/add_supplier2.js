document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addSupplierModal");
    const openBtn = document.getElementById("sup-add-btn");
    const supcancelbtn = document.getElementById("sup-cancel-btn");

    // Open modal when ADD button is clicked
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    }); 

    // Close modal when cancel button is clicked
    supcancelbtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside modal content
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

});