const autoLembur = document.getElementById("form-auto-lembur");

const buttons = {};

const inputForm = {
  switch: document.getElementById("flexSwitchCheckDefault"),
  jam_masuk: document.getElementById("std_in"),
  jam_pulang: document.getElementById("std_out"),
  istirahat: document.getElementById("istirahat"),
  jam_kerja: document.getElementById("jam_kerja"),
};

inputForm.switch.addEventListener("change", () => {
  if (inputForm.switch.checked) {
    autoLembur.removeAttribute("hidden");
  } else {
    autoLembur.setAttribute("hidden", "hidden");
  }
});

inputForm.jam_masuk.addEventListener("change", kalkulasiJamKerja);
inputForm.jam_pulang.addEventListener("change", kalkulasiJamKerja);
inputForm.istirahat.addEventListener("change", kalkulasiJamKerja);

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

  const jam = Math.floor(totalMenitKerja / 60);
  const sisaMenit = totalMenitKerja % 60;

  const hasilString = `${jam}.${sisaMenit.toString().padStart(2, "0")}`;
  //   console.log("Hasil Kalkulasi:", hasilString);

  if (inputForm.jam_kerja) {
    inputForm.jam_kerja.value = hasilString;
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
