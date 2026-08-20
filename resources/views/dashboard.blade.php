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
                <img
                    src="https://www.figma.com/api/mcp/asset/7604635b-f77c-4cfa-893a-f67b1c750d89.svg"
                    class="size-[18px]"
                    alt=""
                >
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
                        Selamat datang, Ahmad Ramzi 👋
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
                    <img
                        src="https://www.figma.com/api/mcp/asset/efcb384d-6213-418c-9d6e-b70e4a682260.svg"
                        class="size-10"
                        alt="Notifikasi"
                    >

                    <span
                        class="absolute right-1 top-1 size-2 rounded-full bg-red-500"
                    ></span>
                </button>


                {{-- Profile --}}
                <div class="relative">

                    <button
                        type="button"
                        id="profileToggle"
                        class="flex items-center gap-[10px]"
                    >

                        <img
                            src="https://www.figma.com/api/mcp/asset/5b52a92c-29aa-452a-aed3-28b87263b453.png"
                            class="size-9 rounded-full object-cover"
                            alt="Profile"
                        >

                        <div class="hidden flex-col gap-px text-left sm:flex">

                            <span
                                id="profileName"
                                class="text-[13px] font-semibold"
                            >
                                Ahmad Ramzi
                            </span>

                            <span
                                id="profileRole"
                                class="text-[11px] text-[#64748b]"
                            >
                                Staff IT
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
                        Buat Pengajuan SPPD
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

                        <button
                            onclick="showAll()"
                            class="text-[12px] font-semibold text-[#0d6efd]"
                        >
                            Lihat Semua
                        </button>

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
                <div class="mb-5 rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-sm sm:p-6">

                    <div class="mb-5">

                        <h2 class="text-[16px] font-bold">
                            Pengajuan Saya
                        </h2>

                        <p class="mt-1 text-[11px] text-[#64748b]">
                            Pengajuan perjalanan dinas yang dibuat oleh Anda.
                        </p>

                    </div>

                    <div id="managerOwnList" class="space-y-3"></div>

                </div>


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

    <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl sm:p-6">

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
                onclick="closeModal()"
                class="flex size-8 items-center justify-center rounded-lg bg-[#f8fafc]"
            >
                ✕
            </button>

        </div>


        <div
            id="modalContent"
            class="space-y-3 text-sm"
        ></div>


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
            name: 'Ahmad Ramzi',
            position: 'Staff IT',
            description: 'Menampilkan pengajuan milik sendiri.'
        },

        manager: {
            name: 'Ahmad Ramzi',
            position: 'Manager IT',
            description: 'Menampilkan pengajuan sendiri dan pengajuan bawahan.'
        },

        ga: {
            name: 'Ahmad Ramzi',
            position: 'General Affair',
            description: 'Menampilkan seluruh pengajuan yang dikelola GA.'
        }

    };


    const applications = [

        {
            no: 'SPPD-2026-00124',
            applicant: 'Ahmad Ramzi',
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
            applicant: 'Ahmad Ramzi',
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
            owner: 'staff',
            approval: true
        },

        {
            no: 'SPPD-2026-00121',
            applicant: 'Budi Santoso',
            destination: 'Jakarta',
            date: '28 Jul 2026',
            submitted: '20 Jul 2026',
            status: 'Selesai',
            type: 'done',
            owner: 'staff',
            approval: false
        },

        {
            no: 'SPPD-2026-00120',
            applicant: 'Ahmad Ramzi',
            destination: 'Semarang',
            date: '18 - 19 Jul 2026',
            submitted: '11 Jul 2026',
            status: 'Selesai',
            type: 'done',
            owner: 'manager',
            approval: false
        }

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

        return `

            <div class="rounded-xl border border-[#eef2f7] p-4 transition hover:border-[#dbeafe] hover:shadow-sm">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-2">

                            <span class="text-[13px] font-bold">
                                ${item.no}
                            </span>

                            ${statusBadge(item)}

                        </div>

                        <div class="mt-2 grid gap-1 text-[11px] text-[#64748b] sm:grid-cols-2 sm:gap-x-6">

                            <span>
                                Pemohon:
                                <b class="text-[#1e293b]">
                                    ${item.applicant}
                                </b>
                            </span>

                            <span>
                                Tujuan:
                                <b class="text-[#1e293b]">
                                    ${item.destination}
                                </b>
                            </span>

                            <span>
                                Tanggal:
                                <b class="text-[#1e293b]">
                                    ${item.date}
                                </b>
                            </span>

                            <span>
                                Diajukan:
                                <b class="text-[#1e293b]">
                                    ${item.submitted}
                                </b>
                            </span>

                        </div>

                    </div>


                    <div class="flex shrink-0 gap-2">

                        <button
                            onclick="showDetail('${item.no}')"
                            class="rounded-lg border border-[#dbeafe] px-3 py-2 text-[11px] font-semibold text-[#0d6efd] hover:bg-[#eff6ff]"
                        >
                            Detail
                        </button>

                        ${
                            approval
                            ?
                            `
                                <button
                                    onclick="approveApplication('${item.no}')"
                                    class="rounded-lg bg-[#16a34a] px-3 py-2 text-[11px] font-semibold text-white hover:bg-[#15803d]"
                                >
                                    Setujui
                                </button>

                                <button
                                    onclick="rejectApplication('${item.no}')"
                                    class="rounded-lg bg-[#fff1f2] px-3 py-2 text-[11px] font-semibold text-[#dc2626] hover:bg-[#ffe4e6]"
                                >
                                    Tolak
                                </button>
                            `
                            :
                            ''
                        }

                    </div>

                </div>

            </div>

        `;

    }


    /*
    |--------------------------------------------------------------------------
    | STAFF
    |--------------------------------------------------------------------------
    */

    function renderStaff() {

        const data = applications.filter(item =>
            item.applicant === 'Ahmad Ramzi'
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

        const own = applications.filter(item =>
            item.owner === 'manager'
        );

        const approval = applications.filter(item =>
            item.approval === true
        );

        document
            .getElementById('managerOwnList')
            .innerHTML = own.length
                ? own.map(item => applicationCard(item)).join('')
                : emptyState('Belum ada pengajuan Anda.');

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

        document
            .getElementById('gaList')
            .innerHTML = applications
                .map(item => applicationCard(item))
                .join('');

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

    function showDetail(no) {

        const item = applications.find(item =>
            item.no === no
        );

        if (!item) return;

        document
            .getElementById('modalTitle')
            .innerText = item.no;

        document
            .getElementById('modalContent')
            .innerHTML = `

                <div class="flex justify-between border-b border-[#f1f5f9] pb-3">
                    <span class="text-[#64748b]">Pemohon</span>
                    <b>${item.applicant}</b>
                </div>

                <div class="flex justify-between border-b border-[#f1f5f9] pb-3">
                    <span class="text-[#64748b]">Tujuan</span>
                    <b>${item.destination}</b>
                </div>

                <div class="flex justify-between border-b border-[#f1f5f9] pb-3">
                    <span class="text-[#64748b]">Tanggal</span>
                    <b>${item.date}</b>
                </div>

                <div class="flex justify-between border-b border-[#f1f5f9] pb-3">
                    <span class="text-[#64748b]">Status</span>
                    ${statusBadge(item)}
                </div>

            `;


        let actions = '';

        if (
            currentRole === 'manager' &&
            item.approval === true
        ) {

            actions = `

                <button
                    onclick="approveApplication('${item.no}'); closeModal();"
                    class="rounded-lg bg-[#16a34a] px-4 py-2 text-sm font-semibold text-white"
                >
                    Setujui
                </button>

                <button
                    onclick="rejectApplication('${item.no}'); closeModal();"
                    class="rounded-lg bg-red-50 px-4 py-2 text-sm font-semibold text-red-600"
                >
                    Tolak
                </button>

            `;

        }

        document
            .getElementById('modalActions')
            .innerHTML = actions;

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

    function approveApplication(no) {

        const item = applications.find(item =>
            item.no === no
        );

        if (!item) return;

        item.status = 'Disetujui';
        item.type = 'done';
        item.approval = false;

        alert(`${no} berhasil disetujui.`);

        renderData();

    }


    function rejectApplication(no) {

        const item = applications.find(item =>
            item.no === no
        );

        if (!item) return;

        item.status = 'Ditolak';
        item.type = 'rejected';
        item.approval = false;

        alert(`${no} ditolak.`);

        renderData();

    }


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

    function showAll() {

        alert('Halaman Riwayat Pengajuan akan digunakan untuk melihat seluruh pengajuan.');

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL
    |--------------------------------------------------------------------------
    */

    renderData();

</script>

</body>
</html>