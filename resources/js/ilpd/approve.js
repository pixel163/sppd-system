// // ============================================================
// // HELPER & UTILITIES
// // ============================================================

// function parseRupiah(val) {
//     if (!val) return 0;
//     return parseInt(val.toString().replace(/[^0-9]/g, '')) || 0;
// }

// function formatRupiah(angka) {
//     if (isNaN(angka) || angka === null || angka === 0) {
//         return '';
//     }
//     return new Intl.NumberFormat('id-ID').format(angka);
// }

// function updateFileName(input) {
//     input.setCustomValidity('');
//     const label = document.getElementById('file-label');

//     if (input.files && input.files[0]) {
//         label.innerText = 'File Terpilih: ' + input.files[0].name;
//         label.classList.add('text-[#2563eb]');
//     } else {
//         label.innerText = 'Upload tiket perjalanan';
//         label.classList.remove('text-[#2563eb]');
//     }
// }

// // function toggleAdjustTarif() {
// //     const wrapper = document.getElementById('wrapperDropdownGolongan');
// //     if (wrapper) {
// //         wrapper.classList.toggle('hidden');
// //     }
// // }

// // ============================================================
// // LOGIKA PERHITUNGAN
// // ============================================================

// function hitungSemua() {
//     const inputMulai = document.getElementById('tanggalMulai');
//     const inputSelesai = document.getElementById('tanggalSelesai');
//     const info = document.getElementById('infoLamaPerjalanan');

//     if (!inputMulai || !inputMulai.value) return;

//     // Ambil durasi dari data-attribute HTML
//     const durasiHari = parseInt(inputMulai.getAttribute('data-durasi')) || 1;

//     const tglMulai = new Date(inputMulai.value);
//     const tglSelesai = new Date(tglMulai);
//     tglSelesai.setDate(tglMulai.getDate() + (durasiHari - 1));

//     const yyyy = tglSelesai.getFullYear();
//     const mm = String(tglSelesai.getMonth() + 1).padStart(2, '0');
//     const dd = String(tglSelesai.getDate()).padStart(2, '0');
//     const formattedSelesai = `${yyyy}-${mm}-${dd}`;

//     if (inputSelesai) inputSelesai.value = formattedSelesai;
//     if (info) {
//         info.innerText = `Total perjalanan: ${durasiHari} Hari (${inputMulai.value} s/d ${formattedSelesai})`;
//         info.classList.remove('hidden');
//     }
// }

// function hitungTotal() {
//     const inputMulai = document.getElementById('tanggalMulai');
//     // Ambil durasi dari data-attribute HTML
//     const durasi = parseInt(inputMulai?.getAttribute('data-durasi')) || 1;

//     // A. Tarif Harian
//     const inputDinas = document.getElementById('inputDinas');
//     const inputMakan = document.getElementById('inputMakan');
//     const inputHotel = document.getElementById('inputHotel');

//     const dinasPerHari = inputDinas ? parseRupiah(inputDinas.value) : 0;
//     const makanPerHari = inputMakan ? parseRupiah(inputMakan.value) : 0;
//     const hotelPerMalam = inputHotel ? parseRupiah(inputHotel.value) : 0;

//     const totalDinas = dinasPerHari * durasi;
//     const totalMakan = makanPerHari * durasi;
//     const totalHotel = hotelPerMalam * durasi;

//     // B. Biaya Opsional
//     const biayaOpsionalIds = [
//         'bbm', 'transportLokal', 'visa', 'fiskal',
//         'airportTax', 'parkirToll', 'entertainment', 'biayaLainnya'
//     ];

//     let totalOpsional = 0;
//     biayaOpsionalIds.forEach(id => {
//         const input = document.getElementById(id);
//         if (input) {
//             totalOpsional += parseRupiah(input.value);
//         }
//     });

//     // C. Grand Total
//     const grandTotal = totalDinas + totalMakan + totalHotel + totalOpsional;

