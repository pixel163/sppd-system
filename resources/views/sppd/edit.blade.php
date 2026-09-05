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

                <form method="POST" action="{{ route('sppd.update', $sppd->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- =================================================
                        DATA PEGAWAI
                    ================================================== --}}

                    {{-- Nama --}}
                    <div class="form-row flex gap-6 py-3">

                        <label for="nama" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            Nama
                        </label>

                        <div class="relative flex-1">

                            <input
                                id="nama"
                                name="nama"
                                type="text"
                                {{-- value="{{ $users->name }}" --}}
                                value="{{ old('name', $sppd->user->name) }}"
                                placeholder="Nama otomatis terisi"
                                class="form-input"
                                readonly>
                        </div>

                    </div>

                    {{-- NIK --}}
                    <div class="form-row flex gap-6 py-3">

                        <label for="nik" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            NIK
                        </label>

                        <input
                            id="nik"
                            name="nik"
                            type="text"
                            value="{{ old('nik', $sppd->user->nik) }}"
                            class="form-input flex-1"
                            readonly>
                    </div>

                    {{-- Jabatan --}}
                    <div class="form-row flex gap-6 py-3">

                        <label for="jabatan" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            Jabatan
                        </label>

                        <input
                            id="jabatan"
                            name="jabatan"
                            type="text"
                            value="{{ old('jabatan', $sppd->user->jabatan->name) }}"
                            class="form-input flex-1"
                            readonly>
                    </div>

                    {{-- Departemen --}}
                    <div class="form-row flex gap-6 py-3">

                        <label for="departemen" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            Departemen
                        </label>

                        <input
                            id="departemen"
                            name="departemen"
                            type="text"
                            value="{{ old('department', $sppd->user->department->name) }}"
                            class="form-input flex-1"
                            readonly>
                    </div>

                    {{-- =================================================
                        DATA PERJALANAN (EDITABLE)
                    ================================================== --}}

                    {{-- Kota Tujuan --}}
                    <div class="form-row flex gap-6 py-3">
                        <label for="kota_id" class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Kota Tujuan
                        </label>
                        <div class="flex-1">
                            <select id="kota_id" name="kota_id" placeholder="Cari atau pilih kota..." autocomplete="off">
                                <option value="">Pilih Kota...</option>
                                @foreach($kotas as $kota)
                                    <option value="{{ $kota->id }}" {{ old('kota_id', $sppd->kota_id) == $kota->id ? 'selected' : '' }}>
                                        {{ $kota->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kota_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Waktu / Durasi --}}
                    <div class="form-row flex gap-6 py-3">
                        <label for="durasi" class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Waktu
                        </label>
                        <div class="flex-1">
                            <div class="relative">
                                <input
                                    id="durasi"
                                    name="durasi"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="2"
                                    onkeydown="cekkunci(event)"
                                    oninput="validasiWaktu(this)"
                                    value="{{ old('durasi', $sppd->durasi) }}"
                                    class="form-input w-full rounded-lg border border-[#e2e8f0] px-4 py-2.5 pr-20 text-[14px] text-slate-800 focus:border-[#2563eb] focus:outline-none">
                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[13px] text-[#94a3b8]">
                                    hari
                                </span>
                            </div>
                            @error('durasi')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Keperluan Dinas --}}
                    <div class="form-row flex gap-6 py-3">
                        <label for="keperluan_id" class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Keperluan Dinas
                        </label>
                        <div class="flex-1">
                            <select id="keperluan_id" name="keperluan_id" class="form-select w-full rounded-lg border border-[#e2e8f0] px-4 py-2.5 text-[14px] text-slate-800 focus:border-[#2563eb] focus:outline-none">
                                @foreach($keperluans as $keperluan)
                                    <option value="{{ $keperluan->id }}" {{ old('keperluan_id', $sppd->keperluan_id) == $keperluan->id ? 'selected' : '' }}>
                                        {{ $keperluan->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('keperluan_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Transportasi --}}
                    <div class="form-row flex gap-6 py-3">
                        <label for="transport_id" class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Transportasi
                        </label>
                        <div class="flex-1">
                            <select id="transport_id" name="transport_id" class="form-select w-full rounded-lg border border-[#e2e8f0] px-4 py-2.5 text-[14px] text-slate-800 focus:border-[#2563eb] focus:outline-none">
                                @foreach($transports as $transport)
                                    <option value="{{ $transport->id }}" {{ old('transport_id', $sppd->transport_id) == $transport->id ? 'selected' : '' }}>
                                        {{ $transport->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('transport_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Tugas --}}
                    <div class="form-row flex gap-6 py-3">
                        <label for="tugas" class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Tugas
                        </label>
                        <div class="flex-1">
                            <textarea
                                id="tugas"
                                name="tugas"
                                rows="4"
                                class="form-textarea w-full rounded-lg border border-[#e2e8f0] p-4 text-[14px] text-slate-800 focus:border-[#2563eb] focus:outline-none resize-none">{{ old('tugas', $sppd->tugas) }}</textarea>
                            @error('tugas')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-6 border-[#f1f5f9]" />

                    {{-- BUTTON ACTIONS --}}
                    <div class="form-actions flex justify-end gap-3 pt-2">
                        <a href="{{ url()->previous() }}" class="rounded-lg border border-slate-200 px-5 py-2.5 text-[14px] font-semibold text-slate-600 transition hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit" class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]">
                            Simpan
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

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#kota_id', {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });

// 1. Mencegah pengetikan karakter non-angka seperti e, +, -, ., dan ,
function cekkunci(e) {
    if (['e', 'E', '+', '-', '.', ','].includes(e.key)) {
        e.preventDefault();
    }
}

// 2. Membersihkan input jika di-paste & membatasi nilai max 14 dan min 1
function validasiWaktu(input) {
    // Hapus karakter selain digit angka
    input.value = input.value.replace(/[^0-9]/g, '');

    if (input.value !== '') {
        let val = parseInt(input.value, 10);
        
        // Mencegah nilai lebih dari 14
        if (val > 14) {
            input.value = 14;
        }
        
        // Mencegah angka 0 di depan (misal: 05 -> 5 atau 00 -> 1)
        if (val < 1) {
            input.value = '';
        }
    }
}

new TomSelect("#kota", {
    create: false,
    sortField: {
        field: "text",
        direction: "asc"
    }
});

</script>
    {{-- @vite('resources/js/sppd.js') --}}

@endpush