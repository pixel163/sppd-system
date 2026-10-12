function showDetail(item) {

    document.getElementById('modalTitle').innerText = item.dinas?.no_dinas || item.no_sppd || item.no || '-';

    const ilpdData = item.ilpd || {}; 
    const tglAwal = ilpdData.tanggal_awal || ilpdData.start_date || item.tanggal_awal;
    const tglAkhir = ilpdData.tanggal_akhir || ilpdData.end_date || item.tanggal_akhir;
    const formattedDate = formatRangeTanggal(tglAwal, tglAkhir);
    const statusMap = {
        0: 'Draft',
        1: 'Menunggu Approval',
        2: 'Sedang Diproses',
        3: 'Disetujui',
        4: 'Ditolak',
        5: 'Menunggu Laporan',
        6: 'Selesai'
    };

    const rawStatus = item.dinas?.status ?? item.status;
    const textStatus = statusMap[rawStatus] ?? rawStatus ?? '-';

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
                    ${typeof statusBadge === 'function' 
                        ? statusBadge(textStatus) 
                        : `<span class="font-semibold">${textStatus}</span>`}
                </div>
            </div>
        </div>
    `;

    const container = document.getElementById('modalContent');
    container.innerHTML = '';

    let dokumenList = [];

    if (item.no_sppd || item.id) {
        dokumenList.push('form_sppd');
    }

    if (item.ilpd) {
        dokumenList.push('form_ilpd');
    }

    // console.log("Dokumen yang berhasil dideteksi:", item.ilpd.id);
    
    const listTiket = item.ilpd ? (item.ilpd.tikets || item.ilpd.tiket) : null;

    const hasTiket = listTiket && (Array.isArray(listTiket) ? listTiket.length > 0 : Object.keys(listTiket).length > 0);

    if (hasTiket) {
        dokumenList.push('tiket');
    }

    console.log("Dokumen yang berhasil dideteksi:", dokumenList);

    if (dokumenList.length === 0) {
        container.innerHTML = `
            <div class="rounded-xl border border-dashed border-[#e2e8f0] p-6 text-center">
                <p class="text-sm font-medium text-[#64748b]">Belum ada dokumen</p>
                <p class="mt-1 text-[12px] text-[#94a3b8]">Dokumen pengajuan belum tersedia.</p>
            </div>
        `;
    } else {
        dokumenList.forEach(type => {
            const doc = getDocumentData(type);
            let itemId = null;
            let rawDate = item.created_at;

            if (type === 'form_sppd') {
                itemId = item.id;
                rawDate = item.created_at;
                doc.title = item.no_sppd || item.no || "Form SPPD";

            } else if (type === 'form_ilpd' && item.ilpd) {
                itemId = item.ilpd.id;
                rawDate = item.ilpd.created_at || item.created_at;
                doc.title = item.ilpd.no_ilpd || "Form ILPD";
                // editUrl = `/ilpd/${itemId}/edit`;
                
            } else if (type === 'tiket') {
                const tiketData = Array.isArray(listTiket) ? listTiket[0] : listTiket;
                rawDate = tiketData?.uploaded_at || tiketData?.created_at || item.created_at;
                doc.title = tiketData?.type ? `Tiket ${tiketData.type.toUpperCase()}` : "Tiket Perjalanan";
            }

            const docDate = typeof formatTanggalIndo === 'function' ? formatTanggalIndo(rawDate) : (rawDate || '-');

            // 1. Dapatkan angka status (Mendukung angka langsung, string angka, atau objek status Enum)
            const rawStatusValue = item.dinas?.status?.value ?? item.dinas?.status ?? item.status;
            const statusNum = Number(rawStatusValue);

            // 2. Pemohon & Role Identifikasi
            const docUserId = item.user_id || item.sppd?.user_id || item.dinas?.sppd?.user_id;
            const isMyDocument = (Number(docUserId) === Number(window.currentUserId));
            const isHRGA = window.currentUserRole === 'HRGA';

            const docUser = item.user || item.sppd?.user;
            const isSubmitterManager = docUser?.jabatan?.name?.toLowerCase().includes('manager') 
                || docUser?.jabatan_id === 2;

            const safeItemJson = encodeURIComponent(JSON.stringify(item));

            // Tombol Default (Selalu muncul)
            let documentButtons = `
                <button type="button" 
                        data-item="${safeItemJson}"
                        onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
                        class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
                    <span>👁</span> Lihat
                </button>
            `;

            // Map Enum DinasStatus:
            // 0: DRAFT, 1: MENUNGGU_APPROVAL, 2: SEDANG_DIPROSES, 3: DISETUJUI, 4: DITOLAK, 5: LAPORAN, 6: SELESAI

            if (isHRGA && !isMyDocument) {

                // Status 1: Menunggu Approval
                if (statusNum === 1) {
                    if (['form_sppd', 'sppd'].includes(type) && isSubmitterManager) {
                        documentButtons += `
                            <a href="/sppd/${itemId}/approves" class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-medium text-white hover:bg-blue-700">
                                Proses
                            </a>
                        `;
                    }
                }

                // Status 2: Sedang Diproses
                else if (statusNum === 2) {
                    if (['form_sppd', 'sppd'].includes(type)) {
                        documentButtons += `
                            <button type="button" 
                                    data-item="${safeItemJson}"
                                    onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
                                    class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
                                <span>🖨</span> Cetak
                            </button>
                        `;
                    } else if (['form_ilpd', 'ilpd'].includes(type)) {
                        documentButtons += `
                            <a href="/ilpd/${itemId}" class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-medium text-white hover:bg-blue-700">
                                Proses
                            </a>
                        `;
                    }
                }

                // Status 3: Disetujui / 5: Laporan / 6: Selesai
                else if ([3, 5, 6].includes(statusNum)) {

                    // ============================================================
                    // LOGIKA PENGECEKAN TANGGAL BERANGKAT KHUSUS STATUS DISETUJUI (3)
                    // ============================================================
                    if (statusNum === 3) {
                        const today = new Date().toISOString().split('T')[0];
                        const tglBerangkat = item.ilpd?.tanggal_awal || item.tanggal_mulai || null;

                        // Hanya untuk ILPD (form 2) & jika belum berangkat
                        if (['form_ilpd', 'ilpd'].includes(type) && tglBerangkat && tglBerangkat > today) {
                            documentButtons += `
                                <a href="/ilpd/${itemId}/edit" class="flex items-center gap-1.5 rounded-lg bg-amber-500 px-4 py-2 text-[13px] font-medium text-white hover:bg-amber-600">
                                    <span>✏️</span> Edit
                                </a>
                            `;
                        }
                    }

                    // Tombol Cetak Tetap Ada
                    documentButtons += `
                        <button type="button" 
                                data-item="${safeItemJson}"
                                onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
                                class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
                            <span>🖨</span> Cetak
                        </button>
                    `;
                }
            }
            // let documentButtons = `
            //     <button type="button" 
            //             data-item="${safeItemJson}"
            //             onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
            //             class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
            //         <span>👁</span> Lihat
            //     </button>
            // `;

            // // Map Enum DinasStatus:
            // // 0: DRAFT, 1: MENUNGGU_APPROVAL, 2: SEDANG_DIPROSES, 3: DISETUJUI, 4: DITOLAK, 5: LAPORAN, 6: SELESAI

            // if (isHRGA && !isMyDocument) {

            //     // Status 1: Menunggu Approval
            //     if (statusNum === 1) {
            //         // SPPD milik Manager yang butuh tindakan approval GA
            //         if (['form_sppd', 'sppd'].includes(type) && isSubmitterManager) {
            //             documentButtons += `
            //                 <a href="/sppd/${itemId}/approves" class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-medium text-white hover:bg-blue-700">
            //                     Proses
            //                 </a>
            //             `;
            //         }
            //     }

            //     // Status 2: Sedang Diproses
            //     else if (statusNum === 2) {
            //         if (['form_sppd', 'sppd'].includes(type)) {
            //             documentButtons += `
            //                 <button type="button" 
            //                         data-item="${safeItemJson}"
            //                         onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
            //                         class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
            //                     <span>🖨</span> Cetak
            //                 </button>
            //             `;
            //         } else if (['form_ilpd', 'ilpd'].includes(type)) {
            //             documentButtons += `
            //                 <a href="/ilpd/${itemId}" class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-medium text-white hover:bg-blue-700">
            //                     Proses
            //                 </a>
            //             `;
            //         }
            //     }

            //     // Status 3: Disetujui / 5: Laporan / 6: Selesai
            //     else if ([3, 5, 6].includes(statusNum)) {
            //         documentButtons += `
            //             <button type="button" 
            //                     data-item="${safeItemJson}"
            //                     onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
            //                     class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
            //                 <span>🖨</span> Cetak
            //             </button>
            //         `;
            //     }
            // } 
            else {
                // Hak Akses untuk Pemohon / Non-HRGA
                
                // Status 2: Sedang Diproses (Pemohon cetak SPPD)
                if (statusNum === 2) {
                    if (['form_sppd', 'sppd'].includes(type)) {
                        documentButtons += `
                            <button type="button" 
                                    data-item="${safeItemJson}"
                                    onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
                                    class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
                                <span>🖨</span> Cetak
                            </button>
                        `;
                    }
                }

                // Status 3, 5, 6: Disetujui / Laporan / Selesai (Pemohon cetak semua form)
                else if ([3, 5, 6].includes(statusNum)) {
                    documentButtons += `
                        <button type="button" 
                                data-item="${safeItemJson}"
                                onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
                                class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
                            <span>🖨</span> Cetak
                        </button>
                    `;
                }
            }
        // });
        // dokumenList.forEach(type => {
        //     const doc = getDocumentData(type);
        //     let itemId = null;
        //     let rawDate = item.created_at;

        //     if (type === 'form_sppd') {
        //         itemId = item.id;
        //         rawDate = item.created_at;
        //         doc.title = item.no_sppd || item.no || "Form SPPD";

        //     } else if (type === 'form_ilpd' && item.ilpd) {
        //         itemId = item.ilpd.id;
        //         rawDate = item.ilpd.created_at || item.created_at;
        //         doc.title = item.ilpd.no_ilpd || "Form ILPD";
                
        //     } else if (type === 'tiket') {
        //         const tiketData = Array.isArray(listTiket) ? listTiket[0] : listTiket;

        //         rawDate = tiketData.uploaded_at || tiketData.created_at || item.created_at;

        //         doc.title = tiketData.type ? `Tiket ${tiketData.type.toUpperCase()}` : "Tiket Perjalanan";
        //     }

        //     const docDate = typeof formatTanggalIndo === 'function' ? formatTanggalIndo(rawDate) : (rawDate || '-');

        //     const mainStatus = item.dinas?.status
        //     const docUserId = item.user_id || item.sppd?.user_id || item.dinas?.sppd?.user_id;

        //     const isMyDocument = (Number(docUserId) === Number(window.currentUserId));
        //     const isHRGA = window.currentUserRole === 'HRGA';

        //     const docUser = item.user || item.sppd?.user;
        //     const isSubmitterManager = docUser?.jabatan?.name?.toLowerCase().includes('Manager') 
        //         || docUser?.jabatan_id === 2; // Sesuaikan ID/Nama jabatan Manager di DB Anda

        //     const safeItemJson = encodeURIComponent(JSON.stringify(item));

        //     let documentButtons = `
        //         <button type="button" class="flex items-center gap-1.5 rounded-lg border ${doc.border} px-4 py-2 text-[13px] font-medium ${doc.iconColor} hover:bg-slate-50" onclick="printItem('${type}', ${JSON.stringify(item).replace(/"/g, '&quot;')})">
        //             <span>👁</span> Lihat
        //         </button>
        //     `;

        //     if (isHRGA && !isMyDocument) {

        //         if (['Menunggu Approval', 'menunggu_approval', 'Pending'].includes(mainStatus)) {
        //             if ((type === 'form_sppd' || type === 'sppd') && isSubmitterManager) {
        //                 documentButtons += `
        //                     <a href="/sppd/${itemId}/approves" class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-medium text-white hover:bg-blue-700">
        //                         Proses
        //                     </a>
        //                 `;
        //             }
        //         }

        //         else if (['Sedang Diproses', 'sedang_diproses'].includes(mainStatus)) {
        //             if (type === 'form_sppd' || type === 'sppd') {
        //                 documentButtons += `
        //                     <button type="button" class="flex items-center gap-1.5 rounded-lg border ${doc.border} px-4 py-2 text-[13px] font-medium ${doc.iconColor} hover:bg-slate-50" onclick="printItem('${type}', ${JSON.stringify(item).replace(/"/g, '&quot;')})">
        //                         <span>🖨</span> Cetak
        //                     </button>
        //                 `;
        //             } else if (['form_ilpd', 'ilpd'].includes(type)) {
        //                 documentButtons += `
        //                     <a href="/ilpd/${itemId}" class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-medium text-white hover:bg-blue-700">
        //                         Proses
        //                     </a>
        //                 `;
        //             }
        //         }

        //         else if (['Disetujui', 'Approved'].includes(mainStatus)) {
        //             documentButtons += `
        //                 <button type="button" class="flex items-center gap-1.5 rounded-lg border ${doc.border} px-4 py-2 text-[13px] font-medium ${doc.iconColor} hover:bg-slate-50" onclick="printItem('${type}', ${JSON.stringify(item).replace(/"/g, '&quot;')})">
        //                     <span>🖨</span> Cetak
        //                 </button>
        //             `;
        //         }
        //     }

        //     else {

        //         if (['Menunggu Approval', 'menunggu_approval', 'Pending'].includes(mainStatus)) {

        //         }

        //         else if (['Sedang Diproses', 'sedang_diproses'].includes(mainStatus)) {
        //             if (['form_sppd', 'sppd'].includes(type)) {
        //                 documentButtons += `
        //                     <button type="button" 
        //                             data-item="${safeItemJson}"
        //                             onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
        //                             class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
        //                         <span>🖨</span> Cetak
        //                     </button>
        //                 `;
        //             }
        //         }

        //         else if (['Disetujui', 'Approved'].includes(mainStatus)) {
        //             documentButtons += `
        //                 <button type="button" 
        //                         data-item="${safeItemJson}"
        //                         onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
        //                         class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
        //                     <span>🖨</span> Cetak
        //                 </button>
        //             `;
        //         }

        //     }

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

    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
    }