//     // D. Update Display Total
//     const elementTotal = document.getElementById('total') ||
//                          document.getElementById('inputTotal') ||
//                          document.getElementById('totalDisplay');

//     if (elementTotal) {
//         if (elementTotal.tagName === 'INPUT') {
//             elementTotal.value = formatRupiah(grandTotal);
//         } else {
//             elementTotal.innerText = 'Rp ' + formatRupiah(grandTotal);
//         }
//     }

//     // E. Update Uang Muka
//     const elementUangMuka = document.getElementById('uangMuka');
//     if (elementUangMuka) {
//         elementUangMuka.value = formatRupiah(grandTotal);
//     }
// }

// // function applyTarifOverride(select) {
// //     const selectedOption = select.options[select.selectedIndex];
// //     if (!selectedOption.value) return;

// //     const dinas = selectedOption.getAttribute('data-dinas');
// //     const makan = selectedOption.getAttribute('data-makan');
// //     const hotel = selectedOption.getAttribute('data-hotel');

// //     const inputDinas = document.getElementById('inputDinas');
// //     const inputMakan = document.getElementById('inputMakan');
// //     const inputHotel = document.getElementById('inputHotel');

// //     if (inputDinas && dinas !== null) inputDinas.value = formatRupiah(dinas);
// //     if (inputMakan && makan !== null) inputMakan.value = formatRupiah(makan);
// //     if (inputHotel && hotel !== null) inputHotel.value = formatRupiah(hotel);

// //     hitungTotal();
// // }

// // ============================================================
// // INITIALIZATION / EVENT LISTENERS
// // ============================================================

// document.addEventListener('DOMContentLoaded', function () {
//     const inputMulai = document.getElementById('tanggalMulai');

//     if (inputMulai) {
//         inputMulai.addEventListener('change', hitungSemua);
//         if (inputMulai.value) {
//             hitungSemua();
//         }
//     }

//     const inputIds = [
//         'bbm', 'inputDinas', 'inputMakan', 'inputHotel',
//         'transportLokal', 'visa', 'fiskal', 'airportTax',
//         'parkirToll', 'entertainment', 'biayaLainnya'
//     ];

//     // Format nilai awal & pasang Realtime Input Listener
//     inputIds.forEach(id => {
//         const input = document.getElementById(id);
//         if (!input) return;

//         if (input.value) {
//             input.value = formatRupiah(parseRupiah(input.value));
//         }

//         input.addEventListener('input', function () {
//             const value = parseRupiah(this.value);
//             this.value = value ? formatRupiah(value) : '';
//             hitungTotal();
//         });
//     });

//     // Perhitungan awal saat halaman terbuka
//     hitungTotal();

//     // Bersihkan format Rupiah ke Angka murni saat Form di-submit
//     const formApprove = document.querySelector('form');
//     if (formApprove) {
//         formApprove.addEventListener('submit', function () {
//             inputIds.forEach(id => {
//                 const input = document.getElementById(id);
//                 if (input) input.value = parseRupiah(input.value);
//             });

//             const total = document.getElementById('total');
//             if (total) total.value = parseRupiah(total.value);

//             const uangMuka = document.getElementById('uangMuka');
//             if (uangMuka) uangMuka.value = parseRupiah(uangMuka.value);
//         });
//     }
// });

// document.addEventListener('DOMContentLoaded', function () {
//     function parseRupiah(val) {
//         if (!val) return 0;
//         return parseFloat(val.toString().replace(/[^0-9]/g, '')) || 0;
//     }

//     function formatRupiah(val) {
//         return new Intl.NumberFormat('id-ID').format(val);
//     }

//     function calculateSubtotals() {
//         // 1. Dinas
//         const dinasRate = parseRupiah(document.getElementById('dinas_rate')?.value);
//         const dinasQty = parseFloat(document.getElementById('dinas_qty')?.value) || 0;
//         document.getElementById('dinas_subtotal').value = formatRupiah(dinasRate * dinasQty);

