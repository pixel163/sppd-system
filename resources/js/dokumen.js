const dataByRole = {

    staff: [

        {
            no: 'SPPD-2026-00124',
            tujuan: 'Bandung',
            tanggal: '20 - 22 Agustus 2026',
            status: 'Selesai',
            pemohon: 'Eko Saputra',
            tanggalPengajuan: '5 Agustus 2026, 09:00 WIB',
            dokumen: ['sppd', 'perizinan', 'tiket']
        },

        {
            no: 'SPPD-2026-00115',
            tujuan: 'Jakarta',
            tanggal: '10 - 12 Agustus 2026',
            status: 'Selesai',
            pemohon: 'Eko Saputra',
            tanggalPengajuan: '2 Agustus 2026, 10:20 WIB',
            dokumen: ['sppd', 'perizinan', 'tiket']
        },

        {
            no: 'SPPD-2026-00102',
            tujuan: 'Surabaya',
            tanggal: '28 - 30 Juli 2026',
            status: 'Approval',
            pemohon: 'Eko Saputra',
            tanggalPengajuan: '20 Juli 2026, 08:40 WIB',
            dokumen: ['sppd', 'perizinan', 'tiket']
        },

        {
            no: 'SPPD-2026-00087',
            tujuan: 'Yogyakarta',
            tanggal: '15 - 17 Juli 2026',
            status: 'Approval',
            pemohon: 'Eko Saputra',
            tanggalPengajuan: '10 Juli 2026, 13:15 WIB',
            dokumen: ['sppd', 'perizinan', 'tiket']
        },

        {
            no: 'SPPD-2026-00096',
            tujuan: 'Semarang',
            tanggal: '10 - 13 Juni 2026',
            status: 'Selesai',
            pemohon: 'Eko Saputra',
            tanggalPengajuan: '9 Juni 2026, 13:15 WIB',
            dokumen: ['sppd', 'perizinan', 'tiket']
        },

    ],

    manager: [

        {
            no: 'SPPD-2026-00124',
            tujuan: 'Bandung',
            tanggal: '20 - 22 Agustus 2026',
            status: 'Selesai',
            pemohon: 'Andi Wijaya',
            tanggalPengajuan: '5 Agustus 2026, 09:00 WIB',
            dokumen: ['sppd', 'perizinan', 'tiket']
        },

        {
            no: 'SPPD-2026-00118',
            tujuan: 'Jakarta',
            tanggal: '18 - 19 Agustus 2026',
            status: 'Approval',
            pemohon: 'Budi Santoso',
            tanggalPengajuan: '8 Agustus 2026, 10:00 WIB',
            dokumen: ['sppd', 'perizinan']
        },

        {
            no: 'SPPD-2026-00110',
            tujuan: 'Bogor',
            tanggal: '12 - 13 Agustus 2026',
            status: 'Approval',
            pemohon: 'Andi Pratama',
            tanggalPengajuan: '6 Agustus 2026, 14:00 WIB',
            dokumen: ['sppd']
        }

    ],

    ga: [

        {
            no: 'SPPD-2026-00124',
            tujuan: 'Bandung',
            tanggal: '20 - 22 Agustus 2026',
            status: 'Selesai',
            pemohon: 'Eko Saputra',
            tanggalPengajuan: '5 Agustus 2026, 09:00 WIB',
            dokumen: ['sppd', 'perizinan', 'tiket']
        },

        {
            no: 'SPPD-2026-00118',
            tujuan: 'Jakarta',
            tanggal: '18 - 19 Agustus 2026',
            status: 'Approval',
            pemohon: 'Budi Santoso',
            tanggalPengajuan: '8 Agustus 2026, 10:00 WIB',
            dokumen: ['sppd', 'perizinan']
        },

        {
            no: 'SPPD-2026-00110',
            tujuan: 'Bogor',
            tanggal: '12 - 13 Agustus 2026',
            status: 'Approval',
            pemohon: 'Andi Pratama',
            tanggalPengajuan: '6 Agustus 2026, 14:00 WIB',
            dokumen: ['sppd']
        },

        {
            no: 'SPPD-2026-00102',
            tujuan: 'Surabaya',
            tanggal: '28 - 30 Juli 2026',
            status: 'Selesai',
            pemohon: 'Eko Saputra',
            tanggalPengajuan: '20 Juli 2026, 08:40 WIB',
            dokumen: ['sppd', 'perizinan', 'tiket']
        },

        {
            no: 'SPPD-2026-00087',
            tujuan: 'Yogyakarta',
            tanggal: '15 - 17 Juli 2026',
            status: 'Approval',
            pemohon: 'Dewi Lestari',
            tanggalPengajuan: '10 Juli 2026, 13:15 WIB',
            dokumen: ['sppd', 'perizinan']
        },

    ]

};

