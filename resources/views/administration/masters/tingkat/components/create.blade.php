<!-- Modal Create Tingkat Start -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateLabel">Buat Data Baru </h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalError" class="alert alert-danger d-none" role="alert"></div>
                <form id="createForm" method="post" class="form-sm">
                    <div class="mb-3">
                        <label for="nama_tingkat" class="form-label">Nama Tingkat</label>
                        <input type="text" class="form-control form-control-sm" id="nama_tingkat" name="nama_tingkat"
                            placeholder="Contoh: 1, 12, X, XII" aria-label="Nama Tingkat" maxlength="10"
                            autocomplete="off" required />
                    </div>
                    <div class="mb-3">
                        <label for="jenjang" class="form-label">Jenjang Pendidikan</label>
                        <select id="jenjang" name="jenjang" class="selectpicker form-control wide form-select-md"
                            data-live-search="false" required aria-describedby="instansi-feedback"
                            aria-label="Pilih Jenjang">
                            @foreach ($jenjangPendidikanList as $singkatan => $singkatan)
                                <option value="{{ $singkatan }}">{{ $singkatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="selectpicker form-control wide form-select-md"
                            data-live-search="false" required aria-describedby="instansi-feedback"
                            aria-label="Pilih Status">
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Tidak aktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="createForm" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Create Tingkat End -->
