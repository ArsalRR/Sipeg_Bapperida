<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KeluargaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'processLogin'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'processRegister'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::resource('keluarga', KeluargaController::class)->except(['create', 'show', 'edit']);
    Route::get('/cetak-kp4', [\App\Http\Controllers\DokumenController::class, 'cetakKp4'])->name('dokumen.kp4');

    // Menu File / Repositori Dokumen
    Route::get('/menu-file', [\App\Http\Controllers\DokumenFileController::class, 'index'])->name('dokumen.file.index');
    Route::get('/menu-file/show/{id}', [\App\Http\Controllers\DokumenFileController::class, 'showJenis'])->name('dokumen.file.showJenis');
    Route::post('/menu-file', [\App\Http\Controllers\DokumenFileController::class, 'store'])->name('dokumen.file.store');
    Route::post('/menu-file/jenis', [\App\Http\Controllers\DokumenFileController::class, 'storeJenis'])->name('dokumen.file.storeJenis');
    Route::put('/menu-file/jenis/{id}', [\App\Http\Controllers\DokumenFileController::class, 'updateJenis'])->name('dokumen.file.updateJenis');
    Route::delete('/menu-file/jenis/{id}', [\App\Http\Controllers\DokumenFileController::class, 'destroyJenis'])->name('dokumen.file.destroyJenis');
    Route::get('/menu-file/download/{id}', [\App\Http\Controllers\DokumenFileController::class, 'download'])->name('dokumen.file.download');
    Route::delete('/menu-file/{id}', [\App\Http\Controllers\DokumenFileController::class, 'destroy'])->name('dokumen.file.destroy');


});

Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
});

Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('pegawais', PegawaiController::class)->except(['create', 'show', 'edit']);
    Route::resource('jabatans', JabatanController::class)->except(['create', 'show', 'edit']);
});
