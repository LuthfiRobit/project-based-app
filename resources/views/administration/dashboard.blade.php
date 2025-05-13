@extends('administration.layouts.app')

@section('this-page-style')
    <link rel="stylesheet" href="{{ asset('templates/administration/vendor/sweetalert2/dist/sweetalert2.min.css') }}">

    <link href="{{ asset('templates/administration/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('templates/administration/vendor/datatables/responsive/responsive.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!-- Content body start -->
    <div class="content-body default-height">
        <div class="container-fluid">
            <!-- Section heading -->
            <div class="form-head mb-4 d-flex justify-content-between align-items-center">
                <h4 class="text-black font-w600">Main | Dashboard</h4>
            </div>

        </div>
    </div>
    <!-- Content body end -->
@endsection

@section('this-page-scripts')
    <script src="{{ asset('templates/administration/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templates/administration/vendor/datatables/responsive/responsive.js') }}"></script>

    <!-- Apex Chart start -->
    <script src="{{ asset('templates/administration/vendor/apexchart/apexchart.js') }}"></script>
    <!-- Apex Chart end -->
@endsection
