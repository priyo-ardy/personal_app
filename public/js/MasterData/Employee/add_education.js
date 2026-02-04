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
                <select name="data_degree[]" class="form-control select2 select2bs5" required>
                    <option value="">-- Choose --</option>
                    ` +
    document.getElementById("listPendidikan").innerHTML +
    `
                </select>
                <div class="invalid-feedback"></div>
            </td>
            <td class="align-middle">
                <input type="text" name="data_sekolah[]" class="form-control rounded-0" placeholder="School name" minlength="3" maxlength="150" required>
                <div class="invalid-feedback"></div>
            </td>
            <td class="align-middle">
                <input type="text" name="data_jurusan[]" class="form-control rounded-0" placeholder="Major" minlength="3" maxlength="150" required>
                <div class="invalid-feedback"></div>
            </td>
            <td class="align-middle">
                <input type="number" name="data_tahun_lulus[]" class="form-control rounded-0" placeholder="Year of graduation" minlength="3" maxlength="150" required>
                <div class="invalid-feedback"></div>
            </td>
            <td class="align-middle">
                <input type="text" name="data_remark[]" class="form-control rounded-0" placeholder="Additional information">
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-success btn-sm rounded-0" onclick="addRow()"><i class="bi bi-plus"></i></button>
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
