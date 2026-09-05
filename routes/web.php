<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SppdController;
use App\Http\Controllers\IlpdController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatController;

// Route::get('/', function () {return view('login');});
Route::get('/', function () {return view('login');})->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login');
Route::get('/login', function () {return view('login');})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/dashboard', function () {return view('dashboard');})->middleware(['auth'])->name('dashboard');
// Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
Route::get('/test', function () {return view('test');})->name('test');
Route::get('/test1', function () {return view('test1');})->name('test1');
Route::get('/sppd', function () {return view('sppd');});
Route::get('/sppd/create', function () {return view('sppd.create');});
Route::get('/ilpd', function () {return view('ilpd');});
// Route::get('/ilpd/create', function () {return view('ilpd.create');});
Route::get('/riwayat', function () {return view('riwayat');});
Route::get('/dokumen', function () {return view('dokumen');});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // staff
    Route::get('/sppd/create', [SppdController::class, 'create'])->name('sppd.create');
    Route::post('/sppd/create', [SppdController::class, 'store'])->name('sppd.store');
    // Route::get('/sppd/manager/{id}', [SppdController::class, 'show'])->name('manager.show');
    Route::get('sppd/{id}/edit', [SppdController::class, 'edit'])->name('sppd.edit');
    Route::put('sppd/{id}/edit', [SppdController::class, 'update'])->name('sppd.update');

    //  manager
    Route::get('/{id}', [SppdController::class, 'show'])->name('approval.show');
    Route::post('/{id}/approve', [SppdController::class, 'approve'])->name('sppd.approve');
    
    // staff
    // Route::get('/sppd/{sppd}/ilpd', [IlpdController::class, 'create'])->name('ilpd.create');
    Route::get('/ilpd/create', [IlpdController::class, 'create'])->name('ilpd.create');
    Route::post('/ilpd/create', [IlpdController::class, 'store'])->name('ilpd.store');

    // Route::get('ilpd/{id}/edit', [IlpdController::class, 'edit'])->name('ilpd.edit');
    // Route::put('ilpd/{id}/edit', [IlpdController::class, 'update'])->name('ilpd.update');
    
    // all
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
});
    
//     Route::middleware(['auth'])->prefix('sppd/approval')->name('sppd.approval.')->group(function () {
//     // 1. Tampilkan Halaman Form Approval (Readonly Data Staff + Form Persetujuan)
//     Route::get('/{id}', [SppdController::class, 'show'])->name('show');
    
//     // 2. Eksekusi Setujui Pengajuan
//     Route::post('/{id}/approve', [SppdController::class, 'approve'])->name('approve');
// });