window.showDetail = showDetail;

function formatRangeTanggal(startDateStr, endDateStr) {
    if (!startDateStr) return '-';

    const start = new Date(startDateStr);
    const end = endDateStr ? new Date(endDateStr) : start;

    const bulanIndo = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];

    const dStart = start.getDate();
    const dEnd = end.getDate();
    const mStart = bulanIndo[start.getMonth()];
    const mEnd = bulanIndo[end.getMonth()];
    const yStart = start.getFullYear();
    const yEnd = end.getFullYear();

    if (mStart === mEnd && yStart === yEnd) {
        if (dStart === dEnd) {
            return `${dStart} ${mStart} ${yStart}`;
        }
        return `${dStart} - ${dEnd} ${mStart} ${yStart}`;
    }

    if (yStart === yEnd) {
        return `${dStart} ${mStart} - ${dEnd} ${mEnd} ${yStart}`;
    }

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
            description: "Form SPPD perjalanan dinas.",
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
            description: "E-ticket perjalanan dinas.",
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

// 1. HELPER: Ekstraksi Data (Menangani perbedaan kolom & relasi dari Laravel)
function extractPrintData(type, item) {
    const ilpd = item.ilpd || {};
    const tiketList = ilpd.tikets || ilpd.tiket || [];
    const tiket = Array.isArray(tiketList) ? (tiketList[0] || {}) : tiketList;

    return {
        // Data Pemohon (Ambil dari user/item)
        nama: item.user?.name || item.nama || item.user_name || '-',
        nik: item.user?.nik || item.nik || '-',
        jabatan: item.user?.jabatan || item.jabatan || '-',
        departemen: item.user?.departemen || item.departemen || '-',

        // Data Form 1 (SPPD)
        noSppd: item.no_sppd || item.no || '-',
        tujuan: item.tujuan || item.kota_tujuan || item.kota || '-',
        waktu: item.tanggal_perjalanan || item.waktu || '-',
        keperluan: item.keperluan || item.keterangan || '-',
        transport: item.transport || item.jenis_transport || '-',
        tugas: item.tugas || item.uraian_tugas || '-',

        // Data Form 2 (ILPD)
        noIlpd: ilpd.no_ilpd || item.no_ilpd || '-',
        biaya: ilpd.total_biaya || ilpd.biaya || '-',

        // Data Tiket
        jenisTiket: tiket.type ? `Tiket ${tiket.type.toUpperCase()}` : 'Tiket Perjalanan',
        fileTiket: tiket.file ? `/storage/${tiket.file}` : null
    };
}

