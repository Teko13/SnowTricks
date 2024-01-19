const up = document.querySelector("#up");
const down = document.querySelector("#down");
const cards = document.querySelectorAll(".card");

down.addEventListener("click", () => {
    window.scrollTo({
        top: window.innerHeight,
        behavior: "smooth"
    });
});
window.addEventListener("scroll", () => {
    up.style.display = ((window.scrollY > window.innerHeight) && (cards.length > 15)) ? "block" : "none";
});
up.addEventListener("click", () => {
    window.scrollTo({
        top: window.innerHeight,
        behavior: "smooth"
    })
});