/* =========================================================
    ROLE CONFIG
========================================================== */

const roleConfig = {

    staff: {
        name: 'Eko Saputra',
        label: 'Staff',
        description: 'Menampilkan pengajuan SPPD milik Anda.',
        note: 'Pastikan dokumen perjalanan dicetak sebelum berangkat untuk keperluan pelaporan dinas.'
    },

    manager: {
        name: 'Andi Wijaya',
        label: 'Manager',
        description: 'Menampilkan pengajuan SPPD yang berada dalam lingkup Anda.',
        note: 'Periksa informasi pengajuan sebelum memberikan keputusan pada proses perjalanan dinas.'
    },

    ga: {
        name: 'Budi Santoso',
        label: 'General Affair',
        description: 'Menampilkan seluruh pengajuan SPPD yang perlu dikelola oleh GA.',
        note: 'Periksa kebutuhan tiket dan budget perjalanan sesuai dengan pengajuan yang tersedia.'
    }

};

let currentRole =
    localStorage.getItem('sppd_role') || 'staff';

let currentData = [];
let selectedId = null;
let statusFilter = 'all';

/* =========================================================
    HELPER
========================================================== */

function getStatusClass(status) {

    if (status === 'Selesai') {

        return 'bg-[#e8f5e9] text-[#2e7d32]';

    }

    if (status === 'Approval') {

        return 'bg-[#fff3e0] text-[#ef6c00]';

    }

    return 'bg-[#f5f5f5] text-[#616161]';

}

function getDocumentData(type) {

    const documents = {

        sppd: {
            title: '1. Form SPPD',
            description: 'Formulir Surat Perjalanan Dinas yang telah diisi oleh Staff.',
            date: 'Diajukan: 5 Agustus 2026, 09:00 WIB',
            iconBg: 'bg-[#eff6ff]',
            iconColor: 'text-[#0d6efd]',
            border: 'border-[#0d6efd]'
        },

        perizinan: {
            title: '2. Form Perizinan (Disetujui)',
            description: 'Formulir perizinan yang telah diperiksa oleh GA.',
            date: 'Diperiksa: 6 Agustus 2026, 14:20 WIB',
            iconBg: 'bg-[#e8f5e9]',
            iconColor: 'text-[#2e7d32]',
            border: 'border-[#2e7d32]'
        },

        tiket: {
            title: '3. Tiket Perjalanan',
            description: 'E-ticket perjalanan dinas yang telah disiapkan oleh GA.',
            date: 'Diterbitkan: 6 Agustus 2026, 15:10 WIB',
            iconBg: 'bg-[#f3e5f5]',
            iconColor: 'text-[#8e24aa]',
            border: 'border-[#8e24aa]'
        }

    };

    return documents[type];

}

/* =========================================================
    RENDER ROLE
========================================================== */

// function renderRole() {

//     const config = roleConfig[currentRole];

//     document.getElementById('profileName').textContent =
//         config.name;

//     document.getElementById('profileRole').textContent =
//         config.label;

//     document.getElementById('pageDescription').textContent =
//         config.description;

//     document.getElementById('roleBadge').textContent =
//         config.label.toUpperCase();

//     document.getElementById('roleNote').textContent =
//         config.note;

//     statusFilter = 'all';

//     currentData = dataByRole[currentRole];

//     selectedId =
//         currentData.length > 0
//             ? currentData[0].no
//             : null;

//     document.getElementById('searchInput').value = '';

//     renderList();

//     renderDetail();

// }

/* =========================================================
    SWITCH ROLE
========================================================== */

function switchRole(role) {

    currentRole = role;

    localStorage.setItem('sppd_role', role);

    document.getElementById('profileMenu')
        .classList.add('hidden');

    renderRole();

}

/* =========================================================
    FILTER + SEARCH
========================================================== */

function getFilteredData() {

    const keyword =
        document.getElementById('searchInput')
            .value
            .toLowerCase()
            .trim();

    return currentData.filter(item => {

        const matchesSearch =
            item.no.toLowerCase().includes(keyword) ||
            item.tujuan.toLowerCase().includes(keyword) ||
            item.pemohon.toLowerCase().includes(keyword);

        const matchesStatus =
            statusFilter === 'all' ||
            item.status === statusFilter;

        return matchesSearch && matchesStatus;

    });

}

function setStatusFilter(status) {

    statusFilter = status;

    document.getElementById('filterMenu')
        .classList.add('hidden');

    renderList();

}

/* =========================================================
    RENDER LIST
========================================================== */

