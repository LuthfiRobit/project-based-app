<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Judul Halaman -->
    <title>
        Aplikasi Pembayaran Iuran Madrasah Ibtidaiyah Ihyauddiniyah Desa Kecik Besuk Probolinggo
    </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Meta begin -->
    <!-- Set Karakter -->
    <meta charset="utf-8" />
    <!-- Mode Rendering -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Penulis Halaman -->
    <meta name="author" content="Madrasah Ibtidaiyah Ihyauddiniyah" />
    <!-- Pengindeksan Mesin Pencari -->
    <meta name="robots" content="index, follow" />

    <!-- Kata Kunci SEO -->
    <meta name="keywords"
        content="Pembayaran Iuran, Madrasah Ibtidaiyah Ihyauddiniyah, Desa Kecik Besuk, Probolinggo, Aplikasi Pembayaran, Sistem Pembayaran Iuran, Pendidikan, Madrasah Ibtidaiyah, Manajemen Keuangan, Desain Responsif, UI Modern, Aplikasi Web, Sistem Administrasi, Pembayaran Mudah, Pengelolaan Iuran, Formulir Pembayaran, Pembayaran Online, Solusi Keuangan Madrasah" />

    <!-- Deskripsi Halaman -->
    <meta name="description"
        content="Aplikasi Pembayaran Iuran untuk Madrasah Ibtidaiyah Ihyauddiniyah di Desa Kecik Besuk Probolinggo. Aplikasi ini memudahkan proses pembayaran iuran, melacak transaksi keuangan, dan mengelola administrasi dengan desain responsif dan fitur yang user-friendly." />

    <!-- Metadata Open Graph -->
    <meta property="og:title"
        content="Aplikasi Pembayaran Iuran Madrasah Ibtidaiyah Ihyauddiniyah Desa Kecik Besuk Probolinggo" />
    <meta property="og:description"
        content="Aplikasi Pembayaran Iuran untuk Madrasah Ibtidaiyah Ihyauddiniyah di Desa Kecik Besuk Probolinggo. Aplikasi ini memudahkan proses pembayaran iuran, melacak transaksi keuangan, dan mengelola administrasi dengan desain responsif dan fitur yang user-friendly." />
    <meta property="og:image" content="{{ asset('templates/administration/social-image.png') }}" />

    <!-- Twitter Card Metadata -->
    <meta name="twitter:title"
        content="Aplikasi Pembayaran Iuran Madrasah Ibtidaiyah Ihyauddiniyah Desa Kecik Besuk Probolinggo" />
    <meta name="twitter:description"
        content="Aplikasi Pembayaran Iuran untuk Madrasah Ibtidaiyah Ihyauddiniyah di Desa Kecik Besuk Probolinggo. Aplikasi ini memudahkan proses pembayaran iuran, melacak transaksi keuangan, dan mengelola administrasi dengan desain responsif dan fitur yang user-friendly." />
    <meta name="twitter:image" content="{{ asset('templates/administration/social-image.png') }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <!-- Meta end -->

    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('templates/administration/images/logo_mi.png') }}" />

    @yield('this-page-style') <!-- Menyertakan Style tambahan dari halaman -->
    @include('administration.layouts.style')
    <!-- Global style start -->
    <link href="{{ asset('templates/administration/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}"
        rel="stylesheet" />
    {{-- <link class="main-css" href="http://payment-app.test/templates/administration/css/style.css" rel="stylesheet" /> --}}
    <!-- Global style end -->
    <link class="main-css" href="{{ asset('templates/administration/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Preloader start -->
    <div id="preloader">
        <!-- Bouncing animation container -->
        <div class="sk-three-bounce">
            <!-- Individual bouncing elements -->
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!-- Preloader end -->

    <!-- Main wrapper start-->
    <div id="main-wrapper">

        @include('administration.layouts.header') <!-- Memanggil header -->

        @include('administration.layouts.sidebar') <!-- Memanggil sidebar -->

        @yield('content') <!-- Konten spesifik halaman -->

        @include('administration.layouts.footer') <!-- Memanggil footer -->
    </div>
    <!-- Main wrapper end-->

    <!-- Global script start -->
    <script src="{{ asset('templates/administration/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('templates/administration/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('templates/administration/js/custom.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('administration.layouts.deznav') <!-- Digunakan karna default js tidak bisa load -->
    @include('administration.layouts.script')
    @include('scripts.globalHandler')
    <!-- Global script end -->

    <!-- Script theme mode start -->
    <script>
        jQuery(document).ready(function() {
            setTimeout(function() {
                dezSettingsOptions.version = "light";
                new dezSettings(dezSettingsOptions);
                setCookie("version", "light");
            }, 1500);
        });
    </script>
    <!-- Script theme mode end -->

    <!-- Script token start -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <!-- Script token end -->

    @yield('this-page-scripts') <!-- Menyertakan JS tambahan dari halaman -->
</body>

</html>
