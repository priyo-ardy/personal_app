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
                        <li class="breadcrumb-item">HR Setup</li>
                        <li class="breadcrumb-item">Employee Setup</li>
                        <li class="breadcrumb-item">Common Data</li>
                        <li class="breadcrumb-item active"><?= $title ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 clearfix">
                    <div class="card rounded-0">
                        <form id="formData">
                            <div class="card-header rounded-0">
                                <h3 class="card-title"><i class="bi bi-pencil-square me-2"></i>Form Data</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3 clearfix" style="display: none;">
                                    <input type="text" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                </div>
                                <div class="form-group mb-3 clearfix">
                                    <label class="form-label" for="data_code">Code</label>
                                    <input type="text" name="data_code" id="data_code" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Automatically generate after save">
                                </div>
                                <div class="form-group mb-3 clearfix">
                                    <label class="form-label" for="data_name">Name<strong class="text-danger fw-bolder">*</strong></label>
                                    <input type="text" name="data_name" id="data_name" class="form-control rounded-0" placeholder="Enter name" maxlength="150" required autofocus autocomplete="off">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="form-label" for="data_remark">Remark</label>
                                    <textarea name="data_remark" id="data_remark" class="form-control rounded-0" placeholder="Write additional information here ..."></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btnCancel" class="btn rounded-0 btn-secondary" title="Cancel"><i class="bi bi-arrow-counterclockwise me-2"></i>Cancel</button>
                                <div class="d-block float-end">
                                    <button type="button" hidden id="btnUpdate" class="btn rounded-0 btn-primary" title="Update"><i class="bi bi-floppy me-2"></i>Update</button>
                                    <button type="button" id="btnSave" class="btn rounded-0 btn-primary" title="Save"><i class="bi bi-floppy me-2"></i>Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 clearfix">
                    <div class="card rounded-0">
                        <div class="card-header rounded-0">
                            <h3 class="card-title"><i class="bi bi-list-ul me-2"></i>Uniform Type List</h3>
                            <div class="card-tools">
                                <button type="button" id="btnDelete" class="btn btn-tool text-black fw-bolder" title="Delete"><i class="bi bi-trash3"></i></button>
                                <button type="button" id="btnExport" class="btn btn-tool text-black fw-bolder" title="Export to excel"><i class="bi bi-download"></i></button>
                                <button type="button" id="btnRefresh" class="btn btn-tool text-black fw-bolder" title="Refresh"><i class="bi bi-arrow-repeat"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center bg-secondary-subtle">
                                                <input type="checkbox" id="select-all" class="form-check-input border-1 border-primary rounded-0">
                                            </th>
                                            <th class="align-middle text-center bg-secondary-subtle">Code</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Name</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Description</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>