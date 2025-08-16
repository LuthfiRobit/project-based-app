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
                        <label for="nama_ruang_kelas" class="form-label">Nama Ruang Kelas</label>
                        <input type="text" class="form-control form-control-sm" id="nama_ruang_kelas"
                            name="nama_ruang_kelas" maxlength="20" placeholder="Contoh: 7A" required>
                    </div>
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
                        <label for="tingkat_id" class="form-label">Tingkat</label>
                        <select id="tingkat_id" name="tingkat_id" class="selectpicker form-control wide form-select-md"
                            data-live-search="true" data-size="5" required aria-describedby="tingkat-feedback"
                            aria-label="Pilih Tingkat">
                            <option value="" disabled selected>Pilih Tingkat</option>
                            @foreach ($tingkatList as $tingkat)
                                <option value="{{ $tingkat->id_tingkat }}">{{ $tingkat->nama_tingkat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan_id" class="form-label">Jurusan</label>
                        <select id="jurusan_id" name="jurusan_id" class="selectpicker form-control wide form-select-md"
                            data-live-search="true" data-size="5" aria-describedby="jurusan-feedback"
                            aria-label="Pilih Jurusan (opsional)">
                            <option value="" selected>Tanpa Jurusan</option>
                            @foreach ($jurusanList as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="wali_kelas_id" class="form-label">Wali Kelas</label>
                        <select id="wali_kelas_id" name="wali_kelas_id"
                            class="selectpicker form-control wide form-select-md" data-live-search="true"
                            aria-describedby="wali-kelas-feedback" data-size="5"
                            aria-label="Pilih Wali Kelas (opsional)">
                            <option value="" selected>Belum ditentukan</option>
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                            @endforeach
                        </select>
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
