@extends('layouts.app')

@section('title', 'Form ILPD - SPPD System')

@section('content')

<body class="min-h-screen bg-white text-[#1e293b]">

<div class="min-h-screen">

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen w-full">
    
    {{-- ALERT PESAN SUKSES --}}
    @if (session('success'))
        <div class="mb-5 flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle-2" class="size-5 text-emerald-600"></i>
                <span class="text-[13px] font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
    @endif

    {{-- ALERT PESAN ERROR (TRANSACTION ROLLBACK / EXCEPTION) --}}
    @if (session('error'))
        <div class="mb-5 flex items-center justify-between rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-800 shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-circle" class="size-5 text-rose-600"></i>
                <span class="text-[13px] font-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
    @endif

    {{-- ALERT ERROR VALIDASI INPUT (Misal File Kegedean / Kosong) --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 shadow-sm">
            <div class="mb-1 flex items-center gap-2 font-bold text-[13px] text-amber-800">
                <i data-lucide="alert-triangle" class="size-4 text-amber-600"></i>
                <span>Gagal Memproses Data:</span>
            </div>
            <ul class="ml-6 list-disc text-[12px] text-amber-700 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

            <form id="ilpdForm" action="{{ route('ilpd.approve', $ilpd->id) }}" method="POST" enctype="multipart/form-data">
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
                            value="{{ $ilpd->sppd->kota->name ?? '-' }}"
                            class="form-input flex-1"
                            readonly>
                    </div>

                    {{-- LAMA PERJALANAN --}}
                    <div class="mb-4">
                        <label class="mb-1.5 block text-[13px] font-semibold">
                            Lama Perjalanan <span class="text-red-500">*</span>
                        </label>

                        <div class="date-wrapper flex items-center gap-4">
                            {{-- TANGGAL MULAI --}}
                            <div class="flex flex-1 items-center rounded-lg border border-[#cbd5e1] px-3.5 py-2.5">
                                <input
                                    id="tanggalMulai"
                                    name="tanggal_awal"
                                    type="date"
                                    class="w-full border-0 text-[14px] outline-none"
                                    value="{{ isset($ilpd->tanggal_awal) ? \Carbon\Carbon::parse($ilpd->tanggal_awal)->format('Y-m-d') : '-' }}"
                                    data-durasi="{{ $ilpd->sppd->durasi ?? $sppd->durasi ?? 1 }}"
                                    onchange="hitungSemua()">
                            </div>

                            <span class="text-[14px] font-semibold text-[#64748b]">s/d</span>

                            {{-- TANGGAL SELESAI (READONLY/DISABLED KARENA OTOMATIS) --}}
                            <div class="flex flex-1 items-center rounded-lg border border-[#cbd5e1] bg-gray-50 px-3.5 py-2.5">
                                <input
                                    id="tanggalSelesai"
                                    name="tanggal_akhir"
                                    type="date"
                                    class="w-full border-0 bg-transparent text-[14px] outline-none"
                                    value="{{ isset($ilpd->tanggal_akhir) ? \Carbon\Carbon::parse($ilpd->tanggal_akhir)->format('Y-m-d') : '-' }}"
                                    disabled>
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
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="transport_id[]"
                                        value="{{ $transport->id }}"
                                        class="size-[18px] accent-[#2563eb]"
                                        {{ in_array($transport->id, $sppd->transport_id ?? []) ? 'checked' : '' }}>
                                    <span class="text-[14px]">
                                        {{ $transport->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        
                        <div class="mt-2 flex items-center gap-3">

                            <label class="flex items-center gap-2">

                                <input 
                                    type="checkbox"
                                    class="size-[18px] accent-[#2563eb]"
                                    {{ !empty($sppd->transport_lainnya) ? 'checked' : '' }}>
                                <span class="text-[14px]">
                                    Lainnya
                                </span>

                            </label>

                            <input
                                type="text"
                                name="transport_lainnya"
                                value="{{ $sppd->transport_lainnya }}"
                                placeholder="Sebutkan..."
                                class="w-[180px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[12px] outline-none">
                        </div>
                    </div>

                    {{-- KEPERLUAN --}}
                    <div class="mb-4">
                        <label class="mb-2 block text-[13px] font-semibold">
                            Keperluan <span class="text-red-500">*</span>
                        </label>

                        <div class="keperluan-grid grid grid-cols-3 gap-x-6 gap-y-3">
                            @foreach ($keperluans as $keperluan)
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="keperluan_id[]"
                                        value="{{ $keperluan->id }}"
                                        class="size-[18px] accent-[#2563eb]"
                                        {{ in_array($keperluan->id, $sppd->keperluan_id ?? []) ? 'checked' : '' }}>
                                    <span class="text-[14px] text-slate-800">
                                        {{ $keperluan->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        
                        {{-- Keperluan Lainnya --}}
                        <div class="mt-2 flex items-center gap-3">
                            <label class="flex items-center gap-2">
                                <input 
                                    type="checkbox"
                                    class="size-[18px] accent-[#2563eb]"
                                    {{ !empty($sppd->keperluan_lainnya) ? 'checked' : '' }}>
                                <span class="text-[14px] text-slate-800">
                                    Lainnya
                                </span>
                            </label>

                            <input
                                type="text"
                                name="keperluan_lainnya"
                                value="{{ $sppd->keperluan_lainnya }}"
                                placeholder="Sebutkan..."
                                class="w-[180px] rounded-md border border-[#cbd5e1] bg-slate-50 px-3 py-2 text-[12px] outline-none text-slate-800 cursor-default">
                        </div>
                    </div>

                    {{-- TUGAS --}}
                    <div>
                        <label for="tugas" class="mb-1.5 block text-[13px] font-semibold text-[#0f172a]">
                            Tugas <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            id="tugas"
                            readonly
                            rows="4"
                            class="w-full rounded-xl border border-[#cbd5e1] bg-[#f8fafc] p-3 text-[13px] leading-relaxed text-[#0f172a] outline-none resize-none cursor-default"
                            placeholder="Tidak ada detail tugas.">{{ $ilpd->sppd->tugas ?? '' }}</textarea>

                        <p class="mt-1 text-[11px] text-[#64748b]">
                            Rincian tugas yang diisikan oleh pemohon.
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

                    <div class="mb-5">
                        <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                            Tiket (Opsional)
                        </label>

                        {{-- DROPZONE BOX --}}
                        <div class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-[#2563eb]/40 bg-[#f8fafc] px-6 py-8 text-center transition-all hover:border-[#2563eb] hover:bg-[#eff6ff]/30">
                            
                            {{-- INPUT FILE TERSEMBUNYI --}}
                            <input 
                                type="file" 
                                id="tiket_file" 
                                name="tiket_file" 
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="absolute inset-0 z-10 cursor-pointer opacity-0" 
                                onchange="updateFileName(this)"
                                oninvalid="this.setCustomValidity('Silahkan upload tiket')"
                                onkeydown="cekkunci(event)"
                            />

                            {{-- IKON CLOUD UPLOAD --}}
                            <div class="mb-3 flex size-10 items-center justify-center rounded-full text-[#2563eb]">
                                <i data-lucide="cloud-upload" class="size-8"></i>
                            </div>

                            {{-- TEKS INSTRUKSI --}}
                            <p id="file-label" class="text-[14px] font-bold text-[#0f172a]">
                                Upload tiket perjalanan
                            </p>
                            <p class="mt-0.5 text-[11px] text-[#64748b]">
                                Format: PDF, JPG, JPEG, PNG (Maks. 5MB)
                            </p>

                            {{-- TOMBOL PILIH FILE --}}
                            <div class="mt-4">
                                <span class="inline-flex items-center justify-center rounded-xl border border-[#2563eb] bg-white px-5 py-2 text-[12px] font-semibold text-[#2563eb] shadow-sm transition-colors hover:bg-[#eff6ff]">
                                    Pilih File
                                </span>
                            </div>
                        </div>
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
                                placeholder="Masukkan jumlah biaya BBM"
                                class="currency-input expense-input"
                                oninput="formatRupiah(this); hitungTotal()">

                        </div>

                    </div>

                    {{-- UANG HARIAN --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm mb-6">
                        {{-- Header Section --}}
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h4 class="text-[14px] font-bold text-slate-800">Rincian Uang Harian & Penginapan</h4>
                                <p class="mt-0.5 text-[11px] text-[#64748b]">
                                    Nilai di bawah dihitung otomatis dari Master Data berdasarkan Golongan Staff dan kota tujuan.
                                </p>
                            </div>

                            {{-- Switch / Tombol untuk Mengaktifkan Mode Edit Khusus GA --}}
                            <button type="button" id="btnToggleEdit" onclick="toggleAdjustTarif()" 
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-slate-50 px-3 py-1.5 text-[12px] font-semibold text-slate-700 hover:bg-slate-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                <span>Sesuaikan Tarif</span>
                            </button>
                        </div>

                        {{-- Dropdown Opsional (Awalnya Tersembunyi) --}}
                        <div id="wrapperDropdownGolongan" class="mb-4 hidden rounded-lg bg-amber-50 p-3 border border-amber-200">
                            <label class="mb-1 block text-[12px] font-semibold text-amber-900">
                                Pilih Tarif Golongan Pengganti (Override):
                            </label>
                            <select id="selectGolonganOverride" onchange="applyTarifOverride(this)" 
                                    class="w-full rounded-md border border-amber-300 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">-- Pilih Golongan Penyesuaian --</option>
                                @foreach($tarifs as $tarif)
                                    <option value="{{ $tarif->id }}" 
                                            data-dinas="{{ $tarif->dinas }}" 
                                            data-makan="{{ $tarif->makan }}" 
                                            data-hotel="{{ $tarif->hotel }}"
                                            {{ (isset($golonganId) && $tarif->golongan_id == $golonganId) ? 'selected' : '' }}>
                                        {{ $tarif->golongan->nama ?? 'Golongan '.$tarif->golongan_id }} — Tarif: Rp {{ number_format($tarif->dinas) }} - {{ number_format($tarif->makan) }} - {{ number_format($tarif->hotel) }} / hari
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Form Input 4 Kolom --}}
                        <div class="grid grid-cols-4 gap-3">

                            {{-- DINAS --}}
                            <div>
                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    Dinas / Hari
                                </label>
                                <div class="currency-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input
                                        id="inputDinas"
                                        name="uang_dinas"
                                        type="text"
                                        class="currency-input input-biaya"
                                        value="{{ number_format($ilpd->detail_ilpd->tarif->dinas ?? 0, 0, ',', '.') }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- MAKAN --}}
                            <div>
                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    Makan / Hari
                                </label>
                                <div class="currency-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input
                                        id="inputMakan"
                                        name="uang_makan"
                                        type="text"
                                        class="currency-input input-biaya"
                                        value="{{ number_format($ilpd->detail_ilpd->tarif->makan ?? 0, 0, ',', '.') }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- HOTEL --}}
                            <div>
                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    Hotel / Malam
                                </label>
                                <div class="currency-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input
                                        id="inputHotel"
                                        name="uang_hotel"
                                        type="text"
                                        class="currency-input input-biaya"
                                        value="{{ number_format($ilpd->detail_ilpd->tarif->hotel ?? 0, 0, ',', '.') }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- LAUNDRY --}}
                            <div>
                                <label class="mb-1.5 block text-[12px] font-semibold text-[#64748b]">
                                    Laundry
                                </label>
                                <div class="currency-wrapper">
                                    <input
                                        id="laundry"
                                        type="text"
                                        class="currency-input readonly-input"
                                        value="Actual"
                                        readonly>
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
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

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
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

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
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

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
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

                            </div>

                        </div>

                        {{-- PARKIR & TOLL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <span class="text-[13px]">
                                Parkir & Toll
                            </span>

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="parkirToll"
                                    name="parkir&toll"
                                    type="text"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

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
                                    type="text"
                                    id="entertainment"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

                            </div>

                        </div>

                        {{-- DLL --}}
                        <div class="expense-row mb-3 flex items-center justify-between">

                            <input
                                id="dll"
                                name="dll"
                                type="text"
                                placeholder="Dll..."
                                class="w-[120px] rounded-md border border-[#cbd5e1] px-3 py-2 text-[13px] outline-none">

                            <div class="currency-wrapper w-[220px]">

                                <span class="currency-prefix">
                                    Rp
                                </span>

                                <input
                                    id="biayaLainnya"
                                    type="text"
                                    placeholder="Masukkan jumlah"
                                    class="currency-input"
                                    oninput="formatRupiah(this); hitungTotal()">

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
                                    value="{{ number_format($ilpd->detailIlpd->total ?? 0, 0, ',', '.') }}"
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
                                    value="{{ number_format($ilpd->detail_ilpd->total ?? 0, 0, ',', '.') }}"
                                    class="currency-input readonly-input font-semibold"
                                    readonly>
                            </div>
                        </div>
                    </div>

                </section>

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
{{-- </html> --}}

@endsection

@push('scripts')

    @vite('resources/js/ilpd/approve.js')

@endpush