document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addSupReturnsModal");
    const openBtn = document.getElementById("supreturns-add-btn");
    const supreturnscancelbtn = document.getElementById("sup-returns-cancel-btn");

    // Open modal when ADD button is clicked
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    }); 

    // Close modal when cancel button is clicked
    supreturnscancelbtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside modal content
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

});