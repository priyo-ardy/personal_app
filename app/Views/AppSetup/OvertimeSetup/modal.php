<div class="modal fade" id="modalRate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalRateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-0">
            <form id="formRate">
                <div class="modal-header">
                    <h5 class="modal-title">Setup Overtime Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalRate()"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 align-items-center" id="listRate"></div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal" onclick="closeModalRate()"><i class="bi bi-x"></i>&ensp;Close</button>
                    <button type="button" id="btnSave" class="btn btn-primary rounded-0"><i class="bi bi-floppy"></i>&ensp;Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditRate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditRateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <div class="modal-title">Edit Overtime Rate | <span id="modal_title"></span></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalEditRate()"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form id="formEditRate">
                        <input type="hidden" name="rate_token" id="rate_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                        <table class="table table-striped table-hover" id="tableRate">
                            <thead>
                                <tr>
                                    <th class="bg-secondary-subtle text-center align-middle col-2">Hours</th>
                                    <th class="bg-secondary-subtle text-center align-middle col-7">Rate</th>
                                    <th class="bg-secondary-subtle text-center align-middle col-3">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyRate"></tbody>
                        </table>
                    </form>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal" onclick="closeModalEditRate()"><i class="bi bi-x"></i>&ensp;Close</button>
                <button type="button" id="btnUpdate" class="btn btn-primary rounded-0"><i class="bi bi-floppy"></i>&ensp;Update</button>
            </div>
        </div>
    </div>
</div>