//         // 2. Makan
//         const makanRate = parseRupiah(document.getElementById('makan_rate')?.value);
//         const makanQty = parseFloat(document.getElementById('makan_qty')?.value) || 0;
//         document.getElementById('makan_subtotal').value = formatRupiah(makanRate * makanQty);

//         // 3. Hotel
//         const hotelRate = parseRupiah(document.getElementById('hotel_rate')?.value);
//         const hotelQty = parseFloat(document.getElementById('hotel_qty')?.value) || 0;
//         document.getElementById('hotel_subtotal').value = formatRupiah(hotelRate * hotelQty);
//     }

//     calculateSubtotals();
// });

// ============================================================
// HELPER & UTILITIES
// ============================================================

function parseRupiah(val) {
    if (!val) return 0;
    return parseInt(val.toString().replace(/[^0-9]/g, '')) || 0;
}

function formatRupiah(angka) {
    if (isNaN(angka) || angka === null || angka === 0) {
        return '0';
    }
    return new Intl.NumberFormat('id-ID').format(angka);
}

// Validasi Input Hari/Malam (Angka Saja & Maksimal durasi SPPD)
function validateInputQty(input) {
    // Hanya izinkan angka
    input.value = input.value.replace(/[^0-9]/g, '');

    const maxVal = parseInt(input.getAttribute('data-max')) || 1;
    let currentVal = parseInt(input.value) || 0;

    // Batasi jika melebihi durasi maksimal
    if (currentVal > maxVal) {
        input.value = maxVal;
    }

    // Hitung ulang subtotal dan grand total setiap kali input berubah
    hitungTotal();
}

// Alias fungsi agar kompatibel dengan atribut oninput di HTML kamu
function validateInput(input) {
    validateInputQty(input);
}

function updateFileName(input) {
    input.setCustomValidity('');
    const label = document.getElementById('file-label');

    if (input.files && input.files[0]) {
        label.innerText = 'File Terpilih: ' + input.files[0].name;
        label.classList.add('text-[#2563eb]');
    } else {
        label.innerText = 'Upload tiket perjalanan';
        label.classList.remove('text-[#2563eb]');
    }
}

// ============================================================
// LOGIKA PERHITUNGAN
// ============================================================

function hitungSemua() {
    const inputMulai = document.getElementById('tanggalMulai');
    const inputSelesai = document.getElementById('tanggalSelesai');
    const info = document.getElementById('infoLamaPerjalanan');

    if (!inputMulai || !inputMulai.value) return;

    const durasiHari = parseInt(inputMulai.getAttribute('data-durasi')) || 1;

    const tglMulai = new Date(inputMulai.value);
    const tglSelesai = new Date(tglMulai);
    tglSelesai.setDate(tglMulai.getDate() + (durasiHari - 1));

    const yyyy = tglSelesai.getFullYear();
    const mm = String(tglSelesai.getMonth() + 1).padStart(2, '0');
    const dd = String(tglSelesai.getDate()).padStart(2, '0');
    const formattedSelesai = `${yyyy}-${mm}-${dd}`;

    if (inputSelesai) inputSelesai.value = formattedSelesai;
    if (info) {
        info.innerText = `Total perjalanan: ${durasiHari} Hari (${inputMulai.value} s/d ${formattedSelesai})`;
        info.classList.remove('hidden');
    }
}

