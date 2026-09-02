<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dokumen & Tiket - SPPD System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >
</head>

<body class="min-h-screen bg-white font-['Inter',sans-serif] text-[#1e293b]">

<div class="flex min-h-screen">

    {{-- =========================================================
         MOBILE OVERLAY
    ========================================================== --}}
    <div
        id="sidebarOverlay"
        class="fixed inset-0 z-40 hidden bg-black/30 lg:hidden"
        onclick="closeSidebar()"
    ></div>

    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}
    <aside
        id="sidebar"
        class="fixed left-0 top-0 z-50 flex h-screen w-[260px] -translate-x-full flex-col gap-6 border-r border-[#e2e8f0] bg-white px-4 py-6 transition-transform duration-300 lg:translate-x-0"
    >

        {{-- BRAND --}}
        <div class="flex items-center gap-3 pl-3">

            <div class="flex size-9 items-center justify-center rounded-[10px] bg-[#0d6efd]">
                <span class="text-lg font-bold text-white">
                    S
                </span>
            </div>

            <div class="flex flex-col gap-[2px]">

                <span class="text-[16px] font-bold">
                    SPPD System
                </span>

                <span class="text-[11px] text-[#94a3b8]">
                    Sistem Perjalanan Dinas
                </span>

            </div>

            {{-- CLOSE MOBILE --}}
            <button
                type="button"
                onclick="closeSidebar()"
                class="ml-auto flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f8fafc] lg:hidden"
            >
                ✕
            </button>

        </div>

        {{-- MENU --}}
        <nav class="flex flex-col gap-1">

            <a
                href="/dashboard"
                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#f8fafc]"
            >
                
                <span class="text-[14px] font-medium">Dashboard</span>
            </a>

            <a
                href="/sppd"
                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium">Pengajuan SPPD</span>
            </a>

            <a
                href="/ilpd"
                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium">Perizinan</span>
            </a>

            <a
                href="/riwayat"
                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium">Riwayat Pengajuan</span>
            </a>

            {{-- ACTIVE --}}
            <a
                href="/dokumen"
                class="flex items-center gap-3 rounded-xl bg-[#eff6ff] px-4 py-3"
            >

                <span class="text-[14px] font-semibold text-[#0d6efd]">
                    Dokumen & Tiket
                </span>
            </a>

        </nav>

        <div class="h-px w-full bg-[#e2e8f0]"></div>

        <div class="flex-1"></div>

    </aside>

    {{-- =========================================================
         MAIN
    ========================================================== --}}
    <main class="min-h-screen flex-1 lg:ml-[260px]">

        {{-- =====================================================
             NAVBAR
        ====================================================== --}}
        <header class="flex h-[72px] items-center justify-between border-b border-[#e2e8f0] px-4 sm:px-6 lg:justify-end lg:px-8">

            {{-- MOBILE MENU --}}
            <button
                type="button"
                onclick="openSidebar()"
                class="flex size-10 items-center justify-center rounded-lg border border-[#e2e8f0] text-[#64748b] lg:hidden"
            >
                ☰
            </button>

            <div class="flex items-center gap-5">

                {{-- Notification --}}
                <button
                    type="button"
                    class="relative flex size-10 items-center justify-center"
                >

                </button>

                {{-- Profile --}}
                <div class="relative">

                    <button
                        type="button"
                        id="profileToggle"
                        class="flex items-center gap-[10px]"
                    >

                        <div class="flex size-9 items-center justify-center rounded-full border border-[#2563eb] bg-[#eff6ff]">

                            <span
                                id="profileInitial"
                                class="text-[13px] font-bold text-[#2563eb]"
                            >
                                ES
                            </span>

                        </div>

                        <div class="hidden flex-col gap-px text-left sm:flex">

                            <span
                                id="profileName"
                                class="text-[13px] font-semibold"
                            >
                                Eko Saputra
                            </span>

                            <span
                                id="profileRole"
                                class="text-[11px] text-[#64748b]"
                            >
                                Staff
                            </span>

                        </div>

                    </button>

                    {{-- PROFILE DROPDOWN --}}
                    <div
                        id="profileMenu"
                        class="absolute right-0 top-12 z-[60] hidden w-52 rounded-xl border border-[#e2e8f0] bg-white p-2 shadow-lg"
                    >

                        <div class="my-1 h-px bg-[#e2e8f0]"></div>

                        {{-- Logout --}}
                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50"
                            >
                                Logout
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>

        {{-- =====================================================
             CONTENT
        ====================================================== --}}
        <div class="px-4 pb-8 pt-6 sm:px-6 lg:px-10 lg:pt-8">

            {{-- TITLE --}}
            <div class="mb-6">

                <h1 class="text-[22px] font-bold">
                    Dokumen & Tiket
                </h1>

                <p
                    id="pageDescription"
                    class="mt-1 text-[13px] text-[#64748b]"
                >
                    Kelola dan unduh dokumen serta tiket perjalanan dinas Anda.
                </p>

            </div>

            {{-- =================================================
                 BODY
            ================================================== --}}
            <div class="flex flex-col items-stretch gap-4 xl:flex-row">

                {{-- =================================================
                     LEFT : LIST SPPD
                ================================================== --}}
                <section class="w-full shrink-0 rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-[0px_4px_6px_rgba(15,23,42,0.02)] sm:p-5 xl:w-[380px]">

                    <div class="mb-4 flex items-center justify-between">

                        <h2 class="text-[15px] font-bold">
                            Daftar Pengajuan SPPD
                        </h2>

                        <span
                            id="roleBadge"
                            class="rounded-md bg-[#eff6ff] px-2 py-1 text-[10px] font-semibold text-[#0d6efd]"
                        >
                            STAFF
                        </span>

                    </div>

                    {{-- SEARCH --}}
                    <div class="mb-4 flex gap-2">

                        <div class="flex min-w-0 flex-1 items-center gap-2 rounded-lg bg-[#f4f6fb] px-3 py-2">

                            <i data-lucide="search" class="size-3.5 shrink-0 text-[#94a3b8]"></i>

                            <input
                                id="searchInput"
                                type="text"
                                placeholder="Cari nomor SPPD atau tujuan..."
                                class="min-w-0 flex-1 bg-transparent text-[12px] outline-none placeholder:text-[#94a3b8]"
                            >

                        </div>

                        <button
                            id="filterButton"
                            type="button"
                            class="flex shrink-0 items-center gap-1.5 rounded-lg border border-[#e2e8f0] px-3 py-2 text-[12px] font-semibold text-[#64748b]"
                        >

                            <i data-lucide="sliders-horizontal" class="size-3.5 text-[#64748b]"></i>

                            Filter

                        </button>

                    </div>

                    {{-- FILTER MENU --}}
                    <div
                        id="filterMenu"
                        class="mb-3 hidden rounded-xl border border-[#e2e8f0] bg-white p-2"
                    >

                        <button
                            type="button"
                            onclick="setStatusFilter('all')"
                            class="w-full rounded-lg px-3 py-2 text-left text-xs hover:bg-[#f8fafc]"
                        >
                            Semua Status
                        </button>

                        <button
                            type="button"
                            onclick="setStatusFilter('Selesai')"
                            class="w-full rounded-lg px-3 py-2 text-left text-xs hover:bg-[#f8fafc]"
                        >
                            Selesai
                        </button>

                        <button
                            type="button"
                            onclick="setStatusFilter('Approval')"
                            class="w-full rounded-lg px-3 py-2 text-left text-xs hover:bg-[#f8fafc]"
                        >
                            Approval
                        </button>

                    </div>

                    {{-- LIST CONTAINER --}}
                    <div
                        id="pengajuanList"
                        class="max-h-[600px] overflow-y-auto"
                    >
                    </div>

                    {{-- EMPTY STATE --}}
                    <div
                        id="emptyState"
                        class="hidden rounded-xl bg-[#f8fafc] px-4 py-8 text-center"
                    >

                        <p class="text-[13px] font-semibold text-[#64748b]">
                            Tidak ada pengajuan
                        </p>

                        <p class="mt-1 text-[11px] text-[#94a3b8]">
                            Tidak ditemukan data sesuai pencarian.
                        </p>

                    </div>

                    {{-- PAGINATION --}}
                    <div
                        id="pagination"
                        class="flex items-center justify-center gap-2 pt-4"
                    >
                    </div>

                </section>

                {{-- =================================================
                     RIGHT
                ================================================== --}}
                <div class="min-w-0 flex-1 space-y-4">

                    {{-- DETAIL HEADER --}}
                    <section class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-[0px_4px_6px_rgba(15,23,42,0.02)] sm:p-6">

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex flex-wrap items-center gap-2">

                                <h2
                                    id="detailNo"
                                    class="text-[18px] font-bold"
                                >
                                    -
                                </h2>

                                <span
                                    id="detailStatus"
                                    class="rounded-md bg-[#e8f5e9] px-2 py-1 text-[11px] font-semibold text-[#2e7d32]"
                                >
                                    -
                                </span>

                            </div>

                            <button
                                id="detailButton"
                                type="button"
                                class="self-start px-0 py-1.5 text-[12px] font-semibold text-[#0d6efd] sm:self-auto"
                            >
                                Lihat Detail Pengajuan
                            </button>

                        </div>

                        <div class="mt-5 flex flex-col gap-5 lg:flex-row lg:justify-between">

                            <div class="flex flex-col gap-3">

                                <div class="flex items-center gap-2">

                                    <i data-lucide="map-pin"class="size-4 shrink-0 text-[#94a3b8]"></i>

                                    <span
                                        id="detailTujuan"
                                        class="text-[13px] font-medium text-[#64748b]"
                                    >
                                        -
                                    </span>

                                </div>

                                <div class="flex items-center gap-2">

                                    <i data-lucide="calendar-days"class="size-4 text-[#94a3b8]"></i>

                                    <span
                                        id="detailTanggal"
                                        class="text-[13px] font-medium text-[#64748b]"
                                    >
                                        -
                                    </span>

                                </div>

                            </div>

                            <div class="flex flex-col gap-3 text-[12px] lg:items-end">

                                <div class="flex flex-wrap gap-3">

                                    <span class="text-[#94a3b8]">
                                        Diajukan oleh:
                                    </span>

                                    <span
                                        id="detailPemohon"
                                        class="font-semibold"
                                    >
                                        -
                                    </span>

                                </div>

                                <div class="flex flex-wrap gap-3">

                                    <span class="text-[#94a3b8]">
                                        Tanggal Pengajuan:
                                    </span>

                                    <span
                                        id="detailTanggalPengajuan"
                                        class="font-semibold"
                                    >
                                        -
                                    </span>

                                </div>

                            </div>

                        </div>

                    </section>

                    {{-- DOKUMEN --}}
                    <section class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-[0px_4px_6px_rgba(15,23,42,0.02)] sm:p-6">

                        <div class="mb-4 flex items-center justify-between">

                            <h2 class="text-[15px] font-bold">
                                Dokumen & Tiket
                            </h2>

                            <span
                                id="documentCount"
                                class="text-[11px] text-[#94a3b8]"
                            >
                                -
                            </span>

                        </div>

                        <div
                            id="documentList"
                            class="flex flex-col gap-3"
                        >
                        </div>

                    </section>

                    {{-- CATATAN --}}
                    <div class="flex items-start gap-2.5 rounded-xl bg-[#eff6ff] p-4">

                        <img
                            src="https://www.figma.com/api/mcp/asset/93cb7c79-3e01-4f43-a86a-4021cad1f778.svg"
                            class="mt-0.5 size-4"
                            alt=""
                        >

                        <div class="text-[12px] text-[#0d6efd]">

                            <p class="font-bold">
                                Catatan
                            </p>

                            <p id="roleNote">
                                Pastikan dokumen fisik dicetak sebelum berangkat untuk keperluan pelaporan dinas.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

