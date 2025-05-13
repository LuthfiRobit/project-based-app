@extends('administration.layouts.app')

@section('this-page-style')
@endsection

@section('content')
    <!-- Content body start -->
    <div class="content-body default-height">
        <div class="container-fluid">
            <!-- Section heading -->
            <div class="form-head mb-4 d-flex justify-content-between align-items-center">
                <h4 class="text-black font-w600">Master | Guru</h4>
            </div>

            <!-- Section contain -->
            <div class="row">
                <div class="card">
                    <div class="card-header d-sm-flex d-block border-0 pb-0 flex-wrap">
                        <div class="pr-3 me-auto mb-sm-0 mb-3">
                            <h4 class="fs-20 text-black mb-1">Tambah Data</h4>
                            <span class="fs-12">Silahkan lengkapi data sesuai ketentuan</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a href="{{ route('master.guru.index') }}"
                                class="btn btn-outline-primary btn-sm btn-rounded light" title="Kembali">
                                <i class="las la-arrow-left scale5 me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary">
                            <strong>Catatan:</strong> <br />
                            <span>Gunakan fitur ini untuk mengelola data guru dengan efisien. Anda dapat melakukan hal-hal
                                berikut:</span>
                            <ul>
                                <li>Menambah data baru dengan mengisi formulir yang disediakan.</li>
                            </ul>
                        </div>
                        <form id="createForm" method="post" class="form-sm" enctype="multipart/form-data">
                            <div class="row">
                                <!-- First Row: Basic Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="nama_guru" class="form-label">Nama Guru</label>
                                    <input type="text" class="form-control form-control-sm" id="nama_guru"
                                        name="nama_guru" placeholder="Masukkan nama guru" aria-label="Nama Guru"
                                        maxlength="100" autocomplete="off" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" class="form-control form-control-sm" id="nip" name="nip"
                                        placeholder="Masukkan NIP (Opsional)" aria-label="NIP" maxlength="15"
                                        autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jabatan_id" class="form-label">Jabatan</label>
                                    <select id="jabatan_id" name="jabatan_id"
                                        class="selectpicker form-control wide form-select-md" data-live-search="true"
                                        required aria-label="Pilih Jabatan" data-size="5" placeholder="Pilih Jabatan">
                                        @foreach ($jabatanList as $item)
                                            <option value="{{ $item->id_jabatan }}">{{ $item->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin"
                                        class="selectpicker form-control wide form-select-md" required
                                        aria-label="Pilih Jenis Kelamin">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control form-control-sm" id="tanggal_lahir"
                                        name="tanggal_lahir" aria-label="Tanggal Lahir" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status_pernikahan" class="form-label">Status Pernikahan</label>
                                    <select id="status_pernikahan" name="status_pernikahan"
                                        class="selectpicker form-control wide form-select-md" required
                                        aria-label="Pilih Status Pernikahan">
                                        <option value="Lajang">Lajang</option>
                                        <option value="Menikah">Menikah</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Second Row: Contact and Other Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control form-control-sm" id="alamat" name="alamat" rows="3"
                                        placeholder="Masukkan alamat" aria-label="Alamat Guru"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="no_telepon" class="form-label">No Telepon</label>
                                    <input type="tel" class="form-control form-control-sm" id="no_telepon"
                                        name="no_telepon" placeholder="Masukkan nomor telepon" aria-label="No Telepon"
                                        maxlength="15" autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="email"
                                        name="email" placeholder="Masukkan email" aria-label="Email"
                                        autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                                    <select id="pendidikan_terakhir" name="pendidikan_terakhir"
                                        class="selectpicker form-control wide form-select-md" data-live-search="true"
                                        required aria-label="Pilih Pendidikan" data-size="5"
                                        placeholder="Pilih Pendidikan">
                                        @foreach ($pendidikanTerakhirList as $singkatan => $kepanjangan)
                                            <option value="{{ $singkatan }}">{{ $kepanjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status_guru" class="form-label">Status Guru</label>
                                    <select id="status_guru" name="status_guru"
                                        class="selectpicker form-control wide form-select-md" data-live-search="true"
                                        required aria-label="Pilih Status Guru" data-size="5"
                                        placeholder="Pilih Status Guru">
                                        @foreach ($statusGuruList as $singkatan => $kepanjangan)
                                            <option value="{{ $singkatan }}">{{ $kepanjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                                    <input type="date" class="form-control form-control-sm" id="tanggal_masuk"
                                        name="tanggal_masuk" aria-label="Tanggal Masuk" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control form-control-sm" id="foto"
                                        name="foto" accept="image/*" aria-label="Upload Foto" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" name="status"
                                        class="selectpicker form-control wide form-select-md" data-live-search="false"
                                        required aria-label="Pilih Status">
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Tidak aktif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary">Batal</button>
                                <button type="submit" form="createForm" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content body end -->
@endsection

@section('this-page-scripts')
    @include('administration.masters.guru.scripts.store')
@endsection
