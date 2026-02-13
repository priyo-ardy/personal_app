<?= $this->extend('Template/Admin/layout'); ?>

<?= $this->section('content'); ?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?= $title; ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard' ?>" onclick="loading()">Dashboard</a></li>
                        <li class="breadcrumb-item">Module</li>
                        <li class="breadcrumb-item">Manufacturing</li>
                        <li class="breadcrumb-item">SPK</li>
                        <li class="breadcrumb-item">List of SPK</li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group" role="group" aria-label="tooltip">
                        <button type="button" id="btnBack" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Back">
                            <i class="bi bi-arrow-left"></i>&ensp;Add
                        </button>
                        <button type="button" id="btnSave" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Save">
                            <i class="bi bi-floppy"></i>&ensp;Save
                        </button>
                        <button type="button" id="btnCancel" class="btn shadow-none rounded-0 btn-light-order-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12 clearfix">
                    <div class="card rounded-0">
                        <form id="formData" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-xl-3 ol-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_type">Document Type <strong class="text-danger">*</strong></label>
                                        <select name="data_type" id="data_type" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <option value="1">1. SPK for Mold Repair</option>
                                            <option value="2">2. SPK for Machine Repair</option>
                                            <option value="3">3. SPK for Preventive Maintenance</option>
                                            <option value="3">3. SPK for Equipment Request</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_location">Location <strong class="text-danger">*</strong></label>
                                        <select name="data_location" id="data_location" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_dept">Reported Dept <strong class="text-danger">*</strong></label>
                                        <select name="data_dept" id="data_dept" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_pelapor">Reported By <strong class="text-danger">*</strong></label>
                                        <select name="data_pelapor" id="data_pelapor" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_tanggal">Reported Date <strong class="text-danger">*</strong></label>
                                        <input type="date" name="data_tanggal" id="data_tanggal" class="form-control rounded-0" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_material">Material/Machine <strong class="text-danger">*</strong></label>
                                        <select name="data_material" id="data_material" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_model">Material/Machine/Equipment Type <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_model" id="data_model" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Material/Machine/Equipment type">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_mold">Machine/Mold No. Type <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_mold" id="data_mold" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Machine/Mold No.">
                                    </div>
                                    <div class="form-group col-xl2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_jig">Mold/Jig Status</label>
                                        <select name="data_jig" id="data_jig" class="form-select select2 select2bs5">
                                            <option value="">-- Choose --</option>
                                            <option value="1">After SOP</option>
                                            <option value="0">Before SOP</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_equipment">Mold/Jig Status</label>
                                        <select name="data_equipment" id="data_equipment" class="form-select select2 select2bs5">
                                            <option value="">-- Choose --</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_leader">Team Leader/Supervisor <strong class="text-danger">*</strong></label>
                                        <select name="data_leader" id="data_leader" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_defect">Defect <strong class="text-danger">*</strong></label>
                                        <select name="data_defect" id="data_defect" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_sub_defect">Sub Defect <strong class="text-danger">*</strong></label>
                                        <select name="data_sub_defect" id="data_sub_defect" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_berulang">Repeat Problem <strong class="text-danger">*</strong></label>
                                        <select name="data_berulang" id="data_berulang" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_reason">Repair Reasom <strong class="text-danger">*</strong></label>
                                        <select name="data_reason" id="data_reason" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_lokasi_repair">Repair Location <strong class="text-danger">*</strong></label>
                                        <select name="data_lokasi_repair" id="data_lokasi_repair" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <option value="0">Internal</option>
                                            <option value="1">External</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_supplier">Supplier <strong class="text-danger">*</strong></label>
                                        <select name="data_supplier" id="data_supplier" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-7 col-lg--7 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label" for="fupload">Upload Image</label>
                                        <input type="file" multiple class="custom-file-input form-control rounded-0" id="data_image" required name="data_image[]" accept="image/jpeg, image/png, image/gif, image/webp">
                                    </div>
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <label class="form-label" for="data_remark">Problem Description <strong class="text-danger">*</strong></label>
                                        <textarea name="data_remark" id="data_remark" class="form-control rounded-0 summernote" required></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</main>

<?= $this->endSection(); ?>