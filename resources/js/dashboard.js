// =========================
// FUNCTION SHOW DETAIL
// =========================
function showDetail(item) {

    // 1. HEADER (Menggunakan no_sppd atau no sesuai kolom DB)
    document.getElementById('modalTitle').innerText = item.dinas?.no_dinas || item.no_sppd || item.no || '-';

    // Mengambil data dari relasi/objek ilpd jika ada
    const ilpdData = item.ilpd || {}; 
    const tglAwal = ilpdData.tanggal_awal || ilpdData.start_date || item.tanggal_awal;
    const tglAkhir = ilpdData.tanggal_akhir || ilpdData.end_date || item.tanggal_akhir;

    const formattedDate = formatRangeTanggal(tglAwal, tglAkhir);
    // 2. INFORMASI PENGAJUAN
    // Sesuaikan nama properti (kiri) jika di database kamu sedikit berbeda (misal: item.nama_pemohon)
    document.getElementById('modalInfo').innerHTML = `
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <p class="text-[11px] text-[#64748b]">Pemohon</p>
                <p class="mt-1 text-sm font-semibold">${item.applicant || item.pemohon || item.user?.name || '-'}</p>
            </div>

            <div>
                <p class="text-[11px] text-[#64748b]">Tujuan</p>
                <p class="mt-1 text-sm font-semibold">${item.destination || item.kota?.name || '-'}</p>
            </div>

            <div>
                <p class="text-[11px] text-[#64748b]">Tanggal</p>
                <p class="mt-1 text-sm font-semibold">${formattedDate}</p>
            </div>

            <div>
                <p class="text-[11px] text-[#64748b]">Status</p>
                <div class="mt-1">
                    ${typeof statusBadge === 'function' ? statusBadge(item) : `<span class="font-semibold">${item.status}</span>`}
                </div>
            </div>
        </div>
    `;

    // 3. DOKUMEN LAMPIRAN
    const container = document.getElementById('modalContent');
    container.innerHTML = '';

    // 1. GENERATE DOKUMEN LIST SECARA DINAMIS
    let dokumenList = [];

    // A. Jika ada no_sppd atau id SPPD, masukkan 'form_sppd'
    if (item.no_sppd || item.id) {
        dokumenList.push('form_sppd');
    }

    // B. Jika ada relasi ilpd (seperti di console log kamu), masukkan 'form_ilpd'
    if (item.ilpd) {
        dokumenList.push('form_ilpd');
    }

    // C. Jika ada data tiket (opsional, sesuaikan nama properti jika ada)
    if (item.tiket || item.tiket_id) {
        dokumenList.push('tiket');
    }

    // 2. DEBUGGING
    console.log("Dokumen yang berhasil dideteksi:", dokumenList);

    // 3. CEK JIKA TIDAK ADA DOKUMEN SAMA SEKALI
    if (dokumenList.length === 0) {
        container.innerHTML = `
            <div class="rounded-xl border border-dashed border-[#e2e8f0] p-6 text-center">
                <p class="text-sm font-medium text-[#64748b]">Belum ada dokumen</p>
                <p class="mt-1 text-[12px] text-[#94a3b8]">Dokumen pengajuan belum tersedia.</p>
            </div>
        `;
    } else {
        // 4. RENDER DOKUMEN
        dokumenList.forEach(type => {
            const doc = getDocumentData(type);
            let documentButtons = '';

            // Tentukan Key Unik (ID/No) & Tanggal berdasarkan jenis dokumen
            let itemId = item.id;
            let rawDate = item.created_at;
            let editUrl = `/sppd/${itemId}/edit`;
            // let itemId = `/sppd/${item.id}/edit`;

            if (type === 'form_ilpd' && item.ilpd) {
                // itemId = item.ilpd.no_ilpd || item.ilpd.id || item.id;
                
                rawDate = item.ilpd.created_at || item.created_at;
                // Ganti Judul dengan Nomor ILPD (misal: ILPD/2026/09/0001)
                doc.title = item.ilpd.no_ilpd || "Form ILPD";

                // Route khusus ILPD
                editUrl = `/ilpd/${itemId}/edit`;

            } else if (type === 'form_sppd') {
                // PERBAIKAN: Gunakan ID database untuk URL
                itemId = item.id; 
                rawDate = item.created_at;
                
                // Judul untuk Tampilan UI
                doc.title = item.no_sppd || item.no || "Form SPPD";
                
                // Route khusus SPPD
                editUrl = `/sppd/${itemId}/edit`;
            }

            // Format tanggal menjadi "1 September 2026"
            const docDate = typeof formatTanggalIndo === 'function' ? formatTanggalIndo(rawDate) : (rawDate || '-');

            // Kondisi Tombol Action
            const status = item.status;

            if (status === 'Menunggu Approval' || status === 'Pending') {
                documentButtons = `
                    <a href="${editUrl}" class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b] hover:bg-slate-50">
                        <span>✎</span> Edit
                    </a>
                    <button type="button" class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b] hover:bg-slate-50" onclick="openDocumentModal('${type}', '${itemId}')">
                        <span>👁</span> Lihat
                    </button>
                `;
            } else if (status === 'Sedang Diproses' || status === 'Processing') {
                documentButtons = `
                    <button type="button" class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b] hover:bg-slate-50" onclick="openDocumentModal('${type}', '${itemId}')">
                        <span>👁</span> Lihat
                    </button>
                `;
            } else if (['Disetujui', 'Approved', 'Approval'].includes(status)) {
                documentButtons = `
                    <button type="button" class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b] hover:bg-slate-50" onclick="openDocumentModal('${type}', '${itemId}')">
                        <span>👁</span> Lihat
                    </button>
                    <button type="button" class="flex items-center gap-1.5 rounded-lg border ${doc.border} px-4 py-2 text-[13px] font-medium ${doc.iconColor} hover:bg-slate-50" onclick="printSpecificDocument('${type}', '${itemId}')">
                        <span>🖨</span> Cetak
                    </button>
                `;
            }

            // Render HTML
            const wrapper = document.createElement('div');
            wrapper.className = 'flex flex-col gap-4 rounded-xl border border-[#f1f5f9] p-4 sm:flex-row sm:items-center';

            wrapper.innerHTML = `
                <div class="flex size-11 shrink-0 items-center justify-center rounded-lg ${doc.iconBg}">
                    <span class="${doc.iconColor} text-lg">▣</span>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-[15px] font-semibold">${doc.title}</h3>
                    <p class="mt-1 text-[12px] text-[#64748b]">${doc.description}</p>
                    <p class="mt-1 text-[11px] text-[#94a3b8]">${docDate}</p>
                </div>
                <div class="flex shrink-0 gap-2">
                    ${documentButtons}
                </div>
            `;

            container.appendChild(wrapper);
        });
    }

    // 4. FOOTER ACTIONS (Disederhanakan untuk tombol Tutup)
    // document.getElementById('modalActions').innerHTML = `
    //     <button type="button" onclick="closeModal()" class="rounded-lg border border-[#e2e8f0] px-4 py-2 text-xs font-semibold text-[#64748b] hover:bg-slate-50">
    //         Tutup
    //     </button>
    // `;

    // 5. BUKA MODAL
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
    }

