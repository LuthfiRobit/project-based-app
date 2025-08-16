<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Master\GuruController;
use App\Http\Controllers\Master\IuranController;
use App\Http\Controllers\Master\JabatanGuruController;
use App\Http\Controllers\Master\JurusanController;
use App\Http\Controllers\Master\KeringananController;
use App\Http\Controllers\Master\RuangKelasController;
use App\Http\Controllers\Master\SekolahController;
use App\Http\Controllers\Master\SemesterController;
use App\Http\Controllers\Master\SiswaController;
use App\Http\Controllers\Master\TahunPelajaranController;
use App\Http\Controllers\Master\TingkatController;
use App\Http\Controllers\RBAC\PermissionController;
use App\Http\Controllers\RBAC\RoleController;
use App\Http\Controllers\RBAC\UserController;
use App\Http\Controllers\System\LogActivityController;
use App\Http\Controllers\System\PermissionSyncController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckPermission;


// Public routes
Route::get('/', fn() => view('welcome'));

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'loginView'])->name('login.view');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected routes (auth + permission check by route name)
Route::middleware(['auth', 'checkPermission'])->group(function () {
    Route::get('dashboard', fn() => view('administration.dashboard'))->name('dashboard');

    // Master
    Route::prefix('master')->name('master.')->group(function () {
        Route::prefix('sekolah')->name('sekolah.')->group(function () {
            Route::get('/', [SekolahController::class, 'index'])->name('index');
        });

        Route::prefix('tingkat')->name('tingkat.')->group(function () {
            Route::get('/', [TingkatController::class, 'index'])->name('index');
            Route::get('/list', [TingkatController::class, 'list'])->name('list');
            Route::post('/store', [TingkatController::class, 'store'])->name('store');
            Route::get('/show/{id}', [TingkatController::class, 'show'])->name('show');
            Route::put('/update/{id}', [TingkatController::class, 'update'])->name('update');
            Route::post('/update-status-multiple', [TingkatController::class, 'updateStatusMultiple'])->name('update-status-multiple');
        });

        Route::prefix('jurusan')->name('jurusan.')->group(function () {
            Route::get('/', [JurusanController::class, 'index'])->name('index');
            Route::get('/list', [JurusanController::class, 'list'])->name('list');
            Route::post('/store', [JurusanController::class, 'store'])->name('store');
            Route::get('/show/{id}', [JurusanController::class, 'show'])->name('show');
            Route::put('/update/{id}', [JurusanController::class, 'update'])->name('update');
            Route::post('/update-status-multiple', [JurusanController::class, 'updateStatusMultiple'])->name('update-status-multiple');
        });

        Route::prefix('ruang-kelas')->name('ruang-kelas.')->group(function () {
            Route::get('/', [RuangKelasController::class, 'index'])->name('index');
            Route::get('/list', [RuangKelasController::class, 'list'])->name('list');
            Route::post('/store', [RuangKelasController::class, 'store'])->name('store');
            Route::get('/show/{id}', [RuangKelasController::class, 'show'])->name('show');
            Route::put('/update/{id}', [RuangKelasController::class, 'update'])->name('update');
            Route::post('/update-status-multiple', [RuangKelasController::class, 'updateStatusMultiple'])->name('update-status-multiple');
        });

        Route::prefix('jabatan-guru')->name('jabatan-guru.')->group(function () {
            Route::get('/', [JabatanGuruController::class, 'index'])->name('index');
            Route::get('/list', [JabatanGuruController::class, 'list'])->name('list');
            Route::post('/store', [JabatanGuruController::class, 'store'])->name('store');
            Route::get('/show/{id}', [JabatanGuruController::class, 'show'])->name('show');
            Route::put('/update/{id}', [JabatanGuruController::class, 'update'])->name('update');
            Route::post('/update-status-multiple', [JabatanGuruController::class, 'updateStatusMultiple'])->name('update-status-multiple');
            Route::post('/import-excel', [JabatanGuruController::class, 'importExcel'])->name('import-excel');
        });

        Route::prefix('guru')->name('guru.')->group(function () {
            Route::get('/', [GuruController::class, 'index'])->name('index');
            Route::get('/list', [GuruController::class, 'list'])->name('list');
            Route::get('/create', [GuruController::class, 'create'])->name('create');
            Route::post('/store', [GuruController::class, 'store'])->name('store');
            Route::get('/show/{id}', [GuruController::class, 'show'])->name('show');
            Route::get('/edit/{id}', [GuruController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [GuruController::class, 'update'])->name('update');
        });

        Route::prefix('tahun-pelajaran')->name('tahun-pelajaran.')->group(function () {
            Route::get('/', [TahunPelajaranController::class, 'index'])->name('index');
            Route::get('/list', [TahunPelajaranController::class, 'list'])->name('list');
            Route::post('/store', [TahunPelajaranController::class, 'store'])->name('store');
            Route::get('/show/{id}', [TahunPelajaranController::class, 'show'])->name('show');
            Route::put('/update/{id}', [TahunPelajaranController::class, 'update'])->name('update');
            Route::post('/update-status-single', [TahunPelajaranController::class, 'updateStatusSingle'])->name('update-status-single');
        });

        Route::prefix('semester')->name('semester.')->group(function () {
            Route::get('/', [SemesterController::class, 'index'])->name('index');
            Route::get('/list', [SemesterController::class, 'list'])->name('list');
            Route::post('/store', [SemesterController::class, 'store'])->name('store');
            Route::get('/show/{id}', [SemesterController::class, 'show'])->name('show');
            Route::put('/update/{id}', [SemesterController::class, 'update'])->name('update');
            Route::post('/update-status-single', [SemesterController::class, 'updateStatusSingle'])->name('update-status-single');
        });

        Route::prefix('iuran')->name('iuran.')->group(function () {
            Route::get('/', [IuranController::class, 'index'])->name('index');
            Route::get('/list', [IuranController::class, 'list'])->name('list');
            Route::post('/store', [IuranController::class, 'store'])->name('store');
            Route::get('/show/{id}', [IuranController::class, 'show'])->name('show');
            Route::put('/update/{id}', [IuranController::class, 'update'])->name('update');
            Route::post('/update-status-multiple', [IuranController::class, 'updateStatusMultiple'])->name('update-status-multiple');
            // Route::post('/import-excel', [IuranController::class, 'importExcel'])->name('import-excel');
        });

        Route::prefix('keringanan')->name('keringanan.')->group(function () {
            Route::get('/', [KeringananController::class, 'index'])->name('index');
            Route::get('/list', [KeringananController::class, 'list'])->name('list');
            Route::post('/store', [KeringananController::class, 'store'])->name('store');
            Route::get('/show/{id}', [KeringananController::class, 'show'])->name('show');
            Route::put('/update/{id}', [KeringananController::class, 'update'])->name('update');
            Route::post('/update-status-multiple', [KeringananController::class, 'updateStatusMultiple'])->name('update-status-multiple');
            // Route::post('/import-excel', [IuranController::class, 'importExcel'])->name('import-excel');
        });

        Route::prefix('siswa')->name('siswa.')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('index');
        });
    });

    // System
    Route::prefix('system')->name('system.')->group(function () {

        Route::prefix('log-activity')->name('log-activity.')->group(function () {
            Route::get('/', [LogActivityController::class, 'index'])->name('index');
            Route::get('/list', [LogActivityController::class, 'list'])->name('list');
            Route::delete('/clear', [LogActivityController::class, 'clear'])->name('clear');
        });
    });

    // RBAC
    Route::prefix('rbac')->name('rbac.')->group(function () {

        Route::prefix('role')->name('role.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/list', [RoleController::class, 'list'])->name('list');
            Route::post('/store', [RoleController::class, 'store'])->name('store');
            Route::get('/show/{id}', [RoleController::class, 'show'])->name('show');
            Route::put('/update/{id}', [RoleController::class, 'update'])->name('update');
            Route::get('/list-role-permission/{id}', [RoleController::class, 'listRolePermission'])->name('list-role-permission');
            Route::post('/store-role-permission/{id}', [RoleController::class, 'storeRolePermission'])->name('store-role-permission');
        });

        Route::prefix('permission')->name('permission.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::get('/list', [PermissionController::class, 'list'])->name('list');
            Route::post('/sync', [PermissionSyncController::class, 'sync'])->name('sync');
            Route::post('/store', [PermissionController::class, 'store'])->name('store');
            Route::get('/show/{id}', [PermissionController::class, 'show'])->name('show');
            Route::put('/update/{id}', [PermissionController::class, 'update'])->name('update');
        });

        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/list', [UserController::class, 'list'])->name('list');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/show/{id}', [UserController::class, 'show'])->name('show');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
            Route::post('/update-status', [UserController::class, 'updateStatus'])->name('update-status');
            Route::post('/update-status-multiple', [UserController::class, 'updateStatusMultiple'])->name('update-status-multiple');
            Route::get('/list-user-role/{id}', [UserController::class, 'listUserRole'])->name('list-user-role');
            Route::post('/store-user-role/{id}', [UserController::class, 'storeUserRole'])->name('store-user-role');
        });
    });
});
