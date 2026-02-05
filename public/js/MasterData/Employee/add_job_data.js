const formData = document.getElementById("formData");
const action = document.getElementById("data_action");
const reason = document.getElementById("data_reason");
const position = document.getElementById("data_position");
const durasi = document.getElementById("data_durasi");
const tipe_durasi = document.getElementById("data_tipe_durasi");
const effective_date = document.getElementById("effective_date");

const button = {
  back: document.querySelectorAll(".btnBack"),
  save: document.querySelectorAll(".btnSave"),
  cancel: document.querySelectorAll(".btnCancel"),
};

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
};

action.onchange = () => {
  if (action.value === "") {
    reason.innerHTML = '<option value="">-- Choose --</option>';
  } else {
    try {
      loading();
      reason.innerHTML = '<option value="">-- Choose --</option>';
      fetchData(
        baseurl + "/job_data_reason/get_by_action/" + action.value,
        "GET",
      )
        .then((result) => {
          hideLoading();
          if (result.data.length > 0) {
            result.data.forEach((item) => {
              reason.innerHTML += `<option value="${item.token}">${item.name}</option>`;
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

position.onchange = () => {
  if (position.value === "") {
    kosongPosisi();
  } else {
    try {
      loading();
      fetchData(baseurl + "/position/position_detail/" + position.value, "GET")
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

effective_date.addEventListener("change", calculateContactDuration);
durasi.addEventListener("change", calculateContactDuration);
tipe_durasi.onchange = calculateContactDuration;

button.save.forEach((btn) => {
  btn.addEventListener("click", saveData);
});

function saveData() {
  if (validasi()) {
    try {
      loading();
      fetchData(
        baseurl + "/employee/job_data/save",
        "POST",
        new FormData(formData),
      )
        .then((result) => {
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
}

function calculateContactDuration() {
  const durasi = document.getElementById("data_durasi");
  const tipe_durasi = document.getElementById("data_tipe_durasi");
  const effective_date = document.getElementById("effective_date");
  const expired_date = document.getElementById("data_akhir_kontrak");

  console.log(tipe_durasi.value);

  const hasDate = effective_date.value !== "";
  const hasDuration = durasi.value !== "" && parseFloat(durasi.value) > 0;
  const hasType = tipe_durasi.value !== "";

  if (hasDate && hasDuration && hasType) {
    // 1. Inisialisasi tanggal awal
    const resultDate = new Date(effective_date.value);
    const amount = parseInt(durasi.value);
    const type = tipe_durasi.value.toLowerCase(); // Pastikan lowercase agar aman

    // 2. Logika penambahan berdasarkan tipe
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

    // 3. Format tanggal kembali ke YYYY-MM-DD agar bisa diterima input type="date"
    const year = resultDate.getFullYear();
    const month = String(resultDate.getMonth() + 1).padStart(2, "0");
    const day = String(resultDate.getDate()).padStart(2, "0");

    const formattedDate = `${year}-${month}-${day}`;

    // 4. Masukkan hasil ke input expired_date
    expired_date.value = formattedDate;

    console.log("Tanggal Berakhir:", formattedDate);
  } else {
    // Kosongkan expired_date jika input belum lengkap
    expired_date.value = "";
    console.log("Data belum lengkap.");
  }
}
