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
const formDocument = document.getElementById("formDocument");
const tBody = document.getElementById("tbodyDocument");
const modalDocumentHeader = document.getElementById("apqp_name");

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
        const apqp_token = (document.getElementById("data_apqp_token").value =
          result.data.token);
        tBody.innerHTML = "";
        let number = 1;
        if (result.data.details.length > 0) {
          result.data.details.forEach((item) => {
            const tr = `
              <tr>
                <td class="editableDocument">${number}. ${item.document_name}</td>
                <td class="editableUploader">${item.uploader}</td>
                <td class="text-center align-middle editableLevel">${item.document_level}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-success rounded-0 btn-add" onclick="addDocumentRow()"><i class="bi bi-plus-circle"></i>&ensp;Add</button>
                    <button type="button" class="btn btn-sm btn-warning rounded-0 btn-edit" onclick="editDocument(this)"><i class="bi bi-pencil-square"></i>&ensp;Edit</button>
                    <button type="button" class="btn btn-sm btn-danger rounded-0 btn-delete" onclick="deleteDocument(this, '${item.token}')"><i class="bi bi-dash-circle"></i>&ensp;Delete</button>
                    <button hidden type="button" class="btn btn-update btn-primary rounded-0 btn-sm btn-update" onclick="updateDocument(this, '${item.token}')"><i class="bi bi-floppy"></i>&ensp;Update</button>
                    <button hidden type="button" class="btn btn-cancel btn-warning rounded-0 btn-sm btn-cancel" onclick="cancelDocument(this)"><i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel</button>
                </td>
              </tr>
            `;
            number++;
            tBody.insertAdjacentHTML("beforeend", tr);
          });
          document
            .getElementById("btnSaveDocument")
            .setAttribute("hidden", true);
        } else {
          addFirstDocumentRow();
        }

        modalDocumentHeader.innerText = result.data.header;
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

