<!-- Modal Create Start-->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateLabel">Buat Data Baru</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalError" class="alert alert-danger d-none" role="alert"></div>
                <form id="createForm" method="post" class="form-sm">
                    <div class="mb-3">
                        <label for="tahun_pelajaran_id" class="form-label">Tahun Pelajaran</label>
                        <select id="tahun_pelajaran_id" name="tahun_pelajaran_id"
                            class="selectpicker form-control wide form-select-md" data-live-search="true" required
                            aria-describedby="tahun_pelajaran-feedback" aria-label="Pilih Tahun Pelajaran">
                            @foreach ($tahunPelajaranList as $tp)
                                <option value="{{ $tp->id_tahun_pelajaran }}">{{ $tp->nama_tahun_pelajaran }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nama_iuran" class="form-label">Nama Iuran</label>
                        <input type="text" class="form-control form-control-sm" id="nama_iuran" name="nama_iuran"
                            placeholder="Masukkan nama iuran" aria-label="Nama Iuran" maxlength="255" autocomplete="off"
                            required />
                    </div>

                    <div class="mb-3">
                        <label for="nominal_iuran" class="form-label">Nominal Iuran (Rp)</label>
                        <input type="number" class="form-control form-control-sm" id="nominal_iuran"
                            name="nominal_iuran" placeholder="Contoh: 15000" aria-label="Nominal Iuran" min="0"
                            autocomplete="off" required />
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="selectpicker form-control wide form-select-md"
                            data-live-search="false" required aria-describedby="instansi-feedback"
                            aria-label="Pilih Status">
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak aktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="createForm" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Create end -->
