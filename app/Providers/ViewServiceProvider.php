<?php

namespace App\Providers;

use App\Models\JabatanGuru;
use App\Models\TahunPelajaran;
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

        View::composer(['administration.masters.iuran.*'], function ($view) {
            $tahunPelajaranList = TahunPelajaran::select('id_tahun_pelajaran', 'nama_tahun_pelajaran')->orderBy('created_at', 'DESC')->get();
            $view->with('tahunPelajaranList', $tahunPelajaranList);
        });
    }
}
