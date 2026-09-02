const dataPegawai = [
    {
        nama: "Eko Saputra",
        nik: "3276010101010001",
        jabatan: "Staff",
        departemen: "IT",
        golongan: "I"
    },

    {
        nama: "Budi Santoso",
        nik: "3276010202020002",
        jabatan: "Manager GA",
        departemen: "General Affair",
        golongan: "II"
    },

    {
        nama: "Andi Wijaya",
        nik: "3276010404040004",
        jabatan: "Manager",
        departemen: "IT",
        golongan: "II"
    }
];

const currentUserByRole = {
    staff: dataPegawai.find(pegawai => pegawai.nama === "Eko Saputra"),
    manager: {
        ...dataPegawai.find(pegawai => pegawai.nama === "Andi Wijaya"),
        jabatan: "Manager"
    },
    ga: {
        ...dataPegawai.find(pegawai => pegawai.nama === "Budi Santoso"),
        jabatan: "Manager",
        departemen: "General Affair"
    }
};

// 3. HELPER UNTUK MENGAMBIL USER AKTIF (Wajib didefinisikan di atas)
function getCurrentUser() {
    const savedRole = localStorage.getItem("sppd_role");
    return currentUserByRole[savedRole] || currentUserByRole.staff;
}

function isiDataUserLogin() {

    const savedRole = localStorage.getItem("sppd_role");
    const role = currentUserByRole[savedRole] ? savedRole : "staff";

    switchRole(role);
}

function switchRole(role) {
    const user = currentUserByRole[role];

    if (!user) return;

    localStorage.setItem("sppd_role", role);

    // Fungsi aman untuk set value/text tanpa bikin JS crash jika ID tidak ditemukan
    const setElText = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.innerText = val ?? '';
    };

    const setElValue = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.value = val ?? '';
    };

    // Set Text (Header/Profile)
    setElText("profileName", user.nama);
    setElText("profileRole", user.jabatan);

    // Set Input Form
    setElValue("nama", user.nama);
    setElValue("nik", user.nik);
    setElValue("jabatan", user.jabatan);
    setElValue("departemen", user.departemen);

    closeProfileMenu();
}

function closeProfileMenu() {

    const profileMenu = document.getElementById("profileMenu");
    const profileToggle = document.getElementById("profileToggle");

    profileMenu.classList.add("hidden");
    profileToggle.setAttribute("aria-expanded", "false");
}

document.addEventListener("DOMContentLoaded", isiDataUserLogin);

/*
|--------------------------------------------------------------------------
| DATA KOTA
|--------------------------------------------------------------------------
| Dummy data untuk simulasi FE.
|--------------------------------------------------------------------------
*/

const dataKota = [
"Jakarta",
"Bandung",
"Bogor",
"Bekasi",
"Depok",
"Surabaya",
"Yogyakarta",
"Semarang",
"medan",
"Bali"
];

/*
|--------------------------------------------------------------------------
| TARIF PERJALANAN
|--------------------------------------------------------------------------
| Simulasi hubungan:
|
| Golongan
|     +
| Kota
|     ↓
| Dinas
| Makan
| Hotel
|--------------------------------------------------------------------------
*/

const tarifPerjalanan = {

"I": {

    "Jakarta": {
        dinas: 150000,
        makan: 150000,
        hotel: 500000
    },

    "Bandung": {
        dinas: 130000,
        makan: 130000,
        hotel: 400000
    },

    "Bogor": {
        dinas: 120000,
        makan: 120000,
        hotel: 350000
    },

    "Surabaya": {
        dinas: 160000,
        makan: 160000,
        hotel: 550000
    },

    "Yogyakarta": {
        dinas: 140000,
        makan: 140000,
        hotel: 450000
    },

    "Bekasi": {
        dinas: 125000,
        makan: 90000,
        hotel: 275000,
    },

    "Semarang": {
        dinas: 170000,
        makan: 145000,
        hotel: 455000
    },

    "medan": {
        dinas: 225000,
        makan: 130000,
        hotel: 450000,
    },

    "Bali": {
        dinas: 225000,
        makan: 130000,
        hotel: 450000,
    },

    "Depok": {
        dinas: 225000,
        makan: 130000,
        hotel: 450000,
    }

}

};

// ======================================================
// AMBIL DATA FORM 1
// ======================================================