{{-- =========================================================
     DOCUMENT MODAL
========================================================== --}}
<div
    id="documentModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 px-4"
>

    <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl sm:p-6">

        <div class="flex items-start justify-between gap-4">

            <div>

                <h2
                    id="modalTitle"
                    class="text-[16px] font-bold"
                >
                    Dokumen
                </h2>

                <p
                    id="modalDescription"
                    class="mt-1 text-[12px] text-[#64748b]"
                >
                    -
                </p>

            </div>

            <button
                type="button"
                onclick="closeDocumentModal()"
                class="flex size-8 shrink-0 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f8fafc]"
            >
                ✕
            </button>

        </div>

        <div class="mt-5 rounded-xl bg-[#f8fafc] p-8 text-center">

            <div class="mx-auto flex size-14 items-center justify-center rounded-xl bg-[#eff6ff]">

                <span class="text-xl text-[#0d6efd]">
                    ▣
                </span>

            </div>

            <p class="mt-3 text-[13px] font-semibold">
                Preview Dokumen FE
            </p>

            <p class="mt-1 text-[11px] text-[#94a3b8]">
                File asli akan dihubungkan saat backend sudah tersedia.
            </p>

        </div>

        <div class="mt-5 flex justify-end gap-2">

            <button
                type="button"
                onclick="closeDocumentModal()"
                class="rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-semibold text-[#64748b]"
            >
                Tutup
            </button>

            <button
                type="button"
                onclick="printDocument()"
                class="rounded-lg border border-[#0d6efd] px-4 py-2 text-[12px] font-semibold text-[#0d6efd]"
            >
                Cetak
            </button>

        </div>

    </div>