function lihatItem(type, item) {

    if (!item) {
        alert('Data tidak ditemukan!');
        return;
    }

    let htmlContent = '';

    // 1. Pilih Layout berdasarkan Tipe Dokumen
    if (type === 'form_sppd') {
        htmlContent = generateSppdLayout(item); // Fungsi SPPD kamu
    } else if (type === 'form_ilpd') {
        htmlContent = generateIlpdLayout(item); // Memanggil fungsi ILPD di atas
    } else if (type === 'tiket') {
        htmlContent = generateTiketLayout(item);
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

function printItem(type, item) {

    if (!item) {
        alert('Data tidak ditemukan!');
        return;
    }

    let htmlContent = '';

    // 1. Pilih Layout berdasarkan Tipe Dokumen
    if (type === 'form_sppd') {
        htmlContent = generateSppdLayout(item); // Fungsi SPPD kamu
    } else if (type === 'form_ilpd') {
        htmlContent = generateIlpdLayout(item); // Memanggil fungsi ILPD di atas
    } else if (type === 'tiket') {
        htmlContent = generateTiketLayout(item);
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

function generateSppdLayout(item) {
    const sppd = item.sppd || {};
    const user = item.user || {};
    const kota = item.kota || {};

    const today = new Date();
    const formattedDate = new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(today);

    // 1. Ambil array approval
    const approvals = item.sppd_approval || item.approval || [];
    const approval = item.ilpd?.ilpd_approval || item.ilpd?.approval || [];

    // 2. Cari data approval yang sudah 'Disetujui' dan punya signature/approver
    const managerApp = Array.isArray(approvals) 
        ? approvals.find(app => app.status === 2 || app.status === '2' && app.signature) || {}
        : (approvals || {});

    // const managerApp = Array.isArray(approvals) 
    //     ? approvals.find(app => app.status === 'Disetujui' && app.signature) || {}
    //     : (approvals || {});

    // 3. Ambil TTD Manager
    const managerSignature = managerApp.signature ? `/storage/${managerApp.signature}` : null;

    // 4. Ambil Nama Manager (panggil relasi 'sppd_approver' yang baru kamu buat)
    const managerName = managerApp.sppd_approver?.name || '........................';

    const approveJabatan = managerApp.sppd_approver?.jabatan?.name || '........................';

    console.log("=== DEBUG APPROVALS ===", approvals);
    // console.log("=== DEBUG ===", managerApp);

    // 3. TTD Pemohon (User pembuat SPPD)
    const pemohonUser = item.user || {};
    const pemohonSignature = pemohonUser.signature ? `/storage/${pemohonUser.signature}` : null;
    const pemohonName = pemohonUser.name || '........................';
    const pemohonJabatan = pemohonUser.jabatan?.name || '........................';

    const tugasArray = item.tugas ? item.tugas.split(/\r?\n/) : [];

    // Buat HTML untuk 3 baris list tugas (1, 2, 3)
    const tugasListHtml = `
        <div class="tugas-list">
            <div class="tugas-item">
                <div class="garis-tugas">${tugasArray[0] || ''}</div>
            </div>
            <div class="tugas-item">
                <div class="garis-tugas">${tugasArray[1] || ''}</div>
            </div>
            <div class="tugas-item">
                <div class="garis-tugas">${tugasArray[2] || ''}</div>
            </div>
        </div>
    `;

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
                    max-height: 80px;
                    height: auto;
                    display: block;
                    margin: 0 auto;
                }

                .kop-perusahaan {
                    width: 50%;
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
                    width: 30%;
                    text-align: right;
                    font-size: 9pt;
                }

                .nomor-box {
                    border: 1px solid #000;
                    display: inline-block;
                    text-align: left;
                    width: 100%;
                    white-space: nowrap;
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
                    vertical-align: top; /* Ditambahkan agar label sejajar di atas saat teks tugas panjang */
                    padding-top: 4px;
                }

                .colon-col {
                    width: 15px;
                    text-align: center;
                    vertical-align: top;
                    padding-top: 4px;
                }

                /* Mengubah box/kotak menjadi garis panjang di bawah */
                .value-box {
                    border: none;
                    border-bottom: 1px solid #000; /* Garis pemisah bawah hitam */
                    border-radius: 0;
                    padding: 4px 6px;
                    min-height: 18px;
                    background-color: transparent;
                    width: 100%;
                }

                /* Style khusus untuk list Tugas bernomor */
                .tugas-list {
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                }

                .tugas-item {
                    display: flex;
                    align-items: flex-end;
                }

                .tugas-item .nomor {
                    width: 20px;
                    font-weight: normal;
                }

                .tugas-item .garis-tugas {
                    flex-grow: 1;
                    border-bottom: 1px solid #000;
                    padding-left: 5px;
                    min-height: 18px;
                }

                /* TANGGAL & TANDA TANGAN */
                .ttd-section {
                    margin-top: 50px; /* Diperbesar agar posisi blok TTD lebih turun ke bawah */
                    width: 100%;
                    page-break-inside: avoid;
                }

                .tanggal-surat {
                    text-align: left;
                    margin-bottom: 25px;
                }

                .ttd-table {
                    width: 100%;
                    border-collapse: collapse;
                    text-align: center;
                    table-layout: fixed;
                    font-size: 9pt;
                }

                /* Header Kolom */
                .ttd-table th {
                    border: 1px solid #000;
                    padding: 6px;
                    font-weight: bold;
                    background-color: #f8fafc;
                }

                /* Cell Tanda Tangan */
                .ttd-table td {
                    border: 1px solid #000;
                    padding: 6px 4px;
                    vertical-align: bottom;
                }

                /* Area Gambar Tanda Tangan (Diperbesar) */
                .signature-box {
                    height: 90px; /* Dipertinggi dari 50px agar area TTD lebih leluasa */
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .signature-box img {
                    max-height: 80px; /* Diperbesar dari 45px */
                    max-width: 120px;
                    object-fit: contain;
                }

                /* Detail Nama & Jabatan */
                .ttd-info {
                    text-align: left;
                    font-size: 8.5pt;
                    line-height: 1.4;
                }

                .ttd-info-center {
                    text-align: center;
                    font-size: 8.5pt;
                    line-height: 1.4;
                }

                /* Garis Pemisah Tipis antara Nama dan Jabatan */
                .ttd-divider {
                    border-top: 1px solid #000;
                    margin: 4px -4px 0 -4px; /* Garis tetap panjang penuh */
                    padding: 4px 4px 0 4px;
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
                        <img src="/images/logo.png" alt="Logo Perusahaan">
                    </div>
                    <div class="kop-col kop-perusahaan">
                        <h4>PT. GOBEL DHARMA SARANA KARYA</h4>
                    </div>
                    <div class="kop-col kop-nomor">
                        <div class="nomor-box">
                            <div><strong>No. Dok:</strong>GDSK-HRD/FR/04-Y</div>
                            <div><strong>Revisi:</strong> 00 <03></div>
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
                        <td><div class="value-box">${item.user?.name || item.name || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">NIK</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${item.user?.nik || item.nik || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Jabatan</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${item.user?.jabatan?.name || item.user?.jabatan || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Departemen</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${item.user?.department?.name || item.user?.name || '-'}</div></td>
                    </tr>
                </table>

                <!-- 4. BAGIAN 2: PERJALANAN DINAS -->
                <div class="section-header">Untuk melakukan perjalanan dinas:</div>
                <table class="form-table">
                    <tr>
                        <td class="label-col">Kota Tujuan</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${item.kota?.name || item.name || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Waktu</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${item.durasi || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Keperluan</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${item.keperluan_list || item.keperluan_lainnya || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Jenis Transportasi</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${item.transport_list || item.transport_lainnya || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Tugas</td>
                        <td class="colon-col">:</td>
                        <td>
                            ${tugasListHtml}
                        </td>
                    </tr>
                </table>

                <!-- 5. TANGGAL & MASA TANDA TANGAN (5 KOLOM) -->
                <div class="ttd-section">
                    <div class="tanggal-surat">
                        Jakarta, ${formattedDate}
                    </div>

                    <table class="ttd-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Yang Ditugaskan</th>
                                <th style="width: 20%;">Yang memberi Tugas</th>
                                <th colspan="3" style="width: 60%;">Disetujui Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris Area Tanda Tangan / Gambar -->
                            <tr>
                                <td>
                                    <div class="signature-box">
                                        ${pemohonSignature ? `<img src="${pemohonSignature}" alt="TTD Pemohon" />` : ''}
                                    </div>
                                </td>
                                <td>
                                    <div class="signature-box">
                                        ${managerSignature ? `<img src="${managerSignature}" alt="TTD Atasan" />` : ''}
                                    </div>
                                </td>
                                <td>
                                    <div class="signature-box"></div>
                                </td>
                                <td>
                                    <div class="signature-box"></div>
                                </td>
                                <td>
                                    <div class="signature-box"></div>
                                </td>
                            </tr>

                            <!-- Baris Nama & Jabatan -->
                            <tr>
                                <td>
                                    <div class="ttd-info">
                                        <div>Nama : ${pemohonName || ''}</div>
                                        <div class="ttd-divider">Jabatan : ${pemohonJabatan || ''}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ttd-info">
                                        <div>Nama : ${managerName || ''}</div>
                                        <div class="ttd-divider">Jabatan : ${approveJabatan || ''}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ttd-info-center">
                                        <div>Chairudin Hidayat D</div>
                                        <div class="ttd-divider">GM HrGA & IT</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ttd-info-center">
                                        <div>Rudi Pasaribu</div>
                                        <div class="ttd-divider">Director</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ttd-info-center">
                                        <div>Ade Kosasih</div>
                                        <div class="ttd-divider">Director</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </body>
        </html>
    `;
}

const formatRupiah = (val) => {
    if (!val || isNaN(val)) return '0';
    return new Intl.NumberFormat('id-ID').format(val);
};

// Fungsi khusus untuk me-render HTML ILPD
function generateIlpdLayout(item) {
    const ilpd = item.ilpd || {};
    const user = item.user || {};
    const kota = item.kota || {};
    const perkiraan = ilpd.detail_ilpd || {};
    // Ambil data tiket jika ada
    const tiketList = ilpd.tikets || ilpd.tiket || [];
    const tiket = Array.isArray(tiketList) ? (tiketList[0] || {}) : tiketList;
    
    const today = new Date();
    const formattedDate = new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(today);
    
    const laporan = ilpd.laporan || {};

    let realisasi = {};
        if (typeof laporan.realisasi === 'string') {
            try {
                realisasi = JSON.parse(laporan.realisasi);
            } catch (e) {
                realisasi = {};
            }
        } else if (laporan.realisasi) {
            realisasi = laporan.realisasi;
        }

    // Ambil array ilpd_approval yang berada di dalam objek ilpd
    const approvals = Array.isArray(item.ilpd?.ilpd_approval)
        ? item.ilpd.ilpd_approval
        : (Array.isArray(item.sppd_approval) ? item.sppd_approval : []);

    console.log("=== DEBUG APPROVALS ===", approvals);

    // 2. Cari data approval yang sudah 'Disetujui' dan punya signature/approver
    const managerApp = approvals.find(app => app.status === 2 || app.status === '2' && app.signature) || {};
    // const managerApp = approvals.find(app => app.status === 'Sedang Diproses' && app.signature) || {};

    const managerSignature = managerApp.signature ? `/storage/${managerApp.signature}` : null;

    // 3. TTD Pemohon (User pembuat SPPD)
    const pemohonUser = item.user || {};
    const pemohonSignature = pemohonUser.signature ? `/storage/${pemohonUser.signature}` : null;

    // Masukkan kode HTML ILPD kamu di dalam template string backtick (`)
    return `
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Cetak ILPD - ${item.no_sppd || '-'}</title>
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
                    <td colspan="4" class="text-right"><strong>No:</strong> ${item.no_sppd || '-'}</td>
                </tr>
                <!-- Nama Penandatangan Atas -->
                <!-- Baris 1: Judul Jabatan / Header TTD -->
                <tr class="text-center font-bold bg-gray-100">
                    <td style="padding: 6px;">Direktur Utama</td>
                    <td style="padding: 6px;">Direktur</td>
                    <td style="padding: 6px;">Direktur Finance</td>
                    <td style="padding: 6px;">Finance</td>
                    <td style="padding: 6px;">HRGA</td>
                    <td style="padding: 6px;">Pemohon</td>
                    <td style="padding: 6px;">Atasan / Manager</td>
                    <td style="padding: 6px;">Tanggal Cetak</td>
                </tr>

                <!-- Baris 2: Area Gambar TTD (ttd-box) -->
                <tr class="ttd-box text-center" style="height: 65px;">
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_3 ? `<img src="/storage/${ilpd.ttd_3}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_4 ? `<img src="/storage/${ilpd.ttd_4}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_5 ? `<img src="/storage/${ilpd.ttd_5}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_6 ? `<img src="/storage/${ilpd.ttd_6}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_7 ? `<img src="/storage/${ilpd.ttd_7}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${pemohonSignature ? `<img src="${pemohonSignature}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${managerSignature ? `<img src="${managerSignature}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle; font-size: 11px;">
                        ${formattedDate || '-'}
                    </td>
                </tr>
            </table>

            <!-- 2. INFORMASI PERJALANAN (FORM 1) -->
            <div class="section-title">1. INFORMASI PERJALANAN</div>
            <table class="table-doc">
                <tr>
                    <td width="20%"><strong>Kota Tujuan</strong></td>
                    <td colspan="3">: ${kota.name || item.kota_tujuan || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Lama Perjalanan</strong></td>
                    <td colspan="3">: ${item.durasi || '-'} Hari</td>
                </tr>
                <tr>
                    <td><strong>Transportasi</strong></td>
                    <td colspan="3">: ${item.transport_list || item.transport_lainnya || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Keperluan</strong></td>
                    <td colspan="3">: ${item.keperluan_list || item.keperluan_lainnya || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Tugas</strong></td>
                    <td colspan="3">
                        <div style="margin: 0;">
                            ${(item.tugas || '-').split('\n').map(t => `<div>${t}</div>`).join('')}
                        </div>
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
                            <tr><td>BBM</td><td class="text-right">Rp ${formatRupiah(perkiraan.bbm)}</td></tr>
                            <tr><td>Uang Harian (Dinas)</td><td class="text-right">Rp ${formatRupiah(perkiraan.dinas)}</td></tr>
                            <tr><td>Uang Makan</td><td class="text-right">Rp ${formatRupiah(perkiraan.makan)}</td></tr>
                            <tr><td>Hotel</td><td class="text-right">Rp ${formatRupiah(perkiraan.hotel)}</td></tr>
                            <tr><td>Transport Lokal</td><td class="text-right">Rp ${formatRupiah(perkiraan.transport_lokal)}</td></tr>
                            <tr><td>Visa</td><td class="text-right">Rp ${formatRupiah(perkiraan.visa)}</td></tr>
                            <tr><td>Fiskal</td><td class="text-right">Rp ${formatRupiah(perkiraan.fiskal)}</td></tr>
                            <tr><td>Tax Airport</td><td class="text-right">Rp ${formatRupiah(perkiraan.airport_tax)}</td></tr>
                            <tr><td>Parkir & Tol</td><td class="text-right">Rp ${formatRupiah(perkiraan.parkirtoll)}</td></tr>
                            <tr><td>Entertaiment</td><td class="text-right">Rp ${formatRupiah(perkiraan.entertaiment)}</td></tr>
                            <tr><td>Lainnya</td><td class="text-right">Rp ${formatRupiah(perkiraan.dll)}</td></tr>
                        </table>
                    </td>
                    <!-- Kolom Realisasi -->
                    <td>
                        <table style="width:100%;">
                            <tr><td>BBM</td><td class="text-right">Rp ${formatRupiah(realisasi.bbm)}</td></tr>
                            <tr><td>Uang Harian (Dinas)</td><td class="text-right">Rp ${formatRupiah(realisasi.dinas)}</td></tr>
                            <tr><td>Uang Makan</td><td class="text-right">Rp ${formatRupiah(realisasi.makan)}</td></tr>
                            <tr><td>Hotel</td><td class="text-right">Rp ${formatRupiah(realisasi.hotel)}</td></tr>
                            <tr><td>Transport Lokal</td><td class="text-right">Rp ${formatRupiah(realisasi.transport_lokal)}</td></tr>
                            <tr><td>Visa</td><td class="text-right">Rp ${formatRupiah(realisasi.visa)}</td></tr>
                            <tr><td>Fiskal</td><td class="text-right">Rp ${formatRupiah(realisasi.fiskal)}</td></tr>
                            <tr><td>Tax Airport</td><td class="text-right">Rp ${formatRupiah(realisasi.airport_tax)}</td></tr>
                            <tr><td>Parkir & Tol</td><td class="text-right">Rp ${formatRupiah(realisasi.parkirtoll)}</td></tr>
                            <tr><td>Entertaiment</td><td class="text-right">Rp ${formatRupiah(realisasi.entertaiment)}</td></tr>
                            <tr><td>Lainnya</td><td class="text-right">Rp ${formatRupiah(realisasi.dll)}</td></tr>
                        </table>
                    </td>
                </tr>
                <tr class="font-bold bg-gray">
                    <td>TOTAL PERKIRAAN: <span style="float:right;">Rp ${formatRupiah(perkiraan.total)}</span></td>
                    <td>TOTAL REALISASI: <span style="float:right;">Rp ${formatRupiah(laporan.total_realisasi)}</span></td>
                </tr>
            </table>

            <!-- 4. SUMMARY KEUANGAN & LOKASI -->
            <table class="table-doc">
                <tr>
                    <td width="50%" rowspan="3">
                        <strong>Yang Dikunjungi / Judul:</strong><br>
                        ${laporan.laporan_1 || '-'}
                    </td>
                    <td width="25%"><strong>Uang Muka</strong></td>
                    <td width="25%" class="text-right">Rp ${formatRupiah(perkiraan.uang_muka)}</td>
                </tr>
                <tr>
                    <td><strong>Selisih (Lebih / Kurang)</strong></td>
                    <td class="text-right">Rp ${formatRupiah(laporan.selisih)}</td>
                </tr>
                <tr>
                    <td><strong>Keterangan Selisih</strong></td>
                    <td>${laporan.keterangan || '-'}</td>
                </tr>
            </table>

            <!-- 5. LAPORAN HASIL -->
            <div class="section-title">LAPORAN HASIL PERJALANAN DINAS</div>
            <table class="table-doc">
                <tr>
                    <td style="height: 60px;">${laporan.laporan_2 || '-'}</td>
                </tr>
            </table>

            <!-- 6. TTD BAWAH (APPROVAL AKHIR) -->
            <table class="table-doc">
                <tr class="text-center font-bold bg-gray-100">
                    <td style="padding: 6px;">Direktur Utama</td>
                    <td style="padding: 6px;">Direktur</td>
                    <td style="padding: 6px;">Direktur Finance</td>
                    <td style="padding: 6px;">Finance</td>
                    <td style="padding: 6px;">HRGA</td>
                    <td style="padding: 6px;">Pemohon</td>
                    <td style="padding: 6px;">Manager</td>
                </tr>

                <tr class="ttd-box text-center" style="height: 65px;">
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_3 ? `<img src="/storage/${ilpd.ttd_3}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_4 ? `<img src="/storage/${ilpd.ttd_4}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_5 ? `<img src="/storage/${ilpd.ttd_5}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_6 ? `<img src="/storage/${ilpd.ttd_6}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${ilpd.ttd_7 ? `<img src="/storage/${ilpd.ttd_7}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${pemohonSignature ? `<img src="${pemohonSignature}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                    <td style="vertical-align: middle;">
                        ${managerSignature ? `<img src="${managerSignature}" style="max-height: 55px; max-width: 110px; margin: 0 auto; object-fit: contain;" />` : ''}
                    </td>
                </tr>
            </table>


        </body>
        </html>
    `;
}