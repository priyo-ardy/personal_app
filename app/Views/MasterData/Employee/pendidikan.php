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
                        <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnBack" data-bs-toggle="tooltip" data-bs-placement="top" title="Back">
                            <i class="bi bi-arrow-left"></i>&ensp;Add
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnSave" data-bs-toggle="tooltip" data-bs-placement="top" title="Save">
                            <i class="bi bi-floppy"></i>&ensp;Save
                        </button>
                        <button type="button" class="btn shadow-none rounded-0 btn-light-order-0 btnCancel" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                    </div>
                </div>
            </div>

            <form id="formData" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 bg-primary">
                                <h5 class="card-title text-white fw-bolder">Employee Data</h5>
                            </div>
                            <div class="card-body rounded-0">
                                <div class="row mb-3 g-2">
                                    <div class="form-group col-12 clearfix" style="display: none;">
                                        <input type="text" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $token; ?>">
                                    </div>
                                </div>
                                <div class="row g-2 mb-3 clearfix">
                                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12 clearfix">
                                        <label class="form-label" for="data_nik">NIK</label>
                                        <input type="text" id="data_nik" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $karyawan->nik; ?>">
                                    </div>
                                    <div class="form-group col-xl-10 col-xl-10 col-md-10 col-sm-12 clearfix">
                                        <label class="form-label" for="data_name">Employee Name</label>
                                        <input type="text" id="data_name" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $karyawan->name; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 bg-primary">
                                <h5 class="card-title text-white fw-bolder">Family Member Data</h5>
                            </div>
                            <div class="card-body rounded-0">
                                <div class="row mb-3 g-2">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle bg-secondary-subtle col-2">Degree</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-3">School Name</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-2">Major</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-2">Year of Graduation</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-2">Remark</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-1">#</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody">
                                                <tr>
                                                    <td class="align-middle">
                                                        <select name="data_degree[]" class="form-control select2 select2bs5" required>
                                                            <option value="">-- Choose --</option>
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="text" name="data_sekolah[]" class="form-control rounded-0" placeholder="School name" minlength="3" maxlength="150" required>
                                                        <div class="invalid-feedback"></div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="text" name="data_jurusan[]" class="form-control rounded-0" placeholder="Major" minlength="3" maxlength="150" required>
                                                        <div class="invalid-feedback"></div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="number" name="data_tahun_lulus[]" class="form-control rounded-0" placeholder="Year of graduation" minlength="3" maxlength="150" required>
                                                        <div class="invalid-feedback"></div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="text" name="data_remark[]" class="form-control rounded-0" placeholder="Additional information">
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <button type="button" class="btn btn-success btn-sm rounded-0" onclick="addRow()"><i class="bi bi-plus"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class=" row mb-3">
                <div class="col-12">
                    <div class="card rounded-0">
                        <div class="card-body rounded-0">
                            <div class="btn-group" role="group" aria-label="tooltip">
                                <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnBack" data-bs-toggle="tooltip" data-bs-placement="top" title="Back">
                                    <i class="bi bi-arrow-left"></i>&ensp;Add
                                </button>
                                <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnSave" data-bs-toggle="tooltip" data-bs-placement="top" title="Save">
                                    <i class="bi bi-floppy"></i>&ensp;Save
                                </button>
                                <button type="button" class="btn shadow-none rounded-0 btn-light border-0 btnCancel" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                                    <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>