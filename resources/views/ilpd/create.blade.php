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

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

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

            <form
                    id="ilpdForm"
                    action="{{ route('ilpd.store') }}"
                    method="POST"
                    class="space-y-0">
                    @csrf
                {{-- =================================================
                    SECTION 1
                ================================================== --}}
                <section
                    class="section-card rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]">

                    <div class="mb-5 flex items-center gap-3">

                        <div
                            class="flex size-7 items-center justify-center rounded-full bg-[#2563eb] text-[14px] font-bold text-white"
                        >
                            1
                        </div>

                        <h2 class="text-[15px] font-bold uppercase">
                            Rencana Perjalanan Dinas
                        </h2>

                    </div>

                    {{-- KOTA --}}
                    <div class="mb-4">

                        <label
                            for="kota"
                            class="mb-1.5 block text-[13px] font-semibold"
                        >
                            Kota Tujuan <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="kota"
                            name="kota"
                            type="text"
                            value="{{ $sppd->kota->name }}"
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
                                    value="{{ $sppd->tanggal_awal?->format('Y-m-d') ?? '' }}"
                                    onchange="hitunglSemua()"
                                >
                            </div>

                            <span class="text-[14px] font-semibold text-[#64748b]">s/d</span>

                            {{-- TANGGAL SELESAI (READONLY/DISABLED KARENA OTOMATIS) --}}
                            <div class="flex flex-1 items-center rounded-lg border border-[#cbd5e1] bg-gray-50 px-3.5 py-2.5">
                                <input
                                    id="tanggalSelesai"
                                    name="tanggal_akhir"
                                    type="date"
                                    class="w-full border-0 bg-transparent text-[14px] outline-none"
                                    value="{{ $sppd->tanggal_akhir?->format('Y-m-d') ?? '' }}"
                                    readonly
                                >
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
                            {{-- Loop data resmi dari tabel 'transports' --}}
                            @foreach ($transports as $transport)
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="transport_id"
                                        value="{{ $transport->id }}"
                                        class="size-[18px] accent-[#2563eb]"
                                        {{-- Jika transport_id dari $sppd sama dengan ID transport ini, otomatis tercentang --}}
                                        {{ $sppd->transport_id == $transport->id ? 'checked' : '' }}
                                    >
                                    <span class="text-[14px]">
                                        {{ $transport->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        
                        <div class="mt-2 flex items-center gap-3">

                            <label class="flex items-center gap-2">

                                <input
                                    type="checkbox"
                                    class="size-[18px] accent-[#2563eb]"
                                >

                                <span class="text-[14px]">
                                    Lainnya
                                </span>

                            </label>

                            <input
                                type="text"
                                placeholder="Sebutkan..."
                                class="w-[180px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[12px] outline-none">

                        </div>
                        {{-- <input
                            type="text"
                            placeholder="Sebutkan..."
                            class="mt-2 w-[180px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[12px] outline-none"> --}}

                    </div>

                    {{-- KEPERLUAN --}}
                    <div class="mb-4">

                        <label class="mb-2 block text-[13px] font-semibold">
                            Keperluan <span class="text-red-500">*</span>
                        </label>

                        <div class="keperluan-grid grid grid-cols-3 gap-x-6 gap-y-3">
                            {{-- Loop data resmi dari tabel 'keperluans' --}}
                            @foreach ($keperluans as $keperluan)
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="keperluan_id"
                                        value="{{ $keperluan->id }}"
                                        class="size-[18px] accent-[#2563eb]"
                                        {{-- Jika keperluan_id dari $sppd sama dengan ID keperluan ini, otomatis tercentang --}}
                                        {{ $sppd->keperluan_id == $keperluan->id ? 'checked' : '' }}
                                    >
                                    <span class="text-[14px]">
                                        {{ $keperluan->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-2 flex items-center gap-3">

                            <label class="flex items-center gap-2">

                                <input
                                    type="checkbox"
                                    class="size-[18px] accent-[#2563eb]"
                                >

                                <span class="text-[14px]">
                                    Lainnya
                                </span>

                            </label>

                            <input
                                type="text"
                                placeholder="Sebutkan..."
                                class="w-[180px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[12px] outline-none">

                        </div>

                    </div>

                    {{-- TUGAS --}}
                    <div>
                        <label for="tugas" class="mb-1.5 block text-[13px] font-semibold">
                            Tugas <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            id="tugas"
                            name="tugas"
                            rows="4"
                            class="form-textarea"
                            placeholder="1.&#10;2.&#10;3."
                        >{{ $sppd->tugas ?? '' }}</textarea>

                        <p class="mt-1 text-[11px] text-[#64748b]">
                            Jelaskan tugas yang akan dilakukan selama perjalanan dinas (maks. 3 poin)
                        </p>
                    </div>

                </section>

                {{-- =================================================
                    SECTION 2
                ================================================== --}}
                <section
                    class="section-card mt-5 rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]">

                    <div class="mb-5 flex items-center gap-3">

                        <div
                            class="flex size-7 items-center justify-center rounded-full bg-[#2563eb] text-[14px] font-bold text-white"
                        >
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
                                type="text"
                                inputmode="numeric"
                                placeholder="Masukkan jumlah biaya BBM"
                                class="currency-input expense-input"
                                oninput="formatRupiah(this); hitungTotal()"
                            >

                        </div>

                    </div>

                    {{-- UANG HARIAN --}}
                    <div class="mb-6">

                        <label class="mb-3 block text-[13px] font-semibold">
                            Uang Harian (sesuai golongan & destinasi)
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="grid grid-cols-4 gap-3">

                            {{-- DINAS --}}
                            <div>
                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    Dinas / Hari
                                </label>
                                <div class="currency-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input
                                        id="dinas"
                                        name="uang_dinas"
                                        type="text"
                                        class="currency-input"
                                        value="{{ number_format($tarif->dinas ?? 0, 0, ',', '.') }}"
                                        readonly
                                    >
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
                                        id="makan"
                                        name="uang_makan"
                                        type="text"
                                        class="currency-input"
                                        value="{{ number_format($tarif->makan ?? 0, 0, ',', '.') }}"
                                        readonly
                                    >
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
                                        id="hotel"
                                        name="uang_hotel"
                                        type="text"
                                        class="currency-input"
                                        value="{{ number_format($tarif->hotel ?? 0, 0, ',', '.') }}"
                                        readonly
                                    >
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
                                        readonly
                                    >

                                </div>

                            </div>

                        </div>

                        <p class="mt-2 text-[11px] text-[#64748b]">
                            Tarif Dinas, Makan, dan Hotel disesuaikan dengan golongan pegawai dan kota tujuan.
                        </p>

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
                                    type="text"
                                    id="transportLokal"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                >

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
                                    type="text"
                                    id="visa"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                >

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
                                    type="text"
                                    id="fiskal"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                >

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
                                    type="text"
                                    id="airportTax"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                >

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
                                    type="text"
                                    id="parkirToll"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                >

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
                                    oninput="formatRupiah(this); hitungTotal()"
                                >

                            </div>

                        </div>

                        {{-- DLL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <input
                                id="namaLainnya"
                                type="text"
                                placeholder="Dll..."
                                class="w-[120px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[13px] outline-none"
                            >

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="biayaLainnya"
                                    type="text"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()"
                                >

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
                                    value="0"
                                    class="currency-input readonly-input font-semibold"
                                    readonly
                                >
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
                                    value="0"
                                    class="currency-input readonly-input font-semibold"
                                    readonly
                                >
                            </div>
                        </div>
                        {{-- TOTAL --}}
                        {{-- <div class="mt-5 flex items-center justify-between border-t border-[#e2e8f0] pt-5">

                            <span class="text-[13px] font-semibold">
                                Total
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="total"
                                    type="text"
                                    value="0"
                                    class="currency-input readonly-input font-semibold"
                                    readonly
                                >

                            </div>

                        </div> --}}

                        {{-- UANG MUKA --}}
                        {{-- <div class="mt-3 flex items-center justify-between">

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
                                    type="text"
                                    value="0"
                                    class="currency-input readonly-input font-semibold"
                                    readonly
                                >

                            </div>

                        </div> --}}

                    </div>

                </section>

                <input type="hidden" name="sppd_id" value="{{ $sppd->id }}">

                {{-- BUTTON --}}
                <div class="button-wrapper flex justify-end gap-3 pb-6 pt-6">

                    <a
                        href="/dashboard"
                        class="rounded-[10px] border border-[#94a3b8] bg-white px-6 py-3 text-[14px] font-semibold text-[#64748b]"
                    >
                        Batal
                    </a>

                    <button type="submit"
                        class="flex items-center gap-2 rounded-[10px] bg-[#2563eb] px-6 py-3 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]"
                    >
                        <span>
                            
                        </span>

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
    document.addEventListener('DOMContentLoaded', function () {
        const inputMulai = document.getElementById('tanggalMulai');
        
        if (inputMulai) {
            // 1. Set minimal tanggal awal = H+1 (Besok)
            const besok = new Date();
            besok.setDate(besok.getDate() + 1);
            
            // Format ke YYYY-MM-DD
            const minDate = besok.toISOString().split('T')[0];
            inputMulai.setAttribute('min', minDate);

            // 2. Pasang event listener saat tanggal awal diubah
            inputMulai.addEventListener('change', hitungSemua);

            // 3. Jalankan perhitungan otomatis jika tanggalMulai sudah terisi saat halaman dimuat
            if (inputMulai.value) {
                hitungSemua();
            }
        }
    });

    // Helper: Membersihkan format Rupiah (titik/koma) menjadi angka murni
    function parseRupiah(val) {
        if (!val) return 0;
        return parseInt(val.toString().replace(/[^0-9]/g, '')) || 0;
    }

    // Helper: Format angka murni ke format ribuan Indonesia (contoh: 1500000 -> 1.500.000)
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Fungsi Utama: Menghitung Tanggal Selesai & Total Biaya Sekaligus
    function hitungSemua() {
        const inputMulai = document.getElementById('tanggalMulai');
        const inputSelesai = document.getElementById('tanggalSelesai');
        const info = document.getElementById('infoLamaPerjalanan');

        // Ambil durasi dari Blade (default: 1)
        const durasiHari = parseInt("{{ $sppd->durasi ?? 1 }}");

        if (!inputMulai || !inputMulai.value) return;

        // A. HITUNG TANGGAL SELESAI
        const tglMulai = new Date(inputMulai.value);
        const tglSelesai = new Date(tglMulai);
        tglSelesai.setDate(tglMulai.getDate() + (durasiHari - 1));

        const yyyy = tglSelesai.getFullYear();
        const mm = String(tglSelesai.getMonth() + 1).padStart(2, '0');
        const dd = String(tglSelesai.getDate()).padStart(2, '0');
        const formattedSelesai = `${yyyy}-${mm}-${dd}`;

        if (inputSelesai) inputSelesai.value = formattedSelesai;
        if (info) {
            info.innerText = `Total perjalanan: ${durasiHari} Hari (${inputMulai.value} s/d ${formattedSelesai})`;
            info.classList.remove('hidden');
        }

        // B. HITUNG TOTAL BIAYA & UANG MUKA
        const dinas = parseRupiah(document.getElementById('dinas')?.value);
        const makan = parseRupiah(document.getElementById('makan')?.value);
        const hotel = parseRupiah(document.getElementById('hotel')?.value);

        // Rumus: (Dinas + Makan + Hotel) x Durasi
        const totalPerHari = dinas + makan + hotel;
        const totalBiaya = totalPerHari * durasiHari;

        // Masukkan ke Input Total & Uang Muka
        const inputTotal = document.getElementById('total');
        const inputUangMuka = document.getElementById('uangMuka');

        if (inputTotal) inputTotal.value = formatRupiah(totalBiaya);
        if (inputUangMuka) inputUangMuka.value = formatRupiah(totalBiaya);
    }
</script>

    {{-- @vite('resources/js/ilpd.js') --}}

@endpush