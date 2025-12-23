const protocol = window.location.protocol;
const hostname = window.location.hostname;
const port = window.location.port;
const baseurl = `${protocol}//${hostname}${port ? ":" + port : ""}`;

const errorAlert = document.getElementById("errorAlert");
const errorMessage = document.getElementById("errorMessage");
const successAlert = document.getElementById("successAlert");
const successMessage = document.getElementById("successMessage");

const formAuth = document.getElementById("formAuth");

const inputForm = {
  username: document.getElementById("username"),
  password: document.getElementById("password"),
};

const buttons = {
  togglePasswrd: document.getElementById("togglePassword"),
  auth: document.getElementById("btnAuth"),
};

buttons.togglePasswrd.addEventListener("click", () => {
  if (inputForm.password.type === "password") {
    inputForm.password.type = "text";
  } else {
    inputForm.password.type = "password";
  }
});

function validasi() {
  let isValid = true;

  if (inputForm.username.value == "") {
    inputForm.username.classList.add("is-invalid");
    isValid = false;
    inputForm.username.parentNode.querySelector(
      ".invalid-feedback"
    ).textContent = "Username is required";
  } else {
    inputForm.username.classList.remove("is-invalid");
  }

  if (inputForm.password.value == "") {
    inputForm.password.classList.add("is-invalid");
    isValid = false;
    inputForm.password.parentNode.querySelector(
      ".invalid-feedback"
    ).textContent = "Password is required";
  } else {
    inputForm.password.classList.remove("is-invalid");
  }

  return isValid;
}

inputForm.username.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    if (inputForm.username.value.trim() === "") {
      inputForm.username.classList.add("is-invalid");
      inputForm.username.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "Username is required";
    } else {
      inputForm.username.classList.remove("is-invalid");
      inputForm.password.focus();
    }
  }
});

inputForm.password.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    if (inputForm.password.value.trim() === "") {
      inputForm.password.classList.add("is-invalid");
      inputForm.password.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "Password is required";
    } else {
      inputForm.password.classList.remove("is-invalid");
      prosesLogin();
    }
  }
});

function clearForm() {
  inputForm.username.value = "";
  inputForm.password.value = "";
  inputForm.username.focus();
}

function kunciForm() {
  inputForm.username.setAttribute("readonly", true);
  inputForm.password.setAttribute("readonly", true);
  buttons.auth.setAttribute("disabled", true);
  buttons.auth.innerHTML =
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
}

function bukaForm() {
  inputForm.username.removeAttribute("readonly");
  inputForm.password.removeAttribute("readonly");
  buttons.auth.removeAttribute("disabled");
  buttons.auth.innerHTML = `<i class="bi bi-box-arrow-in-right me-2"></i> Sign In`;
}

function prosesLogin() {
  if (validasi()) {
    try {
      kunciForm();
      fetchData(baseurl + "/login", "POST", new FormData(formAuth))
        .then((result) => {
          if (!errorAlert.hasAttribute("hidden")) {
            errorAlert.setAttribute("hidden", true);
          }
          successMessage.innerHTML = result.message;
          window.location.replace(baseurl + '/dashboard');
        })
        .catch((error) => {
          if (!successAlert.hasAttribute("hidden")) {
            successAlert.setAttribute("hidden", true);
          }
          errorAlert.removeAttribute("hidden");
          errorMessage.innerHTML = error.message;
          bukaForm();
        });
    } catch (e) {
      errorAlert.removeAttribute("hidden");
      errorMessage.innerHTML = e.message;
      bukaForm();
    }
  }
}

buttons.auth.addEventListener("click", () => {
  prosesLogin();
});
