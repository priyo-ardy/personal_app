function validasi() {
  let isValid = true;

  const requiredElement = document.querySelectorAll("[required]");
  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      if (element.value.trim() === "") {
        isValid = false;
        element.classList.add("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent =
          "This field is required";
      } else {
        element.classList.remove("is-invalid");

        // Hapus teks pesan error agar bersih kembali
        if (feedbackElement) {
          feedbackElement.textContent = "";
        }
      }
    });
  }

  return isValid;
}
