<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form SPPD - SPPD System</title>

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

    {{-- SIDEBAR --}}
    <aside class="fixed left-0 top-0 flex h-screen w-[260px] flex-col gap-6 border-r border-[#e2e8f0] bg-white px-4 py-6">

        {{-- BRAND --}}
        <div class="flex items-center gap-3 pl-3">

            <img
                src="https://www.figma.com/api/mcp/asset/8bd1cbf8-b9f2-4858-be24-cc80f194b69c.svg"
                class="size-9"
                alt="SPPD System"
            >

            <div class="flex flex-col gap-px">

                <span class="text-[16px] font-bold">
                    SPPD System
                </span>

                <span class="text-[11px] text-[#64748b]">
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
                <span class="text-[14px] font-medium text-[#64748b]">
                    ▦
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dashboard
                </span>
            </a>


            {{-- ACTIVE --}}
            <a
                href="/sppd/create"
                class="flex items-center gap-3 rounded-xl bg-[#eff6ff] px-4 py-3"
            >
                <span class="text-[14px] text-[#2563eb]">
                    ▤
                </span>

                <span class="text-[14px] font-semibold text-[#2563eb]">
                    Pengajuan SPPD
                </span>
            </a>


            <a
                href="/ilpd"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ☑
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
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
                href="#"
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


        {{-- MENU BAWAH --}}
        <nav class="flex flex-col gap-1">

            <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3">
                <span class="text-[14px] text-[#64748b]">○</span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Profile
                </span>
            </a>


            <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3">
                <span class="text-[14px] text-[#64748b]">⚙</span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Pengaturan
                </span>
            </a>

        </nav>

    </aside>


    {{-- MAIN --}}
    <main class="ml-[260px] min-h-screen flex-1">

        {{-- TOP NAVBAR --}}
        <header class="flex h-[72px] items-center justify-end border-b border-[#e2e8f0] bg-white px-8">

            <div class="flex items-center gap-5">

                <button class="flex size-10 items-center justify-center">
                    <img
                        src="https://www.figma.com/api/mcp/asset/370f643e-a731-4ab9-8d2b-77abb07c5015.svg"
                        class="size-10"
                        alt="Notifikasi"
                    >
                </button>


                <div class="flex items-center gap-2.5">

                    <div class="flex size-9 items-center justify-center rounded-full border border-[#2563eb] bg-[#eff6ff]">

                        <span class="text-[13px] font-bold text-[#2563eb]">
                            AR
                        </span>

                    </div>


                    <div class="flex flex-col">

                        <span class="text-[14px] font-semibold">
                            Ahmad Ramzi
                        </span>

                        <span class="text-[11px] text-[#64748b]">
                            Staff IT
                        </span>

                    </div>


                    <img
                        src="https://www.figma.com/api/mcp/asset/42c87f30-22b9-4510-bf27-31c65cd5e891.svg"
                        class="size-3.5"
                        alt=""
                    >

                </div>

            </div>

        </header>


        {{-- CONTENT --}}
        <div class="px-10 py-8">

            {{-- TITLE --}}
            <div class="mb-6">

                <h1 class="text-[22px] font-extrabold tracking-[-0.5px]">
                    FORM SPPD
                </h1>

                <p class="mt-1 text-[14px] text-[#64748b]">
                    Silakan lengkapi data di bawah ini dengan benar.
                </p>

            </div>


            {{-- FORM CARD --}}
            <div class="rounded-2xl border border-[#f1f5f9] bg-white p-8 shadow-[0px_4px_6px_rgba(15,23,42,0.02)]">

                <form class="space-y-0">


                    {{-- Nama --}}
                    <div class="flex gap-6 py-3">

                        <label class="w-[180px] pt-2.5 text-[14px] font-bold">
                            Nama
                        </label>

                        <input
                            type="text"
                            placeholder="Masukan Nama"
                            class="flex-1 rounded-lg border border-[#e2e8f0] px-4 py-3 text-[14px] outline-none placeholder:text-[#94a3b8] focus:border-[#2563eb]"
                        >

                    </div>


                    {{-- NIK --}}
                    <div class="flex gap-6 py-3">

                        <label class="w-[180px] pt-2.5 text-[14px] font-bold">
                            NIK
                        </label>

                        <input
                            type="text"
                            placeholder="Masukkan NIK"
                            class="flex-1 rounded-lg border border-[#e2e8f0] px-4 py-3 text-[14px] outline-none placeholder:text-[#94a3b8] focus:border-[#2563eb]"
                        >

                    </div>


                    {{-- Jabatan --}}
                    <div class="flex gap-6 py-3">

                        <label class="w-[180px] pt-2.5 text-[14px] font-bold">
                            Jabatan
                        </label>

                        <select
                            class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-3 text-[14px] text-[#94a3b8] outline-none focus:border-[#2563eb]"
                        >
                            <option value="">Pilih jabatan</option>
                            <option>Staff</option>
                            <option>Supervisor</option>
                            <option>Manager</option>
                            <option>Director</option>
                        </select>

                    </div>


                    {{-- Departement --}}
                    <div class="flex gap-6 py-3">

                        <label class="w-[180px] pt-2.5 text-[14px] font-bold">
                            Departement
                        </label>

                        <select
                            class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-3 text-[14px] text-[#94a3b8] outline-none focus:border-[#2563eb]"
                        >
                            <option value="">Pilih departement</option>
                            <option>IT</option>
                            <option>Finance</option>
                            <option>Human Resource</option>
                            <option>General Affair</option>
                        </select>

                    </div>


                    {{-- Kota --}}
                    <div class="flex gap-6 py-3">

                        <label class="w-[180px] pt-2.5 text-[14px] font-bold">
                            Kota
                        </label>

                        <input
                            type="text"
                            placeholder="Masukkan kota tujuan"
                            class="flex-1 rounded-lg border border-[#e2e8f0] px-4 py-3 text-[14px] outline-none placeholder:text-[#94a3b8] focus:border-[#2563eb]"
                        >

                    </div>


                    {{-- Waktu --}}
                    <div class="flex gap-6 py-3">

                        <label class="w-[180px] pt-2.5 text-[14px] font-bold">
                            Waktu
                        </label>

                        <input
                            type="text"
                            placeholder="Masukkan waktu (per hari)"
                            class="flex-1 rounded-lg border border-[#e2e8f0] px-4 py-3 text-[14px] outline-none placeholder:text-[#94a3b8] focus:border-[#2563eb]"
                        >

                    </div>


                    {{-- Keperluan --}}
                    <div class="flex gap-6 py-3">

                        <label class="w-[180px] pt-2.5 text-[14px] font-bold">
                            Keperluan Dinas
                        </label>

                        <div class="flex h-[140px] flex-1 flex-col rounded-lg border border-[#e2e8f0] px-4 py-3">

                            <span class="text-[14px] text-[#94a3b8]">
                                Jelaskan keperluan dinas Anda (maks. 3 poin)
                            </span>

                            <textarea
                                class="mt-1 flex-1 resize-none border-0 text-[14px] outline-none"
                                placeholder="1.&#10;2.&#10;3."
                            ></textarea>

                        </div>

                    </div>


                    {{-- Transportasi --}}
                    <div class="flex gap-6 py-3">

                        <label class="w-[180px] pt-2.5 text-[14px] font-bold">
                            Transportasi
                        </label>

                        <input
                            type="text"
                            placeholder="Masukkan jenis transportasi yang digunakan"
                            class="flex-1 rounded-lg border border-[#e2e8f0] px-4 py-3 text-[14px] outline-none placeholder:text-[#94a3b8] focus:border-[#2563eb]"
                        >

                    </div>


                    {{-- Tugas --}}
                    <div class="flex gap-6 py-3">

                        <label class="w-[180px] pt-2.5 text-[14px] font-bold">
                            Tugas
                        </label>

                        <div class="flex h-[160px] flex-1 flex-col rounded-lg border border-[#e2e8f0] px-4 py-3">

                            <span class="text-[14px] text-[#94a3b8]">
                                Jelaskan tugas yang akan dilakukan selama perjalanan dinas (maks. 4 poin)
                            </span>

                            <textarea
                                class="mt-1 flex-1 resize-none border-0 text-[14px] outline-none"
                                placeholder="1.&#10;2.&#10;3.&#10;4."
                            ></textarea>

                        </div>

                    </div>


                    {{-- BUTTON --}}
                    <div class="flex justify-end gap-3 pt-6">

                        <a
                            href="/dashboard"
                            class="rounded-lg border border-[#e2e8f0] bg-white px-5 py-2.5 text-[14px] font-semibold text-[#64748b]"
                        >
                            Batal
                        </a>


                        <a
                            href="/dashboard"
                            class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]"
                        >
                            Ajukan
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>

</body>
</html>