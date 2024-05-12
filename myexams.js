function showPopup() {
  // Select the popup element
  const popup = document.querySelector(".popup-hidden");
  // Remove the "hidden" class to display the popup
  popup.classList.remove("popup-hidden");
  // Prevent default link behavior
  return false;
}