</div>

<script>

    /* =========================================================
       DUMMY DATA
       NANTI DIGANTI DATA DATABASE / CONTROLLER
    ========================================================== */

    const dataByRole = {

        staff: [

            {
                no: 'SPPD-2026-00124',
                tujuan: 'Bandung',
                tanggal: '20 - 22 Agustus 2026',
                status: 'Selesai',
                pemohon: 'Eko Saputra',
                tanggalPengajuan: '5 Agustus 2026, 09:00 WIB',
                dokumen: ['sppd', 'perizinan', 'tiket']
            },

            {
                no: 'SPPD-2026-00115',
                tujuan: 'Jakarta',
                tanggal: '10 - 12 Agustus 2026',
                status: 'Selesai',
                pemohon: 'Eko Saputra',
                tanggalPengajuan: '2 Agustus 2026, 10:20 WIB',
                dokumen: ['sppd', 'perizinan', 'tiket']
            },

            {
                no: 'SPPD-2026-00102',
                tujuan: 'Surabaya',
                tanggal: '28 - 30 Juli 2026',
                status: 'Approval',
                pemohon: 'Eko Saputra',
                tanggalPengajuan: '20 Juli 2026, 08:40 WIB',
                dokumen: ['sppd', 'perizinan', 'tiket']
            },

            {
                no: 'SPPD-2026-00087',
                tujuan: 'Yogyakarta',
                tanggal: '15 - 17 Juli 2026',
                status: 'Approval',
                pemohon: 'Eko Saputra',
                tanggalPengajuan: '10 Juli 2026, 13:15 WIB',
                dokumen: ['sppd', 'perizinan', 'tiket']
            },

            {
                no: 'SPPD-2026-00096',
                tujuan: 'Semarang',
                tanggal: '10 - 13 Juni 2026',
                status: 'Selesai',
                pemohon: 'Eko Saputra',
                tanggalPengajuan: '9 Juni 2026, 13:15 WIB',
                dokumen: ['sppd', 'perizinan', 'tiket']
            },

        ],

        manager: [

            {
                no: 'SPPD-2026-00124',
                tujuan: 'Bandung',
                tanggal: '20 - 22 Agustus 2026',
                status: 'Selesai',
                pemohon: 'Andi Wijaya',
                tanggalPengajuan: '5 Agustus 2026, 09:00 WIB',
                dokumen: ['sppd', 'perizinan', 'tiket']
            },

            {
                no: 'SPPD-2026-00118',
                tujuan: 'Jakarta',
                tanggal: '18 - 19 Agustus 2026',
                status: 'Approval',
                pemohon: 'Budi Santoso',
                tanggalPengajuan: '8 Agustus 2026, 10:00 WIB',
                dokumen: ['sppd', 'perizinan']
            },

            {
                no: 'SPPD-2026-00110',
                tujuan: 'Bogor',
                tanggal: '12 - 13 Agustus 2026',
                status: 'Approval',
                pemohon: 'Andi Pratama',
                tanggalPengajuan: '6 Agustus 2026, 14:00 WIB',
                dokumen: ['sppd']
            }

        ],

        ga: [

            {
                no: 'SPPD-2026-00124',
                tujuan: 'Bandung',
                tanggal: '20 - 22 Agustus 2026',
                status: 'Selesai',
                pemohon: 'Eko Saputra',
                tanggalPengajuan: '5 Agustus 2026, 09:00 WIB',
                dokumen: ['sppd', 'perizinan', 'tiket']
            },

            {
                no: 'SPPD-2026-00118',
                tujuan: 'Jakarta',
                tanggal: '18 - 19 Agustus 2026',
                status: 'Approval',
                pemohon: 'Budi Santoso',
                tanggalPengajuan: '8 Agustus 2026, 10:00 WIB',
                dokumen: ['sppd', 'perizinan']
            },

            {
                no: 'SPPD-2026-00110',
                tujuan: 'Bogor',
                tanggal: '12 - 13 Agustus 2026',
                status: 'Approval',
                pemohon: 'Andi Pratama',
                tanggalPengajuan: '6 Agustus 2026, 14:00 WIB',
                dokumen: ['sppd']
            },

            {
                no: 'SPPD-2026-00102',
                tujuan: 'Surabaya',
                tanggal: '28 - 30 Juli 2026',
                status: 'Selesai',
                pemohon: 'Eko Saputra',
                tanggalPengajuan: '20 Juli 2026, 08:40 WIB',
                dokumen: ['sppd', 'perizinan', 'tiket']
            },

            {
                no: 'SPPD-2026-00087',
                tujuan: 'Yogyakarta',
                tanggal: '15 - 17 Juli 2026',
                status: 'Approval',
                pemohon: 'Dewi Lestari',
                tanggalPengajuan: '10 Juli 2026, 13:15 WIB',
                dokumen: ['sppd', 'perizinan']
            },

        ]

    };

    /* =========================================================
       ROLE CONFIG
    ========================================================== */

    const roleConfig = {

        staff: {
            name: 'Eko Saputra',
            label: 'Staff',
            description: 'Menampilkan pengajuan SPPD milik Anda.',
            note: 'Pastikan dokumen perjalanan dicetak sebelum berangkat untuk keperluan pelaporan dinas.'
        },

        manager: {
            name: 'Andi Wijaya',
            label: 'Manager',
            description: 'Menampilkan pengajuan SPPD yang berada dalam lingkup Anda.',
            note: 'Periksa informasi pengajuan sebelum memberikan keputusan pada proses perjalanan dinas.'
        },

        ga: {
            name: 'Budi Santoso',
            label: 'General Affair',
            description: 'Menampilkan seluruh pengajuan SPPD yang perlu dikelola oleh GA.',
            note: 'Periksa kebutuhan tiket dan budget perjalanan sesuai dengan pengajuan yang tersedia.'
        }

    };

    let currentRole =
        localStorage.getItem('sppd_role') || 'staff';

    let currentData = [];
    let selectedId = null;
    let statusFilter = 'all';

    /* =========================================================
       HELPER
    ========================================================== */

    function getStatusClass(status) {

        if (status === 'Selesai') {

            return 'bg-[#e8f5e9] text-[#2e7d32]';

        }

        if (status === 'Approval') {

            return 'bg-[#fff3e0] text-[#ef6c00]';

        }

        return 'bg-[#f5f5f5] text-[#616161]';

    }

    function getDocumentData(type) {

        const documents = {

            sppd: {
                title: '1. Form SPPD',
                description: 'Formulir Surat Perjalanan Dinas yang telah diisi oleh Staff.',
                date: 'Diajukan: 5 Agustus 2026, 09:00 WIB',
                iconBg: 'bg-[#eff6ff]',
                iconColor: 'text-[#0d6efd]',
                border: 'border-[#0d6efd]'
            },

            perizinan: {
                title: '2. Form Perizinan (Disetujui)',
                description: 'Formulir perizinan yang telah diperiksa oleh GA.',
                date: 'Diperiksa: 6 Agustus 2026, 14:20 WIB',
                iconBg: 'bg-[#e8f5e9]',
                iconColor: 'text-[#2e7d32]',
                border: 'border-[#2e7d32]'
            },

            tiket: {
                title: '3. Tiket Perjalanan',
                description: 'E-ticket perjalanan dinas yang telah disiapkan oleh GA.',
                date: 'Diterbitkan: 6 Agustus 2026, 15:10 WIB',
                iconBg: 'bg-[#f3e5f5]',
                iconColor: 'text-[#8e24aa]',
                border: 'border-[#8e24aa]'
            }

        };

        return documents[type];

    }

    /* =========================================================
       RENDER ROLE
    ========================================================== */

    function renderRole() {

        const config = roleConfig[currentRole];

        document.getElementById('profileName').textContent =
            config.name;

        document.getElementById('profileRole').textContent =
            config.label;

        document.getElementById('pageDescription').textContent =
            config.description;

        document.getElementById('roleBadge').textContent =
            config.label.toUpperCase();

        document.getElementById('roleNote').textContent =
            config.note;

        statusFilter = 'all';

        currentData = dataByRole[currentRole];

        selectedId =
            currentData.length > 0
                ? currentData[0].no
                : null;

        document.getElementById('searchInput').value = '';

        renderList();

        renderDetail();

    }

    /* =========================================================
       SWITCH ROLE
    ========================================================== */

    function switchRole(role) {

        currentRole = role;

        localStorage.setItem('sppd_role', role);

        document.getElementById('profileMenu')
            .classList.add('hidden');

        renderRole();

    }

    /* =========================================================
       FILTER + SEARCH
    ========================================================== */

    function getFilteredData() {

        const keyword =
            document.getElementById('searchInput')
                .value
                .toLowerCase()
                .trim();

        return currentData.filter(item => {

            const matchesSearch =
                item.no.toLowerCase().includes(keyword) ||
                item.tujuan.toLowerCase().includes(keyword) ||
                item.pemohon.toLowerCase().includes(keyword);

            const matchesStatus =
                statusFilter === 'all' ||
                item.status === statusFilter;

            return matchesSearch && matchesStatus;

        });

    }

    function setStatusFilter(status) {

        statusFilter = status;

        document.getElementById('filterMenu')
            .classList.add('hidden');

        renderList();

    }

    /* =========================================================
       RENDER LIST
    ========================================================== */

    function renderList() {

        const list =
            document.getElementById('pengajuanList');

        const empty =
            document.getElementById('emptyState');

        const pagination =
            document.getElementById('pagination');

        const filtered =
            getFilteredData();

        list.innerHTML = '';

        if (filtered.length === 0) {

            empty.classList.remove('hidden');
            pagination.innerHTML = '';

            return;

        }

        empty.classList.add('hidden');


        filtered.forEach(item => {

            const active =
                selectedId === item.no;

            const statusClass =
                getStatusClass(item.status);

            const div =
                document.createElement('button');

            div.type = 'button';

            div.className = `
                flex w-full flex-col gap-2 p-4 text-left transition
                ${active
                    ? 'rounded-xl border border-[#0d6efd] bg-[#eff6ff]'
                    : 'border-b border-[#f1f5f9] hover:bg-[#f8fafc]'
                }
            `;

            div.innerHTML = `

                <div class="flex items-center justify-between gap-2">

                    <span class="text-[14px] font-bold">
                        ${item.no}
                    </span>

                    <span class="shrink-0 rounded-md px-2 py-1 text-[11px] font-semibold ${statusClass}">
                        ${item.status}
                    </span>

                </div>

                <div class="flex flex-col gap-1">

                    <span class="text-[13px] text-[#64748b]">
                        ${item.tujuan}
                    </span>

                    <span class="text-[12px] text-[#94a3b8]">
                        ${item.tanggal}
                    </span>

                </div>

            `;

            div.addEventListener('click', () => {

                selectedId = item.no;

                renderList();
                renderDetail();

            });

            list.appendChild(div);

        });

        pagination.innerHTML = `

            <button
                type="button"
                class="size-5 text-[#94a3b8]"
                onclick="fakePagination('previous')"
            >
                ‹
            </button>

            <button
                type="button"
                class="flex size-8 items-center justify-center rounded-md border border-[#0d6efd] bg-[#eff6ff] text-[13px] font-semibold text-[#0d6efd]"
            >
                1
            </button>

            <button
                type="button"
                class="size-8 text-[13px] text-[#64748b]"
                onclick="fakePagination(2)"
            >
                2
            </button>

            <button
                type="button"
                class="size-8 text-[13px] text-[#64748b]"
                onclick="fakePagination(3)"
            >
                3
            </button>

            <button
                type="button"
                class="size-5 text-[#94a3b8]"
                onclick="fakePagination('next')"
            >
                ›
            </button>

        `;

    }

    function fakePagination(page) {

        alert(
            `Pagination halaman ${page} masih simulasi FE. Nanti akan menggunakan pagination dari backend.`
        );

    }

    /* =========================================================
       RENDER DETAIL
    ========================================================== */

    function renderDetail() {

        const item =
            currentData.find(
                data => data.no === selectedId
            );

        if (!item) {

            document.getElementById('detailNo').textContent = '-';
            document.getElementById('detailStatus').textContent = '-';
            document.getElementById('detailTujuan').textContent = '-';
            document.getElementById('detailTanggal').textContent = '-';
            document.getElementById('detailPemohon').textContent = '-';
            document.getElementById('detailTanggalPengajuan').textContent = '-';
            document.getElementById('documentList').innerHTML = '';

            return;

        }

        document.getElementById('detailNo').textContent =
            item.no;

        const statusElement =
            document.getElementById('detailStatus');

        statusElement.textContent =
            item.status;

        statusElement.className =
            `rounded-md px-2 py-1 text-[11px] font-semibold ${getStatusClass(item.status)}`;

        document.getElementById('detailTujuan').textContent =
            item.tujuan;

        document.getElementById('detailTanggal').textContent =
            item.tanggal;

        document.getElementById('detailPemohon').textContent =
            item.pemohon;

        document.getElementById('detailTanggalPengajuan').textContent =
            item.tanggalPengajuan;

        document.getElementById('documentCount').textContent =
            `${item.dokumen.length} dokumen`;

        renderDocuments(item);

    }

    /* =========================================================
       RENDER DOCUMENTS
    ========================================================== */

    function renderDocuments(item) {

        const container =
            document.getElementById('documentList');

        container.innerHTML = '';

        item.dokumen.forEach(type => {

            const doc =
                getDocumentData(type);

            const wrapper =
                document.createElement('div');

            wrapper.className =
                'flex flex-col gap-4 rounded-xl border border-[#f1f5f9] p-4 sm:flex-row sm:items-center';

            wrapper.innerHTML = `

                <div class="flex size-11 shrink-0 items-center justify-center rounded-lg ${doc.iconBg}">

                    <span class="${doc.iconColor} text-lg">
                        ▣
                    </span>

                </div>

                <div class="min-w-0 flex-1">

                    <h3 class="text-[15px] font-semibold">
                        ${doc.title}
                    </h3>

                    <p class="mt-1 text-[12px] text-[#64748b]">
                        ${doc.description}
                    </p>

                    <p class="mt-1 text-[11px] text-[#94a3b8]">
                        ${doc.date}
                    </p>

                </div>

                <div class="flex shrink-0 gap-2">

                    <button
                        type="button"
                        class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b]"
                        onclick="openDocumentModal('${type}', '${item.no}')"
                    >

                        Lihat

                        <span class="text-[#64748b]">👁</span>

                    </button>

                </div>

            `;

            container.appendChild(wrapper);

        });

    }

    /* =========================================================
       MODAL
    ========================================================== */

    function openDocumentModal(type, sppdNo) {

        const doc =
            getDocumentData(type);

        document.getElementById('modalTitle').textContent =
            `${doc.title} - ${sppdNo}`;

        document.getElementById('modalDescription').textContent =
            doc.description;

        document.getElementById('documentModal')
            .classList.remove('hidden');

        document.getElementById('documentModal')
            .classList.add('flex');

    }

    function closeDocumentModal() {

        document.getElementById('documentModal')
            .classList.add('hidden');

        document.getElementById('documentModal')
            .classList.remove('flex');

    }

    function printSpecificDocument(type, sppdNo) {

        const doc =
            getDocumentData(type);

        const printWindow =
            window.open('', '_blank', 'width=900,height=700');

        printWindow.document.write(`

            <!DOCTYPE html>

            <html>

            <head>

                <title>${doc.title} - ${sppdNo}</title>

                <style>

                    body {
                        font-family: Arial, sans-serif;
                        padding: 40px;
                    }

                    h1 {
                        margin-bottom: 10px;
                    }

                    .box {
                        margin-top: 30px;
                        padding: 30px;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                    }

                </style>

            </head>

            <body>

                <h1>${doc.title}</h1>

                <p>Nomor SPPD: ${sppdNo}</p>

                <div class="box">

                    <p>
                        Preview dokumen FE.
                    </p>

                    <p>
                        File asli akan dihubungkan
                        setelah backend tersedia.
                    </p>

                </div>

                <script>
                    window.print();
                <\/script>

            </body>

            </html>

        `);

        printWindow.document.close();

    }

    function printDocument() {

        const title =
            document.getElementById('modalTitle').textContent;

        const printWindow =
            window.open('', '_blank', 'width=900,height=700');

        printWindow.document.write(`

            <html>

            <head>

                <title>${title}</title>

            </head>

            <body style="font-family: Arial; padding: 40px">

                <h1>${title}</h1>

                <p>Preview dokumen FE.</p>

                <script>
                    window.print();
                <\/script>

            </body>

            </html>

        `);

        printWindow.document.close();

    }

    /* =========================================================
       PROFILE DROPDOWN
    ========================================================== */

    /* =========================================================
       SEARCH
    ========================================================== */

    document
        .getElementById('searchInput')
        .addEventListener('input', function() {

            renderList();

        });

    /* =========================================================
       FILTER BUTTON
    ========================================================== */

    document
        .getElementById('filterButton')
        .addEventListener('click', function(event) {

            event.stopPropagation();

            document
                .getElementById('filterMenu')
                .classList.toggle('hidden');

        });

    /* =========================================================
       DETAIL BUTTON
    ========================================================== */

    document
        .getElementById('detailButton')
        .addEventListener('click', function() {

            const item =
                currentData.find(
                    data => data.no === selectedId
                );

            if (!item) return;

            alert(
                `Detail ${item.no}\n\n` +
                `Pemohon: ${item.pemohon}\n` +
                `Tujuan: ${item.tujuan}\n` +
                `Tanggal: ${item.tanggal}\n` +
                `Status: ${item.status}\n\n` +
                `Detail ini sementara masih simulasi FE.`
            );

        });

    /* =========================================================
       SIDEBAR MOBILE
    ========================================================== */

    function openSidebar() {

        document
            .getElementById('sidebar')
            .classList.remove('-translate-x-full');

        document
            .getElementById('sidebarOverlay')
            .classList.remove('hidden');

    }

    function closeSidebar() {

        document
            .getElementById('sidebar')
            .classList.add('-translate-x-full');

        document
            .getElementById('sidebarOverlay')
            .classList.add('hidden');

    }

    /* =========================================================
       ESC KEY
    ========================================================== */

    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            closeDocumentModal();

            document
                .getElementById('profileMenu')
                .classList.add('hidden');

            document
                .getElementById('filterMenu')
                .classList.add('hidden');

        }

    });

    /* =========================================================
       INITIALIZE
    ========================================================== */

    renderRole();

</script>

</body>
</html>