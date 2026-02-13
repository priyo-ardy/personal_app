window.onload = () => {
  $(".summernote").summernote({
    height: 150, // set editor height
    minHeight: null, // set minimum height of editor
    maxHeight: null, // set maximum height of editor
  });
};

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
};

const inputForm = {
  type: document.getElementById("data_type"),
  material: document.getElementById("data_material"),
  defect: document.getElementById("data_defect"),
  sub_defect: document.getElementById("data_sub_defect"),
  model: document.getElementById("data_model"),
  mold: document.getElementById("data_mold"),
};

inputForm.type.onchange = (e) => {
  const type_document = e.target.value;

  switch (type_document) {
    case "":
      inputForm.material.innerHTML = '<option value="">-- Choose --</option>';
      inputForm.model.value = "";
      inputForm.mold.value = "";
      inputForm.defect.innerHTML = '<option value="">-- Choose --</option>';
      inputForm.sub_defect.innerHTML = '<option value="">-- Choose --</option>';
      break;
    case "1": // get part no;
      getMaterialList();
      break;
    case "2": // Get machine, equipment list
      getMachineList();
      break;
    case "3": // Get machine, equipment list
      getMachineList();
      break;
    case "4":
      getAll();
      break; // Get material, machine and equipment list
  }
};

function getMaterialList() {
  inputForm.material.innerHTML = '<option value="">-- Choose --</option>';
}

function getMachineList() {
  inputForm.material.innerHTML = '<option value="">-- Choose --</option>';
}

function getAll() {
  inputForm.material.innerHTML = '<option value="">-- Choose --</option>';
}
