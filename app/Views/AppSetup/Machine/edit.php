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
                        <li class="breadcrumb-item">Application Setup</li>
                        <li class="breadcrumb-item">Mfg Setup</li>
                        <li class="breadcrumb-item">List of Machine</li>
                        <li class="breadcrumb-item active">Edit Machine</li>
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
                        <button type="button" id="btnEdit" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class="bi bi-pencil-square"></i>&ensp;Edit
                        </button>
                        <button type="button" hidden id="btnUpdate" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Update">
                            <i class="bi bi-floppy"></i>&ensp;Update
                        </button>
                        <button type="button" hidden id="btnCancel" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                        <button type="button" id="btnDelete" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="bi bi-trash3"></i>&ensp;Delete
                        </button>
                        <button type="button" id="btnPrev" class="btn shadow-none rounded-0 btn-light-order-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Previous">
                            <i class="bi bi-chevron-double-left"></i>&ensp;Prev
                        </button>
                        <button type="button" id="btnNext" class="btn shadow-none rounded-0 btn-light-order-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Next">
                            Next&ensp;<i class="bi bi-chevron-double-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12 clearfix">
                    <div class="card rounded-0">
                        <form id="formData">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-12 mb-3 clearfix" style="display: none;">
                                        <input type="text" name="data_token" id="data_token" readonly class="form-control rounded-0" value="<?= $data['token'] ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_workshop">Workshop <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_workshop" id="data_workshop" class="form-control select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($workshop as $ws): ?>
                                                <option <?= ($data['workshop'] == $ws->id) ? 'selected' : '' ?> value="<?= $ws->id ?>"><?= $ws->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_code">Machine No <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_code" id="data_code" class="form-control rounded-0" required placeholder="Machine No." maxlength="20" value="<?= $data['code'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_name">Machine Name <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0" required placeholder="Machine Name" maxlength="150" value="<?= $data['name'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_spesifikasi">Specification</label>
                                        <input type="text" name="data_spesifikasi" id="data_spesifikasi" class="form-control rounded-0" placeholder="Machine Specification" value="<?= $data['specification'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_brand">Brand</label>
                                        <input type="text" name="data_brand" id="data_brand" class="form-control rounded-0" placeholder="Machine Brand" maxlength="150" value="<?= $data['brand'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="serial_no">Serial No</label>
                                        <input type="text" name="serial_no" id="serial_no" class="form-control rounded-0" placeholder="Serial No" maxlength="50" value="<?= $data['serial_no'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_tonnage">Tonnage</label>
                                        <select name="data_tonnage" id="data_tonnage" class="form-control select2 select2bs5" disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($tonnage as $tn): ?>
                                                <option <?= ($data['tonnage'] == $tn->id) ? 'selected' : '' ?> value="<?= $tn->id ?>"><?= $tn->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_rate">Machine Rate</label>
                                        <input type="text" name="data_rate" id="data_rate" class="form-control rounded-0" placeholder="Machine Rate" maxlength="20" value="<?= $data['rate'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="mfg_date">Mfg. Date</label>
                                        <input type="date" name="mfg_date" id="mfg_date" class="form-control rounded-0" value="<?= $data['mfg_date'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="purchase_date">Purchase Date</label>
                                        <input type="date" name="purchase_date" id="purchase_date" class="form-control rounded-0" value="<?= $data['purchase_date'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-12">
                                        <label class="form-label" for="data_remark">Remark</label>
                                        <textarea name="data_remark" id="data_remark" class="form-control summernote rounded-0" rows="3" placeholder="Remark"><?= $data['description'] ?></textarea>
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