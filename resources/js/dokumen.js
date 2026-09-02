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

function renderRole() {

    const config = roleConfig[currentRole];

    document.getElementById('profileName').textContent =
        config.name;

    document.getElementById('profileRole').textContent =
        config.label;

    document.getElementById('pageDescription').textContent =
        config.description;

    document.getElementById('roleBadge').textContent =
        config.label.toUpperCase();

    document.getElementById('roleNote').textContent =
        config.note;

    statusFilter = 'all';

    currentData = dataByRole[currentRole];

    selectedId =
        currentData.length > 0
            ? currentData[0].no
            : null;

    document.getElementById('searchInput').value = '';

    renderList();

    renderDetail();

}

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

document
    .getElementById('searchInput')
    .addEventListener('input', function() {

        renderList();

    });

/* =========================================================
    FILTER BUTTON
========================================================== */

document
    .getElementById('filterButton')
    .addEventListener('click', function(event) {

        event.stopPropagation();

        document
            .getElementById('filterMenu')
            .classList.toggle('hidden');

    });

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

renderRole();