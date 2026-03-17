const buttons = {
  save: document.getElementById("btnSave"),
};

buttons.save.addEventListener("click", () => {
  loading();
  fetchData(baseurl + "/document_flow/save", "POST", new FormData(formData))
    .then((result) => {
      pesanSukses(result.message);
      resetForm();
      hideLoading();
    })
    .catch((err) => {
      pesanError(err.message);
      hideLoading();
    });
});

function resetForm() {
  formData.reset();
  const selectElement = document.querySelectorAll("select");

  selectElement.forEach((select) => {
    $(select).trigger("change");
  });
}
