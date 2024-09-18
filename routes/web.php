<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin_DashboardController;
use App\Http\Controllers\Admin_PresensiController;
use App\Http\Controllers\Admin_DosenController;
use App\Http\Controllers\Admin_PengajuanController;
use App\Http\Controllers\HistoriController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfilController;

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



Route::middleware(['guest:dosen'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});

Route::get('/login/admin', [LoginController::class, 'index'])->name('login.admin');
Route::post('/login/admin/auth', [LoginController::class, 'auth'])->name('auth.admin');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register/post', [RegisterController::class, 'store'])->name('post.register');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboardAdmin', [Admin_DashboardController::class, 'index'])->name('dashboard_admin');

    Route::get('/dashboardAdmin/dosen', [Admin_DosenController::class, 'index'])->name('data_dosen');
    Route::put('/dashboardAdmin/dosen/tolak/{id}', [Admin_DosenController::class, 'tolak'])->name('tolak_dosen');
    Route::put('/dashboardAdmin/dosen/terima/{id}', [Admin_DosenController::class, 'terima'])->name('terima_dosen');
    Route::delete('/dashboardAdmin/dosen/hapus/{id}', [Admin_DosenController::class, 'destroy'])->name('hapus_dosen');
    
    Route::get('/dashboardAdmin/pengajuan', [Admin_PengajuanController::class, 'index'])->name('data_pengajuan');
    Route::put('/dashboardAdmin/pengajuan/terima/{id}', [Admin_PengajuanController::class, 'terima'])->name('terima_izin');
    Route::put('/dashboardAdmin/pengajuan/tolak/{id}', [Admin_PengajuanController::class, 'tolak'])->name('tolak_izin');
    
    Route::get('/dashboardAdmin/presensi', [Admin_PresensiController::class, 'index'])->name('data_presensi');
    Route::delete('/dashboardAdmin/presensi/{id}/hapus', [Admin_PresensiController::class, 'destroy'])->name('hapus_absensi');
});

Route::middleware(['auth:dosen'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/proseslogout', [AuthController::class, 'proseslogout']);

    //presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    Route::get('/presensi/halamanupload', [PresensiController::class, 'halamanUpload']);
    Route::post('/presensi/halamanupload/uploadabsensi', [PresensiController::class, 'uploadabsensi']);

    // editprofil
    Route::get('/editprofil', [ProfilController::class, 'editprofil']);
    Route::post('/presensi/{nip}/updateprofil', [ProfilController::class, 'updateprofil']);

    //histori
    Route::get('/presensi/histori', [HistoriController::class, 'histori']);
    Route::post('/gethistori', [HistoriController::class, 'gethistori']);

    //izin
    Route::get('/presensi/izin', [PengajuanController::class, 'izin']);
    Route::get('/presensi/pengajuanizin', [PengajuanController::class, 'pengajuanizin']);
    Route::post('/presensi/storeizin', [PengajuanController::class, 'storeizin']);
   
});
