@extends('layouts.app')

@section('title', 'Form SPPD & ILPD')

@section('content')

<div class="container mx-auto p-6">

    {{-- CARD WRAPPER UTAMA --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- HEADER TAB NAVIGATION --}}
        <div class="relative z-10 flex border-b border-gray-200 bg-gray-50 px-4 pt-3">

            <button
                type="button"
                id="tab-sppd-btn"
                onclick="switchTab('sppd')"
                class="tab-btn border-b-2 border-blue-600 px-6 py-3 text-sm font-bold text-blue-600 transition-all outline-none"
            >
                📄 Form 1: SPPD (Pengajuan Dinas)
            </button>

            <button
                type="button"
                id="tab-ilpd-btn"
                onclick="switchTab('ilpd')"
                class="tab-btn px-6 py-3 text-sm font-medium text-gray-500 transition-all outline-none hover:text-gray-700"
            >
                💰 Form 2: ILPD (Rincian Biaya)
            </button>

        </div>

        {{-- <div id="tab-sppd-content" class="tab-content p-6">
            @include('components.sppd')

            <!-- Container aksi tombol di pojok kanan bawah -->
            <div class="mt-6 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <!-- Form Tolak -->
                <form action="{{ route('sppd.reject', $sppd->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')">
                    @csrf
                    <button 
                        type="submit" 
                        class="rounded-lg bg-rose-600 px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-rose-700">
                        Tolak
                    </button>
                </form>

                <!-- Form Setujui -->
                <form id="approvalForm" method="POST" action="{{ route('sppd.approve', $sppd->id) }}" enctype="multipart/form-data">
                    @csrf
                    <button 
                        type="submit"
                        class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]">
                        Setujui
                    </button>
                </form>
            </div>
        </div> --}}

        <div id="tab-sppd-content" class="tab-content p-6">
            <!-- Form Setujui sekarang membungkus seluruh komponen input -->
            <form id="approvalForm" method="POST" action="{{ route('sppd.approve', $sppd->id) }}" enctype="multipart/form-data">
                @csrf

                {{-- Komponen SPPD (durasi & tugas ada di dalam sini) --}}
                @include('components.sppd')

                <!-- Container aksi tombol di pojok kanan bawah -->
                <div class="mt-6 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    
                    <!-- Form Tolak tetap terpisah menggunakan button type="button" / form mandiri di luar atau di sini -->
                    <button 
                        type="submit" 
                        formaction="{{ route('sppd.reject', $sppd->id) }}"
                        onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')"
                        class="rounded-lg bg-rose-600 px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-rose-700">
                        Tolak
                    </button>

                    <!-- Tombol Setujui -->
                    <button 
                        type="submit"
                        class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]">
                        Setujui
                    </button>
                </div>
            </form>
        </div>
        {{-- <div id="tab-sppd-content" class="tab-content p-6">
            @include('components.sppd')

            <form action="{{ route('sppd.reject', $sppd->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')">
                @csrf
                <button type="submit" class="btn btn-danger">Tolak</button>
            </form>

            <!-- Tombol ditempatkan langsung di dalam Tab SPPD -->
            <form id="approvalForm" method="POST" action="{{ route('sppd.approve', $sppd->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-actions flex justify-end gap-3 pt-6">
                    <button 
                        type="submit"
                        class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]">
                        Setujui
                    </button>
                </div>
            </form>
        </div> --}}

        <div id="tab-ilpd-content" class="tab-content hidden p-6">
            @if(isset($ilpd) && $ilpd)
                @include('components.ilpd')
            @else
                <div class="p-8 text-center text-gray-500">
                    <p class="font-semibold">
                        Form 2 (ILPD) Belum Diisi oleh Pemohon.
                    </p>
                </div>
            @endif
        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>
    function switchTab(tabName) {

        // Sembunyikan semua konten tab
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });

        // Reset semua tombol tab
        document.querySelectorAll('.tab-btn').forEach(btn => {

            btn.classList.remove(
                'font-bold',
                'text-blue-600',
                'border-b-2',
                'border-blue-600'
            );

            btn.classList.add(
                'font-medium',
                'text-gray-500'
            );

        });

        // Tampilkan tab yang dipilih
        const content = document.getElementById(
            'tab-' + tabName + '-content'
        );

        if (content) {
            content.classList.remove('hidden');
        }

        // Aktifkan tombol tab
        const activeBtn = document.getElementById(
            'tab-' + tabName + '-btn'
        );

        if (activeBtn) {

            activeBtn.classList.remove(
                'font-medium',
                'text-gray-500'
            );

            activeBtn.classList.add(
                'font-bold',
                'text-blue-600',
                'border-b-2',
                'border-blue-600'
            );

        }
    }
</script>

@endpush
