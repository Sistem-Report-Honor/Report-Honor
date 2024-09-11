<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\RapatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GolonganController;

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
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/user/table', [AdminController::class, 'table'])->middleware(['role:admin'])->name('table.user');
    Route::get('/user/form', [AdminController::class, 'form'])->middleware(['role:admin'])->name('form.user');
    Route::post('/user/form/create', [AdminController::class, 'create'])->middleware(['role:admin|pimpinan'])->name('post.user');
    Route::get('/user/table/edit/{id}', [AdminController::class, 'view'])->middleware(['role:admin'])->name('edit.user');
    Route::put('/user/table/edit/{id}/post', [AdminController::class, 'update'])->middleware(['role:admin'])->name('edit.user.post');
    Route::delete('/user/table/delete/{id}', [AdminController::class, 'delete'])->middleware(["role:admin"])->name("delete.user");

    Route::get('/rapat', [RapatController::class, 'table'])->middleware(['role:admin|pimpinan'])->name('list.rapat');
    Route::get('/rapat/form', [RapatController::class, 'form'])->middleware(['role:admin|pimpinan'])->name('form.rapat');
    Route::post('/rapat/form/create', [RapatController::class, 'create'])->middleware(['role:admin|pimpinan'])->name('create.rapat');
    Route::get('/rapat/kehadiran/{id}', [RapatController::class, 'kehadiran'])->middleware(["role:admin|pimpinan"])->name('kehadiran.rapat');
    Route::post('/rapat/kehadiran/{id_rapat}', [AbsenController::class, 'verif_selected'])->middleware('role:admin|pimpinan')->name('verif.selected');
    Route::post('/rapat/{id}/status/mulai', [RapatController::class, 'statusMulai'])->middleware('role:admin|pimpinan')->name('mulai');
    Route::post('/rapat/{id}/status/selesai', [RapatController::class, 'statusSelesai'])->middleware('role:admin|pimpinan')->name('selesai');
    Route::get('/rapat/{id}/generate-pdf', [RapatController::class, 'generatePDF'])->middleware('role:admin|pimpinan')->name('generate-pdf');
    Route::delete('/rapat/{id}', [RapatController::class, 'delete'])->middleware('role:admin|pimpinan')->name('rapat.delete');
    Route::get('/rapat/{id_rapat}/export-kehadiran', [AbsenController::class, 'exportKehadiran'])->name('export.kehadiran');


    Route::get('/honor/detail', [ReportController::class, 'reportDetail'])->middleware(['role:admin|keuangan'])->name('list.honor.detail');
    Route::get('/honor/dasar', [ReportController::class, 'reportDasar'])->middleware(['role:admin|keuangan'])->name('list.honor.dasar');
    Route::get('/honor/dasar/print-report', [ReportController::class, 'printReport'])->middleware(['role:admin|keuangan'])->name('print.honor.dasar');
    Route::get('/honor/detail/print-report', [ReportController::class, 'printReportDetail'])->middleware(['role:admin|keuangan'])->name('print.honor.detail');
    Route::get('/honor/dasar/pribadi', [ReportController::class, 'reportPribadi'])->middleware(['role:pimpinan|anggota'])->name('list.honor.dasar.pribadi');
    Route::get('/honor/dasar/pribadi/print-report', [ReportController::class, 'printReportPribadi'])->middleware(['role:pimpinan|anggota'])->name('print.honor.pribadi');

    // Route::get('/absen/user', [UserController::class, 'kehadiran'])->middleware('role:anggota|pimpinan')->name('kehadiran.user');

    Route::get('/golongan', [GolonganController::class, 'index'])->middleware(['role:admin|keuangan'])->name('golongan.index');
    Route::get('/golongan/create', [GolonganController::class, 'create'])->name('golongan.create');
    Route::post('/golongan/store', [GolonganController::class, 'store'])->middleware(['role:admin|keuangan'])->name('golongan.store');
    Route::get('/golongan/{id}/edit', [GolonganController::class, 'edit'])->middleware(['role:admin|keuangan'])->name('golongan.edit');
    Route::put('/golongan/{id}/update', [GolonganController::class, 'update'])->middleware(['role:admin|keuangan'])->name('golongan.update');
    Route::delete('/golongan/{id}/destroy', [GolonganController::class, 'destroy'])->middleware(['role:admin|keuangan'])->name('golongan.destroy');
    
    // Tambahkan rute untuk kehadiran senat dan laporan kehadiran
    Route::get('/kehadiran', [ReportController::class, 'KehadiranSenat'])->middleware(['role:admin|keuangan'])->name('kehadiran.index');
    Route::get('/kehadiran/export', [ReportController::class, 'Printkehadiran'])->middleware(['role:admin|keuangan'])->name('kehadiran.export');
 
    Route::get('/account/change_password', [UserController::class, 'passwordForm'])->name('change.password.form');
    Route::post('/account/change_password', [UserController::class, 'changePassword'])->name('change.password');
    Route::get('/account/detail', [UserController::class, 'detail'])->name('account.detail');
});


require __DIR__ . '/auth.php';

