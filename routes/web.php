<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/', function () {return view('login');});
Route::get('/', function () {return view('login');})->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login');

// Route::get('/login', function () {return view('login');});
Route::get('/dashboard', function () {return view('dashboard');});
Route::get('/sppd', function () {return view('sppd');});
Route::get('/ilpd', function () {return view('ilpd');});
Route::get('/riwayat', function () {return view('riwayat');});
Route::get('/dokumen', function () {return view('dokumen');});

Route::get('/login', function () {return view('login');})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/staff/dashboard', function () {return view('staff.dashboard');})->name('staff.dashboard');
// Route::get('/sppd', function () {return view('staff.sppd');});
// Route::get('/ilpd', function () {return view('staff.ilpd');});

Route::get('/manajer/dashboard', function () {return view('manajer.dashboard');})->name('manajer.dashboard');

Route::get('/ga/dashboard', function () {return view('ga.dashboard');})->name('ga.dashboard');