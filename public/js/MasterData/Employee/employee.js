window.onload = () => {
  loadTable("dataTable", "/employee/table");
};

const buttons = {
  add: document.getElementById("btnAdd"),
  filter: document.getElementById("btnFilter"),
  refresh: document.getElementById("btnRefresh"),
  delete: document.getElementById("btnDelete"),
  export: document.getElementById("btnExport"),
};

buttons.refresh.addEventListener("click", () => {
  refreshTable();
});

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/employee/add");
});

function getData(token) {
  alert(token);
}
