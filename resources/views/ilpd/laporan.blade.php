@extends('layouts.app')

@section('title', 'Dashboard - SPPD System')

@section('content')
<!-- CSS Khusus Sembunyikan Arrow/Spinner pada Input Number -->
<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-6">
    <form action="{{ route('laporan.report', $ilpd->id) }}" method="POST" class="space-y-6">
        @csrf

        @php
            $komponenBiaya = [
                'makan'           => 'Makan',
                'hotel'           => 'Hotel',
                'dinas'           => 'Dinas',
                'laundry'         => 'Laundry',
                'bbm'             => 'BBM',
                'transport_lokal' => 'Transport Lokal',
                'visa'            => 'Visa',
                'fiskal'          => 'Fiskal',
                'airport_tax'     => 'Airport Tax',
                'parkir&toll'     => 'Parkir & Toll',
                'enteriment'      => 'Entertainment',
                'dll'             => 'Lain-lain (DLL)',
            ];

            $realisasiData =$laporan->realisasi_biaya ?? [];
            $uangMukaVal   = (float) ($ilpd->detail_ilpd->uang_muka ?? 0);
        @endphp

        <!-- Card Container Utama -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            
            <!-- Header Section -->
            <div class="bg-slate-50/80 px-6 py-5 border-b border-slate-200">
                <h3 class="text-base font-bold text-slate-800 tracking-wide uppercase">
                    Rincian Biaya Perjalanan Dinas (Realisasi & Settlement)
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Bandingkan estimasi biaya yang disetujui GA dengan realisasi pengeluaran aktual di lapangan.
                </p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Tabel Komponen Biaya -->
                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="w-full border-collapse text-left text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-700 border-b border-slate-200">
                                <th class="p-3 font-semibold uppercase tracking-wider">Komponen Biaya</th>
                                <th class="p-3 font-semibold text-right uppercase tracking-wider">
                                    Kolom 1: Estimasi / ACC GA<br>
                                    <span class="text-[10px] font-normal text-slate-400 capitalize">(Readonly)</span>
                                </th>
                                <th class="p-3 font-semibold text-right uppercase tracking-wider">
                                    Kolom 2: Realisasi Aktual<br>
                                    <span class="text-[10px] font-normal text-slate-400 capitalize">(Input Staff)</span>
                                </th>
                                <th class="p-3 font-semibold text-right uppercase tracking-wider">
                                    Kolom 3: Selisih / Variance<br>
                                    <span class="text-[10px] font-normal text-slate-400 capitalize">(Live Calculation)</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($komponenBiaya as $key =>$label)
                                @php
                                    // Cast ke float secara eksplisit untuk mencegah stringTypeError di number_format
                                    $estimasi  = (float) ($ilpd->detail_ilpd->$key ?? 0);
                                    $realisasi = isset($realisasiData[$key]) ? (float)$realisasiData[$key] :$estimasi;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <!-- Label Komponen -->
                                    <td class="p-3 font-medium text-slate-700 border-r border-slate-100">
                                        {{ $label }}
                                    </td>

                                    <!-- Input Estimasi (Readonly - Parsed Rupiah) -->
                                    <td class="p-3 text-right border-r border-slate-100">
                                        <input type="hidden" class="estimasi-raw-value" value="{{ $estimasi }}">
                                        <input type="text" 
                                               class="estimasi-input w-40 rounded-md border border-slate-200 bg-slate-100 p-2 text-right font-semibold text-slate-600 focus:outline-none" 
                                               value="Rp {{ number_format($estimasi, 0, ',', '.') }}" 
                                               readonly>
                                    </td>

                                    <!-- Input Realisasi (Editable) -->
                                    <td class="p-3 text-right border-r border-slate-100">
                                        <input type="hidden" name="realisasi[{{ $key }}]" id="hidden_realisasi_{{ $key }}" value="{{ $realisasi }}">
                                        <input type="text" 
                                               class="realisasi-formatted-input w-40 rounded-md border border-blue-300 bg-white p-2 text-right font-bold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" 
                                               data-key="{{ $key }}"
                                               value="{{ number_format($realisasi, 0, ',', '.') }}" 
                                               oninput="handleRupiahInput(this); hitungSelisih();">
                                    </td>

                                    <!-- Output Selisih (Live Variance) -->
                                    <td class="p-3 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <span class="selisih-text font-bold text-slate-700" id="text-selisih-{{ $key }}">
                                                Rp 0
                                            </span>
                                            <span class="selisih-badge rounded px-1.5 py-0.5 text-[10px] font-bold" id="badge-selisih-{{ $key }}">
                                                (Pas)
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <!-- Total Row -->
                            <tr class="border-t-2 border-slate-300 bg-slate-50/90 font-bold text-slate-800">
                                <td class="p-3 text-xs uppercase tracking-wider">Total Biaya</td>
                                <td class="p-3 text-right text-xs" id="total-estimasi">Rp 0</td>
                                <td class="p-3 text-right text-xs text-blue-600" id="total-realisasi">Rp 0</td>
                                <td class="p-3 text-right text-xs" id="total-selisih">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pemisah Garis Tipis -->
                <hr class="border-slate-200">

                <!-- Ringkasan Keuangan Settlement -->
                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-3">Ringkasan Penyelesaian (Settlement)</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                        <div class="p-3 rounded-lg bg-white border border-slate-200">
                            <label class="block text-[10px] font-bold uppercase text-slate-400">Total Estimasi GA</label>
                            <input type="text" id="summary_total_perkiraan" readonly class="mt-1 w-full bg-transparent font-bold text-slate-700 text-sm focus:outline-none" value="Rp 0">
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-slate-200 focus-within:border-blue-400 transition">
                            <label class="block text-[10px] font-bold uppercase text-slate-400">Uang Muka (Cashdiv)</label>
                            <input type="hidden" name="uang_muka" id="hidden_uang_muka" value="{{ $uangMukaVal }}">
                            <input type="text" id="input_uang_muka_formatted" class="mt-1 w-full bg-transparent font-bold text-slate-800 text-sm focus:outline-none" value="{{ number_format($uangMukaVal, 0, ',', '.') }}" oninput="handleRupiahInput(this, 'hidden_uang_muka'); hitungSelisih();">
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-slate-200">
                            <label class="block text-[10px] font-bold uppercase text-slate-400">Total Realisasi</label>
                            <input type="text" id="summary_total_realisasi" readonly class="mt-1 w-full bg-transparent font-bold text-blue-600 text-sm focus:outline-none" value="Rp 0">
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-slate-200">
                            <label class="block text-[10px] font-bold uppercase text-slate-400">Status Settlement</label>
                            <input type="text" id="summary_selisih_akhir" readonly class="mt-1 w-full bg-transparent font-extrabold text-xs focus:outline-none" value="Rp 0">
                        </div>
                    </div>
                </div>

                <!-- Hidden Inputs untuk Controller -->
                <input type="hidden" name="total_realisasi" id="hidden_total_realisasi">
                <input type="hidden" name="total_dibayar" id="hidden_total_dibayar">
                <input type="hidden" name="selisih" id="hidden_selisih">
                <input type="hidden" name="keterangan" id="hidden_keterangan">
                {{-- <input type="hidden" name="status" value="submitted"> --}}

                <!-- Pemisah Garis Tipis -->
                <hr class="border-slate-200">

                <!-- Laporan Singkat -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Laporan Singkat Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="laporan_1" rows="3" class="w-full rounded-lg border border-slate-300 p-3 text-xs text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" placeholder="Jelaskan ringkasan hasil kegiatan perjalanan dinas...">{{ $laporan->laporan_singkat_1 ?? '' }}</textarea>
                        {{-- <textarea name="laporan_singkat_1" rows="3" class="w-full rounded-lg border border-slate-300 p-3 text-xs text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" placeholder="Jelaskan ringkasan hasil kegiatan perjalanan dinas..." required>{{ $laporan->laporan_singkat_1 ?? '' }}</textarea> --}}
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Laporan Singkat Tambahan / Kendala Lapangan
                        </label>
                        <textarea name="laporan_2" rows="2" class="w-full rounded-lg border border-slate-300 p-3 text-xs text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" placeholder="Catatan opsional atau kendala selama di lapangan...">{{ $laporan->laporan_singkat_2 ?? '' }}</textarea>
                    </div>
                </div>

            </div>

            <!-- Footer Action -->
            <div class="bg-slate-50/80 px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 transition">
                    Simpan
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- <script>
    // Format Angka ke Tampilan Rupiah
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
    }

    // Convert string angka berformat Rupiah kembali ke Float murni
    function cleanNumber(value) {
        if (!value) return 0;
        let clean = value.toString().replace(/[^0-9]/g, '');
        return parseFloat(clean) || 0;
    }

    // Handle Input ketik Rupiah secara dinamis
    function handleRupiahInput(element, targetHiddenId = null) {
        let rawVal = cleanNumber(element.value);
        
        // Simpan angka murni ke hidden input jika dispesifikasikan, atau ke hidden input realisasi
        if (targetHiddenId) {
            document.getElementById(targetHiddenId).value = rawVal;
        } else {
            let key = element.getAttribute('data-key');
            if (key) {
                document.getElementById(`hidden_realisasi_${key}`).value = rawVal;
            }
        }

        // Format tampilan input dengan pemisah ribuan
        if (rawVal === 0 && element.value === '') {
            element.value = '';
        } else {
            element.value = rawVal.toLocaleString('id-ID');
        }
    }

    // Kalkulator Utama
    function hitungSelisih() {
        let grandTotalEstimasi = 0;
        let grandTotalRealisasi = 0;

        const estimasiElements = document.querySelectorAll('.estimasi-raw-value');
        const realisasiFormattedInputs = document.querySelectorAll('.realisasi-formatted-input');

        realisasiFormattedInputs.forEach((realisasiEl, index) => {
            const key = realisasiEl.getAttribute('data-key');
            const estimasiVal = parseFloat(estimasiElements[index].value) || 0;
            const realisasiVal = cleanNumber(realisasiEl.value);

            // Selisih per baris = Estimasi - Realisasi
            const variance = estimasiVal - realisasiVal;

            grandTotalEstimasi += estimasiVal;
            grandTotalRealisasi += realisasiVal;

            // Update UI Selisih
            const textEl = document.getElementById(`text-selisih-${key}`);
            const badgeEl = document.getElementById(`badge-selisih-${key}`);

            if (variance > 0) {
                textEl.innerText = `+ ${formatRupiah(variance)}`;
                textEl.className = 'selisih-text font-bold text-emerald-600';
                badgeEl.innerText = '(Hemat)';
                badgeEl.className = 'selisih-badge rounded bg-emerald-100 text-emerald-700 px-1.5 py-0.5 text-[10px] font-bold';
            } else if (variance < 0) {
                textEl.innerText = `${formatRupiah(variance)}`;
                textEl.className = 'selisih-text font-bold text-rose-600';
                badgeEl.innerText = '(Nombok)';
                badgeEl.className = 'selisih-badge rounded bg-rose-100 text-rose-700 px-1.5 py-0.5 text-[10px] font-bold';
            } else {
                textEl.innerText = 'Rp 0';
                textEl.className = 'selisih-text font-bold text-slate-500';
                badgeEl.innerText = '(Pas)';
                badgeEl.className = 'selisih-badge rounded bg-slate-100 text-slate-600 px-1.5 py-0.5 text-[10px] font-bold';
            }
        });

        // Hitung Settlement Akhir
        const totalVariance = grandTotalEstimasi - grandTotalRealisasi;
        const uangMuka = cleanNumber(document.getElementById('input_uang_muka_formatted').value);
        // const sisaSettlement = grandTotalRealisasi - uangMuka;
        const sisaSettlement = uangMuka - grandTotalRealisasi;

        // Update Tabel Footer
        document.getElementById('total-estimasi').innerText = formatRupiah(grandTotalEstimasi);
        document.getElementById('total-realisasi').innerText = formatRupiah(grandTotalRealisasi);
        document.getElementById('total-selisih').innerText = formatRupiah(totalVariance);

        // Update Card Summary Settlement
        document.getElementById('summary_total_perkiraan').value = formatRupiah(grandTotalEstimasi);
        document.getElementById('summary_total_realisasi').value = formatRupiah(grandTotalRealisasi);
        
        const summarySelisih = document.getElementById('summary_selisih_akhir');
        if (sisaSettlement > 0) {
            summarySelisih.value = `Bayar: ${formatRupiah(sisaSettlement)}`;
            summarySelisih.className = 'mt-1 w-full bg-transparent font-extrabold text-rose-600 focus:outline-none';
        } else if (sisaSettlement < 0) {
            summarySelisih.value = `Kembalikan: ${formatRupiah(Math.abs(sisaSettlement))}`;
            summarySelisih.className = 'mt-1 w-full bg-transparent font-extrabold text-emerald-600 focus:outline-none';
        } else {
            summarySelisih.value = 'Lunas / Pas';
            summarySelisih.className = 'mt-1 w-full bg-transparent font-extrabold text-slate-600 focus:outline-none';
        }

        // Set Nilai Murni ke Hidden Inputs Controller
        document.getElementById('hidden_total_realisasi').value = grandTotalRealisasi;
        document.getElementById('hidden_total_dibayar').value = grandTotalRealisasi;
        document.getElementById('hidden_selisih').value = sisaSettlement;
    }

    // Jalankan kalkulator saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', hitungSelisih);
</script> --}}
    @vite('resources/js/ilpd/laporan.js')
@endpush