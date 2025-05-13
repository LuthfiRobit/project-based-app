<?php

namespace App\Providers;

use App\Models\JabatanGuru;
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
        // Membagikan data jabatan, status guru, dan pendidikan terakhir ke view admin.index, admin.create, dan admin.edit
        View::composer(['administration.masters.guru.*'], function ($view) {
            // Mengambil data jabatan dan membagikannya ke view
            $jabatanList = JabatanGuru::getActive(); // Model JabatanGuru dengan method getActive()

            // Mengambil data dari file konfigurasi
            $statusGuruList = config('static-data.status_guru');
            $pendidikanTerakhirList = config('static-data.pendidikan_terakhir');

            // Membagikan data ke view
            $view->with('jabatanList', $jabatanList)
                ->with('statusGuruList', $statusGuruList)
                ->with('pendidikanTerakhirList', $pendidikanTerakhirList);
        });
    }
}
