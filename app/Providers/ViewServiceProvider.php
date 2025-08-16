<?php

namespace App\Providers;

use App\Models\Guru;
use App\Models\JabatanGuru;
use App\Models\Jurusan;
use App\Models\TahunPelajaran;
use App\Models\Tingkat;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Menambahkan View Composer
        View::composer(['administration.masters.guru.*'], function ($view) {
            $jabatanList = JabatanGuru::getActive();
            $statusGuruList = config('static-data.status_guru');
            $pendidikanTerakhirList = config('static-data.pendidikan_terakhir');
            $view->with('jabatanList', $jabatanList)
                ->with('statusGuruList', $statusGuruList)
                ->with('pendidikanTerakhirList', $pendidikanTerakhirList);
        });

        View::composer(['administration.masters.*'], function ($view) {
            $jenjangPendidikanList = config('static-data.jenjang_pendidikan');
            $view->with([
                'jenjangPendidikanList' => $jenjangPendidikanList,
            ]);
        });

        View::composer(['administration.masters.semester.*'], function ($view) {
            $tahunPelajaranList = TahunPelajaran::select('id_tahun_pelajaran', 'nama_tahun_pelajaran')->orderBy('created_at', 'DESC')->get();
            $view->with('tahunPelajaranList', $tahunPelajaranList);
        });

        View::composer(['administration.masters.ruangKelas.*'], function ($view) {
            $tahunPelajaranList = TahunPelajaran::select('id_tahun_pelajaran', 'nama_tahun_pelajaran')->orderBy('created_at', 'DESC')->get();
            $tingkatList = Tingkat::select('id_tingkat', 'nama_tingkat')->orderBy('created_at', 'DESC')->get();
            $jurusanList = Jurusan::select('id_jurusan', 'nama_jurusan')->orderBy('created_at', 'DESC')->get();
            $guruList = Guru::select('id_guru', 'nama_guru')->orderBy('created_at', 'DESC')->get();
            $view->with('tahunPelajaranList', $tahunPelajaranList)
                ->with('tingkatList', $tingkatList)
                ->with('jurusanList', $jurusanList)
                ->with('guruList', $guruList);
        });

        View::composer(['administration.masters.iuran.*'], function ($view) {
            $tahunPelajaranList = TahunPelajaran::select('id_tahun_pelajaran', 'nama_tahun_pelajaran')->orderBy('created_at', 'DESC')->get();
            $view->with('tahunPelajaranList', $tahunPelajaranList);
        });
    }
}
