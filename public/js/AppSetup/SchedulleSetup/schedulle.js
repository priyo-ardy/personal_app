const buttons = {
  add: document.getElementById("btnAdd"),
  filter: document.getElementById("btnFilter"),
  refresh: document.getElementById("btnRefresh"),
  delete: document.getElementById("btnDelete"),
  export: document.getElementById("btnExport"),
};

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/schedulle_setup/add");
});

const tbodyShift = document.getElementById("tbodyShift");

window.onload = () => {
  loadTable("dataTable", "/schedulle_setup/table");

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

buttons.refresh.addEventListener("click", () => {
  refreshTable();
});

function showShift(token) {
  loading();
  fetchData(baseurl + "/schedulle_setup/shift/" + token, "GET")
    .then((result) => {
      tbodyShift.innerHTML = "";
      document.getElementById("schedulle_name").textContent =
        result.data.schedulle_name;
      let baris = 1;

      const namaHari = [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
      ];

      if (result.data.shift_list) {
        for (let i = 0; i < result.data.shift_list.length; i++) {
          const indexHari = i % 7;
          const hariIni = namaHari[indexHari];

          const classLabel =
            indexHari === 5 || indexHari === 6 ? "text-danger" : "";
          const namaShift = result.data.shift_list[i].shift_name;

          const row = `
            <tr>
              <td><label class="${classLabel} fw-bolder">Day ${i + 1} - ${hariIni}</label></td>
              <td><label class="${classLabel} fw-bolder">${namaShift}</label></td>
            </tr>
          `;

          tbodyShift.insertAdjacentHTML("beforeend", row);
        }

        $("#daftarShift").modal("show");
      }
      hideLoading();
    })
    .catch((err) => {
      pesanError(err.message);
      hideLoading();
    });
}

function closeModalShift() {
  document.getElementById("schedulle_name").textContent = "";
  tbodyShift.innerHTML = "";
  $("#daftarShift").modal("hide");
}

function getData(token) {
  loading();
  fetchData(baseurl + "/schedulle_setup/get/" + token, "GET")
    .then((result) => {
      window.location.replace(baseurl + "/schedulle_setup/show/" + result.data);
    })
    .catch((err) => {
      pesanError(err.message);
      hideLoading();
    });
}

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
      disableData("/schedulle_setup/mass-delete", selectedData);
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

    const response = await fetch(baseurl + "/schedulle_setup/export", {
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
      "schedulle_setup_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
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
