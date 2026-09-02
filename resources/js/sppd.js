const dataPegawai = [
        {
            nama: "Eko Saputra",
            nik: "3276010101010001",
            jabatan: "Staff",
            departemen: "IT"
        },

        {
            nama: "Budi Santoso",
            nik: "3276010202020002",
            jabatan: "Manager GA",
            departemen: "General Affair"
        },

        {
            nama: "Andi Wijaya",
            nik: "3276010404040004",
            jabatan: "Manager",
            departemen: "IT"
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

function isiDataUserLogin() {

    const savedRole = localStorage.getItem("sppd_role");
    const role = currentUserByRole[savedRole] ? savedRole : "staff";

    switchRole(role);
}

function switchRole(role) {

    const user = currentUserByRole[role];

    if (!user) return;

    localStorage.setItem("sppd_role", role);

    document.getElementById("profileName").innerText = user.nama;
    document.getElementById("profileRole").innerText = user.jabatan;
    document.getElementById("nama").value = user.nama;
    document.getElementById("nik").value = user.nik;
    document.getElementById("jabatan").value = user.jabatan;
    document.getElementById("departemen").value = user.departemen;

    closeProfileMenu();
}

function closeProfileMenu() {

    const profileMenu = document.getElementById("profileMenu");
    const profileToggle = document.getElementById("profileToggle");

    profileMenu.classList.add("hidden");
    profileToggle.setAttribute("aria-expanded", "false");
}

function getCurrentUser() {

    const savedRole = localStorage.getItem("sppd_role");

    return currentUserByRole[savedRole] || currentUserByRole.staff;
}

document.addEventListener("DOMContentLoaded", isiDataUserLogin);

/*
|--------------------------------------------------------------------------
| VALIDASI WAKTU
|--------------------------------------------------------------------------
| Hanya angka 1-14.
| Tidak menerima:
| - huruf
| - koma
| - titik
| - minus
| - angka lebih dari 14
*/

function validasiWaktu(input) {

    // Hanya ambil angka
    input.value = input.value.replace(/[^0-9]/g, "");

    // Jika kosong
    if (input.value === "") {
        return;
    }

    // Hilangkan angka 0 di depan
    input.value = input.value.replace(/^0+/, "");

    // Batasi maksimal 14
    let angka = parseInt(input.value);

    if (angka > 14) {
        input.value = "14";
    }

    // Jika angka 0
    if (angka === 0) {
        input.value = "";
    }
}

/*
|--------------------------------------------------------------------------
| SUBMIT SEMENTARA - FRONTEND ONLY
|--------------------------------------------------------------------------
| Belum dikirim ke backend.
| Hanya untuk simulasi alur presentasi.
*/

function submitForm(event) {

    event.preventDefault();

    const form =
        document.getElementById("sppdForm");

    // =========================
    // VALIDASI
    // =========================

    if (!form.checkValidity()) {

        form.reportValidity();

        return;
    }

    // =========================
    // USER YANG SEDANG LOGIN
    // =========================

    const currentUser =
        getCurrentUser();

    // =========================
    // DATA FORM 1
    // =========================

    const dataForm1 = {

        nama: currentUser.nama,

        nik: currentUser.nik,

        jabatan: currentUser.jabatan,

        departemen: currentUser.departemen,

        kota:
            document.getElementById("kota").value,

        waktu:
            document.getElementById("waktu").value,

        keperluan:
            document.getElementById("keperluan").value,

        transportasi:
            document.getElementById("transportasi").value,

        tugas:
            document.getElementById("tugas").value

    };

    // =========================
    // SIMPAN FORM 1 SEMENTARA
    // =========================

    localStorage.setItem(
        "sppd_form1",
        JSON.stringify(dataForm1)
    );

    // =========================
    // LANJUT KE FORM 2
    // =========================

    window.location.href = "/ilpd";

}