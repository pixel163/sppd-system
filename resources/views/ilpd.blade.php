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

    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}
    <aside
        class="desktop-sidebar fixed left-0 top-0 z-10 flex h-screen w-[260px] flex-col gap-6 border-r border-[#e2e8f0] bg-white px-4 py-6"
    >

        {{-- BRAND --}}
        <div class="flex items-center gap-3 pl-3">

            <div class="flex size-9 items-center justify-center rounded-[10px] bg-[#0d6efd]">
                <span class="text-lg font-bold text-white">
                    S
                </span>
            </div>

            <div class="flex flex-col gap-px">
                <span class="text-[16px] font-bold">
                    SPPD System
                </span>

                <span class="text-[11px] text-[#64748b]">
                    Sistem Perjalanan Dinas
                </span>
            </div>

        </div>


        {{-- NAVIGATION --}}
        <nav class="sidebar-menu flex flex-col gap-1">

            <a
                href="/dashboard"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ▦
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dashboard
                </span>
            </a>


            <a
                href="/sppd"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ▤
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Pengajuan SPPD
                </span>
            </a>


            {{-- ACTIVE --}}
            <a
                href="/ilpd"
                class="flex items-center gap-3 rounded-xl bg-[#eff6ff] px-4 py-3"
            >
                <span class="text-[14px] text-[#2563eb]">
                    ☑
                </span>

                <span class="text-[14px] font-semibold text-[#0d6efd]">
                    Perizinan
                </span>
            </a>


            <a
                href="/riwayat"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ◷
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Riwayat Pengajuan
                </span>
            </a>


            <a
                href="/dokumen"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ▱
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dokumen & Tiket
                </span>
            </a>

        </nav>


        <div class="h-px w-full bg-[#e2e8f0]"></div>


        {{-- <nav class="sidebar-bottom flex flex-col gap-1">

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ○
                </span>

                <span class="text-[14px] text-[#64748b]">
                    Profile
                </span>
            </a>


            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ⚙
                </span>

                <span class="text-[14px] text-[#64748b]">
                    Pengaturan
                </span>
            </a>

        </nav> --}}

        <div class="flex-1"></div>

    </aside>


    {{-- =========================================================
        MAIN
    ========================================================== --}}
    <main class="main-content ml-[260px] min-h-screen flex-1">


        {{-- HEADER --}}
        <header
            class="top-navbar flex h-[66px] items-center justify-between border-b border-[#e2e8f0] px-10"
        >

            <div class="flex items-center gap-3">

                <a
                    href="/sppd/create"
                    class="flex size-[18px] items-center justify-center"
                >
                    <span class="text-lg text-[#64748b]">
                        ←
                    </span>
                </a>

                <span class="text-[14px] font-semibold">
                    Form ILPD
                </span>

            </div>


            <div class="flex items-center gap-5">

                {{-- Notification --}}
                <div
                    class="relative flex size-10 items-center justify-center rounded-full border border-[#e2e8f0]"
                >
                    <span class="text-[#64748b]">
                        ♢
                    </span>

                    <span
                        class="absolute -right-[3px] -top-[3px] flex size-[18px] items-center justify-center rounded-full bg-[#ef4444] text-[10px] font-bold text-white"
                    >
                        3
                    </span>
                </div>


                {{-- USER LOGIN --}}
                <div class="flex items-center gap-2.5">

                    <div
                        class="flex size-9 items-center justify-center rounded-full bg-[#eff6ff] text-[14px] font-bold text-[#2563eb]"
                    >
                        AR
                    </div>

                    <div class="user-info flex flex-col">

                        <span class="text-[14px] font-semibold">
                            Ahmad Ramzi
                        </span>

                        <span class="text-[12px] text-[#64748b]">
                            Staff IT
                        </span>

                    </div>

                    <span class="text-xs text-[#64748b]">
                        ▼
                    </span>

                </div>

            </div>

        </header>


        {{-- =====================================================
            CONTENT
        ====================================================== --}}
        <div class="content-wrapper px-10 pb-10 pt-[15px]">


            {{-- TITLE --}}
            <div class="mb-5">

                <h1 class="text-[24px] font-extrabold uppercase">
                    FORM ILPD
                </h1>

                <p class="mt-1 text-[14px] text-[#64748b]">
                    Lengkapi data perizinan perjalanan dinas Anda.
                </p>

            </div>


            {{-- =================================================
                SECTION 1
            ================================================== --}}
            <section
                class="section-card rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]"
            >

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
                        placeholder="Masukkan Kota Tujuan"
                        class="form-input"
                        autocomplete="off"
                        oninput="cariKota(this.value)"
                    >

                    {{-- AUTOCOMPLETE KOTA --}}
                    <div
                        id="hasilKota"
                        class="relative"
                    ></div>

                </div>


                {{-- LAMA PERJALANAN --}}
                <div class="mb-4">

                    <label
                        class="mb-1.5 block text-[13px] font-semibold"
                    >
                        Lama Perjalanan <span class="text-red-500">*</span>
                    </label>

                    <div class="date-wrapper flex items-center gap-4">

                        <div class="flex flex-1 items-center rounded-lg border border-[#cbd5e1] px-3.5 py-2.5">

                            <input
                                id="tanggalMulai"
                                type="date"
                                class="w-full border-0 text-[14px] outline-none"
                                onchange="hitungLamaPerjalanan()"
                            >

                        </div>

                        <span class="text-[14px] font-semibold text-[#64748b]">
                            s/d
                        </span>

                        <div class="flex flex-1 items-center rounded-lg border border-[#cbd5e1] px-3.5 py-2.5">

                            <input
                                id="tanggalSelesai"
                                type="date"
                                class="w-full border-0 text-[14px] outline-none"
                                onchange="hitungLamaPerjalanan()"
                            >

                        </div>

                    </div>

                    <p
                        id="infoLamaPerjalanan"
                        class="mt-1.5 hidden text-[11px] text-[#64748b]"
                    ></p>

                </div>


                {{-- TRANSPORTASI --}}
                <div class="mb-4">

                    <label class="mb-2 block text-[13px] font-semibold">
                        Transportasi <span class="text-red-500">*</span>
                    </label>

                    <div class="transport-grid grid grid-cols-3 gap-x-6 gap-y-3">

                        @foreach ([
                            'Pesawat',
                            'Kereta Api',
                            'Kapal Laut',
                            'Kendaraan Dinas',
                            'Kendaraan Pribadi',
                            'Lainnya'
                        ] as $transport)

                            <label class="flex items-center gap-2">

                                <input
                                    type="checkbox"
                                    name="transportasi[]"
                                    value="{{ $transport }}"
                                    class="size-[18px] accent-[#2563eb]"
                                >

                                <span class="text-[14px]">
                                    {{ $transport }}
                                </span>

                            </label>

                        @endforeach

                    </div>

                    <input
                        type="text"
                        placeholder="Sebutkan..."
                        class="mt-2 w-[180px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[12px] outline-none"
                    >

                </div>


                {{-- KEPERLUAN --}}
                <div class="mb-4">

                    <label class="mb-2 block text-[13px] font-semibold">
                        Keperluan <span class="text-red-500">*</span>
                    </label>

                    <div class="necessity-grid grid grid-cols-3 gap-x-6 gap-y-3">

                        @foreach ([
                            'Rapat / Meeting',
                            'Pelatihan / Training',
                            'Seminar / Workshop',
                            'Kunjungan Kerja',
                            'Negosiasi / Presentasi',
                            'Pameran / Event'
                        ] as $necessity)

                            <label class="flex items-center gap-2">

                                <input
                                    type="checkbox"
                                    name="keperluan[]"
                                    value="{{ $necessity }}"
                                    class="size-[18px] accent-[#2563eb]"
                                >

                                <span class="text-[14px]">
                                    {{ $necessity }}
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
                            class="w-[180px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[12px] outline-none"
                        >

                    </div>

                </div>


                {{-- TUGAS --}}
                <div>

                    <label
                        for="tugas"
                        class="mb-1.5 block text-[13px] font-semibold"
                    >
                        Tugas <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        id="tugas"
                        rows="4"
                        class="form-textarea"
                        placeholder="1.
