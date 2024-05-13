function showPopup() {
  // Select the popup element
  const popup = document.querySelector(".popup-hidden");
  // Remove the "hidden" class to display the popup
  popup.classList.remove("popup-hidden");
}

function showsmallPopup(){
  const smolpopup = document.querySelector(".smallpopup-hidden")

  smolpopup.classList.remove("smallpopup-hidden");
}

const createButton = document.querySelectorAll(".mebox, .boxme, .emservices");
const boxContainer = document.getElementById("box-container");

createButton.addEventListener("click", function() {
  createBox();
  close_popup();
});

function close_popup(){
  closing_popup.classList.add("popup-hidden");
}

function createBox() {
  const newBox = document.createElement("div");  // Create a new <div> element
  newBox.className = "boxme";  // Apply a class for styling
  // Add content or styling to the box as needed
  // ...
  boxContainer.appendChild(newBox);  // Append the box to the container
}