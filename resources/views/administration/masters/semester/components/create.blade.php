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
                            <option value="" disabled selected>Pilih Tahun Pelajaran</option>
                            @foreach ($tahunPelajaranList as $tp)
                                <option value="{{ $tp->id_tahun_pelajaran }}">{{ $tp->nama_tahun_pelajaran }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nama_semester" class="form-label">Semester</label>
                        <select id="nama_semester" name="nama_semester"
                            class="selectpicker form-control wide form-select-md" data-live-search="false" required
                            aria-describedby="semester-feedback" aria-label="Pilih Semester">
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
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
