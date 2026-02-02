const formData = document.getElementById("formData");
const imgPreview = document.getElementById("img_preview");

const buttons = {
  back: document.querySelectorAll(".btnBack"),
  save: document.querySelectorAll(".btnSave"),
  cancel: document.querySelectorAll(".btnCancel"),
};

const inputForm = {
  fupload: document.getElementById("fupload"),
  category: document.getElementById("data_category"),
  nik: document.getElementById("data_nik"),
  provinsi_ktp: document.getElementById("data_provinsi_ktp"),
  kota_ktp: document.getElementById("data_kota_ktp"),

  provinsi_sekarang: document.getElementById("data_provinsi_sekarang"),
  kota_sekarang: document.getElementById("data_kota_sekarang"),

  provinsi_orang_tua: document.getElementById("data_provinsi_orang_tua"),
  kota_orang_tua: document.getElementById("data_kota_orang_tua"),
};

inputForm.fupload.onchange = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      imgPreview.src = e.target.result;

      imgPreview.style.width = "250px";
      imgPreview.style.height = "300px";
      imgPreview.style.objectFit = "cover";
    };

    reader.readAsDataURL(file);
  }
};

inputForm.category.onchange = async () => {
  const categoryValue = inputForm.category.value;
  const nikInput = inputForm.nik;

  try {
    const isCategoryZero = categoryValue === "0";
    const isEmpty = categoryValue === "";

    nikInput.classList.toggle("bg-secondary-subtle", isCategoryZero);
    nikInput.readOnly = isCategoryZero;

    if (isEmpty) {
      nikInput.value = "";
      return;
    }
    loading();
    const result = await fetchData(
      `${baseurl}/employee/new_nik`,
      "POST",
      JSON.stringify({ category: categoryValue }),
    );

    nikInput.value = result.data;
  } catch (e) {
    pesanError(e.message);
  } finally {
    hideLoading();
  }
};

buttons.back.forEach((buttons) => {
  buttons.addEventListener("click", () => {
    loading();
    window.location.replace(baseurl + "/employee");
  });
});

buttons.save.forEach((buttons) => {
  buttons.addEventListener("click", () => {
    saveData();
  });
});

buttons.cancel.forEach((buttons) => {
  buttons.addEventListener("click", () => {
    resetForm();
  });
});

function saveData() {
  try {
    if (validasi()) {
      loading();
    }
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
}

function resetForm() {
  formData.reset();

  const select2 = document.querySelectorAll(".select2bs5");
  const invalidElement = document.querySelectorAll(".is-invalid");

  if (select2.length > 0) {
    select2.forEach((element) => {
      $(element).trigger("change");
    });
  }

  if (invalidElement.length > 0) {
    invalidElement.forEach((element) => {
      element.classList.remove("is-invalid");
      element.parentNode.querySelector(".invalid-feedback").textContent = "";
    });
  }
}

inputForm.provinsi_ktp.onchange = () => {
  try {
    if (inputForm.provinsi_ktp.value == "") {
      inputForm.kota_ktp.innerHTML = '<option value="">-- Choose --</option>';
    } else {
      getCityByProvince(inputForm.provinsi_ktp.value, inputForm.kota_ktp);
    }
  } catch (e) {
    pesanError(e.message);
  }
};

inputForm.provinsi_sekarang.onchange = () => {
  try {
    if (inputForm.provinsi_sekarang.value == "") {
      inputForm.kota_sekarang.innerHTML =
        '<option value="">-- Choose --</option>';
    } else {
      getCityByProvince(
        inputForm.provinsi_sekarang.value,
        inputForm.kota_sekarang,
      );
    }
  } catch (e) {
    pesanError(e.message);
  }
};

inputForm.provinsi_orang_tua.onchange = () => {
  try {
    if (inputForm.provinsi_orang_tua.value == "") {
      inputForm.kota_orang_tua.innerHTML =
        '<option value="">-- Choose --</option>';
    } else {
      getCityByProvince(
        inputForm.provinsi_orang_tua.value,
        inputForm.kota_orang_tua,
      );
    }
  } catch (e) {
    pesanError(e.message);
  }
};

function getCityByProvince(province, element) {
  try {
    loading();
    fetchData(baseurl + "/city/getCity/" + province, "GET")
      .then((result) => {
        inputForm.kota_ktp.innerHTML = '<option value="">-- Choose --</option>';
        result.data.forEach((item) => {
          element.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
        hideLoading();
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  } catch (e) {
    pesanError(e.message);
  }
}