2.
3."
                    ></textarea>

                    <p class="mt-1 text-[11px] text-[#64748b]">
                        Jelaskan tugas yang akan dilakukan selama perjalanan dinas (maks. 3 poin)
                    </p>

                </div>

            </section>


            {{-- =================================================
                SECTION 2
            ================================================== --}}
            <section
                class="section-card mt-5 rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]"
            >

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

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="dinas"
                                    type="text"
                                    class="currency-input"
                                    value="0"
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

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="makan"
                                    type="text"
                                    class="currency-input"
                                    value="0"
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

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="hotel"
                                    type="text"
                                    class="currency-input"
                                    value="0"
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
                                type="text"
                                value="0"
                                class="currency-input readonly-input font-semibold"
                                readonly
                            >

                        </div>

                    </div>

                </div>

            </section>


            {{-- BUTTON --}}
            <div class="button-wrapper flex justify-end gap-3 pb-6 pt-6">

                <a
                    href="/dashboard"
                    class="rounded-[10px] border border-[#94a3b8] bg-white px-6 py-3 text-[14px] font-semibold text-[#64748b]"
                >
                    Batal
                </a>


                <button
                    type="button"
                    onclick="simpanILPD()"
                    class="flex items-center gap-2 rounded-[10px] bg-[#2563eb] px-6 py-3 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]"
                >
                    <span>
                        ✓
                    </span>

                    Simpan Perizinan
                </button>

            </div>

        </div>

    </main>

