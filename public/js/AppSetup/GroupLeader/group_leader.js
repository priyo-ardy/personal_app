const formData = document.getElementById("formData");

window.onload = () => {
  loadTable("dataTable", "/group_leader/table");
  loadEmployee();
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

const buttons = {
  cancel: document.getElementById("btnCancel"),
  update: document.getElementById("btnUpdate"),
  save: document.getElementById("btnSave"),
  export: document.getElementById("btnExport"),
  refresh: document.getElementById("btnRefresh"),
  delete: document.getElementById("btnDelete"),
};

const formInput = {
  token: document.getElementById("data_token"),
  employee: document.getElementById("data_employee"),
  remark: document.getElementById("data_remark"),
};

function resetForm() {
  formData.reset();
  const select = document.getElementById("data_employee");
  $(select).trigger("change");

  buttons.save.removeAttribute("hidden");
  buttons.update.setAttribute("hidden", true);
}

buttons.cancel.addEventListener("click", () => {
  resetForm();
});

buttons.refresh.addEventListener("click", () => {
  refreshTable();
});

buttons.delete.addEventListener("click", async () => {
  var checkedBoxes = $(".row-checkbox:checked");
  if (checkedBoxes.length === 0) return pesanError("No data selected");

  var selectedData = checkedBoxes
    .map(function () {
      return $(this).val();
    })
    .get();

  try {
    // 1. Tunggu sampai user klik YES dan API selesai merespon
    const disable = await disableData("/group_leader/delete", selectedData);

    // 2. Jalankan loadEmployee HANYA SETELAH poin 1 selesai
    // console.log("Memuat ulang dropdown...");
    // loadEmployee();
    console.log("Proses dihentikan:", disable);

    // Reset checkbox
    $(".row-checkbox").prop("checked", false);
    $("#check-all").prop("checked", false);
  } catch (e) {
    // Menangani jika user klik 'Cancel' atau API error
    console.log("Proses dihentikan:", e);
  }
});

buttons.export.addEventListener("click", async (e) => {
  try {
    loading();
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    const response = await fetch(baseurl + "/group_leader/export", {
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
      "group_leader_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
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

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/group_leader/save", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          refreshTable();
          loadEmployee();
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

function loadEmployee() {
  const select = document.getElementById("data_employee");
  select.innerHTML = '<option value="">-- Choose --</option>';

  fetchData(baseurl + "/group_leader/employee_list", "GET")
    .then((result) => {
      const fragment = document.createDocumentFragment();

    //   console.log(result.data);
        result.data.forEach((item) => {
          const option = document.createElement("option");
          option.value = item.token;
          option.textContent = `${item.nik} - ${item.name}`;
          fragment.appendChild(option);
        });

        select.appendChild(fragment);
    })
    .catch((err) => pesanError(err.message));
}

function getData(token) {
  try {
    loading();
    fetchData(baseurl + "/group_leader/get/" + token, "GET")
      .then((result) => {
        hideLoading();
        formInput.token.value = result.data.token;
        formInput.employee.value = result.data.id;
        $(formInput.employee).trigger("change");
        formInput.remark.value = result.data.remark;

        buttons.save.setAttribute("hidden", true);
        buttons.update.removeAttribute("hidden");
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
        baseurl + "/group_leader/update",
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