window.showDetail = showDetail;

function formatRangeTanggal(startDateStr, endDateStr) {
    if (!startDateStr) return '-';

    const start = new Date(startDateStr);
    const end = endDateStr ? new Date(endDateStr) : start;

    // Daftar nama bulan dalam bahasa Indonesia
    const bulanIndo = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];

    const dStart = start.getDate();
    const dEnd = end.getDate();
    const mStart = bulanIndo[start.getMonth()];
    const mEnd = bulanIndo[end.getMonth()];
    const yStart = start.getFullYear();
    const yEnd = end.getFullYear();

    // Jika dalam bulan dan tahun yang sama (Contoh: 2 - 4 Sep 2026)
    if (mStart === mEnd && yStart === yEnd) {
        if (dStart === dEnd) {
            return `${dStart} ${mStart} ${yStart}`;
        }
        return `${dStart} - ${dEnd} ${mStart} ${yStart}`;
    }

    // Jika beda bulan tapi tahun sama (Contoh: 28 Agt - 2 Sep 2026)
    if (yStart === yEnd) {
        return `${dStart} ${mStart} - ${dEnd} ${mEnd} ${yStart}`;
    }

    // Jika beda tahun (Contoh: 30 Des 2026 - 2 Jan 2027)
    return `${dStart} ${mStart} ${yStart} - ${dEnd} ${mEnd} ${yEnd}`;
}

function formatTanggalIndo(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    const bulanIndo = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    
    const day = date.getDate();
    const month = bulanIndo[date.getMonth()];
    const year = date.getFullYear();
    
    return `${day} ${month} ${year}`;
}

// =========================
// HELPER DOCUMENTS CONFIG
// =========================
function getDocumentData(type) {
    const documents = {
        form_sppd: {
            title: "Form SPPD",
            description: "Dokumen surat tugas perjalanan dinas.",
            date: "-",
            iconBg: "bg-blue-50",
            iconColor: "text-blue-600",
            border: "border-blue-100"
        },
        form_ilpd: {
            title: "Form ILPD",
            description: "Form ILPD perjalanan dinas.",
            date: "-",
            iconBg: "bg-emerald-50",
            iconColor: "text-emerald-600",
            border: "border-emerald-100"
        },
        tiket: {
            title: "Tiket",
            description: "Laporan hasil perjalanan dinas.",
            date: "-",
            iconBg: "bg-purple-50",
            iconColor: "text-purple-600",
            border: "border-purple-100"
        }
    };

    return documents[type] || {
        title: "Dokumen",
        description: "Dokumen pengajuan.",
        date: "-",
        iconBg: "bg-gray-50",
        iconColor: "text-gray-600",
        border: "border-gray-100"
    };
}

// =========================
// CLOSE MODAL FUNCTION
// =========================
function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}

window.closeModal = closeModal;