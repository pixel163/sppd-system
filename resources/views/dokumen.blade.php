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

                <p id="pageDescription"
                    class="mt-1 text-[13px] text-[#64748b]">
                    Kelola dan unduh dokumen serta tiket perjalanan dinas Anda.
                </p>

            </div>

            {{-- =================================================
                 BODY
            ================================================== --}}
            <div class="flex flex-col items-stretch gap-4 xl:flex-row">
                @php
                    $dinas = $detailData['dinas'] ?? null;
                    $sppd = $detailData['sppd'] ?? collect();
                    $ilpd = $detailData['ilpd'] ?? collect();
                    
                    // Mengambil role user login (fallback ke 'STAFF' jika tidak ada)
                    $userRole = auth()->user()->jabatan->name ?? '-';
                @endphp

                {{-- =================================================
                     LEFT : LIST SPPD
                ================================================== --}}
                <section class="w-full shrink-0 rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-[0px_4px_6px_rgba(15,23,42,0.02)] sm:p-5 xl:w-[380px] flex flex-col">

                    <!-- HEADER -->
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-[15px] font-bold text-[#0f172a]">
                            Daftar Pengajuan SPPD
                        </h2>
                        <span id="roleBadge" class="rounded-md bg-[#eff6ff] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#2563eb]">
                            {{ $userRole }}
                        </span>
                    </div>

                    <!-- SEARCH & FILTER FORM -->
                    <form method="GET" action="{{ route('dokumen') }}" class="mb-4">
                        {{-- Pertahankan query parameter yang ada --}}
                        @if(request('selected_id'))
                            <input type="hidden" name="selected_id" value="{{ request('selected_id') }}">
                        @endif
                        @if(request('status'))
                            <input type="hidden" name="status" id="statusInput" value="{{ request('status') }}">
                        @endif

                        <div class="flex gap-2">
                            <div class="flex min-w-0 flex-1 items-center gap-2 rounded-lg bg-[#f4f6fb] px-3 py-2">
                                <i data-lucide="search" class="size-3.5 shrink-0 text-[#94a3b8]"></i>
                                <input 
                                    name="search" 
                                    type="text" 
                                    value="{{ request('search') }}"
                                    placeholder="Cari nomor SPPD atau tujuan..."
                                    class="min-w-0 flex-1 bg-transparent text-[12px] outline-none placeholder:text-[#94a3b8] text-[#0f172a]">
                            </div>

                            <button 
                                type="button" 
                                id="filterButton"
                                onclick="toggleFilterMenu()"
                                class="flex shrink-0 items-center gap-1.5 rounded-lg border border-[#e2e8f0] px-3 py-2 text-[12px] font-semibold text-[#64748b] hover:bg-[#f8fafc]">
                                <i data-lucide="sliders-horizontal" class="size-3.5 text-[#64748b]"></i>
                                Filter
                            </button>
                        </div>
                    </form>

                    <!-- FILTER DROPDOWN MENU -->
                    <div id="filterMenu" class="mb-3 hidden rounded-xl border border-[#e2e8f0] bg-white p-1.5 shadow-sm space-y-0.5">
                        <a href="{{ route('dokumen', array_merge(request()->except(['status', 'page']))) }}" 
                        class="block w-full rounded-lg px-3 py-1.5 text-left text-[12px] font-medium text-[#64748b] hover:bg-[#f8fafc] {{ !request('status') ? 'bg-[#f1f5f9] font-semibold text-[#0f172a]' : '' }}">
                            Semua Status
                        </a>
                        <a href="{{ route('dokumen', array_merge(request()->except(['page']), ['status' => 'Selesai'])) }}" 
                        class="block w-full rounded-lg px-3 py-1.5 text-left text-[12px] font-medium text-[#64748b] hover:bg-[#f8fafc] {{ request('status') == 'Selesai' ? 'bg-[#f1f5f9] font-semibold text-[#0f172a]' : '' }}">
                            Selesai
                        </a>
                        <a href="{{ route('dokumen', array_merge(request()->except(['page']), ['status' => 'Approval'])) }}" 
                        class="block w-full rounded-lg px-3 py-1.5 text-left text-[12px] font-medium text-[#64748b] hover:bg-[#f8fafc] {{ request('status') == 'Approval' ? 'bg-[#f1f5f9] font-semibold text-[#0f172a]' : '' }}">
                            Approval
                        </a>
                    </div>

                    <!-- LIST CONTAINER WITH SCROLLBAR -->
                    <div id="pengajuanList" class="space-y-2.5 max-h-[520px] overflow-y-auto pr-1">
                        @forelse($daftarDinas as $item)
                            @php $isSelected = ($selectedId == $item->id); @endphp
                            
                            <a href="{{ route('dokumen', array_merge(request()->query(), ['selected_id' => $item->id])) }}" 
                            class="block rounded-xl border p-3.5 transition-all duration-150 relative 
                            {{ $isSelected 
                                ? 'border-[#0d6efd] bg-[#eff6ff]/40 shadow-sm' 
                                : 'border-[#e2e8f0] bg-white hover:border-[#cbd5e1] hover:bg-[#f8fafc]' }}">
                                
                                <!-- Card Header -->
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[13px] font-bold text-[#0f172a] truncate max-w-[200px]">
                                        {{ $item->no_dinas }}
                                    </span>
                                    
                                    {{-- Status Badge --}}
                                    @if($item->status == 'Selesai')
                                        <span class="rounded-md bg-[#dcfce7] px-2 py-0.5 text-[10px] font-semibold text-[#15803d]">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="rounded-md bg-[#fef3c7] px-2 py-0.5 text-[10px] font-semibold text-[#b45309]">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Card Body -->
                                <div class="space-y-1.5 text-[12px] text-[#64748b]">
                                    <!-- Kota Tujuan -->
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="map-pin" class="size-3.5 shrink-0 text-[#94a3b8]"></i>
                                        <span class="truncate">{{ $item->kota_tujuan ?? 'Tujuan tidak diisi' }}</span>
                                    </div>

                                    <!-- Tanggal -->
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="calendar" class="size-3.5 shrink-0 text-[#94a3b8]"></i>
                                        <span>
                                            {{ $item->tanggal_awal ? \Carbon\Carbon::parse($item->tanggal_awal)->format('d M Y') : '-' }} 
                                            - 
                                            {{ $item->tanggal_akhir ? \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <!-- EMPTY STATE -->
                            <div class="rounded-xl border border-dashed border-[#e2e8f0] bg-[#f8fafc] px-4 py-8 text-center">
                                <i data-lucide="inbox" class="mx-auto size-8 text-[#94a3b8] mb-2"></i>
                                <p class="text-[13px] font-semibold text-[#64748b]">Tidak ada pengajuan</p>
                                <p class="mt-0.5 text-[11px] text-[#94a3b8]">Tidak ditemukan data sesuai pencarian.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- PAGINASI -->
                    @if($daftarDinas->hasPages())
                        <div class="mt-4 pt-3 border-t border-[#f1f5f9]">
                            {{ $daftarDinas->withQueryString()->links() }}
                        </div>
                    @endif

                </section>

                {{-- =================================================
                     RIGHT
                ================================================== --}}
                <div class="min-w-0 flex-1 space-y-4">

                    {{-- DETAIL HEADER --}}
                    <section class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-[0px_4px_6px_rgba(15,23,42,0.02)] sm:p-6">
        
                        <!-- TOP HEADER: NO DINAS, STATUS, & LIHAT DETAIL BUTTON -->
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h2 id="detailNo" class="text-[18px] font-bold text-[#0f172a]">
                                    {{ $dinas->no_dinas ?? '-' }}
                                </h2>

                                {{-- Status Badge --}}
                                @if(($dinas->status ?? '') == 'Selesai')
                                    <span id="detailStatus" class="rounded-md bg-[#dcfce7] px-2.5 py-1 text-[11px] font-semibold text-[#15803d]">
                                        Selesai
                                    </span>
                                @else
                                    <span id="detailStatus" class="rounded-md bg-[#fef3c7] px-2.5 py-1 text-[11px] font-semibold text-[#b45309]">
                                        {{ $dinas->status ?? '-' }}
                                    </span>
                                @endif
                            </div>

                            <button id="detailButton" type="button" class="self-start px-0 py-1.5 text-[12px] font-semibold text-[#0d6efd] hover:underline sm:self-auto">
                                Lihat Detail Pengajuan
                            </button>
                        </div>

                        <!-- BOTTOM CONTENT: TUJUAN, TANGGAL, PEMOHON -->
                        <div class="mt-0 flex flex-col gap-5 border-t border-[#f1f5f9] pt-4 lg:flex-row lg:justify-between">
                            
                            <!-- INFORMASI KOTA & TANGGAL -->
                            <div class="flex flex-col gap-2.5">
                                <!-- Kota Tujuan -->
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="size-4 shrink-0 text-[#94a3b8]"></i>
                                    <span id="detailTujuan" class="text-[13px] font-medium text-[#64748b]">
                                        {{ $dinas->kota_tujuan ?? 'Tujuan tidak diisi' }}
                                    </span>
                                </div>

                                <!-- Tanggal Pelaksanaan -->
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar-days" class="size-4 shrink-0 text-[#94a3b8]"></i>
                                    <span id="detailTanggal" class="text-[13px] font-medium text-[#64748b]">
                                        {{ isset($dinas->tanggal_awal) ? \Carbon\Carbon::parse($dinas->tanggal_awal)->format('d M Y') : '-' }}
                                        s/d
                                        {{ isset($dinas->tanggal_akhir) ? \Carbon\Carbon::parse($dinas->tanggal_akhir)->format('d M Y') : '-' }}
                                    </span>
                                </div>
                            </div>

                            <!-- INFORMASI PEMOHON -->
                            <div class="flex flex-col gap-2 text-[12px] lg:items-end">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[#94a3b8]">Diajukan oleh:</span>
                                    <span id="detailPemohon" class="font-semibold text-[#0f172a]">
                                        {{ $dinas->name ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[#94a3b8]">Tanggal Pengajuan:</span>
                                    <span id="detailTanggalPengajuan" class="font-semibold text-[#0f172a]">
                                        {{ isset($dinas->tgl_pengajuan) ? \Carbon\Carbon::parse($dinas->tgl_pengajuan)->format('d M Y') : '-' }}
                                    </span>
                                </div>
                            </div>

                        </div>

                    </section>

                    {{-- DOKUMEN --}}
                    <section class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-[0px_4px_6px_rgba(15,23,42,0.02)] sm:p-6">
    
                        <!-- HEADER BAGIAN -->
                        <div class="mb-5 flex items-center justify-between border-b border-[#f1f5f9] pb-3">
                            <h2 class="text-[15px] font-bold text-[#0f172a]">
                                Dokumen & Tiket
                            </h2>
                            <span id="documentCount" class="rounded-full bg-[#f1f5f9] px-2.5 py-0.5 text-[11px] font-medium text-[#64748b]">
                                {{ ($sppd->count() ?? 0) + ($ilpd->count() ?? 0) }} Dokumen
                            </span>
                        </div>

                        <!-- DAFTAR DOKUMEN -->
                        <div class="space-y-5">
                            
                            <!-- KATEGORI: FORM 1 - SPPD -->
                            @if(isset($sppd) && $sppd->count() > 0)
                                <div class="space-y-2.5">
                                    <h3 class="text-[11px] font-bold uppercase tracking-wider text-[#94a3b8]">
                                        Form 1 - SPPD
                                    </h3>

                                    @foreach($sppd as $doc)
                                        <div class="flex items-center justify-between gap-3 rounded-xl border border-[#e2e8f0] bg-white p-3.5 transition-all hover:border-[#cbd5e1]">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#eff6ff] text-[#2563eb]">
                                                    <i data-lucide="file-text" class="size-5"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="truncate text-[13px] font-semibold text-[#0f172a]">
                                                        {{ $doc->no_sppd ?? 'No. SPPD Tidak Ada' }}
                                                    </h4>
                                                    <p class="truncate text-[11px] text-[#64748b]">
                                                        Formulir Surat Perjalanan Dinas
                                                    </p>
                                                </div>
                                            </div>

                                            @if(!empty($doc->file_path))
                                                <a href="{{ asset($doc->file_path) }}" target="_blank" class="shrink-0 rounded-lg bg-[#f8fafc] px-3 py-1.5 text-[12px] font-medium text-[#0f172a] border border-[#e2e8f0] hover:bg-[#f1f5f9] transition-colors">
                                                    Unduh
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- KATEGORI: FORM 2 - ILPD -->
                            @if(isset($ilpd) && $ilpd->count() > 0)
                                <div class="space-y-2.5">
                                    <h3 class="text-[11px] font-bold uppercase tracking-wider text-[#94a3b8]">
                                        Form 2 - ILPD
                                    </h3>

                                    @foreach($ilpd as $doc)
                                        <div class="flex items-center justify-between gap-3 rounded-xl border border-[#e2e8f0] bg-white p-3.5 transition-all hover:border-[#cbd5e1]">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#ecfdf5] text-[#059669]">
                                                    <i data-lucide="ticket" class="size-5"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="truncate text-[13px] font-semibold text-[#0f172a]">
                                                        {{ $doc->no_ilpd ?? 'No. ILPD Tidak Ada' }}
                                                    </h4>
                                                    <p class="truncate text-[11px] text-[#64748b]">
                                                        Formulir perizinan yang telah diperiksa
                                                    </p>
                                                </div>
                                            </div>

                                            @if(!empty($doc->file_path))
                                                <a href="{{ asset($doc->file_path) }}" target="_blank" class="shrink-0 rounded-lg bg-[#f8fafc] px-3 py-1.5 text-[12px] font-medium text-[#0f172a] border border-[#e2e8f0] hover:bg-[#f1f5f9] transition-colors">
                                                    Unduh
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- KATEGORI: TIKET -->
                            @if(isset($tiket) && $tiket->count() > 0)
                                <div class="space-y-2.5">
                                    <h3 class="text-[11px] font-bold uppercase tracking-wider text-[#94a3b8]">
                                        Tiket
                                    </h3>

                                    @foreach($tiket as $doc)
                                        <div class="flex items-center justify-between gap-3 rounded-xl border border-[#e2e8f0] bg-white p-3.5 transition-all hover:border-[#cbd5e1]">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#ecfdf5] text-[#059669]">
                                                    <i data-lucide="ticket" class="size-5"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="truncate text-[13px] font-semibold text-[#0f172a]">
                                                        {{ $doc->no_ilpd ?? 'No. ILPD Tidak Ada' }}
                                                    </h4>
                                                    <p class="truncate text-[11px] text-[#64748b]">
                                                        E-ticket perjalanan dinas yang telah disiapkan
                                                    </p>
                                                </div>
                                            </div>

                                            @if(!empty($doc->file_path))
                                                <a href="{{ asset($doc->file_path) }}" target="_blank" class="shrink-0 rounded-lg bg-[#f8fafc] px-3 py-1.5 text-[12px] font-medium text-[#0f172a] border border-[#e2e8f0] hover:bg-[#f1f5f9] transition-colors">
                                                    Unduh
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- STATE KOSONG / BELUM ADA DOKUMEN -->
                            @if((!isset($sppd) || $sppd->count() == 0) && (!isset($ilpd) || $ilpd->count() == 0))
                                <div class="flex flex-col items-center justify-center py-8 text-center">
                                    <div class="mb-2 flex size-10 items-center justify-center rounded-full bg-[#f8fafc] text-[#94a3b8]">
                                        <i data-lucide="folder-open" class="size-5"></i>
                                    </div>
                                    <p class="text-[13px] font-medium text-[#64748b]">Belum ada dokumen atau tiket</p>
                                    <p class="text-[11px] text-[#94a3b8] mt-0.5">Dokumen terlampir akan muncul di sini secara otomatis.</p>
                                </div>
                            @endif

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
<div id="documentModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 px-4">

    <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl sm:p-6">

        <div class="flex items-start justify-between gap-4">

            <div>

                <h2
                    id="modalTitle"
                    class="text-[16px] font-bold">
                    Dokumen
                </h2>

                <p
                    id="modalDescription"
                    class="mt-1 text-[12px] text-[#64748b]">
                    -
                </p>

            </div>

            <button
                type="button"
                onclick="closeDocumentModal()"
                class="flex size-8 shrink-0 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f8fafc]">
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
                class="rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-semibold text-[#64748b]">
                Tutup
            </button>

            <button
                type="button"
                onclick="printDocument()"
                class="rounded-lg border border-[#0d6efd] px-4 py-2 text-[12px] font-semibold text-[#0d6efd]">
                Cetak
            </button>

        </div>

    </div>

</div>

</body>
</html>

@endsection

@push('scripts')

<!-- SCRIPT UNTUK TOGGLE FILTER MENU -->
<script>
    function toggleFilterMenu() {
        const menu = document.getElementById('filterMenu');
        menu.classList.toggle('hidden');
    }
</script>
    {{-- @vite('resources/js/dokumen.js') --}}

@endpush