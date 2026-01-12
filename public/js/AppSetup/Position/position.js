window.onload = () => {
  loadTable("dataTable", "/position/table");
};

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

function getData(token) {
  try {
    loading();
    fetchData(baseurl + "/position/get/" + token, "GET")
      .then((result) => {
        window.location.replace(
          baseurl + "/position/show/" + result.data.token
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

buttons.referesh.addEventListener("click", () => {
  refreshTable();
});
