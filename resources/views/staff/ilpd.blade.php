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
</head>

<body class="min-h-screen bg-white font-['Inter',sans-serif] text-[#1e293b]">

<div class="flex min-h-screen">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="fixed left-0 top-0 z-10 flex h-screen w-[260px] flex-col gap-6 border-r border-[#e2e8f0] bg-white px-4 py-6">

        {{-- Brand --}}
        <div class="flex items-center gap-3 pl-3">

            <div class="flex size-9 items-center justify-center rounded-[10px] bg-[#0d6efd]">
                <img
                    src="https://www.figma.com/api/mcp/asset/216eebc6-e355-4a19-a2b5-034eb4e10ede.svg"
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

        </div>


        {{-- Navigation --}}
        <nav class="flex flex-col gap-1">

            <a
                href="/dashboard"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <img
                    src="https://www.figma.com/api/mcp/asset/1e7ecbf7-f07f-412d-9efd-2b111a2dc583.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dashboard
                </span>
            </a>


            <a
                href="/sppd"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <img
                    src="https://www.figma.com/api/mcp/asset/4a9386ae-5ec4-4de2-ac5b-969d19540618.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Pengajuan SPPD
                </span>
            </a>


            {{-- ACTIVE --}}
            <a
                href="/ilpd"
                class="flex items-center gap-3 rounded-xl bg-[#eff6ff] px-4 py-3"
            >
                <img
                    src="https://www.figma.com/api/mcp/asset/4aa75988-a098-40c9-822b-dceda736f498.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-semibold text-[#0d6efd]">
                    Perizinan
                </span>
            </a>


            <a
                href="/riwayat"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <img
                    src="https://www.figma.com/api/mcp/asset/15d3e751-c5a7-468e-964a-9079fa23b7a3.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Riwayat Pengajuan
                </span>
            </a>


            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <img
                    src="https://www.figma.com/api/mcp/asset/631c8d2b-c193-425e-b786-9d9b494ac944.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dokumen & Tiket
                </span>
            </a>

        </nav>


        <div class="h-px w-full bg-[#e2e8f0]"></div>


        <nav class="flex flex-col gap-1">

            <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3">

                <img
                    src="https://www.figma.com/api/mcp/asset/1be2f993-f852-4cf7-8e96-07cdc02558c9.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] text-[#64748b]">
                    Profile
                </span>

            </a>


            <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3">

                <img
                    src="https://www.figma.com/api/mcp/asset/5ed292fe-db24-46f7-98d7-c9f949384680.svg"
                    class="size-5"
                    alt=""
                >

                <span class="text-[14px] text-[#64748b]">
                    Pengaturan
                </span>

            </a>

        </nav>

        <div class="flex-1"></div>

    </aside>


    {{-- ================= MAIN ================= --}}
    <main class="ml-[260px] min-h-screen flex-1">


        {{-- HEADER --}}
        <header class="flex h-[66px] items-center justify-between border-b border-[#e2e8f0] px-10">

            <div class="flex items-center gap-3">

                <a href="/sppd/create" class="flex size-[18px] items-center justify-center">
                    <img
                        src="https://www.figma.com/api/mcp/asset/63154a8c-106c-49a5-8e89-a46a8bf592b4.svg"
                        class="size-[18px]"
                        alt="Kembali"
                    >
                </a>

                <span class="text-[14px] font-semibold">
                    Form ILPD
                </span>

            </div>


            <div class="flex items-center gap-5">

                {{-- Notification --}}
                <div class="relative flex size-10 items-center justify-center rounded-full border border-[#e2e8f0]">

                    <img
                        src="https://www.figma.com/api/mcp/asset/4db2edf4-5492-4b32-b6c2-1916fa6b0610.svg"
                        class="size-5"
                        alt="Notifikasi"
                    >

                    <span class="absolute -right-[3px] -top-[3px] flex size-[18px] items-center justify-center rounded-full bg-[#ef4444] text-[10px] font-bold text-white">
                        3
                    </span>

                </div>


                {{-- User --}}
                <div class="flex items-center gap-2.5">

                    <div class="flex size-9 items-center justify-center rounded-full bg-[#eff6ff] text-[14px] font-bold text-[#2563eb]">
                        AR
                    </div>

                    <div class="flex flex-col">

                        <span class="text-[14px] font-semibold">
                            Ahmad Ramzi
                        </span>

                        <span class="text-[12px] text-[#64748b]">
                            Staff IT
                        </span>

                    </div>

                    <img
                        src="https://www.figma.com/api/mcp/asset/b72ed057-41f3-4461-8600-6490f155edde.svg"
                        class="size-4"
                        alt=""
                    >

                </div>

            </div>

        </header>


        {{-- CONTENT --}}
        <div class="px-10 pb-10 pt-[15px]">


            {{-- TITLE --}}
            <div class="mb-5">

                <h1 class="text-[24px] font-extrabold uppercase">
                    FORM ILPD
                </h1>

                <p class="mt-1 text-[14px] text-[#64748b]">
                    Lengkapi data perizinan perjalanan dinas Anda.
                </p>

            </div>


            {{-- ================= SECTION 1 ================= --}}
            <section class="rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]">

                <div class="mb-5 flex items-center gap-3">

                    <div class="flex size-7 items-center justify-center rounded-full bg-[#2563eb] text-[14px] font-bold text-white">
                        1
                    </div>

                    <h2 class="text-[15px] font-bold uppercase">
                        Rencana Perjalanan Dinas
                    </h2>

                </div>


                {{-- Kota --}}
                <div class="mb-4">

                    <label class="mb-1.5 block text-[13px] font-semibold">
                        Kota Tujuan <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        placeholder="Masukan Kota Tujuan"
                        {{-- class="flex-1 rounded-lg border border-[#e2e8f0] px-4 py-3 text-[14px] outline-none placeholder:text-[#94a3b8] focus:border-[#2563eb]" --}}
                        class="w-full resize-none rounded-lg border border-[#cbd5e1] p-3.5 text-[14px] outline-none"
                    >

                </div>


                {{-- Tanggal --}}
                <div class="mb-4">

                    <label class="mb-1.5 block text-[13px] font-semibold">
                        Lama Perjalanan <span class="text-red-500">*</span>
                    </label>

                    <div class="flex items-center gap-4">

                        <div class="flex flex-1 items-center gap-2 rounded-lg border border-[#cbd5e1] px-3.5 py-2.5">

                            {{-- <img
                                src="https://www.figma.com/api/mcp/asset/7c94be07-968a-424a-8c6b-d4cdb5f9ec9a.svg"
                                class="size-4"
                                alt=""
                            > --}}

                            {{-- <input
                                type="date"
                                value="2026-08-12"
                                class="w-full border-0 text-[14px] outline-none"
                            > --}}
                            <input
                                type="date"
                                {{-- "Pilih Tanggal Awal" --}}
                                {{-- class="flex-1 rounded-lg border border-[#e2e8f0] px-4 py-3 text-[14px] outline-none placeholder:text-[#94a3b8] focus:border-[#2563eb]" --}}
                                class="w-full border-0 text-[14px] outline-none"
                            >

                        </div>

                        <span class="text-[14px] font-semibold text-[#64748b]">
                            s/d
                        </span>

                        <div class="flex flex-1 items-center gap-2 rounded-lg border border-[#cbd5e1] px-3.5 py-2.5">

                            {{-- <img
                                src="https://www.figma.com/api/mcp/asset/7c94be07-968a-424a-8c6b-d4cdb5f9ec9a.svg"
                                class="size-4"
                                alt=""
                            > --}}

                            <input
                                type="date"
                                {{-- value="2026-08-13" --}}
                                class="w-full border-0 text-[14px] outline-none"
                            >

                        </div>

                    </div>

                </div>


                {{-- Transportasi --}}
                <div class="mb-4">

                    <label class="mb-2 block text-[13px] font-semibold">
                        Transportasi <span class="text-red-500">*</span>
                    </label>

                    <div class="grid grid-cols-3 gap-y-3 gap-x-6">

                        @foreach ([
                            ['Pesawat', true],
                            ['Kereta Api', false],
                            ['Kapal Laut', false],
                            ['Kendaraan Dinas', false],
                            ['Kendaraan Pribadi', false],
                            ['Lainnya', false]
                        ] as $transport)

                            <label class="flex items-center gap-2">

                                <input
                                    type="checkbox"
                                    {{ $transport[1] ? 'checked' : '' }}
                                    class="size-[18px] rounded border-[#cbd5e1] text-[#2563eb] accent-[#2563eb]"
                                >

                                <span class="text-[14px]">
                                    {{ $transport[0] }}
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


                {{-- Keperluan --}}
                <div class="mb-4">

                    <label class="mb-2 block text-[13px] font-semibold">
                        Keperluan <span class="text-red-500">*</span>
                    </label>

                    <div class="grid grid-cols-3 gap-x-6 gap-y-3">

                        @foreach ([
                            ['Rapat / Meeting', true],
                            ['Pelatihan / Training', false],
                            ['Seminar / Workshop', false],
                            ['Kunjungan Kerja', false],
                            ['Negosiasi / Presentasi', false],
                            ['Pameran / Event', false]
                        ] as $necessity)

                            <label class="flex items-center gap-2">

                                <input
                                    type="checkbox"
                                    {{ $necessity[1] ? 'checked' : '' }}
                                    class="size-[18px] accent-[#2563eb]"
                                >

                                <span class="text-[14px]">
                                    {{ $necessity[0] }}
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


                {{-- Tugas --}}
                <div>

                    <label class="mb-1.5 block text-[13px] font-semibold">
                        Tugas <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        rows="4"
                        class="w-full resize-none rounded-lg border border-[#cbd5e1] p-3.5 text-[14px] outline-none"
                    >1.
