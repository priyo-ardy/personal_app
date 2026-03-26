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
                        <li class="breadcrumb-item">RnD Setup</li>
                        <li class="breadcrumb-item">Docs. Flow Setup</li>
                        <li class="breadcrumb-item active">Docs. Flow Stages</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2">
                <div class="col-sm-12">
                    <div class="card rounded-0">
                        <div class="card-header rounded-0">APQP Document Stages Setup</div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="form-group col-sm-2 clearfix">
                                    <label class="form-label" for="data_type">Stage Type <strong class="text-danger">*</strong></label>
                                    <select name="data_type" id="data_type" class="form-select select2 select2bs5 rounded-0" required>
                                        <option value="document">Document</option>
                                        <option value="gateway">Gateway</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label" for="data_name">Stage Name</label>
                                    <input type="text" name="data_name" id="data_name" class="form-control rounded-0" placeholder="Stage Name" maxlength="150" autocomplete="off">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="form-label" for="data_document">APQP Document</label>
                                    <select name="data_document" id="data_document" class="form-select select2 select2bs5 rounded-0">
                                        <option value="">-- Choose Document --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>