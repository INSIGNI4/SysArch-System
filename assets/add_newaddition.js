document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addNewAdditionModal");
    const openBtn = document.getElementById("na-add-btn");
    const newaddcancelbtn = document.getElementById("newadd-cancel-btn");


    // Open modal when ADD button is clicked
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    }); 

    // Close modal when cancel button is clicked
    newaddcancelbtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside modal content
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
    
});