2.
3. 
                    </textarea>

                    <p class="mt-1 text-[11px] text-[#64748b]">
                        Jelaskan tugas yang akan dilakukan selama perjalanan dinas (maks. 3 poin)
                    </p>

                </div>

            </section>


            {{-- ================= SECTION 2 ================= --}}
            <section class="mt-5 rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]">

                <div class="mb-5 flex items-center gap-3">

                    <div class="flex size-7 items-center justify-center rounded-full bg-[#2563eb] text-[14px] font-bold text-white">
                        2
                    </div>

                    <h2 class="text-[15px] font-bold uppercase">
                        Perkiraan Biaya
                    </h2>

                </div>


                {{-- Tiket --}}
                <div class="mb-4">

                    <label class="mb-1.5 block text-[13px] font-semibold">
                        Tiket <span class="text-red-500">*</span>
                    </label>

                    <div class="flex flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-[#2563eb] bg-[#fafbfe] p-6">

                        <img
                            src="https://www.figma.com/api/mcp/asset/c1573d2a-6a8c-478d-b90a-8436fdb80e30.svg"
                            class="size-8"
                            alt=""
                        >

                        <div class="text-center">

                            <p class="text-[14px] font-semibold">
                                Upload tiket perjalanan
                            </p>

                            <p class="mt-1 text-[11px] text-[#64748b]">
                                Format: PDF, JPG, JPEG, PNG (Maks. 5MB)
                            </p>

                        </div>

                        <label class="cursor-pointer rounded-md border border-[#2563eb] bg-white px-4 py-2 text-[13px] font-semibold text-[#2563eb]">

                            Pilih File

                            <input
                                type="file"
                                class="hidden"
                                accept=".pdf,.jpg,.jpeg,.png"
                            >

                        </label>

                    </div>

                </div>


                {{-- BBM --}}
                <div class="mb-5">

                    <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                        BBM (Opsional)
                    </label>

                    <div class="flex overflow-hidden rounded-lg border border-[#cbd5e1]">

                        <span class="flex items-center bg-[#f1f5f9] px-3 text-[14px] font-semibold text-[#64748b]">
                            Rp
                        </span>

                        <input
                            type="number"
                            placeholder="Masukkan jumlah biaya BBM"
                            class="flex-1 px-3 py-2.5 text-[14px] outline-none"
                        >

                    </div>

                </div>


                {{-- Uang Harian --}}
                <div class="mb-6">

                    <label class="mb-3 block text-[13px] font-semibold">
                        Uang Harian (sesuai golongan & destinasi)
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="grid grid-cols-3 gap-3">

                        @foreach ([
                            'Dinas (per hari)',
                            'Makan (per hari)',
                            'Hotel (per malam)'
                        ] as $label)

                            <div>

                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    {{ $label }}
                                </label>

                                <div class="flex overflow-hidden rounded-lg border border-[#cbd5e1]">

                                    <span class="flex items-center bg-[#f1f5f9] px-3 text-[14px] font-semibold text-[#64748b]">
                                        Rp
                                    </span>

                                    <input
                                        type="number"
                                        placeholder="Masukkan jumlah"
                                        class="min-w-0 flex-1 px-3 py-2.5 text-[14px] outline-none"
                                    >

                                </div>

                            </div>

                        @endforeach

                    </div>

                    <p class="mt-2 text-[11px] text-[#64748b]">
                        * Sesuaikan dengan golongan dan kota tujuan
                    </p>

                </div>


                {{-- Biaya Lainnya --}}
                <div>

                    <h3 class="mb-4 text-[13px] font-semibold">
                        Biaya Lainnya (Opsional)
                    </h3>

                    @foreach ([
                        'Transport Lokal',
                        'Visa',
                        'Fiskal',
                        'Tax Airport',
                        'Laundry',
                        'Parkir & Toll',
                        'Entertaiment'
                    ] as $expense)

                        <div class="mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                {{ $expense }}
                            </span>

                            <div class="flex w-[220px] overflow-hidden rounded-lg border border-[#cbd5e1]">

                                <span class="flex items-center bg-[#f1f5f9] px-3 text-[14px] font-semibold text-[#64748b]">
                                    Rp
                                </span>

                                <input
                                    type="number"
                                    placeholder="Masukkan jumlah"
                                    class="min-w-0 flex-1 px-3 py-2.5 text-[14px] outline-none"
                                >

                            </div>

                        </div>

                    @endforeach


                    {{-- DLL --}}
                    <div class="mb-3 flex items-center justify-between">

                        <input
                            type="text"
                            placeholder="Dll..."
                            class="w-[120px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[13px] outline-none"
                        >

                        <div class="flex w-[220px] overflow-hidden rounded-lg border border-[#cbd5e1]">

                            <span class="flex items-center bg-[#f1f5f9] px-3 text-[14px] font-semibold text-[#64748b]">
                                Rp
                            </span>

                            <input
                                type="number"
                                placeholder="Masukkan jumlah"
                                class="min-w-0 flex-1 px-3 py-2.5 text-[14px] outline-none"
                            >

                        </div>

                    </div>


                    {{-- Total --}}
                    <div class="mt-5 flex items-center justify-between">

                        <span class="text-[13px]">
                            Total
                        </span>

                        <div class="flex w-[220px] overflow-hidden rounded-lg border border-[#cbd5e1]">

                            <span class="flex items-center bg-[#f1f5f9] px-3 text-[14px] font-semibold text-[#64748b]">
                                Rp
                            </span>

                            <input
                                type="number"
                                placeholder="Masukkan jumlah"
                                class="min-w-0 flex-1 px-3 py-2.5 text-[14px] outline-none"
                            >

                        </div>

                    </div>


                    {{-- Uang Muka --}}
                    <div class="mt-3 flex items-center justify-between">

                        <span class="text-[13px]">
                            Uang Muka
                        </span>

                        <div class="flex w-[220px] overflow-hidden rounded-lg border border-[#cbd5e1]">

                            <span class="flex items-center bg-[#f1f5f9] px-3 text-[14px] font-semibold text-[#64748b]">
                                Rp
                            </span>

                            <input
                                type="number"
                                placeholder="Masukkan jumlah"
                                class="min-w-0 flex-1 px-3 py-2.5 text-[14px] outline-none"
                            >

                        </div>

                    </div>

                </div>

            </section>


            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 pb-6 pt-6">

                <a
                    href="/dashboard"
                    class="rounded-[10px] border border-[#94a3b8] bg-white px-6 py-3 text-[14px] font-semibold text-[#64748b]"
                >
                    Batal
                </a>


                <a
                    href="/dashboard"
                    class="flex items-center gap-2 rounded-[10px] bg-[#2563eb] px-6 py-3 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]"
                >

                    <img
                        src="https://www.figma.com/api/mcp/asset/e064646b-6c8d-4b77-b1d5-4a65c19a301f.svg"
                        class="size-4"
                        alt=""
                    >

                    Simpan Perizinan

                </a>

            </div>

        </div>

    </main>

</div>

</body>
</html>