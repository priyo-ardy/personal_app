window.addEventListener("DOMContentLoaded", () => {
  loadTable("dataTable", "/apqp_setup/table");

  $("#select-all").on("click", function () {
    var isChecked = this.checked;
    $(".row-checkbox").prop("checked", isChecked);
  });

  $("#dataTable tbody").on("click", ".row-checkbox", function () {
    var totalCheckbox = $(".row-checkbox").length;
    var totalChecked = $(".row-checkbox:checked").length;

    if (totalCheckbox == totalChecked) {
      $("#select-all").prop("checked", true);
    } else {
      $("#select-all").prop("checked", false);
    }
  });
});

const formData = document.getElementById("formData");

const button = {
  cancel: document.getElementById("btnCancel"),
  update: document.getElementById("btnUpdate"),
  save: document.getElementById("btnSave"),
  delete: document.getElementById("btnDelete"),
  refresh: document.getElementById("btnRefresh"),
};

const inputForm = {
  token: document.getElementById("data_token"),
  sequence: document.getElementById("data_sequence"),
  name: document.getElementById("data_name"),
  remark: document.getElementById("data_remark"),
};

function refreshTable() {
  $("#dataTable").DataTable().ajax.reload(null, false);
}

function resetForm() {
  formData.reset();
  inputForm.sequence.focus();

  inputForm.sequence.removeAttribute("readonly");
  inputForm.sequence.classList.remove("bg-secondary-subtle");
  button.update.setAttribute("hidden", true);
  button.save.removeAttribute("hidden");
}

button.cancel.addEventListener("click", () => {
  resetForm();
});

button.refresh.addEventListener("click", () => {
  refreshTable();
});

button.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/apqp_setup/save", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          refreshTable();
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

function getData(token) {
  try {
    loading();
    fetchData(baseurl + "/apqp_setup/get/" + token, "GET")
      .then((result) => {
        hideLoading();
        inputForm.token.value = result.data.token;
        inputForm.sequence.value = result.data.sequence;
        inputForm.name.value = result.data.name;
        inputForm.remark.value = result.data.remark;
        inputForm.sequence.setAttribute("readonly", true);
        inputForm.sequence.classList.add("bg-secondary-subtle");

        inputForm.name.focus();

        button.save.setAttribute("hidden", true);
        button.update.removeAttribute("hidden");
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

button.update.addEventListener("click", (e) => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/apqp_setup/update", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          refreshTable();
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

button.delete.addEventListener("click", (e) => {
  var checkedBoxes = $(".row-checkbox:checked");
  var isAnyCheckboxChecked = checkedBoxes.length > 0;

  if (!isAnyCheckboxChecked) {
    pesanError("Delete failed, no data selected");
  } else {
    var selectedData = checkedBoxes
      .map(function () {
        return $(this).val();
      })
      .get();

    try {
      disableData("/apqp_setup/delete", selectedData);
    } catch (e) {
      pesanError(e.message);
    }
  }
});

function getApprover(token) {
  $("#modalApprover").modal("show");
}
