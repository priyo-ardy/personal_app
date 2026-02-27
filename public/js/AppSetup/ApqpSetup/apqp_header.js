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

function getDocument(token) {
  try {
    loading();
    fetchData(baseurl + "/apqp_setup/document_list/" + token, "GET")
      .then((result) => {
        if (result.data.length > 0) {
        } else {
          addDocumentRow();
        }

        $("#modalDocument").modal("show");
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

function addDocumentRow(tbody) {
  const tBody = document.getElementById("tableDocument");
  tBody.innerHTML = "";

  const row =
    `
    <tr>
      <td>
        <input type="text" name="document_name[]" class="form-control rounded-0" placeholder="Document name" required>
      </td>
      <td>
        <select name="uploader[]" class="form-control select2 select2bs5" required>
          <option value="">-- Choose --</option>
          ` +
    document.getElementById("listEmployee").innerHTML  +
    `
        </select>
      </td>
      <td>
        <select name="document_level[]" class="form-control select2 select2bs5" required>
          <option value="">-- Choose --</option>
          <option value="1">Level 1</option>
          <option value="2">Level 2</option>
          <option value="3">Level 3</option>
          <option value="4">Level 4</option>
        </select>
      </td>
      <td class="align-middle">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-primary btn-xs rounded-0" onclick="addRow()"><i class="bi bi-plus-circle"></i></button>
        </div>
      </td>
    </tr>
  `;

  tBody.insertAdjacentHTML("beforeend", row);

  $(".select2bs5").select2({
    dropdownParent: $("#modalDocument"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
}
function getApprover(token) {
  // $("#modalApprover").modal("show");
}