function renderList() {

    const list =
        document.getElementById('pengajuanList');

    const empty =
        document.getElementById('emptyState');

    const pagination =
        document.getElementById('pagination');

    const filtered =
        getFilteredData();

    list.innerHTML = '';

    if (filtered.length === 0) {

        empty.classList.remove('hidden');
        pagination.innerHTML = '';

        return;

    }

    empty.classList.add('hidden');


    filtered.forEach(item => {

        const active =
            selectedId === item.no;

        const statusClass =
            getStatusClass(item.status);

        const div =
            document.createElement('button');

        div.type = 'button';

        div.className = `
            flex w-full flex-col gap-2 p-4 text-left transition
            ${active
                ? 'rounded-xl border border-[#0d6efd] bg-[#eff6ff]'
                : 'border-b border-[#f1f5f9] hover:bg-[#f8fafc]'
            }
        `;

        div.innerHTML = `

            <div class="flex items-center justify-between gap-2">

                <span class="text-[14px] font-bold">
                    ${item.no}
                </span>

                <span class="shrink-0 rounded-md px-2 py-1 text-[11px] font-semibold ${statusClass}">
                    ${item.status}
                </span>

            </div>

            <div class="flex flex-col gap-1">

                <span class="text-[13px] text-[#64748b]">
                    ${item.tujuan}
                </span>

                <span class="text-[12px] text-[#94a3b8]">
                    ${item.tanggal}
                </span>

            </div>

        `;

        div.addEventListener('click', () => {

            selectedId = item.no;

            renderList();
            renderDetail();

        });

        list.appendChild(div);

    });

    pagination.innerHTML = `

        <button
            type="button"
            class="size-5 text-[#94a3b8]"
            onclick="fakePagination('previous')"
        >
            ‹
        </button>

        <button
            type="button"
            class="flex size-8 items-center justify-center rounded-md border border-[#0d6efd] bg-[#eff6ff] text-[13px] font-semibold text-[#0d6efd]"
        >
            1
        </button>

        <button
            type="button"
            class="size-8 text-[13px] text-[#64748b]"
            onclick="fakePagination(2)"
        >
            2
        </button>

        <button
            type="button"
            class="size-8 text-[13px] text-[#64748b]"
            onclick="fakePagination(3)"
        >
            3
        </button>

        <button
            type="button"
            class="size-5 text-[#94a3b8]"
            onclick="fakePagination('next')"
        >
            ›
        </button>

    `;

}

function fakePagination(page) {

    alert(
        `Pagination halaman ${page} masih simulasi FE. Nanti akan menggunakan pagination dari backend.`
    );

}

/* =========================================================
    RENDER DETAIL
========================================================== */

function renderDetail() {

    const item =
        currentData.find(
            data => data.no === selectedId
        );

    if (!item) {

        document.getElementById('detailNo').textContent = '-';
        document.getElementById('detailStatus').textContent = '-';
        document.getElementById('detailTujuan').textContent = '-';
        document.getElementById('detailTanggal').textContent = '-';
        document.getElementById('detailPemohon').textContent = '-';
        document.getElementById('detailTanggalPengajuan').textContent = '-';
        document.getElementById('documentList').innerHTML = '';

        return;

    }

    document.getElementById('detailNo').textContent =
        item.no;

    const statusElement =
        document.getElementById('detailStatus');

    statusElement.textContent =
        item.status;

    statusElement.className =
        `rounded-md px-2 py-1 text-[11px] font-semibold ${getStatusClass(item.status)}`;

    document.getElementById('detailTujuan').textContent =
        item.tujuan;

    document.getElementById('detailTanggal').textContent =
        item.tanggal;

    document.getElementById('detailPemohon').textContent =
        item.pemohon;

    document.getElementById('detailTanggalPengajuan').textContent =
        item.tanggalPengajuan;

    document.getElementById('documentCount').textContent =
        `${item.dokumen.length} dokumen`;

    renderDocuments(item);

}

/* =========================================================
    RENDER DOCUMENTS
========================================================== */

function renderDocuments(item) {

    const container =
        document.getElementById('documentList');

    container.innerHTML = '';

    item.dokumen.forEach(type => {

        const doc =
            getDocumentData(type);

        const wrapper =
            document.createElement('div');

        wrapper.className =
            'flex flex-col gap-4 rounded-xl border border-[#f1f5f9] p-4 sm:flex-row sm:items-center';

        wrapper.innerHTML = `

            <div class="flex size-11 shrink-0 items-center justify-center rounded-lg ${doc.iconBg}">

                <span class="${doc.iconColor} text-lg">
                    ▣
                </span>

            </div>

            <div class="min-w-0 flex-1">

                <h3 class="text-[15px] font-semibold">
                    ${doc.title}
                </h3>

                <p class="mt-1 text-[12px] text-[#64748b]">
                    ${doc.description}
                </p>

                <p class="mt-1 text-[11px] text-[#94a3b8]">
                    ${doc.date}
                </p>

            </div>

            <div class="flex shrink-0 gap-2">

                <button
                    type="button"
                    class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b]"
                    onclick="openDocumentModal('${type}', '${item.no}')"
                >

                    Lihat
                    
                    <span class="text-[#64748b]">👁</span>

                </button>

            </div>

        `;

        container.appendChild(wrapper);

    });

}

