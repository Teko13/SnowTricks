const up = document.querySelector("#up");
const down = document.querySelector("#down");
const readMore = document.querySelector("#loadMore");
const cards = document.querySelectorAll(".card");
readMore.style.display = cards.length > 7 ? "block" : "none";
for (i = 0; i < cards.length; i++) {
    if (i > 7) {
        cards[i].style.display = "none";
    }
}

down.addEventListener("click", () => {
    window.scrollTo({
        top: window.innerHeight,
        behavior: "smooth"
    });
});
readMore.addEventListener("click", () => {
    for (i = 0; i < cards.length; i++) {
        cards[i].style.display = "inline-block"
    }
    readMore.style.display = "none";
});
window.addEventListener("scroll", () => {
    up.style.display = (window.scrollY > window.innerHeight) ? "block" : "none";
});
up.addEventListener("click", () => {
    window.scrollTo({
        top: window.innerHeight,
        behavior: "smooth"
    })
});
