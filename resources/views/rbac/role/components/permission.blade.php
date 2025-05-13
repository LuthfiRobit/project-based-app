<!-- Modal Permission Start-->
<div class="modal modal-xl fade" id="modalPermission" tabindex="-1" role="dialog" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPermissionLabel">Permission Data</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="permissionForm" method="post" class="form" data-id="">
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <div class="row">
                                <div class="col-6"><span class="fs-6 fw-bold">Nama Role</span></div>
                                <div class="col-6"><span class="fs-6 fw-bold">:</span> <span id="detail_role_name"
                                        class="mb-0"></span></div>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="row">
                                <div class="col-6"><span class="fs-6 fw-bold">Deskripsi Role</span></div>
                                <div class="col-6"><span class="fs-6 fw-bold">:</span> <span
                                        id="detail_role_description" class="mb-0"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12"><span class="fs-6 fw-bold">Permissions</span></div>
                        </div>
                        {{-- <div id="permissions_list" class="row mt-2">
                            <div class="col-4 mb-2">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="check2" value="">
                                    <label class="form-check-label" for="check2">master-guru-index</label>
                                </div>
                            </div>
                        </div> --}}
                        <div id="permissions_list" class="row row-gap-1 column-gap-0 justify-content-center">
                            <!-- Checkbox columns will be injected here -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="permissionForm" id="savePermissions"
                    class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Permission end -->
