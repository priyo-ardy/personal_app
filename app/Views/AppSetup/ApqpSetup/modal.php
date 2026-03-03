<div class="modal fade" id="modalDocument" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDocumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title">APQP Document List | <span id="apqp_name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalDocument()"></button>
            </div>
            <div class="modal-body">
                <form id="formDocument">
                    <div class="form-group">
                        <input type="hidden" name="data_apqp_token" id="data_apqp_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                    </div>
                    <div class=" table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="tableDocument">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center bg-secondary-subtle col-4">Document Name</th>
                                    <th class="align-middle text-center bg-secondary-subtle col-3">Document Uploader</th>
                                    <th class="align-middle text-center bg-secondary-subtle col-2">Document Level</th>
                                    <th class="align-middle text-center bg-secondary-subtle col-3">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyDocument"></tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" id="btnCloseDocument" class="btn btn-secondary rounded-0 me-2" onclick="closeModalDocument()" data-bs-dismiss="modal" title="Close"><i class="bi bi-x"></i>&ensp;Close</button>
                <button type="button" id="btnSaveDocument" class="btn btn-primary rounded-0 me-2"><i class="bi bi-floppy"></i>&ensp;Save</button>
            </div>
        </div>
    </div>
</div>

<div class="fade modal" id="modalApprover" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalApproverLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title">Approver List | <span id="approver_title"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalApprover()"></button>
            </div>
            <div class="modal-body">
                <form id="formApprover">
                    <div class="row g-2 mb-3">
                        <input type="text" name="approver_token" id="approver_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                    </div>
                    <div class="row g-2">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="tableApprover">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle bg-secondary-subtle col-9">Approver Name</th>
                                        <th class="text-center align-middle bg-secondary-subtle col-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="approverList"></tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" id="btnCloseApprover" class="btn btn-secondary rounded-0 me-2" onclick="closeModalApprover()" data-bs-dismiss="modal" title="Close"><i class="bi bi-x"></i>&ensp;Close</button>
                <button type="button" id="btnSaveApprover" class="btn btn-primary rounded-0 me-2"><i class="bi bi-floppy"></i>&ensp;Save</button>
            </div>
        </div>
    </div>
</div>