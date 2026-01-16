const formData = document.getElementById("formData");

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
};

function resetForm() {
  formData.reset();
  $("#data_workshop").trigger("change");
  $("#data_tonnage").trigger("change");
  $(".summernote").summernote("code", "");

  document.getElementById("data_workshop").focus();
}

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/machine/save", "POST", new FormData(formData))
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

buttons.back.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/machine");
});

buttons.cancel.addEventListener("click", () => {
  resetForm();
});
