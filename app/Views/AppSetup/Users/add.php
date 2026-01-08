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
                        <li class="breadcrumb-item">User Management</li>
                        <li class="breadcrumb-item active">List of Users</li>
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
                    <div class="card rounded-0 card-primary card-outline">
                        <form id="formData">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 mb-3 clearfix">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <img src="<?= base_url() . 'img/default.png' ?>" class="img img-responsive img-thumbnail img-fluid" alt="Profile Picture">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 mb-3 clearfix">
                                        <div class="row g-2">
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix mb-3">
                                                <label class="form-label" for="data_username">Username <strong class="text-danger">*</strong></label>
                                                <input type="text" name="data_username" id="data_user_name" class="form-control rounded-0" maxlength="20" autofocus autocomplete="off" placeholder="Username" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix mb-3">
                                                <label class="form-label" for="data_fullname">Full Name <strong class="text-danger">*</strong></label>
                                                <input type="text" name="data_fullname" id="data_full_name" class="form-control rounded-0" maxlength="150" autocomplete="off" placeholder="Full Name" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix mb-3">
                                                <label class="form-label" for="data_email">Email Address <strong class="text-danger">*</strong></label>
                                                <input type="email" name="data_email" id="data_email" class="form-control rounded-0" maxlength="150" autocomplete="off" placeholder="Email Address" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix mb-3">
                                                <label class="form-label" for="data_phone">Phone Number <strong class="text-danger">*</strong></label>
                                                <input type="number" name="data_phone" id="data_phone" class="form-control rounded-0" maxlength="20" autocomplete="off" placeholder="Phone Number" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix mb-3">
                                                <label class="form-label" for="data_password">Password <strong class="text-danger">*</strong></label>
                                                <input type="password" name="data_password" id="data_password" class="form-control rounded-0" minlength="8" maxlength="20" autocomplete="off" placeholder="Password" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix mb-3">
                                                <label class="form-label" for="data_level">User Level <strong class="text-danger">*</strong></label>
                                                <select name="data_level" id="data_level" class="form-select select2 select2bs5 rounded-0" required>
                                                    <option value="">-- Choose User Level --</option>
                                                    <option value="superadmin">Super Administrator</option>
                                                    <option value="administrator">Administrator</option>
                                                    <option value="manager">Manager</option>
                                                    <option value="supervisor">Supervisor</option>
                                                    <option value="leader">Leader</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="user">User</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-6 mb-3 clearfix">
                                                <label class="form-label" for="data_remark">Remark</label>
                                                <textarea name="data_remark" id="data_remark" class="form-control summernote rounded-0" rows="3" placeholder="Remark"></textarea>
                                            </div>
                                        </div>
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