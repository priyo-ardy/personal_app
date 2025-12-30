document.addEventListener("DOMContentLoaded", () => {

})

const formData = document.getElementById("formData");

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
};

const inputForm = {
  user_name: document.getElementById("data_user_name"),
  full_name: document.getElementById("data_full_name"),
  email: document.getElementById("data_email"),
  phone: document.getElementById("data_phone"),
  password: document.getElementById("data_password"),
  level: document.getElementById("data_level"),
};

function validasi() {
  let isValid = true;

  const requiredElement = document.querySelectorAll("[required]");
  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      if (element.value.trim() === "") {
        isValid = false;
        element.classList.add("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent =
          "This field is required";
      } else {
        element.classList.remove("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent = "";
      }
    });
  }

  return isValid;
}

function resetForm() {
  formData.reset();
  $(inputForm.level).trigger("change");
  inputForm.user_name.focus();
  $('.summernote').summernote('code', '');
}

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/users/save", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          hideLoading();
        })
        .catch((err) => {
          pesanError(err.message);
          hideLoading();
        });
    } catch (e) {
      pesanError(e.message);
      hideLoading();
    }
  }
});

buttons.back.addEventListener('click', (e) => {
  loading();
  window.location.replace(baseurl + '/users');
});

buttons.cancel.addEventListener('click', (e) => {
  resetForm();
  hideLoading();
})
