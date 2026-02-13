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
                        <li class="breadcrumb-item active">List of SPK</li>
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
                        <button type="button" id="btnAdd" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="New">
                            <i class="bi bi-file-earmark-plus"></i>&ensp;Add
                        </button>
                        <button type="button" id="btnFilter" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter">
                            <i class="bi bi-funnel"></i>&ensp;Filter
                        </button>
                        <button type="button" id="btnRefresh" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh">
                            <i class="bi bi-arrow-repeat"></i>&ensp;Refresh
                        </button>
                        <button type="button" id="btnDelete" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="bi bi-trash3"></i>&ensp;Delete
                        </button>
                        <button type="button" id="btnExport" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Export">
                            <i class="bi bi-download"></i>&ensp;Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12 clearfix">
                    <div class="card rounded-0 card-primary card-outline">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center bg-secondary-subtle">
                                                <input type="checkbox" id="select-all" class="form-check-input rounded-0 border-1 border-primary">
                                            </th>
                                            <th class="align-middle text-center bg-secondary-subtle">Code</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Name</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Specification</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Material Category</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Cust Part No</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Cust Part Name</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Color</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Workshop</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Material Property</th>
                                            <th class="align-middle text-center bg-secondary-subtle">UoM</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Shift Capacity</th>
                                            <th class="align-middle text-center bg-secondary-subtle">SQP</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Qty/Bag</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Net Weight</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Gross Weight</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Cavity</th>
                                            <th class="align-middle text-center bg-secondary-subtle">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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