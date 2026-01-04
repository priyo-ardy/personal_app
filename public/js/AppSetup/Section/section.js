window.onload = function () {
    loadTable('dataTable', '/section/table');

    $("#select-all").on("click", function () {
        var isChecked = this.checked;
        $(".row-checkbox").prop("checked", isChecked);
    });

    $("#dataTable tbody").on("click", ".row-checkbox", function () {
        var totalCheckbox = $(".row-checkbox").length;
        var totalChecked = $(".row-checkbox:checked").length;

        if (totalCheckbox == totalChecked) {
            $("#select-all").prop("checked", true);
        } else {
            $("#select-all").prop("checked", false);
        }
    });
}
const formData = document.getElementById('formData');
const inputForm = {
    token: document.getElementById('data_token'),
    code: document.getElementById('data_code'),
    dept: document.getElementById('data_dept'),
    name: document.getElementById('data_name'),
    effective_date: document.getElementById('effective_date'),
    remark: document.getElementById('data_remark')
}
const buttons = {
    cancel: document.getElementById('btnCancel'),
    update: document.getElementById('btnUpdate'),
    save: document.getElementById('btnSave'),
    delete: document.getElementById('btnDelete'),
    export: document.getElementById('btnExport'),
    refresh: document.getElementById('btnRefresh')
}

function resetForm() {
    formData.reset();
    $(inputForm.dept).trigger('change');
    $(inputForm.name).focus();

    buttons.save.removeAttribute('hidden');
    buttons.update.setAttribute('hidden', 'hidden');
}

buttons.cancel.addEventListener('click', (e) => {
    resetForm();;
});

buttons.refresh.addEventListener('click', () => {
    refreshTable();
})

buttons.save.addEventListener('click', (e) => {
    if (validasi()) {
        try {
            loading();
            fetchData(baseurl + '/section/save', 'POST', new FormData(formData))
                .then(result => {
                    pesanSukses(result.message);
                    resetForm();
                    refreshTable();
                    hideLoading();
                })
                .catch(err => {
                    pesanError(err.message);
                    hideLoading();
                })
        } catch (e) {
            pesanError(e.message);
            hideLoading();
        }
    }
})

function getData(token) {
    try {
        loading();
        fetchData(baseurl + '/section/get/' + token)
            .then(result => {
                inputForm.token.value = result.data.token;
                inputForm.code.value = result.data.code;
                inputForm.dept.value = result.data.dept;
                $(inputForm.dept).trigger('change');
                inputForm.name.value = result.data.name;
                inputForm.effective_date.value = result.data.effective_date;
                inputForm.remark.value = result.data.description;
                buttons.save.setAttribute('hidden', 'hidden');
                buttons.update.removeAttribute('hidden');
                hideLoading();
            })
            .catch(err => {
                pesanError(err.message);
                hideLoading();
            })
    } catch (e) {
        pesanError(e.message);
        hideLoading();
    }
}

buttons.update.addEventListener('click', (e) => {
    if (validasi()) {
        try {
            fetchData(baseurl + '/section/update', 'POST', new FormData(formData))
                .then(result => {
                    pesanSukses(result.message);
                    resetForm();
                    refreshTable();
                    hideLoading();
                })
                .catch(err => {
                    pesanError(err.message);
                    hideLoading();
                })
        } catch (e) {
            pesanError(e.message);
            hideLoading();
        }
    }
})

buttons.delete.addEventListener('click', () => {
    var checkedBoxes = $(".row-checkbox:checked");
    var isAnyCheckboxChecked = checkedBoxes.length > 0;

    if (!isAnyCheckboxChecked) {
        pesanError("Delete failed, no data selected");
    } else {
        var selectedData = checkedBoxes
            .map(function () {
                return $(this).val();
            })
            .get();

        try {
            disableData('/section/delete', selectedData);
        } catch (e) {
            pesanError(e.message);
            hideLoading();
        }
    }
})

buttons.export.addEventListener("click", async (e) => {
    try {
        loading();
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 300000);

        const response = await fetch(baseurl + "/section/export", {
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
            "section_list" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
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