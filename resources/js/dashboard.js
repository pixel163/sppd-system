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
                    ${typeof statusBadge === 'function' ? statusBadge(item.dinas?.status) : `<span class="font-semibold">${item.dinas?.status}</span>`}
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

    // C. Ambil data tiket dari dalam objek ILPD (Mendukung relasi 'tikets' array atau 'tiket' object)
    const listTiket = item.ilpd ? (item.ilpd.tikets || item.ilpd.tiket) : null;

    // Cek apakah ada data tiket (Baik bentuk Array yang ada isinya, maupun Object tunggal)
    const hasTiket = listTiket && (Array.isArray(listTiket) ? listTiket.length > 0 : Object.keys(listTiket).length > 0);

    if (hasTiket) {
        dokumenList.push('tiket');
    }
    // if (item.tiket || item.tiket_id) {
    //     dokumenList.push('tiket');
    // }

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
                
            } else if (type === 'tiket') {
                // Ambil tiket (jika array ambil elemen pertama)
                const tiketData = Array.isArray(listTiket) ? listTiket[0] : listTiket;

                // Tanggal upload tiket
                rawDate = tiketData.uploaded_at || tiketData.created_at || item.created_at;

                // Judul Dokumen (Misal: "Tiket (jalan)" atau "Tiket Perjalanan")
                doc.title = tiketData.type ? `Tiket ${tiketData.type.toUpperCase()}` : "Tiket Perjalanan";

                // Tampilan disamakan seperti form 1 & 2 (Mengarahkan ke route/URL file atau edit)
                // Jika tiket berupa file upload yang ingin langsung dibuka/diklik:
                if (tiketData.file) {
                    editUrl = `/storage/${tiketData.file}`; // Atau route preview tiket kamu
                } else {
                    editUrl = `/tiket/${tiketData.id}/edit`;
                }
            }

            // Format tanggal menjadi "1 September 2026"
            const docDate = typeof formatTanggalIndo === 'function' ? formatTanggalIndo(rawDate) : (rawDate || '-');

            // Kondisi Tombol Action
            // const status = item.status;
            const status = type === 'form_ilpd'
                ? item.ilpd?.status
                : item.sppd?.status ?? item.status;
            const docUserId = item.user_id || item.sppd?.user_id || item.dinas?.sppd?.user_id;
            // Cek kepemilikan & Role
            const isMyDocument = (Number(docUserId) === Number(window.currentUserId));
            const isHRGA = window.currentUserRole === 'HRGA';

            if (status === 'Menunggu Approval' || status === 'Menunggu Approval') {
                documentButtons = `
                    <button type="button" class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b] hover:bg-slate-50" onclick="openDocumentModal('${type}', '${itemId}')">
                        <span>👁</span> Lihat
                    </button>
                `;
            } else if (status === 'Sedang Diproses' || status === 'Sedang Diproses') {
                const showProcessBtn = (type === 'form_ilpd') && isHRGA && !isMyDocument;;
                documentButtons = `
                    <button type="button" class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b] hover:bg-slate-50" onclick="openDocumentModal('${type}', '${itemId}')">
                        <span>👁</span> Lihat
                    </button>
                    ${showProcessBtn ? `
                        <a href="/ilpd/${itemId}" class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-medium text-white hover:bg-blue-700">
                            Proses
                        </a>
                    ` : ''}
                `;
            } else if (['Disetujui', 'Approved', 'Approval'].includes(status)) {
                documentButtons = `
                    <button type="button" class="flex items-center gap-1.5 rounded-lg border border-[#f1f5f9] px-4 py-2 text-[13px] font-medium text-[#64748b] hover:bg-slate-50" onclick="openDocumentModal('${type}', '${itemId}')">
                        <span>👁</span> Lihat
                    </button>
                    <button type="button" class="flex items-center gap-1.5 rounded-lg border ${doc.border} px-4 py-2 text-[13px] font-medium ${doc.iconColor} hover:bg-slate-50" onclick="printItem('${type}', ${JSON.stringify(item).replace(/"/g, '&quot;')})">
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
        transportasi: item.transportasi || item.jenis_transportasi || '-',
        tugas: item.tugas || item.uraian_tugas || '-',

        // Data Form 2 (ILPD)
        noIlpd: ilpd.no_ilpd || item.no_ilpd || '-',
        biaya: ilpd.total_biaya || ilpd.biaya || '-',

        // Data Tiket
        jenisTiket: tiket.type ? `Tiket ${tiket.type.toUpperCase()}` : 'Tiket Perjalanan',
        fileTiket: tiket.file ? `/storage/${tiket.file}` : null
    };
}

function printItem(type, item) {

    if (!item) {
        alert('Data tidak ditemukan!');
        return;
    }

    // const ilpd = item.ilpd || {};
    // const tiketList = ilpd.tikets || ilpd.tiket || [];
    // const tiket = Array.isArray(tiketList) ? (tiketList[0] || {}) : tiketList;

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
    // let htmlContent = '';

    // // 1. Pilih Layout berdasarkan Tipe Dokumen
    // if (type === 'form_sppd') {
    //     htmlContent = generateSppdLayout(item);
    // } else if (type === 'form_ilpd') {
    //     htmlContent = generateIlpdLayout(item);
    // } else if (type === 'tiket') {
    //     htmlContent = generateTiketLayout(item);
    // }

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
    // Ambil data tiket jika ada
    // const tiketList = ilpd.tikets || ilpd.tiket || [];
    // const tiket = Array.isArray(tiketList) ? (tiketList[0] || {}) : tiketList;

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
                        <td><div class="value-box">${item.durasi || item.durasi || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Keperluan</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${item.keperluan?.name || item.name || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Jenis Transportasi</td>
                        <td class="colon-col">:</td>
                        <td><div class="value-box">${item.transport?.name || item.name || '-'}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Tugas</td>
                        <td class="colon-col">:</td>
                        <td>
                            <div class="value-box large">
                                ${item.tugas || item.tugas || '-'}
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
function generateIlpdLayout(item) {
    const ilpd = item.ilpd || {};
    const user = item.user || {};
    const kota = item.kota || {};
    const perkiraan = ilpd.perkiraan_biaya || {};
    const realisasi = ilpd.realisasi_biaya || {};
    // Ambil data tiket jika ada
    const tiketList = ilpd.tikets || ilpd.tiket || [];
    const tiket = Array.isArray(tiketList) ? (tiketList[0] || {}) : tiketList;

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
                    <td colspan="8" class="title-header">${ilpd.nama_surat || 'IZIN LOKASI PERJALANAN DINAS (ILPD)'}</td>
                </tr>
                <tr>
                    <td colspan="4" class="company-name">${ilpd.nama_perusahaan || 'PT. NAMA PERUSAHAAN'}</td>
                    <td colspan="4" class="text-right"><strong>No:</strong> ${item.no_sppd || '-'}</td>
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
                    <td colspan="3">: ${kota.name || item.kota_tujuan || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Lama Perjalanan</strong></td>
                    <td colspan="3">: ${item.tgl_mulai || '-'} s/d ${item.tgl_akhir || '-'} (${item.durasi || '-'} Hari)</td>
                </tr>
                <tr>
                    <td><strong>Transportasi</strong></td>
                    <td colspan="3">: ${item.transport || item.transport_id || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Keperluan</strong></td>
                    <td colspan="3">: ${item.keperluan || item.keperluan_id || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Tugas</strong></td>
                    <td colspan="3">
                        <ol style="margin: 0; padding-left: 15px;">
                            ${(item.tugas || '-').split('\n').map(t => `<li>${t}</li>`).join('')}
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
                            <tr><td>Uang Harian (Dinas)</td><td class="text-right">Rp ${perkiraan.uang_dinas || '0'}</td></tr>
                            <tr><td>Uang Makan</td><td class="text-right">Rp ${perkiraan.uang_makan || '0'}</td></tr>
                            <tr><td>Hotel</td><td class="text-right">Rp ${perkiraan.hotel || '0'}</td></tr>
                            <tr><td>Transport Lokal</td><td class="text-right">Rp ${perkiraan.transport_lokal || '0'}</td></tr>
                            <tr><td>Visa / Fiskal</td><td class="text-right">Rp ${perkiraan.visa_fiskal || '0'}</td></tr>
                            <tr><td>Tax Airport / Parkir / Tol</td><td class="text-right">Rp ${perkiraan.tax_parkir_tol || '0'}</td></tr>
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
                    <td width="25%" class="text-right">Rp ${ilpd.uang_muka || '0'}</td>
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