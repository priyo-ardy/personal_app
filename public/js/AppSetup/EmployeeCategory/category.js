window.onload = () => {
  loadTable("dataTable", "/employee_category/table");

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
};

const formData = document.getElementById("formData");

const inputForm = {
  token: document.getElementById("data_token"),
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  effective_date: document.getElementById("effective_date"),
  remark: document.getElementById("data_remark"),
};

const buttons = {
  cancel: document.getElementById("btnCancel"),
  update: document.getElementById("btnUpdate"),
  save: document.getElementById("btnSave"),
  export: document.getElementById("btnExport"),
  refresh: document.getElementById("btnRefresh"),
  delete: document.getElementById("btnDelete"),
};

function resetForm() {
  formData.reset();
  buttons.update.setAttribute("hidden", true);
  buttons.save.removeAttribute("hidden");
  inputForm.name.focus();
}

buttons.cancel.addEventListener("click", () => {
  resetForm();
});

buttons.refresh.addEventListener("click", () => {
  refreshTable();
});

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(
        baseurl + "/employee_category/save",
        "POST",
        new FormData(formData)
      )
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
    fetchData(baseurl + "/employee_category/get/" + token, "GET")
      .then((result) => {
        inputForm.token.value = result.data.token;
        inputForm.code.value = result.data.code;
        inputForm.name.value = result.data.name;
        inputForm.effective_date.value = result.data.effective_date;
        inputForm.remark.value = result.data.description;

        buttons.update.removeAttribute("hidden");
        buttons.save.setAttribute("hidden", true);

        inputForm.name.focus();
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

buttons.update.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(
        baseurl + "/employee_category/update",
        "POST",
        new FormData(formData)
      )
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

buttons.delete.addEventListener("click", () => {
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
      disableData("/employee_category/delete", selectedData);
    } catch (e) {
      pesanError(e.message);
    }
  }
});

buttons.export.addEventListener("click", async () => {
  try {
    loading();
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    const response = await fetch(baseurl + "/employee_category/export", {
      method: "GET",
      signal: controller.signal,
    });

    clearTimeout(timeoutId);

    if (!response.ok) {
      const errorData = await response.json().catch(() => null);
      throw new Error(
        errorData?.error || `HTTP error! status: ${response.status}`
      );
    }

    // Dapatkan Blob
    const blob = await response.blob();

    if (blob.size === 0) {
      throw new Error("Failed to creating exported file");
    }

    // Buat link downlaod
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.style.display = "none";
    a.href = url;
    a.download =
      "employee_category_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
    document.body.appendChild(a);
    a.click();

    // Bersihkan
    window.URL.revokeObjectURL(url);
    a.remove();
    hideLoading();
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
});
