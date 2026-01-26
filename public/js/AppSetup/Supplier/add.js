const formData = document.getElementById("formData");

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
};

function resetForm() {
  const selectElements = document.querySelectorAll("select");
  const invalidFeedback = document.querySelectorAll(".invalid-feedback");

  formData.reset();
  selectElements.forEach((element) => {
    element.value = "";
    $(element).trigger("change");
  });

  invalidFeedback.forEach((feedback) => {
    feedback.textContent = "";
  });
}

buttons.back.addEventListener("click", (e) => {
  loading();
  window.location.replace(baseurl + "/supplier");
});

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/supplier/save", "POST", new FormData(formData))
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
