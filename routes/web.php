<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Master\GuruController;
use App\Http\Controllers\Master\JabatanGuruController;
use App\Http\Controllers\RBAC\PermissionController;
use App\Http\Controllers\RBAC\RoleController;
use App\Http\Controllers\System\LogActivityController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckPermission;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'loginView'])->name('login.view');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('dashboard', function () {
    return view('administration.dashboard');
})->name('dashboard')->middleware(['auth', 'checkPermission:dashboard-view']); //view

Route::prefix('master')->name('master.')->group(function (): void {

    Route::prefix('jabatan-guru')->name('jabatan-guru.')->group(function (): void {
        Route::get('/', [JabatanGuruController::class, 'index'])->name('index')->middleware('checkPermission:master-jabatan-guru-index'); //view
        Route::get('/list', [JabatanGuruController::class, 'list'])->name('list')->middleware('checkPermission:master-jabatan-guru-list'); // json datatable
        Route::post('/store', [JabatanGuruController::class, 'store'])->name('store')->middleware('checkPermission:master-jabatan-guru-store'); //json
        Route::get('/show/{id}', [JabatanGuruController::class, 'show'])->name('show')->middleware('checkPermission:master-jabatan-guru-show'); //json
        Route::put('/update/{id}', [JabatanGuruController::class, 'update'])->name('update')->middleware('checkPermission:master-jabatan-guru-update');
        Route::post('/update-status-multiple', [JabatanGuruController::class, 'updateStatusMultiple'])->name('update-status-multiple')->middleware('checkPermission:master-jabatan-guru-update-status-multiple'); //json
    });

    Route::prefix('guru')->name('guru.')->middleware('auth')->group(function (): void {
        Route::get('/', [GuruController::class, 'index'])->name('index')->middleware('checkPermission:master-guru-index'); //view
        Route::get('/list', [GuruController::class, 'list'])->name('list')->middleware('checkPermission:master-guru-list'); //json datatable
        Route::get('/create', [GuruController::class, 'create'])->name('create')->middleware('checkPermission:master-guru-create'); //view
        Route::post('/store', [GuruController::class, 'store'])->name('store')->middleware('checkPermission:master-guru-store'); //json
        Route::get('/show/{id}', [GuruController::class, 'show'])->name('show')->middleware('checkPermission:master-guru-show'); //json
        Route::get('/edit/{id}', [GuruController::class, 'edit'])->name('edit')->middleware('checkPermission:master-guru-edit'); //view
        Route::put('/update/{id}', [GuruController::class, 'update'])->name('update')->middleware('checkPermission:master-guru-update'); //json
    });
});

Route::prefix('system')->name('system.')->group(function (): void {

    Route::prefix('log-activity')->name('log-activity.')->group(function (): void {
        Route::get('/', [LogActivityController::class, 'index'])->name('index')->middleware('checkPermission:system-log-activity-index'); //view
        Route::get('/list', [LogActivityController::class, 'list'])->name('list')->middleware('checkPermission:system-log-activity-list'); //json
        Route::delete('/clear', [LogActivityController::class, 'clear'])->name('clear')->middleware('checkPermission:system-log-activity-clear'); //json
    });
});

Route::prefix('rbac')->name('rbac.')->group(function (): void {

    Route::prefix('role')->name('role.')->group(function (): void {
        Route::get('/', [RoleController::class, 'index'])->name('index')->middleware('checkPermission:rbac-role-index'); //view
        Route::get('/list', [RoleController::class, 'list'])->name('list')->middleware('checkPermission:rbac-role-list'); //json
        Route::post('/store', [RoleController::class, 'store'])->name('store')->middleware('checkPermission:rbac-role-store'); //json
        Route::get('/show/{id}', [RoleController::class, 'show'])->name('show')->middleware('checkPermission:rbac-role-show'); //json
        Route::put('/update/{id}', [RoleController::class, 'update'])->name('update')->middleware('checkPermission:rbac-role-update'); //json
        Route::get('/list-role-permission/{id}', [RoleController::class, 'listRolePermission'])->name('list-role-permission')->middleware('checkPermission:rbac-role-list-role-permission'); //json
        Route::post('/store-role-permission/{id}', [RoleController::class, 'storeRolePermission'])->name('store-role-permission')->middleware('checkPermission:rbac-role-store-role-permission');
    });

    Route::prefix('permission')->name('permission.')->group(function (): void {
        Route::get('/', [PermissionController::class, 'index'])->name('index')->middleware('checkPermission:rbac-permission-index'); //view
        Route::get('/list', [PermissionController::class, 'list'])->name('list')->middleware('checkPermission:rbac-permission-list'); //json
        Route::post('/store', [PermissionController::class, 'store'])->name('store')->middleware('checkPermission:rbac-permission-store'); //json
        Route::get('/show/{id}', [PermissionController::class, 'show'])->name('show')->middleware('checkPermission:rbac-permission-show'); //json
        Route::put('/update/{id}', [PermissionController::class, 'update'])->name('update')->middleware('checkPermission:rbac-permission-update'); //json
    });
});