</div>


<script>

/*
|--------------------------------------------------------------------------
| SIMULASI USER LOGIN
|--------------------------------------------------------------------------
| Nanti data ini berasal dari session/login backend.
|--------------------------------------------------------------------------
*/

const userLogin = {
    nama: "Ahmad Ramzi",
    jabatan: "Staff IT",
    golongan: "III"
};


/*
|--------------------------------------------------------------------------
| DATA KOTA
|--------------------------------------------------------------------------
| Dummy data untuk simulasi FE.
|--------------------------------------------------------------------------
*/

const dataKota = [
    "Jakarta",
    "Bandung",
    "Bogor",
    "Bekasi",
    "Depok",
    "Surabaya",
    "Yogyakarta",
    "Semarang",
    "Medan",
    "Bali"
];


/*
|--------------------------------------------------------------------------
| TARIF PERJALANAN
|--------------------------------------------------------------------------
| Simulasi hubungan:
|
| Golongan
|     +
| Kota
|     ↓
| Dinas
| Makan
| Hotel
|--------------------------------------------------------------------------
*/

const tarifPerjalanan = {

    "III": {

        "Jakarta": {
            dinas: 150000,
            makan: 150000,
            hotel: 500000
        },

        "Bandung": {
            dinas: 130000,
            makan: 130000,
            hotel: 400000
        },

        "Bogor": {
            dinas: 120000,
            makan: 120000,
            hotel: 350000
        },

        "Surabaya": {
            dinas: 160000,
            makan: 160000,
            hotel: 550000
        },

        "Yogyakarta": {
            dinas: 140000,
            makan: 140000,
            hotel: 450000
        }

    }

};


/*
|--------------------------------------------------------------------------
| SEARCH KOTA
|--------------------------------------------------------------------------
*/

function cariKota(keyword) {

    const container = document.getElementById("hasilKota");

    keyword = keyword.trim().toLowerCase();

    container.innerHTML = "";

    if (keyword === "") {

        resetTarif();

        return;
    }


    const hasil = dataKota.filter(kota =>
        kota.toLowerCase().includes(keyword)
    );


    if (hasil.length === 0) {

        container.innerHTML = `
            <div class="search-result">
                <div class="px-4 py-3 text-[13px] text-[#94a3b8]">
                    Kota tidak ditemukan.
                </div>
            </div>
        `;

        return;
    }


    container.innerHTML = `
        <div class="search-result">

            ${hasil.map(kota => `

                <button
                    type="button"
                    class="search-item"
                    onclick="pilihKota('${kota}')"
                >

                    <div class="text-[14px] font-semibold text-[#1e293b]">
                        ${kota}
                    </div>

                    <div class="mt-1 text-[11px] text-[#64748b]">
                        Lihat tarif perjalanan berdasarkan golongan
                    </div>

                </button>

            `).join("")}

        </div>
    `;
}


