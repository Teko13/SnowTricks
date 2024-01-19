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
function editTrickFile(fileName) {
const fileForm = document.querySelector("#trick_file_form");
fileForm.setAttribute("action", `/create/trick?editTrick=${fileName}`);
}