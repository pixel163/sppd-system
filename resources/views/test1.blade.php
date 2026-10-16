@extends('layouts.app')

@section('title', 'Dashboard - SPPD System')

@section('content')
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
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
                'bbm'             => 'BBM',
                'transport_lokal' => 'Transport Lokal',
                'visa'            => 'Visa',
                'fiskal'          => 'Fiskal',
                'airport_tax'     => 'Airport Tax',
                'laundry'         => 'Laundry',
                'parkir_toll'     => 'Parkir & Toll',
                'entertainment'   => 'Entertainment',
                'dll'             => 'Lain-lain (DLL)',
            ];

            $realisasiData = $laporan->realisasi_biaya ?? [];
            $totalEstimasi = (float) ($ilpd->detail_ilpd->total ?? 0);
            $uangMukaVal   = (float) ($ilpd->detail_ilpd->uang_muka ?? 0);
        @endphp

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            <div class="bg-slate-50/80 px-6 py-5 border-b border-slate-200">
                <h3 class="text-base font-bold text-slate-800 tracking-wide uppercase">
                    Rincian Biaya Perjalanan Dinas (Realisasi & Settlement)
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Bandingkan estimasi biaya yang disetujui GA dengan realisasi pengeluaran aktual di lapangan.
                </p>
            </div>

            <div class="p-6 space-y-6">
                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="w-full border-collapse text-left text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-700 border-b border-slate-200">
                                <th class="p-3 font-semibold uppercase tracking-wider">Komponen Biaya</th>
                                <th class="p-3 font-semibold text-right uppercase tracking-wider">
                                    Perkiraan Biaya<br>
                                </th>
                                <th class="p-3 font-semibold text-right uppercase tracking-wider">
                                    Realisasi Biaya<br>
                                </th>
                                <th class="p-3 font-semibold text-right uppercase tracking-wider">
                                    Selisih / Total<br>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($komponenBiaya as $key => $label)
                                @php
                                    $estimasi  = (float) ($ilpd->detail_ilpd->$key ?? 0);
                                    $realisasi = isset($realisasiData[$key]) ? (float)$realisasiData[$key] : $estimasi;
                                    
                                    $keyLower = strtolower($key);
                                    $isMakan  = str_contains($keyLower, 'makan');
                                    $isHotel  = str_contains($keyLower, 'hotel');
                                    $isDinas  = str_contains($keyLower, 'dinas');
                                    $isLaundry = str_contains($keyLower, 'laundry');

                                    $multiplierValue = 0;
                                    $multiplierLabel = '';

                                    if ($isDinas) {
                                        $multiplierValue = $ilpd->sppd->durasi ?? $ilpd->durasi ?? 0;
                                        $multiplierLabel = 'Hari';
                                    } elseif ($isMakan) {
                                        $multiplierValue = $ilpd->detail_ilpd->makan_qty ?? 0;
                                        $multiplierLabel = 'Hari';
                                    } elseif ($isHotel) {
                                        $multiplierValue = $ilpd->detail_ilpd->hotel_qty ?? 0;
                                        $multiplierLabel = 'Malam';
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3 font-medium text-slate-700 border-r border-slate-100">
                                        {{ $label }}
                                    </td>

                                    <td class="p-3 text-right border-r border-slate-100">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <input type="hidden" class="estimasi-raw-value" value="{{ $estimasi }}">
                                            
                                            @if($isMakan || $isHotel || $isDinas)
                                                <input type="text" 
                                                    class="estimasi-input w-32 rounded-md border border-slate-200 bg-slate-100 p-2 text-right font-semibold text-slate-600 focus:outline-none text-sm" 
                                                    value="Rp {{ number_format($estimasi, 0, ',', '.') }}" 
                                                    readonly>
                                                <span class="text-xs text-slate-400 font-medium">×</span>
                                                <input type="text" 
                                                    class="estimasi-multiplier w-14 rounded-md border border-slate-200 bg-slate-100 p-2 text-center text-sm font-semibold text-slate-600" 
                                                    value="{{ $multiplierValue }}" 
                                                    readonly title="{{ $multiplierLabel }}">
                                            @elseif($isLaundry)
                                                <input type="text" 
                                                    class="estimasi-input w-51 rounded-md border border-slate-200 bg-slate-100 p-2 text-center font-semibold text-slate-600 focus:outline-none text-sm" 
                                                    value="Actual" readonly>
                                            @else
                                                <input type="text" 
                                                    class="estimasi-input w-51 rounded-md border border-slate-200 bg-slate-100 p-2 text-right font-semibold text-slate-600 focus:outline-none text-sm" 
                                                    value="Rp {{ number_format($estimasi, 0, ',', '.') }}" 
                                                    readonly>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="p-3 text-right border-r border-slate-100">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <input type="hidden" name="realisasi[{{ $key }}]" id="hidden_realisasi_{{ $key }}" value="{{ $realisasi }}">
                                            
                                            @if($isMakan || $isHotel)
                                                <input type="text" 
                                                    class="realisasi-formatted-input w-32 rounded-md border border-blue-300 bg-white p-2 text-right font-bold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-sm" 
                                                    data-key="{{ $key }}"
                                                    value="{{ number_format($realisasi, 0, ',', '.') }}" 
                                                    oninput="handleRupiahInput(this); hitungSelisih();">
                                                <span class="text-xs text-slate-400 font-medium">×</span>
                                                <input type="text"
                                                    min="0" 
                                                    class="realisasi-multiplier w-14 rounded-md border border-slate-200 bg-white p-2 text-center text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100" 
                                                    value="{{ $multiplierValue }}" title="Ubah {{ $multiplierLabel }}">
                                            @elseif($isDinas)
                                                <input type="text" 
                                                    class="realisasi-formatted-input w-32 rounded-md border border-slate-200 bg-slate-100 p-2 text-right font-semibold text-slate-600 focus:outline-none text-sm" 
                                                    data-key="{{ $key }}"
                                                    value="{{ number_format($realisasi, 0, ',', '.') }}" 
                                                    readonly>
                                                <span class="text-xs text-slate-400 font-medium">×</span>
                                                <input type="text" 
                                                    class="realisasi-multiplier w-14 rounded-md border border-slate-200 bg-slate-100 p-2 text-center text-sm font-semibold text-slate-600" 
                                                    value="{{ $multiplierValue }}" readonly title="{{ $multiplierLabel }}">
                                            @elseif($isLaundry)
                                                <input type="text" 
                                                    class="realisasi-formatted-input w-51 rounded-md border border-slate-200 bg-slate-100 p-2 text-center font-semibold text-slate-600 focus:outline-none text-sm" 
                                                    data-key="{{ $key }}"
                                                    value="{{ $realisasi > 0 ? number_format($realisasi, 0, ',', '.') : 'Actual' }}" 
                                                    readonly>
                                            @else
                                                <input type="text" 
                                                    class="realisasi-formatted-input w-51 rounded-md border border-blue-300 bg-white p-2 text-right font-bold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-sm" 
                                                    data-key="{{ $key }}"
                                                    value="{{ number_format($realisasi, 0, ',', '.') }}" 
                                                    oninput="handleRupiahInput(this); hitungSelisih();">
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-3 text-right">
                                        @if($isLaundry)
                                            <span class="font-semibold text-slate-500 text-xs italic">Actual</span>
                                        @else
                                            <div class="flex items-center justify-end gap-1.5">
                                                <span class="selisih-text font-bold text-slate-700 text-sm" id="text-selisih-{{ $key }}">
                                                    Rp 0
                                                </span>
                                                <span class="selisih-badge rounded px-1.5 py-0.5 text-[10px] font-bold" id="badge-selisih-{{ $key }}">
                                                    (Pas)
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-300 bg-slate-50/90 font-bold text-slate-800">
                                <td class="p-3 text-xs uppercase tracking-wider">Total Biaya</td>
                                <td class="p-3 text-right text-xs" id="total-estimasi">
                                    Rp {{ number_format($ilpd->detail_ilpd->total ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-right text-xs text-blue-600" id="total-realisasi">Rp 0</td>
                                <td class="p-3 text-right text-xs" id="total-selisih">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <hr class="border-slate-200">

                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-3">Ringkasan Penyelesaian (Settlement)</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                        <div class="p-3 rounded-lg bg-white border border-slate-200 focus-within:border-blue-400 transition">
                            <label class="block text-[10px] font-bold uppercase text-slate-400">Total Estimasi GA</label>
                            <input type="text" id="summary_total_perkiraan" readonly class="mt-1 w-full bg-transparent font-bold text-slate-800 text-sm focus:outline-none" value="{{ number_format($totalEstimasi, 0, ',', '.') }}">
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-slate-200 focus-within:border-blue-400 transition">
                            <label class="block text-[10px] font-bold uppercase text-slate-400">Uang Muka</label>
                            <input type="hidden" name="uang_muka" id="hidden_uang_muka" value="{{ $uangMukaVal }}">
                            <input type="text" id="input_uang_muka_formatted" readonly class="mt-1 w-full bg-transparent font-bold text-slate-800 text-sm focus:outline-none" value="{{ number_format($uangMukaVal, 0, ',', '.') }}" oninput="handleRupiahInput(this, 'hidden_uang_muka'); hitungSelisih();">
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

                <input type="hidden" name="total_realisasi" id="hidden_total_realisasi">
                <input type="hidden" name="total_dibayar" id="hidden_total_dibayar">
                <input type="hidden" name="selisih" id="hidden_selisih">
                <input type="hidden" name="keterangan" id="hidden_keterangan">

                <hr class="border-slate-200">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Laporan Singkat Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="laporan_1" rows="3" class="w-full rounded-lg border border-slate-300 p-3 text-xs text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" placeholder="Jelaskan ringkasan hasil kegiatan perjalanan dinas..." required>{{ $laporan->laporan_singkat_1 ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Laporan Singkat Hasil Perjalanan Dinas
                        </label>
                        <textarea name="laporan_2" rows="2" class="w-full rounded-lg border border-slate-300 p-3 text-xs text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" placeholder="Catatan opsional atau kendala selama di lapangan...">{{ $laporan->laporan_singkat_2 ?? '' }}</textarea>
                    </div>
                </div>
            </div>

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
    @vite('resources/js/ilpd/laporan.js')
@endpush