/*
|--------------------------------------------------------------------------
| PILIH KOTA
|--------------------------------------------------------------------------
*/

// function pilihKota(kota) {

//     document.getElementById("kota").value = kota;

//     document.getElementById("hasilKota").innerHTML = "";

//     isiTarif(kota);
// }

function pilihKota(kota) {

    document.getElementById("kota").value = kota;

    document.getElementById("hasilKota").innerHTML = "";

    // Jangan hitung tarif dulu.
    // Tarif baru dihitung setelah tanggal awal dan akhir diisi.
    cekDanHitungBiaya();
}


/*
|--------------------------------------------------------------------------
| ISI TARIF OTOMATIS
|--------------------------------------------------------------------------
*/

// function isiTarif(kota) {

//     const golongan = userLogin.golongan;

//     const tarifGolongan = tarifPerjalanan[golongan];

//     if (!tarifGolongan || !tarifGolongan[kota]) {

//         resetTarif();

//         return;
//     }


//     const tarif = tarifGolongan[kota];


//     document.getElementById("dinas").value =
//         formatAngka(tarif.dinas);

//     document.getElementById("makan").value =
//         formatAngka(tarif.makan);

//     document.getElementById("hotel").value =
//         formatAngka(tarif.hotel);


//     hitungTotal();
// }
function isiTarif(kota, jumlahHari, jumlahMalam) {

    const golongan =
        userLogin.golongan;


    const tarifGolongan =
        tarifPerjalanan[golongan];


    if (
        !tarifGolongan ||
        !tarifGolongan[kota]
    ) {

        resetTarif();

        return;
    }


    const tarif =
        tarifGolongan[kota];


    /*
    |--------------------------------------------------------------------------
    | HITUNG BIAYA
    |--------------------------------------------------------------------------
    */

    const totalDinas =
        tarif.dinas * jumlahHari;


    const totalMakan =
        tarif.makan * jumlahHari;


    const totalHotel =
        tarif.hotel * jumlahMalam;


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN HASIL
    |--------------------------------------------------------------------------
    */

    document.getElementById("dinas").value =
        formatAngka(totalDinas);


    document.getElementById("makan").value =
        formatAngka(totalMakan);


    document.getElementById("hotel").value =
        formatAngka(totalHotel);


    document.getElementById("laundry").value =
        "Actual";


    /*
    |--------------------------------------------------------------------------
    | HITUNG TOTAL KESELURUHAN
    |--------------------------------------------------------------------------
    */

    hitungTotal();
}


/*
|--------------------------------------------------------------------------
| RESET TARIF
|--------------------------------------------------------------------------
*/

function resetTarif() {

    document.getElementById("dinas").value = "0";
    document.getElementById("makan").value = "0";
    document.getElementById("hotel").value = "0";

    document.getElementById("laundry").value = "Actual";

    hitungTotal();
}


/*
|--------------------------------------------------------------------------
| FORMAT ANGKA MENJADI RUPIAH
|--------------------------------------------------------------------------
|
| 1500000
| ↓
| 1.500.000
|
|--------------------------------------------------------------------------
*/

function formatAngka(angka) {

    return new Intl.NumberFormat("id-ID").format(angka);
}


/*
|--------------------------------------------------------------------------
| FORMAT INPUT RUPIAH
|--------------------------------------------------------------------------
*/

function formatRupiah(input) {

    // Hanya angka
    let angka = input.value.replace(/\D/g, "");

    if (angka === "") {

        input.value = "";

        return;
    }


    // Hilangkan angka 0 yang tidak perlu
    angka = angka.replace(/^0+(?=\d)/, "");


    input.value = formatAngka(parseInt(angka));
}


/*
|--------------------------------------------------------------------------
| AMBIL NILAI DARI INPUT RUPIAH
|--------------------------------------------------------------------------
*/

function ambilAngka(id) {

    const element = document.getElementById(id);

    if (!element) {
        return 0;
    }


    const angka = element.value
        .replace(/\./g, "")
        .replace(/[^0-9]/g, "");


    return angka === "" ? 0 : parseInt(angka);
}


