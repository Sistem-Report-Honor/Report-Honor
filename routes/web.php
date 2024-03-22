<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RapatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
Route::middleware(['auth','role:admin|pimpinan|keuangan|anggota'])->group(function () {
    Route::get('/dashboard', function () {
        return view('content.dashboard');
    })->name('dashboard');

    Route::get('/user/table',[AdminController::class, 'table'])->middleware(['role:admin'])->name('table.user');
    Route::get('/user/form',[AdminController::class, 'form'] )->middleware(['role:admin'])->name('form.user');
    Route::post('/user/form/create',[AdminController::class, 'create'])->middleware(['role:admin|pimpinan'])->name('post.user');
    Route::post('/user/table/edit/{id}', [AdminController::class, 'edit'])->middleware(['role:admin'])->name('edit.user');
    Route::delete('/user/table/delete/{id}', [AdminController::class, 'delete'])->middleware(["role:admin"])->name("delete.user");

    Route::get('/rapat', [RapatController::class, 'table'])->middleware(['role:admin|pimpinan'])->name('list.rapat');
    Route::get('/rapat/form', [RapatController::class,'form'])->middleware(['role:pimpinan'])->name('form.rapat');
    Route::post('/rapat/form/create',[RapatController::class, 'create'])->middleware(['role:pimpinan'])->name('create.rapat');

    Route::get('/honor/detail', function(){
        return view('content.honor.honor-detail');
    })->middleware(['role:admin|keuangan'])->name('list.honor.detail');

    Route::get('/honor/dasar', [AdminController::class, 'tableSenat'])->middleware(['role:admin|keuangan'])->name('list.honor.dasar');

    Route::get('/honor/dasar/pribadi', function(){
        return view('content.honor.honor-dasar-pribadi');
    })->middleware(['role:pimpinan|anggota'])->name('list.honor.dasar.pribadi');

    Route::get('/account/detail', function(){
        return view('content.account.detail-account');
    })->name('account.detail');

    Route::get('/account/change_password', function(){
        return view('content.account.change-password');
    })->name('change.password');

    Route::get('/absen/{kode_unik?}', function(){
        return view('content.absen.absen');
    })->middleware(['role:anggota|pimpinan'])->name('absen');
});




require __DIR__.'/auth.php';
