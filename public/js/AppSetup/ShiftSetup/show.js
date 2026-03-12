const autoLembur = document.getElementById("form-auto-lembur");
const formData = document.getElementById("formData");

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
  edit: document.getElementById("btnEdit"),
  add: document.getElementById("btnAdd"),
  delete: document.getElementById("btnDelete"),
  prev: document.getElementById("btnPrev"),
  next: document.getElementById("btnNext"),
};

const inputForm = {
  token: document.getElementById("data_token"),
  code: document.getElementById("data_code"),
  switch: document.getElementById("flexSwitchCheckDefault"),
  jam_masuk: document.getElementById("std_in"),
  jam_pulang: document.getElementById("std_out"),
  istirahat: document.getElementById("istirahat"),
  jam_kerja: document.getElementById("jam_kerja"),
  overtime_type: document.getElementById("overtime_type"),
  lembur_mulai: document.getElementById("lembur_mulai"),
  lembur_selesai: document.getElementById("lembur_selesai"),
  working_hour: document.getElementById("working_hour_type"),
  min_ot: document.getElementById("min_overtime"),
};

const overtime = {
  lama_lembur: document.getElementById("lama_lembur"),
  istirahat: document.getElementById("lama_istirahat"),
  rate: document.getElementById("rate_lembur"),
  x15: document.getElementById("lembur_x15"),
  x20: document.getElementById("lembur_x20"),
  x30: document.getElementById("lembur_x30"),
  x40: document.getElementById("lembur_x40"),
};

buttons.back.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/shift_setup");
});

inputForm.jam_masuk.addEventListener("change", kalkulasiJamKerja);
inputForm.jam_pulang.addEventListener("change", kalkulasiJamKerja);
inputForm.istirahat.addEventListener("change", kalkulasiJamKerja);
inputForm.overtime_type.addEventListener("change", kalkulasiOvertime);
inputForm.working_hour.addEventListener("change", kalkulasiOvertime);
inputForm.lembur_mulai.addEventListener("change", kalkulasiOvertime);
inputForm.lembur_selesai.addEventListener("change", kalkulasiOvertime);

function kalkulasiJamKerja() {
  const masuk = inputForm.jam_masuk.value;
  const pulang = inputForm.jam_pulang.value;
  const istirahatMenit = parseInt(inputForm.istirahat.value) || 0;

  if (!masuk || !pulang) {
    resetValidation();
    return;
  }

  const dateMasuk = new Date(`1970-01-01T${masuk}:00`);
  let datePulang = new Date(`1970-01-01T${pulang}:00`);

  if (datePulang < dateMasuk) {
    datePulang.setDate(datePulang.getDate() + 1);
  }
  let selisihMs = datePulang - dateMasuk;
  let totalMenitKerja = selisihMs / (1000 * 60) - istirahatMenit;

  if (totalMenitKerja < 0) {
    showError("Total jam kerja tidak valid (cek jam & istirahat)");
    return;
  } else {
    resetValidation();
  }

  // --- BAGIAN PERUBAHAN ---
  // Bagi total menit dengan 60 untuk mendapatkan desimal
  // toFixed(2) digunakan agar hasilnya konsisten 2 angka di belakang koma (misal 8.50)
  const hasilDesimal = (totalMenitKerja / 60).toFixed(2);

  if (inputForm.jam_kerja) {
    // Gunakan parseFloat untuk menghilangkan nol tidak berguna di ujung (misal 8.50 jadi 8.5)
    inputForm.jam_kerja.value = parseFloat(hasilDesimal);
  }
}

function showError(msg) {
  [inputForm.jam_masuk, inputForm.jam_pulang].forEach((el) => {
    el.classList.add("is-invalid");
    const feedback = el.parentNode.querySelector(".invalid-feedback");
    if (feedback) feedback.textContent = msg;
  });
}

function resetValidation() {
  [inputForm.jam_masuk, inputForm.jam_pulang].forEach((el) => {
    el.classList.remove("is-invalid");
  });
}

function kalkulasiOvertime() {
  try {
    if (
      inputForm.overtime_type.value !== "" &&
      inputForm.lembur_mulai.value !== "" &&
      inputForm.lembur_selesai.value !== ""
    ) {
      loading();
      fetchData(
        baseurl + "/shift_setup/hitung_lembur",
        "POST",
        JSON.stringify({
          overtime_type: inputForm.overtime_type.value,
          lembur_mulai: inputForm.lembur_mulai.value,
          lembur_selesai: inputForm.lembur_selesai.value,
          working_hour: inputForm.working_hour.value,
          min_ot: inputForm.min_ot.value,
        }),
      )
        .then((result) => {
          overtime.lama_lembur.value = result.lama_lembur;
          overtime.istirahat.value = result.lama_istirahat;
          overtime.rate.value = result.rate_lembur;
          overtime.x15.value = result.x15;
          overtime.x20.value = result.x20;
          overtime.x30.value = result.x30;
          overtime.x40.value = result.x40;
          hideLoading();
        })
        .catch((err) => {
          pesanError(err.message);
          hideLoading();
        });
    }
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
}

function bukaFrom() {
  buttons.back.setAttribute("hidden", true);
  buttons.edit.setAttribute("hidden", true);
  buttons.save.removeAttribute("hidden");
  buttons.cancel.removeAttribute("hidden");
  buttons.add.setAttribute("hidden", true);
  buttons.delete.setAttribute("hidden", true);
  buttons.prev.setAttribute("hidden", true);
  buttons.next.setAttribute("hidden", true);

  const disabledElement = document.querySelectorAll("[disabled]");
  if (disabledElement.length > 0) {
    disabledElement.forEach((item) => {
      item.removeAttribute("disabled");
    });
  }
}

buttons.edit.addEventListener("click", bukaFrom);

buttons.cancel.addEventListener("click", () => {
  loading();
  window.location.reload();
});

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/shift_setup/add");
});

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/shift_setup/update", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          setTimeout(() => {
            window.location.reload();
          }, 1000);
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

buttons.prev.addEventListener("click", () => {
  loading();
  fetchData(
    baseurl + "/shift_setup/prev",
    "POST",
    JSON.stringify({ code: inputForm.code.value }),
  )
    .then((result) => {
      window.location.replace(baseurl + "/shift_setup/show/" + result.data);
    })
    .catch((err) => {
      pesanError(err.message);
      hideLoading();
    });
});

buttons.next.addEventListener("click", () => {
  loading();
  fetchData(
    baseurl + "/shift_setup/next",
    "POST",
    JSON.stringify({ code: inputForm.code.value }),
  )
    .then((result) => {
      window.location.replace(baseurl + "/shift_setup/show/" + result.data);
    })
    .catch((err) => {
      pesanError(err.message);
      hideLoading();
    });
});

buttons.delete.addEventListener("click", () => {
  try {
    disableData("/shift_setup/delete", inputForm.token.value, "/shift_setup");
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
});
