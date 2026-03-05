const formAllPeriod = document.getElementById("formAllPeriod");
const formMyPeriod = document.getElementById("formMyPeriod");

const buttons = {
  all: document.getElementById("btnAll"),
  my: document.getElementById("btnPeriod"),
};

buttons.all.addEventListener("click", () => {
  if (validasiForm(formAllPeriod)) {
    try {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-primary rounded-0",
          cancelButton: "btn btn-secondary rounded-0",
        },
      });

      swalWithBootstrapButtons
        .fire({
          title: "Warning !",
          html: "Are you sure to change <strong class='text-danger fw-bolder'>All Period Setup</strong> ?",
          icon: "warning",
          showCancelButton: true,
          cancelButtonColor: "#d33",
          confirmButtonText: '<i class="bi bi-check"></i>&ensp;Yes',
          cancelButtonText: '<i class="bi bi-x"></i>&ensp;Cancel',
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: "Please wait...",
              timerProgressBar: true,
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                swal.showLoading();
              },
            }).then(
              fetchData(
                baseurl + "/period_setup/save",
                "POST",
                new FormData(formAllPeriod),
              )
                .then((result) => {
                  pesanSukses(result.message);
                  setTimeout(() => {
                    window.location.reload();
                  }, 1000);
                })
                .catch((err) => {
                  pesanError(err.message);
                }),
            );
          }
        });
    } catch (e) {
      pesanError(e.message);
      hideLoading();
    }
  }
});

buttons.my.addEventListener("click", () => {
  if (validasiForm(formMyPeriod)) {
    try {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-primary rounded-0",
          cancelButton: "btn btn-secondary rounded-0",
        },
      });

      swalWithBootstrapButtons
        .fire({
          title: "Warning !",
          html: "Are you sure to change <strong class='text-danger fw-bolder'>My Period Setup</strong> ?",
          icon: "warning",
          showCancelButton: true,
          cancelButtonColor: "#d33",
          confirmButtonText: '<i class="bi bi-check"></i>&ensp;Yes',
          cancelButtonText: '<i class="bi bi-x"></i>&ensp;Cancel',
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: "Please wait...",
              timerProgressBar: true,
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                swal.showLoading();
              },
            }).then(
              fetchData(
                baseurl + "/period_setup/save",
                "POST",
                new FormData(formMyPeriod),
              )
                .then((result) => {
                  pesanSukses(result.message);
                  setTimeout(() => {
                    window.location.reload();
                  }, 1000);
                })
                .catch((err) => {
                  pesanError(err.message);
                }),
            );
          }
        });
    } catch (e) {
      pesanError(e.message);
      hideLoading();
    }
  }
});

function validasiForm(formElement) {
  const requiredInputs = formElement.querySelectorAll("[required]");

  const tgl_awal = formElement.querySelector('[name="data_tgl_awal"]');
  const tgl_akhir = formElement.querySelector('[name="data_tgl_akhir"]');

  let isValid = true;

  formElement.querySelectorAll(".is-invalid").forEach((el) => {
    el.classList.remove("is-invalid");
  });

  requiredInputs.forEach((input) => {
    if (input.value.trim() === "") {
      isValid = false;
      showError(input, "Field ini wajib diisi");
    }
  });

  if (tgl_awal && tgl_akhir && tgl_awal.value && tgl_akhir.value) {
    if (new Date(tgl_awal.value) > new Date(tgl_akhir.value)) {
      isValid = false;
      showError(tgl_awal, "Start date must be less than end date");
      showError(tgl_akhir, "End date must be greater than start date");
    }
  }

  return isValid;
}

function showError(input, message) {
  input.classList.add("is-invalid");
  const feedback = input.parentNode.querySelector(".invalid-feedback");
  if (feedback) {
    feedback.innerText = message;
  }
}
