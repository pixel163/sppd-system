const historyData = [
    {
        no: 'SPPD-2026-00124',
        tujuan: 'Bandung',
        tanggal: '20 - 22 Agustus 2026',
        status: 'Sedang Diproses',
        type: 'manager',
        date: '11 Agustus 2026',
        user: 'Eko Saputra',
        owner: 'staff',
        manager: 'Andi Wijaya'
    },
    {
        no: 'SPPD-2026-00123',
        tujuan: 'Yogyakarta',
        tanggal: '15 - 17 Agustus 2026',
        status: 'Sedang Diproses',
        type: 'ga',
        date: '8 Agustus 2026',
        user: 'Eko Saputra',
        owner: 'staff',
        manager: 'Budi Santoso'
    },
    {
        no: 'SPPD-2026-00122',
        tujuan: 'Surabaya',
        tanggal: '05 - 06 Agustus 2026',
        status: 'Menunggu Approval',
        type: 'gm',
        date: '5 Agustus 2026',
        user: 'Eko Saputra',
        owner: 'staff',
        manager: 'Andi Wijaya'
    },
    {
        no: 'SPPD-2026-00121',
        tujuan: 'Jakarta',
        tanggal: '28 Juli 2026',
        status: 'Selesai',
        type: 'done',
        date: '20 Juli 2026',
        user: 'Eko Saputra',
        owner: 'staff',
        manager: 'Andi Wijaya'
    },
    {
        no: 'SPPD-2026-00120',
        tujuan: 'Semarang',
        tanggal: '18 - 19 Juli 2026',
        status: 'Selesai',
        type: 'done',
        date: '11 Juli 2026',
        user: 'Eko Saputra',
        owner: 'staff',
        manager: 'Budi Santoso'
    },
    {
        no: 'SPPD-2026-00118',
        tujuan: 'Bandung',
        tanggal: '10 - 11 Juli 2026',
        status: 'Selesai',
        type: 'done',
        date: '1 Juli 2026',
        user: 'Eko Saputra',
        owner: 'staff',
        manager: 'Budi Santoso'
    },
    {
        no: 'SPPD-2026-00117',
        tujuan: 'Surabaya',
        tanggal: '1 - 3 Juli 2026',
        status: 'Selesai',
        type: 'done',
        date: '25 Juni 2026',
        user: 'Eko Saputra',
        owner: 'staff',
        manager: 'Budi Santoso'
    }
];

// Deklarasikan variabel global untuk menyimpan detail item yang sedang aktif
let currentDetail = null;

/* ============================================================
   STATUS BADGE
============================================================ */
function statusBadge(item) {
    let classes = '';

    if (item.type === 'manager') {
        classes = 'bg-[#fff7ed] text-[#d97706]';
    } else if (item.type === 'ga') {
        classes = 'bg-[#eff6ff] text-[#2563eb]';
    } else if (item.type === 'gm') {
        classes = 'bg-[#f3e8ff] text-[#9333ea]';
    } else if (item.type === 'done') {
        classes = 'bg-[#f0fdf4] text-[#16a34a]';
    } else {
        classes = 'bg-[#fef2f2] text-[#dc2626]';
    }

    return `
        <span class="inline-flex rounded-lg px-[10px] py-[6px] text-center text-[11px] font-semibold leading-[1.2] ${classes}">
            ${item.status}
        </span>
    `;
}

/* ============================================================
   RENDER HISTORY
============================================================ */
function renderHistory() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    const search = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const filter = statusFilter ? statusFilter.value : 'all';

    // FIX: Ambil data dari historyData
    let filteredData = [...historyData];

    if (search !== '') {
        filteredData = filteredData.filter(item =>
            item.no.toLowerCase().includes(search) ||
            item.tujuan.toLowerCase().includes(search) ||
            item.user.toLowerCase().includes(search)
        );
    }

    if (filter !== 'all') {
        filteredData = filteredData.filter(item => item.type === filter);
    }

    renderDesktop(filteredData);
    renderMobile(filteredData);
}

/* ============================================================
   DESKTOP
============================================================ */
function renderDesktop(data) {
    const container = document.getElementById('desktopHistoryList');
    if (!container) return;

    if (data.length === 0) {
        container.innerHTML = '';
        showEmpty();
        return;
    }

    hideEmpty();

    // Optimasi render dengan Array.map
    container.innerHTML = data.map(item => `
        <div class="grid grid-cols-[130px_110px_150px_160px_110px_110px] items-center gap-3 border-b border-[#f1f5f9] px-4 py-[14px] text-[12px] hover:bg-[#fafcff]">
            <span class="font-semibold">${item.no}</span>
            <span>${item.tujuan}</span>
            <span>${item.tanggal}</span>
            <span>${statusBadge(item)}</span>
            <span class="text-[#64748b]">${item.date}</span>
            <div class="flex items-center justify-center gap-1">
                <button
                    type="button"
                    onclick="openDetail('${item.no}')"
                    class="flex size-8 items-center justify-center rounded-lg hover:bg-[#eff6ff]"
                    title="Lihat"
                >
                    <span class="text-[#64748b]">👁</span>
                </button>
                <button
                    type="button"
                    onclick="printItem('${item.no}')"
                    class="flex size-8 items-center justify-center rounded-lg hover:bg-[#eff6ff]"
                    title="Cetak"
                >
                    <span class="text-[#0d6efd]">🖨</span>
                </button>
            </div>
        </div>
    `).join('');
}

