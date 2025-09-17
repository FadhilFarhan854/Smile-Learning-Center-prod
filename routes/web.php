<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ReaktifasiController;

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

Route::middleware(['auth', 'checkrole:administrator 1,administrator 2'])->group(function () {
    
    
    //kelas
    Route::get('export-kelas', [KelasController::class, 'exportKelas']);
    Route::post('change-guru', [KelasController::class, 'changeGuru'])->name('changeGuru');
    Route::post('change-admin', [KelasController::class, 'changeAdmin'])->name('changeAdmin');
    Route::resource('kelas', KelasController::class);
    
    //siswa
    Route::post('import-siswa', [SiswaController::class, 'importSiswa']);
    Route::get('export-siswa', [SiswaController::class, 'exportSiswa']);
    Route::post('insert-note', [SiswaController::class, 'insertNote']);
    Route::post('change-level', [SiswaController::class, 'changeLevel']);
    Route::post('konfirmasi-pembayaran', [SiswaController::class, 'konfirmasiPembayaran']);
    
    
    //order
    
    Route::post('konfirmasi-spp', [OrderController::class, 'konfirmasiSPP']);
    Route::get('order/edit/{id}/{month}/{year}', [OrderController::class, 'edit']);

    //modul
    Route::get('export-modul', [ModulController::class, 'exportModul']);
    Route::put('kurang-stock/{id}', [ModulController::class, 'kurangStock']);
    Route::put('tambah-stock/{id}', [ModulController::class, 'tambahStock']);
    Route::put('change-tersedia/{id}', [ModulController::class, 'changeStatus']);
    Route::resource('modul', ModulController::class);
    
    //unit
    Route::get('export-unit', [UnitController::class, 'exportUnit']);
    Route::resource('unit', UnitController::class);
    
    //user
    Route::get('export-user', [UserController::class, 'exportUser']);
    Route::resource('user', UserController::class);
	
	//notif
    Route::post('change-notif/{id}', [App\Http\Controllers\HomeController::class, 'changeNotifStatus']);
	Route::post('delete-notif/{id}', [App\Http\Controllers\HomeController::class, 'deleteNotif']);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::middleware(['auth', 'checkrole:administrator 1,administrator 2,admin'])->group(function () {
    //siswa
    Route::post('change-status/{id}', [SiswaController::class, 'changeStatus']);
    Route::resource('siswa', SiswaController::class);

    //reaktifasi
    Route::get('reaktifasi', [ReaktifasiController::class, 'index']);
    Route::post('reaktifasi/{id}', [ReaktifasiController::class, 'reaktifasi']);

    //order
	Route::post('insert-additional', [OrderController::class, 'insertAdditional']);
    Route::get('export-order/{month}/{year}', [OrderController::class, 'exportOrder']);
    Route::post('input-spp', [OrderController::class, 'inputSPP']);
});

Route::middleware(['auth', 'checkrole:administrator 1,administrator 2,admin,motivator,guru'])->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    //order
    Route::resource('order', OrderController::class);
    
    //absen
    Route::get('export-absen/{month}/{year}', [AbsenController::class, 'exportAbsen']);
    Route::post('input-note', [AbsenController::class, 'inputNote']);
    Route::delete('delete-note', [AbsenController::class, 'deleteNote']);
    Route::resource('absen', AbsenController::class);
});

Auth::routes();

// Temporary test routes
Route::get('/test-absen', function() {
    return view('test_absen');
})->middleware('auth');

Route::get('/test-modul', function() {
    return view('test_modul');
})->middleware('auth');

