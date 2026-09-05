@extends('layouts.app')

@section('title', 'Dashboard - SPPD System')

@section('content')

<section class="px-5 pb-8 pt-5 sm:px-7">

    {{-- STAFF --}}
    <div id="staffSection">
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

                <a href="/riwayat" class="text-[12px] font-semibold text-[#0d6efd] hover:underline">
                {{-- <a href="/riwayat" class="rounded-lg bg-[#0d6efd] px-3 py-2 text-[11px] font-semibold text-white hover:bg-[#0958c9]"> --}}
                    Lihat Semua
                </a>
            </div>

            <div id="staffList" class="space-y-3">
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
                                            'approved', 'disetujui' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'rejected', 'ditolak'   => 'bg-rose-50 text-rose-600 border-rose-100',
                                            default                => 'bg-amber-50 text-amber-600 border-amber-100',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize {{ $statusClass }}">
                                        {{ str_replace('_', ' ', $item->status) }}
                                    </span>
                                </div>

                                <div class="mt-2 grid gap-1 text-[11px] text-[#64748b] sm:grid-cols-2 sm:gap-x-6">
                                    <span>Pemohon: <b class="text-[#1e293b]">{{ $item->user->name ?? '-' }}</b></span>
                                    <span>Tujuan: <b class="text-[#1e293b]">{{ $item->kota->name ?? '-' }}</b></span>
                                    <span>Waktu Dinas: 
                                        <b class="text-[#1e293b]">
                                            {{ $item->ilpd ? $item->ilpd->tanggal_awal->format('d') . ' - ' . $item->ilpd->tanggal_akhir->format('d M Y') : '-' }}
                                        </b>
                                    </span>
                                    <span>Diajukan: <b class="text-[#1e293b]">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</b></span>
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
                        <p class="text-xs font-medium text-[#64748b]">Belum ada pengajuan SPPD.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- MANAGER --}}
    {{-- @if(
        (is_string(auth()->user()->jabatan) && trim(auth()->user()->jabatan) === 'Manager') ||
        (is_object(auth()->user()->jabatan) && optional(auth()->user()->jabatan)->nama === 'Manager')
    ) --}}
    @if(optional(auth()->user()->jabatan)->name === 'Manager')
        <div id="managerSection" class="mt-6">
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
                                                'approved', 'disetujui' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'rejected', 'ditolak'   => 'bg-rose-50 text-rose-600 border-rose-100',
                                                 default                => 'bg-amber-50 text-amber-600 border-amber-100',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium capitalize {{ $statusClass }}">
                                            {{ str_replace('_', ' ', $item->status) }}
                                        </span>
                                    </div>

                                    <div class="mt-2 grid gap-1 text-[11px] text-[#64748b] sm:grid-cols-2 sm:gap-x-6">
                                        <span>Pemohon: <b class="text-[#1e293b]">{{ $item->user->name ?? '-' }}</b></span>
                                        <span>Tujuan: <b class="text-[#1e293b]">{{ $item->kota->name ?? '-' }}</b></span>
                                        <span>Waktu Dinas: 
                                            <b class="text-[#1e293b]">
                                                {{ $item->ilpd ? $item->ilpd->tanggal_awal->format('d') . ' - ' . $item->ilpd->tanggal_akhir->format('d M Y') : '-' }}
                                            </b>
                                        </span>
                                        <span>Diajukan: <b class="text-[#1e293b]">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</b></span>
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
                            <p class="text-xs font-medium text-[#64748b]">Belum ada pengajuan yang membutuhkan persetujuan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- GA --}}
    <div id="gaSection" class="hidden">

        {{-- COPY isi GA dari kode lama kamu di sini --}}
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

    @vite('resources/js/dashboard.js')

@endpush