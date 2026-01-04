function loadTable(tableId, url) {
  $("#" + tableId + "").DataTable({
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
      url: baseurl + url,
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
