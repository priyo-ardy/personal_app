const formData = document.getElementById("formData");
const inputForm = {
  token: document.getElementById("data_token"),
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  effective_date: document.getElementById("effective_date"),
  from_salary: document.getElementById("data_salary_from"),
  to_salary: document.getElementById("data_salary_to"),
  remark: document.getElementById("data_remark"),
};
const buttons = {
  cancel: document.getElementById("btnCancel"),
  update: document.getElementById("btnUpdate"),
  save: document.getElementById("btnSave"),
  delete: document.getElementById("btnDelete"),
  export: document.getElementById("btnExport"),
  refresh: document.getElementById("btnRefresh"),
};

function resetForm() {
  formData.reset();
  const requiredElement = document.querySelectorAll(".is-invalid");

  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      element.classList.remove("is-invalid");
      element.parentNode.querySelector(".invalid-feedback").textContent = "";
    });
  }

  buttons.update.setAttribute("hidden", true);
  buttons.save.removeAttribute("hidden");
  inputForm.code.focus();
}

buttons.cancel.addEventListener("click", () => {
  resetForm();
});

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/salary_rank/save", "POST", new FormData(formData))
        .then((result) => {
          console.log(result);
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