/* =========================================================
    MODAL
========================================================== */

function openDocumentModal(type, sppdNo) {

    const doc =
        getDocumentData(type);

    document.getElementById('modalTitle').textContent =
        `${doc.title} - ${sppdNo}`;

    document.getElementById('modalDescription').textContent =
        doc.description;

    document.getElementById('documentModal')
        .classList.remove('hidden');

    document.getElementById('documentModal')
        .classList.add('flex');

}

window.openDocumentModal = openDocumentModal;

function closeDocumentModal() {
    
    document.getElementById('documentModal')
    .classList.add('hidden');
    
    document.getElementById('documentModal')
    .classList.remove('flex');
    
}

window.closeDocumentModal = closeDocumentModal;

function printSpecificDocument(type, sppdNo) {

    const doc =
        getDocumentData(type);

    const printWindow =
        window.open('', '_blank', 'width=900,height=700');

    printWindow.document.write(`

        <!DOCTYPE html>

        <html>

        <head>

            <title>${doc.title} - ${sppdNo}</title>

            <style>

                body {
                    font-family: Arial, sans-serif;
                    padding: 40px;
                }

                h1 {
                    margin-bottom: 10px;
                }

                .box {
                    margin-top: 30px;
                    padding: 30px;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                }

            </style>

        </head>

        <body>

            <h1>${doc.title}</h1>

            <p>Nomor SPPD: ${sppdNo}</p>

            <div class="box">

                <p>
                    Preview dokumen FE.
                </p>

                <p>
                    File asli akan dihubungkan
                    setelah backend tersedia.
                </p>

            </div>

            <script>
                window.print();
            <\/script>

        </body>

        </html>

    `);

    printWindow.document.close();

}

window.printSpecificDocument = printSpecificDocument;

function printDocument() {

    const title =
        document.getElementById('modalTitle').textContent;

    const printWindow =
        window.open('', '_blank', 'width=900,height=700');

    printWindow.document.write(`

        <html>

        <head>

            <title>${title}</title>

        </head>

        <body style="font-family: Arial; padding: 40px">

            <h1>${title}</h1>

            <p>Preview dokumen FE.</p>

            <script>
                window.print();
            <\/script>

        </body>

        </html>

    `);

    printWindow.document.close();

}

window.printDocument = printDocument;

/* =========================================================
    PROFILE DROPDOWN
========================================================== */

/* =========================================================
    SEARCH
========================================================== */

// document
//     .getElementById('searchInput')
//     .addEventListener('input', function() {

//         renderList();

//     });

/* =========================================================
    FILTER BUTTON
========================================================== */

// document
//     .getElementById('filterButton')
//     .addEventListener('click', function(event) {

//         event.stopPropagation();

//         document
//             .getElementById('filterMenu')
//             .classList.toggle('hidden');

//     });

/* =========================================================
    DETAIL BUTTON
========================================================== */

document
    .getElementById('detailButton')
    .addEventListener('click', function() {

        const item =
            currentData.find(
                data => data.no === selectedId
            );

        if (!item) return;

        alert(
            `Detail ${item.no}\n\n` +
            `Pemohon: ${item.pemohon}\n` +
            `Tujuan: ${item.tujuan}\n` +
            `Tanggal: ${item.tanggal}\n` +
            `Status: ${item.status}\n\n` +
            `Detail ini sementara masih simulasi FE.`
        );

    });

/* =========================================================
    SIDEBAR MOBILE
========================================================== */

function openSidebar() {

    document
        .getElementById('sidebar')
        .classList.remove('-translate-x-full');

    document
        .getElementById('sidebarOverlay')
        .classList.remove('hidden');

}

function closeSidebar() {

    document
        .getElementById('sidebar')
        .classList.add('-translate-x-full');

    document
        .getElementById('sidebarOverlay')
        .classList.add('hidden');

}

