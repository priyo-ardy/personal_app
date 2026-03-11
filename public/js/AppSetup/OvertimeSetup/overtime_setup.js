window.addEventListener("DOMContentLoaded", () => {
  loadTable("dataTable", "/overtime_setup/table");

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
const formRate = document.getElementById("formRate");
const listRate = document.getElementById("listRate");
const tbodyRate = document.getElementById("tbodyRate");
const formEditRate = document.getElementById("formEditRate");

const button = {
  cancel: document.getElementById("btnCancel"),
  update_data: document.getElementById("btnUpdateData"),
  update: document.getElementById("btnUpdate"),
  save: document.getElementById("btnSave"),
  delete: document.getElementById("btnDelete"),
  export: document.getElementById("btnExport"),
  refresh: document.getElementById("btnRefresh"),
  rate: document.getElementById("btnRate"),
};

const inputForm = {
  token: document.getElementById("data_token"),
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  type: document.getElementById("data_type"),
  remark: document.getElementById("data_remark"),
  rate: document.getElementById("data_rate"),
  rate_token: document.getElementById("rate_token"),
};

function refreshTable() {
  $("#dataTable").DataTable().ajax.reload(null, false);
}

function resetForm() {
  formData.reset();
  inputForm.name.focus();
  $(inputForm.type).trigger("change");

  //   button.update.setAttribute("hidden", true);
  //   button.save.removeAttribute("hidden");
}

button.rate.addEventListener("click", () => {
  listRate.innerHTML = "";
  if (validasi()) {
    if (inputForm.rate.value === "" || inputForm.rate.value <= 0) {
      inputForm.rate.classList.add("is-invalid");
      inputForm.rate.parentNode.querySelector(".invalid-feedback").textContent =
        "Overtime rate is required and must be greater than 0";
    } else {
      inputForm.rate.classList.remove("is-invalid");
      inputForm.rate.parentNode.querySelector(".invalid-feedback").textContent =
        "";

      const totalRow = inputForm.rate.value;

      for (let i = 0; i < totalRow; i++) {
        listRate.innerHTML += `
        <div class="col-2">
            <label class="form-label">Hour ${i + 1}</label>
        </div>
        <div class="col-10">
            <input type="number" name="rate[]" class="form-control rounded-0" step="0.1" placeholder="Rate Hour ${i + 1}" required>
        </div>
      `;
      }

      $("#modalRate").modal("show");
    }
  }
});

function closeModalRate() {
  listRate.innerHTML = "";
  $("#modalRate").modal("hide");
}

function validasiRate() {
  const requiredElement = formRate.querySelectorAll("[required]");
  let isValid = true;

  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      if (element.value.trim() === "") {
        isValid = false;
        element.classList.add("is-invalid");
        ("This field is required");
      } else {
        element.classList.remove("is-invalid");
        element.classList.add("is-valid");
      }
    });
  }

  return isValid;
}