function getForm1Data() {

const saved =
    localStorage.getItem("sppd_form1");

if (!saved) {

    console.warn(
        "Data sppd_form1 tidak ditemukan."
    );

    return null;
}

try {

    return JSON.parse(saved);

} catch (error) {

    console.error(
        "Data sppd_form1 tidak valid:",
        error
    );

    return null;
}
}

function setCheckboxValue(name, value) {
if (value === undefined || value === null) return;

// 1. Selector universal untuk menangani 'transportasi' maupun 'transportasi[]'
const cleanName = name.replace(/\[\]$/, '');
const checkboxes = document.querySelectorAll(`input[name="${cleanName}"], input[name="${cleanName}[]"]`);

if (checkboxes.length === 0) {
    console.warn(`Checkbox dengan name="${name}" tidak ditemukan di DOM.`);
    return;
}

// 2. Normalisasi input 'value' menjadi Array of String
let values = [];

if (Array.isArray(value)) {
    values = value;
} else if (typeof value === 'string') {
    const trimmed = value.trim();
    // Cek jika tersimpan sebagai JSON string seperti '["Pesawat","Bus"]'
    if (trimmed.startsWith('[') && trimmed.endsWith(']')) {
        try {
            values = JSON.parse(trimmed);
        } catch (e) {
            values = trimmed.split(',').map(item => item.trim());
        }
    } else {
        values = trimmed.split(',').map(item => item.trim());
    }
}

// Pastikan semua elemen array berbentuk string dan di-trim
values = values.map(val => String(val).trim().toLowerCase());

// 3. Match value dengan checkbox (case-insensitive & trim spaces)
checkboxes.forEach(checkbox => {
    const cbValue = String(checkbox.value).trim().toLowerCase();
    checkbox.checked = values.includes(cbValue);
});
}

document.addEventListener("DOMContentLoaded", () => {
loadForm1ToILPD();
});

// ======================================================
// ISI DATA DARI FORM 1
// ======================================================

// Fungsi untuk memuat data dari Form 1 (Pengajuan) ke Form 2 (ILPD)
function loadForm1ToILPD() {

const form1 = getForm1Data();

if (!form1) return;

// ==============================
// KOTA
// ==============================

const kota =
    document.getElementById("kota");

if (kota) {
    kota.value = form1.kota || "";
}

// Transportasi
setCheckboxValue(
    "transportasi",
    form1.transportasi
);

// Keperluan
setCheckboxValue(
    "keperluan",
    form1.keperluan
);

// ==============================
// TUGAS
// ==============================

const tugas =
    document.getElementById("tugas");

if (tugas) {
    tugas.value =
        form1.tugas || "";
}

// Jalankan setup batasan tanggal (H+1)
setupTanggal();

// Jalankan kalkulasi jika tanggal mulai sudah terisi sebelumnya
hitungLamaPerjalanan();

// Hitung biaya berdasarkan tanggal/durasi baru jika ada
if (typeof hitungBiaya === "function") {
    hitungBiaya();
}

// hitungBiaya();
}

// ======================================================
// 2. FUNGSI TANGGAL & HELPER (Ditaruh di luar/Scope Global)
// ======================================================
function setupTanggal() {
const tanggalMulai = document.getElementById("tanggalMulai");
const tanggalSelesai = document.getElementById("tanggalSelesai");

if (!tanggalMulai || !tanggalSelesai) return;

// Set minimal tanggal mulai adalah H+1 (Besok)
const besok = new Date();
besok.setDate(besok.getDate() + 1);

const minDate = formatDateInput(besok);
tanggalMulai.min = minDate;

// Kunci tanggal selesai dari input manual
tanggalSelesai.readOnly = true;
}

function hitungLamaPerjalanan() {
const tanggalMulai = document.getElementById("tanggalMulai");
const tanggalSelesai = document.getElementById("tanggalSelesai");

if (!tanggalMulai || !tanggalSelesai) return;

const form1 = getForm1Data();
if (!form1) return;

const durasi = Number(form1.waktu);
if (!durasi || durasi < 1) return;

// Jika tanggal mulai sudah dipilih
if (tanggalMulai.value) {
    const awal = parseLocalDate(tanggalMulai.value);
    const akhir = new Date(awal);

    // Hitung tanggal selesai: Tanggal Mulai + (Durasi - 1)
    akhir.setDate(akhir.getDate() + durasi - 1);

    const tanggalSelesaiValue = formatDateInput(akhir);

    // Otomatis isi nilai tanggal selesai
    tanggalSelesai.value = tanggalSelesaiValue;
    tanggalSelesai.min = tanggalSelesaiValue;
    tanggalSelesai.max = tanggalSelesaiValue;

    // Tampilkan teks info jika ada element-nya
    const info = document.getElementById("infoLamaPerjalanan");
    if (info) {
        info.textContent = `Lama perjalanan: ${durasi} hari (${tanggalMulai.value} s/d ${tanggalSelesaiValue})`;
        info.classList.remove("hidden");
    }
}

if (typeof hitungBiaya === "function") {
    hitungBiaya();
}
}

