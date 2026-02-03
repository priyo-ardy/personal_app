// function validasi() {
//   let isValid = true;

//   const requiredElements = document.querySelectorAll("[required]");

//   requiredElements.forEach((element) => {
//     const feedbackElement =
//       element.parentNode.querySelector(".invalid-feedback");

//     if (element.value.trim() === "") {
//       isValid = false;
//       element.classList.add("is-invalid");

//       if (feedbackElement) {
//         feedbackElement.textContent = "This field is required";
//       }
//     } else {
//       element.classList.remove("is-invalid");

//       if (feedbackElement) {
//         feedbackElement.textContent = "";
//       }
//     }
//   });

//   return isValid;
// }

function validasi() {
  let isValid = true;
  const requiredElements = document.querySelectorAll("[required]");

  if (requiredElements.length === 0) {
    return isValid;
  }

  requiredElements.forEach((element) => {
    const parent = element.parentNode;
    const feedbackElement = parent.querySelector(".invalid-feedback");

    // Reset status validasi
    element.classList.remove("is-invalid", "is-valid");

    // Validasi field kosong
    if (element.value.trim() === "") {
      isValid = false;
      element.classList.add("is-invalid");

      if (feedbackElement) {
        // Gunakan pesan default atau custom dari atribut data
        const customMessage = element.getAttribute("data-error-message");
        feedbackElement.textContent = customMessage || "This field is required";
      }
    } else {
      element.classList.add("is-valid");

      // Hapus pesan error jika ada
      if (feedbackElement) {
        feedbackElement.textContent = "";
      }

      // Validasi tambahan berdasarkan tipe input
      if (element.type === "email") {
        if (!validateEmail(element.value)) {
          isValid = false;
          element.classList.remove("is-valid");
          element.classList.add("is-invalid");
          if (feedbackElement) {
            feedbackElement.textContent = "Please enter a valid email address";
          }
        }
      }

      if (element.type === "tel") {
        if (!validatePhone(element.value)) {
          isValid = false;
          element.classList.remove("is-valid");
          element.classList.add("is-invalid");
          if (feedbackElement) {
            feedbackElement.textContent = "Please enter a valid phone number";
          }
        }
      }

      // Validasi panjang minimum
      const minLength = element.getAttribute("minlength");
      if (minLength && element.value.length < parseInt(minLength)) {
        isValid = false;
        element.classList.remove("is-valid");
        element.classList.add("is-invalid");
        if (feedbackElement) {
          feedbackElement.textContent = `Minimum ${minLength} characters required`;
        }
      }

      // Validasi panjang maksimum
      const maxLength = element.getAttribute("maxlength");
      if (maxLength && element.value.length > parseInt(maxLength)) {
        isValid = false;
        element.classList.remove("is-valid");
        element.classList.add("is-invalid");
        if (feedbackElement) {
          feedbackElement.textContent = `Maximum ${maxLength} characters allowed`;
        }
      }

      // Validasi pola regex
      const pattern = element.getAttribute("pattern");
      if (pattern) {
        const regex = new RegExp(pattern);
        if (!regex.test(element.value)) {
          isValid = false;
          element.classList.remove("is-valid");
          element.classList.add("is-invalid");
          if (feedbackElement) {
            const patternMessage = element.getAttribute("data-pattern-message");
            feedbackElement.textContent = patternMessage || "Invalid format";
          }
        }
      }
    }
  });

  return isValid;
}

// Fungsi validasi email
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Fungsi validasi nomor telepon (format internasional sederhana)
function validatePhone(phone) {
  const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
  return phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ""));
}

// Event listener untuk validasi real-time (opsional)
document.addEventListener("DOMContentLoaded", function () {
  const requiredElements = document.querySelectorAll("[required]");

  requiredElements.forEach((element) => {
    // Validasi saat input berubah
    element.addEventListener("input", function () {
      const parent = this.parentNode;
      const feedbackElement = parent.querySelector(".invalid-feedback");

      // Reset status
      this.classList.remove("is-invalid", "is-valid");
      if (feedbackElement) {
        feedbackElement.textContent = "";
      }

      // Lakukan validasi jika field sudah diisi
      if (this.value.trim() !== "") {
        // Panggil validasi untuk field ini saja
        validateSingleField(this);
      }
    });

    // Validasi saat kehilangan fokus
    element.addEventListener("blur", function () {
      if (this.value.trim() !== "") {
        validateSingleField(this);
      }
    });
  });
});

// Fungsi untuk validasi field tunggal
function validateSingleField(element) {
  const parent = element.parentNode;
  const feedbackElement = parent.querySelector(".invalid-feedback");

  // Reset status
  element.classList.remove("is-invalid", "is-valid");

  // Validasi berdasarkan tipe dan atribut
  let isValid = true;
  let errorMessage = "";

  if (element.value.trim() === "") {
    return; // Tidak validasi jika kosong
  }

  // Validasi email
  if (element.type === "email" && !validateEmail(element.value)) {
    isValid = false;
    errorMessage = "Please enter a valid email address";
  }

  // Validasi telepon
  if (element.type === "tel" && !validatePhone(element.value)) {
    isValid = false;
    errorMessage = "Please enter a valid phone number";
  }

  // Validasi panjang minimum
  const minLength = element.getAttribute("minlength");
  if (minLength && element.value.length < parseInt(minLength)) {
    isValid = false;
    errorMessage = `Minimum ${minLength} characters required`;
  }

  // Validasi panjang maksimum
  const maxLength = element.getAttribute("maxlength");
  if (maxLength && element.value.length > parseInt(maxLength)) {
    isValid = false;
    errorMessage = `Maximum ${maxLength} characters allowed`;
  }

  // Validasi pola regex
  const pattern = element.getAttribute("pattern");
  if (pattern) {
    const regex = new RegExp(pattern);
    if (!regex.test(element.value)) {
      isValid = false;
      const patternMessage = element.getAttribute("data-pattern-message");
      errorMessage = patternMessage || "Invalid format";
    }
  }

  // Terapkan hasil validasi
  if (isValid) {
    element.classList.add("is-valid");
    if (feedbackElement) {
      feedbackElement.textContent = "";
    }
  } else {
    element.classList.add("is-invalid");
    if (feedbackElement) {
      feedbackElement.textContent = errorMessage;
    }
  }
}
