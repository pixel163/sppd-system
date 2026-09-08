<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - SPPD System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >
</head>

<body class="min-h-screen bg-[#f8fafc] font-['Inter',sans-serif] text-[#1e293b]">

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
        class="fixed inset-y-0 left-0 z-50 flex w-[260px] -translate-x-full flex-col border-r border-[#e2e8f0] bg-white px-4 py-6 transition-transform duration-300 lg:static lg:translate-x-0"
    >

        {{-- Brand --}}
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

            <button
                onclick="toggleSidebar()"
                class="ml-auto lg:hidden"
            >
                ✕
            </button>

        </div>


        {{-- Main Navigation --}}
        <nav class="mt-8 flex flex-col gap-1">

            <a
                href="/dashboard"
                class="flex w-full items-center gap-3 rounded-xl bg-[#eff6ff] px-4 py-3"
            >

                <span class="text-[14px] font-semibold text-[#0d6efd]">
                    Dashboard
                </span>
            </a>

            <a
                href="/sppd"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Pengajuan SPPD
                </span>
            </a>

            <a
                href="/ilpd"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Perizinan
                </span>
            </a>

            <a
                href="/riwayat"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Riwayat Pengajuan
                </span>
            </a>

            <a
                href="/dokumen"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-[#f8fafc]"
            >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dokumen & Tiket
                </span>
            </a>

        </nav>


        <div class="my-4 h-px w-full bg-[#e2e8f0]"></div>


        {{-- Secondary Navigation --}}
        {{-- <nav class="flex flex-col gap-1">

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#f8fafc]"
            >
                <span class="text-[14px] font-medium text-[#64748b]">
                    Profile
                </span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#f8fafc]"
            >
                <span class="text-[14px] font-medium text-[#64748b]">
                    Pengaturan
                </span>
            </a>

        </nav> --}}

        <div class="flex-1"></div>

    </aside>


    {{-- =========================================================
         MAIN
    ========================================================== --}}
    <main class="min-w-0 flex-1">

        {{-- HEADER --}}
        <header
            class="flex flex-col gap-5 border-b border-[#e2e8f0] bg-white px-5 py-5 sm:px-7 lg:flex-row lg:items-center lg:justify-between lg:border-0 lg:bg-transparent"
        >

            <div class="flex items-start gap-3">

                {{-- Mobile Menu --}}
                <button
                    onclick="toggleSidebar()"
                    class="mt-1 flex size-9 shrink-0 items-center justify-center rounded-lg border border-[#e2e8f0] bg-white lg:hidden"
                >
                    ☰
                </button>

                <div class="flex flex-col gap-1">

                    <h1
                        id="welcomeText"
                        class="text-[20px] font-bold sm:text-[24px]"
                    >
                        Selamat datang, Eko Saputra 👋
                    </h1>

                    <p class="text-[12px] text-[#64748b] sm:text-[13px]">
                        Kelola pengajuan SPPD dan perizinan perjalanan dinas Anda dengan mudah.
                    </p>

                </div>

            </div>


            <div class="flex items-center justify-between gap-4 sm:justify-end">

                {{-- Notification --}}
                <button
                    type="button"
                    onclick="showNotification()"
                    class="relative flex size-10 items-center justify-center rounded-xl bg-white"
                >

                </button>


                {{-- Profile --}}
                <div class="relative">

                    <button
                        type="button"
                        id="profileToggle"
                        class="flex items-center gap-[10px]"
                        aria-expanded="false"
                    >

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


                    {{-- Profile Dropdown --}}
                    <div
                        id="profileMenu"
                        class="absolute right-0 top-12 z-50 hidden w-52 rounded-xl border border-[#e2e8f0] bg-white p-2 shadow-lg"
                    >

                        <div class="border-b border-[#e2e8f0] px-3 py-2">

                            <p class="text-xs font-semibold">
                                Mode Tampilan
                            </p>

                            <p class="mt-1 text-[11px] text-[#64748b]">
                                Untuk simulasi FE
                            </p>

                        </div>


                        <button
                            onclick="switchRole('staff')"
                            class="w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-[#f8fafc]"
                        >
                            Staff
                        </button>

                        <button
                            onclick="switchRole('manager')"
                            class="w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-[#f8fafc]"
                        >
                            Manager
                        </button>

                        <button
                            onclick="switchRole('ga')"
                            class="w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-[#f8fafc]"
                        >
                            GA
                        </button>


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


                {{-- Create SPPD --}}
                <a
                    href="/sppd"
                    class="flex items-center gap-2 rounded-[10px] bg-[#0d6efd] px-4 py-[10px] text-[13px] font-semibold text-white transition hover:bg-[#0958c9]"
                >
                    <span>+</span>
                    <span class="hidden sm:inline">
                        Buat SPPD
                    </span>
                    <span class="sm:hidden">
                        Buat SPPD
                    </span>
                </a>

            </div>

        </header>


        {{-- =========================================================
             CONTENT
        ========================================================== --}}
        <section class="px-5 pb-8 pt-5 sm:px-7">


            {{-- Role Indicator --}}
            <div
                id="roleInfo"
                class="mb-5 rounded-xl border border-[#dbeafe] bg-[#eff6ff] px-4 py-3"
            >
                <p class="text-xs font-semibold text-[#1d4ed8]">
                    Mode Staff
                </p>

                <p class="mt-1 text-[11px] text-[#64748b]">
                    Menampilkan pengajuan milik sendiri.
                </p>
            </div>


            {{-- =====================================================
                 STAFF
            ====================================================== --}}
            <div id="staffSection">

                <div class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-[0px_4px_6px_rgba(15,23,42,0.02)] sm:p-6">

                    <div class="mb-5 flex items-center justify-between">

                        <div>
                            <h2 class="text-[16px] font-bold">
                                Pengajuan Terbaru
                            </h2>

                            <p class="mt-1 text-[11px] text-[#64748b]">
                                Pengajuan perjalanan dinas Anda
                            </p>
                        </div>

                        <a href="/riwayat"
                            {{-- onclick="showAll()" --}}
                            class="text-[12px] font-semibold text-[#0d6efd]"
                        >
                            Lihat Semua
                        </a>

                    </div>


                    <div id="staffList" class="space-y-3"></div>

                </div>

            </div>


            {{-- =====================================================
                 MANAGER
            ====================================================== --}}
            <div
                id="managerSection"
                class="hidden"
            >

                {{-- Pengajuan Manager --}}
                {{-- <div class="mb-5 rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-sm sm:p-6">

                    <div class="mb-5">

                        <h2 class="text-[16px] font-bold">
                            Pengajuan Saya
                        </h2>

                        <p class="mt-1 text-[11px] text-[#64748b]">
                            Pengajuan perjalanan dinas yang dibuat oleh Anda.
                        </p>

                    </div>

                    <div id="managerOwnList" class="space-y-3"></div>

                </div> --}}


                {{-- Approval --}}
                <div class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-sm sm:p-6">

                    <div class="mb-5">

                        <h2 class="text-[16px] font-bold">
                            Perlu Persetujuan Saya
                        </h2>

                        <p class="mt-1 text-[11px] text-[#64748b]">
                            Pengajuan staff/bawahan yang membutuhkan pemeriksaan Anda.
                        </p>

                    </div>

                    <div id="approvalList" class="space-y-3"></div>

                </div>

            </div>


            {{-- =====================================================
                 GA
            ====================================================== --}}
            <div
                id="gaSection"
                class="hidden"
            >

                <div class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-sm sm:p-6">

                    <div class="mb-5">

                        <h2 class="text-[16px] font-bold">
                            Pengajuan SPPD
                        </h2>

                        <p class="mt-1 text-[11px] text-[#64748b]">
                            Daftar pengajuan yang perlu dikelola oleh GA.
                        </p>

                    </div>

                    <div id="gaList" class="space-y-3"></div>

                </div>

            </div>

        </section>

    </main>

</div>


{{-- =============================================================
     DETAIL MODAL
============================================================= --}}
<div
    id="detailModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/30 p-4"
>
    <div
        class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white p-5 shadow-xl sm:p-6"
    >

        <!-- Header -->
        <div class="mb-5 flex items-start justify-between">

            <div>
                <p class="text-[11px] text-[#64748b]">
                    Detail Pengajuan
                </p>

                <h3
                    id="modalTitle"
                    class="mt-1 text-lg font-bold"
                >
                    SPPD
                </h3>
            </div>

            <button
                type="button"
                onclick="closeModal()"
                class="flex size-8 items-center justify-center rounded-lg bg-[#f8fafc] text-[#64748b] hover:bg-[#f1f5f9]"
            >
                ✕
            </button>

        </div>


        <!-- Informasi Pengajuan -->
        <div
            id="modalInfo"
            class="mb-5 rounded-xl border border-[#f1f5f9] p-4"
        ></div>


        <!-- Daftar Dokumen -->
        <div>

            <div class="mb-3">
                <h4 class="text-sm font-semibold">
                    Dokumen Pengajuan
                </h4>

                <p class="mt-1 text-[12px] text-[#64748b]">
                    Dokumen yang terkait dengan pengajuan ini.
                </p>
            </div>

            <div
                id="modalContent"
                class="space-y-3"
            ></div>

        </div>


        <!-- Action -->
        <div
            id="modalActions"
            class="mt-6 flex justify-end gap-2"
        ></div>

    </div>
</div>

{{-- =============================================================
     JAVASCRIPT
============================================================= --}}
<script>

    /*
    |--------------------------------------------------------------------------
    | DATA DUMMY
    |--------------------------------------------------------------------------
    */

    const users = {

        staff: {
            name: 'Eko Saputra',
            position: 'Staff',
            description: 'Menampilkan pengajuan milik sendiri.'
        },

        manager: {
            name: 'Andi Wijaya',
            position: 'Manager',
            description: 'Menampilkan pengajuan sendiri dan pengajuan bawahan.'
        },

        ga: {
            name: 'Budi Santoso',
            position: 'General Affair',
            description: 'Menampilkan seluruh pengajuan yang dikelola GA.'
        }

    };

    let applications = [

        {
            no: 'SPPD-2026-00124',
            applicant: 'Eko Saputra',
            destination: 'Bandung',
            date: '20 - 22 Aug 2026',
            submitted: '11 Aug 2026',
            status: 'Menunggu Approval',
            type: 'waiting',
            owner: 'staff',
            approval: true
        },

        {
            no: 'SPPD-2026-00123',
            applicant: 'Eko Saputra',
            destination: 'Yogyakarta',
            date: '15 - 17 Aug 2026',
            submitted: '8 Aug 2026',
            status: 'Sedang Diproses',
            type: 'process',
            owner: 'staff',
            approval: false
        },

        {
            no: 'SPPD-2026-00122',
            applicant: 'Budi Santoso',
            destination: 'Surabaya',
            date: '05 - 06 Aug 2026',
            submitted: '5 Aug 2026',
            status: 'Menunggu Approval',
            type: 'waiting',
            owner: 'manager',
            approval: false
        },

        {
            no: 'SPPD-2026-00121',
            applicant: 'Eko Saputra',
            destination: 'Jakarta',
            date: '28 - 30 Jun 2026',
            submitted: '20 Jul 2026',
            status: 'Approval',
            type: 'done',
            owner: 'ga',
            approval: false
        },

        {
            no: 'SPPD-2026-00125',
            applicant: 'Budi Santoso',
            destination: 'Jakarta',
            date: '25 - 28 Jul 2026',
            submitted: '20 Jul 2026',
            status: 'Approval',
            type: 'done',
            owner: 'ga',
            approval: false
        },

        {
            no: 'SPPD-2026-00120',
            applicant: 'Andi Wijaya',
            destination: 'Semarang',
            date: '18 - 19 Jul 2026',
            submitted: '11 Jul 2026',
            status: 'Approval',
            type: 'done',
            owner: 'manager',
            approval: false
        }

    ];

    const savedApplications =
    JSON.parse(localStorage.getItem("sppd_applications")) || [];
    
    
    applications = [
        ...savedApplications,
        ...applications
    ];
    

    let currentRole = 'staff';

    /*
    |--------------------------------------------------------------------------
    | PROFILE DROPDOWN
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ROLE SWITCH
    |--------------------------------------------------------------------------
    */

    function switchRole(role) {

        currentRole = role;

        localStorage.setItem('sppd_role', role);

        const user = users[role];

        document
            .getElementById('profileName')
            .innerText = user.name;

        document
            .getElementById('profileRole')
            .innerText = user.position;

        document
            .getElementById('welcomeText')
            .innerText = `Selamat datang, ${user.name} 👋`;

        document
            .getElementById('roleInfo')
            .innerHTML = `
                <p class="text-xs font-semibold text-[#1d4ed8]">
                    Mode ${role.toUpperCase()}
                </p>

                <p class="mt-1 text-[11px] text-[#64748b]">
                    ${user.description}
                </p>
            `;

        document.getElementById('staffSection').classList.add('hidden');
        document.getElementById('managerSection').classList.add('hidden');
        document.getElementById('gaSection').classList.add('hidden');

        if (role === 'staff') {
            document.getElementById('staffSection').classList.remove('hidden');
        }

        if (role === 'manager') {
            document.getElementById('managerSection').classList.remove('hidden');
        }

        if (role === 'ga') {
            document.getElementById('gaSection').classList.remove('hidden');
        }

        document
            .getElementById('profileMenu')
            .classList.add('hidden');

        renderData();

    }

    /*
    |--------------------------------------------------------------------------
    | RENDER DATA
    |--------------------------------------------------------------------------
    */

    function renderData() {

        renderStaff();

        renderManager();

        renderGA();

    }


    function statusBadge(item) {

        let classes = '';

        if (item.type === 'waiting') {
            classes = 'bg-[#fff7ed] text-[#d97706]';
        }

        else if (item.type === 'process') {
            classes = 'bg-[#eff6ff] text-[#2563eb]';
        }

        else if (item.type === 'rejected') {
            classes = 'bg-[#fef2f2] text-[#dc2626]';
        }

        else {
            classes = 'bg-[#f0fdf4] text-[#16a34a]';
        }

        return `
            <span class="inline-flex rounded-lg px-2.5 py-1.5 text-[11px] font-semibold ${classes}">
                ${item.status}
            </span>
        `;

    }

    function applicationCard(item, options = {}) {
        const approval = options.approval || false;
        // Pastikan nomor SPPD aman dari karakter pengganggu
        const noSPPD = String(item.no || '').trim();

        return `
            <div class="rounded-xl border border-[#eef2f7] p-4 transition hover:border-[#dbeafe] hover:shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[13px] font-bold">
                                ${noSPPD}
                            </span>
                            ${typeof statusBadge === 'function' ? statusBadge(item) : ''}
                        </div>

                        <div class="mt-2 grid gap-1 text-[11px] text-[#64748b] sm:grid-cols-2 sm:gap-x-6">
                            <span>Pemohon: <b class="text-[#1e293b]">${item.applicant || '-'}</b></span>
                            <span>Tujuan: <b class="text-[#1e293b]">${item.destination || '-'}</b></span>
                            <span>Waktu Dinas: <b class="text-[#1e293b]">${item.date || '-'}</b></span>
                            <span>Diajukan: <b class="text-[#1e293b]">${item.submitted || '-'}</b></span>
                        </div>
                    </div>

                    <div class="flex shrink-0 gap-2">
                        ${
                            approval
                            ? `
                                <button
                                    onclick="processApplication('${noSPPD}')"
                                    class="rounded-lg bg-[#0d6efd] px-3 py-2 text-[11px] font-semibold text-white hover:bg-[#0958c9]"
                                >
                                    Proses
                                </button>
                            `
                            : `
                                <button
                                    onclick="showDetail('${noSPPD}')"
                                    class="rounded-lg border border-[#dbeafe] px-3 py-2 text-[11px] font-semibold text-[#0d6efd] hover:bg-[#eff6ff]"
                                >
                                    Detail
                                </button>
                            `
                        }
                    </div>

                </div>
            </div>
        `;
    }

    function editApplication(id) {
        // Cari data berdasarkan ID
        const itemToEdit = applications.find(app => app.id === id);

        if (!itemToEdit) return;

        // Isi input form kamu dengan data lama
        // Sesuaikan ID input di bawah ini dengan ID elemen <input> pada HTML form kamu
        document.getElementById('inputAppId').value = itemToEdit.id; // Input Hidden untuk simpan ID
        document.getElementById('inputDestination').value = itemToEdit.destination || '';
        // ... masukan field form lainnya di sini ...

        // Buka Modal / Tampilkan Form Edit kamu jika menggunakan modal
        // document.getElementById('myModal').classList.remove('hidden');
    }

    function saveEditApplication(event) {
        event.preventDefault(); // Cegah reload halaman

        const appId = parseInt(document.getElementById('inputAppId').value);

        // Cari index data di dalam array
        const index = applications.findIndex(app => app.id === appId);

        if (index !== -1) {
            // Update data di array dengan nilai input baru dari Form
            applications[index].destination = document.getElementById('inputDestination').value;
            // ... update field lainnya di sini ...

            // 1. Simpan Array yang sudah di-update ke LocalStorage
            localStorage.setItem("sppd_applications", JSON.stringify(applications));

            // 2. Render ulang tampilan layar agar data terbaru langsung terlihat
            renderData();

            alert("Data pengajuan berhasil diperbarui!");
        }
    }


    /*
    |--------------------------------------------------------------------------
    | STAFF
    |--------------------------------------------------------------------------
    */

    function renderStaff() {

        const data = applications.filter(item =>
            item.applicant === users.staff.name
        );

        document
            .getElementById('staffList')
            .innerHTML = data
            .map(item => applicationCard(item))
            .join('');

    }

    /*
    |--------------------------------------------------------------------------
    | MANAGER
    |--------------------------------------------------------------------------
    */

    function renderManager() {

        // const own = applications.filter(item =>
        //     item.owner === 'manager'
        // );

        const approval = applications.filter(item =>
            item.approval === true
        );

        // document
        //     .getElementById('managerOwnList')
        //     .innerHTML = own.length
        //         ? own.map(item => applicationCard(item)).join('')
        //         : emptyState('Belum ada pengajuan Anda.');

        document
            .getElementById('approvalList')
            .innerHTML = approval.length
                ? approval.map(item => applicationCard(item, {
                    approval: true
                })).join('')
                : emptyState('Tidak ada pengajuan yang menunggu persetujuan.');

    }

    /*
    |--------------------------------------------------------------------------
    | GA
    |--------------------------------------------------------------------------
    */

    function renderGA() {
        // Ambil data gabungan dari window/static dan localStorage
        const localData = JSON.parse(localStorage.getItem('sppd_applications')) || [];
        const allApplications = [...(window.applicationsDetails || []), ...localData];

        // Filter data yang butuh tindakan/proses dari GA
        // (Pengajuan yang sudah lolos persetujuan Manager)
        const gaItems = allApplications.filter(item => 
            item.status === 'Disetujui Manager' || 
            item.status === 'Waiting GA' ||
            item.status === 'Sedang Diproses'
        );

        const container = document.getElementById('gaList');
        if (!container) return;

        // Render ke HTML
        container.innerHTML = gaItems.length > 0 
            ? gaItems.map(item => gaApplicationCard(item)).join('')
            : emptyState('Tidak ada pengajuan yang perlu dikelola GA.');
    }
    // function renderGA() {

    //     document
    //         .getElementById('gaList')
    //         .innerHTML = applications
    //             .map(item => applicationCard(item))
    //             .join('');

    // }

    function gaApplicationCard(item) {
        return `
            <div class="flex flex-col gap-4 rounded-xl border border-[#e2e8f0] bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-[#0f172a]">${item.no}</span>
                        <span class="rounded bg-blue-100 px-2 py-0.5 text-[11px] font-medium text-blue-700">${item.status}</span>
                    </div>
                    <p class="mt-1 text-xs text-[#64748b]">Pemohon: <strong>${item.applicant || '-'}</strong> | Tujuan: <strong>${item.destination || '-'}</strong></p>
                    <p class="text-[11px] text-[#94a3b8]">Tgl Pengajuan: ${item.date || '-'}</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <!-- Tombol Proses Khusus GA -->
                    <button type="button" 
                            onclick="prosesOlehGA('${item.no}')" 
                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                        Proses
                    </button>
                </div>
            </div>
        `;
    }

    function emptyState(message) {

        return `
            <div class="rounded-xl border border-dashed border-[#e2e8f0] px-4 py-8 text-center">

                <p class="text-sm font-medium text-[#64748b]">
                    ${message}
                </p>

            </div>
        `;

    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */
    const applicationsDetails = [

            {
                no: "SPPD-2026-00123",
                applicant: "Eko Saputra",
                destination: "Yogyakarta",
                date: "15 - 17 Agustus 2026",
                status: "Sedang Diproses",

                dokumen: [
                    "form_sppd",
                    "form_ilpd",
                ]
            },

            {
                no: "SPPD-2026-00124",
                applicant: "Eko Saputra",
                destination: "Bandung",
                date: "20 - 22 Agustus 2026",
                status: "Menunggu Approval",

                dokumen: [
                    "form_sppd",
                    "form_ilpd",
                ]
            },

            {
                no: 'SPPD-2026-00122',
                applicant: 'Budi Santoso',
                destination: 'Surabaya',
                date: '05 - 06 Aug 2026',
                status: 'Menunggu Approval',
                
                dokumen: [
                    "form_sppd",
                    "form_ilpd",
                ]
            },

            {
                no: 'SPPD-2026-00121',
                applicant: 'Eko Saputra',
                destination: 'Jakarta',
                date: '28 - 30 Jun 2026',
                status: 'Disetujui',

                dokumen: [
                    "form_sppd",
                    "form_ilpd",
                    "tiket"
                ]
            },

            {
                no: 'SPPD-2026-00125',
                applicant: 'Budi Santoso',
                destination: 'Jakarta',
                date: '25 - 28 Jul 2026',
                status: 'Disetujui',

                dokumen: [
                    "form_sppd",
                    "form_ilpd",
                    "tiket"
                ]
            },

            {
                no: "SPPD-2026-00120",
                applicant: "Andi Wijaya",
                destination: "Semarang",
                date: "18 - 19 Juli 2026",
                status: "Disetujui",

                dokumen: [
                    "form_sppd",
                    "form_ilpd",
                    "tiket"
                ]
            }

        ];

    function getDocumentData(type) {
        const documents = {
            form_sppd: {
                title: "Form SPPD",
                description: "Dokumen surat tugas perjalanan dinas.",
                date: "Dibuat 20 Agustus 2026",
                iconBg: "bg-blue-50",
                iconColor: "text-blue-600",
                border: "border-blue-100"
            },
            form_ilpd: {
                title: "Form ILPD",
                description: "Form ILPD perjalanan dinas.",
                date: "Dibuat 20 Agustus 2026",
                iconBg: "bg-emerald-50",
                iconColor: "text-emerald-600",
                border: "border-emerald-100"
            },
            tiket: {
                title: "Tiket",
                description: "Laporan hasil perjalanan dinas.",
                date: "Dibuat 22 Agustus 2026",
                iconBg: "bg-purple-50",
                iconColor: "text-purple-600",
                border: "border-purple-100"
            }
        };

        return documents[type] || {
            title: "Dokumen",
            description: "Dokumen pengajuan.",
            date: "-",
            iconBg: "bg-gray-50",
            iconColor: "text-gray-600",
            border: "border-gray-100"
        };
    }

    function showDetail(no) {

        // 1. Ambil data dari localStorage (sesuaikan nama key storage-nya, misal: 'sppd_data')
        const localData = JSON.parse(localStorage.getItem('sppd_applications')) || [];
        
        // Optional: Gabungkan data localStorage dengan applicationsDetails jika masih butuh data dummy
        const allItems = [...applicationsDetails, ...localData];

        // 2. Cari berdasarkan nomor SPPD
        const item = allItems.find(item => item.no === no);

        // Debugging: Cek di console jika item tidak ditemukan
        if (!item) {
            console.error('Data tidak ditemukan untuk No:', no);
            return;
        }

        // const item = applicationsDetails.find(item =>
        //     item.no === no
        // );

        // if (!item) return;

        // =========================
        // HEADER
        // =========================

        document
            .getElementById('modalTitle')
            .innerText = item.no;

        // =========================
        // INFORMASI PENGAJUAN
        // =========================

        document
            .getElementById('modalInfo')
            .innerHTML = `

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                    <div>
                        <p class="text-[11px] text-[#64748b]">
                            Pemohon
                        </p>

                        <p class="mt-1 text-sm font-semibold">
                            ${item.applicant}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] text-[#64748b]">
                            Tujuan
                        </p>

                        <p class="mt-1 text-sm font-semibold">
                            ${item.destination}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] text-[#64748b]">
                            Tanggal
                        </p>

                        <p class="mt-1 text-sm font-semibold">
                            ${item.date}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] text-[#64748b]">
                            Status
                        </p>

                        <div class="mt-1">
                            ${statusBadge(item)}
                        </div>
                    </div>

                </div>

            `;

        // =========================
        // DOKUMEN
        // =========================

        const container =
            document.getElementById('modalContent');

        container.innerHTML = '';

        /*
        * Kalau item.dokumen kosong,
        * tampilkan pesan.
        */

        if (!item.dokumen || item.dokumen.length === 0) {

            container.innerHTML = `

                <div class="rounded-xl border border-dashed border-[#e2e8f0] p-6 text-center">

                    <p class="text-sm font-medium text-[#64748b]">
                        Belum ada dokumen
                    </p>

                    <p class="mt-1 text-[12px] text-[#94a3b8]">
                        Dokumen pengajuan belum tersedia.
                    </p>

                </div>

            `;

        } else {

            item.dokumen.forEach(type => {

                const doc =
                    getDocumentData(type);

                function getDocumentData(type) {

                const documents = {

                    form_sppd: {

                        title: "Form SPPD",

                        description:
                            "Dokumen surat tugas perjalanan dinas.",

                        date:
                            "Dibuat 20 Agustus 2026",

                        iconBg:
                            "bg-blue-50",

                        iconColor:
                            "text-blue-600",

                        border:
                            "border-blue-100"

                    },

                    form_ilpd: {

                        title: "Form ILPD",

                        description:
                            "Form ILPD perjalanan dinas.",

                        date:
                            "Dibuat 20 Agustus 2026",

                        iconBg:
                            "bg-emerald-50",

                        iconColor:
                            "text-emerald-600",

                        border:
                            "border-emerald-100"

                    },

                    tiket: {

                        title: "Tiket",

                        description:
                            "Laporan hasil perjalanan dinas.",

                        date:
                            "Dibuat 22 Agustus 2026",

                        iconBg:
                            "bg-purple-50",

                        iconColor:
                            "text-purple-600",

                        border:
                            "border-purple-100"

                    }

                };

                return documents[type] || {

                    title: "Dokumen",

                    description:
                        "Dokumen pengajuan.",

                    date:
                        "-",

                    iconBg:
                        "bg-gray-50",

                    iconColor:
                        "text-gray-600",

                    border:
                        "border-gray-100"

                };

            }

                const wrapper =
                    document.createElement('div');

                wrapper.className =
                    'flex flex-col gap-4 rounded-xl border border-[#f1f5f9] p-4 sm:flex-row sm:items-center';

                // =========================
                // TOMBOL DOKUMEN
                // =========================

                let documentButtons = '';

                /*
                * MENUNGGU APPROVAL
                * Edit + Lihat
                */

                if (
                    item.status === 'Menunggu Approval'
                ) {

                    documentButtons = `

                        <button
                            type="button"
                            class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b]"
                            onclick="editDocument('${type}', '${item.no}')"
                        >

                            <span>
                                ✎
                            </span>

                            Edit

                        </button>

                        <button
                            type="button"
                            class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b]"
                            onclick="openDocumentModal('${type}', '${item.no}')"
                        >

                            <span>
                                👁
                            </span>

                            Lihat

                        </button>

                    `;

                }

                /*
                * SEDANG DIPROSES
                * Hanya Lihat
                */

                else if (
                    item.status === 'Sedang Diproses'
                ) {

                    documentButtons = `

                        <button
                            type="button"
                            class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b]"
                            onclick="openDocumentModal('${type}', '${item.no}')"
                        >

                            <span>
                                👁
                            </span>

                            Lihat

                        </button>

                    `;

                }

                /*
                * DISETUJUI / APPROVAL
                * Lihat + Cetak
                */

                else if (
                    item.status === 'Disetujui' ||
                    item.status === 'Approved' ||
                    item.status === 'Approval'
                ) {

                    documentButtons = `

                        <button
                            type="button"
                            class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b]"
                            onclick="openDocumentModal('${type}', '${item.no}')"
                        >

                            <span>
                                👁
                            </span>

                            Lihat

                        </button>

                        <button
                            type="button"
                            class="flex items-center gap-1.5 rounded-lg border ${doc.border} px-4 py-2 text-[13px] font-medium ${doc.iconColor}"
                            onclick="printSpecificDocument('${type}', '${item.no}')"
                        >

                            <span>
                                🖨
                            </span>

                            Cetak

                        </button>

                    `;

                }

                // =========================
                // HTML DOKUMEN
                // =========================

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

                        ${documentButtons}

                    </div>

                `;

                container.appendChild(wrapper);

            });

        }

        // =========================
        // MODAL ACTIONS
        // =========================

        document
            .getElementById('modalActions')
            .innerHTML = '';

        // =========================
        // OPEN MODAL
        // =========================

        document
            .getElementById('detailModal')
            .classList.remove('hidden');

        document
            .getElementById('detailModal')
            .classList.add('flex');

    }

    function closeModal() {

        document
            .getElementById('detailModal')
            .classList.add('hidden');

        document
            .getElementById('detailModal')
            .classList.remove('flex');

    }

    /*
    |--------------------------------------------------------------------------
    | APPROVAL SIMULATION
    |--------------------------------------------------------------------------
    */
    function processApplication(no) {
        if (!no) {
            alert("Nomor SPPD tidak valid.");
            return;
        }
        // Arahkan ke halaman manager approval dengan query string ?no=...
        window.location.href = `/manager/sam?no=${encodeURIComponent(no)}`;
    }
    // function processApplication(no) {
    //     window.location.href =
    //         `/manager/sam?no=${encodeURIComponent(no)}`;
    // }
    // function processApplication(no) {
    //     // 1. Cari data pengajuan berdasarkan nomor SPPD
    //     const item = applications.find(app => app.no === no);
    //     if (!item) return;

    //     // 2. Isi data staff ke dalam form SPPD (autofill)
    //     document.getElementById('noSppdInput').value = item.no;
    //     document.getElementById('namaPemohonInput').value = item.applicant;
    //     document.getElementById('tujuanInput').value = item.destination;
    //     document.getElementById('tanggalInput').value = item.date;

    //     // 3. Sembunyikan view dashboard/list dan tampilkan view form
    //     document.getElementById('dashboardView').classList.add('hidden');
    //     document.getElementById('formSppdView').classList.remove('hidden');
    // }

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION
    |--------------------------------------------------------------------------
    */

    function showNotification() {

        alert('Belum ada notifikasi baru.');

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE SIDEBAR
    |--------------------------------------------------------------------------
    */

    function toggleSidebar() {

        const sidebar =
            document.getElementById('sidebar');

        const overlay =
            document.getElementById('sidebarOverlay');

        sidebar.classList.toggle('-translate-x-full');

        overlay.classList.toggle('hidden');

    }


    /*
    |--------------------------------------------------------------------------
    | VIEW ALL
    |--------------------------------------------------------------------------
    */

    // function showAll() {

    //     alert('Halaman Riwayat Pengajuan akan digunakan untuk melihat seluruh pengajuan.');

    // }


    /*
    |--------------------------------------------------------------------------
    | INITIAL
    |--------------------------------------------------------------------------
    */

    renderData();

</script>

</body>
</html>