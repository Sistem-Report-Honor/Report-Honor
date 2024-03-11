<?php

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

    Route::get('/user', function(){
        return view('content.user.table-user');
    })->middleware(['role:admin'])->name('list.user');

    Route::get('/user/form', function(){
        return view('content.user.form-user');
    })->middleware(['role:admin'])->name('form.user');

    Route::get('/rapat', function(){
        return view('content.rapat.table-rapat');
    })->middleware(['role:admin|pimpinan'])->name('list.rapat');

    Route::get('/rapat/form', function(){
        return view('content.rapat.form-rapat');
    })->middleware(['role:admin|pimpinan'])->name('form.rapat');

    Route::get('/honor/detail', function(){
        return view('content.honor.honor-detail');
    })->middleware(['role:admin|keuangan'])->name('list.honor.detail');

    Route::get('/honor/dasar', function(){
        return view('content.honor.honor-dasar');
    })->middleware(['role:admin|keuangan'])->name('list.honor.dasar');

    Route::get('/honor/dasar/pribadi', function(){
        return view('content.honor.honor-dasar-pribadi');
    })->middleware(['role:pimpinan|anggota'])->name('list.honor.dasar.pribadi');

    Route::get('/account/detail', function(){
        return view('content.account.detail-account');
    })->name('account.detail');

    Route::get('/account/change_password', function(){
        return view('content.account.change-password');
    })->name('change.password');

    Route::get('/absen', function(){
        return view('content.absen.absen');
    })->middleware(['role:anggota|pimpinan'])->name('absen');
});




require __DIR__.'/auth.php';