function formatDateInput(date) {
const year = date.getFullYear();
const month = String(date.getMonth() + 1).padStart(2, "0");
const day = String(date.getDate()).padStart(2, "0");

return `${year}-${month}-${day}`;
}

function parseLocalDate(value) {
const [year, month, day] = value.split("-").map(Number);
return new Date(year, month - 1, day);
}

// ======================================================
// HITUNG BIAYA
// ======================================================

function hitungBiaya() {

const kota =
    document.getElementById("kota")?.value;

const tanggalMulai =
    document.getElementById("tanggalMulai")?.value;

const tanggalSelesai =
    document.getElementById("tanggalSelesai")?.value;

if (
    !kota ||
    !tanggalMulai ||
    !tanggalSelesai
) {
    return;
}

// Ambil user aktif dari localStorage via getCurrentUser
const currentUser = getCurrentUser();
const golongan = currentUser?.golongan || "I";

// 2. Ambil Tarif berdasarkan Golongan + Kota
const tarifGolongan = tarifPerjalanan[golongan];
const tarif = tarifGolongan ? tarifGolongan[kota] : null;

if (!tarif) {
    console.warn(`Tarif untuk golongan ${golongan} di kota ${kota} belum tersedia.`);
    return;
}

const mulai =
    parseLocalDate(tanggalMulai);

const selesai =
    parseLocalDate(tanggalSelesai);

const hari =
    Math.round(
        (
            selesai - mulai
        ) /
        (1000 * 60 * 60 * 24)
    ) + 1;

if (hari <= 0) return;

// Malam = jumlah hari - 1

const malam =
    Math.max(
        hari - 1,
        0
    );

// ==============================
// HITUNG
// ==============================

const biayaDinas =
    tarif.dinas * hari;

const biayaMakan =
    tarif.makan * hari;

const biayaHotel =
    tarif.hotel * malam;

// const biayaLaundry =
//     tarif.laundry * malam;

const total =
    biayaDinas +
    biayaMakan +
    biayaHotel ;
    // biayaLaundry;

// ==============================
// TAMPILKAN
// ==============================

setMoney(
    "dinas",
    biayaDinas
);

setMoney(
    "makan",
    biayaMakan
);

setMoney(
    "hotel",
    biayaHotel
);

// setMoney(
//     "laundry",
//     biayaLaundry
// );

setMoney(
    "total",
    total
);

// Untuk sementara uang muka
// mengikuti total

setMoney(
    "uangMuka",
    total
);
}

function setMoney(id, value) {

const element =
    document.getElementById(id);

if (!element) return;


element.value =
    new Intl.NumberFormat(
        "id-ID"
    ).format(value);
}

// ======================================================
// SUBMIT FORM 2 / ILPD
// ======================================================