button.save.addEventListener("click", () => {
  // console.log(formRate.entries());
  if (validasiRate()) {
    const gabungForm = new FormData();

    const form1 = new FormData(formData);
    for (let [key, value] of form1.entries()) {
      gabungForm.append(key, value);
    }

    const form2 = new FormData(formRate);
    for (let [key, value] of form2.entries()) {
      gabungForm.append(key, value);
    }

    loading();
    fetchData(baseurl + "/overtime_setup/save", "POST", gabungForm)
      .then((result) => {
        pesanSukses(result.message);
        closeModalRate();
        resetForm();
        refreshTable();
        hideLoading();
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  }
});

function showRate(token) {
  try {
    loading();
    fetchData(baseurl + "/overtime_setup/get_rate/" + token, "GET")
      .then((result) => {
        tbodyRate.innerHTML = "";
        const rate = JSON.parse(result.data.rate);
        let i = 1;
        listRate.innerHTML = "";
        inputForm.rate_token.value = result.data.token;
        rate.forEach((item) => {
          const row = `
            <tr>
              <td>Hour ${i}</td>
              <td>
                <input type="number" name="rate[]" class="form-control rounded-0" step="0.1" placeholder="Rate Hour ${i}" value="${item}" required step="0.1">
              </td>
              <td class="align-middle text-center">
                <button type="button" class="btn btn-success rounded-0 btn-sm" onclick="addRow()"><i class="bi bi-plus-circle"></i></button>
                <button type="button" class="btn btn-danger rounded-0 btn-sm" onclick="deleteRow(this)"><i class="bi bi-trash3"></i></button>
              </td>
            </tr>
          `;

          tbodyRate.insertAdjacentHTML("beforeend", row);

          i++;
        });
        $("#modalEditRate").modal("show");
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

function closeModalEditRate() {
  tbodyRate.innerHTML = "";
  inputForm.rate_token.value = "";
  $("#modalEditRate").modal("hide");
}

function validasiEditRate() {
  const requiredElement = formEditRate.querySelectorAll("[required]");
  let isValid = true;

  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      if (element.value.trim() === "" || element.value <= 0) {
        isValid = false;
        element.classList.add("is-invalid");
        ("This field is required");
      } else {
        element.classList.remove("is-invalid");
        element.classList.add("is-valid");
      }
    });
  }

  return isValid;
}

button.update.addEventListener("click", () => {
  if (validasiEditRate()) {
    try {
      loading();
      fetchData(
        baseurl + "/overtime_setup/update_rate",
        "POST",
        new FormData(formEditRate),
      )
        .then((result) => {
          pesanSukses(result.message);
          closeModalEditRate();
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

function addRow() {
  let baris = tbodyRate.rows.length;
  baris += 1;
  const row = `
            <tr>
              <td>Hour ${baris}</td>
              <td>
                <input type="number" name="rate[]" class="form-control rounded-0" step="0.1" placeholder="Rate Hour ${baris}" value="" required step="0.1">
              </td>
              <td class="align-middle text-center">
                <button type="button" class="btn btn-success rounded-0 btn-sm" onclick="addRow()"><i class="bi bi-plus-circle"></i></button>
                <button type="button" class="btn btn-danger rounded-0 btn-sm" onclick="deleteRow(this)"><i class="bi bi-trash3"></i></button>
              </td>
            </tr>
          `;

  tbodyRate.insertAdjacentHTML("beforeend", row);

  baris++;
}

function deleteRow(btn) {
  btn.closest("tr").remove();
}

function getData(token) {
  try {
    loading();
    fetchData(baseurl + "/overtime_setup/get/" + token, "GET")
      .then((result) => {
        inputForm.token.value = result.data.token;
        inputForm.code.value = result.data.code;
        inputForm.name.value = result.data.name;
        inputForm.type.value = result.data.day_type;
        $(inputForm.type).trigger("change");
        inputForm.rate.value = result.data.total_row;
        inputForm.remark.value = result.data.description;
        inputForm.name.focus();
        inputForm.rate.setAttribute("disabled", true);

        button.rate.setAttribute("hidden", true);
        button.cancel.removeAttribute("hidden");
        button.update_data.removeAttribute("hidden");

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

button.cancel.addEventListener("click", () => {
  cancelForm();
});

function cancelForm() {
  formData.reset();
  $(inputForm.type).trigger("change");
  inputForm.rate.removeAttribute("disabled");
  button.rate.removeAttribute("hidden");
  button.cancel.setAttribute("hidden", true);
  button.update_data.setAttribute("hidden", true);
}

button.update_data.addEventListener("click", () => {
  loading();
  fetchData(baseurl + "/overtime_setup/update", "POST", new FormData(formData))
    .then((result) => {
      pesanSukses(result.message);
      cancelForm();
      refreshTable();
      hideLoading();
    })
    .catch((err) => {
      pesanError(err.message);
      hideLoading();
    });
});

button.delete.addEventListener("click", () => {
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
      disableData("/overtime_setup/delete", selectedData);
    } catch (e) {
      pesanError(e.message);
    }
  }
});

button.export.addEventListener("click", async () => {
  try {
    loading();
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    const response = await fetch(baseurl + "/overtime_setup/export", {
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
      "overtime_setup_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
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
