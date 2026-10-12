@extends('layouts.app')

@section('title', 'Form ILPD - SPPD System')

@section('content')

<body class="min-h-screen bg-white text-[#1e293b]">

<div class="min-h-screen">

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen w-full">

        {{-- =====================================================
             CONTENT
        ====================================================== --}}
        <div class="w-full px-5 py-6 sm:px-7 lg:px-8">

            {{-- TITLE --}}
            <div class="mb-5">

                <h1 class="text-[24px] font-extrabold uppercase">
                    FORM ILPD
                </h1>

                <p class="mt-1 text-[14px] text-[#64748b]">
                    Lengkapi data perizinan perjalanan dinas Anda.
                </p>

            </div>

            <form id="ilpdForm" action="{{ route('ilpd.store') }}" method="POST" class="space-y-0">
                    @csrf
                {{-- =================================================
                    SECTION 1
                ================================================== --}}
                <section class="section-card rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]">

                    <div class="mb-5 flex items-center gap-3">

                        <div class="flex size-7 items-center justify-center rounded-full bg-[#2563eb] text-[14px] font-bold text-white">
                            1
                        </div>

                        <h2 class="text-[15px] font-bold uppercase">
                            Rencana Perjalanan Dinas
                        </h2>

                    </div>

                    {{-- KOTA --}}
                    <div class="mb-4">

                        <label for="kota"
                            class="mb-1.5 block text-[13px] font-semibold">
                            Kota Tujuan <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="kota"
                            name="kota"
                            type="text"
                            {{-- value="{{ $sppd->kota->name }}" --}}
                            value="{{ $sppd->kota_dan_unit }}"
                            class="form-input flex-1"
                            readonly>
                    </div>

                    {{-- LAMA PERJALANAN --}}
                    <div class="mb-4">
                        <label class="mb-1.5 block text-[13px] font-semibold">
                            Lama Perjalanan <span class="text-red-500">*</span>
                            {{-- Lama Perjalanan <span class="text-red-500">*</span> <span>(minimal waktu pengajuan h-7)</span> --}}
                        </label>

                        <div class="date-wrapper flex items-center gap-4">
                            {{-- TANGGAL MULAI --}}
                            <div class="flex flex-1 items-center rounded-lg border border-[#cbd5e1] px-3.5 py-2.5">
                                <input
                                    id="tanggalMulai"
                                    name="tanggal_awal"
                                    type="date"
                                    class="w-full border-0 text-[14px] outline-none"
                                    value="{{ $sppd->tanggal_awal?->format('Y-m-d') ?? '' }}"
                                    data-durasi="{{ $sppd->durasi ?? 1 }}">
                                    {{-- onchange="hitungSemua()"> --}}
                            </div>

                            <span class="text-[14px] font-semibold text-[#64748b]">s/d</span>

                            {{-- TANGGAL SELESAI (READONLY/DISABLED KARENA OTOMATIS) --}}
                            <div class="flex flex-1 items-center rounded-lg border border-[#cbd5e1] bg-gray-50 px-3.5 py-2.5">
                                <input
                                    id="tanggalSelesai"
                                    name="tanggal_akhir"
                                    type="date"
                                    class="w-full border-0 bg-transparent text-[14px] outline-none"
                                    value="{{ $sppd->tanggal_akhir?->format('Y-m-d') ?? '' }}"
                                    readonly>
                            </div>
                        </div>

                        <p id="infoLamaPerjalanan" class="mt-1.5 text-[11px] text-[#64748b]"></p>
                    </div>

                    {{-- TRANSPORTASI --}}
                    <div class="mb-4">
                        <label class="mb-2 block text-[13px] font-semibold">
                            Transportasi <span class="text-red-500">*</span>
                        </label>

                        <div class="transport-grid grid grid-cols-3 gap-x-6 gap-y-3">
                            @foreach ($transports as $transport)
                                <label class="flex items-center gap-2 pointer-events-none cursor-default">
                                    <input
                                        type="checkbox"
                                        name="transport_id[]"
                                        value="{{ $transport->id }}"
                                        class="size-[18px] accent-[#2563eb]"
                                        {{ in_array($transport->id, $sppd->transport_id ?? []) ? 'checked' : '' }}
                                        readonly>
                                    <span class="text-[14px] text-slate-800">
                                        {{ $transport->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        
                        {{-- Transportasi Lainnya --}}
                        <div class="mt-2 flex items-center gap-3">
                            <label class="flex items-center gap-2 pointer-events-none cursor-default">
                                <input 
                                    type="checkbox"
                                    class="size-[18px] accent-[#2563eb]"
                                    {{ !empty($sppd->transport_lainnya) ? 'checked' : '' }}
                                    readonly>
                                <span class="text-[14px] text-slate-800">
                                    Lainnya
                                </span>
                            </label>

                            <input
                                type="text"
                                name="transport_lainnya"
                                value="{{ $sppd->transport_lainnya }}"
                                placeholder="Sebutkan..."
                                class="w-[180px] rounded-md border border-[#cbd5e1] bg-slate-50 px-3 py-2 text-[12px] outline-none text-slate-800 cursor-default"
                                readonly>
                        </div>
                    </div>

                    {{-- KEPERLUAN --}}
                    <div class="mb-4">
                        <label class="mb-2 block text-[13px] font-semibold">
                            Keperluan <span class="text-red-500">*</span>
                        </label>

                        <div class="keperluan-grid grid grid-cols-3 gap-x-6 gap-y-3">
                            @foreach ($keperluans as $keperluan)
                                <label class="flex items-center gap-2 pointer-events-none cursor-default">
                                    <input
                                        type="checkbox"
                                        name="keperluan_id[]"
                                        value="{{ $keperluan->id }}"
                                        class="size-[18px] accent-[#2563eb]"
                                        {{ in_array($keperluan->id, $sppd->keperluan_id ?? []) ? 'checked' : '' }}
                                        readonly>
                                    <span class="text-[14px] text-slate-800">
                                        {{ $keperluan->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        
                        {{-- Keperluan Lainnya --}}
                        <div class="mt-2 flex items-center gap-3">
                            <label class="flex items-center gap-2 pointer-events-none cursor-default">
                                <input 
                                    type="checkbox"
                                    class="size-[18px] accent-[#2563eb]"
                                    {{ !empty($sppd->keperluan_lainnya) ? 'checked' : '' }}
                                    readonly>
                                <span class="text-[14px] text-slate-800">
                                    Lainnya
                                </span>
                            </label>

                            <input
                                type="text"
                                name="keperluan_lainnya"
                                value="{{ $sppd->keperluan_lainnya }}"
                                placeholder="Sebutkan..."
                                class="w-[180px] rounded-md border border-[#cbd5e1] bg-slate-50 px-3 py-2 text-[12px] outline-none text-slate-800 cursor-default"
                                readonly>
                        </div>
                    </div>

                    {{-- TUGAS --}}
                    <div>
                        <label for="tugas" class="mb-1.5 block text-[13px] font-semibold">
                            Tugas <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            id="tugas"
                            name="tugas"
                            rows="4"
                            class="form-textarea"
                            placeholder="1.&#10;2.&#10;3."
                            readonly>{{ $sppd->tugas ?? '' }}</textarea>

                        <p class="mt-1 text-[11px] text-[#64748b]">
                            Jelaskan tugas yang akan dilakukan selama perjalanan dinas (maks. 3 poin)
                        </p>
                    </div>

                </section>

                {{-- =================================================
                    SECTION 2
                ================================================== --}}
                <section class="section-card mt-5 rounded-2xl border border-[#f1f5f9] bg-white p-7 shadow-[0px_4px_6px_rgba(0,0,0,0.02)]">

                    <div class="mb-5 flex items-center gap-3">

                        <div class="flex size-7 items-center justify-center rounded-full bg-[#2563eb] text-[14px] font-bold text-white">
                            2
                        </div>

                        <h2 class="text-[15px] font-bold uppercase">
                            Perkiraan Biaya
                        </h2>

                    </div>

                    {{-- BBM --}}
                    <div class="mb-5">

                        <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                            BBM (Opsional)
                        </label>

                        <div class="currency-wrapper">

                            <span class="currency-prefix">
                                Rp
                            </span>

                            <input
                                id="bbm"
                                name="bbm"
                                type="text"
                                inputmode="numeric"
                                max="1000000"
                                placeholder="Masukkan jumlah biaya BBM"
                                class="currency-input expense-input"
                                oninput="validateInput(this)">

                        </div>

                    </div>

                    {{-- UANG HARIAN --}}
                    <div class="mb-6">
                        <div class="mb-3 flex items-center justify-between border-b border-slate-100 pb-2">
                            <label class="text-xs font-bold text-slate-800">
                                Uang Harian (Golongan & Destinasi) <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[11px] font-medium text-slate-500">
                                Durasi SPPD: <strong class="text-slate-700">{{ $sppd->durasi ?? 1 }} Hari</strong>
                            </span>
                        </div>

                        <input type="hidden" id="durasi_dinas" value="{{ $sppd->durasi ?? 1 }}">

                        <div class="flex flex-col gap-3">

                            {{-- 1. DINAS --}}
                            <div class="flex items-end justify-between">
                                <div class="flex items-end gap-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-slate-500">Dinas</label>
                                        <div class="currency-wrapper expense-input w-[160px]">
                                            <span class="currency-prefix text-xs">Rp</span>
                                            <input id="dinas_rate" name="uang_dinas" type="text" class="currency-input expense-input text-xs" 
                                                value="{{ number_format($tarif->dinas ?? 0, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <span class="mb-2 text-xs font-semibold text-slate-400">×</span>

                                    <div>
                                        <label class="mb-1 block text-center text-xs font-medium text-slate-500">Hari</label>
                                        <div class="w-[60px]">
                                            <input id="dinas_qty" name="dinas_qty" type="text" 
                                                class="h-[38px] w-full rounded-md border border-slate-200 bg-slate-100 text-center text-xs font-semibold text-slate-600 focus:outline-none" 
                                                value="{{ $sppd->durasi ?? 1 }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-right text-xs font-medium text-slate-500">Subtotal Dinas</label>
                                    <div class="currency-wrapper expense-input w-[220px]">
                                        <span class="currency-prefix text-xs">Rp</span>
                                        <input id="dinas_subtotal" type="text" class="currency-input expense-input text-xs font-bold text-slate-800 bg-slate-50 text-right" value="0" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. MAKAN --}}
                            <div class="flex items-end justify-between">
                                <div class="flex items-end gap-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-slate-500">Makan</label>
                                        <div class="currency-wrapper expense-input w-[160px]">
                                            <span class="currency-prefix text-xs">Rp</span>
                                            <input id="makan_rate" name="uang_makan" type="text" class="currency-input expense-input text-xs" 
                                                value="{{ number_format($tarif->makan ?? 0, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <span class="mb-2 text-xs font-semibold text-slate-400">×</span>

                                    <div>
                                        <label class="mb-1 block text-center text-xs font-medium text-slate-500">Hari</label>
                                        <div class="w-[60px]">
                                            <input id="makan_qty" name="makan_qty" type="text" inputmode="numeric" 
                                                data-max="{{ $sppd->durasi ?? 1 }}"
                                                {{-- oninput="validateInput(this)" --}}
                                                oninput="validateInputQty(this)"
                                                class="h-[38px] w-full rounded-md border border-slate-300 text-center text-xs font-semibold text-slate-700 focus:border-blue-500 focus:outline-none" 
                                                value="0">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-right text-xs font-medium text-slate-500">Subtotal Makan</label>
                                    <div class="currency-wrapper expense-input w-[220px]">
                                        <span class="currency-prefix text-xs">Rp</span>
                                        <input id="makan_subtotal" type="text" class="currency-input expense-input text-xs font-bold text-slate-800 bg-slate-50 text-right" value="0" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. HOTEL --}}
                            <div class="flex items-end justify-between">
                                <div class="flex items-end gap-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-slate-500">Hotel</label>
                                        <div class="currency-wrapper expense-input w-[160px]">
                                            <span class="currency-prefix text-xs">Rp</span>
                                            <input id="hotel_rate" name="uang_hotel" type="text" class="currency-input expense-input text-xs" 
                                                value="{{ number_format($tarif->hotel ?? 0, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <span class="mb-2 text-xs font-semibold text-slate-400">×</span>

                                    <div>
                                        <label class="mb-1 block text-center text-xs font-medium text-slate-500">Malam</label>
                                        <div class="w-[60px]">
                                            <input id="hotel_qty" name="hotel_qty" type="text" inputmode="numeric" 
                                                data-max="{{ $sppd->durasi ?? 1 }}"
                                                oninput="validateInputQty(this)"
                                                class="h-[38px] w-full rounded-md border border-slate-300 text-center text-xs font-semibold text-slate-700 focus:border-blue-500 focus:outline-none" 
                                                value="0">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-right text-xs font-medium text-slate-500">Subtotal Hotel</label>
                                    <div class="currency-wrapper expense-input w-[220px]">
                                        <span class="currency-prefix text-xs">Rp</span>
                                        <input id="hotel_subtotal" type="text" class="currency-input expense-input text-xs font-bold text-slate-800 bg-slate-50 text-right" value="0" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- BIAYA LAINNYA --}}
                    <div>

                        <h3 class="mb-4 text-[13px] font-semibold">
                            Biaya Lainnya (Opsional)
                        </h3>

                        {{-- TRANSPORT LOKAL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Transport Lokal
                            </span>

                            <div class="currency-wrapper expense-input w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="transportLokal"
                                    name="transport_lokal"
                                    type="text"
                                    inputmode="numeric"
                                    max="1000000"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input expense-input"
                                    oninput="validateInput(this)">

                            </div>

                        </div>

                        {{-- VISA --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Visa
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="visa"
                                    name="visa"
                                    type="text"
                                    inputmode="numeric"
                                    max="1000000"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input expense-input"
                                    oninput="validateInput(this)">

                            </div>

                        </div>

                        {{-- FISKAL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Fiskal
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="fiskal"
                                    name="fiskal"
                                    type="text"
                                    inputmode="numeric"
                                    max="1000000"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input expense-input"
                                    oninput="validateInput(this)">

                            </div>

                        </div>

                        {{-- AIRPORT TAX --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Airport Tax
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="airportTax"
                                    name="airport_tax"
                                    type="text"
                                    inputmode="numeric"
                                    max="1000000"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input expense-input"
                                    oninput="validateInput(this)">

                            </div>

                        </div>

                        {{-- LAUNDRY --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Laundry
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <input
                                    id="laundry"
                                    type="text"
                                    class="currency-input text-center readonly-input"
                                    value="Actual"
                                    readonly>

                            </div>

                        </div>

                        {{-- PARKIR & TOLL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">
                            <span class="text-[13px]">Parkir & Toll</span>
                            <div class="currency-wrapper w-[220px]">
                                <span class="currency-prefix">Rp</span>
                                <input
                                    {{-- id="parkirToll"
                                    name="parkir&toll"
                                    type="text" --}}
                                    id="parkir_toll"
                                    name="parkir_toll"
                                    type="text"
                                    inputmode="numeric"
                                    max="1000000"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input expense-input"
                                    oninput="validateInput(this)">
                            </div>
                        </div>

                        {{-- ENTERTAINMENT --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Entertainment
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="entertainment"
                                    name="entertainment"
                                    type="text"
                                    inputmode="numeric"
                                    max="1000000"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input expense-input"
                                    oninput="validateInput(this)">

                            </div>

                        </div>

                        {{-- DLL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            {{-- <input
                                id="dll"
                                name="dll"
                                type="text"
                                placeholder="Dll..."
                                class="w-[120px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[13px] outline-none"> --}}
                            <span class="text-[13px]">
                                Lain-lain
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    {{-- id="biayaLainnya"
                                    name="dll"
                                    type="text" --}}
                                    id="dll"
                                    name="dll"
                                    type="text"
                                    max="1000000"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input expense-input"
                                    oninput="validateInput(this)">

                            </div>

                        </div>

                        {{-- TOTAL --}}
                        <div class="mt-5 flex items-center justify-between border-t border-[#e2e8f0] pt-5">
                            <span class="text-[13px] font-semibold">
                                Total
                            </span>

                            <div class="currency-wrapper w-[220px]">
                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="total"
                                    name="total_biaya"
                                    type="text"
                                    value="0"
                                    class="currency-input readonly-input font-semibold"
                                    readonly>
                            </div>
                        </div>

                        {{-- UANG MUKA --}}
                        <div class="mt-3 flex items-center justify-between">
                            <div>
                                <span class="text-[13px] font-semibold">
                                    Uang Muka
                                </span>

                                <p class="mt-1 text-[10px] text-[#94a3b8]">
                                    Otomatis berdasarkan total perkiraan biaya
                                </p>
                            </div>

                            <div class="currency-wrapper w-[220px]">
                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="uangMuka"
                                    name="uang_muka"
                                    type="text"
                                    value="0"
                                    class="currency-input readonly-input font-semibold"
                                    readonly>
                            </div>
                        </div>
                    </div>

                </section>

                <input type="hidden" name="sppd_id" value="{{ $sppd->id }}">

                {{-- BUTTON --}}
                <div class="button-wrapper flex justify-end gap-3 pb-6 pt-6">

                    <a href="/dashboard"
                        class="rounded-[10px] border border-[#94a3b8] bg-white px-6 py-3 text-[14px] font-semibold text-[#64748b]">
                        Batal
                    </a>

                    <button type="submit"
                        class="flex items-center gap-2 rounded-[10px] bg-[#2563eb] px-6 py-3 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

</body>

@endsection

@push('scripts')

    @vite('resources/js/ilpd/form.js')

@endpush