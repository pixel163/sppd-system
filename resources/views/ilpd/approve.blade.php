@extends('layouts.app')

@section('title', 'Form ILPD - SPPD System')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form ILPD - SPPD System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        /* =========================
           FORM
        ========================== */

        .form-input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 14px;
            outline: none;
            background: white;
            color: #1e293b;
            transition: all .2s ease;
        }

        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .readonly-input {
            background: #f8fafc;
            color: #475569;
            cursor: not-allowed;
        }

        .form-textarea {
            width: 100%;
            resize: vertical;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 13px 14px;
            font-size: 14px;
            outline: none;
            color: #1e293b;
        }

        .form-textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
        }

        /* =========================
           CURRENCY INPUT
        ========================== */

        .currency-wrapper {
            display: flex;
            overflow: hidden;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: white;
        }

        .currency-prefix {
            display: flex;
            align-items: center;
            padding: 0 12px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            border-right: 1px solid #e2e8f0;
        }

        .currency-input {
            width: 100%;
            min-width: 0;
            border: 0;
            padding: 10px 12px;
            font-size: 14px;
            outline: none;
        }

        .currency-input:focus {
            box-shadow: inset 0 0 0 1px #2563eb;
        }

        .currency-input[readonly] {
            background: #f8fafc;
            color: #475569;
            cursor: not-allowed;
        }

        /* =========================
           SEARCH / AUTOCOMPLETE
        ========================== */

        .search-result {
            position: absolute;
            left: 0;
            right: 0;
            top: calc(100% + 4px);
            z-index: 50;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            box-shadow: 0 10px 25px rgba(15, 23, 42, .10);
        }

        .search-item {
            width: 100%;
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
            background: white;
            cursor: pointer;
            transition: background .15s ease;
        }

        .search-item:last-child {
            border-bottom: 0;
        }

        .search-item:hover {
            background: #f8fafc;
        }

        /* =========================
           MOBILE
        ========================== */

        @media (max-width: 767px) {

            .desktop-sidebar {
                position: static;
                width: 100%;
                height: auto;
                border-right: 0;
                border-bottom: 1px solid #e2e8f0;
                padding: 16px;
            }

            .sidebar-menu {
                display: flex;
                overflow-x: auto;
                gap: 5px;
                padding-bottom: 3px;
            }

            .sidebar-menu a {
                flex: 0 0 auto;
                white-space: nowrap;
            }

            .sidebar-bottom {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }

            .top-navbar {
                height: auto;
                padding: 14px 16px;
            }

            .content-wrapper {
                padding: 20px 16px;
            }

            .section-card {
                padding: 18px;
            }

            .date-wrapper {
                flex-direction: column;
                align-items: stretch;
            }

            .date-wrapper > div {
                width: 100%;
            }

            .transport-grid,
            .necessity-grid {
                grid-template-columns: 1fr;
            }

            .expense-row {
                flex-direction: column;
                align-items: stretch !important;
                gap: 8px;
            }

            .expense-input {
                width: 100% !important;
            }

            .user-info {
                display: none;
            }

            .button-wrapper {
                flex-direction: column-reverse;
            }

            .button-wrapper a,
            .button-wrapper button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-white text-[#1e293b]">

<div class="min-h-screen">

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen w-full">
    
    {{-- ALERT PESAN SUKSES --}}
    @if (session('success'))
        <div class="mb-5 flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle-2" class="size-5 text-emerald-600"></i>
                <span class="text-[13px] font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
    @endif

    {{-- ALERT PESAN ERROR (TRANSACTION ROLLBACK / EXCEPTION) --}}
    @if (session('error'))
        <div class="mb-5 flex items-center justify-between rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-800 shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-circle" class="size-5 text-rose-600"></i>
                <span class="text-[13px] font-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
    @endif

    {{-- ALERT ERROR VALIDASI INPUT (Misal File Kegedean / Kosong) --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 shadow-sm">
            <div class="mb-1 flex items-center gap-2 font-bold text-[13px] text-amber-800">
                <i data-lucide="alert-triangle" class="size-4 text-amber-600"></i>
                <span>Gagal Memproses Data:</span>
            </div>
            <ul class="ml-6 list-disc text-[12px] text-amber-700 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        {{-- =====================================================
             CONTENT
        ====================================================== --}}
        <div class="w-full px-5 py-6 sm:px-7 lg:px-8">

            {{-- TITLE --}}
            <div class="mb-5">

                <h1 class="text-[24px] font-extrabold uppercase">
                    FORM ILPD
                </h1>

                <p class="mt-1 text-[14px] text-[#64748b]">
                    Lengkapi data perizinan perjalanan dinas Anda.
                </p>

            </div>

            <form id="ilpdForm" action="{{ route('ilpd.approve', $ilpd->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                {{-- =================================================
                    SECTION 1
                ================================================== --}}
                <section class="section-card rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]">

                    <div class="mb-5 flex items-center gap-3">

                        <div class="flex size-7 items-center justify-center rounded-full bg-[#2563eb] text-[14px] font-bold text-white">
                            1
                        </div>

                        <h2 class="text-[15px] font-bold uppercase">
                            Rencana Perjalanan Dinas
                        </h2>

                    </div>

                    {{-- KOTA --}}
                    <div class="mb-4">

                        <label for="kota"
                            class="mb-1.5 block text-[13px] font-semibold">
                            Kota Tujuan <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="kota"
                            name="kota"
                            type="text"
                            value="{{ $ilpd->sppd->kota->name ?? '-' }}"
                            class="form-input flex-1"
                            readonly>
                    </div>

                    {{-- LAMA PERJALANAN --}}
                    <div class="mb-4">
                        <label class="mb-1.5 block text-[13px] font-semibold">
                            Lama Perjalanan <span class="text-red-500">*</span>
                        </label>

                        <div class="date-wrapper flex items-center gap-4">
                            {{-- TANGGAL MULAI --}}
                            <div class="flex flex-1 items-center rounded-lg border border-[#cbd5e1] px-3.5 py-2.5">
                                <input
                                    id="tanggalMulai"
                                    name="tanggal_awal"
                                    type="date"
                                    class="w-full border-0 text-[14px] outline-none"
                                    value="{{ isset($ilpd->tanggal_awal) ? \Carbon\Carbon::parse($ilpd->tanggal_awal)->format('Y-m-d') : '-' }}"
                                    readonly>
                            </div>

                            <span class="text-[14px] font-semibold text-[#64748b]">s/d</span>

                            {{-- TANGGAL SELESAI (READONLY/DISABLED KARENA OTOMATIS) --}}
                            <div class="flex flex-1 items-center rounded-lg border border-[#cbd5e1] bg-gray-50 px-3.5 py-2.5">
                                <input
                                    id="tanggalSelesai"
                                    name="tanggal_akhir"
                                    type="date"
                                    class="w-full border-0 bg-transparent text-[14px] outline-none"
                                    value="{{ isset($ilpd->tanggal_akhir) ? \Carbon\Carbon::parse($ilpd->tanggal_akhir)->format('Y-m-d') : '-' }}"
                                    readonly>
                            </div>
                        </div>

                        <p id="infoLamaPerjalanan" class="mt-1.5 text-[11px] text-[#64748b]"></p>
                    </div>

                    {{-- TRANSPORTASI --}}
                    <div class="mb-4">
                        <label class="mb-2 block text-[13px] font-semibold text-[#0f172a]">
                            Transportasi
                        </label>

                        <div class="transport-grid grid grid-cols-3 gap-x-6 gap-y-3">
                            {{-- Loop data dari tabel 'transports' --}}
                            @foreach ($transports as $transport)
                                @php
                                    $isChecked = isset($ilpd->sppd) && $ilpd->sppd->transport_id == $transport->id;
                                @endphp
                                <label class="flex items-center gap-2 cursor-default select-none">
                                    <input
                                        type="checkbox"
                                        disabled
                                        {{ $isChecked ? 'checked' : '' }}
                                        class="size-[18px] accent-[#2563eb] disabled:opacity-100 cursor-default">
                                    <span class="text-[14px] {{ $isChecked ? 'font-medium text-[#0f172a]' : 'text-[#64748b]' }}">
                                        {{ $transport->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        
                        {{-- Input Lainnya (Read-only) --}}
                        @if(isset($ilpd->sppd->transport_lainnya))
                            <div class="mt-2 flex items-center gap-3">
                                <label class="flex items-center gap-2 cursor-default">
                                    <input type="checkbox" checked disabled class="size-[18px] accent-[#2563eb] disabled:opacity-100">
                                    <span class="text-[14px] font-medium text-[#0f172a]">Lainnya</span>
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $ilpd->sppd->transport_lainnya }}"
                                    class="w-[180px] rounded-md border border-[#cbd5e1] bg-[#f8fafc] px-3 py-1.5 text-[12px] text-[#0f172a] outline-none cursor-default">
                            </div>
                        @endif
                    </div>

                    {{-- KEPERLUAN --}}
                    <div class="mb-4">
                        <label class="mb-2 block text-[13px] font-semibold text-[#0f172a]">
                            Keperluan
                        </label>

                        <div class="keperluan-grid grid grid-cols-3 gap-x-6 gap-y-3">
                            {{-- Loop data dari tabel 'keperluans' --}}
                            @foreach ($keperluans as $keperluan)
                                @php
                                    $isChecked = isset($ilpd->sppd) && $ilpd->sppd->keperluan_id == $keperluan->id;
                                @endphp
                                <label class="flex items-center gap-2 cursor-default select-none">
                                    <input
                                        type="checkbox"
                                        disabled
                                        {{ $isChecked ? 'checked' : '' }}
                                        class="size-[18px] accent-[#2563eb] disabled:opacity-100 cursor-default">
                                    <span class="text-[14px] {{ $isChecked ? 'font-medium text-[#0f172a]' : 'text-[#64748b]' }}">
                                        {{ $keperluan->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Input Lainnya (Read-only) --}}
                        @if(isset($ilpd->sppd->keperluan_lainnya))
                            <div class="mt-2 flex items-center gap-3">
                                <label class="flex items-center gap-2 cursor-default">
                                    <input type="checkbox" checked disabled class="size-[18px] accent-[#2563eb] disabled:opacity-100">
                                    <span class="text-[14px] font-medium text-[#0f172a]">Lainnya</span>
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $ilpd->sppd->keperluan_lainnya }}"
                                    class="w-[180px] rounded-md border border-[#cbd5e1] bg-[#f8fafc] px-3 py-1.5 text-[12px] text-[#0f172a] outline-none cursor-default">
                            </div>
                        @endif
                    </div>

                    {{-- TUGAS --}}
                    <div>
                        <label for="tugas" class="mb-1.5 block text-[13px] font-semibold text-[#0f172a]">
                            Tugas
                        </label>

                        <textarea
                            id="tugas"
                            readonly
                            rows="4"
                            class="w-full rounded-xl border border-[#cbd5e1] bg-[#f8fafc] p-3 text-[13px] leading-relaxed text-[#0f172a] outline-none resize-none cursor-default"
                            placeholder="Tidak ada detail tugas.">{{ $ilpd->sppd->tugas ?? '' }}</textarea>

                        <p class="mt-1 text-[11px] text-[#64748b]">
                            Rincian tugas yang diisikan oleh pemohon.
                        </p>
                    </div>

                </section>

                {{-- =================================================
                    SECTION 2
                ================================================== --}}
                <section class="section-card mt-5 rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]">

                    <div class="mb-5 flex items-center gap-3">

                        <div class="flex size-7 items-center justify-center rounded-full bg-[#2563eb] text-[14px] font-bold text-white">
                            2
                        </div>

                        <h2 class="text-[15px] font-bold uppercase">
                            Perkiraan Biaya
                        </h2>

                    </div>

                    <div class="mb-5">
                        <label class="mb-2 block text-[13px] font-semibold text-[#0f172a]">
                            Tiket <span class="text-red-500">*</span>
                        </label>

                        {{-- DROPZONE BOX --}}
                        <div class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-[#2563eb]/40 bg-[#f8fafc] px-6 py-8 text-center transition-all hover:border-[#2563eb] hover:bg-[#eff6ff]/30">
                            
                            {{-- INPUT FILE TERSEMBUNYI --}}
                            <input 
                                type="file" 
                                id="tiket_file" 
                                name="tiket_file" 
                                accept=".pdf,.jpg,.jpeg,.png"
                                required
                                class="absolute inset-0 z-10 cursor-pointer opacity-0" 
                                onchange="updateFileName(this)"
                                oninvalid="this.setCustomValidity('Silahkan upload tiket')"
                                onkeydown="cekkunci(event)"
                            />

                            {{-- IKON CLOUD UPLOAD --}}
                            <div class="mb-3 flex size-10 items-center justify-center rounded-full text-[#2563eb]">
                                <i data-lucide="cloud-upload" class="size-8"></i>
                            </div>

                            {{-- TEKS INSTRUKSI --}}
                            <p id="file-label" class="text-[14px] font-bold text-[#0f172a]">
                                Upload tiket perjalanan
                            </p>
                            <p class="mt-0.5 text-[11px] text-[#64748b]">
                                Format: PDF, JPG, JPEG, PNG (Maks. 5MB)
                            </p>

                            {{-- TOMBOL PILIH FILE --}}
                            <div class="mt-4">
                                <span class="inline-flex items-center justify-center rounded-xl border border-[#2563eb] bg-white px-5 py-2 text-[12px] font-semibold text-[#2563eb] shadow-sm transition-colors hover:bg-[#eff6ff]">
                                    Pilih File
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- BBM --}}
                    <div class="mb-5">

                        <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                            BBM (Opsional)
                        </label>

                        <div class="currency-wrapper">

                            <span class="currency-prefix">
                                Rp
                            </span>

                            <input
                                id="bbm"
                                name="bbm"
                                type="text"
                                inputmode="numeric"
                                placeholder="Masukkan jumlah biaya BBM"
                                class="currency-input expense-input"
                                oninput="formatRupiah(this); hitungTotal()">

                        </div>

                    </div>

                    {{-- UANG HARIAN --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm mb-6">
                        {{-- Header Section --}}
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h4 class="text-[14px] font-bold text-slate-800">Rincian Uang Harian & Penginapan</h4>
                                <p class="mt-0.5 text-[11px] text-[#64748b]">
                                    Nilai di bawah dihitung otomatis dari Master Data berdasarkan Golongan Staff dan kota tujuan.
                                </p>
                            </div>

                            {{-- Switch / Tombol untuk Mengaktifkan Mode Edit Khusus GA --}}
                            <button type="button" id="btnToggleEdit" onclick="toggleAdjustTarif()" 
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-slate-50 px-3 py-1.5 text-[12px] font-semibold text-slate-700 hover:bg-slate-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                <span>Sesuaikan Tarif</span>
                            </button>
                        </div>

                        {{-- Dropdown Opsional (Awalnya Tersembunyi) --}}
                        <div id="wrapperDropdownGolongan" class="mb-4 hidden rounded-lg bg-amber-50 p-3 border border-amber-200">
                            <label class="mb-1 block text-[12px] font-semibold text-amber-900">
                                Pilih Tarif Golongan Pengganti (Override):
                            </label>
                            <select id="selectGolonganOverride" onchange="applyTarifOverride(this)" 
                                    class="w-full rounded-md border border-amber-300 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">-- Pilih Golongan Penyesuaian --</option>
                                @foreach($tarifs as $tarif)
                                    <option value="{{ $tarif->id }}" 
                                            data-dinas="{{ $tarif->dinas }}" 
                                            data-makan="{{ $tarif->makan }}" 
                                            data-hotel="{{ $tarif->hotel }}"
                                            {{ (isset($golonganId) && $tarif->golongan_id == $golonganId) ? 'selected' : '' }}>
                                        {{ $tarif->golongan->nama ?? 'Golongan '.$tarif->golongan_id }} — Tarif: Rp {{ number_format($tarif->dinas) }} - {{ number_format($tarif->makan) }} - {{ number_format($tarif->hotel) }} / hari
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Form Input 4 Kolom --}}
                        <div class="grid grid-cols-4 gap-3">

                            {{-- DINAS --}}
                            <div>
                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    Dinas / Hari
                                </label>
                                <div class="currency-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input
                                        id="inputDinas"
                                        name="uang_dinas"
                                        type="text"
                                        class="currency-input input-biaya"
                                        value="{{ number_format($ilpd->detail_ilpd->dinas ?? 0, 0, ',', '.') }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- MAKAN --}}
                            <div>
                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    Makan / Hari
                                </label>
                                <div class="currency-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input
                                        id="inputMakan"
                                        name="uang_makan"
                                        type="text"
                                        class="currency-input input-biaya"
                                        value="{{ number_format($ilpd->detail_ilpd->makan ?? 0, 0, ',', '.') }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- HOTEL --}}
                            <div>
                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    Hotel / Malam
                                </label>
                                <div class="currency-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input
                                        id="inputHotel"
                                        name="uang_hotel"
                                        type="text"
                                        class="currency-input input-biaya"
                                        value="{{ number_format($ilpd->detail_ilpd->hotel ?? 0, 0, ',', '.') }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- LAUNDRY --}}
                            <div>
                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    Laundry
                                </label>
                                <div class="currency-wrapper">
                                    <input
                                        id="laundry"
                                        type="text"
                                        class="currency-input readonly-input"
                                        value="Actual"
                                        readonly>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- BIAYA LAINNYA --}}
                    <div>

                        <h3 class="mb-4 text-[13px] font-semibold">
                            Biaya Lainnya (Opsional)
                        </h3>

                        {{-- TRANSPORT LOKAL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Transport Lokal
                            </span>

                            <div class="currency-wrapper expense-input w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="transportLokal"
                                    name="transport_lokal"
                                    type="text"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

                            </div>

                        </div>

                        {{-- VISA --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Visa
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="visa"
                                    name="visa"
                                    type="text"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

                            </div>

                        </div>

                        {{-- FISKAL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Fiskal
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="fiskal"
                                    name="fiskal"
                                    type="text"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

                            </div>

                        </div>

                        {{-- AIRPORT TAX --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Airport Tax
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="airportTax"
                                    name="airport_tax"
                                    type="text"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

                            </div>

                        </div>

                        {{-- PARKIR & TOLL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Parkir & Toll
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="parkirToll"
                                    name="parkir&toll"
                                    type="text"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

                            </div>

                        </div>

                        {{-- ENTERTAINMENT --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Entertainment
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    type="text"
                                    id="entertainment"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

                            </div>

                        </div>

                        {{-- DLL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <input
                                id="dll"
                                name="dll"
                                type="text"
                                placeholder="Dll..."
                                class="w-[120px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[13px] outline-none">

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="biayaLainnya"
                                    type="text"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

                            </div>

                        </div>

                        {{-- TOTAL --}}
                        <div class="mt-5 flex items-center justify-between border-t border-[#e2e8f0] pt-5">
                            <span class="text-[13px] font-semibold">
                                Total
                            </span>

                            <div class="currency-wrapper w-[220px]">
                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="total"
                                    name="total_biaya"
                                    type="text"
                                    value="{{ number_format($ilpd->detailIlpd->total ?? 0, 0, ',', '.') }}"
                                    class="currency-input readonly-input font-semibold"
                                    readonly>
                            </div>
                        </div>

                        {{-- UANG MUKA --}}
                        <div class="mt-3 flex items-center justify-between">
                            <div>
                                <span class="text-[13px] font-semibold">
                                    Uang Muka
                                </span>

                                <p class="mt-1 text-[10px] text-[#94a3b8]">
                                    Otomatis berdasarkan total perkiraan biaya
                                </p>
                            </div>

                            <div class="currency-wrapper w-[220px]">
                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="uangMuka"
                                    name="uang_muka"
                                    type="text"
                                    value="{{ number_format($ilpd->detail_ilpd->total ?? 0, 0, ',', '.') }}"
                                    class="currency-input readonly-input font-semibold"
                                    readonly>
                            </div>
                        </div>
                    </div>

                </section>

                {{-- BUTTON --}}
                <div class="button-wrapper flex justify-end gap-3 pb-6 pt-6">

                    <a href="/dashboard"
                        class="rounded-[10px] border border-[#94a3b8] bg-white px-6 py-3 text-[14px] font-semibold text-[#64748b]">
                        Batal
                    </a>

                    <button type="submit"
                        class="flex items-center gap-2 rounded-[10px] bg-[#2563eb] px-6 py-3 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

</body>
</html>

@endsection

@push('scripts')

<script>
    // ============================================================
    // 1. UPDATE NAMA FILE TIKET
    // ============================================================
    function updateFileName(input) {
        input.setCustomValidity('');

        const label = document.getElementById('file-label');

        if (input.files && input.files[0]) {
            label.innerText = 'File Terpilih: ' + input.files[0].name;
            label.classList.add('text-[#2563eb]');
        } else {
            label.innerText = 'Upload tiket perjalanan';
            label.classList.remove('text-[#2563eb]');
        }
    }


    // ============================================================
    // 2. PARSE RUPIAH
    // ============================================================
    function parseRupiah(val) {
        if (!val) return 0;

        return parseInt(
            val.toString().replace(/[^0-9]/g, '')
        ) || 0;
    }


    // ============================================================
    // 3. FORMAT RUPIAH
    // ============================================================
    function formatRupiah(angka) {
        if (isNaN(angka) || angka === null || angka === 0) {
            return '';
        }

        return new Intl.NumberFormat('id-ID').format(angka);
    }


    // ============================================================
    // 4. TOGGLE PENYESUAIAN TARIF
    // ============================================================
    function toggleAdjustTarif() {
        const wrapper = document.getElementById('wrapperDropdownGolongan');

        if (wrapper) {
            wrapper.classList.toggle('hidden');
        }
    }


    // ============================================================
    // 5. HITUNG TOTAL
    // ============================================================
    function hitungTotal() {

        // --------------------------------------------------------
        // DURASI PERJALANAN
        // --------------------------------------------------------
        const durasiPerjalanan = {{ $ilpd->sppd->durasi ?? 1 }};
        const durasi = Number(durasiPerjalanan) || 1;


        // --------------------------------------------------------
        // A. TARIF HARIAN
        //
        // Dinas, Makan, Hotel = tarif per hari/malam
        // sehingga dikalikan dengan durasi
        // --------------------------------------------------------

        const inputDinas = document.getElementById('inputDinas');
        const inputMakan = document.getElementById('inputMakan');
        const inputHotel = document.getElementById('inputHotel');

        const dinasPerHari = inputDinas
            ? parseRupiah(inputDinas.value)
            : 0;

        const makanPerHari = inputMakan
            ? parseRupiah(inputMakan.value)
            : 0;

        const hotelPerMalam = inputHotel
            ? parseRupiah(inputHotel.value)
            : 0;


        const totalDinas = dinasPerHari * durasi;
        const totalMakan = makanPerHari * durasi;
        const totalHotel = hotelPerMalam * durasi;


        // --------------------------------------------------------
        // B. BIAYA OPSIONAL
        //
        // Kosong = 0
        // Tidak wajib diisi
        // --------------------------------------------------------

        const biayaOpsionalIds = [
            'bbm',
            'transportLokal',
            'visa',
            'fiskal',
            'airportTax',
            'parkirToll',
            'entertainment',
            'biayaLainnya'
        ];

        let totalOpsional = 0;

        biayaOpsionalIds.forEach(id => {
            const input = document.getElementById(id);

            if (input) {
                totalOpsional += parseRupiah(input.value);
            }
        });


        // --------------------------------------------------------
        // C. GRAND TOTAL
        // --------------------------------------------------------

        const grandTotal =
            totalDinas +
            totalMakan +
            totalHotel +
            totalOpsional;


        // --------------------------------------------------------
        // D. UPDATE TOTAL
        // --------------------------------------------------------

        const elementTotal =
            document.getElementById('total') ||
            document.getElementById('inputTotal') ||
            document.getElementById('totalDisplay');

        if (elementTotal) {

            if (elementTotal.tagName === 'INPUT') {
                elementTotal.value = formatRupiah(grandTotal);
            } else {
                elementTotal.innerText = 'Rp ' + formatRupiah(grandTotal);
            }
        }


        // --------------------------------------------------------
        // E. UPDATE UANG MUKA
        //
        // Uang muka mengikuti total
        // --------------------------------------------------------

        const elementUangMuka =
            document.getElementById('uangMuka');

        if (elementUangMuka) {
            elementUangMuka.value = formatRupiah(grandTotal);
        }
    }


    // ============================================================
    // 6. APPLY TARIF OVERRIDE
    // ============================================================
    function applyTarifOverride(select) {

        const selectedOption =
            select.options[select.selectedIndex];

        if (!selectedOption.value) return;


        const dinas =
            selectedOption.getAttribute('data-dinas');

        const makan =
            selectedOption.getAttribute('data-makan');

        const hotel =
            selectedOption.getAttribute('data-hotel');


        const inputDinas =
            document.getElementById('inputDinas');

        const inputMakan =
            document.getElementById('inputMakan');

        const inputHotel =
            document.getElementById('inputHotel');

        if (inputDinas && dinas !== null) {
            inputDinas.value = formatRupiah(dinas);
        }

        if (inputMakan && makan !== null) {
            inputMakan.value = formatRupiah(makan);
        }

        if (inputHotel && hotel !== null) {
            inputHotel.value = formatRupiah(hotel);
        }
        
        // if (inputDinas && dinas !== null) {
        //     inputDinas.value = formatRupiah(
        //         parseRupiah(dinas)
        //     );
        // }

        // if (inputMakan && makan !== null) {
        //     inputMakan.value = formatRupiah(
        //         parseRupiah(makan)
        //     );
        // }

        // if (inputHotel && hotel !== null) {
        //     inputHotel.value = formatRupiah(
        //         parseRupiah(hotel)
        //     );
        // }


        // Langsung hitung ulang
        hitungTotal();
    }


    // ============================================================
    // 7. FORMAT INPUT + REALTIME CALCULATOR
    // ============================================================
    document.addEventListener('DOMContentLoaded', function () {

        const inputIds = [
            'bbm',
            'inputDinas',
            'inputMakan',
            'inputHotel',
            'transportLokal',
            'visa',
            'fiskal',
            'airportTax',
            'parkirToll',
            'entertainment',
            'biayaLainnya'
        ];


        // --------------------------------------------------------
        // FORMAT NILAI AWAL
        // --------------------------------------------------------

        inputIds.forEach(id => {

            const input = document.getElementById(id);

            if (input && input.value) {
                input.value = formatRupiah(
                    parseRupiah(input.value)
                );
            }
        });


        // --------------------------------------------------------
        // REALTIME INPUT
        // --------------------------------------------------------

        inputIds.forEach(id => {

            const input = document.getElementById(id);

            if (!input) return;


            input.addEventListener('input', function () {

                const value = parseRupiah(this.value);

                this.value = value
                    ? formatRupiah(value)
                    : '';


                // LANGSUNG HITUNG
                hitungTotal();
            });
        });


        // --------------------------------------------------------
        // HITUNG SAAT HALAMAN PERTAMA DIBUKA
        // --------------------------------------------------------

        hitungTotal();


        // --------------------------------------------------------
        // BERSIHKAN FORMAT RUPIAH SEBELUM SUBMIT
        // --------------------------------------------------------

        const formApprove =
            document.querySelector('form');

        if (formApprove) {

            formApprove.addEventListener('submit', function () {

                inputIds.forEach(id => {

                    const input =
                        document.getElementById(id);

                    if (input) {
                        input.value =
                            parseRupiah(input.value);
                    }
                });


                const total =
                    document.getElementById('total');

                if (total) {
                    total.value =
                        parseRupiah(total.value);
                }


                const uangMuka =
                    document.getElementById('uangMuka');

                if (uangMuka) {
                    uangMuka.value =
                        parseRupiah(uangMuka.value);
                }
            });
        }
    });
</script>
{{-- <script>
    function updateFileName(input) {
        const label = document.getElementById('file-label');
        if (input.files && input.files[0]) {
            label.innerText = 'File Terpilih: ' + input.files[0].name;
            label.classList.add('text-[#2563eb]');
        } else {
            label.innerText = 'Upload tiket perjalanan';
            label.classList.remove('text-[#2563eb]');
        }
    }

    // Helper: Membersihkan format Rupiah (titik/koma) menjadi angka murni
    function parseRupiah(val) {
        if (!val) return 0;
        return parseInt(val.toString().replace(/[^0-9]/g, '')) || 0;
    }

    // Helper: Format angka murni ke format ribuan Indonesia (contoh: 1500000 -> 1.500.000)
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function toggleAdjustTarif() {
        const wrapper = document.getElementById('wrapperDropdownGolongan');
        wrapper.classList.toggle('hidden');
    }

    function applyTarifOverride(select) {
        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption.value) return;

        // Ambil data dari atribut option yang dipilih
        const dinas = selectedOption.getAttribute('data-dinas');
        const makan = selectedOption.getAttribute('data-makan');
        const hotel = selectedOption.getAttribute('data-hotel');

        // Update nilai input
        document.getElementById('inputDinas').value = formatRupiah(dinas);
        document.getElementById('inputMakan').value = formatRupiah(makan);
        document.getElementById('inputHotel').value = formatRupiah(hotel);
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

</script> --}}

    {{-- @vite('resources/js/ilpd.js') --}}

@endpush