function addFirstDocumentRow() {
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
    document.getElementById("listEmployee").innerHTML +
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
          <button type="button" class="btn btn-success btn-sm rounded-0" onclick="addDocumentRow()"><i class="bi bi-plus-circle"></i>&ensp;Add</button>
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

function addDocumentRow() {
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
    document.getElementById("listEmployee").innerHTML +
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
        <div role="group">
          <button type="button" class="btn btn-success btn-sm rounded-0" onclick="addDocumentRow()"><i class="bi bi-plus-circle"></i>&ensp;Add</button>
          <button type="button" class="btn btn-danger btn-sm rounded-0" onclick="removeDocumentRow(this)"><i class="bi bi-dash-circle"></i>&ensp;Delete</button>
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

  document.getElementById("btnSaveDocument").removeAttribute("hidden", true);
}

function removeDocumentRow(btn) {
  const row = btn.closest("tr");

  if (row) {
    const tbody = document.getElementById("listApprover");
    row.remove();
  }
}

document.getElementById("btnSaveDocument").addEventListener("click", () => {
  if (validasiTable("formDocument")) {
    try {
      loading();
      fetchData(
        baseurl + "/apqp_setup/save_document",
        "POST",
        new FormData(formDocument),
      )
        .then((result) => {
          pesanSukses(result.message);
          closeModalDocument();
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

function closeModalDocument() {
  tBody.innerHTML = "";
  modalDocumentHeader.innerText = "";
  document.getElementById("data_apqp_token").value = "";
  document.getElementById("btnSaveDocument").removeAttribute("hidden", true);
  $("#modalDocument").modal("hide");
}

function editDocument(button) {
  const row = button.closest("tr");
  const namaDocument = row.querySelectorAll(".editableDocument");
  const namaUploader = row.querySelectorAll(".editableUploader");
  const levelDokumen = row.querySelectorAll(".editableLevel");

  let namaDokumenOriginal = [];
  let namaUploaderOriginal = [];
  let levelDokumenOriginal = [];

  namaDocument.forEach((cell) => {
    namaDokumenOriginal.push(cell.textContent.trim());
  });

  namaUploader.forEach((cell) => {
    namaUploaderOriginal.push(cell.textContent.trim());
  });

  levelDokumen.forEach((cell) => {
    levelDokumenOriginal.push(cell.textContent.trim());
  });

  row.setAttribute(
    "data-original-document-value",
    `${namaDokumenOriginal.join("|||")}`,
  );

  row.setAttribute(
    "data-original-uploader-value",
    `${namaUploaderOriginal.join("|||")}`,
  );

  row.setAttribute(
    "data-original-level-value",
    `${levelDokumenOriginal.join("|||")}`,
  );

  namaDocument.forEach((cell) => {
    const currentValue = cell.textContent.trim();
    cell.innerHTML = "";

    cell.innerHTML = `<input type="text" name="document_name[]" class="form-control rounded-0" value="${currentValue}" placeholder="Document Name" maxlength="150" value="${cell.textContent.trim()}" required>
        <div class="invalid-feedback"></div>`;
  });

  namaUploader.forEach((cell) => {
    const currentValue = cell.textContent.trim();
    cell.innerHTML = "";

    const selectElement = document.createElement("select");
    selectElement.className = "form-control select2 select2bs5";
    selectElement.name = "uploader[]";
    selectElement.innerHTML = document.getElementById("listEmployee").innerHTML;

    for (let i = 0; i < selectElement.options.length; i++) {
      if (selectElement.options[i].text.trim() === currentValue) {
        selectElement.options[i].selected = true;
        break; // Berhenti loop jika sudah ketemu (Best Practice)
      }
    }

    cell.appendChild(selectElement);
  });

  levelDokumen.forEach((cell) => {
    const currentValue = cell.textContent.trim();
    cell.innerHTML = "";

    cell.innerHTML = `
        <select class="form-control select2 select2bs5" name="document_level[]" required>
            <option value="">-- Choose --</option>
            <option value="1" ${
              currentValue === "1" ? "selected" : ""
            }>Level 1</option>
            <option value="2" ${
              currentValue === "2" ? "selected" : ""
            }>Level 2</option>
            <option value="3" ${
              currentValue === "3" ? "selected" : ""
            }>Level 3</option>
            <option value="4" ${
              currentValue === "4" ? "selected" : ""
            }>Level 4</option>
        </select>
    `;

    cell.value = currentValue;
    $(cell).trigger("change");
  });

  $(".select2bs5").select2({
    dropdownParent: $("#modalDocument"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });

  row.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-add").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-delete").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-update").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-cancel").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
}

function cancelDocument(btn) {
  const row = btn.closest("tr");
  const namaDokumen = row.querySelectorAll(".editableDocument");
  const namaUploader = row.querySelectorAll(".editableUploader");
  const levelDokumen = row.querySelectorAll(".editableLevel");
  const originalDocument = row.getAttribute("data-original-document-value");
  const originalUploader = row.getAttribute("data-original-uploader-value");
  const originalLevelDokumen = row.getAttribute("data-original-level-value");

  if (!originalDocument || !originalUploader || !originalLevelDokumen) return;

  const originalDocumentValue = originalDocument.split("|||");
  const originalUploaderValue = originalUploader.split("|||");
  const originalLevelValue = originalLevelDokumen.split("|||");

  namaDokumen.forEach((cell, index) => {
    cell.innerHTML = originalDocumentValue[index]; // Kembalikan ke teks asli
  });

  namaUploader.forEach((cell, index) => {
    cell.innerHTML = originalUploaderValue[index]; // Kembalikan ke teks asli
  });

  levelDokumen.forEach((cell, index) => {
    cell.innerHTML = originalLevelValue[index]; // Kembalikan ke teks asli
  });

  row.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-add").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-delete").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-update").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-cancel").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });

  row.removeAttribute("data-original-values");
}

function getApprover(token) {
  // $("#modalApprover").modal("show");
}

function updateDocument(button, token) {
  const row = button.closest("tr");
  const namaDocument = row.querySelector("input[name='document_name[]']");
  const namaUploader = row.querySelector("select[name='uploader[]']");
  const levelDokumen = row.querySelector("select[name='document_level[]']");
  const editableDocument = row.querySelectorAll(".editableDocument");
  const editableUploader = row.querySelectorAll(".editableUploader");
  const editableLevel = row.querySelectorAll(".editableLevel");

  if (namaDocument.value == "") {
    namaDocument.classList.add("is-invalid");
  } else if (namaUploader.value == "") {
    namaUploader.classList.add("is-invalid");
  } else if (levelDokumen.value == "") {
    levelDokumen.classList.add("is-invalid");
  } else {
    namaDocument.classList.remove("is-invalid");
    namaUploader.classList.remove("is-invalid");

    try {
      loading();
      fetchData(
        baseurl + "/apqp_setup/update_document",
        "POST",
        JSON.stringify({
          token: token,
          document_name: namaDocument.value,
          uploader: namaUploader.value,
          document_level: levelDokumen.value,
        }),
      )
        .then((result) => {
          pesanSukses(result.message);
          const token = document.getElementById("data_apqp_token").value;
          getDocument(token);
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
}

function deleteDocument(button, token) {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-primary rounded-0",
      cancelButton: "btn btn-secondary rounded-0",
    },
  });

  swalWithBootstrapButtons
    .fire({
      title: "Warning !",
      text: "Deleted data cannot be recovered",
      icon: "warning",
      showCancelButton: true,
      cancelButtonColor: "#d33",
      confirmButtonText: '<i class="bi bi-check"></i>&ensp;Yes',
      cancelButtonText: '<i class="bi bi-x"></i>&ensp;Cancel',
      reverseButtons: true,
    })
    .then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: "Please wait...",
          timerProgressBar: true,
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            swal.showLoading();
          },
        }).then(
          fetchData(
            baseurl + "/apqp_setup/delete_document",
            "POST",
            JSON.stringify({ token: token }),
          )
            .then((result) => {
              pesanSukses(result.message);
              removeDocumentRow(button);
            })
            .catch((err) => {
              pesanError(err.message);
            }),
        );
      }
    });
}
