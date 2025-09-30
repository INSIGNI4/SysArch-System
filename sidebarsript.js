
const items = document.querySelectorAll(".has-dropdown");

function dropdownToggle() {
    items.forEach((item)=>{
        if(item !== this){
            item.classList.remove("active");
        }
    });
    this.classList.toggle("active");
}

items.forEach((item) => item.addEventListener("click", dropdownToggle));

const hamburger = document.querySelector(".sidetoggle-btn");

hamburger.addEventListener("click",function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
})
