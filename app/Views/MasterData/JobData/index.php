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
                        <li class="breadcrumb-item">HRIS</li>
                        <li class="breadcrumb-item">List of Employee</li>
                        <li class="breadcrumb-item active">Register New Employee</li>
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
                        <button type="button" class="btn shadow-none rounded-0 btn-light border-0" id="btnAdd" data-bs-toggle=" tooltip" data-bs-placement="top" title="Save">
                            <i class="bi bi-file-earmark-plus"></i>&ensp;Register Job Data
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light-order-0" id="btnFilter" data-bs-toggle=" tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-funnel"></i>&ensp;Filter
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light-order-0" id="btnRefresh" data-bs-toggle=" tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-repeat"></i>&ensp;Refresh
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light-order-0" id="btnDelete" data-bs-toggle=" tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-trash3"></i>&ensp;Delete
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light-order-0" id="btnExport" data-bs-toggle=" tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-download"></i>&ensp;Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="card rounded-0">
                        <div class="card-body rounded-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th class="bg-secondary-subtle text-center align-middle">
                                                <input type="checkbox" id="select-all" class="form-check-input rounded-0 border-1 border-primary">
                                            </th>
                                            <th class="bg-secondary-subtle text-center align-middle">Employee</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Position</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Dept</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Dept</th>
                                            <th class="bg-secondary-subtle text-center align-middle">NBHX Position</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Grade</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Rank</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Category</th>
                                            <th class="bg-secondary-subtle text-center align-middle">NBHX Category</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Allow Finger</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Allow Overtime</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Report To Position</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Report To</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Join Date</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Length of Service</th>
                                            <th class="bg-secondary-subtle text-center align-middle">On Job Position</th>
                                            <th class="bg-secondary-subtle text-center align-middle">On Job Length</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Action</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Reason</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Work Relationship</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Contract No</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Contract Duration</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Duration Type</th>
                                            <th class="bg-secondary-subtle text-center align-middle">End of Contract</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Remark</th>
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