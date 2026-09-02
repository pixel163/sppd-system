@extends('layouts.app')

@section('title', 'Dokumen - SPPD System')

@section('content')

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

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen w-full">

        {{-- =====================================================
             CONTENT
        ====================================================== --}}
        <div class="w-full px-5 py-6 sm:px-7 lg:px-8">

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

                        <i data-lucide="info"class="mt-0.5 size-4 shrink-0"></i>

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

</body>
</html>

@endsection

@push('scripts')

    @vite('resources/js/dokumen.js')

@endpush