window.onload = () => {
  loadTable("dataTable", "/salary_rank/table");
};

const formData = document.getElementById("formData");
const inputForm = {
  token: document.getElementById("data_token"),
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  effective_date: document.getElementById("effective_date"),
  from_salary: document.getElementById("data_salary_from"),
  to_salary: document.getElementById("data_salary_to"),
  remark: document.getElementById("data_remark"),
};
const buttons = {
  cancel: document.getElementById("btnCancel"),
  update: document.getElementById("btnUpdate"),
  save: document.getElementById("btnSave"),
  delete: document.getElementById("btnDelete"),
  export: document.getElementById("btnExport"),
  refresh: document.getElementById("btnRefresh"),
};

buttons.refresh.addEventListener("click", () => {
  refreshTable();
});

function resetForm() {
  formData.reset();
  const requiredElement = document.querySelectorAll(".is-invalid");

  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      element.classList.remove("is-invalid");
      element.parentNode.querySelector(".invalid-feedback").textContent = "";
    });
  }

  buttons.update.setAttribute("hidden", true);
  buttons.save.removeAttribute("hidden");
  inputForm.code.focus();
}

buttons.cancel.addEventListener("click", () => {
  resetForm();
});

function validasiInput() {
  let isValid = true;
  const requiredElemet = document.querySelectorAll("[required]");
  if (requiredElemet.length > 0) {
    requiredElemet.forEach((element) => {
      if (element.value.trim() === "") {
        isValid = false;
        element.classList.add("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent =
          "This field is required";
      } else {
        element.classList.remove("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent = "";
      }
    });
  }

  if (inputForm.from_salary.value !== "" || inputForm.to_salary.value !== "") {
    if (inputForm.from_salary.value > inputForm.to_salary.value) {
      isValid = false;
      inputForm.from_salary.classList.add("is-invalid");
      inputForm.from_salary.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "From salary must be less than to salary";
      inputForm.to_salary.classList.add("is-invalid");
      inputForm.to_salary.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "To salary must be greater than from salary";
    } else {
      inputForm.from_salary.classList.remove("is-invalid");
      inputForm.from_salary.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "";
      inputForm.to_salary.classList.remove("is-invalid");
      inputForm.to_salary.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "";
    }
  } else {
    inputForm.from_salary.classList.add("is-invalid");
    inputForm.from_salary.parentNode.querySelector(
      ".invalid-feedback"
    ).textContent = "This field is required";
    inputForm.to_salary.classList.add("is-invalid");
    inputForm.to_salary.parentNode.querySelector(
      ".invalid-feedback"
    ).textContent = "This field is required";
  }

  return isValid;
}

buttons.save.addEventListener("click", () => {
  if (validasiInput()) {
    try {
      loading();
      fetchData(baseurl + "/salary_rank/save", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          refreshTable();
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

function getData(token) {
  try {
    loading();
    fetchData(baseurl + "/salary_rank/get/" + token, "GET")
      .then((result) => {
        inputForm.token.value = result.data.token;
        inputForm.code.value = result.data.code;
        inputForm.name.value = result.data.name;
        inputForm.effective_date.value = result.data.effective_date;
        inputForm.from_salary.value = result.data.from_salary;
        inputForm.to_salary.value = result.data.to_salary;
        inputForm.remark.value = result.data.description;

        buttons.update.removeAttribute("hidden");
        buttons.save.setAttribute("hidden", true);
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

buttons.update.addEventListener("click", () => {
  if (validasiInput()) {
    try {
      loading();
      fetchData(baseurl + "/salary_rank/update", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          refreshTable();
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

// buttons.export.addEventListener("click", () => {
//   fetchData(baseurl + "/salary_rank/export", "GET");
// });

buttons.export.addEventListener("click", async () => {
  try {
    loading();
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    const response = await fetch(baseurl + "/salary_rank/export", {
      method: "GET",
      signal: controller.signal,
    });

    clearTimeout(timeoutId);

    if (!response.ok) {
      const errorData = await response.json().catch(() => null);
      throw new Error(
        errorData?.error || `HTTP error! status: ${response.status}`
      );
    }

    // Dapatkan Blob
    const blob = await response.blob();

    if (blob.size === 0) {
      throw new Error("Failed to creating exported file");
    }

    // Buat link downlaod
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.style.display = "none";
    a.href = url;
    a.download =
      "nbhx_position_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
    document.body.appendChild(a);
    a.click();

    // Bersihkan
    window.URL.revokeObjectURL(url);
    a.remove();
    hideLoading();
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
});