function simpanILPD(event) {

if (event) {
    event.preventDefault();
}

const form1 =
    getForm1Data();

if (!form1) {

    alert(
        "Data Form 1 tidak ditemukan. Silakan isi Form 1 terlebih dahulu."
    );

    return;
}

const form =
    document.getElementById("ilpdForm");

if (
    form &&
    !form.checkValidity()
) {

    form.reportValidity();

    return;
}

const tanggalMulai =
    document.getElementById(
        "tanggalMulai"
    )?.value;

const tanggalSelesai =
    document.getElementById(
        "tanggalSelesai"
    )?.value;

// ==============================
// VALIDASI TANGGAL
// ==============================

const durasi =
    Number(form1.waktu);

if (
    !tanggalMulai ||
    !tanggalSelesai
) {

    alert(
        "Silakan pilih tanggal perjalanan."
    );

    return;
}

const mulai =
    parseLocalDate(
        tanggalMulai
    );

const selesai =
    parseLocalDate(
        tanggalSelesai
    );

const jumlahHari =
    Math.round(
        (
            selesai - mulai
        ) /
        (1000 * 60 * 60 * 24)
    ) + 1;


if (jumlahHari !== durasi) {

    alert(
        `Tanggal perjalanan harus tepat ${durasi} hari.`
    );

    return;
}

// ==============================
// BUAT NOMOR SPPD
// ==============================

const nomorSPPD =
    "SPPD-" +
    new Date().getFullYear() +
    "-" +
    Date.now()
        .toString()
        .slice(-5);

// ==============================
// DATA FORM 2
// ==============================
// 1. Ambil checkbox transportasi yang tercentang di Form 2
const selectedTransport = Array.from(
    document.querySelectorAll('input[name="transportasi[]"]:checked')
).map(cb => cb.value);

// 2. Ambil checkbox keperluan yang tercentang di Form 2 (jika keperluan juga berupa checkbox)
const selectedKeperluan = Array.from(
    document.querySelectorAll('input[name="keperluan[]"]:checked')
).map(cb => cb.value);

const form2 = {

    tanggalMulai:
        tanggalMulai,

    tanggalSelesai:
        tanggalSelesai,

    kota:
        document.getElementById(
            "kota"
        )?.value || form1.kota,

    transportasi: selectedTransport.length > 0 
        ? selectedTransport.join(', ') 
        : (form1.transportasi || ""),

    keperluan: selectedKeperluan.length > 0 
        ? selectedKeperluan.join(', ') 
        : (form1.keperluan || ""),

    tugas:
        document.getElementById(
            "tugas"
        )?.value || form1.tugas,

    dinas:
        getMoneyValue("dinas"),

    makan:
        getMoneyValue("makan"),

    hotel:
        getMoneyValue("hotel"),

    laundry:
        getMoneyValue("laundry"),

    total:
        getMoneyValue("total"),

    uangMuka:
        getMoneyValue("uangMuka")

};

// ==============================
// GABUNGKAN FORM 1 + FORM 2
// ==============================

const pengajuanBaru = {

    no: nomorSPPD,

    applicant:
        form1.nama,

    destination:
        form2.kota,

    date:
        `${form2.tanggalMulai} - ${form2.tanggalSelesai}`,

    submitted:
        new Date().toLocaleDateString(
            "id-ID",
            {
                day: "2-digit",
                month: "short",
                year: "numeric"
            }
        ),

    status:
        "Menunggu Approval",

    type:
        "waiting",

    owner:
        form1.role || "staff",

    approval:
        true,

    // ==========================
    // DATA FORM 1
    // ==========================

    form1: form1,

    // ==========================
    // DATA FORM 2
    // ==========================

    form2: form2

};

// ==============================
// AMBIL PENGAJUAN LAMA
// ==============================

const existing =
    JSON.parse(
        localStorage.getItem(
            "sppd_applications"
        )
    ) || [];

existing.unshift(
    pengajuanBaru
);

// ==============================
// SIMPAN PENGAJUAN FINAL
// ==============================

localStorage.setItem(
    "sppd_applications",
    JSON.stringify(existing)
);

// ==============================
// BERSIHKAN DRAFT FORM 1
// ==============================

localStorage.removeItem(
    "sppd_form1"
);

localStorage.removeItem(
    "sppd_durasi"
);

// ==============================
// REDIRECT
// ==============================

alert(
    `Pengajuan berhasil dibuat.\nNomor SPPD: ${nomorSPPD}`
);

window.location.href =
    "/dashboard";
}

function getMoneyValue(id) {

const element =
    document.getElementById(id);

if (!element) {
    return 0;
}

const value =
    element.value || "";

return Number(
    value.replace(
        /[^0-9]/g,
        ""
    )
) || 0;
}

document.addEventListener(
"DOMContentLoaded",
function () {

    loadForm1ToILPD();

    setupTanggal();

}
);

/*
|--------------------------------------------------------------------------
| CEGAH INPUT NEGATIF / SPINNER
|--------------------------------------------------------------------------
| Karena input biaya menggunakan text, user tidak akan mendapatkan
| spinner +/- dari input number.
|--------------------------------------------------------------------------
*/

// document.addEventListener("wheel", function(event) {

//     if (
//         document.activeElement &&
//         document.activeElement.classList.contains("currency-input")
//     ) {
//         document.activeElement.blur();
//     }

// });

/*
|--------------------------------------------------------------------------
| INISIALISASI
|--------------------------------------------------------------------------
*/
document.addEventListener("DOMContentLoaded", () => {
isiDataUserLogin(); // Bawa data Pegawai yang sedang login (termasuk Golongan)
loadForm1ToILPD();  // Tampilkan data Form 1 dari localStorage & hitung biaya
});
// resetTarif();