@extends('administration.layouts.app')

@section('this-page-style')
    {{-- <link rel="stylesheet" href="{{ asset('templates/assets/plugins/datatables/dataTables.bootstrap5.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('templates/assets/plugins/datatables/responsive.bootstrap.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('templates/assets/plugins/datatables/buttons.dataTables.min.css') }}"> --}}


    <link href="{{ asset('templates/administration/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('templates/administration/vendor/datatables/responsive/responsive.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!-- Content body start -->
    <div class="content-body default-height">
        <div class="container-fluid">
            <!-- Section heading -->
            <div class="form-head mb-4 d-flex justify-content-between align-items-center">
                <h4 class="text-black font-w600">Master | Jabatan Guru</h4>
            </div>

            <!-- Section contain -->
            <div class="row">
                <div class="card">
                    <div class="card-header d-sm-flex d-block border-0 pb-0 flex-wrap">
                        <div class="pr-3 me-auto mb-sm-0 mb-3">
                            <h4 class="fs-20 text-black mb-1">List Jabatan Guru</h4>
                            <span class="fs-12 text-muted">Kelola jabatan guru, filter status, tambah data baru, dan ekspor
                                data.</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <div class="">
                                <select id="filter_status" class="selectpicker form-control wide form-select-md"
                                    data-live-search="false" aria-describedby="instansi-feedback" placeholder="Pilih status"
                                    required>
                                    <option value="">Semua</option>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak aktif</option>
                                </select>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#modalCreate">
                                <i class="las la-plus me-1"></i>Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- Aksi Tambahan -->
                        <div class="row mb-3 gy-2">
                            <div class="col-12 col-md d-flex flex-wrap gap-2">
                                <button class="btn-update-status btn btn-sm btn-primary" data-status="active">
                                    <i class="las la-check-circle me-1"></i>Aktifkan
                                </button>
                                <button class="btn-update-status btn btn-sm btn-danger" data-status="inactive">
                                    <i class="las la-times-circle me-1"></i>Nonaktifkan
                                </button>
                            </div>
                            <div class="col-12 col-md-auto d-flex flex-wrap gap-2 justify-content-md-end">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#modalImport">
                                    <i class="las la-file-excel me-1"></i>Import
                                </button>
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="las la-file-excel me-1"></i>Export
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="example" class="table table-sm align-middle table-striped gs-0 gy-2 nowrap"
                                style="width:100%;">
                                <thead>
                                    <tr class="text-center text-muted text-uppercase">
                                        <th style="width: 5%;" class="align-middle">
                                            <span class="d-inline-flex align-items-center gap-1">
                                                <input type="checkbox" class="form-check-input m-0" id="selectAll" />
                                                <i class="bi bi-info-circle-fill text-primary" data-bs-toggle="tooltip"
                                                    title="Pilih beberapa data pada halaman ini untuk melakukan aksi massal."></i>
                                            </span>
                                        </th>
                                        <th style="width: 10%;" class="align-middle">Aksi</th>
                                        <th style="width: 65%;" class="text-start align-middle">Jabatan</th>
                                        <th style="width: 10%;" class="align-middle">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-800 fw-bolder fs-sm-8 fs-lg-6">
                                    <!-- Data dinamis disini -->
                                </tbody>
                            </table>

                        </div>
                        <div class="alert alert-primary mt-3">
                            <strong>Catatan:</strong> Fitur ini digunakan untuk mengelola jabatan guru. Anda dapat
                            menambahkan, mengubah, menonaktifkan, atau menghapus jabatan sesuai kebutuhan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content body end -->

    @include('administration.masters.jabatanGuru.components.create')
    @include('administration.masters.jabatanGuru.components.import')
    @include('administration.masters.jabatanGuru.components.detail')
    @include('administration.masters.jabatanGuru.components.edit')
@endsection

@section('this-page-scripts')
    {{-- <script src="{{ asset('templates/administration/vendor/datatables/js/jquery.dataTables.min.js') }}"></script> --}}
    <script src="{{ asset('templates/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templates/administration/vendor/datatables/responsive/responsive.js') }}"></script>

    <script src="{{ asset('templates/assets/plugins/datatables/lodash.min.js') }}"></script>
    {{-- <script src="{{ asset('templates/assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('templates/assets/plugins/datatables/dataTables.colReorder.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('templates/assets/plugins/datatables/dataTables.responsive.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('templates/assets/plugins/datatables/dataTables.buttons.min.js') }}"></script> --}}

    {{-- <script src="{{ asset('templates/assets/plugins/datatables/vfs_fonts.js') }}"></script> --}}
    {{-- <script src="{{ asset('templates/assets/plugins/datatables/buttons.html5.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('templates/assets/plugins/datatables/jszip.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('templates/assets/plugins/datatables/buttons.colVis.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('templates/assets/plugins/datatables/print.js') }}"></script> --}}
    {{-- <script src="{{ asset('templates/assets/plugins/datatables/responsive.bootstrap.min.js') }}"></script> --}}


    @include('administration.masters.jabatanGuru.scripts.datatable-init')
    @include('administration.masters.jabatanGuru.scripts.modal-create-handler')
    @include('administration.masters.jabatanGuru.scripts.modal-edit-handler')
    @include('administration.masters.jabatanGuru.scripts.modal-import-handler')
    @include('administration.masters.jabatanGuru.scripts.status-update-handler')
    @include('administration.masters.jabatanGuru.scripts.actions-handler')
@endsection
