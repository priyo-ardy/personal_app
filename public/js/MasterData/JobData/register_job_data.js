window.onload = function () {
  getEmployeeList();
  getSuperiorList();
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
  superior: document.getElementById("data_superior"),
};

inputForm.effective_date.addEventListener("change", calculateContactDuration);
inputForm.tipe_durasi.onchange = calculateContactDuration;
inputForm.durasi.addEventListener("change", calculateContactDuration);

inputForm.relasi.onchange = () => {
  // 1. Cek apakah elemen pembantu sudah ada di dokumen
  let inputTipeDurasi = document.getElementById("data_tipe_durasi_helper");

  if (inputForm.relasi.value === "Tetap") {
    inputForm.durasi.value = "0";
    inputForm.tipe_durasi.value = "Tahun";
    $(inputForm.tipe_durasi).trigger("change");
    inputForm.expired_date.value = "9999-12-31";

    inputForm.durasi.setAttribute("readonly", true);
    inputForm.expired_date.setAttribute("readonly", true);
    inputForm.tipe_durasi.setAttribute("disabled", true);

    inputForm.durasi.classList.add("bg-secondary-subtle");
    inputForm.expired_date.classList.add("bg-secondary-subtle");
    inputForm.tipe_durasi.classList.add("bg-secondary-subtle");

    if (!inputTipeDurasi) {
      inputTipeDurasi = document.createElement("input");
      inputTipeDurasi.id = "data_tipe_durasi_helper";
      inputTipeDurasi.name = "data_tipe_durasi";
      inputTipeDurasi.value = "Tahun";
      inputTipeDurasi.type = "hidden";
      formData.appendChild(inputTipeDurasi);
    }
  } else {
    inputForm.durasi.value = "";
    inputForm.tipe_durasi.value = "";
    inputForm.expired_date.value = "";
    $(inputForm.tipe_durasi).trigger("change");

    inputForm.durasi.removeAttribute("readonly");
    inputForm.expired_date.removeAttribute("readonly");
    inputForm.tipe_durasi.removeAttribute("disabled");

    inputForm.durasi.classList.remove("bg-secondary-subtle");
    inputForm.expired_date.classList.remove("bg-secondary-subtle");
    inputForm.tipe_durasi.classList.remove("bg-secondary-subtle");

    if (inputTipeDurasi) {
      formData.removeChild(inputTipeDurasi);
    }
  }
};

function getSuperiorList() {
  try {
    inputForm.superior.innerHTML = '<option value="">-- Choose --</option>';
    fetchData(baseurl + "/employee/active_employee", "GET")
      .then((result) => {
        if (result.data.length > 0) {
          result.data.forEach((item) => {
            inputForm.superior.innerHTML += `<option value="${item.id}">${item.nik} - ${item.name}</option>`;
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

const formPosition = {
  nbhx_position: document.getElementById("nbhx_position"),
  position_status: document.getElementById("position_status"),
  position_grade: document.getElementById("position_grade"),
  position_rank: document.getElementById("position_rank"),
  employee_category: document.getElementById("employee_category"),
  nbhx_category: document.getElementById("nbhx_category"),
  salary_rank: document.getElementById("salary_rank"),
  data_dept: document.getElementById("data_dept"),
  data_section: document.getElementById("data_section"),
  data_report_to: document.getElementById("data_report_to"),
  superior: document.getElementById("data_superior"),
};

function kosongPosisi() {
  formPosition.nbhx_position.value = "";
  formPosition.position_status.value = "";
  formPosition.position_grade.value = "";
  formPosition.position_rank.value = "";
  formPosition.employee_category.value = "";
  formPosition.nbhx_category.value = "";
  formPosition.salary_rank.value = "";
  formPosition.data_dept.value = "";
  formPosition.data_section.value = "";
  formPosition.data_report_to.value = "";
}

inputForm.action.onchange = () => {
  if (inputForm.action.value === "") {
    inputForm.reason.innerHTML = '<option value="">-- Choose --</option>';
  } else {
    try {
      loading();
      inputForm.reason.innerHTML = '<option value="">-- Choose --</option>';
      fetchData(
        baseurl + "/job_data_reason/get_by_action/" + inputForm.action.value,
        "GET",
      )
        .then((result) => {
          hideLoading();
          if (result.data.length > 0) {
            result.data.forEach((item) => {
              inputForm.reason.innerHTML += `<option value="${item.token}">${item.name}</option>`;
            });
          }
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
};

inputForm.position.onchange = () => {
  if (inputForm.position.value === "") {
    kosongPosisi();
  } else {
    try {
      loading();
      fetchData(
        baseurl + "/position/position_detail/" + inputForm.position.value,
        "GET",
      )
        .then((result) => {
          hideLoading();

          formPosition.nbhx_position.value = result.data.nbhx_position;
          formPosition.position_status.value = result.data.status;
          formPosition.position_grade.value = result.data.grade;
          formPosition.position_rank.value = result.data.rank;
          formPosition.employee_category.value = result.data.category;
          formPosition.nbhx_category.value = result.data.nbhx_category;
          formPosition.data_dept.value = result.data.dept;
          formPosition.data_section.value = result.data.section;
          formPosition.data_report_to.value = result.data.report_to_position;
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
};

function getEmployeeList() {
  try {
    fetchData(baseurl + "/employee/employee_job_data", "GET")
      .then((result) => {
        inputForm.employee.innerHTML = "<option value=''>-- Choose --</option>";
        if (result.data.length > 0) {
          result.data.forEach((item) => {
            inputForm.employee.innerHTML += `<option value="${item.id}">${item.nik} - ${item.name}</option>`;
          });
        }
      })
      .catch((err) => {
        pesanError(err.message);
      });
  } catch (e) {
    console.log(e);
  }
}

function calculateContactDuration() {
  const hasDate = inputForm.effective_date.value !== "";
  const hasDuration =
    inputForm.durasi.value !== "" && parseFloat(inputForm.durasi.value) > 0;
  const hasType = inputForm.tipe_durasi.value !== "";

  if (hasDate && hasDuration && hasType) {
    const resultDate = new Date(inputForm.effective_date.value);
    const amount = parseInt(inputForm.durasi.value);
    const type = inputForm.tipe_durasi.value.toLowerCase();

    switch (type) {
      case "hari":
      case "days":
        resultDate.setDate(resultDate.getDate() + amount);
        break;
      case "minggu":
      case "weeks":
        resultDate.setDate(resultDate.getDate() + amount * 7);
        break;
      case "bulan":
      case "months":
        resultDate.setMonth(resultDate.getMonth() + amount);
        break;
      case "tahun":
      case "years":
        resultDate.setFullYear(resultDate.getFullYear() + amount);
        break;
      default:
        console.error("Tipe durasi tidak dikenal!");
        return;
    }

    const year = resultDate.getFullYear();
    const month = String(resultDate.getMonth() + 1).padStart(2, "0");
    const day = String(resultDate.getDate()).padStart(2, "0");

    const formattedDate = `${year}-${month}-${day}`;
    inputForm.expired_date.value = formattedDate;
  } else {
    inputForm.expired_date.value = "";
  }
}

function resetForm() {
  formData.reset();
  const selectElement = document.querySelectorAll("select");
  selectElement.forEach((element) => {
    element.value = "";
    $(element).trigger("change");
  });
}

buttons.cancel.addEventListener("click", () => {
  resetForm();
});

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(
        baseurl + "/register_job_data/save",
        "POST",
        new FormData(formData),
      )
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          getEmployeeList();
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
