<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Master\GuruController;
use App\Http\Controllers\Master\JabatanGuruController;
use App\Http\Controllers\RBAC\PermissionController;
use App\Http\Controllers\RBAC\RoleController;
use App\Http\Controllers\RBAC\UserController;
use App\Http\Controllers\System\LogActivityController;
use App\Http\Controllers\System\PermissionSyncController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckPermission;


// Public routes
Route::get('/', fn() => view('welcome'));
Route::get('login', [AuthController::class, 'loginView'])->name('login.view');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (auth + permission check by route name)
Route::middleware(['auth', 'checkPermission'])->group(function () {
    Route::get('dashboard', fn() => view('administration.dashboard'))->name('dashboard');

    // Master
    Route::prefix('master')->name('master.')->group(function () {

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
