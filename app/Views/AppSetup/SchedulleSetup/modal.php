<div class="modal fade" id="daftarShift" tabindex="-1" aria-labelledby="daftarShiftLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title">Shift List for <span id="schedulle_name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalShift()"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center align-middle bg-secondary-subtle">Days</th>
                                <th class="text-center align-middle bg-secondary-subtle">Shift</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyShift"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-primary rounded-0" onclick="closeModalShift()">Close</button>
            </div>
        </div>
    </div>
</div>