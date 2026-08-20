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

    {{-- SIDEBAR --}}
    <aside class="fixed left-0 top-0 flex h-screen w-[260px] flex-col gap-6 border-r border-[#e2e8f0] bg-white px-4 py-6">

        {{-- BRAND --}}
        <div class="flex items-center gap-3 pl-3">

            <div class="flex size-9 items-center justify-center rounded-[10px] bg-[#0d6efd]">

                <img
                    src="https://www.figma.com/api/mcp/asset/77d932f5-7c87-4f13-b6c2-41e986d31ef9.svg"
                    class="size-5"
                    alt=""
                >

            </div>

            <div class="flex flex-col gap-[2px]">

                <span class="text-[16px] font-bold">
                    SPPD System
                </span>

                <span class="text-[11px] text-[#94a3b8]">
                    Sistem Perjalanan Dinas
                </span>

            </div>

        </div>


        {{-- MENU --}}
        <nav class="flex flex-col gap-1">

            <a
                href="/dashboard"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px]">
                    ▦
                </span>

                <span class="text-[14px] font-medium">
                    Dashboard
                </span>
            </a>


            <a
                href="/sppd"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px]">
                    ↗
                </span>

                <span class="text-[14px] font-medium">
                    Pengajuan SPPD
                </span>
            </a>


            <a
                href="/ilpd"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px]">
                    ✓
                </span>

                <span class="text-[14px] font-medium">
                    Perizinan
                </span>
            </a>


            <a
                href="/riwayat"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px]">
                    ◷
                </span>

                <span class="text-[14px] font-medium">
                    Riwayat Pengajuan
                </span>
            </a>


            {{-- ACTIVE --}}
            <a
                href="/dokumen"
                class="flex items-center gap-3 rounded-xl bg-[#eff6ff] px-4 py-3"
            >
                <span class="text-[14px] text-[#0d6efd]">
                    ▣
                </span>

                <span class="text-[14px] font-semibold text-[#0d6efd]">
                    Dokumen & Tiket
                </span>
            </a>

        </nav>


        <div class="h-px w-full bg-[#e2e8f0]"></div>


        {{-- MENU BAWAH --}}
        {{-- <nav class="flex flex-col gap-1">

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px]">
                    ♙
                </span>

                <span class="text-[14px] font-medium">
                    Profile
                </span>
            </a>


            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px]">
                    ⚙
                </span>

                <span class="text-[14px] font-medium">
                    Pengaturan
                </span>
            </a>

        </nav> --}}

        <div class="flex-1"></div>

    </aside>


    {{-- MAIN --}}
    <main class="ml-[260px] min-h-screen flex-1">


        {{-- NAVBAR --}}
        <header class="flex h-[72px] items-center justify-end border-b border-[#e2e8f0] px-8">

            <div class="flex items-center gap-5">

                {{-- Notification --}}
                <button class="relative flex size-10 items-center justify-center">

                    <img
                        src="https://www.figma.com/api/mcp/asset/08044250-4987-42bb-992d-da2c80f8be9b.svg"
                        class="size-10"
                        alt="Notifikasi"
                    >

                </button>


                {{-- Profile --}}
                <div class="flex items-center gap-[10px]">

                    <div class="flex size-9 items-center justify-center rounded-[18px] border border-[#2563eb] bg-[#eff6ff]">

                        <span class="text-[13px] font-bold text-[#2563eb]">
                            AR
                        </span>

                    </div>


                    <div class="flex flex-col gap-px">

                        <span class="text-[14px] font-semibold">
                            Ahmad Ramzi
                        </span>

                        <span class="text-[11px] text-[#64748b]">
                            Staff IT
                        </span>

                    </div>


                    <img
                        src="https://www.figma.com/api/mcp/asset/9a56ddde-be73-4c09-9ace-93aa9720bb79.svg"
                        class="size-3.5"
                        alt=""
                    >

                </div>

            </div>

        </header>


        {{-- CONTENT --}}
        <div class="px-10 pb-8 pt-8">


            {{-- TITLE --}}
            <div class="mb-6">

                <h1 class="text-[22px] font-bold">
                    Dokumen & Tiket
                </h1>

                <p class="mt-1 text-[13px] text-[#64748b]">
                    Kelola dan unduh dokumen serta tiket perjalanan dinas Anda.
                </p>

            </div>


            {{-- BODY --}}
            <div class="flex items-start gap-4">


                {{-- =========================
                     LEFT : LIST SPPD
                ========================== --}}
                <section class="w-[380px] shrink-0 rounded-2xl border border-[#f1f5f9] bg-white p-5 shadow-[0px_4px_6px_rgba(15,23,42,0.02)]">

                    <h2 class="mb-4 text-[15px] font-bold">
                        Daftar Pengajuan SPPD
                    </h2>


                    {{-- SEARCH --}}
                    <div class="mb-4 flex gap-2">

                        <div class="flex flex-1 items-center gap-2 rounded-lg bg-[#f4f6fb] px-3 py-2">

                            <img
                                src="https://www.figma.com/api/mcp/asset/0cd49deb-7a29-451a-96f7-d17e79d9e9b0.svg"
                                class="size-3.5"
                                alt=""
                            >

                            <input
                                type="text"
                                placeholder="Cari nomor SPPD atau tujuan..."
                                class="min-w-0 flex-1 bg-transparent text-[12px] outline-none placeholder:text-[#94a3b8]"
                            >

                        </div>


                        <button class="flex items-center gap-1.5 rounded-lg border border-[#e2e8f0] px-3 py-2 text-[12px] font-semibold text-[#64748b]">

                            <img
                                src="https://www.figma.com/api/mcp/asset/dc59cce2-e000-4bb3-9a63-a3806daf3700.svg"
                                class="size-3.5"
                                alt=""
                            >

                            Filter

                        </button>

                    </div>


                    {{-- LIST --}}
                    @php

                        $pengajuan = [

                            [
                                'no' => 'SPPD-2026-00124',
                                'tujuan' => 'Bandung',
                                'tanggal' => '20 - 22 Agustus 2026',
                                'status' => 'Selesai',
                                'warna' => 'green',
                                'active' => true
                            ],

                            [
                                'no' => 'SPPD-2026-00115',
                                'tujuan' => 'Jakarta',
                                'tanggal' => '10 - 12 Agustus 2026',
                                'status' => 'Selesai',
                                'warna' => 'green',
                                'active' => false
                            ],

                            [
                                'no' => 'SPPD-2026-00102',
                                'tujuan' => 'Surabaya',
                                'tanggal' => '28 - 30 Juli 2026',
                                'status' => 'Selesai',
                                'warna' => 'green',
                                'active' => false
                            ],

                            [
                                'no' => 'SPPD-2026-00087',
                                'tujuan' => 'Yogyakarta',
                                'tanggal' => '15 - 17 Juli 2026',
                                'status' => 'Diproses',
                                'warna' => 'orange',
                                'active' => false
                            ],

                            [
                                'no' => 'SPPD-2026-00065',
                                'tujuan' => 'Semarang',
                                'tanggal' => '1 - 3 Juli 2026',
                                'status' => 'Dibatalkan',
                                'warna' => 'gray',
                                'active' => false
                            ],

                        ];

                    @endphp


                    <div>

                        @foreach ($pengajuan as $item)

                            <div
                                class="
                                    flex flex-col gap-2 p-4
                                    {{ $item['active']
                                        ? 'rounded-xl border border-[#0d6efd] bg-[#eff6ff]'
                                        : 'border-b border-[#f1f5f9]' }}
                                "
                            >

                                <div class="flex items-center justify-between">

                                    <span class="text-[14px] font-bold">
                                        {{ $item['no'] }}
                                    </span>


                                    @if ($item['warna'] === 'green')

                                        <span class="rounded-md bg-[#e8f5e9] px-2 py-1 text-[11px] font-semibold text-[#2e7d32]">
                                            {{ $item['status'] }}
                                        </span>

                                    @elseif ($item['warna'] === 'orange')

                                        <span class="rounded-md bg-[#fff3e0] px-2 py-1 text-[11px] font-semibold text-[#ef6c00]">
                                            {{ $item['status'] }}
                                        </span>

                                    @else

                                        <span class="rounded-md bg-[#f5f5f5] px-2 py-1 text-[11px] font-semibold text-[#616161]">
                                            {{ $item['status'] }}
                                        </span>

                                    @endif

                                </div>


                                <div class="flex flex-col gap-1">

                                    <span class="text-[13px] text-[#64748b]">
                                        {{ $item['tujuan'] }}
                                    </span>

                                    <span class="text-[12px] text-[#94a3b8]">
                                        {{ $item['tanggal'] }}
                                    </span>

                                </div>

                            </div>

                        @endforeach

                    </div>


                    {{-- PAGINATION --}}
                    <div class="flex items-center justify-center gap-2 pt-4">

                        <button class="size-5">
                            <img
                                src="https://www.figma.com/api/mcp/asset/ec799be6-b435-4fdb-9346-b947d7f93534.svg"
                                class="size-3.5"
                                alt="Previous"
                            >
                        </button>


                        <button class="flex size-8 items-center justify-center rounded-md border border-[#0d6efd] bg-[#eff6ff] text-[13px] font-semibold text-[#0d6efd]">
                            1
                        </button>


                        <button class="size-8 text-[13px] text-[#64748b]">
                            2
                        </button>

                        <button class="size-8 text-[13px] text-[#64748b]">
                            3
                        </button>


                        <button class="size-5">
                            <img
                                src="https://www.figma.com/api/mcp/asset/d4ec2afb-7854-4b09-bd8d-bd9d26af226f.svg"
                                class="size-3.5"
                                alt="Next"
                            >
                        </button>

                    </div>

                </section>


                {{-- =========================
                     RIGHT
                ========================== --}}
                <div class="min-w-0 flex-1 space-y-4">


                    {{-- DETAIL HEADER --}}
                    <section class="rounded-2xl border border-[#f1f5f9] bg-white p-6 shadow-[0px_4px_6px_rgba(15,23,42,0.02)]">

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-2">

                                <h2 class="text-[18px] font-bold">
                                    SPPD-2026-00124
                                </h2>

                                <span class="rounded-md bg-[#e8f5e9] px-2 py-1 text-[11px] font-semibold text-[#2e7d32]">
                                    Selesai
                                </span>

                            </div>


                            <button class="px-3 py-1.5 text-[12px] font-semibold text-[#0d6efd]">
                                Lihat Detail Pengajuan
                            </button>

                        </div>


                        <div class="mt-5 flex justify-between">

                            <div class="flex flex-col gap-3">

                                <div class="flex items-center gap-2">

                                    <img
                                        src="https://www.figma.com/api/mcp/asset/89ed67bc-0b02-43bf-abb5-544a0f3a4cb9.svg"
                                        class="size-4"
                                        alt=""
                                    >

                                    <span class="text-[13px] font-medium text-[#64748b]">
                                        Bandung
                                    </span>

                                </div>


                                <div class="flex items-center gap-2">

                                    <img
                                        src="https://www.figma.com/api/mcp/asset/1255b000-8be1-4f94-87b2-e2a289650efc.svg"
                                        class="size-4"
                                        alt=""
                                    >

                                    <span class="text-[13px] font-medium text-[#64748b]">
                                        20 - 22 Agustus 2026
                                    </span>

                                </div>

                            </div>


                            <div class="flex flex-col items-end gap-3 text-[12px]">

                                <div class="flex gap-3">

                                    <span class="text-[#94a3b8]">
                                        Diajukan oleh:
                                    </span>

                                    <span class="font-semibold">
                                        Ahmad Ramzi
                                    </span>

                                </div>


                                <div class="flex gap-3">

                                    <span class="text-[#94a3b8]">
                                        Tanggal Pengajuan:
                                    </span>

                                    <span class="font-semibold">
                                        5 Agustus 2026, 09:00 WIB
                                    </span>

                                </div>

                            </div>

                        </div>

                    </section>


                    {{-- DOKUMEN --}}
                    <section class="rounded-2xl border border-[#f1f5f9] bg-white p-6 shadow-[0px_4px_6px_rgba(15,23,42,0.02)]">

                        <h2 class="mb-4 text-[15px] font-bold">
                            Dokumen & Tiket
                        </h2>


                        <div class="flex flex-col gap-3">


                            {{-- FORM SPPD --}}
                            <div class="flex items-center gap-4 rounded-xl border border-[#f1f5f9] p-4">

                                <div class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-[#eff6ff]">

                                    <img
                                        src="https://www.figma.com/api/mcp/asset/143c99a5-aedd-4c4d-b404-9d50d67d15b9.svg"
                                        class="size-5"
                                        alt=""
                                    >

                                </div>


                                <div class="min-w-0 flex-1">

                                    <h3 class="text-[15px] font-semibold">
                                        1. Form SPPD
                                    </h3>

                                    <p class="mt-1 text-[12px] text-[#64748b]">
                                        Formulir Surat Perjalanan Dinas yang telah diisi oleh Anda.
                                    </p>

                                    <p class="mt-1 text-[11px] text-[#94a3b8]">
                                        Diajukan: 5 Agustus 2026, 09:00 WIB
                                    </p>

                                </div>


                                <div class="flex gap-2">

                                    <button class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b]">

                                        <img
                                            src="https://www.figma.com/api/mcp/asset/9dcade87-3ecb-4c17-93a2-ac060800d896.svg"
                                            class="size-3.5"
                                            alt=""
                                        >

                                        Lihat

                                    </button>


                                    <button class="flex items-center gap-1.5 rounded-lg border border-[#0d6efd] px-4 py-2 text-[13px] font-medium text-[#0d6efd]">

                                        <img
                                            src="https://www.figma.com/api/mcp/asset/f40d7cb4-9b4d-4f33-9165-29f7fc85eb9b.svg"
                                            class="size-3.5"
                                            alt=""
                                        >

                                        Cetak

                                    </button>

                                </div>

                            </div>


                            {{-- FORM PERIZINAN --}}
                            <div class="flex items-center gap-4 rounded-xl border border-[#f1f5f9] p-4">

                                <div class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-[#e8f5e9]">

                                    <img
                                        src="https://www.figma.com/api/mcp/asset/3f490637-7bde-4927-9e4a-e2cd74c93afb.svg"
                                        class="size-5"
                                        alt=""
                                    >

                                </div>


                                <div class="min-w-0 flex-1">

                                    <h3 class="text-[15px] font-semibold">
                                        2. Form Perizinan (Disetujui)
                                    </h3>

                                    <p class="mt-1 text-[12px] text-[#64748b]">
                                        Formulir perizinan yang telah diperiksa dan disetujui oleh GA.
                                    </p>

                                    <p class="mt-1 text-[11px] text-[#94a3b8]">
                                        Disetujui: 6 Agustus 2026, 14:20 WIB
                                    </p>

                                </div>


                                <div class="flex gap-2">

                                    <button class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b]">

                                        <img
                                            src="https://www.figma.com/api/mcp/asset/9dcade87-3ecb-4c17-93a2-ac060800d896.svg"
                                            class="size-3.5"
                                            alt=""
                                        >

                                        Lihat

                                    </button>


                                    <button class="flex items-center gap-1.5 rounded-lg border border-[#2e7d32] px-4 py-2 text-[13px] font-medium text-[#2e7d32]">

                                        <img
                                            src="https://www.figma.com/api/mcp/asset/284e9399-feb2-4375-951a-219b949d5258.svg"
                                            class="size-3.5"
                                            alt=""
                                        >

                                        Cetak

                                    </button>

                                </div>

                            </div>


                            {{-- TIKET --}}
                            <div class="flex items-center gap-4 rounded-xl border border-[#f1f5f9] p-4">

                                <div class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-[#f3e5f5]">

                                    <img
                                        src="https://www.figma.com/api/mcp/asset/be35c302-ec64-406f-b058-d8fb4b152096.svg"
                                        class="size-5"
                                        alt=""
                                    >

                                </div>


                                <div class="min-w-0 flex-1">

                                    <h3 class="text-[15px] font-semibold">
                                        3. Tiket Perjalanan
                                    </h3>

                                    <p class="mt-1 text-[12px] text-[#64748b]">
                                        E-ticket perjalanan dinas yang telah disiapkan oleh GA.
                                    </p>

                                    <p class="mt-1 text-[11px] text-[#94a3b8]">
                                        Diterbitkan: 6 Agustus 2026, 15:10 WIB
                                    </p>

                                </div>


                                <div class="flex gap-2">

                                    <button class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b]">

                                        <img
                                            src="https://www.figma.com/api/mcp/asset/9dcade87-3ecb-4c17-93a2-ac060800d896.svg"
                                            class="size-3.5"
                                            alt=""
                                        >

                                        Lihat

                                    </button>


                                    <button class="flex items-center gap-1.5 rounded-lg border border-[#8e24aa] px-4 py-2 text-[13px] font-medium text-[#8e24aa]">

                                        <img
                                            src="https://www.figma.com/api/mcp/asset/5d1b58f3-0a45-47f0-9c5b-bcfba5eb3bf0.svg"
                                            class="size-3.5"
                                            alt=""
                                        >

                                        Cetak

                                    </button>

                                </div>

                            </div>

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

                            <p>
                                Pastikan dokumen fisik dicetak sebelum berangkat untuk keperluan pelaporan dinas.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>