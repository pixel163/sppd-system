// ============================================================
// HELPER & UTILITY FUNCTIONS
// ============================================================

// Format Angka ke Tampilan Rupiah (contoh: Rp 100.000)
function formatRupiah(number) {
    if (isNaN(number) || number === null) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(number);
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
        const targetEl = document.getElementById(targetHiddenId);
        if (targetEl) targetEl.value = rawVal;
    } else {
        let key = element.getAttribute('data-key');
        if (key) {
            const hiddenEl = document.getElementById(`hidden_realisasi_${key}`);
            if (hiddenEl) hiddenEl.value = rawVal;
        }
    }

    // Format tampilan input dengan pemisah ribuan
    if (rawVal === 0 && element.value === '') {
        element.value = '';
    } else {
        element.value = rawVal.toLocaleString('id-ID');
    }
}

// Ekspos ke global window agar jika ada HTML yang panggil via attribute `oninput` tetap tidak error
window.formatRupiah = formatRupiah;
window.cleanNumber = cleanNumber;
window.handleRupiahInput = handleRupiahInput;

// ============================================================
// KALKULATOR UTAMA
// ============================================================

function hitungSelisih() {
    let grandTotalEstimasi = 0;
    let grandTotalRealisasi = 0;

    const estimasiElements = document.querySelectorAll('.estimasi-raw-value');
    const realisasiFormattedInputs = document.querySelectorAll('.realisasi-formatted-input');

    realisasiFormattedInputs.forEach((realisasiEl, index) => {
        const key = realisasiEl.getAttribute('data-key');
        const estimasiVal = parseFloat(estimasiElements[index]?.value) || 0;
        const realisasiVal = cleanNumber(realisasiEl.value);

        // Selisih per baris = Estimasi - Realisasi
        const variance = estimasiVal - realisasiVal;

        grandTotalEstimasi += estimasiVal;
        grandTotalRealisasi += realisasiVal;

        // Update UI Selisih
        const textEl = document.getElementById(`text-selisih-${key}`);
        const badgeEl = document.getElementById(`badge-selisih-${key}`);

        if (textEl && badgeEl) {
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
        }
    });

    // Hitung Settlement Akhir
    const totalVariance = grandTotalEstimasi - grandTotalRealisasi;
    const uangMukaInput = document.getElementById('input_uang_muka_formatted');
    const uangMuka = uangMukaInput ? cleanNumber(uangMukaInput.value) : 0;
    const sisaSettlement = uangMuka - grandTotalRealisasi;

    // Helper aman untuk set text/value
    const setElementText = (id, text) => {
        const el = document.getElementById(id);
        if (el) el.innerText = text;
    };

    const setElementValue = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.value = val;
    };

    // Update Tabel Footer
    setElementText('total-estimasi', formatRupiah(grandTotalEstimasi));
    setElementText('total-realisasi', formatRupiah(grandTotalRealisasi));
    setElementText('total-selisih', formatRupiah(totalVariance));

    // Update Card Summary Settlement
    setElementValue('summary_total_perkiraan', formatRupiah(grandTotalEstimasi));
    setElementValue('summary_total_realisasi', formatRupiah(grandTotalRealisasi));

    const summarySelisih = document.getElementById('summary_selisih_akhir');
    if (summarySelisih) {
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
    }

    // Set Nilai Murni ke Hidden Inputs Controller
    setElementValue('hidden_total_realisasi', grandTotalRealisasi);
    setElementValue('hidden_total_dibayar', grandTotalRealisasi);
    setElementValue('hidden_selisih', sisaSettlement);
}

window.hitungSelisih = hitungSelisih;

// ============================================================
// INITIALIZATION & EVENT LISTENERS
// ============================================================

document.addEventListener('DOMContentLoaded', function () {
    // 1. Format awal semua input realisasi jika halaman dipanggil ulang/edit
    const realisasiInputs = document.querySelectorAll('.realisasi-formatted-input');
    realisasiInputs.forEach(input => {
        if (input.value) {
            handleRupiahInput(input);
        }
    });

    // 2. Automated Event Listener (Real-time recalculate saat diketik)
    document.addEventListener('input', function (e) {
        if (
            e.target.classList.contains('realisasi-formatted-input') || 
            e.target.id === 'input_uang_muka_formatted'
        ) {
            handleRupiahInput(e.target);
            hitungSelisih();
        }
    });

    // 3. Jalankan kalkulasi awal saat halaman dimuat
    hitungSelisih();
});