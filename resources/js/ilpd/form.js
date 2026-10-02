// ============================================================
// HELPER & UTILITIES
// ============================================================

// Clean format Rupiah to pure integer
function parseRupiah(val) {
    if (!val) return 0;
    return parseInt(val.toString().replace(/[^0-9]/g, '')) || 0;
}

// Format integer to IDR string
function formatRupiah(val) {
    let number = parseRupiah(val);
    return number ? number.toLocaleString('id-ID') : '0';
}

// Validasi input & batasan nominal maksimal
window.validateInput = function(element) {
    let maxLimit = parseInt(element.getAttribute('max')) || 1000000;
    let nominal = parseRupiah(element.value);

    // Batasi jika melebihi nilai max
    if (nominal > maxLimit) {
        nominal = maxLimit;
    }

    // Format ulang tampilan input
    element.value = nominal ? formatRupiah(nominal) : '';

    // Hitung ulang total
    if (typeof hitungSemua === 'function') {
        hitungSemua();
    }
};
// function validateInput(element) {
//     let maxLimit = parseInt(element.getAttribute('max')) || 100000000; // default safe limit
//     let nominal = parseRupiah(element.value);

//     if (nominal > maxLimit) {
//         nominal = maxLimit;
//     }

//     element.value = nominal ? formatRupiah(nominal) : '';
//     hitungSemua();
// }

// ============================================================
// KALKULATOR & LOGIKA TANGGAL
// ============================================================

function hitungSemua() {
    const inputMulai = document.getElementById('tanggalMulai');
    const inputSelesai = document.getElementById('tanggalSelesai');
    const info = document.getElementById('infoLamaPerjalanan');

    // Ambil durasi bawaan dari HTML attribute data-durasi
    const durasiFallback = parseInt(inputMulai?.getAttribute('data-durasi')) || 1;

    // Kunci UX: Jika tanggalMulai belum diisi, durasi dianggap 0 untuk kalkulasi biaya
    const durasiHari = (inputMulai && inputMulai.value) ? durasiFallback : 0;

    // A. HITUNG TANGGAL SELESAI
    if (inputMulai && inputMulai.value) {
        const tglMulai = new Date(inputMulai.value);
        const tglSelesai = new Date(tglMulai);
        tglSelesai.setDate(tglMulai.getDate() + (durasiFallback - 1));

        const yyyy = tglSelesai.getFullYear();
        const mm = String(tglSelesai.getMonth() + 1).padStart(2, '0');
        const dd = String(tglSelesai.getDate()).padStart(2, '0');
        const formattedSelesai = `${yyyy}-${mm}-${dd}`;

        if (inputSelesai) inputSelesai.value = formattedSelesai;
        if (info) {
            info.innerText = `Total perjalanan: ${durasiFallback} Hari (${inputMulai.value} s/d ${formattedSelesai})`;
            info.classList.remove('hidden');
        }
    }

    // B. BIAYA HARIAN (Dinas + Makan + Hotel) x Durasi
    const dinas = parseRupiah(document.getElementById('dinas')?.value);
    const makan = parseRupiah(document.getElementById('makan')?.value);
    const hotel = parseRupiah(document.getElementById('hotel')?.value);
    const totalHarian = (dinas + makan + hotel) * durasiHari;

    // C. BIAYA OPSIONAL
    const bbm = parseRupiah(document.getElementById('bbm')?.value);
    const transportLokal = parseRupiah(document.getElementById('transportLokal')?.value);
    const visa = parseRupiah(document.getElementById('visa')?.value);
    const fiskal = parseRupiah(document.getElementById('fiskal')?.value);
    const airportTax = parseRupiah(document.getElementById('airportTax')?.value);
    const parkirToll = parseRupiah(document.getElementById('parkirToll')?.value);
    const entertainment = parseRupiah(document.getElementById('entertainment')?.value);
    const biayaLainnya = parseRupiah(document.getElementById('biayaLainnya')?.value);

    const totalOpsional = bbm + transportLokal + visa + fiskal + airportTax + parkirToll + entertainment + biayaLainnya;

    // D. GRAND TOTAL
    const grandTotal = totalHarian + totalOpsional;

    const inputTotal = document.getElementById('total');
    const inputUangMuka = document.getElementById('uangMuka');

    if (inputTotal) inputTotal.value = formatRupiah(grandTotal);
    if (inputUangMuka) inputUangMuka.value = formatRupiah(grandTotal);
}

// ============================================================
// EVENT LISTENERS & INITIALIZATION
// ============================================================

document.addEventListener('DOMContentLoaded', function () {
    const inputMulai = document.getElementById('tanggalMulai');

    if (inputMulai) {
        // 1. Set minimal tanggal awal = H+1 (Besok)
        const besok = new Date();
        besok.setDate(besok.getDate() + 1);
        const minDate = besok.toISOString().split('T')[0];
        inputMulai.setAttribute('min', minDate);

        // 2. Event listener ubah tanggal
        inputMulai.addEventListener('change', hitungSemua);
        if (inputMulai.value) {
            hitungSemua();
        }
    }

    // Bersihkan titik sebelum form dikirim ke Laravel backend
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function () {
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                // Jika input bertipe teks & berisi angka berformat rupiah, ubah ke integer murni
                if (input.type === 'text' && input.value && !isNaN(parseRupiah(input.value))) {
                    input.value = parseRupiah(input.value);
                }
            });
        });
    }
});