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
                            <span class="fs-12">Anda bisa memfilter berdasarkan status</span>
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
                            <a href="javascript:void(0)" class="btn btn-rounded btn-outline-primary light btn-sm"
                                data-bs-toggle="modal" data-bs-target="#modalCreate" title="Create">
                                <i class="las la-plus scale5 me-1"></i>Buat
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary">
                            <strong>Catatan:</strong> <br />
                            <span>Gunakan fitur ini untuk mengelola iuran siswa dengan efisien. Anda dapat melakukan hal-hal
                                berikut:</span>
                            <ul>
                                <li>Menambah iuran baru dengan mengisi formulir yang disediakan.</li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <button class="btn-update-status btn btn-sm btn-primary fs-sm-8 fs-lg-6" data-status="active">
                                <i class="las la-check-circle fs-4"></i> Aktifkan
                            </button>
                            <button class="btn-update-status btn btn-sm btn-danger fs-sm-8 fs-lg-6" data-status="inactive">
                                <i class="las la-times-circle fs-4"></i> Nonaktifkan
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-sm  align-middle table-striped gs-0 gy-2 nowrap"
                                style="width:100%;">
                                <thead>
                                    <tr class="text-center text-muted text-uppercase">
                                        <th class="w-10">
                                            <input type="checkbox" class="form-check-input" id="selectAll"> *
                                        </th>
                                        <th class="w-10">Actions</th>
                                        <th class="w-65">Jabatan</th>
                                        <th class="w-15">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-800 fw-bolder fs-sm-8 fs-lg-6">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content body end -->

    @include('administration.masters.jabatanGuru.components.create')
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


    @include('administration.masters.jabatanGuru.scripts.list')
    @include('administration.masters.jabatanGuru.scripts.action')
    @include('administration.masters.jabatanGuru.scripts.store')
    @include('administration.masters.jabatanGuru.scripts.update')
@endsection
