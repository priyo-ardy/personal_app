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
                        <li class="breadcrumb-item">Base Data</li>
                        <li class="breadcrumb-item">List of Customer</li>
                        <li class="breadcrumb-item active">Add New Customer</li>
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
                        <form id="formData">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_code">Supplier Code</label>
                                        <input type="text" name="data_code" id="data_code" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Automatically generated after save" maxlength="20">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_category">Category <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_category" id="data_category" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($category as $row) : ?>
                                                <option value="<?= $row->id ?>"><?= $row->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_name">Supplier Name <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0" required placeholder="Supplier Name" maxlength="150" autocomplete="off">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-5 col-lg-5 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_address">Address</label>
                                        <input type="text" name="data_address" id="data_address" class="form-control rounded-0" placeholder="Supplier Address" autocomplete="off">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_email">Email Address</label>
                                        <input type="email" name="data_email" id="data_email" class="form-control rounded-0" placeholder="Email Address" maxlength="150" autocomplete="off">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_phone">Phone Number</label>
                                        <input type="number" name="data_phone" id="data_phone" class="form-control rounded-0" placeholder="Phone Number" maxlength="20" autocomplete="off">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_contact">Contact Person</label>
                                        <input type="text" name="data_contact" id="data_contact" class="form-control rounded-0" placeholder="Contact Person Name" autocomplete="off" maxlength="150">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="email_contact">Email Contact Person</label>
                                        <input type="email" name="email_contact" id="email_contact" class="form-control rounded-0" placeholder="Contact Person Email" autocomplete="off" maxlength="150">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="phone_contact">Email Contact Person</label>
                                        <input type="number" name="phone_contact" id="phone_contact" class="form-control rounded-0" placeholder="Contact Person Phone" autocomplete="off" maxlength="150">
                                    </div>
                                    <div class="form-group col-xl-9 col-lg-9 col-md-6 cool-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_remark">Remark</label>
                                        <textarea name="data_remark" id="data_remark" class="form-control" rows="1" placeholder="Remark"></textarea>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</main>

<?= $this->endSection(); ?>