function hitungTotal() {
    // A. Subtotal Tarif Harian (Dinas, Makan, Hotel)
    const dinasRate = parseRupiah(document.getElementById('dinas_rate')?.value);
    const dinasQty  = parseInt(document.getElementById('dinas_qty')?.value) || 0;
    const subtotalDinas = dinasRate * dinasQty;

    const makanRate = parseRupiah(document.getElementById('makan_rate')?.value);
    const makanQty  = parseInt(document.getElementById('makan_qty')?.value) || 0;
    const subtotalMakan = makanRate * makanQty;

    const hotelRate = parseRupiah(document.getElementById('hotel_rate')?.value);
    const hotelQty  = parseInt(document.getElementById('hotel_qty')?.value) || 0;
    const subtotalHotel = hotelRate * hotelQty;

    // Update Display Subtotal masing-masing
    const elSubDinas = document.getElementById('dinas_subtotal');
    const elSubMakan = document.getElementById('makan_subtotal');
    const elSubHotel = document.getElementById('hotel_subtotal');

    if (elSubDinas) elSubDinas.value = formatRupiah(subtotalDinas);
    if (elSubMakan) elSubMakan.value = formatRupiah(subtotalMakan);
    if (elSubHotel) elSubHotel.value = formatRupiah(subtotalHotel);

    // B. Biaya Opsional
    const biayaOpsionalIds = [
        'bbm', 'transportLokal', 'visa', 'fiskal',
        'airportTax', 'parkirToll', 'entertainment', 'biayaLainnya'
    ];

    let totalOpsional = 0;
    biayaOpsionalIds.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            totalOpsional += parseRupiah(input.value);
        }
    });

    // C. Grand Total
    const grandTotal = subtotalDinas + subtotalMakan + subtotalHotel + totalOpsional;

    // D. Update Display Total
    const elementTotal = document.getElementById('total') ||
                         document.getElementById('inputTotal') ||
                         document.getElementById('totalDisplay');

    if (elementTotal) {
        if (elementTotal.tagName === 'INPUT') {
            elementTotal.value = formatRupiah(grandTotal);
        } else {
            elementTotal.innerText = 'Rp ' + formatRupiah(grandTotal);
        }
    }

    // E. Update Uang Muka
    const elementUangMuka = document.getElementById('uangMuka');
    if (elementUangMuka) {
        elementUangMuka.value = formatRupiah(grandTotal);
    }
}

// ============================================================
// INITIALIZATION / EVENT LISTENERS
// ============================================================

document.addEventListener('DOMContentLoaded', function () {
    const inputMulai = document.getElementById('tanggalMulai');

    if (inputMulai) {
        inputMulai.addEventListener('change', hitungSemua);
        if (inputMulai.value) {
            hitungSemua();
        }
    }

    // Event Listener untuk Input Qty (Makan & Hotel)
    // const qtyInputIds = ['dinas_qty','makan_qty', 'hotel_qty'];
    const qtyInputIds = ['dinas_qty', 'makan_qty', 'hotel_qty'];
    qtyInputIds.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function () {
                validateInputQty(this);
            });
        }
    });

    // Event Listener untuk Biaya Opsional & Rate
    const inputIds = [
        'bbm', 'dinas_rate', 'makan_rate', 'hotel_rate',
        'transportLokal', 'visa', 'fiskal', 'airportTax',
        'parkirToll', 'entertainment', 'biayaLainnya'
    ];

    // Format nilai awal & pasang Realtime Input Listener
    inputIds.forEach(id => {
        const input = document.getElementById(id);
        if (!input) return;

        if (input.value && !input.readOnly) {
            input.value = formatRupiah(parseRupiah(input.value));
        }

        input.addEventListener('input', function () {
            const value = parseRupiah(this.value);
            this.value = value ? formatRupiah(value) : '0';
            hitungTotal();
        });
    });

    // Perhitungan awal saat halaman terbuka
    hitungTotal();

    // Bersihkan format Rupiah ke Angka murni saat Form di-submit
    const formApprove = document.querySelector('form');
    if (formApprove) {
        formApprove.addEventListener('submit', function () {
            inputIds.forEach(id => {
                const input = document.getElementById(id);
                if (input) input.value = parseRupiah(input.value);
            });

            qtyInputIds.forEach(id => {
                const input = document.getElementById(id);
                if (input) input.value = parseRupiah(input.value);
            });

            const total = document.getElementById('total') || document.getElementById('inputTotal');
            if (total) total.value = parseRupiah(total.value);

            const uangMuka = document.getElementById('uangMuka');
            if (uangMuka) uangMuka.value = parseRupiah(uangMuka.value);
        });
    }
});