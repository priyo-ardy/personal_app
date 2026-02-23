window.onload = function () {
  loadEmployee();
};

const buttons = {
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
};

const inputForm = {
  employee: document.getElementById("data_employee"),
  action: document.getElementById("data_action"),
  reason: document.getElementById("data_reason"),
  position: document.getElementById("data_position"),
  durasi: document.getElementById("data_durasi"),
  tipe_durasi: document.getElementById("data_tipe_durasi"),
  akhir_kontrak: document.getElementById("data_akhir_kontrak"),
  effective_date: document.getElementById("effective_date"),
  hubungan_kerja: document.getElementById("data_hubungan_kerja"),
  durasi: document.getElementById("data_durasi"),
  expired_date: document.getElementById("data_akhir_kontrak"),
  relasi: document.getElementById("data_relasi"),
};

function loadEmployee() {
  try {
    fetchData(baseurl + "/employee/employee_registered_job_data", "GET")
      .then((result) => {
        inputForm.employee.innerHTML = `<option value="">-- Choose --</option>`;
        if (result.data.length > 0) {
          result.data.forEach((item) => {
            inputForm.employee.innerHTML += `<option value="${item.job_id}">${item.nik} - ${item.name}</option>`;
          });
        }
      })
      .catch((err) => {
        pesanError(err.message);
      });
  } catch (e) {
    pesanError(e.message);
  }
}

inputForm.employee.onchange = () => {
  try {
    loading();
    fetchData(baseurl + "/job_data/info/" + inputForm.employee.value, "GET")
      .then((result) => {
        console.log(result.data);
        hideLoading();
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  } catch (e) {
    pesanError(e.message);
  }
};
