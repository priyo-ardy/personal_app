<div class="modal fade" id="modalDocument" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalApproverLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title">APQP Document List | <span id="apqp_name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered" id="tableDocument">
                        <thead>
                            <tr>
                                <th class="align-middle text-center bg-secondary-subtle">Document Name</th>
                                <th class="align-middle text-center bg-secondary-subtle">Document Uploader</th>
                                <th class="align-middle text-center bg-secondary-subtle">Document Level</th>
                                <th class="align-middle text-center bg-secondary-subtle">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDocument"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" id="btnCloseDocument" class="btn btn-secondary rounded-0 me-2" data-bs-dismiss="modal" title="Close"><i class="bi bi-x"></i>&ensp;Close</button>
                <button type="button" id="btnSaveDocument" class="btn btn-primary rounded-0 me-2" data-bs-dismiss="modal" title="Close"><i class="bi bi-floppy"></i>&ensp;Save</button>
            </div>
        </div>
    </div>
</div>