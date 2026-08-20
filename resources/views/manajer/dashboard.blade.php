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

<body class="min-h-screen bg-white font-['Inter',sans-serif] text-[#1e293b]">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="flex w-[260px] shrink-0 flex-col gap-6 border-r border-[#e2e8f0] bg-white px-4 py-6">

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
                <span class="text-[16px] font-bold leading-normal">
                    SPPD System
                </span>

                <span class="text-[11px] text-[#64748b]">
                    Sistem Perjalanan Dinas
                </span>
            </div>

        </div>


        {{-- Main Navigation --}}
        <nav class="flex flex-col gap-1">

            <a
                href="#"
                class="flex w-full items-center gap-3 rounded-xl bg-[#eff6ff] px-4 py-3"
            >
                <img
                    src="https://www.figma.com/api/mcp/asset/e308fa30-39b3-4687-9b33-5b778b6d6db8.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-semibold text-[#0d6efd]">
                    Dashboard
                </span>
            </a>


            <a href="/sppd" class="flex items-center gap-3 rounded-xl px-4 py-3">
                <img
                    src="https://www.figma.com/api/mcp/asset/b17b146a-b4ea-4830-9131-124cfcfc5c75.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Pengajuan SPPD
                </span>
            </a>


            <a href="/ilpd" class="flex items-center gap-3 rounded-xl px-4 py-3">
                <img
                    src="https://www.figma.com/api/mcp/asset/7c4e7547-5192-438e-9ea6-4b9b31eba86a.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Perizinan
                </span>
            </a>


            <a href="/riwayat" class="flex items-center gap-3 rounded-xl px-4 py-3">
                <img
                    src="https://www.figma.com/api/mcp/asset/8cc31aa4-62e7-4276-a746-dad59f3e7baa.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Riwayat Pengajuan
                </span>
            </a>


            <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3">
                <img
                    src="https://www.figma.com/api/mcp/asset/ad4db6e0-4aad-4b24-82e7-4d824088c87e.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dokumen & Tiket
                </span>
            </a>

        </nav>


        {{-- Divider --}}
        <div class="h-px w-full bg-[#e2e8f0]"></div>


        {{-- Secondary Navigation --}}
        <nav class="flex flex-col gap-1">

            <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3">

                <img
                    src="https://www.figma.com/api/mcp/asset/50c971e5-aa3a-463e-a6c6-ab8e9a54c485.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Profile
                </span>

            </a>


            <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3">

                <img
                    src="https://www.figma.com/api/mcp/asset/d4ae2b3a-9ce3-4e6b-8575-f17e48d7cb50.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Pengaturan
                </span>

            </a>

        </nav>

        <div class="flex-1"></div>

    </aside>


    {{-- MAIN CONTENT --}}
    <main class="min-w-0 flex-1">

        {{-- HEADER --}}
        <header class="flex items-start justify-between px-[14px] pt-[17px]">

            <div class="flex flex-col gap-1">

                <h1 class="text-[24px] font-bold leading-normal">
                    Selamat datang, Ahmad Ramzi 👋
                </h1>

                <p class="text-[13px] text-[#64748b]">
                    Kelola pengajuan SPPD dan perizinan perjalanan dinas Anda dengan mudah.
                </p>

            </div>


            <div class="flex items-center gap-5">

                {{-- Notification --}}
                <button class="size-10">

                    <img
                        src="https://www.figma.com/api/mcp/asset/efcb384d-6213-418c-9d6e-b70e4a682260.svg"
                        class="size-10"
                        alt="Notifikasi"
                    >

                </button>


                {{-- Profile --}}
                <div class="relative">

                    <button
                        type="button"
                        onclick="toggleProfileMenu()"
                        class="flex items-center gap-[10px]"
                    >
                        <img
                            src="https://www.figma.com/api/mcp/asset/5b52a92c-29aa-452a-aed3-28b87263b453.png"
                            class="size-9 rounded-full object-cover"
                            alt="Ahmad Ramzi"
                        >

                        <div class="flex flex-col gap-px text-left">
                            <span class="text-[13px] font-semibold">
                                User
                            </span>

                            <span class="text-[11px] text-[#64748b]">
                                Manager
                            </span>
                        </div>
                    </button>

                    {{-- Dropdown --}}
                    <div
                        id="profileMenu"
                        class="absolute right-0 top-12 z-50 hidden w-40 rounded-xl border border-[#e2e8f0] bg-white p-2 shadow-lg"
                    >
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-lg px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50"
                            >
                                Logout
                            </button>
                        </form>
                    </div>

                </div>
                {{-- <div class="flex items-center gap-[10px]">

                    <img
                        src="https://www.figma.com/api/mcp/asset/5b52a92c-29aa-452a-aed3-28b87263b453.png"
                        class="size-9 rounded-full object-cover"
                        alt="Ahmad Ramzi"
                    >

                    <div class="flex flex-col gap-px">

                        <span class="text-[13px] font-semibold">
                            User
                        </span>

                        <span class="text-[11px] text-[#64748b]">
                            
                        </span>

                    </div>

                </div> --}}


                {{-- Create SPPD --}}
                <a
                    href="/sppd"
                    class="flex items-center gap-2 rounded-[10px] bg-[#0d6efd] px-4 py-[10px] text-[13px] font-semibold text-white"
                >

                    <img
                        src="https://www.figma.com/api/mcp/asset/9f45c1e7-bc7f-49b4-a782-585389cb17ef.svg"
                        class="size-[14px]"
                        alt=""
                    >

                    Buat Pengajuan SPPD

                </a>

            </div>

        </header>


        {{-- TABLE CARD --}}
        <section class="ml-[33px] mt-[25px] w-[calc(100%-66px)] rounded-2xl border border-[#f1f5f9] bg-white p-6 shadow-[0px_4px_6px_rgba(15,23,42,0.02)]">

            {{-- Card Header --}}
            <div class="mb-4 flex items-center justify-between">

                <h2 class="text-[16px] font-bold">
                    Pengajuan Terbaru
                </h2>

                <button class="text-[12px] font-semibold text-[#0d6efd]">
                    Lihat Semua
                </button>

            </div>


            {{-- Table --}}
            <div class="overflow-hidden rounded-xl">

                {{-- Header --}}
                <div class="grid grid-cols-[120px_100px_140px_140px_100px_40px] gap-3 bg-[#f4f6fb] px-4 py-3 text-[11px] font-semibold text-[#64748b]">

                    <span>No. SPPD</span>
                    <span>Tujuan</span>
                    <span>Tanggal Perjalanan</span>
                    <span>Status</span>
                    <span>Diajukan Pada</span>
                    <span class="text-center">Aksi</span>

                </div>


                {{-- Rows --}}
                @php
                    $pengajuan = [
                        [
                            'no' => 'SPPD-2026-00124',
                            'tujuan' => 'Bandung',
                            'tanggal' => '20 - 22 Aug 2026',
                            'status' => 'Menunggu Approval|Manager',
                            'type' => 'waiting',
                            'date' => '11 Aug 2026'
                        ],
                        [
                            'no' => 'SPPD-2026-00123',
                            'tujuan' => 'Yogyakarta',
                            'tanggal' => '15 - 17 Aug 2026',
                            'status' => 'Sedang Diproses|(GA)',
                            'type' => 'process',
                            'date' => '8 Aug 2026'
                        ],
                        [
                            'no' => 'SPPD-2026-00122',
                            'tujuan' => 'Surabaya',
                            'tanggal' => '05 - 06 Aug 2026',
                            'status' => 'Menunggu Approval|Manager',
                            'type' => 'waiting',
                            'date' => '5 Aug 2026'
                        ],
                        [
                            'no' => 'SPPD-2026-00121',
                            'tujuan' => 'Jakarta',
                            'tanggal' => '28 Jul 2026',
                            'status' => 'Selesai',
                            'type' => 'done',
                            'date' => '20 Jul 2026'
                        ],
                        [
                            'no' => 'SPPD-2026-00120',
                            'tujuan' => 'Semarang',
                            'tanggal' => '18 - 19 Jul 2026',
                            'status' => 'Selesai',
                            'type' => 'done',
                            'date' => '11 Jul 2026'
                        ],
                    ];
                @endphp


                @foreach ($pengajuan as $item)

                    <div class="grid grid-cols-[120px_100px_140px_140px_100px_40px] items-center gap-3 border-b border-[#f1f5f9] px-4 py-[14px] text-[12px]">

                        <span class="font-semibold">
                            {{ $item['no'] }}
                        </span>

                        <span>
                            {{ $item['tujuan'] }}
                        </span>

                        <span>
                            {{ $item['tanggal'] }}
                        </span>


                        <span>

                            @if ($item['type'] === 'waiting')

                                <span class="inline-flex rounded-lg bg-[#fff7ed] px-[10px] py-[6px] text-center text-[11px] font-semibold leading-[1.2] text-[#d97706]">
                                    {!! str_replace('|', '<br>', $item['status']) !!}
                                </span>

                            @elseif ($item['type'] === 'process')

                                <span class="inline-flex rounded-lg bg-[#eff6ff] px-[10px] py-[6px] text-center text-[11px] font-semibold leading-[1.2] text-[#2563eb]">
                                    {!! str_replace('|', '<br>', $item['status']) !!}
                                </span>

                            @else

                                <span class="inline-flex rounded-lg bg-[#f0fdf4] px-[10px] py-[6px] text-[11px] font-semibold text-[#16a34a]">
                                    {{ $item['status'] }}
                                </span>

                            @endif

                        </span>


                        <span class="text-[#64748b]">
                            {{ $item['date'] }}
                        </span>


                        <button class="flex items-center justify-center">

                            <img
                                src="https://www.figma.com/api/mcp/asset/fd18bc63-cfa1-4a86-b1ae-034d1ed7ef1c.svg"
                                class="size-4"
                                alt="Lihat"
                            >

                        </button>

                    </div>

                @endforeach

            </div>


            {{-- Bottom Button --}}
            <div class="flex justify-center pt-5">

                <button
                    class="flex items-center gap-2 rounded-full border border-[#0d6efd] px-5 py-[10px] text-[12px] font-semibold text-[#0d6efd]"
                >
                    Lihat Semua Pengajuan

                    <img
                        src="https://www.figma.com/api/mcp/asset/70c06faf-44b0-49c8-a647-a4a70e3af7f9.svg"
                        class="size-[14px]"
                        alt=""
                    >

                </button>

            </div>

        </section>

    </main>

</div>

<script>
    function toggleProfileMenu() {
        document.getElementById('profileMenu').classList.toggle('hidden');
    }
</script>

</body>
</html>