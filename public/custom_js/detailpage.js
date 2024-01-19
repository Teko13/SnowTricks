const displayBtn = document.querySelector("#displayTrickFiles");
const block = document.querySelector("#trickFiles");
let isDisplay = false; // Changed const to let

displayBtn.addEventListener("click", () => {
isDisplay = ! isDisplay;
if (isDisplay) {
block.style.display = "flex";
displayBtn.textContent = "Cacheer";
} else if (! isDisplay) {
block.style.display = "none";
displayBtn.textContent = "Voir plus";
}
});