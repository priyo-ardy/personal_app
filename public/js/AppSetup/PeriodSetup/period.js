/**
 * Konfigurasi Global Swal untuk konsistensi UI
 */
const swalConfig = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-primary rounded-0",
    cancelButton: "btn btn-secondary rounded-0",
  },
  buttonsStyling: true, // Ubah ke true atau hapus baris ini
  confirmButtonColor: "#0d6efd", // Warna primary Bootstrap
  cancelButtonColor: "#6c757d", // Warna secondary Bootstrap
});

/**
 * Fungsi Utama untuk Menangani Submit Form
 * @param {HTMLFormElement} form - Elemen form yang akan dikirim
 * @param {string} periodName - Nama periode untuk pesan konfirmasi
 */
async function handlePeriodSubmit(form, periodName) {
  if (!validasiForm(form)) return;

  const result = await swalConfig.fire({
    title: "Warning !",
    html: `Are you sure to change <strong class='text-danger fw-bolder'>${periodName}</strong> ?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: '<i class="bi bi-check"></i>&ensp;Yes',
    cancelButtonText: '<i class="bi bi-x"></i>&ensp;Cancel',
    reverseButtons: true,
  });

  if (result.isConfirmed) {
    // Tampilkan Loading
    Swal.fire({
      title: "Please wait...",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    try {
      const response = await fetchData(
        `${baseurl}/period_setup/save`,
        "POST",
        new FormData(form),
      );

      pesanSukses(response.message);
      setTimeout(() => window.location.reload(), 1000);
    } catch (err) {
      pesanError(err.message || "An error occurred");
    } finally {
      // Pastikan loading tertutup jika terjadi error
      if (typeof hideLoading === "function") hideLoading();
    }
  }
}

/**
 * Event Listeners dengan pengecekan eksistensi elemen
 */
const btnAll = document.getElementById("btnAll");
const btnMy = document.getElementById("btnPeriod");
const formAll = document.getElementById("formAllPeriod");
const formMy = document.getElementById("formMyPeriod");

if (btnAll && formAll) {
  btnAll.addEventListener("click", () =>
    handlePeriodSubmit(formAll, "All Period Setup"),
  );
}

if (btnMy && formMy) {
  btnMy.addEventListener("click", () =>
    handlePeriodSubmit(formMy, "My Period Setup"),
  );
}

/**
 * Fungsi Validasi yang telah dioptimasi
 */
function validasiForm(formElement) {
  const inputs = formElement.querySelectorAll("[required]");
  const tglAwal = formElement.querySelector('[name="data_tgl_awal"]');
  const tglAkhir = formElement.querySelector('[name="data_tgl_akhir"]');
  let isValid = true;

  // Bersihkan error sebelumnya
  formElement
    .querySelectorAll(".is-invalid")
    .forEach((el) => el.classList.remove("is-invalid"));

  // Validasi Required
  inputs.forEach((input) => {
    if (!input.value.trim()) {
      isValid = false;
      showError(input, "Field ini wajib diisi");
    }
  });

  // Validasi Logika Tanggal
  if (isValid && tglAwal?.value && tglAkhir?.value) {
    if (new Date(tglAwal.value) > new Date(tglAkhir.value)) {
      isValid = false;
      showError(tglAwal, "Start date must be less than end date");
      showError(tglAkhir, "End date must be greater than start date");
    }
  }

  return isValid;
}

function showError(input, message) {
  input.classList.add("is-invalid");
  const feedback = input.parentNode.querySelector(".invalid-feedback");
  if (feedback) feedback.innerText = message;
}
