<?php

use App\Http\Controllers\Warga\AuthController as WargaAuthController;
use App\Http\Controllers\Pegawai\AuthController as PegawaiAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\PemeriksaanController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/warga/test', [WargaAuthController::class, 'test'])->name('warga.test');
Route::get('/warga/login', [WargaAuthController::class, 'loginWargaForm'])->name('warga.loginForm');
Route::post('/warga/login', [WargaAuthController::class, 'loginWarga'])->name('warga.login');
Route::get('/warga/logout', [WargaAuthController::class, 'logoutWarga'])->name('warga.logout');
Route::get('/warga/register', [WargaAuthController::class, 'registerWargaForm'])->name('warga.registerForm');
Route::post('/warga/register', [WargaAuthController::class, 'registerWarga'])->name('warga.register');
Route::get('/token', function () {
    return csrf_token();
});

//LOGIN REGISTER USER
Route::get('/pegawai/test', [PegawaiAuthController::class, 'test'])->name('pegawai.test');
Route::get('/pegawai/login', [PegawaiAuthController::class, 'loginPegawaiForm'])->name('pegawai.loginForm');
Route::post('/pegawai/login', [PegawaiAuthController::class, 'loginPegawai'])->name('pegawai.login')
;
//Route::post('/pegawai/login', [PegawaiAuthController::class, 'store'])->name('pegawai.store');
Route::get('/pegawai/logout', [PegawaiAuthController::class, 'logout'])->name('pegawai.logout');
Route::post('/pegawai/register', [PegawaiAuthController::class, 'registerPegawai'])->name('pegawai.register');

Route::get('/pegawai/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');


//CRUD USER
Route::get('/pegawais', [UserController::class, 'index'])->name('pegawais');
Route::post('/pegawais/update/{id}', [UserController::class, 'update'])->name('pegawais.update');
Route::get('/pegawais/edit', [UserController::class, 'edit'])->name('pegawais.edit');
Route::post('/pegawais/delete/{id}', [UserController::class, 'destroy'])->name('pegawais.delete');

//CRUD LAPORAN
Route::get('/laporans', [LaporanController::class, 'index'])->name('laporans');
Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
Route::get('/laporan/edit/{book}', [LaporanController::class, 'edit'])->name('laporan.edit');
Route::post('/laporan/update/{id}', [LaporanController::class, 'update'])->name('laporan.update');
Route::post('/laporan/delete/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
Route::post('/laporan/store', [LaporanController::class, 'store'])->name('laporan.store');

//CRUD PEMERIKSAAN
Route::get('/pemeriksaans', [PemeriksaanController::class, 'index'])->name('pemeriksaans');
Route::get('/pemeriksaan/create', [PemeriksaanController::class, 'create'])->name('pemeriksaan.create');
Route::get('/pemeriksaan/edit/{book}', [PemeriksaanController::class, 'edit'])->name('pemeriksaan.edit');
Route::post('/pemeriksaan/update/{id}', [PemeriksaanController::class, 'update'])->name('pemeriksaan.update');
Route::post('/pemeriksaan/delete/{id}', [PemeriksaanController::class, 'destroy'])->name('pemeriksaan.destroy');
Route::post('/pemeriksaan/store', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');

//LURAH
Route::get('/table_warga', [WargaController::class, 'index'])->name('wargas');
Route::get('/table_petugas', [UserController::class, 'index'])->name('users');

