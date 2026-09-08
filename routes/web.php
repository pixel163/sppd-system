<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SppdController;
use App\Http\Controllers\IlpdController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\DokumenController;

Route::get('/', function () {return view('login');})->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login');
Route::get('/login', function () {return view('login');})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/test', function () {return view('test');})->name('test');
Route::get('/test1', function () {return view('test1');})->name('test1');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // all
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen');

    // staff sppd
    Route::get('/sppd/create', [SppdController::class, 'create'])->name('sppd.create');
    Route::post('/sppd/create', [SppdController::class, 'store'])->name('sppd.store');
    // Route::get('sppd/{id}/edit', [SppdController::class, 'edit'])->name('sppd.edit');
    // Route::put('sppd/{id}/edit', [SppdController::class, 'update'])->name('sppd.update');
    
    // staff ilpd
    Route::get('/ilpd/create/{sppd?}', [IlpdController::class, 'create'])->name('ilpd.create');
    Route::post('/ilpd/create', [IlpdController::class, 'store'])->name('ilpd.store');
    // Route::get('/ilpd/create/{sppd}', [IlpdController::class, 'create'])->name('ilpd.create');
    // Route::get('ilpd/{id}/edit', [IlpdController::class, 'edit'])->name('ilpd.edit');
    // Route::put('ilpd/{id}/edit', [IlpdController::class, 'update'])->name('ilpd.update');
    
    //  manager
    Route::get('/sppd/{id}', [SppdController::class, 'show'])->name('approval.show');
    Route::post('/sppd/{id}/approve', [SppdController::class, 'approve'])->name('sppd.approve');
    // Route::get('/sppd/manager/{id}', [SppdController::class, 'show'])->name('manager.show');

    // ga
    Route::get('/ilpd/{id}', [IlpdController::class, 'show'])->name('approve.show');
    Route::post('/ilpd/{id}/approve', [IlpdController::class, 'approve'])->name('ilpd.approve');
});


// Route::get('/sppd', function () {return view('sppd');});
// Route::get('/sppd/create', function () {return view('sppd.create');});
// Route::get('/ilpd', function () {return view('ilpd');});
// Route::get('/riwayat', function () {return view('riwayat');});