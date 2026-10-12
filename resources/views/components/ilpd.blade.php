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
                            {{-- value="{{ $ilpd->sppd->kota->name ?? '-' }}" --}}
                            value="{{ $sppd->kota_dan_unit }}"
                            class="form-input flex-1"
                            disabled>
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
                        <label class="mb-2 block text-[13px] font-semibold">
                            Transportasi <span class="text-red-500">*</span>
                        </label>

                        <div class="transport-grid grid grid-cols-3 gap-x-6 gap-y-3">
                            @foreach ($transports as $transport)
                                {{-- pointer-events-none mengunci klik, cursor-default menjaga tampilan kursor biasa --}}
                                <label class="flex items-center gap-2 pointer-events-none cursor-default">
                                    <input
                                        type="checkbox"
                                        name="transport_id[]"
                                        value="{{ $transport->id }}"
                                        class="size-[18px] accent-[#2563eb]"
                                        {{ in_array($transport->id, $sppd->transport_id ?? []) ? 'checked' : '' }}
                                        readonly>
                                    <span class="text-[14px] text-slate-800">
                                        {{ $transport->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        
                        {{-- Transportasi Lainnya --}}
                        <div class="mt-2 flex items-center gap-3">
                            <label class="flex items-center gap-2 pointer-events-none cursor-default">
                                <input 
                                    type="checkbox"
                                    class="size-[18px] accent-[#2563eb]"
                                    {{ !empty($sppd->transport_lainnya) ? 'checked' : '' }}
                                    readonly>
                                <span class="text-[14px] text-slate-800">
                                    Lainnya
                                </span>
                            </label>

                            <input
                                type="text"
                                name="transport_lainnya"
                                value="{{ $sppd->transport_lainnya }}"
                                placeholder="Sebutkan..."
                                class="w-[180px] rounded-md border border-[#cbd5e1] bg-slate-50 px-3 py-2 text-[12px] outline-none text-slate-800 cursor-default"
                                readonly>
                        </div>
                    </div>

                    {{-- KEPERLUAN --}}
                    <div class="mb-4">
                        <label class="mb-2 block text-[13px] font-semibold">
                            Keperluan <span class="text-red-500">*</span>
                        </label>

                        <div class="keperluan-grid grid grid-cols-3 gap-x-6 gap-y-3">
                            @foreach ($keperluans as $keperluan)
                                {{-- pointer-events-none mengunci klik, cursor-default menjaga tampilan kursor biasa --}}
                                <label class="flex items-center gap-2 pointer-events-none cursor-default">
                                    <input
                                        type="checkbox"
                                        name="keperluan_id[]"
                                        value="{{ $keperluan->id }}"
                                        class="size-[18px] accent-[#2563eb]"
                                        {{ in_array($keperluan->id, $sppd->keperluan_id ?? []) ? 'checked' : '' }}
                                        readonly>
                                    <span class="text-[14px] text-slate-800">
                                        {{ $keperluan->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        
                        {{-- Keperluan Lainnya --}}
                        <div class="mt-2 flex items-center gap-3">
                            <label class="flex items-center gap-2 pointer-events-none cursor-default">
                                <input 
                                    type="checkbox"
                                    class="size-[18px] accent-[#2563eb]"
                                    {{ !empty($sppd->keperluan_lainnya) ? 'checked' : '' }}
                                    readonly>
                                <span class="text-[14px] text-slate-800">
                                    Lainnya
                                </span>
                            </label>

                            <input
                                type="text"
                                name="keperluan_lainnya"
                                value="{{ $sppd->keperluan_lainnya }}"
                                placeholder="-"
                                class="w-[180px] rounded-md border border-[#cbd5e1] bg-slate-50 px-3 py-2 text-[12px] outline-none text-slate-800 cursor-default"
                                readonly>
                        </div>
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
                                value="{{ number_format(optional($sppd->ilpd->detail_ilpd)->bbm, 0, '', '') }}"
                                placeholder="Masukkan jumlah biaya BBM"
                                class="currency-input expense-input"
                                oninput="formatRupiah(this); hitungTotal()"
                                disabled>

                        </div>

                    </div>

                    {{-- UANG HARIAN --}}
                    <div class="mb-6">
                        <div class="mb-3 flex items-center justify-between border-b border-slate-100 pb-2">
                            <label class="text-xs font-bold text-slate-800">
                                Uang Harian (Golongan & Destinasi) <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[11px] font-medium text-slate-500">
                                Durasi SPPD: <strong class="text-slate-700">{{ $sppd->durasi ?? 1 }} Hari</strong>
                            </span>
                        </div>

                        <input type="hidden" id="durasi_dinas" value="{{ $sppd->durasi ?? 1 }}">

                        <div class="flex flex-col gap-3">

                            {{-- 1. DINAS --}}
                            <div class="flex items-end justify-between">
                                <div class="flex items-end gap-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-slate-500">Dinas</label>
                                        <div class="currency-wrapper expense-input w-[160px]">
                                            <span class="currency-prefix text-xs">Rp</span>
                                            <input id="dinas_rate" name="uang_dinas" type="text" class="currency-input expense-input text-xs" 
                                                value="{{ number_format($tarif->dinas ?? 0, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <span class="mb-2 text-xs font-semibold text-slate-400">×</span>

                                    <div>
                                        <label class="mb-1 block text-center text-xs font-medium text-slate-500">Hari</label>
                                        <div class="w-[60px]">
                                            <input id="dinas_qty" name="dinas_qty" type="text" 
                                                class="h-[38px] w-full rounded-md border border-slate-200 bg-slate-100 text-center text-xs font-semibold text-slate-600 focus:outline-none" 
                                                value="{{ $sppd->durasi ?? 1 }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-right text-xs font-medium text-slate-500">Subtotal Dinas</label>
                                    <div class="currency-wrapper expense-input w-[220px]">
                                        <span class="currency-prefix text-xs">Rp</span>
                                        <input id="dinas_subtotal" type="text" class="currency-input expense-input text-xs font-bold text-slate-800 bg-slate-50 text-right" value="0" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. MAKAN --}}
                            <div class="flex items-end justify-between">
                                <div class="flex items-end gap-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-slate-500">Makan</label>
                                        <div class="currency-wrapper expense-input w-[160px]">
                                            <span class="currency-prefix text-xs">Rp</span>
                                            <input id="makan_rate" name="uang_makan" type="text" class="currency-input expense-input text-xs" 
                                                value="{{ number_format($tarif->makan ?? 0, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <span class="mb-2 text-xs font-semibold text-slate-400">×</span>

                                    <div>
                                        <label class="mb-1 block text-center text-xs font-medium text-slate-500">Hari</label>
                                        <div class="w-[60px]">
                                            <input id="makan_qty" name="makan_qty" type="text" 
                                                class="h-[38px] w-full rounded-md border border-slate-200 bg-slate-100 text-center text-xs font-semibold text-slate-600 focus:outline-none" 
                                                value="{{ $sppd->ilpd->detail_ilpd->makan_qty ?? 1 }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-right text-xs font-medium text-slate-500">Subtotal Makan</label>
                                    <div class="currency-wrapper expense-input w-[220px]">
                                        <span class="currency-prefix text-xs">Rp</span>
                                        <input id="makan_subtotal" type="text" class="currency-input expense-input text-xs font-bold text-slate-800 bg-slate-50 text-right" value="0" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. HOTEL --}}
                            <div class="flex items-end justify-between">
                                <div class="flex items-end gap-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-slate-500">Hotel</label>
                                        <div class="currency-wrapper expense-input w-[160px]">
                                            <span class="currency-prefix text-xs">Rp</span>
                                            <input id="hotel_rate" name="uang_hotel" type="text" class="currency-input expense-input text-xs" 
                                                value="{{ number_format($tarif->hotel ?? 0, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <span class="mb-2 text-xs font-semibold text-slate-400">×</span>

                                    <div>
                                        <label class="mb-1 block text-center text-xs font-medium text-slate-500">Hari</label>
                                        <div class="w-[60px]">
                                            <input id="hotel_qty" name="hotel_qty" type="text" 
                                                class="h-[38px] w-full rounded-md border border-slate-200 bg-slate-100 text-center text-xs font-semibold text-slate-600 focus:outline-none" 
                                                value="{{ $sppd->ilpd->detail_ilpd->hotel_qty ?? 1 }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-right text-xs font-medium text-slate-500">Subtotal Hotel</label>
                                    <div class="currency-wrapper expense-input w-[220px]">
                                        <span class="currency-prefix text-xs">Rp</span>
                                        <input id="hotel_subtotal" type="text" class="currency-input expense-input text-xs font-bold text-slate-800 bg-slate-50 text-right" value="0" readonly>
                                    </div>
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

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="transportLokal"
                                    name="transport_lokal"
                                    type="text"
                                    value="{{ number_format(optional($sppd->ilpd->detail_ilpd)->transport_lokal, 0, '', '') }}"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                    disabled>

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
                                    value="{{ number_format(optional($sppd->ilpd->detail_ilpd)->visa, 0, '', '') }}"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                    disabled>

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
                                    value="{{ number_format(optional($sppd->ilpd->detail_ilpd)->fiskal, 0, '', '') }}"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                    disabled>

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
                                    value="{{ number_format(optional($sppd->ilpd->detail_ilpd)->airport_tax, 0, '', '') }}"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                    disabled>

                            </div>

                        </div>

                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Laundry
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <input
                                    id="laundry"
                                    type="text"
                                    class="currency-input text-center readonly-input"
                                    value="Actual"
                                    readonly>

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
                                    value="{{ number_format(optional($sppd->ilpd->detail_ilpd)->parkir_toll, 0, '', '') }}"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                    disabled>

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
                                    value="{{ number_format(optional($sppd->ilpd->detail_ilpd)->entertainment, 0, '', '') }}"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                    disabled>

                            </div>

                        </div>

                        {{-- DLL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <input
                                id="dll"
                                name="dll"
                                type="text"
                                placeholder="Dll..."
                                class="w-[120px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[13px] outline-none"
                                disabled>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="biayaLainnya"
                                    type="text"
                                    value="{{ number_format(optional($sppd->ilpd->detail_ilpd)->dll, 0, '', '') }}"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                    disabled>

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
                                    value="{{ number_format($ilpd->detail_ilpd->total ?? 0, 0, ',', '.') }}"
                                    class="currency-input readonly-input font-semibold"
                                    disabled>
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
                                    disabled>
                            </div>
                        </div>
                    </div>

                </section>

            </form>

        </div>

    </main>

</div>

</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function parseRupiah(val) {
        if (!val) return 0;
        return parseFloat(val.toString().replace(/[^0-9]/g, '')) || 0;
    }

    function formatRupiah(val) {
        return new Intl.NumberFormat('id-ID').format(val);
    }

    function calculateSubtotals() {
        // 1. Dinas
        const dinasRate = parseRupiah(document.getElementById('dinas_rate')?.value);
        const dinasQty = parseFloat(document.getElementById('dinas_qty')?.value) || 0;
        document.getElementById('dinas_subtotal').value = formatRupiah(dinasRate * dinasQty);

        // 2. Makan
        const makanRate = parseRupiah(document.getElementById('makan_rate')?.value);
        const makanQty = parseFloat(document.getElementById('makan_qty')?.value) || 0;
        document.getElementById('makan_subtotal').value = formatRupiah(makanRate * makanQty);

        // 3. Hotel
        const hotelRate = parseRupiah(document.getElementById('hotel_rate')?.value);
        const hotelQty = parseFloat(document.getElementById('hotel_qty')?.value) || 0;
        document.getElementById('hotel_subtotal').value = formatRupiah(hotelRate * hotelQty);
    }

    calculateSubtotals();
});
</script>