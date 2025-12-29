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
                        <li class="breadcrumb-item">App Setup</li>
                        <li class="breadcrumb-item">Application Setting</li>
                        <li class="breadcrumb-item active">Site Setting</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2">
                <div class="col-8 mx-auto">
                    <div class="card rounded-0">
                        <form id="formData">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group mb-3 clearfix">
                                        <label class="form-label">Application Name <strong class="text-danger">*</strong></label>
                                        <input type="text" class="form-control rounded-0" placeholder="Application name" name="app_name" id="app_name" require autofocus autocomplete="off" maxlength="50">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-6 col-lg-6 col-md-12 col-sm-12 clearfix mb-3">
                                        <label class="form-label">Application Logo <strong class="text-danger">*</strong></label>
                                        <input type="file" name="app_logo" id="app_logo" class="form-control custom-file-input rounded-0" accept="image/*">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-6 col-lg-6 col-md-12 col-sm-12 clearfix mb-3">
                                        <label class="form-label">Favicon <strong class="text-danger">*</strong></label>
                                        <input type="file" name="app_icon" id="app_icon" class="form-control custom-file-input rounded-0" accept="image/*">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary rounded-0" title="Save">
                                    <i class="bi bi-floppy me-2"></i>Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>