window.onload = () => {
  $(".summernote").summernote("disable");
};

const formData = document.getElementById("formData");

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  edit: document.getElementById("btnEdit"),
  cancel: document.getElementById("btnCancel"),
  delete: document.getElementById("btnDelete"),
  prev: document.getElementById("btnPrev"),
  next: document.getElementById("btnNext"),
};

const inputForm = {
  token: document.getElementById("data_token"),
  user_name: document.getElementById("data_user_name"),
  full_name: document.getElementById("data_full_name"),
  email: document.getElementById("data_email"),
  phone: document.getElementById("data_phone"),
  password: document.getElementById("data_password"),
  level: document.getElementById("data_level"),
};

buttons.back.addEventListener("click", (e) => {
  loading();
  window.location.replace(baseurl + "/users");
});

buttons.cancel.addEventListener("click", (e) => {
  loading();
  window.location.reload();
});

function bukaForm() {
  buttons.save.removeAttribute("hidden");
  buttons.cancel.removeAttribute("hidden");

  buttons.back.setAttribute("hidden", true);
  buttons.edit.setAttribute("hidden", true);
  buttons.delete.setAttribute("hidden", true);
  buttons.prev.setAttribute("hidden", true);
  buttons.next.setAttribute("hidden", true);

  inputForm.user_name.classList.remove("bg-secondary-subtle");
  inputForm.full_name.classList.remove("bg-secondary-subtle");
  inputForm.email.classList.remove("bg-secondary-subtle");
  inputForm.phone.classList.remove("bg-secondary-subtle");
  inputForm.level.removeAttribute("disabled");
  inputForm.user_name.removeAttribute("readonly");
  inputForm.full_name.removeAttribute("readonly");
  inputForm.email.removeAttribute("readonly");
  inputForm.phone.removeAttribute("readonly");

  $(".summernote").summernote("enable");

  inputForm.user_name.focus();
}

buttons.edit.addEventListener("click", (e) => {
  bukaForm();
});

function validasi() {
  let isValid = true;
  if (inputForm.user_name.value.trim() === "") {
    isValid = false;
    inputForm.user_name.classList.add("is-invalid");
    inputForm.user_name.parentNode.querySelector(
      ".invalid-feedback"
    ).textContent = "Username is required";
  } else {
    inputForm.user_name.classList.remove("is-invalid");
  }
  return isValid;
}
