<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\RapatController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes

|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/absen/{kode_unik}/{id_komisi}', [AbsenController::class, 'absen'])->name('absen');
Route::post('/absen/{kode_unik}/{id_komisi}', [AbsenController::class, 'kehadiran'])->name('hadir');

Route::middleware(['auth', 'role:admin|pimpinan|keuangan|anggota'])->group(function () {
    Route::get('/', function () {
        return view('content.dashboard');
    });
    Route::get('/dashboard', function () {
        return view('content.dashboard');
    })->name('dashboard');

    Route::get('/user/table', [AdminController::class, 'table'])->middleware(['role:admin'])->name('table.user');
    Route::get('/user/form', [AdminController::class, 'form'])->middleware(['role:admin'])->name('form.user');
    Route::post('/user/form/create', [AdminController::class, 'create'])->middleware(['role:admin|pimpinan'])->name('post.user');
    Route::get('/user/table/edit/{id}', [AdminController::class, 'view'])->middleware(['role:admin'])->name('edit.user');
    Route::post('/user/table/edit/{id}/post', [AdminController::class, 'update'])->middleware(['role:admin'])->name('edit.user.post');
    Route::delete('/user/table/delete/{id}', [AdminController::class, 'delete'])->middleware(["role:admin"])->name("delete.user");

    Route::get('/rapat', [RapatController::class, 'table'])->middleware(['role:admin|pimpinan'])->name('list.rapat');
    Route::get('/rapat/form', [RapatController::class, 'form'])->middleware(['role:admin|pimpinan'])->name('form.rapat');
    Route::post('/rapat/form/create', [RapatController::class, 'create'])->middleware(['role:admin|pimpinan'])->name('create.rapat');
    Route::get('/rapat/kehadiran/{id}', [RapatController::class, 'kehadiran'])->middleware(["role:admin|pimpinan"])->name('kehadiran.rapat');
    Route::post('/rapat/kehadiran/{id_rapat}', [AbsenController::class, 'verif_selected'])->middleware('role:admin|pimpinan')->name('verif.selected');
    Route::post('/rapat/{id}/status/mulai', [RapatController::class, 'statusMulai'])->middleware('role:admin|pimpinan')->name('mulai');
    Route::post('/rapat/{id}/status/selesai', [RapatController::class, 'statusSelesai'])->middleware('role:admin|pimpinan')->name('selesai');
    Route::get('/rapat/{id}/generate-pdf', [RapatController::class, 'generatePDF'])->middleware('role:admin|pimpinan')->name('generate-pdf');


    Route::get('/honor/detail', [ReportController::class, 'reportDetail'])->middleware(['role:admin|keuangan'])->name('list.honor.detail');
    Route::get('/honor/dasar', [ReportController::class, 'reportDasar'])->middleware(['role:admin|keuangan'])->name('list.honor.dasar');
    Route::get('/honor/dasar/print-report', [ReportController::class, 'printReport'])->middleware(['role:admin|keuangan'])->name('print.honor.dasar');
    Route::get('/honor/detail/print-report', [ReportController::class, 'printReportDetail'])->middleware(['role:admin|keuangan'])->name('print.honor.detail');
    Route::get('/honor/dasar/pribadi', [ReportController::class, 'reportPribadi'])->middleware(['role:pimpinan|anggota'])->name('list.honor.dasar.pribadi');
    Route::get('/honor/dasar/pribadi/print-report', [ReportController::class, 'printReportPribadi'])->middleware(['role:pimpinan|anggota'])->name('print.honor.pribadi');

    // Route::get('/absen/user', [UserController::class, 'kehadiran'])->middleware('role:anggota|pimpinan')->name('kehadiran.user');

    Route::get('/account/detail', [UserController::class, 'detail'])->name('account.detail');

    Route::get('/account/change_password', [UserController::class, 'passwordForm'])->name('change.password.form');

    Route::post('/account/change_password', [UserController::class, 'changePassword'])->name('change.password');
});


require __DIR__ . '/auth.php';
