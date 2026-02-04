const tbody = document.getElementById("tbody");

const button = {
  back: document.querySelectorAll(".btnBack"),
  save: document.querySelectorAll(".btnSave"),
  cancel: document.querySelectorAll(".btnCancel"),
};
function addRow() {
  const baris =
    `
        <tr>
            <td class="align-middle">
                <select name="data_relation[]" class="form-control select2 select2bs5" required>
                    <option value="">-- Choose --</option>
                    ` +
    document.getElementById("listRelasi").innerHTML +
    `
                </select>
                <div class="invalid-feedback"></div>
            </td>
            <td class="align-middle">
                <input type="text" name="data_name[]" class="form-control rounded-0" placeholder="Family member name" minlength="3" maxlength="150" required>
                <div class="invalid-feedback"></div>
            </td>
            <td class="align-middle">
                <select name="data_ocupation[]" class="form-control select2 select2bs5" required>
                    <option value="">-- Choose --</option>
                    ` +
    document.getElementById("listPekerjaan").innerHTML +
    `
                </select>
                <div class="invalid-feedback"></div>
            </td>
            <td class="align-middle">
                <input type="text" name="data_remark[]" class="form-control rounded-0" placeholder="Additional information">
                <div class="invalid-feedback"></div>
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-success btn-sm rounded-0" onclick="addRow()"><i class="bi bi-plus"></i></button>
                <button type="button" class="btn btn-danger btn-sm rounded-0" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>
            </td>
        </tr>
    `;

  tbody.insertAdjacentHTML("beforeend", baris);

  $(".select2bs5").select2({
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
}

function removeRow(btn) {
  btn.closest("tr").remove();
}

button.save.forEach((btn) => {
  btn.addEventListener("click", saveData);
});

function saveData() {
  if (validasi()) {
    try {
      loading();
      fetchData(
        baseurl + "/employee/family/save",
        "POST",
        new FormData(formData),
      )
        .then((result) => {
          pesanSukses(result.message);
          window.location.replace(
            baseurl + "/employee/education/" + result.data.token,
          );
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
