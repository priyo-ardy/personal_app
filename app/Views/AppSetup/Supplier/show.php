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
                        <li class="breadcrumb-item">List of Supplier</li>
                        <li class="breadcrumb-item">Edit</li>
                        <li class="breadcrumb-item active"><?= $supplier['name'] ?></li>
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
                                    <div class="form-group col-12">
                                        <input type="text" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" hidden value="<?= $supplier['token'] ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_code">Supplier Code</label>
                                        <input type="text" name="data_code" id="data_code" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Automatically generated after save" maxlength="20" value="<?= $supplier['code'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_name">Supplier Name <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0" required placeholder="Supplier Name" maxlength="150" autocomplete="off" value="<?= $supplier['name'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-7 col-lg-7 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_address">Address</label>
                                        <input type="text" name="data_address" id="data_address" class="form-control rounded-0" placeholder="Supplier Address" autocomplete="off" value="<?= $supplier['address'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_email">Email Address</label>
                                        <input type="email" name="data_email" id="data_email" class="form-control rounded-0" placeholder="Email Address" maxlength="150" autocomplete="off" value="<?= $supplier['email'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_phone">Phone Number</label>
                                        <input type="number" name="data_phone" id="data_phone" class="form-control rounded-0" placeholder="Phone Number" maxlength="20" autocomplete="off" value="<?= $supplier['phone'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_contact_person">Contact Person</label>
                                        <input type="text" name="data_contact_person" id="data_contact_person" class="form-control rounded-0" placeholder="Contact Person Name" autocomplete="off" maxlength="150" value="<?= $supplier['contact_person'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_contact_person_email">Email Contact Person</label>
                                        <input type="email" name="data_contact_person_email" id="data_contact_person_email" class="form-control rounded-0" placeholder="Contact Person Email" autocomplete="off" maxlength="150" value="<?= $supplier['contact_person_email'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_contact_person_phone">Contact Person Phone No.</label>
                                        <input type="number" name="data_contact_person_phone" id="data_contact_person_phone" class="form-control rounded-0" placeholder="Contact Person Phone" autocomplete="off" maxlength="150" value="<?= $supplier['contact_person_phone'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_npwp_no">Tax Registration No</label>
                                        <input type="number" name="data_npwp_no" id="data_npwp_no" class="form-control rounded-0" placeholder="Tax Registration No" autocomplete="off" maxlength="20" value="<?= $supplier['npwp_no'] ?>" disabled>
                                    </div>
                                    <div class="for-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_bank_name">Bank Name</label>
                                        <input type="text" name="data_bank_name" id="data_bank_name" class="form-control rounded-0" placeholder="Bank Name" autocomplete="off" maxlength="150" value="<?= $supplier['bank_name'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_bank_account_no">Bank Account No.</label>
                                        <input type="number" name="data_bank_account_no" id="data_bank_account_no" class="form-control rounded-0" placeholder="Bank Account No." autocomplete="off" maxlength="20" value="<?= $supplier['bank_account_no'] ?>" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_bank_account_name">Bank Account Name</label>
                                        <input type="text" name="data_bank_account_name" id="data_bank_account_name" class="form-control rounded-0" placeholder="Bank Account Name" autocomplete="off" maxlength="150" value="<?= $supplier['bank_account_name'] ?>" disabled>
                                    </div>
                                    <div class=" form-group col-xl-9 col-lg-9 col-md-6 cool-sm-12 clearfix">
                                        <label class="form-label" for="data_remark">Remark</label>
                                        <textarea name="data_remark" id="data_remark" class="form-control rounded-0" rows="1" placeholder="Remark" disabled><?= $supplier['description'] ?></textarea>
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