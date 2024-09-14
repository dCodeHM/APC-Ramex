document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("passwordLogin");
    const passwordToggle = document.getElementById("passwordLoginToggle");
    const passwordIcon = passwordToggle.querySelector("i");
  
    passwordToggle.addEventListener("click", function () {
      const isPasswordVisible = passwordInput.type === "text";
  
      passwordInput.type = isPasswordVisible ? "password" : "text";
      passwordIcon.classList.toggle("fa-eye", !isPasswordVisible);
      passwordIcon.classList.toggle("fa-eye-slash", isPasswordVisible);
    });
  });