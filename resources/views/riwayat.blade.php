@extends('layouts.app')

@section('title', 'Riwayat - SPPD System')

@section('content')

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

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen w-full">

        {{-- =====================================================
             CONTENT
        ====================================================== --}}
        <div class="w-full px-5 py-6 sm:px-7 lg:px-8">

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
                    <div>
                        @foreach ($pengajuanStaff as $sppd)
                            
                            <div class="grid grid-cols-[130px_110px_150px_160px_110px_110px] items-center gap-3 border-b border-[#f1f5f9] px-4 py-[14px] text-[12px] hover:bg-[#fafcff]">

                                {{-- No. SPPD --}}
                                <span class="font-semibold">
                                    {{ $sppd->dinas->no_dinas ?? '-' }}
                                </span>

                                {{-- Tujuan --}}
                                <span>
                                    {{ $sppd->kota->name ?? '-' }}
                                </span>

                                {{-- Tanggal Perjalanan --}}
                                <span>
                                    {{ $sppd->ilpd ? ($sppd->ilpd->tanggal_awal)->format('d') . ' - ' . ($sppd->ilpd->tanggal_akhir)->format('d M Y') : '-' }}
                                </span>

                                {{-- Status --}}
                                <span>
                                    {{ $sppd->status ?? '-' }}
                                </span>

                                {{-- Diajukan Pada --}}
                                <span class="text-[#64748b]">
                                    {{ $sppd->created_at?->format('d F Y') ?? '-' }}
                                </span>

                                {{-- Aksi --}}
                                <div class="flex items-center justify-center gap-1">
                                    <button
                                        type="button"
                                        onclick="openDetail('{{ $sppd->no_sppd }}')"
                                        class="flex size-8 items-center justify-center rounded-lg hover:bg-[#eff6ff]"
                                        title="Lihat"
                                    >
                                        <span class="text-[#64748b]">👁</span>
                                    </button>

                                    <button
                                        type="button"
                                        onclick="printItem('{{ $sppd->no_sppd }}')"
                                        class="flex size-8 items-center justify-center rounded-lg hover:bg-[#eff6ff]"
                                        title="Cetak"
                                    >
                                        <span class="text-[#0d6efd]">🖨</span>
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    {{-- <div id="desktopHistoryList"></div> --}}

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

</body>
</html>

@endsection

@push('scripts')

    {{-- @vite('resources/js/riwayat.js') --}}

@endpush