function printItem(type, doc) {

    if (!doc) {
        alert('Data tidak ditemukan!');
        return;
    }

    let htmlContent = '';

    // 1. Pilih Layout berdasarkan Tipe Dokumen
    if (type === 'form_sppd') {
        htmlContent = generateSppdLayout(doc); // Fungsi SPPD kamu
    } else if (type === 'form_ilpd') {
        htmlContent = generateIlpdLayout(doc); // Memanggil fungsi ILPD di atas
    } else if (type === 'tiket') {
        htmlContent = generateTiketLayout(doc);
    } else {
        alert('Tipe dokumen tidak valid!');
        return;
    }

    // 2. Buka jendela cetak
    const printWindow = window.open('', '_blank', 'width=900,height=700');

    if (printWindow) {
        // Tulis kode HTML ke jendela baru
        printWindow.document.open();
        printWindow.document.write(htmlContent);
        printWindow.document.close();
    } else {
        alert('Pop-up terblokir! Harap izinkan pop-up di browser Anda.');
    }

    // printWindow.document.write(`

    // `);

    // printWindow.document.close();

    // printWindow.focus();

    // printWindow.print();

}

window.printItem = printItem;

function generateSppdLayout(doc) {
    // const sppd = doc.sppd || {};
    // const user = doc.user || {};
    // const kota = doc.kota || {};

    // Masukkan kode HTML ILPD kamu di dalam template string backtick (`)
    return `
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Surat Perintah Perjalanan Dinas (SPPD)</title>
            <style>
                /* Setup Halaman Cetak A4 */
                @page {
                    size: A4 portrait;
                    margin: 15mm 20mm 15mm 20mm;
                }

                body {
                    font-family: 'Times New Roman', Times, serif; /* Standar surat resmi */
                    font-size: 11pt;
                    line-height: 1.4;
                    color: #000;
                    background-color: #f1f5f9;
                    margin: 0;
                    padding: 20px;
                }

                /* Container Dokumen (Mode Preview di Screen) */
                .page {
                    width: 210mm;
                    min-height: 297mm;
                    padding: 20mm;
                    margin: 0 auto;
                    background: #fff;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                    box-sizing: border-box;
                }

                /* KOP SURAT */
                .kop-surat {
                    display: table;
                    width: 100%;
                    border-bottom: 2px solid #000;
                    padding-bottom: 10px;
                    margin-bottom: 20px;
                }

                .kop-col {
                    display: table-cell;
                    vertical-align: middle;
                }

                .kop-logo {
                    width: 20%;
                }

                .kop-logo img {
                    max-width: 80px;
                    height: auto;
                }

                .kop-perusahaan {
                    width: 55%;
                    text-align: center;
                }

                .kop-perusahaan h2 {
                    margin: 0;
                    font-size: 14pt;
                    font-weight: bold;
                    text-transform: uppercase;
                }

                .kop-perusahaan p {
                    margin: 2px 0 0 0;
                    font-size: 9pt;
                }

                .kop-nomor {
                    width: 25%;
                    text-align: right;
                    font-size: 9pt;
                }

                .nomor-box {
                    border: 1px solid #000;
                    display: inline-block;
                    text-align: left;
                    width: 100%;
                }

                .nomor-box div {
                    padding: 4px 6px;
                }

                .nomor-box div:first-child {
                    border-bottom: 1px solid #000;
                }

                /* JUDUL SURAT */
                .judul-surat {
                    text-align: center;
                    margin-bottom: 25px;
                }

                .judul-surat h1 {
                    font-size: 13pt;
                    font-weight: bold;
                    text-decoration: underline;
                    text-transform: uppercase;
                    margin: 0;
                }

                /* SECTION HEADER & TABEL DATA */
                .section-header {
                    font-weight: bold;
                    text-transform: uppercase;
                    margin-top: 15px;
                    margin-bottom: 8px;
                    font-size: 11pt;
                }

                .form-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15px;
                }

                .form-table td {
                    padding: 5px 0;
                    vertical-align: top;
                }

                .label-col {
                    width: 130px;
                }

                .colon-col {
                    width: 15px;
                    text-align: center;
                }

                .value-box {
                    border: 1px solid #94a3b8;
                    border-radius: 4px;
                    padding: 6px 10px;
                    min-height: 18px;
                    background-color: #fafafa;
                }

                .value-box.large {
                    min-height: 80px;
                }

                /* TANGGAL & TANDA TANGAN */
                .ttd-section {
                    margin-top: 30px;
                }

                .tanggal-surat {
                    margin-bottom: 15px;
                }

                .ttd-table {
                    width: 100%;
                    border-collapse: collapse;
                    text-align: center;
                    table-layout: fixed;
                }

                .ttd-table th {
                    font-weight: bold;
                    font-size: 9.5pt;
                    padding-bottom: 60px; /* Space untuk Tanda Tangan */
                    vertical-align: top;
                }

                .ttd-table td {
                    font-size: 9.5pt;
                    vertical-align: bottom;
                }

                .nama-ttd {
                    font-weight: bold;
                    text-decoration: underline;
                }

                /* CSS KHUSUS PRINT */
                @media print {
                    body {
                        background: none;
                        padding: 0;
                    }

                    .page {
                        width: 100%;
                        min-height: auto;
                        box-shadow: none;
                        padding: 0;
                        margin: 0;
                    }

                    .value-box {
                        background-color: transparent !important;
                        border-color: #000 !important;
                    }

                    .no-print {
                        display: none !important;
                    }
                }

                /* Tombol Print untuk Preview */
                .btn-print {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 10px 20px;
                    background: #0284c7;
                    color: white;
                    border: none;
                    border-radius: 6px;
                    font-weight: bold;
                    cursor: pointer;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                }
                .btn-print:hover { background: #0369a1; }
            </style>
        </head>
        <body>

            <!-- Tombol Khusus Layar Preview -->
            <button class="btn-print no-print" onclick="window.print()">Cetak Dokumen</button>

            <div class="page">
                <!-- 1. KOP SURAT -->
                <div class="kop-surat">
                    <div class="kop-col kop-logo">
                        <!-- Ganti src dengan logo perusahaan kamu -->
                        <img src="https://via.placeholder.com/80x80?text=LOGO" alt="Logo">
                    </div>
                    <div class="kop-col kop-perusahaan">
                        <h2>PT. NAMA PERUSAHAAN ANDA</h2>
                        <p>Jl. Jendral Sudirman No. 123, Jakarta Selatan</p>
                        <p>Telp: (021) 555-0192 | Email: info@perusahaan.com</p>
                    </div>
                    <div class="kop-col kop-nomor">
                        <div class="nomor-box">
                            <div><strong>No. Doc:</strong> SPPD/2026/001</div>
                            <div><strong>Revisi:</strong> 00</div>
                        </div>
                    </div>
                </div>

                <!-- 2. JUDUL SURAT -->
                <div class="judul-surat">
                    <h1>SURAT PERINTAH PERJALANAN DINAS</h1>
                </div>

                <!-- 3. BAGIAN 1: DITUGASKAN KEPADA -->
                <div class="section-header">Dengan ini ditugaskan kepada:</div>
                <table class="form-table">
                    <tr>
                        <td class="label-col">Nama</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${doc.user?.name || doc.name || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">NIK</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${doc.user?.nik || doc.nik || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Jabatan</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${doc.user?.jabatan?.name || doc.user?.jabatan || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Departemen</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${doc.user?.department?.name || doc.user?.name || '-'}</div></td>
                    </tr>
                </table>

                <!-- 4. BAGIAN 2: PERJALANAN DINAS -->
                <div class="section-header">Untuk melakukan perjalanan dinas:</div>
                <table class="form-table">
                    <tr>
                        <td class="label-col">Kota Tujuan</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${doc.kota?.name || doc.name || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Waktu</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${doc.durasi || doc.durasi || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Keperluan</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${doc.keperluan?.name || doc.name || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Jenis Transportasi</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${doc.transport?.name || doc.name || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Tugas</td>
                        <td class="colon-col">:</td>
                        <td>
                            <div class="value-box large">
                                ${doc.tugas || doc.tugas || '-'}
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- 5. TANGGAL & MASA TANDA TANGAN (5 KOLOM) -->
                <div class="ttd-section">
                    <div class="tanggal-surat">
                        Jakarta, ......................... 20....
                    </div>

                    <table class="ttd-table">
                        <tr>
                            <th>Pemohon</th>
                            <th>Atasan Langsung</th>
                            <th>HRD / GA</th>
                            <th>Finance</th>
                            <th>Direksi</th>
                        </tr>
                        <tr>
                            <td>
                                <div class="nama-ttd">( ........................ )</div>
                                <div>Staf</div>
                            </td>
                            <td>
                                <div class="nama-ttd">( ........................ )</div>
                                <div>Manager / Team Lead</div>
                            </td>
                            <td>
                                <div class="nama-ttd">( ........................ )</div>
                                <div>HR Manager</div>
                            </td>
                            <td>
                                <div class="nama-ttd">( ........................ )</div>
                                <div>Finance Dept</div>
                            </td>
                            <td>
                                <div class="nama-ttd">( ........................ )</div>
                                <div>Direktur</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </body>
        </html>
    `;
}

