<!-- Modal Import -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <!-- Form upload -->
                <form id="importForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fileInput" class="form-label fw-semibold">Pilih File Excel</label>
                        <input type="file" class="form-control" id="fileInput" name="fileInput" accept=".xlsx,.xls"
                            required>
                        <div class="form-text text-muted">
                            Format yang diterima: <code>.xlsx</code> atau <code>.xls</code>
                        </div>
                    </div>
                </form>

                <!-- Informasi penting -->
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle-fill me-2 fs-5 mt-1"></i>
                    <div>
                        <strong>Petunjuk:</strong> Pastikan Anda menggunakan <em>template Excel</em> yang sudah
                        disediakan agar data dapat terbaca dengan benar.
                        <br>
                        <a href="#" class="btn btn-sm btn-outline-primary mt-2" download>
                            <i class="bi bi-download me-1"></i> Unduh Template Excel
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="importForm" class="btn btn-primary">Import</button>
            </div>
        </div>
    </div>
</div>
