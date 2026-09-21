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
function openDetail(dinas) {
    if (!dinas) return;

    // Ambil child Form 1 (SPPD) dari objek dinas
    const sppd = dinas.sppd || {};
    const ilpd = dinas.ilpd || {};

    document.getElementById('modalNo').textContent = dinas.no_dinas || '-';
    document.getElementById('modalUser').textContent = dinas.sppd?.user?.name || '-';
    
    document.getElementById('modalTanggal').textContent = formatRangeTanggal(
        dinas.ilpd?.tanggal_awal, 
        dinas.ilpd?.tanggal_akhir
    );
    document.getElementById('modalTujuan').textContent = dinas.sppd?.kota?.name || '-';
    document.getElementById('modalDate').textContent = formatTanggalLengkap(dinas.created_at);

    // Status bisa ambil dari Dinas (global) atau ILPD
    document.getElementById('modalStatus').innerHTML = statusBadge(dinas);

    // Render Alur Pengajuan secara Dinamis
    renderAlurPengajuan(dinas);

    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

// Tambahkan baris ini tepat di luar/setelah deklarasi fungsi openDetail
window.openDetail = openDetail;

// Fungsi Khusus Render Alur Pengajuan
function renderAlurPengajuan(dinas) {
    // Ambil status langsung dari tabel dinas
    const status = dinas ? dinas.status : 'Draft';

    let currentStep = 1;

    // Tentukan currentStep berdasarkan status dinas
    switch (status) {
        case 'Draft':
            currentStep = 2; // Step 1 selesai (ceklis), Step 2 aktif
            break;
        case 'Menunggu Approval':
            currentStep = 3; // Step 2 selesai (ceklis), Step 3 aktif
            break;
        case 'Sedang Diproses':
            currentStep = 4; // Step 3 selesai (ceklis), Step 4 aktif
            break;
        case 'Disetujui':
            currentStep = 6; // Step 4 & 5 selesai (ceklis semua)
            break;
        default:
            currentStep = 1; // Default jika status tidak cocok
    }

    // Master list step
    const steps = [
        { number: 1, role: 'Pembuatan SPPD', desc: 'Pembuatan draft pengajuan' },
        { number: 2, role: 'Pengajuan ILPD', desc: 'Pengisian form rincian biaya' },
        { number: 3, role: 'Approval Manager', desc: 'Pemeriksaan pengajuan' },
        { number: 4, role: 'Approval General Affair', desc: 'Pemeriksaan budget dan tiket' },
        { number: 5, role: 'Disetujui', desc: 'Persetujuan akhir' }
    ];

    // Generate HTML
    let html = '';

    steps.forEach(step => {
        // Step dianggap lulus/selesai jika nomor step < currentStep (akan dapat centang)
        const isPassed = step.number < currentStep;
        // Step dianggap aktif berjalan jika nomor step == currentStep
        const isActive = step.number === currentStep;

        // Styling Warna
        let circleStyle = 'bg-gray-100 text-gray-400';
        let titleStyle = 'text-gray-400';
        let descStyle = 'text-gray-300';

        if (isPassed) {
            // Sudah selesai (Centang)
            circleStyle = 'bg-[#eff6ff] text-[#0d6efd]';
            titleStyle = 'text-gray-900';
            descStyle = 'text-[#94a3b8]';
        } else if (isActive) {
            // Sedang aktif (Angka disorot)
            circleStyle = 'bg-[#0d6efd] text-white';
            titleStyle = 'text-gray-900 font-bold';
            descStyle = 'text-gray-500';
        }

        // Icon Centang jika step sudah terlampaui (isPassed)
        const badgeContent = isPassed
            ? `<svg class="w-4 h-4 text-[#0d6efd]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
               </svg>`
            : step.number;

        html += `
            <div class="flex items-center gap-3">
                <div class="flex size-8 items-center justify-center rounded-full text-xs font-bold ${circleStyle}">
                    ${badgeContent}
                </div>
                <div>
                    <p class="text-[12px] font-semibold ${titleStyle}">
                        ${step.role}
                    </p>
                    <p class="text-[11px] ${descStyle}">
                        ${step.desc}
                    </p>
                </div>
            </div>
        `;
    });

    const container = document.getElementById('modalAlurPengajuan');
    if (container) {
        container.innerHTML = html;
    }
}
// function renderAlurPengajuan(dinas) {
//     const sppd = dinas.sppd || {};
//     const ilpd = dinas.ilpd || {};

//     // Ambil approval paling baru dari sppd dan ilpd
//     const sppdApprovals = sppd.approvals || [];
//     const ilpdApprovals = ilpd.approvals || [];

//     const latestSppdApproval = sppdApprovals.length > 0 ? sppdApprovals[sppdApprovals.length - 1] : null;
//     const latestIlpdApproval = ilpdApprovals.length > 0 ? ilpdApprovals[ilpdApprovals.length - 1] : null;

//     // Hitung currentStep (1 - 5)
//     let currentStep = 1; // Default Step 1: Draft SPPD

//     if (dinas.ilpd) {
//         currentStep = 2; // ILPD sudah terbuat
//     }
//     if (latestSppdApproval && latestSppdApproval.status === 'Menunggu Approval') {
//         currentStep = 3; // Menunggu Manager
//     }
//     if (latestIlpdApproval && latestIlpdApproval.status === 'Sedang Diproses') {
//         currentStep = 4; // Menunggu GA / Finance
//     }
//     if (latestIlpdApproval && latestIlpdApproval.status === 'Disetujui') {
//         currentStep = 5; // Selesai / Disetujui Semua
//     }

//     // Master list step
//     const steps = [
//         { number: 1, role: 'Pembuatan SPPD', desc: 'Pembuatan draft pengajuan' },
//         { number: 2, role: 'Pengajuan ILPD', desc: 'Pengisian form rincian biaya' },
//         { number: 3, role: 'Approval Manager', desc: 'Pemeriksaan pengajuan' },
//         { number: 4, role: 'Approval General Affair', desc: 'Pemeriksaan budget dan tiket' },
//         { number: 5, role: 'Disetujui', desc: 'Persetujuan akhir' }
//     ];

//     // Generate HTML
//     let html = '';

//     steps.forEach(step => {
//         const isPassed = step.number <= currentStep;
//         const isCompletedStep = isPassed && step.number < currentStep;

//         // Styling Warna
//         const circleStyle = isPassed 
//             ? 'bg-[#eff6ff] text-[#0d6efd]' 
//             : 'bg-gray-100 text-gray-400';
            
//         const titleStyle = isPassed ? 'text-gray-900' : 'text-gray-400';
//         const descStyle = isPassed ? 'text-[#94a3b8]' : 'text-gray-300';

//         // Icon Centang jika step sudah terlampaui
//         const badgeContent = isCompletedStep
//             ? `<svg class="w-4 h-4 text-[#0d6efd]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
//                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
//                </svg>`
//             : step.number;

//         html += `
//             <div class="flex items-center gap-3">
//                 <div class="flex size-8 items-center justify-center rounded-full text-xs font-bold ${circleStyle}">
//                     ${badgeContent}
//                 </div>
//                 <div>
//                     <p class="text-[12px] font-semibold ${titleStyle}">
//                         ${step.role}
//                     </p>
//                     <p class="text-[11px] ${descStyle}">
//                         ${step.desc}
//                     </p>
//                 </div>
//             </div>
//         `;
//     });

//     const container = document.getElementById('modalAlurPengajuan');
//     if (container) {
//         container.innerHTML = html;
//     }
// }

window.renderAlurPengajuan = renderAlurPengajuan;

function closeDetail() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.body.classList.remove('overflow-hidden');
}

window.closeDetail = closeDetail;

// Format 1: Rentang Tanggal (cth: "14 - 18 September 2026")
function formatRangeTanggal(tglAwal, tglAkhir) {
    if (!tglAwal || !tglAkhir) return '-';

    const dAwal = new Date(tglAwal);
    const dAkhir = new Date(tglAkhir);

    const dayAwal = dAwal.getDate(); // Cuma ambil angka hari (cth: 14)
    
    // Format tanggal akhir lengkap (cth: "18 September 2026")
    const optionAkhir = { day: 'numeric', month: 'long', year: 'numeric' };
    const stringAkhir = dAkhir.toLocaleDateString('id-ID', optionAkhir);

    return `${dayAwal} - ${stringAkhir}`;
}

// Format 2: Tanggal Lengkap dengan Hari (cth: "Senin, 14 September 2026")
function formatTanggalLengkap(dateString) {
    if (!dateString) return '-';

    const date = new Date(dateString);
    const options = { 
        weekday: 'long', 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
    };

    return date.toLocaleDateString('id-ID', options);
}

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