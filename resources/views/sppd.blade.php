<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form SPPD - SPPD System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        /* Input / Select */
        .form-input,
        .form-select {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 14px;
            outline: none;
            background: white;
            color: #1e293b;
            transition: all .2s ease;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
        }

        .form-input[readonly] {
            background: #f8fafc;
            color: #475569;
            cursor: not-allowed;
        }

        .form-textarea {
            width: 100%;
            min-height: 130px;
            resize: vertical;
            border: 0;
            outline: none;
            font-size: 14px;
            color: #1e293b;
        }

        .form-textarea::placeholder {
            color: #94a3b8;
        }

        /* Mobile */
        @media (max-width: 767px) {
            .desktop-sidebar {
                position: static;
                width: 100%;
                height: auto;
                border-right: 0;
                border-bottom: 1px solid #e2e8f0;
                padding: 16px;
            }

            .sidebar-menu {
                display: flex;
                overflow-x: auto;
                gap: 6px;
                padding-bottom: 3px;
            }

            .sidebar-menu a {
                flex: 0 0 auto;
                white-space: nowrap;
            }

            .sidebar-bottom {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }

            .top-navbar {
                height: auto;
                padding: 14px 16px;
            }

            .content-wrapper {
                padding: 20px 16px;
            }

            .form-card {
                padding: 18px;
            }

            .form-row {
                flex-direction: column;
                gap: 8px;
                padding: 10px 0;
            }

            .form-label {
                width: 100%;
                padding-top: 0;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions a,
            .form-actions button {
                width: 100%;
                text-align: center;
            }

            .user-info {
                display: none;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-white text-[#1e293b]">

<div class="min-h-screen">

    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}
    <aside
        class="desktop-sidebar fixed left-0 top-0 flex h-screen w-[260px] flex-col gap-6 border-r border-[#e2e8f0] bg-white px-4 py-6"
    >

        {{-- BRAND --}}
        <div class="flex items-center gap-3 pl-3">

            <div class="flex size-9 items-center justify-center rounded-xl bg-[#eff6ff]">
                <span class="text-lg font-bold text-[#2563eb]">
                    S
                </span>
            </div>

            <div class="flex flex-col gap-px">
                <span class="text-[16px] font-bold">
                    SPPD System
                </span>

                <span class="text-[11px] text-[#64748b]">
                    Sistem Perjalanan Dinas
                </span>
            </div>

        </div>


        {{-- MENU --}}
        <nav class="sidebar-menu flex flex-col gap-1">

            <a
                href="/dashboard"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] font-medium text-[#64748b]">
                    ▦
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dashboard
                </span>
            </a>


            {{-- ACTIVE --}}
            <a
                href="/sppd/create"
                class="flex items-center gap-3 rounded-xl bg-[#eff6ff] px-4 py-3"
            >
                <span class="text-[14px] text-[#2563eb]">
                    ▤
                </span>

                <span class="text-[14px] font-semibold text-[#2563eb]">
                    Pengajuan SPPD
                </span>
            </a>


            <a
                href="/ilpd"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ☑
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Perizinan
                </span>
            </a>


            <a
                href="/riwayat"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ◷
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Riwayat Pengajuan
                </span>
            </a>


            <a
                href="/dokumen"
                class="flex items-center gap-3 rounded-xl px-4 py-3"
            >
                <span class="text-[14px] text-[#64748b]">
                    ▱
                </span>

                <span class="text-[14px] font-medium text-[#64748b]">
                    Dokumen & Tiket
                </span>
            </a>

        </nav>


        {{-- MENU BAWAH --}}
        {{-- <div class="sidebar-bottom">

            <div class="mb-2 h-px w-full bg-[#e2e8f0]"></div>

            <nav class="flex flex-col gap-1">

                <a
                    href="#"
                    class="flex items-center gap-3 rounded-xl px-4 py-3"
                >
                    <span class="text-[14px] text-[#64748b]">
                        ○
                    </span>

                    <span class="text-[14px] font-medium text-[#64748b]">
                        Profile
                    </span>
                </a>


                <a
                    href="#"
                    class="flex items-center gap-3 rounded-xl px-4 py-3"
                >
                    <span class="text-[14px] text-[#64748b]">
                        ⚙
                    </span>

                    <span class="text-[14px] font-medium text-[#64748b]">
                        Pengaturan
                    </span>
                </a>

            </nav>

        </div> --}}

    </aside>


    {{-- =========================================================
        MAIN
    ========================================================== --}}
    <main class="main-content ml-[260px] min-h-screen">

        {{-- TOP NAVBAR --}}
        <header
            class="top-navbar flex h-[72px] items-center justify-end border-b border-[#e2e8f0] bg-white px-8"
        >

            <div class="flex items-center gap-5">

                {{-- NOTIFICATION --}}
                <button
                    type="button"
                    class="flex size-10 items-center justify-center rounded-lg hover:bg-slate-50"
                >
                    <span class="text-lg text-[#64748b]">
                        ♢
                    </span>
                </button>


                {{-- USER --}}
                <div class="flex items-center gap-2.5">

                    <div
                        class="flex size-9 items-center justify-center rounded-full border border-[#2563eb] bg-[#eff6ff]"
                    >
                        <span class="text-[13px] font-bold text-[#2563eb]">
                            AR
                        </span>
                    </div>


                    <div class="user-info flex flex-col">

                        <span class="text-[14px] font-semibold">
                            Ahmad Ramzi
                        </span>

                        <span class="text-[11px] text-[#64748b]">
                            Staff IT
                        </span>

                    </div>

                    <span class="text-xs text-[#64748b]">
                        ▼
                    </span>

                </div>

            </div>

        </header>


        {{-- =====================================================
            CONTENT
        ====================================================== --}}
        <div class="content-wrapper px-10 py-8">

            {{-- TITLE --}}
            <div class="mb-6">

                <h1 class="text-[22px] font-extrabold tracking-[-0.5px]">
                    FORM SPPD
                </h1>

                <p class="mt-1 text-[14px] text-[#64748b]">
                    Silakan lengkapi data di bawah ini dengan benar.
                </p>

            </div>


            {{-- =================================================
                FORM CARD
            ================================================== --}}
            <div
                class="form-card rounded-2xl border border-[#f1f5f9] bg-white p-8 shadow-[0px_4px_6px_rgba(15,23,42,0.02)]"
            >

                <form
                    id="sppdForm"
                    class="space-y-0"
                    onsubmit="submitForm(event)"
                >

                    {{-- =================================================
                        DATA PEGAWAI
                    ================================================== --}}

                    {{-- Nama --}}
                    <div class="form-row flex gap-6 py-3">

                        <label
                            for="nama"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            Nama
                        </label>

                        <div class="relative flex-1">

                            <input
                                id="nama"
                                name="nama"
                                type="text"
                                autocomplete="off"
                                placeholder="Ketik nama karyawan"
                                class="form-input"
                                oninput="cariPegawai(this.value)"
                                required
                            >

                            {{-- HASIL PENCARIAN --}}
                            <div
                                id="hasilPencarian"
                                class="absolute left-0 right-0 top-full z-50 mt-1 hidden overflow-hidden rounded-lg border border-[#e2e8f0] bg-white shadow-lg"
                            ></div>

                            {{-- <p class="mt-1.5 text-[11px] text-[#94a3b8]">
                                Masukan Nama.
                            </p> --}}

                        </div>

                    </div>
                    {{-- <div class="form-row flex gap-6 py-3">

                        <label
                            for="nama"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            Nama
                        </label>

                        <div class="flex-1">

                            <select
                                id="nama"
                                name="nama"
                                class="form-select"
                                required
                                onchange="isiDataPegawai()"
                            >
                                <option value="">
                                    Pilih nama pegawai
                                </option>

                                <option value="ahmad">
                                    Ahmad Ramzi
                                </option>

                                <option value="budi">
                                    Budi Santoso
                                </option>

                                <option value="siti">
                                    Siti Rahma
                                </option>

                                <option value="andi">
                                    Andi Wijaya
                                </option>
                            </select>

                            <p class="mt-1.5 text-[11px] text-[#94a3b8]">
                                Pilih nama untuk mengisi data pegawai secara otomatis.
                            </p>

                        </div>

                    </div> --}}


                    {{-- NIK --}}
                    <div class="form-row flex gap-6 py-3">

                        <label
                            for="nik"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            NIK
                        </label>

                        <input
                            id="nik"
                            name="nik"
                            type="text"
                            placeholder="NIK otomatis terisi"
                            class="form-input flex-1"
                            readonly
                        >

                    </div>


                    {{-- Jabatan --}}
                    <div class="form-row flex gap-6 py-3">

                        <label
                            for="jabatan"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            Jabatan
                        </label>

                        <input
                            id="jabatan"
                            name="jabatan"
                            type="text"
                            placeholder="Jabatan otomatis terisi"
                            class="form-input flex-1"
                            readonly
                        >

                    </div>


                    {{-- Departemen --}}
                    <div class="form-row flex gap-6 py-3">

                        <label
                            for="departemen"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            Departemen
                        </label>

                        <input
                            id="departemen"
                            name="departemen"
                            type="text"
                            placeholder="Departemen otomatis terisi"
                            class="form-input flex-1"
                            readonly
                        >

                    </div>


                    {{-- =================================================
                        DATA PERJALANAN
                    ================================================== --}}

                    {{-- Kota --}}
                    <div class="form-row flex gap-6 py-3">

                        <label
                            for="kota"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            Kota Tujuan
                        </label>

                        <input
                            id="kota"
                            name="kota"
                            type="text"
                            placeholder="Masukkan kota tujuan"
                            class="form-input flex-1"
                            required
                        >

                    </div>


                    {{-- Waktu --}}
                    <div class="form-row flex gap-6 py-3">

                        <label
                            for="waktu"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            Waktu
                        </label>

                        <div class="flex-1">

                            <div class="relative">

                                <input
                                    id="waktu"
                                    name="waktu"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="2"
                                    placeholder="Masukkan waktu"
                                    class="form-input pr-20"
                                    required
                                    oninput="validasiWaktu(this)"
                                >

                                <span
                                    class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[13px] text-[#94a3b8]"
                                >
                                    hari
                                </span>

                            </div>

                            <p class="mt-1.5 text-[11px] text-[#94a3b8]">
                                Masukkan angka 1–14 hari.
                            </p>

                        </div>

                    </div>


                    {{-- Keperluan --}}
                    <div class="form-row flex gap-6 py-3">

                        <label
                            for="keperluan"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            Keperluan Dinas
                        </label>

                        <div
                            class="flex min-h-[140px] flex-1 flex-col rounded-lg border border-[#e2e8f0] px-4 py-3 focus-within:border-[#2563eb]"
                        >

                            <span class="text-[14px] text-[#94a3b8]">
                                Jelaskan keperluan dinas Anda (maks. 3 poin)
                            </span>

                            <textarea
                                id="keperluan"
                                name="keperluan"
                                class="form-textarea mt-1 flex-1"
                                placeholder="1.&#10;2.&#10;3."
                                required
                            ></textarea>

                        </div>

                    </div>


                    {{-- Transportasi --}}
                    <div class="form-row flex gap-6 py-3">

                        <label
                            for="transportasi"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            Transportasi
                        </label>

                        <input
                            id="transportasi"
                            name="transportasi"
                            type="text"
                            placeholder="Masukkan jenis transportasi yang digunakan"
                            class="form-input flex-1"
                            required
                        >

                    </div>


                    {{-- Tugas --}}
                    <div class="form-row flex gap-6 py-3">

                        <label
                            for="tugas"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold"
                        >
                            Tugas
                        </label>

                        <div
                            class="flex min-h-[160px] flex-1 flex-col rounded-lg border border-[#e2e8f0] px-4 py-3 focus-within:border-[#2563eb]"
                        >

                            <span class="text-[14px] text-[#94a3b8]">
                                Jelaskan tugas yang akan dilakukan selama perjalanan dinas (maks. 4 poin)
                            </span>

                            <textarea
                                id="tugas"
                                name="tugas"
                                class="form-textarea mt-1 flex-1"
                                placeholder="1.&#10;2.&#10;3.&#10;4."
                                required
                            ></textarea>

                        </div>

                    </div>


                    {{-- =================================================
                        BUTTON
                    ================================================== --}}
                    <div class="form-actions flex justify-end gap-3 pt-6">

                        <a
                            href="/dashboard"
                            class="rounded-lg border border-[#e2e8f0] bg-white px-5 py-2.5 text-[14px] font-semibold text-[#64748b] transition hover:bg-slate-50"
                        >
                            Batal
                        </a>


                        <button
                            type="submit"
                            class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]"
                        >
                            Ajukan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>


{{-- =============================================================
    JAVASCRIPT - FRONTEND ONLY
============================================================== --}}
<script>

    /*
    |--------------------------------------------------------------------------
    | DATA PEGAWAI DUMMY
    |--------------------------------------------------------------------------
    | Untuk sementara data ini hanya berada di FE.
    | Nanti ketika backend sudah dibuat, bagian ini bisa diganti
    | dengan data dari database/API.
    */

    /*
|--------------------------------------------------------------------------
| DATA PEGAWAI DUMMY
|--------------------------------------------------------------------------
| Sementara masih berupa data FE.
| Nanti bisa diganti dengan database/API.
*/

    const dataPegawai = [
        {
            nama: "Ahmad Ramzi",
            nik: "3276010101010001",
            jabatan: "Staff IT",
            departemen: "IT"
        },
        {
            nama: "Ahmad Fauzan",
            nik: "3276010101010005",
            jabatan: "Staff Finance",
            departemen: "Finance"
        },
        {
            nama: "Budi Santoso",
            nik: "3276010202020002",
            jabatan: "Supervisor",
            departemen: "General Affair"
        },
        {
            nama: "Siti Rahma",
            nik: "3276010303030003",
            jabatan: "Staff Finance",
            departemen: "Finance"
        },
        {
            nama: "Andi Wijaya",
            nik: "3276010404040004",
            jabatan: "Manager",
            departemen: "Human Resource"
        }
    ];


    /*
    |--------------------------------------------------------------------------
    | SEARCH / AUTOCOMPLETE
    |--------------------------------------------------------------------------
    */

    function cariPegawai(keyword) {

        const hasil = document.getElementById("hasilPencarian");

        keyword = keyword.trim().toLowerCase();

        // Reset data pegawai jika input dikosongkan
        if (keyword === "") {

            hasil.innerHTML = "";
            hasil.classList.add("hidden");

            document.getElementById("nik").value = "";
            document.getElementById("jabatan").value = "";
            document.getElementById("departemen").value = "";

            return;
        }


        // Cari nama yang sesuai
        const hasilCari = dataPegawai.filter(pegawai =>
            pegawai.nama.toLowerCase().includes(keyword)
        );


        // Tidak ada hasil
        if (hasilCari.length === 0) {

            hasil.innerHTML = `
                <div class="px-4 py-3 text-[13px] text-[#94a3b8]">
                    Data karyawan tidak ditemukan.
                </div>
            `;

            hasil.classList.remove("hidden");

            return;
        }


        // Tampilkan hasil pencarian
        hasil.innerHTML = hasilCari.map((pegawai, index) => `

            <button
                type="button"
                class="w-full border-b border-[#f1f5f9] px-4 py-3 text-left transition last:border-b-0 hover:bg-[#f8fafc]"
                onclick="pilihPegawai(${dataPegawai.indexOf(pegawai)})"
            >

                <div class="text-[14px] font-semibold text-[#1e293b]">
                    ${pegawai.nama}
                </div>

                <div class="mt-1 text-[11px] text-[#64748b]">
                    ${pegawai.jabatan} • ${pegawai.departemen}
                </div>

            </button>

        `).join("");

        hasil.classList.remove("hidden");
    }


    /*
    |--------------------------------------------------------------------------
    | PILIH PEGAWAI
    |--------------------------------------------------------------------------
    */

    function pilihPegawai(index) {

        const pegawai = dataPegawai[index];

        if (!pegawai) {
            return;
        }


        // Isi Nama
        document.getElementById("nama").value = pegawai.nama;


        // Isi otomatis data lainnya
        document.getElementById("nik").value = pegawai.nik;

        document.getElementById("jabatan").value = pegawai.jabatan;

        document.getElementById("departemen").value = pegawai.departemen;


        // Tutup hasil pencarian
        const hasil = document.getElementById("hasilPencarian");

        hasil.innerHTML = "";
        hasil.classList.add("hidden");
    }
    // const dataPegawai = {

    //     ahmad: {
    //         nama: "Ahmad Ramzi",
    //         nik: "3276010101010001",
    //         jabatan: "Staff IT",
    //         departemen: "IT"
    //     },

    //     budi: {
    //         nama: "Budi Santoso",
    //         nik: "3276010202020002",
    //         jabatan: "Supervisor",
    //         departemen: "General Affair"
    //     },

    //     siti: {
    //         nama: "Siti Rahma",
    //         nik: "3276010303030003",
    //         jabatan: "Staff Finance",
    //         departemen: "Finance"
    //     },

    //     andi: {
    //         nama: "Andi Wijaya",
    //         nik: "3276010404040004",
    //         jabatan: "Manager",
    //         departemen: "Human Resource"
    //     }

    // };


    // /*
    // |--------------------------------------------------------------------------
    // | AUTO ISI DATA PEGAWAI
    // |--------------------------------------------------------------------------
    // */

    // function isiDataPegawai() {

    //     const nama = document.getElementById("nama").value;

    //     const nik = document.getElementById("nik");
    //     const jabatan = document.getElementById("jabatan");
    //     const departemen = document.getElementById("departemen");

    //     // Jika belum memilih nama
    //     if (!nama) {

    //         nik.value = "";
    //         jabatan.value = "";
    //         departemen.value = "";

    //         return;
    //     }

    //     // Ambil data dari object dummy
    //     const pegawai = dataPegawai[nama];

    //     if (!pegawai) {
    //         return;
    //     }

    //     // Isi otomatis
    //     nik.value = pegawai.nik;
    //     jabatan.value = pegawai.jabatan;
    //     departemen.value = pegawai.departemen;
    // }


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

        const form = document.getElementById("sppdForm");

        // Validasi HTML
        if (!form.checkValidity()) {

            form.reportValidity();

            return;
        }

        const data = {

            nama: document.getElementById("nama").value,
            nik: document.getElementById("nik").value,
            jabatan: document.getElementById("jabatan").value,
            departemen: document.getElementById("departemen").value,
            kota: document.getElementById("kota").value,
            waktu: document.getElementById("waktu").value,
            keperluan: document.getElementById("keperluan").value,
            transportasi: document.getElementById("transportasi").value,
            tugas: document.getElementById("tugas").value

        };

        console.log("Data SPPD:", data);

        alert(
            "Pengajuan SPPD berhasil disiapkan!\n\n" +
            "Nama: " + data.nik + " - " + data.nama + "\n" +
            "Jabatan: " + data.jabatan + "\n" +
            "Departemen: " + data.departemen + "\n" +
            "Tujuan: " + data.kota + "\n" +
            "Waktu: " + data.waktu + " hari\n\n" +
            "(Saat ini masih simulasi FE)"
        );

    }

</script>

</body>
</html>