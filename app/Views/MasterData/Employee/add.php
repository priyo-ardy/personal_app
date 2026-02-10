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
                <div class="row g-2 mb-3">
                    <div class="col-12 clearfix">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 bg-primary">
                                <h5 class="card-title text-white fw-bolder">Employee Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3 align-middle text-center">
                                                    <img src="<?= base_url() . 'img/no-foto.jpg' ?>" alt="employee_photo" id="img_preview" class="img-thumbnail rounded-0" width="250px" height="300px">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="input-group">
                                                    <div class="custom-file col-12">
                                                        <input type="file" class="form-control rounded-0 custom-file-input" id="fupload" name="fupload" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12">
                                        <div class="row g-2">
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_category">Category <strong class="text-danger fw-bolder">*</strong></label>
                                                <select name="data_category" id="data_category" class="form-control select2 select2bs5" required>
                                                    <option value="">-- Chose --</option>
                                                    <option value="0">Regular</option>
                                                    <option value="5">Support</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_nik">NIK <strong class="text-danger fw-bolder">*</strong></label>
                                                <input type="text" name="data_nik" id="data_nik" class="form-control rounded-0" required placeholder="Employee Code" maxlength="5" autocomplete="off">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_lokasi">Work Location <strong class="text-danger fw-bolder">*</strong></label>
                                                <select name="data_lokasi" id="data_lokasi" class="form-control select2 select2bs5" required>
                                                    <option value="">-- Chose --</option>
                                                    <?php foreach ($location as $loc): ?>
                                                        <option value="<?= $loc->id ?>"><?= $loc->name ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_name">Employee Name <strong class="text-danger fw-bolder">*</strong></label>
                                                <input type="text" name="data_name" id="data_name" class="form-control rounded-0" required placeholder="Employee Name" minlength="2" maxlength="150" autocomplete="off">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_ktp">KTP No / Identification ID <strong class="text-danger fw-bolder">*</strong></label>
                                                <input type="text" name="data_ktp" id="data_ktp" class="form-control rounded-0" required placeholder="Employee Name" minlength="3" maxlength="20" autocomplete="off">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_tempat_lahir">Place of Birth <strong class="text-danger fw-bolder">*</strong></label>
                                                <select name="data_tempat_lahir" id="data_tempat_lahir" class="form-control select2 select2bs5" required>
                                                    <option value="">-- Choose --</option>
                                                    <?php foreach ($tempat_lahir as $tl): ?>
                                                        <option value="<?= $tl->id ?>"><?= $tl->name ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_tgl_lahir">Date of Birth <srong class="text-danger fw-bolder">*</srong></label>
                                                <input type="date" name="data_tgl_lahir" id="data_tgl_lahir" class="form-control rounded-0" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_gender">Gender <strong class="text-danger fw-bolder">*</strong></label>
                                                <select name="data_gender" id="data_gender" class="form-control select2 select2bs5" required>
                                                    <option value="">-- Choose --</option>
                                                    <option value="L">Male</option>
                                                    <option value="P">Female</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_gol_darah">Blood Type</label>
                                                <select name="data_gol_darah" id="data_gol_darah" class="form-control select2 select2bs5">
                                                    <option value="">-- Choose --</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="O">O</option>
                                                    <option value="AB">AB</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_agama">Religion <strong class="text-danger fw-bolder"></strong></label>
                                                <select name="data_agama" required id="data_agama" class="form-control select2 select2bs5">
                                                    <option value="">-- Choose --</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Kristen">Kristen</option>
                                                    <option value="Katolik">Katolik</option>
                                                    <option value="Hindu">Hindu</option>
                                                    <option value="Budha">Budha</option>
                                                    <option value="Konghucu">Konghucu</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_email">Email Address <strong class="text-danger fw-bolder">*</strong></label>
                                                <input type="email" name="data_email" id="data_email" class="form-control rounded-0" required placeholder="Email Address" autocomplete="off">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_tlp_1">Primary Phone No. <sup><i class="bi bi-question-circle" title="Whatsapp Active"></i></sup><strong class="text-danger fw-bolder">*</strong></label>
                                                <input type="tel" name="data_tlp_1" id="data_tlp_1" class="form-control rounded-0" required placeholder="Primary Phone No." autocomplete="off" pattern="^[\+]?[1-9][\d]{0,15}$" data-pattern-message="Invalid phone number format">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_tlp_3">Secondary Phone No.</label>
                                                <input type="tel" name="data_tlp_2" id="data_tlp_2" class="form-control rounded-0" placeholder="Secondary Phone No." autocomplete="off" pattern="^[\+]?[1-9][\d]{0,15}$" data-pattern-message="Invalid phone number format">
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_tinggi">Height (cm)</label>
                                                <input type="number" name="data_tinggi" id="data_tinggi" class="form-control rounded-0" placeholder="Height" autocomplete="off">
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_berat">Weight (Kg)</label>
                                                <input type="number" name="data_berat" id="data_berat" class="form-control rounded-0" placeholder="Weight" autocomplete="off">
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix">
                                                <label class="form-label" for="data_join">Join Date <strong class="text-danger fw-bolder">*</strong></label>
                                                <input type="date" name="data_join" id="data_join" class="form-control rounded-0" required autocomplete="off">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Address -->
                    <div class="col-12 clearfix">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 bg-primary">
                                <h5 class="card-title text-white fw-bolder">Employee Address</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_alamat_ktp">Address Based on ID Card <strong class="text-danger fw-bolder">*</strong></label>
                                        <textarea name="data_alamat_ktp" id="data_alamat_ktp" class="form-control rounded-0" required placeholder="Current Address" autocomplete="off"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_provinsi_ktp">KTP Province <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_provinsi_ktp" id="data_provinsi_ktp" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($province as $row) : ?>
                                                <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_kota_ktp">KTP City <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_kota_ktp" id="data_kota_ktp" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>

                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_alamat_sekarang">Current Address <strong class="text-danger fw-bolder">*</strong></label>
                                        <textarea name="data_alamat_sekarang" id="data_alamat_sekarang" class="form-control rounded-0" required placeholder="Current Address" autocomplete="off"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_provinsi_sekarang">Current Province <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_provinsi_sekarang" id="data_provinsi_sekarang" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($province as $p) : ?>
                                                <option value="<?= $p->id; ?>"><?= $p->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_kota_sekarang">Current City <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_kota_sekarang" id="data_kota_sekarang" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_alamat_orang_tua">Parent Address <strong class="text-danger fw-bolder">*</strong></label>
                                        <textarea name="data_alamat_orang_tua" id="data_alamat_orang_tua" class="form-control rounded-0" required placeholder="Current Address" autocomplete="off"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_provinsi_orang_tua">Parent Province <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_provinsi_orang_tua" id="data_provinsi_orang_tua" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($province as $r) : ?>
                                                <option value="<?= $r->id; ?>"><?= $r->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_kota_orang_tua">Parent City <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_kota_orang_tua" id="data_kota_orang_tua" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Emergency Contact -->
                    <div class="col-12 clearfix">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 bg-primary">
                                <h5 class="card-title text-white fw-bolder">Emergency Contact</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-xl-4 col-lg-4 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label" for="data_nama_emergency">Emergency Contact Name <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" name="data_nama_emergency" id="data_nama_emergency" class="form-control rounded-0" required placeholder="Emergency Contact Name" autocomplete="off" minlength="2" maxlength="150">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label" for="data_relasi_emergency">Relationship <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_relasi_emergency" id="data_relasi_emergency" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($relasi as $rls): ?>
                                                <option value="<?= $rls->id ?>"><?= $rls->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label" for="data_tlp_emergency">Emergency Contact Phone No. <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="tel" name="data_tlp_emergency" id="data_tlp_emergency" class="form-control rounded-0" required placeholder="Emergency Contact Phone No." autocomplete="off" pattern="^[\+]?[1-9][\d]{0,15}$" data-pattern-message="Invalid phone number format">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-12 clearfix">
                                        <label class="form-label" for="data_alamat_emergency">Emergency Contact Address <strong class="text-danger fw-bolder">*</strong></label>
                                        <textarea name="data_alamat_emergency" id="data_alamat_emergency" class="form-control rounded-0" required placeholder="Emergency Contact Address" autocomplete="off"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Supporting Document -->
                    <div class="col-12 clearfix">
                        <div class="card rounded-0">
                            <div class="card-header bg-primary rounded-0">
                                <h5 class="card-title text-white fw-bolder">Supporting Document</h5>
                            </div>
                            <div class="card-body rounded-0">
                                <div class="row g-2">
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_npwp">Tax Registration No. (NPWP)</label>
                                        <input type="number" name="data_npwp" id="data_npwp" class="form-control rounded-0" placeholder="Tax Registration No. (NPWP)" autocomplete="off" maxlength="20">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_bpjs_tk">BPJS Tk. No</label>
                                        <input type="number" name="data_bpjs_tk" id="data_bpjs_tk" class="form-control rounded-0" placeholder="BPJS Ketenagan Kerjaan" autocomplete="off" maxlength="20">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_bpjs_kesehatan">BPJS Kesehatan No.</label>
                                        <input type="number" name="data_bpjs_kesehatan" id="data_bpjs_kesehatan" class="form-control rounded-0" placeholder="BPJS Kesehatan" autocomplete="off" maxlength="20">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_rekening">Bank Account No.</label>
                                        <input type="number" name="data_rekening" id="data_rekening" class="form-control rounded-0" placeholder="Bank Account No." autocomplete="off" maxlength="20">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_nama_rekening">Bank Account Name</label>
                                        <input type="text" name="data_nama_rekening" id="data_nama_rekening" class="form-control rounded-0" placeholder="Bank Account Name" autocomplete="off" maxlength="150">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_nama_bank">Bank Name</label>
                                        <input type="text" name="data_nama_bank" id="data_nama_bank" class="form-control rounded-0" placeholder="Bank Name" autocomplete="off" maxlength="150">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_bank_address">Bank Address</label>
                                        <input type="text" name="data_bank_address" id="data_bank_address" class="form-control rounded-0" placeholder="Bank Address" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 clearfix">
                        <div class="card rounded-0">
                            <div class="card-header bg-primary rounded-0">
                                <h5 class="card-title text-white fw-bolder">Employee Uniform</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_jenis_seragam">Uniform Type <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_jenis_seragam" id="data_jenis_seragam" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($jenis_seragam as $js): ?>
                                                <option value="<?= $js->id ?>"><?= $js->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_ukuran_seragam">Uniform Size <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_ukuran_seragam" id="data_ukuran_seragam" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($ukuran_seragam as $us): ?>
                                                <option value="<?= $us->id ?>"><?= $us->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_ukuran_sepatu">Shoes Size <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_ukuran_sepatu" id="data_ukuran_sepatu" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($ukuran_sepatu as $ss): ?>
                                                <option value="<?= $ss->id ?>"><?= $ss->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 clearfix">
                                        <label class="form-label" for="data_aksesoris">Accessories <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_aksesoris" id="data_aksesoris" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <option value="1">Topi Navy</option>
                                            <option value="2">Topi Kuning</option>
                                            <option value="3">Hijab</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class=" row mb-3">
                <div class="col-12">
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
</main>

<?= $this->endSection(); ?>


