<!-- Modal Detail Start -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Detail Guru</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <!-- FOTO DI KIRI & TEKS DI KANAN — KEDUANYA BERADA DI TENGAH -->
                <div class="row mb-4 justify-content-center gy-2">
                    <div class="col-auto d-flex align-items-center">
                        <img id="detail_foto" src="" alt="Foto Guru" class="img-thumbnail shadow-sm"
                            style="max-width: 120px;">
                    </div>
                    <div class="col-auto d-flex flex-column justify-content-center m">
                        <h5 id="detail_nama_guru" class="fw-bold mb-1"></h5>
                        <p class="mb-1"><strong>NIP:</strong> <span id="detail_nip"></span></p>
                        <p class="mb-0"><strong>Jabatan:</strong> <span id="detail_nama_jabatan"></span></p>
                    </div>
                </div>

                <!-- INFORMASI DETAIL -->
                <div class="row row-cols-1 row-cols-md-2 g-3">

                    <!-- Kolom Kiri -->
                    <div class="col">
                        <div class="border-bottom pb-2"><strong>Jenis Kelamin :</strong> <span
                                id="detail_jenis_kelamin"></span></div>
                        <div class="border-bottom pb-2"><strong>Tanggal Lahir :</strong> <span
                                id="detail_tanggal_lahir"></span></div>
                        <div class="border-bottom pb-2"><strong>Pendidikan Terakhir :</strong> <span
                                id="detail_pendidikan_terakhir"></span></div>
                        <div class="border-bottom pb-2"><strong>Status Guru :</strong> <span
                                id="detail_status_guru"></span></div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col">
                        <div class="border-bottom pb-2"><strong>Alamat :</strong> <span id="detail_alamat"></span>
                        </div>
                        <div class="border-bottom pb-2"><strong>No Telepon :</strong> <span
                                id="detail_no_telepon"></span></div>
                        <div class="border-bottom pb-2"><strong>Email :</strong> <span id="detail_email"></span>
                        </div>
                        <div class="border-bottom pb-2"><strong>Status Pernikahan :</strong> <span
                                id="detail_status_pernikahan"></span></div>
                        <div class="border-bottom pb-2"><strong>Tanggal Masuk :</strong> <span
                                id="detail_tanggal_masuk"></span></div>
                        <div class="pt-2"><strong>Status :</strong> <span id="detail_status"></span></div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Detail End -->
