@extends('layouts.app')

@section('title', 'Dashboard - SPPD System')

@section('content')

<section class="px-5 pb-8 pt-5 sm:px-7">

    {{-- ========================================================= --}}
    {{-- STAFF / PENGAJUAN SAYA --}}
    {{-- ========================================================= --}}
    @if(optional(auth()->user()->jabatan)->name !== 'HRGA')
        <div id="allSection">

            {{-- Untuk Manager: jadikan kolom kiri --}}
            <div class="@if(optional(auth()->user()->jabatan)->name === 'Manager') grid gap-6 lg:grid-cols-2 @endif">

                {{-- PENGAJUAN TERBARU SAYA --}}
                <div class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-[0px_4px_6px_rgba(15,23,42,0.02)] sm:p-6">

                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h2 class="text-[16px] font-bold text-[#0f172a]">
                                Pengajuan Terbaru
                            </h2>

                            <p class="mt-1 text-[11px] text-[#64748b]">
                                Pengajuan perjalanan dinas Anda
                            </p>
                        </div>

                        <a href="/riwayat"
                        class="text-[12px] font-semibold text-[#0d6efd] hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    <div id="allList" class="space-y-3">

                        @forelse($pengajuanSaya as $item)

                            <div class="rounded-xl border border-[#eef2f7] p-4 transition hover:border-[#dbeafe] hover:shadow-sm">

                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                    <div class="min-w-0">

                                        <div class="flex flex-wrap items-center gap-2">

                                            <span class="text-[13px] font-bold text-[#0f172a]">
                                                {{ $item->dinas->no_dinas ?? '-' }}
                                            </span>

                                            @php
                                                $statusClass = match($item->status) {
                                                    'approved', 'disetujui'
                                                        => 'bg-emerald-50 text-emerald-600 border-emerald-100',

                                                    'rejected', 'ditolak'
                                                        => 'bg-rose-50 text-rose-600 border-rose-100',

                                                    default
                                                        => 'bg-amber-50 text-amber-600 border-amber-100',
                                                };
                                            @endphp

                                            <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize {{ $statusClass }}">
                                                {{ str_replace('_', ' ', $item->dinas->status) }}
                                            </span>

                                        </div>

                                        <div class="mt-2 grid gap-1 text-[11px] text-[#64748b] sm:grid-cols-2 sm:gap-x-6">

                                            <span>
                                                Pemohon:
                                                <b class="text-[#1e293b]">
                                                    {{ $item->user->name ?? '-' }}
                                                </b>
                                            </span>

                                            <span>
                                                Tujuan:
                                                <b class="text-[#1e293b]">
                                                    {{ $item->kota->name ?? '-' }}
                                                </b>
                                            </span>

                                            <span>
                                                Waktu Dinas:
                                                <b class="text-[#1e293b]">
                                                    {{ $item->ilpd
                                                        ? $item->ilpd->tanggal_awal->format('d') . ' - ' . $item->ilpd->tanggal_akhir->format('d M Y')
                                                        : '-'
                                                    }}
                                                </b>
                                            </span>

                                            <span>
                                                Diajukan:
                                                <b class="text-[#1e293b]">
                                                    {{ $item->created_at
                                                        ? $item->created_at->format('d M Y')
                                                        : '-'
                                                    }}
                                                </b>
                                            </span>

                                        </div>

                                    </div>

                                    <div class="flex shrink-0 gap-2">

                                        <button
                                            type="button"
                                            onclick='showDetail(@json($item))'
                                            class="rounded-lg bg-[#0d6efd] px-3 py-2 text-[11px] font-semibold text-white hover:bg-[#0958c9]">
                                            Detail
                                        </button>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="rounded-xl border border-dashed border-[#e2e8f0] py-8 text-center">
                                <p class="text-xs font-medium text-[#64748b]">
                                    Belum ada pengajuan SPPD.
                                </p>
                            </div>

                        @endforelse

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MANAGER : PERLU PERSETUJUAN --}}
                {{-- ================================================= --}}
                @if(optional(auth()->user()->jabatan)->name === 'Manager')

                    <div id="managerSection">

                        <div class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-sm sm:p-6">

                            <div class="mb-5">
                                <h2 class="text-[16px] font-bold text-[#0f172a]">
                                    Perlu Persetujuan Saya
                                </h2>

                                <p class="mt-1 text-[11px] text-[#64748b]">
                                    Pengajuan staff yang membutuhkan pemeriksaan Anda.
                                </p>
                            </div>

                            <div id="managerList" class="space-y-3">

                                @forelse($approvalManager as $item)

                                    <div class="rounded-xl border border-[#eef2f7] p-4 transition hover:border-[#dbeafe] hover:shadow-sm">

                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                            <div class="min-w-0">

                                                <div class="flex flex-wrap items-center gap-2">

                                                    <span class="text-[13px] font-bold text-[#0f172a]">
                                                        {{ $item->dinas->no_dinas ?? '-' }}
                                                    </span>

                                                    @php
                                                        $statusClass = match($item->status) {
                                                            'approved', 'disetujui'
                                                                => 'bg-emerald-50 text-emerald-600 border-emerald-100',

                                                            'rejected', 'ditolak'
                                                                => 'bg-rose-50 text-rose-600 border-rose-100',

                                                            default
                                                                => 'bg-amber-50 text-amber-600 border-amber-100',
                                                        };
                                                    @endphp

                                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize {{ $statusClass }}">
                                                        {{ str_replace('_', ' ', $item->status) }}
                                                    </span>

                                                </div>

                                                <div class="mt-2 grid gap-1 text-[11px] text-[#64748b] sm:grid-cols-2 sm:gap-x-6">

                                                    <span>
                                                        Pemohon:
                                                        <b class="text-[#1e293b]">
                                                            {{ $item->user->name ?? '-' }}
                                                        </b>
                                                    </span>

                                                    <span>
                                                        Tujuan:
                                                        <b class="text-[#1e293b]">
                                                            {{ $item->kota->name ?? '-' }}
                                                        </b>
                                                    </span>

                                                    <span>
                                                        Waktu Dinas:
                                                        <b class="text-[#1e293b]">
                                                            {{ $item->ilpd
                                                                ? $item->ilpd->tanggal_awal->format('d') . ' - ' . $item->ilpd->tanggal_akhir->format('d M Y')
                                                                : '-'
                                                            }}
                                                        </b>
                                                    </span>

                                                    <span>
                                                        Diajukan:
                                                        <b class="text-[#1e293b]">
                                                            {{ $item->created_at
                                                                ? $item->created_at->format('d M Y')
                                                                : '-'
                                                            }}
                                                        </b>
                                                    </span>

                                                </div>

                                            </div>

                                            <div class="flex shrink-0 gap-2">

                                                <a href="{{ route('approval.show', $item->id) }}"
                                                class="rounded-lg bg-[#0d6efd] px-3 py-2 text-[11px] font-semibold text-white hover:bg-[#0958c9]">
                                                    Proses
                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <div class="rounded-xl border border-dashed border-[#e2e8f0] py-8 text-center">

                                        <p class="text-xs font-medium text-[#64748b]">
                                            Belum ada pengajuan yang membutuhkan persetujuan.
                                        </p>

                                    </div>

                                @endforelse

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- GA / HRGA --}}
    {{-- ========================================================= --}}
    @if(optional(auth()->user()->jabatan)->name === 'HRGA')

        <div id="gaSection" class="mt-6">

            {{-- STATISTIK --}}
            {{-- <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4"> --}}
            <div class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-5">

                {{-- Draft --}}
                <div class="rounded-2xl border border-[#f1f5f9] bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-medium text-[#64748b]">
                        Draft
                    </p>

                    <p class="mt-2 text-2xl font-bold text-[#0f172a]">
                        {{ $draft ?? 0 }}
                    </p>
                </div>

                {{-- MENUNGGU APPROVAL --}}
                <div class="rounded-2xl border border-[#f1f5f9] bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-medium text-[#64748b]">
                        Menunggu Approval
                    </p>

                    <p class="mt-2 text-2xl font-bold text-amber-500">
                        {{ $menungguApproval ?? 0 }}
                    </p>
                </div>

                {{-- SEDANG DIPROSES --}}
                <div class="rounded-2xl border border-[#f1f5f9] bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-medium text-[#64748b]">
                        Sedang Diproses
                    </p>

                    <p class="mt-2 text-2xl font-bold text-blue-500">
                        {{ $sedangDiproses ?? 0 }}
                    </p>
                </div>

                <div class="rounded-2xl border border-[#f1f5f9] bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-medium text-[#64748b]">
                        Disetujui
                    </p>

                    <p class="mt-2 text-2xl font-bold text-[#be13d4]">
                        {{ $Disetujui ?? 0 }}
                    </p>
                </div>

                {{-- SELESAI --}}
                <div class="rounded-2xl border border-[#f1f5f9] bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-medium text-[#64748b]">
                        Selesai
                    </p>

                    <p class="mt-2 text-2xl font-bold text-emerald-500">
                        {{ $selesai ?? 0 }}
                    </p>
                </div>

            </div>

            {{-- SEMUA PENGAJUAN --}}
            <div class="rounded-2xl border border-[#f1f5f9] bg-white p-4 shadow-sm sm:p-6">

                <div class="mb-5 flex items-center justify-between">

                    <div>
                        <h2 class="text-[16px] font-bold text-[#0f172a]">
                            Semua Pengajuan
                        </h2>

                        <p class="mt-1 text-[11px] text-[#64748b]">
                            Daftar seluruh pengajuan perjalanan dinas.
                        </p>
                    </div>

                    <a href="/riwayat"
                        class="text-[12px] font-semibold text-[#0d6efd] hover:underline">
                        Lihat Semua
                    </a>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-left text-[11px]">

                        <thead>
                            <tr class="border-b border-[#eef2f7] text-[#64748b]">

                                <th class="whitespace-nowrap px-4 py-3 font-semibold">
                                    No. Dinas
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold">
                                    Pemohon
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold">
                                    Tujuan
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold">
                                    Waktu Dinas
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold">
                                    Status
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 font-semibold">
                                    SLA
                                </th>

                                <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">
                                    Action
                                </th>

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#f1f5f9]">

                            @forelse($pengajuanGa ?? [] as $item)

                                <tr class="transition hover:bg-[#f8fafc]">

                                    <td class="whitespace-nowrap px-4 py-3 font-semibold text-[#0f172a]">
                                        {{ $item->dinas->no_dinas ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-[#334155]">
                                        {{ $item->sppd->user->name ?? $item->user->name ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-[#334155]">
                                        {{ $item->sppd->kota->name ?? $item->kota->name ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-[#334155]">
                                        {{ $item->ilpd
                                            ? $item->ilpd->tanggal_awal->format('d') . ' - ' . $item->ilpd->tanggal_akhir->format('d M Y')
                                            : '-'
                                        }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3">

                                        @php
                                            $statusClass = match($item->dinas->status) {
                                                'approved', 'disetujui'
                                                    => 'bg-emerald-50 text-emerald-600 border-emerald-100',

                                                'rejected', 'ditolak'
                                                    => 'bg-rose-50 text-rose-600 border-rose-100',

                                                default
                                                    => 'bg-amber-50 text-amber-600 border-amber-100',
                                            };
                                        @endphp

                                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize {{ $statusClass }}">
                                            {{ str_replace('_', ' ', $item->dinas->status) }}
                                        </span>

                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3">

                                        @php
                                            // 1. Style Warna untuk Status Pengajuan
                                            // $statusClass = match(strtolower($item->dinas->status ?? '')) {
                                            //     'approved', 'disetujui' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            //     'rejected', 'ditolak'   => 'bg-rose-50 text-rose-600 border-rose-100',
                                            //     default                 => 'bg-amber-50 text-amber-600 border-amber-100',
                                            // };

                                            // 2. Ambil Informasi SLA dari Model Dinas
                                            $sla = $item->dinas->sla_info;
                                        @endphp

                                        <div class="flex items-center gap-1.5">
                                            {{-- Badge Status Pengajuan Utama --}}
                                            {{-- <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize {{ $statusClass }}">
                                                {{ str_replace('_', ' ', $item->dinas->status) }}
                                            </span> --}}

                                            {{-- Badge Timer SLA (Hanya Tampil Jika Masih Proses / Ada SLA) --}}
                                            @if($sla['status'] !== 'none')
                                                <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $sla['class'] }}" title="Batas Waktu SLA">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $sla['label'] }}
                                                </span>
                                            @endif
                                        </div>

                                    </td>
                                    {{-- <td class="whitespace-nowrap px-4 py-3">

                                        @php
                                            $statusClass = match($item->dinas->status) {
                                                'approved', 'disetujui'
                                                    => 'bg-emerald-50 text-emerald-600 border-emerald-100',

                                                'rejected', 'ditolak'
                                                    => 'bg-rose-50 text-rose-600 border-rose-100',

                                                default
                                                    => 'bg-amber-50 text-amber-600 border-amber-100',
                                            };
                                        @endphp

                                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize {{ $statusClass }}">
                                            {{ str_replace('_', ' ', $item->dinas->status) }}
                                        </span>

                                    </td> --}}

                                    <td class="whitespace-nowrap px-4 py-3 text-right">

                                        {{-- <a href="{{ route('approve.show', $item->id) }}"
                                           class="rounded-lg bg-[#0d6efd] px-3 py-2 text-[10px] font-semibold text-white hover:bg-[#0958c9]">
                                            Detail
                                        </a> --}}
                                        <button 
                                            type="button" 
                                            onclick='showDetail(@json($item))'
                                            class="rounded-lg bg-[#0d6efd] px-3 py-2 text-[11px] font-semibold text-white hover:bg-[#0958c9]">
                                            Detail 
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="py-8 text-center text-xs text-[#64748b]">
                                        Belum ada pengajuan.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif

</section>

{{-- MODAL DETAIL --}}
<!-- Backdrop & Modal Container -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    
    <!-- Modal Box -->
    <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl max-h-[90vh] overflow-y-auto">
        
        <!-- HEADER MODAL -->
        <div class="flex items-center justify-between border-b border-[#f1f5f9] pb-4">
            <div>
                <p class="text-[11px] font-medium text-[#64748b]">Detail Pengajuan</p>
                <h2 id="modalTitle" class="text-lg font-bold text-[#0f172a]">-</h2>
            </div>
            
            <!-- Tombol Close Modal -->
            <button 
                type="button" 
                onclick="closeModal()" 
                class="rounded-lg p-1.5 text-[#64748b] hover:bg-[#f1f5f9]">
                ✕
            </button>
        </div>

        <!-- INFORMASI PENGAJUAN (Diisi via JS) -->
        <div id="modalInfo" class="my-5 rounded-xl bg-[#f8fafc] p-4">
            <!-- Data Pemohon, Tujuan, Tanggal, Status masuk ke sini -->
        </div>

        <!-- DOKUMEN LAMPIRAN (Diisi via JS) -->
        <div class="space-y-3">
            <h3 class="text-xs font-semibold text-[#64748b] uppercase tracking-wider">Dokumen Lampiran</h3>
            
            <div id="modalContent" class="space-y-3">
                <!-- List Dokumen & Tombol Action per Dokumen masuk ke sini -->
            </div>
        </div>

        <!-- FOOTER / MODAL ACTIONS (Diisi via JS) -->
        <div id="modalActions" class="mt-6 flex justify-end gap-2 border-t border-[#f1f5f9] pt-4">
            <!-- Tombol aksi bawah (misal tombol Tutup) masuk ke sini -->
        </div>

    </div>
</div>

@endsection

@push('scripts')

<script>
    // Passing data auth dari Laravel/Blade ke global window JavaScript
    window.currentUserId = {{ auth()->id() }};
    window.currentUserRole = "{{ auth()->user()->jabatan->name ?? '-' }}";
</script>

    @vite('resources/js/dashboard.js')

@endpush