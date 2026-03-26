<?= $this->extend('Template/Admin/layout'); ?>

<?= $this->section('content'); ?>

<style>
    /* CSS sedikit disesuaikan untuk Canvas G6 */
    #flow-network {
        width: 100%;
        height: 700px;
        border: 1px solid #ddd;
        background-color: #fafafa;
        border-radius: 8px;
        overflow: hidden;
        /* Canvas G6 akan menangani scroll/zoom sendiri */
    }
</style>

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
                        <li class="breadcrumb-item active">Document Flow Setup</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 clearfix">
                    <div class="card rounded-0">
                        <form id="formData">
                            <div class="card-header rounded-0">
                                <h3 class="card-title"><i class="bi bi-pencil-square me-2"></i>Form Data</h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label" for="child_id">1. Pilih Dokumen yang Ingin Diatur Alurnya (Tujuan):</label>
                                        <select name="child_id" id="child_id" class="form-select select2 select2bs5" required>
                                            <option value="">-- Choose Child Document --</option>
                                            <?php foreach ($documents as $doc): ?>
                                                <option value="<?= $doc->id ?>"><?= $doc->document_name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-6 col-lg-6 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label">2. Pilih Dokumen Syarat (Parent): <i class="bi bi-question-circle" data-tooltip="tooltip" data-placement="top" title="Ceklis dokumen apa saja yang WAJIB DIUPLOAD sebelum dokumen di atas terbuka. Jika ini dokumen pertama, kosongkan saja."></i></label>
                                        <select name="parent_id[]" id="parent_id" class="form-select select2 select2bs5" multiple size="5">
                                            <option value="">-- Choose Parent Document --</option>
                                            <?php foreach ($documents as $doc): ?>
                                                <option value="<?= $doc->id ?>"><?= $doc->document_name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class=" card-footer">
                                <button type="button" id="btnCancel" class="btn rounded-0 btn-secondary" title="Cancel"><i class="bi bi-arrow-counterclockwise me-2"></i>Cancel</button>
                                <div class="d-block float-end">
                                    <button type="button" hidden id="btnUpdate" class="btn rounded-0 btn-primary" title="Update"><i class="bi bi-floppy me-2"></i>Update</button>
                                    <button type="button" id="btnSave" class="btn rounded-0 btn-primary" title="Save"><i class="bi bi-floppy me-2"></i>Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 clearfix">
                    <div class="card rounded-0">
                        <div class="card-header rounded-0">
                            <h3 class="card-title"><i class="bi bi-list-ul me-2"></i>APQP Document Flow List</h3>
                            <div class="card-tools">
                                <button id="refreshFlow" class="btn btn-tool" title="Refresh"><i class="bi bi-arrow-repeat"></i></button>
                            </div>
                        </div>
                        <div class="card-body" id="flow-network">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>