// Fungsi khusus untuk me-render HTML ILPD
function generateIlpdLayout(doc) {
    console.log("ISI DATA DOC/ILPD:", doc);
    console.log("ISI DATA SPPD:", doc?.sppd);
    console.log("ISI DATA Kota:", doc?.sppd?.kota);
    const ilpd = doc || {};
    const sppd = doc.sppd || {};
    // const kota = sppd.kota || {};
    // const user = doc.user || {};
    const perkiraan = doc.detail_ilpd || {};
    const realisasi = doc.realisasi_biaya || {};
    // // Ambil data tiket jika ada
    // const tiketList = ilpd.tikets || ilpd.tiket || [];
    // const tiket = Array.isArray(tiketList) ? (tiketList[0] || {}) : tiketList;

    // Masukkan kode HTML ILPD kamu di dalam template string backtick (`)
    return `
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Cetak ILPD - ${doc.no_ilpd || '-'}</title>
            <style>
                * { box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
                body { margin: 0; padding: 15px; color: #000; font-size: 11px; }
                
                .table-doc { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
                .table-doc th, .table-doc td { border: 1px solid #000; padding: 4px 6px; vertical-align: top; }
                
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .font-bold { font-weight: bold; }
                .bg-gray { background-color: #f1f5f9; }
                
                .title-header { font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase; }
                .company-name { font-size: 12px; font-weight: bold; }
                
                .section-title { font-weight: bold; background-color: #e2e8f0; padding: 4px; border: 1px solid #000; border-bottom: none; }
                .ttd-box { height: 45px; }

                @media print {
                    @page { size: A4 portrait; margin: 10mm; }
                    body { padding: 0; }
                }
            </style>
        </head>
        <body>

            <!-- 1. HEADER SURAT & TTD ATAS -->
            <table class="table-doc">
                <tr>
                    <td colspan="8" class="title-header">${ilpd.nama_surat || 'IZIN DAN LAPORAN PERJALANAN DINAS (ILPD)'}</td>
                </tr>
                <tr>
                    <td colspan="4" class="company-name">${ilpd.nama_perusahaan || 'PT. NAMA PERUSAHAAN'}</td>
                    <td colspan="4" class="text-right"><strong>No:</strong> ${doc.no_ilpd || '-'}</td>
                </tr>
                <!-- Nama Penandatangan Atas -->
                <tr class="text-center bg-gray font-bold">
                    <td>${ilpd.ttd_1_nama || 'Pemohon'}</td>
                    <td>${ilpd.ttd_2_nama || 'Atasan Direct'}</td>
                    <td>${ilpd.ttd_3_nama || 'Head Dept'}</td>
                    <td>${ilpd.ttd_4_nama || 'HRD'}</td>
                    <td>${ilpd.ttd_5_nama || 'Finance'}</td>
                    <td>${ilpd.ttd_6_nama || 'GA'}</td>
                    <td>${ilpd.ttd_7_nama || 'Director'}</td>
                    <td>Tanggal</td>
                </tr>
                <!-- Area TTD Atas -->
                <tr class="ttd-box">
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td class="text-center">${ilpd.tanggal || '-'}</td>
                </tr>
            </table>

            <!-- 2. INFORMASI PERJALANAN (FORM 1) -->
            <div class="section-title">1. INFORMASI PERJALANAN</div>
            <table class="table-doc">
                <tr>
                    <td width="20%"><strong>Kota Tujuan</strong></td>
                    <td colspan="3">: ${sppd?.kota.name || ilpd.kota_tujuan || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Lama Perjalanan</strong></td>
                    <td colspan="3">: ${ilpd.sppd?.durasi || '-'} Hari</td>
                </tr>
                <tr>
                    <td><strong>Transportasi</strong></td>
                    <td colspan="3">: ${sppd.transport?.name || ilpd.transport_id || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Keperluan</strong></td>
                    <td colspan="3">: ${sppd.keperluan?.name || ilpd.keperluan_id || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Tugas</strong></td>
                    <td colspan="3">
                        <ol style="margin: 0; padding-left: 15px;">
                            ${(doc.sppd?.tugas || '-').split('\n').map(t => `<li>${t}</li>`).join('')}
                        </ol>
                    </td>
                </tr>
            </table>

            <!-- 3. PERKIRAAN & REALISASI BIAYA (FORM 2 - 2 KOLOM) -->
            <table class="table-doc">
                <tr class="bg-gray font-bold text-center">
                    <td width="50%">2. PERKIRAAN BIAYA</td>
                    <td width="50%">2. REALISASI BIAYA</td>
                </tr>
                <tr>
                    <!-- Kolom Perkiraan -->
                    <td>
                        <table style="width:100%;">
                            <tr><td>BBM</td><td class="text-right">Rp ${perkiraan.bbm || '0'}</td></tr>
                            <tr><td>Uang Harian (Dinas)</td><td class="text-right">Rp ${perkiraan.dinas || '0'}</td></tr>
                            <tr><td>Uang Makan</td><td class="text-right">Rp ${perkiraan.makan || '0'}</td></tr>
                            <tr><td>Hotel</td><td class="text-right">Rp ${perkiraan.hotel || '0'}</td></tr>
                            <tr><td>Transport Lokal</td><td class="text-right">Rp ${perkiraan.transport_lokal || '0'}</td></tr>
                            <tr><td>Visa</td><td class="text-right">Rp ${perkiraan.visa || '0'}</td></tr>
                            <tr><td>Fiskal</td><td class="text-right">Rp ${perkiraan.fiskal || '0'}</td></tr>
                            <tr><td>Tax Airport</td><td class="text-right">Rp ${perkiraan.airport_tax || '0'}</td></tr>
                            <tr><td>Parkir / Tol</td><td class="text-right">Rp ${perkiraan.parkirtoll || '0'}</td></tr>
                            <tr><td>Laundry / Lainnya</td><td class="text-right">Rp ${perkiraan.laundry_dll || '0'}</td></tr>
                        </table>
                    </td>
                    <!-- Kolom Realisasi -->
                    <td>
                        <table style="width:100%;">
                            <tr><td>BBM</td><td class="text-right">Rp ${realisasi.bbm || '0'}</td></tr>
                            <tr><td>Uang Harian (Dinas)</td><td class="text-right">Rp ${realisasi.uang_dinas || '0'}</td></tr>
                            <tr><td>Uang Makan</td><td class="text-right">Rp ${realisasi.uang_makan || '0'}</td></tr>
                            <tr><td>Hotel</td><td class="text-right">Rp ${realisasi.hotel || '0'}</td></tr>
                            <tr><td>Transport Lokal</td><td class="text-right">Rp ${realisasi.transport_lokal || '0'}</td></tr>
                            <tr><td>Visa / Fiskal</td><td class="text-right">Rp ${realisasi.visa_fiskal || '0'}</td></tr>
                            <tr><td>Tax Airport / Parkir / Tol</td><td class="text-right">Rp ${realisasi.tax_parkir_tol || '0'}</td></tr>
                            <tr><td>Laundry / Lainnya</td><td class="text-right">Rp ${realisasi.laundry_dll || '0'}</td></tr>
                        </table>
                    </td>
                </tr>
                <tr class="font-bold bg-gray">
                    <td>TOTAL PERKIRAAN: <span style="float:right;">Rp ${perkiraan.total || '0'}</span></td>
                    <td>TOTAL REALISASI: <span style="float:right;">Rp ${realisasi.total || '0'}</span></td>
                </tr>
            </table>

            <!-- 4. SUMMARY KEUANGAN & LOKASI -->
            <table class="table-doc">
                <tr>
                    <td width="50%" rowspan="3">
                        <strong>Yang Dikunjungi / Judul:</strong><br>
                        ${ilpd.yang_dikunjungi || '-'}
                    </td>
                    <td width="25%"><strong>Uang Muka</strong></td>
                    <td width="25%" class="text-right">Rp ${perkiraan.uang_muka || '0'}</td>
                </tr>
                <tr>
                    <td><strong>Selisih (Lebih / Kurang)</strong></td>
                    <td class="text-right">Rp ${ilpd.selisih || '0'}</td>
                </tr>
                <tr>
                    <td><strong>Keterangan Selisih</strong></td>
                    <td>${ilpd.keterangan_selisih || '-'}</td>
                </tr>
            </table>

            <!-- 5. LAPORAN HASIL -->
            <div class="section-title">LAPORAN HASIL PERJALANAN DINAS</div>
            <table class="table-doc">
                <tr>
                    <td style="height: 60px;">${ilpd.laporan_hasil || '-'}</td>
                </tr>
            </table>

            <!-- 6. TTD BAWAH (APPROVAL AKHIR) -->
            <table class="table-doc">
                <tr class="text-center bg-gray font-bold">
                    <td>${ilpd.ttd_bwh_1 || 'Dibuat Oleh'}</td>
                    <td>${ilpd.ttd_bwh_2 || 'Diperiksa'}</td>
                    <td>${ilpd.ttd_bwh_3 || 'Disetujui'}</td>
                    <td>${ilpd.ttd_bwh_4 || 'Finance'}</td>
                    <td>${ilpd.ttd_bwh_5 || 'Kasir'}</td>
                    <td>${ilpd.ttd_bwh_6 || 'Penerima'}</td>
                    <td>${ilpd.ttd_bwh_7 || 'Mengetahui'}</td>
                </tr>
                <tr class="ttd-box">
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
            </table>

            <script>
                window.onload = function() {
                    window.print();
                };
            <\/script>
        </body>
        </html>
    `;
}

/* =========================================================
    ESC KEY
========================================================== */

document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {

        closeDocumentModal();

        document
            .getElementById('profileMenu')
            .classList.add('hidden');

        document
            .getElementById('filterMenu')
            .classList.add('hidden');

    }

});

/* =========================================================
    INITIALIZE
========================================================== */

// renderRole();