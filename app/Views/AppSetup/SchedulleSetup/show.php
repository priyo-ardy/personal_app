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
                        <li class="breadcrumb-item">HR Setup</li>
                        <li class="breadcrumb-item">Time & Labor Setup</li>
                        <li class="breadcrumb-item">Schedulle Setup</li>
                        <li class="breadcrumb-item">Show</li>
                        <li class="breadcrumb-item active"><?= $data['name'] ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group" role="group" aria-label="tooltip" style="position: sticky; top: 0; z-index: 999;">
                        <button type="button" id="btnBack" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Back">
                            <i class="bi bi-arrow-left"></i>&ensp;Back
                        </button>
                        <button type="button" id="btnEdit" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class="bi bi-pencil-square"></i>&ensp;Edit
                        </button>
                        <button type="button" hidden id="btnUpdate" onclick="updateData()" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Update">
                            <i class="bi bi-floppy"></i>&ensp;Update
                        </button>
                        <button type="button" hidden id="btnCancel" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                        <button type="button" id="btnAdd" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Add">
                            <i class="bi bi-file-earmark-plus"></i>&ensp;Add
                        </button>
                        <button type="button" id="btnDelete" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="bi bi-trash3"></i>&ensp;Delete
                        </button>
                        <button type="button" id="btnPrev" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Previous">
                            <i class="bi bi-chevron-double-left"></i>&ensp;Prev
                        </button>
                        <button type="button" id="btnNext" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Next">
                            Next&ensp;<i class="bi bi-chevron-double-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <form id="formData">
                <div class="row g-2 mb-3">
                    <div class="col-12 clearfix">
                        <div class="card rounded-0 card-primary card-outline">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-12" style="display: none;">
                                        <input tye="text" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= $data['token'] ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_code">Code</label>
                                        <input type="text" name="data_code" id="data_code" class="form-control rounded-0 bg-secondary-subtle text-primary fw-bolder" readonly placeholder="Automatic generate after save the document" value="<?= $data['code'] ?>">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_name">Schedulle Name <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0" placeholder="Schedulle name" autocomplete="off" minlength="2" maxlength="150" autofocus required value="<?= $data['name'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_hari">Total Day Period <strong class="text-danger">*</strong></label>
                                        <input type="number" name="data_hari" id="data_hari" class="form-control rounded-0" placeholder="Total day period" autocomplete="off" required value="<?= $data['total_day'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="effective_date">Effective Date <strong class="text-danger">*</strong></label>
                                        <input type="date" name="effective_date" id="effective_date" class="form-control rounded-0" autocomplete="off" required value="<?= $data['effective_date'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_remark">remark</label>
                                        <input type="text" name="data_remark" id="data_remark" class="form-control rounded-0" placeholder="Remark" autocomplete="off" value="<?= $data['remark'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btnGenerate" disabled class="btn btn-success rounded-0" title="Generate Shift Table"><i class="bi bi-gear"></i>&ensp;Generate Shift Table</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="align-middle bg-secondary-subtle text-center col-2">Day</th>
                                                <th class="align-middle bg-secondary-subtle text-center col-7">Shift</th>
                                                <th class="align-middle bg-secondary-subtle text-center col-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="shiftList">
                                            <?php
                                            $no = 1;
                                            $hari = 0;
                                            ?>
                                            <?php foreach ($details as $index => $row): ?>
                                                <tr>
                                                    <td class="align-middle label-hari"><label class="fw-bolder <?= $hari === 5 || $hari === 6 ? 'text-danger' : ''; ?>">Days - <?= $no++ ?> (<?= $label_hari[$index] ?>)</label></td>
                                                    <td>
                                                        <select name="shift[]" class="form-control select2 select2bs5" required disabled>
                                                            <option value="">-- Choose</option>
                                                            <?php foreach ($shift as $r): ?>
                                                                <option <?= ($row['shift_token'] == $r->id) ? 'selected' : ''; ?> value="<?= $r->id ?>"><?= $r->name; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary rounded-0 btn-sm" onclick="addRow()" disabled>Add Row</button>
                                                        <button type="button" class="btn btn-success rounded-0 btn-sm" onclick="insertRow(this)" disabled>Insert Row</button>
                                                        <button type="button" class="btn btn-danger rounded-0 btn-sm" onclick="deleteRow(this)" disabled>Delete Row</button>
                                                    </td>
                                                </tr>
                                                <?php
                                                $hari++;
                                                if ($hari > 6) {
                                                    $hari = 0;
                                                }
                                                ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<div id="daftarShift" style="display: none;">
    <?php foreach ($shift as $s): ?>
        <option value="<?= $s->id ?>"><?= "$s->code - $s->name" ?></option>
    <?php endforeach; ?>
</div>

<?= $this->endSection(); ?>