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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-white font-['Inter',sans-serif] text-[#1e293b]">

<div class="flex min-h-screen">

    {{-- =========================================================
         MOBILE OVERLAY
    ========================================================== --}}
    <div
        id="sidebarOverlay"
        class="fixed inset-0 z-40 hidden bg-black/30 lg:hidden"
        onclick="toggleSidebar()">
    </div>

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
                            class="text-[16px] font-bold">
                            Riwayat Pengajuan Saya
                        </h2>

                        <p
                            id="historyDescription"
                            class="mt-1 text-[12px] text-[#64748b]">
                            Menampilkan seluruh pengajuan SPPD yang Anda miliki.
                        </p>

                    </div>

                    {{-- SEARCH + FILTER --}}
                    <form id="filterForm" method="GET" action="{{ route('riwayat') }}" class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">

                        {{-- SEARCH --}}
                        <div class="flex min-w-0 flex-1 items-center gap-2 rounded-lg bg-[#f4f6fb] px-3 py-2 sm:w-[260px] sm:flex-none">

                            <span class="text-sm text-[#94a3b8]">
                                ⌕
                            </span>

                            <input
                                {{-- id="searchInput" --}}
                                name="search"
                                type="text"
                                value="{{ request('search') }}"
                                placeholder="Cari No. Dinas atau tujuan..."
                                {{-- oninput="renderHistory()" --}}
                                class="min-w-0 flex-1 bg-transparent text-[12px] outline-none placeholder:text-[#94a3b8]">

                        </div>

                        {{-- FILTER --}}
                        <select
                            name="status"
                            id="statusFilter"
                            {{-- onchange="document.getElementById('filterForm').submit()" --}}
                            onchange="this.form.submit()"
                            class="rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 text-[12px] font-semibold text-[#64748b] outline-none focus:border-[#0d6efd]">

                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>
                                Semua Status
                            </option>

                            <option value="Menunggu Approval" {{ request('status') == 'Menunggu Approval' ? 'selected' : '' }}>
                                Menunggu Approval
                            </option>

                            <option value="Sedang Diproses" {{ request('status') == 'Sedang Diproses' ? 'selected' : '' }}>
                                Sedang Diproses
                            </option>

                            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>
                                Disetujui
                            </option>

                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>

                        </select>
                        {{-- <select
                            id="statusFilter"
                            onchange="renderHistory()"
                            class="rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 text-[12px] font-semibold text-[#64748b] outline-none focus:border-[#0d6efd]">

                            <option value="all">
                                Semua Status
                            </option>

                            <option value="manager">
                                Menunggu Approval
                            </option>

                            <option value="ga">
                                Sedang Diproses
                            </option>

                            <option value="rejected">
                                Disetujui
                            </option>

                            <option value="done">
                                Selesai
                            </option>

                        </select> --}}

                    </form>

                </div>

                {{-- =================================================
                     DESKTOP TABLE
                ================================================== --}}
                <div class="hidden overflow-hidden rounded-xl border border-[#f1f5f9] lg:block">

                    {{-- HEADER --}}
                    <div class="flex items-center gap-4 bg-slate-100 px-4 py-3 text-xs font-semibold text-slate-500">
                        <div class="w-30 shrink-0">No. SPPD</div>
                        <div class="w-28 shrink-0">Tujuan</div>
                        <div class="w-36 shrink-0">Tanggal</div>
                        <div class="w-25 shrink-0">Status</div>
                        <div class="w-30 shrink-0">Diajukan Pada</div>
                        <div class="w-18 shrink-0">SLA</div>
                        <div class="w-20 shrink-0 text-right">Aksi</div>
                    </div>
                    {{-- <div class="grid grid-cols-[130px_110px_130px_130px_110px_80px_110px] gap-3 bg-[#f4f6fb] px-4 py-3 text-[11px] font-semibold text-[#64748b]">

                        <span>No. Dinas</span>

                        <span>Tujuan</span>

                        <span>Tanggal Perjalanan</span>

                        <span>Status</span>

                        <span>Diajukan Pada</span>

                        <span>SLA</span>

                        <span class="text-center">
                            Aksi
                        </span>

                    </div> --}}

                    {{-- DATA --}}
                    <div class="divide-y divide-slate-100">
                        @foreach ($daftarDinas as $dinas)
                            @php
                                $sla = $dinas->sla_info;
                                
                                // Dynamic styling untuk badge status utama
                                $statusClass = match(strtolower($dinas->status ?? '')) {
                                    'approved', 'disetujui' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                    'rejected', 'ditolak'   => 'bg-rose-50 text-rose-600 border-rose-200',
                                    default                 => 'bg-amber-50 text-amber-600 border-amber-200',
                                };
                            @endphp

                            <div class="grid grid-cols-[130px_110px_140px_130px_110px_110px_80px] items-center gap-2 px-4 py-3 text-xs hover:bg-slate-50/80 transition-colors">

                                {{-- 1. No. SPPD --}}
                                <span class="font-semibold text-slate-800 truncate" title="{{ $dinas->no_dinas }}">
                                    {{ $dinas->no_dinas ?? '-' }}
                                </span>

                                {{-- 2. Tujuan --}}
                                <span class="text-slate-600 truncate" title="{{ $dinas->sppd->kota->name ?? '-' }}">
                                    {{ $dinas->sppd->kota->name ?? '-' }}
                                </span>

                                {{-- 3. Tanggal Perjalanan --}}
                                <span class="text-slate-600 font-medium">
                                    @if($dinas->ilpd?->tanggal_awal && $dinas->ilpd?->tanggal_akhir)
                                        {{ $dinas->ilpd->tanggal_awal->format('d') }} - {{ $dinas->ilpd->tanggal_akhir->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </span>

                                {{-- 4. Status Utama --}}
                                <div>
                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize {{ $statusClass }}">
                                        {{ str_replace('_', ' ', $dinas->status ?? '-') }}
                                    </span>
                                </div>

                                {{-- 5. Diajukan Pada --}}
                                <span class="text-slate-500">
                                    {{ $dinas->created_at?->format('d M Y') ?? '-' }}
                                </span>

                                {{-- 6. Timer SLA --}}
                                <div>
                                    @if(isset($sla['status']) && $sla['status'] !== 'none')
                                        <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $sla['class'] }}" title="Batas Waktu SLA">
                                            <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="whitespace-nowrap">{{ $sla['label'] }}</span>
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[11px]">-</span>
                                    @endif
                                </div>

                                {{-- 7. Tombol Aksi --}}
                                <div class="flex items-center justify-end gap-1">
                                    <button
                                        type="button"
                                        onclick="openDetail({{ json_encode($dinas) }})"
                                        class="flex size-7 items-center justify-center rounded-lg text-slate-500 hover:bg-sky-50 hover:text-sky-600 transition-colors"
                                        title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    <button
                                        type="button"
                                        onclick="printItem('{{ $dinas->no_dinas }}')"
                                        class="flex size-7 items-center justify-center rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Cetak Dokument">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>

                {{-- =================================================
                     MOBILE CARD LIST
                ================================================== --}}
                <div
                    id="mobileHistoryList"
                    class="flex flex-col gap-3 lg:hidden">
                </div>

                {{-- EMPTY STATE --}}
                <div id="emptyState" class="hidden py-12 text-center">

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
                <div id="pagination" class="flex items-center justify-center gap-2 pt-5">

                    <button
                        type="button"
                        onclick="previousPage()"
                        class="flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f4f6fb]">
                        ‹
                    </button>

                    <span id="pageNumber"
                        class="flex size-8 items-center justify-center rounded-md border border-[#0d6efd] bg-[#eff6ff] text-[13px] font-semibold text-[#0d6efd]">
                        1
                    </span>

                    <button
                        type="button"
                        onclick="nextPage()"
                        class="flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f4f6fb]">
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
<div id="detailModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/30 p-4">

    <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl">

        {{-- HEADER --}}
        <div class="flex items-center justify-between border-b border-[#e2e8f0] p-5">

            <div>

                <h2 class="text-[16px] font-bold">
                    Detail Pengajuan
                </h2>

                <p id="modalNo"
                    class="mt-1 text-[12px] text-[#64748b]">
                </p>

            </div>

            <button
                type="button"
                onclick="closeDetail()"
                class="flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f4f6fb]">
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

                    <p id="modalUser"
                        class="mt-1 text-sm font-semibold">
                    </p>

                </div>

                <div>

                    <p class="text-[11px] text-[#94a3b8]">
                        Tujuan
                    </p>

                    <p id="modalTujuan"
                        class="mt-1 text-sm font-semibold">
                    </p>

                </div>

                <div>

                    <p class="text-[11px] text-[#94a3b8]">
                        Tanggal Perjalanan
                    </p>

                    <p id="modalTanggal"
                        class="mt-1 text-sm font-semibold">
                    </p>

                </div>

                <div>

                    <p class="text-[11px] text-[#94a3b8]">
                        Diajukan Pada
                    </p>

                    <p id="modalDate"
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
            {{-- @foreach($daftarDinas as $dinas)
                <!-- Tombol Detail Modal -->
                <button type="button" 
                        onclick="openDetail({{ json_encode($dinas) }})"
                        class="btn btn-primary">
                    Detail
                </button>
            @endforeach
            <div> --}}
                <p class="mb-3 text-[13px] font-bold">
                    Alur Pengajuan
                </p>

                <!-- Tempat penampung item alur pengajuan dari JS -->
                <div id="modalAlurPengajuan" class="space-y-3">
                    <!-- Akan diisi otomatis oleh Javascript -->
                </div>
            </div>

            {{-- <div>

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

            </div> --}}

        </div>

        {{-- FOOTER --}}
        {{-- <div class="flex justify-end gap-2 border-t border-[#e2e8f0] p-5">

            <button
                type="button"
                onclick="closeDetail()"
                class="rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-semibold text-[#64748b]">
                Tutup
            </button>

            <button
                type="button"
                onclick="printCurrentDetail()"
                class="rounded-lg bg-[#0d6efd] px-4 py-2 text-[12px] font-semibold text-white">
                Cetak
            </button>

        </div> --}}

    </div>

</div>

</body>
</html>

@endsection

@push('scripts')

    @vite('resources/js/riwayat.js')

@endpush