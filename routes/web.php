<?php

use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanRWController;
use App\Http\Controllers\LaporanLurahController;
use App\Http\Controllers\LaporanRTController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Warga\FiturController as WargaFiturController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\TindakanController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\TestingController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('user.auth.login');
});

//LOGIN REGISTER USER
Route::get('/user/test', [UserAuthController::class, 'test'])->name('user.test');
Route::get('/login', [UserAuthController::class, 'loginUserForm'])->name('user.loginForm');
Route::post('/login', [UserAuthController::class, 'loginUser'])->name('user.login');
Route::get('/logout', [UserAuthController::class, 'logoutUser'])->name('user.logout');
Route::get('/register', [UserAuthController::class, 'registerUserForm'])->name('user.registerForm');
Route::post('/register', [UserAuthController::class, 'registerUser'])->name('user.register');
Route::get('/user/{id}/edit', [UserAuthController::class, 'editUser'])->name('user.edit');
Route::put('/user/{id}', [UserAuthController::class, 'updateUser'])->name('user.update');
Route::get('/token', function () {
    return csrf_token();
});

// Route::get('/pegawai/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard')->middleware('role:Lurah');
Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.index');
Route::get('/total-bangunan', [UserDashboardController::class, 'getTotalBangunan'])->name('pegawai.getBangunan');;
Route::get('/chart-data', [UserController::class, 'chartData'])->name('chart-data');

//CRUD PEMERIKSAAN
//Route::get('/pemeriksaans', [PemeriksaanController::class, 'index'])->name('pemeriksaans');
Route::get('/pemeriksaan/create', [PemeriksaanController::class, 'create'])->name('pemeriksaan.create');
Route::get('/pemeriksaan/edit/{book}', [PemeriksaanController::class, 'edit'])->name('pemeriksaan.edit');
Route::post('/pemeriksaan/update/{id}', [PemeriksaanController::class, 'update'])->name('pemeriksaan.update');
Route::post('/pemeriksaan/delete/{id}', [PemeriksaanController::class, 'destroy'])->name('pemeriksaan.destroy');
Route::post('/pemeriksaan/store', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');
Route::post('/pemeriksaan/update-status', [PemeriksaanController::class, 'updateStatus'])->name('pemeriksaan.updateStatus');

//LURAH
Route::get('/table_warga', [WargaController::class, 'index'])->name('wargas');
Route::get('/table_petugas', [UserController::class, 'index'])->name('users');
Route::get('/laporan_jumantik1', [PemeriksaanController::class, 'index'])->name('pemeriksaans');
Route::get('/laporanRT', [LaporanRTController::class, 'index'])->name('laporan.rt');
Route::get('/laporanRW', [LaporanRWController::class, 'index'])->name('laporan.rw');
Route::get('/laporanlurah', [LaporanLurahController::class, 'index'])->name('laporan.lurah');
Route::get('/laporan_puskesmas', [TindakanController::class, 'index'])->name('tindakans');


//PUSKESMAS
Route::get('/laporan_puskesmas', [TindakanController::class, 'index'])->name('tindakans');
Route::post('/tindakan/store', [TindakanController::class, 'store'])->name('tindakan.store');


Route::get('/testing', [TestingController::class, 'index'])->name('testings');
