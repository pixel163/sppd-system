@extends('layouts.app')

@section('title', 'Form SPPD - SPPD System')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form SPPD - SPPD System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen w-full">

        {{-- =====================================================
             CONTENT
        ====================================================== --}}
        <div class="w-full px-5 py-6 sm:px-7 lg:px-8">

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
            <div class="form-card rounded-2xl border border-[#f1f5f9] bg-white p-8 shadow-[0px_4px_6px_rgba(15,23,42,0.02)]">

                {{-- =================================================
                    DATA PEGAWAI (READONLY)
                ================================================== --}}

                {{-- Nama --}}
                <div class="form-row flex gap-6 py-3">
                    <label for="nama" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                        Nama
                    </label>
                    <div class="relative flex-1">
                        <input
                            id="nama"
                            type="text"
                            value="{{ $sppd->user->name ?? '-' }}"
                            class="form-input w-full rounded-lg border border-[#e2e8f0] bg-slate-50 px-4 py-2.5 text-[14px] text-slate-600"
                            disabled>
                    </div>
                </div>

                {{-- NIK --}}
                <div class="form-row flex gap-6 py-3">
                    <label for="nik" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                        NIK
                    </label>
                    <input
                        id="nik"
                        type="text"
                        value="{{ $sppd->user->nik ?? '-' }}"
                        class="form-input flex-1 rounded-lg border border-[#e2e8f0] bg-slate-50 px-4 py-2.5 text-[14px] text-slate-600"
                        disabled>
                </div>

                {{-- Jabatan --}}
                <div class="form-row flex gap-6 py-3">
                    <label for="jabatan" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                        Jabatan
                    </label>
                    <input
                        id="jabatan"
                        type="text"
                        value="{{ $sppd->user->jabatan->name ?? '-' }}"
                        class="form-input flex-1 rounded-lg border border-[#e2e8f0] bg-slate-50 px-4 py-2.5 text-[14px] text-slate-600"
                        disabled>
                </div>

                {{-- Departemen --}}
                <div class="form-row flex gap-6 py-3">
                    <label for="departemen" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                        Departemen
                    </label>
                    <input
                        id="departemen"
                        type="text"
                        value="{{ $sppd->user->department->name ?? '-' }}"
                        class="form-input flex-1 rounded-lg border border-[#e2e8f0] bg-slate-50 px-4 py-2.5 text-[14px] text-slate-600"
                        disabled>
                </div>

                {{-- =================================================
                    DATA PERJALANAN (READONLY)
                ================================================== --}}

                {{-- Kota Tujuan --}}
                <div class="form-row flex gap-6 py-3">
                    <label for="kota" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                        Kota Tujuan
                    </label>
                    <div class="flex-1">
                        <input 
                            type="text" 
                            value="{{ $sppd->kota->name ?? '-' }}" 
                            class="form-input w-full rounded-lg border border-[#e2e8f0] bg-slate-50 px-4 py-2.5 text-[14px] text-slate-600" 
                            disabled>
                    </div>
                </div>

                {{-- Waktu / Durasi --}}
                <div class="form-row flex gap-6 py-3">
                    <label for="waktu" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                        Waktu
                    </label>
                    <div class="flex-1">
                        <div class="relative">
                            <input
                                id="durasi"
                                type="text"
                                value="{{ $sppd->durasi }}"
                                class="form-input w-full rounded-lg border border-[#e2e8f0] bg-slate-50 px-4 py-2.5 pr-20 text-[14px] text-slate-600"
                                disabled>
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[13px] text-[#94a3b8]">
                                hari
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Keperluan Dinas --}}
                <div class="form-row flex gap-6 py-3">
                    <label for="keperluan" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                        Keperluan Dinas
                    </label>
                    <div class="flex-1">
                        <input 
                            type="text" 
                            value="{{ $sppd->keperluan->name ?? '-' }}" 
                            class="form-input w-full rounded-lg border border-[#e2e8f0] bg-slate-50 px-4 py-2.5 text-[14px] text-slate-600" 
                            disabled>
                    </div>
                </div>

                {{-- Transportasi --}}
                <div class="form-row flex gap-6 py-3">
                    <label for="transport" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                        Transportasi
                    </label>
                    <div class="flex-1">
                        <input 
                            type="text" 
                            value="{{ $sppd->transport->name ?? '-' }}" 
                            class="form-input w-full rounded-lg border border-[#e2e8f0] bg-slate-50 px-4 py-2.5 text-[14px] text-slate-600" 
                            disabled>
                    </div>
                </div>

                {{-- Tugas --}}
                <div class="form-row flex gap-6 py-3">
                    <label for="tugas" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                        Tugas
                    </label>
                    <div class="flex min-h-[140px] flex-1 rounded-lg border border-[#e2e8f0] bg-slate-50 px-4 py-3">
                        <textarea
                            id="tugas"
                            class="form-textarea w-full bg-transparent text-[14px] text-slate-600 outline-none resize-none"
                            disabled>{{ $sppd->tugas }}</textarea>
                    </div>
                </div>

                {{-- =================================================
                    SECTION KEPUTUSAN APPROVAL MANAGER
                ================================================== --}}
                {{-- <hr class="my-6 border-[#f1f5f9]" />

                <form id="approvalForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf --}}

                    {{-- Catatan Manager --}}
                    {{-- <div class="form-row flex gap-6 py-3">
                        <label for="catatan" class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Catatan Manager
                        </label>
                        <div class="flex-1">
                            <textarea
                                id="catatan"
                                name="catatan"
                                rows="3"
                                placeholder="Tambahkan catatan persetujuan atau alasan penolakan (Wajib diisi jika menolak)..."
                                class="w-full rounded-lg border border-[#e2e8f0] px-4 py-2.5 text-[14px] text-slate-800 outline-none transition focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20"></textarea>
                            @error('catatan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div> --}}

                    {{-- Kolom Tanda Tangan Digital (Canvas Pad) --}}
                    {{-- <div class="form-row flex gap-6 py-3">
                        <label class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Tanda Tangan
                        </label>
                        <div class="flex-1">
                            <div class="relative w-full max-w-md rounded-xl border border-[#e2e8f0] bg-slate-50 p-3">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="text-[12px] font-medium text-slate-500">Goreskan tanda tangan di bawah ini:</span>
                                    <button type="button" onclick="clearSignature()" class="text-[12px] font-semibold text-red-500 hover:text-red-700">
                                        ↺ Bersihkan
                                    </button>
                                </div> --}}

                                {{-- Area Coret TTD --}}
                                {{-- <canvas id="signatureCanvas" class="h-40 w-full touch-none rounded-lg border border-dashed border-slate-300 bg-white cursor-crosshair"></canvas> --}}
                                
                                {{-- Input Hidden untuk simpan string Base64 gambar TTD --}}
                                {{-- <input type="hidden" name="ttd_digital" id="ttdInput">
                            </div>
                            <p class="mt-1.5 text-[11px] text-[#94a3b8]">Tanda tangan wajib diisi sebelum menyetujui dokumen.</p>
                        </div>
                    </div> --}}

                    {{-- BUTTON ACTIONS --}}
                    {{-- <div class="form-actions flex justify-end gap-3 pt-6">

                        <button type="submit"
                            onclick="submitApproval('{{ route('sppd.approve', $sppd->id) }}')"
                            class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]">
                            Setujui
                        </button>

                    </div>
                </form> --}}

                <hr class="my-6 border-[#f1f5f9]" />

                <form id="approvalForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf

                    {{-- Upload File Tanda Tangan --}}
                    <div class="form-row flex gap-6 py-3">
                        <label for="ttd_file" class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Tanda Tangan (PNG/JPG)
                        </label>
                        <div class="flex-1">
                            <div class="flex items-center gap-4">
                                {{-- Input File --}}
                                <label class="flex cursor-pointer items-center justify-center rounded-lg border border-dashed border-[#cbd5e1] bg-slate-50 px-5 py-3 transition hover:border-[#2563eb] hover:bg-slate-100">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        <span class="text-[13px] font-medium text-slate-600" id="fileNameLabel">Upload File TTD</span>
                                    </div>
                                    <input 
                                        type="file" 
                                        id="ttd_file" 
                                        name="ttd_file" 
                                        accept="image/png, image/jpeg, image/jpg" 
                                        class="hidden" 
                                        onchange="previewTTD(event)">
                                </label>

                                {{-- Preview Gambar --}}
                                <div id="previewContainer" class="hidden h-16 w-32 rounded-lg border border-slate-200 bg-white p-1">
                                    <img id="imagePreview" src="#" alt="Preview TTD" class="h-full w-full object-contain" />
                                </div>
                            </div>

                            @error('ttd_file')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-[11px] text-[#94a3b8]">Format yang didukung: PNG, JPG, JPEG (Maks. 2MB). Disarankan menggunakan background transparan.</p>
                        </div>
                    </div>

                    {{-- BUTTON ACTIONS --}}
                    <div class="form-actions flex justify-end gap-3 pt-6">

                        <button type="submit"
                            onclick="submitApproval('{{ route('sppd.approve', $sppd->id) }}')"
                            class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]">
                            Setujui
                        </button>

                    </div>
                </form>

            </div>

        </div>

    </main>

</div>

</body>
</html>

@endsection

@push('scripts')

{{-- Script Preview File --}}
<script>
    function previewTTD(event) {
        const input = event.target;
        const label = document.getElementById('fileNameLabel');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            label.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function submitApproval(url) {
        const form = document.getElementById('approvalForm');
        form.action = url;
    }
</script>
    {{-- @vite('resources/js/sppd.js') --}}

{{-- Script JS untuk Handle Canvas TTD --}}
{{-- <script>
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let hasSignature = false;

    // Set ukuran canvas internal sesuai element
    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#0f172a';
    }
    window.addEventListener('resize', resizeCanvas);
    setTimeout(resizeCanvas, 100);

    // Event Mouse / Touch
    function startDrawing(e) {
        isDrawing = true;
        hasSignature = true;
        ctx.beginPath();
        const pos = getPos(e);
        ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
        if (!isDrawing) return;
        const pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function stopDrawing() {
        isDrawing = false;
    }

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('ttdInput').value = '';
        hasSignature = false;
    }

    function submitApproval(url) {
        const form = document.getElementById('approvalForm');
        form.action = url;
        
        // Simpan gambar TTD ke input hidden sebagai string Base64
        if (hasSignature) {
            document.getElementById('ttdInput').value = canvas.toDataURL('image/png');
        }
    }
</script> --}}
@endpush