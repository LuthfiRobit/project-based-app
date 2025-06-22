@extends('administration.layouts.app')

@section('this-page-style')
@endsection

@section('content')
    <!-- Content body start -->
    <div class="content-body default-height">
        <div class="container-fluid">
            <!-- Section Heading -->
            <div class="form-head mb-4 d-flex align-items-center gap-2">
                <small class="text-muted">Akademik -</small>
                <h4 class="text-dark fw-semibold mb-0">Sekolah</h4>
            </div>

            <!-- Section contain -->
            <div class="row">
                <div class="card">
                    <div class="card-header d-sm-flex d-block border-0 pb-0 flex-wrap">
                        <div class="pr-3 me-auto mb-sm-0 mb-3">
                            <h4 class="fs-20 text-black mb-1">Tambah Data Sekolah</h4>
                            <span class="fs-12">Silahkan lengkapi data sesuai ketentuan</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a href="{{ route('master.sekolah.index') }}"
                                class="btn btn-outline-primary btn-sm btn-rounded light" title="Kembali">
                                <i class="las la-arrow-left scale5 me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary">
                            <strong>Catatan:</strong> <br />
                            <span>Gunakan fitur ini untuk mengelola data sekolah dengan efisien. Anda dapat melakukan
                                hal-hal
                                berikut:</span>
                            <ul>
                                <li>Menambah data baru dengan mengisi formulir yang disediakan.</li>
                            </ul>
                        </div>
                        <form id="createForm" method="post" action="" class="form-sm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- First Row: Identitas Umum -->
                                <div class="col-md-6 mb-3">
                                    <label for="npsn" class="form-label">NPSN</label>
                                    <input type="text" class="form-control form-control-sm" id="npsn" name="npsn"
                                        maxlength="20" placeholder="Masukkan NPSN" aria-label="NPSN" required
                                        autocomplete="off" value="{{ old('npsn') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                                    <input type="text" class="form-control form-control-sm" id="nama_sekolah"
                                        name="nama_sekolah" placeholder="Masukkan nama sekolah" aria-label="Nama Sekolah"
                                        required autocomplete="off" value="{{ old('nama_sekolah') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jenjang" class="form-label">Jenjang</label>
                                    <select id="jenjang" name="jenjang"
                                        class="selectpicker form-control wide form-select-md" required
                                        aria-label="Pilih Jenjang" data-live-search="false" data-size="5"
                                        placeholder="Pilih Jenjang">
                                        <option value="" disabled selected>Pilih Jenjang</option>
                                        @foreach (['SD', 'MI', 'SMP', 'MTS', 'SMA', 'SMK', 'MA'] as $level)
                                            <option value="{{ $level }}"
                                                {{ old('jenjang') == $level ? 'selected' : '' }}>{{ $level }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr />

                            <h5>Alamat Sekolah</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control form-control-sm" id="alamat" name="alamat" rows="3"
                                        placeholder="Masukkan alamat" aria-label="Alamat">{{ old('alamat') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="desa_kelurahan" class="form-label">Desa/Kelurahan</label>
                                    <input type="text" class="form-control form-control-sm" id="desa_kelurahan"
                                        name="desa_kelurahan" placeholder="Masukkan desa/kelurahan"
                                        aria-label="Desa/Kelurahan" value="{{ old('desa_kelurahan') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <input type="text" class="form-control form-control-sm" id="kecamatan"
                                        name="kecamatan" placeholder="Masukkan kecamatan" aria-label="Kecamatan"
                                        value="{{ old('kecamatan') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kabupaten_kota" class="form-label">Kabupaten/Kota</label>
                                    <input type="text" class="form-control form-control-sm" id="kabupaten_kota"
                                        name="kabupaten_kota" placeholder="Masukkan kabupaten/kota"
                                        aria-label="Kabupaten/Kota" value="{{ old('kabupaten_kota') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control form-control-sm" id="provinsi"
                                        name="provinsi" placeholder="Masukkan provinsi" aria-label="Provinsi"
                                        value="{{ old('provinsi') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control form-control-sm" id="kode_pos"
                                        name="kode_pos" maxlength="10" placeholder="Masukkan kode pos"
                                        aria-label="Kode Pos" value="{{ old('kode_pos') }}" />
                                </div>
                            </div>

                            <hr />

                            <h5>Kontak Sekolah</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_telp" class="form-label">No Telepon</label>
                                    <input type="tel" class="form-control form-control-sm" id="no_telp"
                                        name="no_telp" maxlength="20" placeholder="Masukkan nomor telepon"
                                        aria-label="No Telepon" value="{{ old('no_telp') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="email"
                                        name="email" placeholder="Masukkan email" aria-label="Email"
                                        value="{{ old('email') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control form-control-sm" id="website"
                                        name="website" placeholder="Masukkan website" aria-label="Website"
                                        value="{{ old('website') }}" />
                                </div>
                            </div>

                            <hr />

                            <h5>Kepala Sekolah</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kepala_sekolah" class="form-label">Nama Kepala Sekolah</label>
                                    <input type="text" class="form-control form-control-sm" id="kepala_sekolah"
                                        name="kepala_sekolah" placeholder="Masukkan nama kepala sekolah"
                                        aria-label="Nama Kepala Sekolah" value="{{ old('kepala_sekolah') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nip_kepala_sekolah" class="form-label">NIP Kepala Sekolah</label>
                                    <input type="text" class="form-control form-control-sm" id="nip_kepala_sekolah"
                                        name="nip_kepala_sekolah" maxlength="30"
                                        placeholder="Masukkan NIP kepala sekolah" aria-label="NIP Kepala Sekolah"
                                        value="{{ old('nip_kepala_sekolah') }}" />
                                </div>
                            </div>

                            <hr />

                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo Sekolah (opsional)</label>
                                <input type="file" class="form-control form-control-sm" id="logo" name="logo"
                                    accept="image/*" aria-label="Upload Logo Sekolah" />
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="reset" class="btn btn-secondary">Batal</button>
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
    @include('administration.masters.guru.scripts.create-handler')
@endsection
