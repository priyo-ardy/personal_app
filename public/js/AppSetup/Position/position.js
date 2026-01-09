const buttons = {
  add: document.getElementById("btnAdd"),
  filter: document.getElementById("btnFilter"),
  referesh: document.getElementById("btnRefresh"),
  delete: document.getElementById("btnDelete"),
  export: document.getElementById("btnExport"),
};

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/position/add");
});
