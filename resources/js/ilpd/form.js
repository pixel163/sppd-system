// ============================================================
// HELPER & UTILITIES
// ============================================================

// Clean format Rupiah to pure integer
function parseRupiah(val) {
    if (!val) return 0;
    return parseInt(val.toString().replace(/[^0-9]/g, ''), 10) || 0;
}

// Format integer to IDR string
function formatRupiah(val) {
    let number = parseRupiah(val);
    return number ? number.toLocaleString('id-ID') : '0';
}

// Validasi input & batasan nominal maksimal (Opsional Expense Inputs)
window.validateInput = function(element) {
    let maxLimit = parseInt(element.getAttribute('max'), 10) || 100000000;
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

// Validasi input Qty (hanya angka, min 0, max berdasarkan data-max)
window.validateInputQty = function(input) {
    let maxVal = parseInt(input.getAttribute('data-max'), 10) || 0;
    
    // Hapus karakter selain angka
    let valStr = input.value.replace(/[^0-9]/g, '');
    let valNum = parseInt(valStr, 10);

    if (isNaN(valNum)) {
        input.value = '0';
    } else if (valNum > maxVal) {
        input.value = maxVal;
    } else {
        input.value = valNum; // Mencegah leading zero seperti "05"
    }

    // Hitung ulang subtotal dinas/makan/hotel & grand total
    calculateSubtotals();
};

// ============================================================
// KALKULATOR & LOGIKA SUBTOTAL
// ============================================================

// Hitung masing-masing subtotal (Dinas, Makan, Hotel)
function calculateSubtotals() {
    // 1. Subtotal Dinas
    const dinasRate = parseRupiah(document.getElementById('dinas_rate')?.value);
    const dinasQty = parseInt(document.getElementById('dinas_qty')?.value, 10) || 0;
    const dinasSubtotal = dinasRate * dinasQty;
    const inputDinasSubtotal = document.getElementById('dinas_subtotal');
    if (inputDinasSubtotal) inputDinasSubtotal.value = formatRupiah(dinasSubtotal);

    // 2. Subtotal Makan
    const makanRate = parseRupiah(document.getElementById('makan_rate')?.value);
    const makanQty = parseInt(document.getElementById('makan_qty')?.value, 10) || 0;
    const makanSubtotal = makanRate * makanQty;
    const inputMakanSubtotal = document.getElementById('makan_subtotal');
    if (inputMakanSubtotal) inputMakanSubtotal.value = formatRupiah(makanSubtotal);

    // 3. Subtotal Hotel
    const hotelRate = parseRupiah(document.getElementById('hotel_rate')?.value);
    const hotelQty = parseInt(document.getElementById('hotel_qty')?.value, 10) || 0;
    const hotelSubtotal = hotelRate * hotelQty;
    const inputHotelSubtotal = document.getElementById('hotel_subtotal');
    if (inputHotelSubtotal) inputHotelSubtotal.value = formatRupiah(hotelSubtotal);

    // Setelah subtotal dihitung, panggil hitungSemua() untuk memperbarui Grand Total
    hitungSemua();
}

// Hitung Grand Total Keseluruhan
function hitungSemua() {
    const inputMulai = document.getElementById('tanggalMulai');
    const inputSelesai = document.getElementById('tanggalSelesai');
    const info = document.getElementById('infoLamaPerjalanan');

    const durasiFallback = parseInt(inputMulai?.getAttribute('data-durasi'), 10) || 1;

    // A. SET TANGGAL SELESAI & INFORMASI PERJALANAN
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

    // B. TOTAL BIAYA HARIAN (Langsung mengambil jumlah Subtotal Dinas + Makan + Hotel)
    const subtotalDinas = parseRupiah(document.getElementById('dinas_subtotal')?.value);
    const subtotalMakan = parseRupiah(document.getElementById('makan_subtotal')?.value);
    const subtotalHotel = parseRupiah(document.getElementById('hotel_subtotal')?.value);

    const totalHarian = subtotalDinas + subtotalMakan + subtotalHotel;

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
    // 1. Hitung awal saat halaman pertama kali dimuat
    calculateSubtotals();

    // 2. Setup tanggal mulai
    const inputMulai = document.getElementById('tanggalMulai');
    if (inputMulai) {
        const besok = new Date();
        besok.setDate(besok.getDate() + 7);
        const minDate = besok.toISOString().split('T')[0];
        inputMulai.setAttribute('min', minDate);

        inputMulai.addEventListener('change', hitungSemua);
        if (inputMulai.value) {
            hitungSemua();
        }
    }

    // 3. Clean format sebelum Submit Form
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function () {
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                if (input.type === 'text' && input.value && !isNaN(parseRupiah(input.value))) {
                    input.value = parseRupiah(input.value);
                }
            });
        });
    }
});