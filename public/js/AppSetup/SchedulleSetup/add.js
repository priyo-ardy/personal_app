const formData = document.getElementById("formData");
const shiftList = document.getElementById("shiftList");
const daftarShift = document.getElementById("daftarShift");

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
  generate: document.getElementById("btnGenerate"),
  clear: document.getElementById("btnClear"),
};

const inputForm = {
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  hari: document.getElementById("data_hari"),
  effective_date: document.getElementById("effective_date"),
  remark: document.getElementById("data_remark"),
};

buttons.back.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/schedulle_setup");
});

buttons.generate.addEventListener("click", () => {
  const totalHariInput = inputForm.hari.value;

  // 1. Validasi Input
  if (totalHariInput !== "" && totalHariInput !== null && totalHariInput > 0) {
    // Reset state validasi (hapus error jika sebelumnya ada)
    inputForm.hari.classList.remove("is-invalid");
    const feedback =
      inputForm.hari.parentNode.querySelector(".invalid-feedback");
    if (feedback) feedback.textContent = "";

    // 2. Kosongkan daftar sebelum generate ulang
    shiftList.innerHTML = "";

    const namaHari = [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
      "Sunday",
    ];

    // 3. Looping untuk membuat baris tabel
    for (let i = 0; i < totalHariInput; i++) {
      // Menggunakan modulo (%) agar indeks hari (0-6) selalu berulang otomatis
      const indexHari = i % 7;
      const hariIni = namaHari[indexHari];

      // Tentukan warna merah untuk Sabtu (indeks 5) dan Minggu (indeks 6)
      const classLabel =
        indexHari === 5 || indexHari === 6 ? "text-danger" : "";

      const row = `
                <tr>
                    <td class="align-middle label-hari"><label class="${classLabel} fw-bolder">Day ${i + 1} - ${hariIni}</label></td>
                    <td>
                        <select name="shift[]" class="form-control rounded-0 select2 select2bs5" required>
                            <option value="">-- Choose --</option>
                            ${daftarShift.innerHTML}
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary rounded-0 btn-sm" onclick="addRow()">Add Row</button>
                        <button type="button" class="btn btn-success rounded-0 btn-sm" onclick="insertRow(this)">Insert Row</button>
                        <button type="button" class="btn btn-danger rounded-0 btn-sm" onclick="deleteRow(this)">Delete Row</button>
                    </td>
                </tr>
            `;

      shiftList.insertAdjacentHTML("beforeend", row);
    }

    // Opsional: Jika kamu menggunakan Select2, inisialisasi ulang di sini
    $(".select2").select2({
      theme: "bootstrap-5",
      dropdownCssClass: "rounded-0",
      selectionCssClass: "rounded-0",
    });
  } else {
    // Tampilkan pesan error jika input tidak valid
    inputForm.hari.classList.add("is-invalid");
    const feedback =
      inputForm.hari.parentNode.querySelector(".invalid-feedback");
    if (feedback) {
      feedback.textContent =
        "Total day period is required and must be greater than 0";
    }
  }
});

function updateDaySequence() {
  // Mengambil semua baris (tr) yang ada di dalam tabel
  const rows = shiftList.querySelectorAll("tr");

  const namaHari = [
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday",
  ];

  // Melakukan perulangan untuk setiap baris
  rows.forEach((row, index) => {
    // Menghitung ulang hari berdasarkan indeks baris saat ini
    const indexHari = index % 7;
    const hariIni = namaHari[indexHari];

    // Cek apakah hari libur (Sabtu/Minggu) untuk warna merah
    const classLabel = indexHari === 5 || indexHari === 6 ? "text-danger" : "";

    // Mencari kolom pertama (yang punya class label-hari) di baris ini
    const tdHari = row.querySelector(".label-hari");

    // Jika kolom ditemukan, ganti isi teksnya dengan urutan yang benar
    if (tdHari) {
      tdHari.innerHTML = `<label class="${classLabel} fw-bolder mb-0">Day ${index + 1} ( ${hariIni} ) </label>`;
    }
  });
}

function addRow() {
  const row = `
        <tr>
            <td class="align-middle label-hari"></td>
            <td>
                <select name="shift[]" class="form-control rounded-0 select2 select2bs5" required>
                    <option value="">-- Choose --</option>
                    ${daftarShift.innerHTML}
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-primary rounded-0 btn-sm" onclick="addRow()">Add Row</button>
                <button type="button" class="btn btn-success rounded-0 btn-sm" onclick="insertRow(this)">Insert Row</button>
                <button type="button" class="btn btn-danger rounded-0 btn-sm" onclick="deleteRow(this)">Delete Row</button>
            </td>
        </tr>
    `;

  shiftList.insertAdjacentHTML("beforeend", row);

  $(".select2").select2({
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });

  updateDaySequence();
}

function deleteRow(button) {
  // Mencari elemen <tr> terdekat dari tombol yang diklik
  const row = button.closest("tr");

  // Hapus baris tersebut
  row.remove();

  // Urutkan ulang harinya agar nomornya tidak melompat
  updateDaySequence();
}

function insertRow(button) {
  const row = button.closest("tr");

  const newRowHTML = `
      <tr>
        <td class="align-middle label-hari">
            </td>
        <td>
            <select name="shift[]" class="form-control select2 select2bs5" required>
                <option value="">-- Choose --</option>
                ${daftarShift.innerHTML}
            </select>
        </td>
        <td class="align-middle text-center">
            <button type="button" class="btn btn-primary rounded-0 btn-sm" onclick="addRow()">Add Row</button>
            <button type="button" class="btn btn-success rounded-0 btn-sm" onclick="insertRow(this)">Insert Row</button>
            <button type="button" class="btn btn-danger rounded-0 btn-sm" onclick="deleteRow(this)">Delete Row</button>
        </td>
      </tr>
    `;

  row.insertAdjacentHTML("beforebegin", newRowHTML);

  updateDaySequence();

  $(".select2").select2({
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
}

buttons.clear.addEventListener("click", () => {
  shiftList.innerHTML = "";
});

function clearForm() {
  formData.reset();
  shiftList.innerHTML = "";

  const validElement = document.querySelectorAll(".is-valid");
  validElement.forEach((element) => {
    element.classList.remove("is-valid");
  });
}

buttons.cancel.addEventListener("click", () => {
  clearForm();
});

buttons.save.addEventListener("click", () => {
  saveData();
});
function saveData() {
  if (validasi()) {
    loading();
    fetchData(baseurl + "/schedulle_setup/save", "POST", new FormData(formData))
      .then((result) => {
        pesanSukses(result.message);
        clearForm();
        hideLoading();
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  }
}
