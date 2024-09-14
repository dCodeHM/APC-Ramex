//Toggle Password from visible to unvisible and change the icon
document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const passwordToggle = document.getElementById("passwordToggle");
    const passwordIcon = passwordToggle.querySelector("i");
    const confirmPasswordInput = document.getElementById("confirmPassword");
    const confirmPasswordToggle = document.getElementById("confirmPasswordToggle");
    const confirmPasswordIcon = confirmPasswordToggle.querySelector("i2");
  
    passwordToggle.addEventListener("click", function () {
      const isPasswordVisible = passwordInput.type === "text";
  
      passwordInput.type = isPasswordVisible ? "password" : "text";
      passwordIcon.classList.toggle("fa-eye", !isPasswordVisible);
      passwordIcon.classList.toggle("fa-eye-slash", isPasswordVisible);
    });
  
    confirmPasswordToggle.addEventListener("click", function () {
      const isPasswordVisible = confirmPasswordInput.type === "text";
  
      confirmPasswordInput.type = isPasswordVisible ? "password" : "text";
      confirmPasswordIcon.classList.toggle("fa-eye", !isPasswordVisible);
      confirmPasswordIcon.classList.toggle("fa-eye-slash", isPasswordVisible);
    });
  });

  // Function to validate the email format
function validateEmailFormat(fieldName, email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    showError(fieldName, "Invalid email format");
  } else {
    hideError(fieldName);
  }
}

// Function to validate the password
function validatePassword() {
  const password = document.getElementById("password").value;
  const passwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[-_@$!%*?&])[A-Za-z\d\-_@$!%*?&]{8,}$/;

  if (!passwordRegex.test(password)) {
    showError(
      "password",
      "Password must meet certain criteria (uppercase, lowercase, special characters, numbers, at least 8 characters)"
    );
    return false;
  } else {
    hideError("password");
    return true;
  }
}

  // Function to show error messages
  function showError(field, message) {
    const errorElement = document.getElementById(`${field}Error`);
    errorElement.innerHTML = message;
    errorElement.style.display = "block";
  
    const inputElement = document.getElementById(field);
    inputElement.classList.add("error-input");
  }
  
  // Function to hide error messages
  function hideError(field) {
    const errorElement = document.getElementById(`${field}Error`);
    errorElement.style.display = "none";
  
    const inputElement = document.getElementById(field);
    inputElement.classList.remove("error-input");
  }
  
  // Function to validate the login form
  function validateSignupForm() {
    const fname = document.getElementById("firstName").value;
    const lname = document.getElementById("lastName").value;
    const email = document.getElementById("emailSignup").value;
    const password = document.getElementById("password").value;
    const remail = document.getElementById("repeatemailSignup").value;
    const rpassword = document.getElementById("confirmPassword").value;
    hideError("firstName");
    hideError("lastName");
    hideError("emailSignup");
    hideError("repeatemailSignup");
    hideError("password");
    hideError("confirmPassword");
    var check = 9;
  
    validateEmailFormat("emailSignup", email);
    if(validatePassword()){
        check=check-1;
    }

    if (!fname) {
        showError("firstName", "Please enter your first name");
        check=check-1;
    }
  
    if (!lname) {
        showError("lastName", "Please enter your last name");
        check=check-1;
    }

    if (!email) {
        showError("emailSignup", "Please enter an email address");
        check=check-1;
    }

    if (!password) {
        showError("password", "Password cannot be empty");
        check=check-1;
    }

    if(remail !== email){
        showError("repeatemailSignup", "Email does not match");
        check=check-1;
    }
    if (!remail) {
        showError("repeatemailSignup", "Please re-enter an email address");
        check=check-1;
    }

    if(rpassword !== password){
        showError("confirmPassword", "Password does not match");
        check=check-1;
    }
    if (!rpassword) {
        showError("confirmPassword", "Please re-enter your password");
        check=check-1;
    }
    
    if(check==8)
        return true;
    else
        return false;
  }



// Function to check if the email exists
function checkEmailExists() {
  const email = document.getElementById("emailSignup").value;

  // Make a GET request to check if the email exists
  fetch(
    `http://localhost/APC%20AcademX%20Website/api/search-user.php?email=${encodeURIComponent(
      email
    )}`
  )
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.exists) {
        // Email already exists, show an error message
        showError("emailSignup", "This email already exists");
      } else {
        postData();
      }
    })
    .catch((error) => {
      console.error("Error checking email existence:", error);
    });
}

function postData() {
  const form = document.querySelector("form");
  const formData = new FormData(form);

  $.ajax({
    url: 'http://localhost/APC%20AcademX%20Website/api/add-user.php',
    method: 'POST',
    processData: false,
    contentType: false,
    data: formData,
    dataType: 'json',
    success: function(data) {
      if (data.activation_code) {
        const activation_code = data.activation_code;
        alert("Account has been registered!");
        const email = document.getElementById("emailSignup").value;
        window.location.href = `verification.php?email=${email}&activation_code=${activation_code}&action=signup`;
    } else {
        console.error('Error: Activation code not found in response');
    }
    },
    error: function(xhr, status, error) {
      console.error('Error:', error);
    }
  });
}



function handleSignup(event) {
    event.preventDefault();
    const errorMessages = document.querySelectorAll(".error-message");
    for (const message of errorMessages) {
      if (message.style.display === "block") {
        event.preventDefault(); // Prevent form submission if there are errors
      }
    }
    if (!validateSignupForm()) {
      return;
    } else {
        checkEmailExists();
    }
  }

const signupForm = document.querySelector(".form.signup");
signupForm.addEventListener("submit", handleSignup);
