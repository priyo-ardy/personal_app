window.onload = () => {
  $(".summernote").summernote("disable");
};

const formData = document.getElementById("formData");

const buttons = {
  back: document.getElementById("btnBack"),
  edit: document.getElementById("btnEdit"),
  update: document.getElementById("btnUpdate"),
  cancel: document.getElementById("btnCancel"),
  delete: document.getElementById("btnDelete"),
  prev: document.getElementById("btnPrev"),
  next: document.getElementById("btnNext"),
};

buttons.back.addEventListener("click", (e) => {
  loading();
  window.location.replace(baseurl + "/customer");
});

buttons.cancel.addEventListener("click", (e) => {
  loading();
  window.location.reload();
});

buttons.update.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/customer/update", "POST", new FormData(formData))
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

buttons.delete.addEventListener("click", () => {
  const token = document.getElementById("data_token");
  disableData("/customer/delete", token.value, "/customer");
});

buttons.prev.addEventListener("click", (e) => {
  const code = document.getElementById("data_code");
  const workshop = document.getElementById("data_workshop");
  try {
    loading();
    fetchData(
      baseurl + "/customer/prev/",
      "POST",
      JSON.stringify({ code: code.value, workshop: workshop.value }),
    )
      .then((result) => {
        window.location.replace(
          baseurl + "/customer/show/" + result.data.token,
        );
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
});

buttons.next.addEventListener("click", () => {
  const code = document.getElementById("data_code");
  const workshop = document.getElementById("data_workshop");
  try {
    loading();
    fetchData(
      baseurl + "/customer/next/",
      "POST",
      JSON.stringify({ code: code.value, workshop: workshop.value }),
    )
      .then((result) => {
        window.location.replace(
          baseurl + "/customer/show/" + result.data.token,
        );
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
});

buttons.edit.addEventListener("click", (e) => {
  const disabledElement = document.querySelectorAll("[disabled]");

  if (disabledElement.length > 0) {
    disabledElement.forEach((item) => {
      item.removeAttribute("disabled");
    });
  }

  $(".summernote").summernote("enable");

  buttons.update.removeAttribute("hidden");
  buttons.cancel.removeAttribute("hidden");

  buttons.back.setAttribute("hidden", true);
  buttons.edit.setAttribute("hidden", true);
  buttons.delete.setAttribute("hidden", true);
  buttons.prev.setAttribute("hidden", true);
  buttons.next.setAttribute("hidden", true);

  document.getElementById("data_workshop").focus();
});
