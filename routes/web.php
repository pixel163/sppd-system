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

Route::get('/dashboard', function () {return view('dashboard');})->middleware(['auth'])->name('dashboard');
Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
Route::get('/test', function () {return view('test');})->name('test');
Route::get('/test1', function () {return view('test1');})->name('test1');
Route::get('/sppd', function () {return view('sppd');});
Route::get('/sppd/create', function () {return view('sppd.create');});
Route::get('/ilpd', function () {return view('ilpd');});
// Route::get('/ilpd/create', function () {return view('ilpd.create');});
Route::get('/riwayat', function () {return view('riwayat');});
Route::get('/dokumen', function () {return view('dokumen');});

// Daftarkan route untuk /manager/sam
Route::view('/manager/sam', 'manager.sam');
Route::view('/ga/saga', 'ga.saga');
// Route::view('/manager/sam', 'manager.sam')->name('manager.sam');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/sppd/create', [SppdController::class, 'create'])->name('sppd.create');
    Route::post('/sppd/create', [SppdController::class, 'store'])->name('sppd.store');
    
    Route::get('/sppd/{sppd}/ilpd', [IlpdController::class, 'create'])->name('ilpd.create');
    Route::get('/ilpd/create', [IlpdController::class, 'create'])->name('ilpd.create');
    Route::post('/ilpd/create', [IlpdController::class, 'store'])->name('ilpd.store');
    
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
});