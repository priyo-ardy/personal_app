window.addEventListener("DOMContentLoaded", () => {
  loadTable("dataTable", "/special_leave_setup/table");

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
  export: document.getElementById("btnExport"),
  refresh: document.getElementById("btnRefresh"),
};

const inputForm = {
  token: document.getElementById("data_token"),
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  hari: document.getElementById("data_hari"),
  dokumen: document.getElementById("data_dokumen"),
  upload_dokumen: document.getElementById("data_upload_dokumen"),
  remark: document.getElementById("data_remark"),
};

function resetForm() {
  formData.reset();
  inputForm.name.focus();
  const selectElement = document.querySelectorAll("select");
  selectElement.forEach((element) => {
    element.value = "";
    $(element).trigger("change");
  });

  const validElement = document.querySelectorAll(".is-valid");
  validElement.forEach((element) => {
    element.classList.remove("is-valid");
  });

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
      fetchData(
        baseurl + "/special_leave_setup/save",
        "POST",
        new FormData(formData),
      )
        .then((result) => {
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
    fetchData(baseurl + "/special_leave_setup/get/" + token, "GET")
      .then((result) => {
        hideLoading();
        inputForm.token.value = result.data.token;
        inputForm.code.value = result.data.code;
        inputForm.name.value = result.data.name;
        inputForm.hari.value = result.data.jml_hari;
        inputForm.dokumen.value = result.data.dokumen;
        inputForm.upload_dokumen.value = result.data.upload_dokumen;
        $(inputForm.upload_dokumen).trigger("change");
        inputForm.remark.value = result.data.remark;

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
      fetchData(
        baseurl + "/special_leave_setup/update",
        "POST",
        new FormData(formData),
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
      disableData("/special_leave_setup/delete", selectedData);
    } catch (e) {
      pesanError(e.message);
    }
  }
});

button.export.addEventListener("click", async (e) => {
  try {
    loading();
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    const response = await fetch(baseurl + "/special_leave_setup/export", {
      method: "GET",
      signal: controller.signal,
    });

    clearTimeout(timeoutId);

    if (!response.ok) {
      const errorData = await response.json().catch(() => null);
      throw new Error(
        errorData?.error || `HTTP error! status: ${response.status}`,
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
      "special_leave_setup_list_" +
      moment().format("YYYYMMDD_HHMMSS") +
      ".xlsx";
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
