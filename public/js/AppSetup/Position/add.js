window.onload = () => {
  loadPositionList();
};

const formData = document.getElementById("formData");

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btncancel"),
};

const inputForm = {
  name: document.getElementById("data_name"),
  nbhx_position: document.getElementById("nbhx_position"),
  remark: document.getElementById("data_remark"),
  dept: document.getElementById("data_dept"),
  section: document.getElementById("data_section"),
  report_to: document.getElementById("data_report_to"),
  grade: document.getElementById("data_grade"),
  rank: document.getElementById("data_rank"),
  status: document.getElementById("data_status"),
  category: document.getElementById("data_category"),
  nbhx_category: document.getElementById("nbhx_category"),
  effective_date: document.getElementById("effective_date"),
  absen: document.getElementById("data_absen"),
  lembur: document.getElementById("data_lembur"),
};

inputForm.dept.onchange = () => {
  if (inputForm.dept.value === "") {
    inputForm.section.innerHTML = '<option value="">-- Choose --</option>';
  } else {
    try {
      loading();
      inputForm.section.innerHTML = '<option value="">-- Choose --</option>';
      fetchData(baseurl + "/section/get_by_dept/" + inputForm.dept.value, "GET")
        .then((result) => {
          inputForm.section.innerHTML =
            '<option value="">-- Choose --</option>';
          if (result.data.length > 0) {
            result.data.forEach((item) => {
              inputForm.section.innerHTML += `<option value="${item.token}">${item.code} - ${item.name}</option>`;
            });
          }
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
};

function loadPositionList() {
  try {
    fetchData(baseurl + "/position/list", "GET")
      .then((result) => {
        inputForm.report_to.innerHTML =
          '<option value="">-- Choose --</option>';
        if (result.data.length > 0) {
          result.data.forEach((item) => {
            inputForm.report_to.innerHTML += `<option value="${item.token}">${item.code} - ${item.name}</option>`;
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

function resetForm() {
  formData.reset();
  inputForm.dept.value = "";
  $(inputForm.dept).trigger("change");
  inputForm.section.innerHTML = '<option value="">-- Choose --</option>';
  loadPositionList();
  inputForm.grade.value = "";
  $(inputForm.grade).trigger("change");
  inputForm.rank.value = "";
  $(inputForm.rank).trigger("change");
  inputForm.status.value = "";
  $(inputForm.status).trigger("change");
  inputForm.category.value = "";
  $(inputForm.category).trigger("change");
  inputForm.nbhx_category.value = "";
  $(inputForm.nbhx_category).trigger("change");
  inputForm.nbhx_position.value = "";
  $(inputForm.nbhx_position).trigger("change");
  inputForm.absen.value = "";
  $(inputForm.absen).trigger("change");
  inputForm.lembur.value = "";
  $(inputForm.lembur).trigger("change");
  inputForm.absen.value = "1";
  $(inputForm.absen).trigger("change");
  inputForm.lembur.value = "0";
  $(inputForm.lembur).trigger("change");

  inputForm.name.focus();
}

buttons.cancel.addEventListener("click", () => {
  resetForm();
});

buttons.back.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/position");
});

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/position/save", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          loadPositionList();
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
