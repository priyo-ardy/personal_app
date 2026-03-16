const formData = document.getElementById("formData");
const shiftList = document.getElementById("shiftList");
const daftarShift = document.getElementById("daftarShift");

const buttons = {
  back: document.getElementById("btnBack"),
  edit: document.getElementById("btnEdit"),
  update: document.getElementById("btnUpdate"),
  cancel: document.getElementById("btnCancel"),
  add: document.getElementById("btnAdd"),
  delete: document.getElementById("btnDelete"),
  prev: document.getElementById("btnPrev"),
  next: document.getElementById("btnNext"),
  generate: document.getElementById("btnGenerate"),
};

const inputForm = {
  token: document.getElementById("data_token"),
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  hari: document.getElementById("data_hari"),
  effective_date: document.getElementById("effective_date"),
  remark: document.getElementById("data_remark"),
};

function bukaForm() {
  buttons.back.setAttribute("hidden", true);
  buttons.edit.setAttribute("hidden", true);
  buttons.add.setAttribute("hidden", true);
  buttons.delete.setAttribute("hidden", true);
  buttons.prev.setAttribute("hidden", true);
  buttons.next.setAttribute("hidden", true);
  buttons.update.removeAttribute("hidden");
  buttons.cancel.removeAttribute("hidden");

  const disabledElement = document.querySelectorAll("[disabled]");

  if (disabledElement.length > 0) {
    disabledElement.forEach((item) => {
      item.removeAttribute("disabled");
    });
  }
}

buttons.edit.addEventListener("click", (e) => {
  bukaForm();
});

buttons.back.addEventListener("click", (e) => {
  loading();
  window.location.replace(baseurl + "/schedulle_setup");
});

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/schedulle_setup/add");
});

buttons.cancel.addEventListener("click", (e) => {
  loading();
  window.location.reload();
});

buttons.generate.addEventListener("click", () => {
  if (validasi()) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-primary rounded-0",
        cancelButton: "btn btn-secondary rounded-0",
      },
    });

    swalWithBootstrapButtons
      .fire({
        title: "Warning !",
        text: "Are you sure you want to regenerate the shift list for this schedule?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonColor: "#d33",
        confirmButtonText: '<i class="bi bi-check"></i>&ensp;Yes',
        cancelButtonText: '<i class="bi bi-x"></i>&ensp;Cancel',
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {
          loading();
          generateList();
        }
      });
  }
});

function generateList() {
  const totalHariInput = inputForm.hari.value;
  if (totalHariInput !== "" && totalHariInput !== null && totalHariInput > 0) {
    inputForm.hari.classList.remove("is-invalid");
    const feedback =
      inputForm.hari.parentNode.querySelector(".invalid-feedback");
    if (feedback) feedback.textContent = "";

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

    for (let i = 0; i < totalHariInput; i++) {
      // Menggunakan modulo (%) agar indeks hari (0-6) selalu berulang otomatis
      const indexHari = i % 7;
      const hariIni = namaHari[indexHari];

      const classLabel =
        indexHari === 5 || indexHari === 6 ? "text-danger" : "";

      const row = `
                <tr>
                    <td><label class="${classLabel} fw-bolder">Day ${i + 1} - ${hariIni}</label></td>
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

    $(".select2").select2({
      theme: "bootstrap-5",
      dropdownCssClass: "rounded-0",
      selectionCssClass: "rounded-0",
    });
  }

  hideLoading();
}

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

buttons.cancel.addEventListener("click", () => {
  loading();
  window.location.reload();
});

function updateData() {
  if (validasi()) {
    loading();
    fetchData(
      baseurl + "/schedulle_setup/update",
      "POST",
      new FormData(formData),
    )
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
  }
}

buttons.delete.addEventListener("click", () => {
  disableData(
    "/schedulle_setup/delete",
    inputForm.token.value,
    "/schedulle_setup",
  );
});

buttons.prev.addEventListener("click", () => {
  loading();

  fetchData(
    baseurl + "/schedulle_setup/prev",
    "POST",
    JSON.stringify({ code: inputForm.code.value }),
  )
    .then((result) => {
      window.location.replace(baseurl + "/schedulle_setup/show/" + result.data);
    })
    .catch((err) => {
      pesanError(err.message);
      hideLoading();
    });
});

buttons.next.addEventListener("click", () => {
  loading();
  fetchData(
    baseurl + "/schedulle_setup/next",
    "POST",
    JSON.stringify({ code: inputForm.code.value }),
  )
    .then((result) => {
      window.location.replace(baseurl + "/schedulle_setup/show/" + result.data);
    })
    .catch((err) => {
      pesanError(err.message);
      hideLoading();
    });
});
