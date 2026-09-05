<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat - SPPD System</title>

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
        onclick="toggleSidebar()"
    ></div>

    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}
    <aside
        id="sidebar"
        class="fixed left-0 top-0 z-50 flex h-screen w-[260px] -translate-x-full flex-col gap-6 border-r border-[#e2e8f0] bg-white px-4 py-6 transition-transform duration-300 lg:translate-x-0"
    >

        {{-- BRAND --}}
        <div class="flex items-center justify-between">

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

            {{-- MOBILE CLOSE --}}
            <button
                type="button"
                onclick="toggleSidebar()"
                class="mr-1 flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f8fafc] lg:hidden"
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

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dashboard
                </span>

            </a>

            <a
                href="/sppd"
                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Pengajuan SPPD
                </span>

            </a>

            <a
                href="/ilpd"
                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Perizinan
                </span>

            </a>

            {{-- ACTIVE --}}
            <a
                href="/riwayat"
                class="flex w-full items-center gap-3 rounded-xl bg-[#eff6ff] px-4 py-3"
            >

                <span class="text-[14px] font-semibold text-[#0d6efd]">
                    Riwayat Pengajuan
                </span>

            </a>

            <a
                href="/dokumen"
                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium text-[#64748b]">
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
    <main class="min-w-0 flex-1 lg:ml-[260px]">

        {{-- =====================================================
             NAVBAR
        ====================================================== --}}
        <header class="flex h-[72px] items-center justify-between border-b border-[#e2e8f0] bg-white px-4 sm:px-6 lg:justify-end lg:px-8">

            {{-- MOBILE MENU --}}
            <button
                type="button"
                onclick="toggleSidebar()"
                class="flex size-10 items-center justify-center rounded-lg hover:bg-[#f8fafc] lg:hidden"
            >
                ☰
            </button>

            <div class="flex items-center gap-4 sm:gap-5">

                {{-- NOTIFICATION --}}
                <button
                    type="button"
                    class="relative flex size-10 items-center justify-center"
                >

                </button>

                {{-- PROFILE --}}
                <div class="relative">

                    <button
                        type="button"
                        id="profileToggle"
                        class="flex items-center gap-2.5"
                    >

                        <div class="flex size-9 items-center justify-center rounded-full border border-[#2563eb] bg-[#eff6ff]">

                            <span
                                id="profileInitial"
                                class="text-[13px] font-bold text-[#2563eb]"
                            >
                                AR
                            </span>

                        </div>

                        <div class="hidden flex-col text-left sm:flex">

                            <span
                                id="profileName"
                                class="text-[14px] font-semibold"
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

                        <span class="text-xs text-[#64748b]">
                            ▾
                        </span>

                    </button>

                    {{-- PROFILE MENU --}}
                    <div
                        id="profileMenu"
                        class="absolute right-0 top-12 z-[60] hidden w-56 rounded-xl border border-[#e2e8f0] bg-white p-2 shadow-xl"
                    >

                        <div class="my-1 h-px bg-[#e2e8f0]"></div>

                        {{-- LOGOUT --}}
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
        <div class="px-4 pb-8 pt-6 sm:px-6 lg:px-8 lg:pt-8">

            {{-- PAGE TITLE --}}
            <div class="mb-6">

                <h1 class="text-[22px] font-bold">
                    Riwayat Pengajuan
                </h1>

                <p class="mt-1 text-[13px] text-[#64748b]">
                    Melihat dan memantau riwayat pengajuan perjalanan dinas.
                </p>

            </div>

            {{-- =================================================
                 TABLE CARD
            ================================================== --}}
            <section class="w-full rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-[0px_4px_6px_rgba(15,23,42,0.02)] sm:p-6">

                {{-- HEADER --}}
                <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    <div>

                        <h2
                            id="historyTitle"
                            class="text-[16px] font-bold"
                        >
                            Riwayat Pengajuan Saya
                        </h2>

                        <p
                            id="historyDescription"
                            class="mt-1 text-[12px] text-[#64748b]"
                        >
                            Menampilkan seluruh pengajuan SPPD yang Anda miliki.
                        </p>

                    </div>

                    {{-- SEARCH + FILTER --}}
                    <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">

                        {{-- SEARCH --}}
                        <div class="flex min-w-0 flex-1 items-center gap-2 rounded-lg bg-[#f4f6fb] px-3 py-2 sm:w-[260px] sm:flex-none">

                            <span class="text-sm text-[#94a3b8]">
                                ⌕
                            </span>

                            <input
                                id="searchInput"
                                type="text"
                                placeholder="Cari No. SPPD atau tujuan..."
                                oninput="renderHistory()"
                                class="min-w-0 flex-1 bg-transparent text-[12px] outline-none placeholder:text-[#94a3b8]"
                            >

                        </div>

                        {{-- FILTER --}}
                        <select
                            id="statusFilter"
                            onchange="renderHistory()"
                            class="rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 text-[12px] font-semibold text-[#64748b] outline-none focus:border-[#0d6efd]"
                        >

                            <option value="all">
                                Semua Status
                            </option>

                            <option value="manager">
                                Menunggu Approval
                            </option>

                            <option value="ga">
                                Sedang Diproses
                            </option>

                            <option value="done">
                                Selesai
                            </option>

                            <option value="rejected">
                                Approval
                            </option>

                        </select>

                    </div>

                </div>

                {{-- =================================================
                     DESKTOP TABLE
                ================================================== --}}
                <div class="hidden overflow-hidden rounded-xl border border-[#f1f5f9] lg:block">

                    {{-- HEADER --}}
                    <div class="grid grid-cols-[130px_110px_150px_160px_110px_110px] gap-3 bg-[#f4f6fb] px-4 py-3 text-[11px] font-semibold text-[#64748b]">

                        <span>No. SPPD</span>

                        <span>Tujuan</span>

                        <span>Tanggal Perjalanan</span>

                        <span>Status</span>

                        <span>Diajukan Pada</span>

                        <span class="text-center">
                            Aksi
                        </span>

                    </div>

                    {{-- DATA --}}
                    <div id="desktopHistoryList"></div>

                </div>

                {{-- =================================================
                     MOBILE CARD LIST
                ================================================== --}}
                <div
                    id="mobileHistoryList"
                    class="flex flex-col gap-3 lg:hidden"
                ></div>

                {{-- EMPTY STATE --}}
                <div
                    id="emptyState"
                    class="hidden py-12 text-center"
                >

                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-[#f4f6fb] text-xl text-[#94a3b8]">
                        ▣
                    </div>

                    <p class="mt-3 text-sm font-semibold">
                        Data tidak ditemukan
                    </p>

                    <p class="mt-1 text-xs text-[#94a3b8]">
                        Coba ubah kata pencarian atau filter status.
                    </p>

                </div>

                {{-- =================================================
                     PAGINATION
                ================================================== --}}
                <div
                    id="pagination"
                    class="flex items-center justify-center gap-2 pt-5"
                >

                    <button
                        type="button"
                        onclick="previousPage()"
                        class="flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f4f6fb]"
                    >
                        ‹
                    </button>

                    <span
                        id="pageNumber"
                        class="flex size-8 items-center justify-center rounded-md border border-[#0d6efd] bg-[#eff6ff] text-[13px] font-semibold text-[#0d6efd]"
                    >
                        1
                    </span>

                    <button
                        type="button"
                        onclick="nextPage()"
                        class="flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f4f6fb]"
                    >
                        ›
                    </button>

                </div>

            </section>

        </div>

    </main>

</div>

{{-- =============================================================
     DETAIL MODAL
============================================================= --}}
<div
    id="detailModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/30 p-4"
>

    <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl">

        {{-- HEADER --}}
        <div class="flex items-center justify-between border-b border-[#e2e8f0] p-5">

            <div>

                <h2 class="text-[16px] font-bold">
                    Detail Pengajuan
                </h2>

                <p
                    id="modalNo"
                    class="mt-1 text-[12px] text-[#64748b]"
                ></p>

            </div>

            <button
                type="button"
                onclick="closeDetail()"
                class="flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f4f6fb]"
            >
                ✕
            </button>

        </div>

        {{-- BODY --}}
        <div class="space-y-4 p-5">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>

                    <p class="text-[11px] text-[#94a3b8]">
                        Diajukan Oleh
                    </p>

                    <p
                        id="modalUser"
                        class="mt-1 text-sm font-semibold"
                    ></p>

                </div>

                <div>

                    <p class="text-[11px] text-[#94a3b8]">
                        Tujuan
                    </p>

                    <p
                        id="modalTujuan"
                        class="mt-1 text-sm font-semibold"
                    ></p>

                </div>

                <div>

                    <p class="text-[11px] text-[#94a3b8]">
                        Tanggal Perjalanan
                    </p>

                    <p
                        id="modalTanggal"
                        class="mt-1 text-sm font-semibold">
                    </p>

                </div>

                <div>

                    <p class="text-[11px] text-[#94a3b8]">
                        Diajukan Pada
                    </p>

                    <p
                        id="modalDate"
                        class="mt-1 text-sm font-semibold">
                    </p>

                </div>

            </div>

            {{-- STATUS --}}
            <div class="rounded-xl bg-[#f8fafc] p-4">

                <p class="text-[11px] text-[#94a3b8]">
                    Status
                </p>

                <div
                    id="modalStatus"
                    class="mt-2">
                </div>

            </div>

            {{-- ALUR --}}
            <div>

                <p class="mb-3 text-[13px] font-bold">
                    Alur Pengajuan
                </p>

                <div class="space-y-3">

                    <div class="flex items-center gap-3">

                        <div class="flex size-8 items-center justify-center rounded-full bg-[#eff6ff] text-xs font-bold text-[#0d6efd]">
                            1
                        </div>

                        <div>

                            <p class="text-[12px] font-semibold">
                                Manager
                            </p>

                            <p class="text-[11px] text-[#94a3b8]">
                                Pemeriksaan pengajuan
                            </p>

                        </div>

                    </div>

                    <div class="flex items-center gap-3">

                        <div class="flex size-8 items-center justify-center rounded-full bg-[#eff6ff] text-xs font-bold text-[#0d6efd]">
                            2
                        </div>

                        <div>

                            <p class="text-[12px] font-semibold">
                                General Affair
                            </p>

                            <p class="text-[11px] text-[#94a3b8]">
                                Pemeriksaan budget dan tiket
                            </p>

                        </div>

                    </div>

                    <div class="flex items-center gap-3">

                        <div class="flex size-8 items-center justify-center rounded-full bg-[#eff6ff] text-xs font-bold text-[#0d6efd]">
                            3
                        </div>

                        <div>

                            <p class="text-[12px] font-semibold">
                                General Manager
                            </p>

                            <p class="text-[11px] text-[#94a3b8]">
                                Persetujuan akhir
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- FOOTER --}}
        <div class="flex justify-end gap-2 border-t border-[#e2e8f0] p-5">

            <button
                type="button"
                onclick="closeDetail()"
                class="rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-semibold text-[#64748b]"
            >
                Tutup
            </button>

            <button
                type="button"
                onclick="printCurrentDetail()"
                class="rounded-lg bg-[#0d6efd] px-4 py-2 text-[12px] font-semibold text-white"
            >
                Cetak
            </button>

        </div>

    </div>

