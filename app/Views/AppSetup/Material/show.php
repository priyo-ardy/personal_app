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
                        <li class="breadcrumb-item">List of Material</li>
                        <li class="breadcrumb-item">Edit Material</li>
                        <li class="breadcrumb-item active"><?= $material['code'] ?></li>
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
                        <form id="formData" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-12" style="display: none;">
                                        <input type="text" name="data_token" id="data_token" class="form-control form-control-lg rounded-0 bg-secondary-subtle" readonly value="<?= $material['token'] ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_category">Material Category <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_category" id="data_category" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($category as $ctg): ?>
                                                <option <?= ($material['category'] == $ctg->id) ? 'selected' : '' ?> value="<?= $ctg->id ?>"><?= $ctg->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_code">Material Code <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_code" id="data_code" class="form-control rounded-0" required placeholder="Material Code" maxlength="150" autocomplete="off" value="<?= $material['code'] ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_name">Material Name <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0" required placeholder="Material Name" maxlength="150" autocomplete="off" value="<?= $material['name'] ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_specification">Specification <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_specification" id="data_specification" maxlength="255" class="form-control rounded-0" required placeholder="Material Specification" autocomplete="off" value="<?= $material['specification'] ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_cust_part_no">Customer Part No.</label>
                                        <input type="text" name="data_cust_part_no" id="data_cust_part_no" class="form-control rounded-0" placeholder="Customer Part No." maxlength="255" autocomplete="off" value="<?= $material['cust_part_no'] ?>">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_cust_part_name">Customer Part Name</label>
                                        <input type="text" name="data_cust_part_name" id="data_cust_part_name" class="form-control rounded-0" placeholder="Customer Part Name" maxlength="255" autocomplete="off" value="<?= $material['cust_part_name'] ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_color">Material Color</label>
                                        <input type="text" name="data_color" id="data_color" class="form-control rounded-0" placeholder="Material Color" maxlength="50" autocomplete="off" value="<?= $material['color'] ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_workshop">Workshop <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_workshop" id="data_workshop" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($workshop as $ws): ?>
                                                <option <?= ($material['workshop'] == $ws->id) ? 'selected' : '' ?> value="<?= $ws->id ?>"><?= $ws->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_property">Material Property <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_property" id="data_property" class="form-control select2 select2bs5" required>
                                            <option <?= ($material['property'] == '') ? 'selected' : '' ?> value="">-- Choose --</option>
                                            <option <?= ($material['property'] == 'purchase') ? 'selected' : '' ?> value="purchase">Purchase</option>
                                            <option <?= ($material['property'] == 'selfmade') ? 'selected' : '' ?> value="selfmade">Self Made</option>
                                            <option <?= ($material['property'] == 'subcontract') ? 'selected' : '' ?> value="subcontract">Subcontract</option>
                                            <option <?= ($material['property'] == 'configure') ? 'selected' : '' ?> value="configure">Configure</option>
                                            <option <?= ($material['property'] == 'asset') ? 'selected' : '' ?> value="asset">Asset</option>
                                            <option <?= ($material['property'] == 'feature') ? 'selected' : '' ?> value="feature">Feature</option>
                                            <option <?= ($material['property'] == 'expense') ? 'selected' : '' ?> value="expense">Expense</option>
                                            <option <?= ($material['property'] == 'virtual') ? 'selected' : '' ?> value="virtual">Virtual</option>
                                            <option <?= ($material['property'] == 'service') ? 'selected' : '' ?> value="service">Service</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-1 col-lg-1 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_uom">UOM <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_uom" id="data_uom" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($uom as $u): ?>
                                                <option <?= ($material['uom'] == $u->id) ? 'selected' : '' ?> value="<?= $u->id ?>"><?= $u->symbol ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-1 col-lg-1 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_shift_capacity">Shift Capacity</label>
                                        <input type="number" step="1" name="data_shift_capacity" id="data_shift_capacity" class="form-control rounded-0" placeholder="Shift Capacity" autocomplete="off" value="<?= $material['shift_capacity'] ?>">
                                    </div>
                                    <div class="form-group col-xl-1 col-lg-1 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_spq">SPQ</label>
                                        <input type="number" step="1" name="data_spq" id="data_spq" class="form-control rounded-0" placeholder="Shift Capacity" autocomplete="off" value="<?= $material['spq'] ?>">
                                    </div>
                                    <div class="form-group col-xl-1 col-lg-1 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_qty_per_bag">Qty/Bag</label>
                                        <input type="number" step="1" name="data_qty_per_bag" id="data_qty_per_bag" class="form-control rounded-0" placeholder="Qty/Bag" autocomplete="off" value="<?= $material['qty_per_bag'] ?>">
                                    </div>
                                    <div class="form-group col-xl-1 col-lg-1 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_net_weight">Net Weight</label>
                                        <input type="number" step="0.01" name="data_net_weight" id="data_net_weight" class="form-control rounded-0" placeholder="Net Weight" autocomplete="off" value="<?= $material['net_weight'] ?>">
                                    </div>
                                    <div class="form-group col-xl-1 col-lg-1 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_gross_weight">Gross Weight</label>
                                        <input type="number" step="0.01" name="data_gross_weight" id="data_gross_weight" class="form-control rounded-0" placeholder="Gross Weight" autocomplete="off" value="<?= $material['gross_weight'] ?>">
                                    </div>
                                    <div class="form-group col-xl-1 col-lg-1 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_cavity">Cavity</label>
                                        <input type="number" step="0.01" name="data_cavity" id="data_cavity" class="form-control rounded-0" placeholder="Cavity" autocomplete="off" value="<?= $material['cavity'] ?>">
                                    </div>
                                    <div class="form-group col-xl-5 col-lg-5 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="fupload">Material Image</label>
                                        <input type="file" name="fupload" id="fupload" class="form-control rounded-0 form-control-file" placeholder="Material Image" autocomplete="off" accept="image/*">
                                    </div>
                                    <div class="form-group col-12 clearfix">
                                        <label class="form-label" for="data_description">Remark</label>
                                        <textarea name="data_description" id="data_description" class=" form-control rounded-0" placeholder="Additional information"><?= $material['description'] ?></textarea>
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