/*
|--------------------------------------------------------------------------
| HITUNG TOTAL
|--------------------------------------------------------------------------
|
| Semua biaya yang berupa nominal akan dijumlahkan.
|
| Dinas
| Makan
| Hotel
| BBM
| Transport Lokal
| Visa
| Fiskal
| Airport Tax
| Parkir & Toll
| Entertainment
| DLL
|--------------------------------------------------------------------------
*/

// function hitungTotal() {

//     const biaya = [

//         // Uang harian
//         ambilAngka("dinas"),
//         ambilAngka("makan"),
//         ambilAngka("hotel"),

//         // Biaya tambahan
//         ambilAngka("bbm"),
//         ambilAngka("transportLokal"),
//         ambilAngka("visa"),
//         ambilAngka("fiskal"),
//         ambilAngka("airportTax"),
//         ambilAngka("parkirToll"),
//         ambilAngka("entertainment"),
//         ambilAngka("biayaLainnya")

//     ];


//     const total = biaya.reduce(
//         (jumlah, nilai) => jumlah + nilai,
//         0
//     );


//     document.getElementById("total").value =
//         formatAngka(total);


//     /*
//     |--------------------------------------------------------------------------
//     | UANG MUKA
//     |--------------------------------------------------------------------------
//     | Untuk prototype:
//     | Uang muka = 100% dari total.
//     |
//     | Nanti bisa diubah sesuai aturan perusahaan.
//     |--------------------------------------------------------------------------
//     */

//     const uangMuka = total;

//     document.getElementById("uangMuka").value =
//         formatAngka(uangMuka);
// }
function hitungTotal() {

    const biaya = [

        // Biaya berdasarkan perjalanan
        ambilAngka("dinas"),
        ambilAngka("makan"),
        ambilAngka("hotel"),

        // Biaya tambahan
        ambilAngka("bbm"),
        ambilAngka("transportLokal"),
        ambilAngka("visa"),
        ambilAngka("fiskal"),
        ambilAngka("airportTax"),
        ambilAngka("parkirToll"),
        ambilAngka("entertainment"),
        ambilAngka("biayaLainnya")

    ];


    const total =
        biaya.reduce(
            (jumlah, nilai) => jumlah + nilai,
            0
        );


    document.getElementById("total").value =
        formatAngka(total);


    // Sementara untuk prototype:
    const uangMuka = total;


    document.getElementById("uangMuka").value =
        formatAngka(uangMuka);
}


/*
|--------------------------------------------------------------------------
| HITUNG LAMA PERJALANAN
|--------------------------------------------------------------------------
*/

// function hitungLamaPerjalanan() {

//     const mulai =
//         document.getElementById("tanggalMulai").value;

//     const selesai =
//         document.getElementById("tanggalSelesai").value;

//     const info =
//         document.getElementById("infoLamaPerjalanan");


//     if (!mulai || !selesai) {

//         info.classList.add("hidden");

//         return;
//     }


//     const tanggalMulai =
//         new Date(mulai);

//     const tanggalSelesai =
//         new Date(selesai);


//     if (tanggalSelesai < tanggalMulai) {

//         info.textContent =
//             "Tanggal selesai tidak boleh sebelum tanggal mulai.";

//         info.classList.remove("hidden");

//         info.classList.remove("text-[#64748b]");
//         info.classList.add("text-red-500");

//         return;
//     }


//     const selisih =
//         tanggalSelesai - tanggalMulai;


//     const jumlahHari =
//         Math.floor(
//             selisih / (1000 * 60 * 60 * 24)
//         ) + 1;


//     info.textContent =
//         `Lama perjalanan: ${jumlahHari} hari`;

//     info.classList.remove("hidden");

