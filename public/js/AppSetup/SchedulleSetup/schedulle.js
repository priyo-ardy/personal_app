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