</div>

<script>

    /* ============================================================
       DATA FE
    ============================================================ */

    const historyData = [

        {
            no: 'SPPD-2026-00124',
            tujuan: 'Bandung',
            tanggal: '20 - 22 Agustus 2026',
            status: 'Sedang Diproses',
            type: 'manager',
            date: '11 Agustus 2026',
            user: 'Eko Saputra',
            owner: 'staff',
            manager: 'Andi Wijaya'
        },

        {
            no: 'SPPD-2026-00123',
            tujuan: 'Yogyakarta',
            tanggal: '15 - 17 Agustus 2026',
            status: 'Sedang Diproses',
            type: 'ga',
            date: '8 Agustus 2026',
            user: 'Eko Saputra',
            owner: 'staff',
            manager: 'Budi Santoso'
        },

        {
            no: 'SPPD-2026-00122',
            tujuan: 'Surabaya',
            tanggal: '05 - 06 Agustus 2026',
            status: 'Menunggu Approval',
            type: 'gm',
            date: '5 Agustus 2026',
            user: 'Eko Saputra',
            owner: 'staff',
            manager: 'Andi Wijaya'
        },

        {
            no: 'SPPD-2026-00121',
            tujuan: 'Jakarta',
            tanggal: '28 Juli 2026',
            status: 'Selesai',
            type: 'done',
            date: '20 Juli 2026',
            user: 'Eko Saputra',
            owner: 'staff',
            manager: 'Andi Wijaya'
        },

        {
            no: 'SPPD-2026-00120',
            tujuan: 'Semarang',
            tanggal: '18 - 19 Juli 2026',
            status: 'Selesai',
            type: 'done',
            date: '11 Juli 2026',
            user: 'Eko Saputra',
            owner: 'staff',
            manager: 'Budi Santoso'
        },

        {
            no: 'SPPD-2026-00118',
            tujuan: 'Bandung',
            tanggal: '10 - 11 Juli 2026',
            status: 'Selesai',
            type: 'done',
            date: '1 Juli 2026',
            user: 'Eko Saputra',
            owner: 'staff',
            manager: 'Budi Santoso'
        },

        {
            no: 'SPPD-2026-00117',
            tujuan: 'Surabaya',
            tanggal: '1 - 3 Juli 2026',
            status: 'Selesai',
            type: 'done',
            date: '25 Juni 2026',
            user: 'Eko Saputra',
            owner: 'staff',
            manager: 'Budi Santoso'
        }

    ];

    /* ============================================================
       ROLE
    ============================================================ */

    let currentRole = 'staff';

    let currentDetail = null;

    function switchRole(role) {

        currentRole = role;

        localStorage.setItem('sppd_role', role);

        const roleName = document.getElementById('profileRole');
        const profileName = document.getElementById('profileName');
        const initial = document.getElementById('profileInitial');

        if (role === 'staff') {

            roleName.textContent = 'Staff';

            profileName.textContent = 'Eko Saputra';

            initial.textContent = 'ES';

            document.getElementById('historyTitle').textContent =
                'Riwayat Pengajuan Saya';

            document.getElementById('historyDescription').textContent =
                'Menampilkan seluruh pengajuan SPPD yang Anda ajukan.';

        }

        else if (role === 'manager') {

            roleName.textContent = 'Manager';

            profileName.textContent = 'Andi Wijaya';

            initial.textContent = 'AW';

            document.getElementById('historyTitle').textContent =
                'Riwayat Pengajuan';

            document.getElementById('historyDescription').textContent =
                'Menampilkan riwayat pengajuan SPPD yang menjadi tanggung jawab Anda.';

        }

        else if (role === 'ga') {

            roleName.textContent = 'General Affair';

            profileName.textContent = 'Budi Santoso';

            initial.textContent = 'BS';

            document.getElementById('historyTitle').textContent =
                'Seluruh Riwayat Pengajuan';

            document.getElementById('historyDescription').textContent =
                'Menampilkan seluruh riwayat pengajuan SPPD yang masuk ke General Affair.';

        }

        closeProfileMenu();

        renderHistory();

    }

    /* ============================================================
       ROLE FILTER
    ============================================================ */

    function getRoleData() {

        if (currentRole === 'staff') {

            return historyData.filter(item =>
                item.user === 'Eko Saputra'
            );

        }

        if (currentRole === 'manager') {

            return historyData.filter(item =>
                item.manager === 'Andi Wijaya'
            );

        }


        if (currentRole === 'ga') {

            return historyData;

        }

        return [];

    }

    /* ============================================================
       STATUS BADGE
    ============================================================ */

    function statusBadge(item) {

        let classes = '';

        if (item.type === 'manager') {

            classes =
                'bg-[#fff7ed] text-[#d97706]';

        }

        else if (item.type === 'ga') {

            classes =
                'bg-[#eff6ff] text-[#2563eb]';

        }

        else if (item.type === 'gm') {

            classes =
                'bg-[#f3e8ff] text-[#9333ea]';

        }

        else if (item.type === 'done') {

            classes =
                'bg-[#f0fdf4] text-[#16a34a]';

        }

        else {

            classes =
                'bg-[#fef2f2] text-[#dc2626]';

        }

        return `
            <span class="inline-flex rounded-lg px-[10px] py-[6px] text-center text-[11px] font-semibold leading-[1.2] ${classes}">
                ${item.status}
            </span>
        `;

    }

    /* ============================================================
       RENDER HISTORY
    ============================================================ */

    function renderHistory() {

        const search =
            document
                .getElementById('searchInput')
                .value
                .toLowerCase()
                .trim();

        const filter =
            document
                .getElementById('statusFilter')
                .value;

        let data = getRoleData();

        if (search !== '') {

            data = data.filter(item =>

                item.no.toLowerCase().includes(search) ||

                item.tujuan.toLowerCase().includes(search) ||

                item.user.toLowerCase().includes(search)

            );

        }

        if (filter !== 'all') {

            data = data.filter(item =>
                item.type === filter
            );

        }

        renderDesktop(data);

        renderMobile(data);

    }

    /* ============================================================
       DESKTOP
    ============================================================ */

    function renderDesktop(data) {

        const container =
            document.getElementById('desktopHistoryList');

        container.innerHTML = '';

        if (data.length === 0) {

            showEmpty();

            return;

        }

        hideEmpty();

        data.forEach(item => {

            container.innerHTML += `

                <div
                    class="grid grid-cols-[130px_110px_150px_160px_110px_110px] items-center gap-3 border-b border-[#f1f5f9] px-4 py-[14px] text-[12px] hover:bg-[#fafcff]"
                >

                    <span class="font-semibold">
                        ${item.no}
                    </span>

                    <span>
                        ${item.tujuan}
                    </span>

                    <span>
                        ${item.tanggal}
                    </span>

                    <span>
                        ${statusBadge(item)}
                    </span>

                    <span class="text-[#64748b]">
                        ${item.date}
                    </span>

                    <div class="flex items-center justify-center gap-1">

                        <button
                            type="button"
                            onclick="openDetail('${item.no}')"
                            class="flex size-8 items-center justify-center rounded-lg hover:bg-[#eff6ff]"
                            title="Lihat"
                        >
                            <span class="text-[#64748b]">
                                👁
                            </span>
                        </button>

                        <button
                            type="button"
                            onclick="printItem('${item.no}')"
                            class="flex size-8 items-center justify-center rounded-lg hover:bg-[#eff6ff]"
                            title="Cetak"
                        >
                            <span class="text-[#0d6efd]">
                                🖨
                            </span>
                        </button>

                    </div>

                </div>

            `;

        });

    }

    /* ============================================================
       MOBILE
    ============================================================ */

    function renderMobile(data) {

        const container =
            document.getElementById('mobileHistoryList');

        container.innerHTML = '';

        if (data.length === 0) {

            return;

        }

        data.forEach(item => {

            container.innerHTML += `

                <div
                    class="rounded-xl border border-[#f1f5f9] p-4 hover:bg-[#fafcff]"
                >

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">

                            <p class="truncate text-[13px] font-bold">
                                ${item.no}
                            </p>

                            <p class="mt-1 text-[12px] text-[#64748b]">
                                ${item.tujuan}
                            </p>

                        </div>

                        ${statusBadge(item)}

                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">

                        <div>

                            <p class="text-[10px] text-[#94a3b8]">
                                Tanggal Perjalanan
                            </p>

                            <p class="mt-1 text-[11px] font-semibold">
                                ${item.tanggal}
                            </p>

                        </div>

                        <div>

                            <p class="text-[10px] text-[#94a3b8]">
                                Diajukan Pada
                            </p>

                            <p class="mt-1 text-[11px] font-semibold">
                                ${item.date}
                            </p>

                        </div>

                    </div>

                    <div class="mt-4 flex justify-end gap-2 border-t border-[#f1f5f9] pt-3">

                        <button
                            type="button"
                            onclick="openDetail('${item.no}')"
                            class="rounded-lg border border-[#e2e8f0] px-3 py-2 text-[11px] font-semibold text-[#64748b]"
                        >
                            Lihat
                        </button>

                        <button
                            type="button"
                            onclick="printItem('${item.no}')"
                            class="rounded-lg border border-[#0d6efd] px-3 py-2 text-[11px] font-semibold text-[#0d6efd]"
                        >
                            Cetak
                        </button>

                    </div>

                </div>

            `;

        });

    }

    /* ============================================================
       EMPTY
    ============================================================ */

    function showEmpty() {

        document
            .getElementById('emptyState')
            .classList
            .remove('hidden');

        document
            .getElementById('pagination')
            .classList
            .add('hidden');

    }

    function hideEmpty() {

        document
            .getElementById('emptyState')
            .classList
            .add('hidden');

        document
            .getElementById('pagination')
            .classList
            .remove('hidden');

    }

    /* ============================================================
       DETAIL
    ============================================================ */

    function openDetail(no) {

        const item =
            historyData.find(data =>
                data.no === no
            );

        if (!item) return;

        currentDetail = item;

        document.getElementById('modalNo').textContent =
            item.no;

        document.getElementById('modalUser').textContent =
            item.user;

        document.getElementById('modalTujuan').textContent =
            item.tujuan;

        document.getElementById('modalTanggal').textContent =
            item.tanggal;

        document.getElementById('modalDate').textContent =
            item.date;

        document.getElementById('modalStatus').innerHTML =
            statusBadge(item);

        const modal =
            document.getElementById('detailModal');

        modal.classList.remove('hidden');

        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');

    }

    function closeDetail() {

        const modal =
            document.getElementById('detailModal');

        modal.classList.add('hidden');

        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');

    }

    /* ============================================================
       PRINT
    ============================================================ */

    function printItem(no) {

        const item =
            historyData.find(data =>
                data.no === no
            );

        if (!item) return;

        const printWindow =
            window.open('', '_blank', 'width=900,height=700');

        printWindow.document.write(`

            <!DOCTYPE html>

            <html>

            <head>

                <title>${item.no}</title>

                <style>

                    body {
                        font-family: Arial, sans-serif;
                        padding: 40px;
                        color: #1e293b;
                    }

                    h1 {
                        font-size: 22px;
                        margin-bottom: 25px;
                    }

                    .row {
                        margin-bottom: 15px;
                    }

                    .label {
                        color: #64748b;
                        font-size: 12px;
                    }

                    .value {
                        font-weight: bold;
                        margin-top: 5px;
                    }

                </style>

            </head>

            <body>

                <h1>Riwayat Pengajuan SPPD</h1>

                <div class="row">
                    <div class="label">No. SPPD</div>
                    <div class="value">${item.no}</div>
                </div>

                <div class="row">
                    <div class="label">Diajukan Oleh</div>
                    <div class="value">${item.user}</div>
                </div>

                <div class="row">
                    <div class="label">Tujuan</div>
                    <div class="value">${item.tujuan}</div>
                </div>

                <div class="row">
                    <div class="label">Tanggal Perjalanan</div>
                    <div class="value">${item.tanggal}</div>
                </div>

                <div class="row">
                    <div class="label">Status</div>
                    <div class="value">${item.status}</div>
                </div>

                <div class="row">
                    <div class="label">Diajukan Pada</div>
                    <div class="value">${item.date}</div>
                </div>

                <hr>

                <p>
                    Dokumen ini merupakan simulasi Frontend
                    SPPD System.
                </p>

            </body>

            </html>

        `);

        printWindow.document.close();

        printWindow.focus();

        printWindow.print();

    }

    function printCurrentDetail() {

        if (!currentDetail) return;

        printItem(currentDetail.no);

    }

    /* ============================================================
       PROFILE
    ============================================================ */

    function closeProfileMenu() {

        document
            .getElementById('profileMenu')
            .classList
            .add('hidden');

    }

    /* ============================================================
       MOBILE SIDEBAR
    ============================================================ */

    function toggleSidebar() {

        const sidebar =
            document.getElementById('sidebar');

        const overlay =
            document.getElementById('sidebarOverlay');

        sidebar.classList.toggle('-translate-x-full');

        overlay.classList.toggle('hidden');

    }

    /* ============================================================
       PAGINATION SIMULATION
    ============================================================ */

    function previousPage() {

        alert('Halaman sebelumnya - simulasi Frontend.');

    }

    function nextPage() {

        alert('Halaman berikutnya - simulasi Frontend.');

    }

    /* ============================================================
       INIT
    ============================================================ */

    document.addEventListener('DOMContentLoaded', function() {

        const savedRole =
            localStorage.getItem('sppd_role');

        if (
            savedRole === 'staff' ||
            savedRole === 'manager' ||
            savedRole === 'ga'
        ) {

            switchRole(savedRole);

        }

        else {

            switchRole('staff');

        }

    });

</script>

</body>
</html>