//     info.classList.remove("text-red-500");
//     info.classList.add("text-[#64748b]");
// }
function hitungLamaPerjalanan() {

    const mulai = document.getElementById("tanggalMulai").value;
    const selesai = document.getElementById("tanggalSelesai").value;

    const info = document.getElementById("infoLamaPerjalanan");

    // Kalau salah satu tanggal belum diisi
    if (!mulai || !selesai) {

        info.classList.add("hidden");

        resetTarif();

        return;
    }


    const tanggalMulai = new Date(mulai);
    const tanggalSelesai = new Date(selesai);


    // Tanggal akhir tidak boleh sebelum tanggal awal
    if (tanggalSelesai < tanggalMulai) {

        info.textContent =
            "Tanggal selesai tidak boleh sebelum tanggal mulai.";

        info.classList.remove("hidden");

        info.classList.remove("text-[#64748b]");
        info.classList.add("text-red-500");

        resetTarif();

        return;
    }


    // Hitung selisih tanggal
    const selisih =
        tanggalSelesai - tanggalMulai;


    // +1 karena tanggal awal ikut dihitung
    const jumlahHari =
        Math.floor(
            selisih / (1000 * 60 * 60 * 24)
        ) + 1;


    // Hotel menggunakan jumlah malam
    const jumlahMalam =
        Math.max(jumlahHari - 1, 0);


    info.textContent =
        `Lama perjalanan: ${jumlahHari} hari (${jumlahMalam} malam)`;

    info.classList.remove("hidden");

    info.classList.remove("text-red-500");
    info.classList.add("text-[#64748b]");


    // Setelah tanggal valid → cek tarif
    cekDanHitungBiaya();
}

/*
|--------------------------------------------------------------------------
| Cek Hitung Biaya - SIMULASI FE
|--------------------------------------------------------------------------
*/

function cekDanHitungBiaya() {

    const kota =
        document.getElementById("kota").value;

    const mulai =
        document.getElementById("tanggalMulai").value;

    const selesai =
        document.getElementById("tanggalSelesai").value;


    // Kota belum dipilih
    if (!kota) {

        resetTarif();

        return;
    }


    // Tanggal belum lengkap
    if (!mulai || !selesai) {

        resetTarif();

        return;
    }


    const tanggalMulai =
        new Date(mulai);

    const tanggalSelesai =
        new Date(selesai);


    // Tanggal tidak valid
    if (tanggalSelesai < tanggalMulai) {

        resetTarif();

        return;
    }


    const selisih =
        tanggalSelesai - tanggalMulai;


    const jumlahHari =
        Math.floor(
            selisih / (1000 * 60 * 60 * 24)
        ) + 1;


    const jumlahMalam =
        Math.max(jumlahHari - 1, 0);


    isiTarif(
        kota,
        jumlahHari,
        jumlahMalam
    );
}

/*
|--------------------------------------------------------------------------
| SIMPAN ILPD - SIMULASI FE
|--------------------------------------------------------------------------
*/

function simpanILPD() {

    const kota =
        document.getElementById("kota").value;

    const total =
        document.getElementById("total").value;

    const uangMuka =
        document.getElementById("uangMuka").value;


    if (!kota) {

        alert("Silakan pilih kota tujuan terlebih dahulu.");

        document.getElementById("kota").focus();

        return;
    }


    if (total === "0") {

        alert("Belum ada perkiraan biaya yang diisi.");

        return;
    }


    console.log("DATA ILPD:", {

        user: userLogin,

        kota: kota,

        golongan: userLogin.golongan,

        dinas:
            document.getElementById("dinas").value,

        makan:
            document.getElementById("makan").value,

        hotel:
            document.getElementById("hotel").value,

        laundry:
            document.getElementById("laundry").value,

        total: total,

        uangMuka: uangMuka

    });


    alert(
        "Data ILPD berhasil disiapkan!\n\n" +
        "Pegawai: " + userLogin.nama + "\n" +
        "Golongan: " + userLogin.golongan + "\n" +
        "Kota Tujuan: " + kota + "\n" +
        "Total: Rp " + total + "\n" +
        "Uang Muka: Rp " + uangMuka +
        "\n\n(Saat ini masih simulasi FE)"
    );
}


/*
|--------------------------------------------------------------------------
| CEGAH INPUT NEGATIF / SPINNER
|--------------------------------------------------------------------------
| Karena input biaya menggunakan text, user tidak akan mendapatkan
| spinner +/- dari input number.
|--------------------------------------------------------------------------
*/

document.addEventListener("wheel", function(event) {

    if (
        document.activeElement &&
        document.activeElement.classList.contains("currency-input")
    ) {
        document.activeElement.blur();
    }

});


/*
|--------------------------------------------------------------------------
| INISIALISASI
|--------------------------------------------------------------------------
*/

resetTarif();

</script>

</body>
</html>
```
