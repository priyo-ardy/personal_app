const protocol = window.location.protocol;
const hostname = window.location.hostname;
const port = window.location.port;
const baseurl = `${protocol}//${hostname}${port ? ":" + port : ""}`;

const formForgot = document.getElementById("formForgot");

const alertData = {
  error: document.getElementById("errorAlert"),
  success: document.getElementById("successAlert"),
  success_msg: document.getElementById("successMessage"),
  error_msg: document.getElementById("errorAlert"),
};

const inputForm = {
  email: document.getElementById("email"),
};

const buttons = {
  reset: document.getElementById("btnReset"),
};

function validasi() {
  let isValid = true;
  const regex =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

  if (inputForm.email.value.trim() === "") {
    isValid = false;
    inputForm.email.classList.add("is-invalid");
    inputForm.email.parentNode.querySelector(".invalid-feedback").textContent =
      "This field is required";
  } else {
    inputForm.email.classList.remove("is-invalid");
  }

  if (!regex.test(inputForm.email.value.trim())) {
    isValid = false;
    inputForm.email.classList.add("is-invalid");
    inputForm.email.parentNode.querySelector(".invalid-feedback").textContent =
      "Invalid email address";
  } else {
    inputForm.email.classList.remove("is-invalid");
  }

  return isValid;
}

function kunciForm() {
  inputForm.email.setAttribute("readonly", true);
  buttons.reset.setAttribute("disabled", true);
  buttons.reset.innerHTML =
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
}

function bukaForm() {
  inputForm.email.removeAttribute("readonly");
  inputForm.email.focus();
  buttons.reset.removeAttribute("disabled");
  buttons.reset.innerHTML =
    '<i class="bi bi-box-arrow-in-right me-2"></i> Reset Password';
}

inputForm.email.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    if (validasi()) {
      buttons.reset.click();
    }
  }
});

buttons.reset.addEventListener("click", (e) => {
  if (validasi()) {
    try {
      kunciForm();
      fetchData(baseurl + "/reset-password", "POST", new FormData(formForgot))
        .then((result) => {
          if (!alertData.error.hasAttribute("hidden")) {
            alertData.error.setAttribute("hidden", true);
          }
          alertData.success.removeAttribute("hidden");
          alertData.success.innerHTML = result.message;
          inputForm.email.value = "";
          bukaForm();
        })
        .catch((err) => {
          if (!alertData.success.hasAttribute("hidden")) {
            alertData.success.setAttribute("hidden", true);
          }
          alertData.error.removeAttribute("hidden");
          alertData.error.innerHTML = err.message;
          bukaForm();
        });
    } catch (e) {
      if (!alertData.success.hasAttribute("hidden")) {
        alertData.success.setAttribute("hidden", true);
      }
      alertData.error.removeAttribute("hidden");
      alertData.error_msg.textContent = e.message;
    }
  }
});
