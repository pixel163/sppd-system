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

    {{-- ALERT PESAN SUKSES --}}
    @if (session('success'))
        <div class="mb-5 flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle-2" class="size-5 text-emerald-600"></i>
                <span class="text-[13px] font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
    @endif

    {{-- ALERT PESAN ERROR (TRANSACTION ROLLBACK / EXCEPTION) --}}
    @if (session('error'))
        <div class="mb-5 flex items-center justify-between rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-800 shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-circle" class="size-5 text-rose-600"></i>
                <span class="text-[13px] font-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
    @endif

    {{-- ALERT ERROR VALIDASI INPUT (Misal File Kegedean / Kosong) --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 shadow-sm">
            <div class="mb-1 flex items-center gap-2 font-bold text-[13px] text-amber-800">
                <i data-lucide="alert-triangle" class="size-4 text-amber-600"></i>
                <span>Gagal Memproses Data:</span>
            </div>
            <ul class="ml-6 list-disc text-[12px] text-amber-700 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                            value="{{ $sppd->keperluan_list ?? '-' }}"
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
                            value="{{ $sppd->transport_list ?? '-' }}" 
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
                <form id="approvalForm" method="POST" action="{{ route('sppd.approve', $sppd->id) }}" enctype="multipart/form-data">
                    @csrf

                    {{-- BUTTON ACTIONS --}}
                    <div class="form-actions flex justify-end gap-3 pt-6">
                        <button 
                            type="submit"
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

@endpush