/* ============================================================
   MOBILE
============================================================ */
function renderMobile(data) {
    const container = document.getElementById('mobileHistoryList');
    if (!container) return;

    if (data.length === 0) {
        container.innerHTML = '';
        return;
    }

    // Optimasi render dengan Array.map
    container.innerHTML = data.map(item => `
        <div class="rounded-xl border border-[#f1f5f9] p-4 hover:bg-[#fafcff]">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="truncate text-[13px] font-bold">${item.no}</p>
                    <p class="mt-1 text-[12px] text-[#64748b]">${item.tujuan}</p>
                </div>
                ${statusBadge(item)}
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <div>
                    <p class="text-[10px] text-[#94a3b8]">Tanggal Perjalanan</p>
                    <p class="mt-1 text-[11px] font-semibold">${item.tanggal}</p>
                </div>
                <div>
                    <p class="text-[10px] text-[#94a3b8]">Diajukan Pada</p>
                    <p class="mt-1 text-[11px] font-semibold">${item.date}</p>
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2 border-t border-[#f1f5f9] pt-3">
                <button
                    type="button"
                    onclick="openDetail('${item.no}')"
                    class="rounded-lg border border-[#e2e8f0] px-3 py-2 text-[11px] font-semibold text-[#64748b]"
                >
                    Lihat
                </button>
                <button
                    type="button"
                    onclick="printItem('${item.no}')"
                    class="rounded-lg border border-[#0d6efd] px-3 py-2 text-[11px] font-semibold text-[#0d6efd]"
                >
                    Cetak
                </button>
            </div>
        </div>
    `).join('');
}

/* ============================================================
   EMPTY STATE & PAGINATION
============================================================ */
function showEmpty() {
    document.getElementById('emptyState')?.classList.remove('hidden');
    document.getElementById('pagination')?.classList.add('hidden');
}

function hideEmpty() {
    document.getElementById('emptyState')?.classList.add('hidden');
    document.getElementById('pagination')?.classList.remove('hidden');
}

/* ============================================================
   DETAIL
============================================================ */
function openDetail(no) {
    const item = historyData.find(data => data.no === no);
    if (!item) return;

    currentDetail = item;

    document.getElementById('modalNo').textContent = item.no;
    document.getElementById('modalUser').textContent = item.user;
    document.getElementById('modalTujuan').textContent = item.tujuan;
    document.getElementById('modalTanggal').textContent = item.tanggal;
    document.getElementById('modalDate').textContent = item.date;
    document.getElementById('modalStatus').innerHTML = statusBadge(item);

    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    document.body.classList.add('overflow-hidden');
}

// Tambahkan baris ini tepat di luar/setelah deklarasi fungsi openDetail
window.openDetail = openDetail;

function closeDetail() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.body.classList.remove('overflow-hidden');
}

window.closeDetail = closeDetail;

/* ============================================================
   PRINT
============================================================ */
function printItem(no) {
    const item = historyData.find(data => data.no === no);
    if (!item) return;
    
    const printWindow = window.open('', '_blank', 'width=900,height=700');
    if (!printWindow) return;
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
        <title>${item.no}</title>
        <style>
        body { font-family: Arial, sans-serif; padding: 40px; color: #1e293b; }
        h1 { font-size: 22px; margin-bottom: 25px; }
        .row { margin-bottom: 15px; }
                .label { color: #64748b; font-size: 12px; }
                .value { font-weight: bold; margin-top: 5px; }
                </style>
        </head>
        <body>
        <h1>Riwayat Pengajuan SPPD</h1>
        <div class="row"><div class="label">No. SPPD</div><div class="value">${item.no}</div></div>
        <div class="row"><div class="label">Diajukan Oleh</div><div class="value">${item.user}</div></div>
        <div class="row"><div class="label">Tujuan</div><div class="value">${item.tujuan}</div></div>
        <div class="row"><div class="label">Tanggal Perjalanan</div><div class="value">${item.tanggal}</div></div>
        <div class="row"><div class="label">Status</div><div class="value">${item.status}</div></div>
        <div class="row"><div class="label">Diajukan Pada</div><div class="value">${item.date}</div></div>
        <hr>
        <p>Dokumen ini merupakan simulasi Frontend SPPD System.</p>
        </body>
        </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    }

    window.printItem = printItem;
    
    function printCurrentDetail() {
        if (!currentDetail) return;
        printItem(currentDetail.no);
    }

    window.printCurrentDetail = printCurrentDetail;

/* ============================================================
   PROFILE & SIDEBAR
============================================================ */
function closeProfileMenu() {
    document.getElementById('profileMenu')?.classList.add('hidden');
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar && overlay) {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
}

/* ============================================================
   PAGINATION SIMULATION
============================================================ */
function previousPage() {
    alert('Halaman sebelumnya - simulasi Frontend.');
}

function nextPage() {
    alert('Halaman berikutnya - simulasi Frontend.');
}

/* ============================================================
   INIT
============================================================ */
document.addEventListener('DOMContentLoaded', function() {
    renderHistory();
});