window.onload = () => {
  loadTable();

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
  add: document.getElementById("btnAdd"),
  filter: document.getElementById("btnFilter"),
  refresh: document.getElementById("btnRefresh"),
  delete: document.getElementById("btnDelete"),
  export: document.getElementById("btnExport"),
};

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/users/add");
});

function loadTable() {
  table = $("#dataTable").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    bDestroy: true,
    autoWidth: false,
    search: {
      return: false,
    },
    order: [],
    ajax: {
      url: baseurl + "/users/table",
      type: "POST",
      data: function (d) {},
    },
    error: function (xhr, error, thrown) {
      if (typeof pesanError === "function") {
        pesanError(xhr.responseJSON ? xhr.responseJSON.message : error);
      } else {
        console.error("Error:", error);
      }
    },
    deferRender: true,
    columnDefs: [
      {
        targets: 0,
        orderable: false,
        className: "text-center align-middle",
        render: function (data, type, row) {
          return (
            '<input type="checkbox" class="row-checkbox form-check-input border-1 rounded-0 border-primary" name="token[]" value="' +
            data +
            '">'
          );
        },
      },
    ],
    drawCallback: function (settings) {
      $("#select-all").prop("checked", false);
    },
  });
}

function refreshTable() {
  $("#dataTable").DataTable().ajax.reload(null, false);
}

buttons.refresh.addEventListener("click", () => {
  refreshTable();
});

function editData(token) {
  try {
    loading();
    fetchData(baseurl + "/users/get/" + token, "GET")
      .then((result) => {
        window.location.replace(baseurl + "/users/show/" + result.data);
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

buttons.export.addEventListener("click", async () => {
  try {
    loading();
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    const response = await fetch(baseurl + "/users/export", {
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
    a.download = "users_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
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

buttons.delete.addEventListener("click", (e) => {
  var checkedBoxes = $(".row-checkbox:checked");
  var isAnyCheckboxChecked = checkedBoxes.length > 0;

  if (!isAnyCheckboxChecked) {
    pesanWarning("Failed to delete data. No data selected.");
    return;
  } else {
    var selectedData = checkedBoxes
      .map(function () {
        return $(this).val();
      })
      .get();

    try {
      deleteData(selectedData);
    } catch (e) {
      pesanError(e.message);
    }
  }
});

function deleteData(selectedData) {
  try {
    disableData("/users/mass-delete", selectedData);
  } catch (e) {
    pesanError(e.message);
  }
}
