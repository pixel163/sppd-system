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

                <form id="sppdForm" action="{{ route('sppd.store') }}" method="POST" class="space-y-0">
                    @csrf

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
                                value="{{ $user->name }}"
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
                            value="{{ $user->nik }}"
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
                            value="{{ $user->jabatan->name }}"
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
                            value="{{ $user->department->name }}"
                            class="form-input flex-1"
                            readonly>
                    </div>

                    {{-- =================================================
                        DATA PERJALANAN
                    ================================================== --}}

                    {{-- Kota (Searchable Dropdown) --}}
                    <div class="form-row flex gap-6 py-3" 
                        x-data="sppdForm">
                        
                        <!-- Label Kiri -->
                        <label class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Tujuan Dinas
                        </label>

                        <!-- Konten Kanan -->
                        <div class="flex-1 space-y-4">
                            
                            <!-- 1. PILIH AREA -->
                            <div class="w-full">
                                {{-- <label class="block font-medium text-sm text-slate-700 mb-1">Area Tujuan</label> --}}
                                <select name="area_id" x-model="selectedArea" @change="fetchKota" class="w-full rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 text-[14px] text-slate-800 focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20">
                                    <option value="">-- Pilih Area --</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 2. PILIH KOTA (Muncul jika Area dipilih) -->
                            <div class="w-full" x-show="selectedArea" x-cloak>
                                <label class="block font-medium text-sm text-slate-700 mb-1">Kota Tujuan</label>
                                <select name="kota_id" x-model="selectedKota" @change="fetchUnit" class="w-full rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 text-[14px] text-slate-800 focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20">
                                    <option value="">-- Pilih Kota --</option>
                                    <template x-for="kota in kotaList" :key="kota.id">
                                        <option :value="kota.id" x-text="kota.name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- 3. PILIH UNIT (Custom Dropdown Checkbox mirip Keperluan Dinas, muncul jika Kota dipilih) -->
                            <div class="w-full" x-show="selectedKota" x-cloak>
                                <label class="block font-medium text-sm text-slate-700 mb-1">Unit Tujuan</label>
                                
                                <div class="relative" @click.outside="openUnit = false">
                                    
                                    <!-- Hidden Inputs untuk dikirim ke Controller Laravel -->
                                    <template x-for="id in selectedUnits" :key="id">
                                        <input type="hidden" name="unit_ids[]" :value="id">
                                    </template>

                                    <!-- Display Box -->
                                    <div @click="openUnit = !openUnit" 
                                        class="min-h-[42px] w-full cursor-pointer rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 pr-10 text-[14px] text-slate-800 transition-all duration-200 focus-within:border-[#2563eb] focus-within:ring-2 focus-within:ring-[#2563eb]/20 flex flex-wrap gap-1.5 items-center">
                                        
                                        <!-- Placeholder -->
                                        <span x-show="selectedUnits.length === 0" class="text-slate-400">
                                            Pilih Unit Tujuan...
                                        </span>

                                        <!-- Badge Unit Terpilih -->
                                        <template x-for="(name, index) in selectedUnitNames" :key="index">
                                            <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                                <span x-text="name"></span>
                                                <button type="button" @click.stop="toggleUnit(selectedUnits[index], name)" class="text-blue-600 hover:text-blue-800">
                                                    &times;
                                                </button>
                                            </span>
                                        </template>

                                        <!-- Panah Dropdown -->
                                        <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                            <svg class="h-4 w-4 fill-current transition-transform duration-200" :class="openUnit ? 'rotate-180' : ''" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Menu Dropdown Checkbox Unit -->
                                    <div x-show="openUnit" 
                                        x-transition
                                        class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-slate-200 bg-white p-2 shadow-lg ring-1 ring-black/5"
                                        style="display: none;">
                                        
                                        <template x-for="unit in unitList" :key="unit.id">
                                            <label class="flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-[14px] text-slate-700 hover:bg-slate-50 transition-colors">
                                                <input type="checkbox" 
                                                    :value="unit.id"
                                                    :checked="selectedUnits.includes(String(unit.id))"
                                                    @change="toggleUnit(unit.id, unit.name)"
                                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                <span x-text="unit.name"></span>
                                            </label>
                                        </template>

                                        <div x-show="unitList.length === 0" class="px-3 py-2 text-sm text-slate-400 italic">Tidak ada unit di kota ini.</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- <div class="form-row flex gap-6 py-3">
                        <label for="kota" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            Kota Tujuan
                        </label>

                        <div class="flex-1">
                            <select 
                                id="kota" 
                                name="kota_id" 
                                class="form-select flex-1 w-full rounded-lg border border-[#e2e8f0] px-4 py-2.5 focus:border-[#2563eb]" 
                                required
                                oninvalid="this.setCustomValidity('Silahkan pilih kota tujuan')"
                                oninput="this.setCustomValidity('')">
                                <option value="">Pilih atau cari kota tujuan...</option>
                                @foreach($masterKota as $kota)
                                    <option value="{{ $kota->id }}" {{ old('kota_id') == $kota->id ? 'selected' : '' }}>
                                        {{ $kota->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    {{-- Waktu --}}
                    {{-- <div class="form-row flex gap-6 py-3">
                        <label class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            Waktu (Durasi)
                        </label>
                        <div class="flex-1">
                            <div class="flex gap-4">
                                <div class="relative flex-1">
                                    <input 
                                        name="durasi_bulan" 
                                        type="number" 
                                        min="0" 
                                        placeholder="0" 
                                        class="form-input pr-16"
                                        value="{{ old('durasi_bulan', $durasiBulan ?? '') }}">
                                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[13px] text-[#94a3b8]">
                                        bulan
                                    </span>
                                </div>
                                <div class="relative flex-1">
                                    <input 
                                        name="durasi_hari" 
                                        type="number" 
                                        min="0" 
                                        placeholder="0" 
                                        class="form-input pr-16"
                                        value="{{ old('durasi_hari', $durasiHari ?? '') }}"
                                        required>
                                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[13px] text-[#94a3b8]">
                                        hari
                                    </span>
                                </div>
                            </div>
                            <p class="mt-1.5 text-[11px] text-[#94a3b8]">
                                Isi kolom bulan jika perjalanan dinas lebih dari 30 hari.
                            </p>
                        </div>
                    </div> --}}

                    {{-- <div class="form-row flex gap-6 py-3">
                        <label for="durasi" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            Waktu
                        </label>
                        <div class="flex-1">
                            <div class="relative">
                                <input
                                    id="durasi"
                                    name="durasi"
                                    type="text"
                                    placeholder="Contoh: 35 hari atau 1 bulan 7 hari"
                                    class="form-input"
                                    value="{{ old('durasi', $durasi ?? '') }}"
                                    required
                                    oninvalid="this.setCustomValidity('Silahkan masukan waktu dinas')"
                                    oninput="this.setCustomValidity('')">
                            </div>
                            <p class="mt-1.5 text-[11px] text-[#94a3b8]">
                                Ketik bebas sesuai durasi perjalanan dinas (misal: 45 hari / 1 bulan 15 hari).
                            </p>
                        </div>
                    </div> --}}

                    <div class="form-row flex gap-6 py-3">

                        <label for="waktu" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            Waktu
                        </label>

                        <div class="flex-1">

                            <div class="relative">

                                <input
                                    id="durasi"
                                    name="durasi"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="3"
                                    placeholder="Masukkan waktu"
                                    class="form-input pr-20"
                                    required
                                    oninvalid="this.setCustomValidity('Silahkan masukan waktu dinas')"
                                    onkeydown="cekkunci(event)"
                                    oninput="this.setCustomValidity(''); validasiWaktu(this)">

                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[13px] text-[#94a3b8]">
                                    hari
                                </span>

                            </div>

                            {{-- <p class="mt-1.5 text-[11px] text-[#94a3b8]">
                                Masukkan angka 1–14 hari.
                            </p> --}}

                        </div>

                    </div>

                    {{-- Keperluan Dinas --}}
                    <div class="form-row flex gap-6 py-3" 
                        x-data="{ 
                            open: false, 
                            selected: [], 
                            selectedNames: [],
                            hasLainnya: false,
                            lainnyaText: '',
                            
                            toggleOption(id, name) {
                                let index = this.selected.indexOf(id);
                                if (index > -1) {
                                    this.selected.splice(index, 1);
                                    this.selectedNames.splice(index, 1);
                                } else {
                                    this.selected.push(id);
                                    this.selectedNames.push(name);
                                }
                            }
                        }">
                        
                        <label class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Keperluan Dinas
                        </label>

                        <div class="flex-1 space-y-2">
                            <!-- Custom Dropdown Container -->
                            <div class="relative" @click.outside="open = false">
                                
                                <!-- Hidden Inputs untuk dikirim ke Controller Laravel -->
                                <template x-for="id in selected" :key="id">
                                    <input type="hidden" name="keperluan_id[]" :value="id">
                                </template>

                                <!-- Display Box (Mirip Input Dropdown) -->
                                <div @click="open = !open" 
                                    class="min-h-[42px] w-full cursor-pointer rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 pr-10 text-[14px] text-slate-800 transition-all duration-200 focus-within:border-[#2563eb] focus-within:ring-2 focus-within:ring-[#2563eb]/20 flex flex-wrap gap-1.5 items-center">
                                    
                                    <!-- Placeholder jika belum ada yang dipilih -->
                                    <span x-show="selected.length === 0 && !hasLainnya" class="text-slate-400">
                                        Pilih Keperluan Dinas...
                                    </span>

                                    <!-- Badge Keperluan Terpilih -->
                                    <template x-for="(name, index) in selectedNames" :key="index">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            <span x-text="name"></span>
                                            <button type="button" @click.stop="toggleOption(selected[index], name)" class="text-blue-600 hover:text-blue-800">
                                                &times;
                                            </button>
                                        </span>
                                    </template>

                                    <!-- Badge jika "Lainnya" dicentang -->
                                    <span x-show="hasLainnya" class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        <span x-text="lainnyaText ? 'Lainnya: ' + lainnyaText : 'Lainnya...'"></span>
                                    </span>

                                    <!-- Panah Dropdown -->
                                    <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg class="h-4 w-4 fill-current transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Menu Dropdown dengan Checkbox -->
                                <div x-show="open" 
                                    x-transition
                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-slate-200 bg-white p-2 shadow-lg ring-1 ring-black/5"
                                    style="display: none;">
                                    
                                    <!-- Loop Master Keperluan -->
                                    @foreach($masterKeperluan as $keperluan)
                                        <label class="flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-[14px] text-slate-700 hover:bg-slate-50 transition-colors">
                                            <input type="checkbox" 
                                                value="{{ $keperluan->id }}"
                                                :checked="selected.includes({{ $keperluan->id }})"
                                                @change="toggleOption({{ $keperluan->id }}, '{{ $keperluan->name }}')"
                                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            <span>{{ $keperluan->name }}</span>
                                        </label>
                                    @endforeach

                                    <div class="my-1 border-t border-slate-100"></div>

                                    <!-- Opsi Checkbox "Lainnya" -->
                                    <label class="flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-[14px] font-medium text-amber-700 hover:bg-amber-50 transition-colors">
                                        <input type="checkbox" 
                                            x-model="hasLainnya"
                                            @change="if(hasLainnya) { open = false; $nextTick(() => $refs.inputLainnya.focus()); }"
                                            class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                        <span>Lainnya (Input Manual)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Input Text tambahan yang muncul saat "Lainnya" dicentang -->
                            <div x-show="hasLainnya" x-transition class="pt-1">
                                <input type="text" 
                                    x-ref="inputLainnya"
                                    name="keperluan_lainnya" 
                                    x-model="lainnyaText"
                                    placeholder="Ketik keperluan dinas lainnya di sini..."
                                    class="w-full rounded-lg border border-amber-300 bg-amber-50/30 px-4 py-2 text-[14px] text-slate-800 outline-none transition-all focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                            </div>
                        </div>
                    </div>

                    {{-- Transportasi (Dropdown Custom) --}}
                    <div class="form-row flex gap-6 py-3" 
                        x-data="{ 
                            open: false, 
                            selected: [], 
                            selectedNames: [],
                            hasLainnya: false,
                            lainnyaText: '',
                            
                            toggleOption(id, name) {
                                let index = this.selected.indexOf(id);
                                if (index > -1) {
                                    this.selected.splice(index, 1);
                                    this.selectedNames.splice(index, 1);
                                } else {
                                    this.selected.push(id);
                                    this.selectedNames.push(name);
                                }
                            }
                        }">
                        
                        <label class="form-label w-[180px] pt-2.5 text-[14px] font-bold text-slate-800">
                            Transportasi
                        </label>

                        <div class="flex-1 space-y-2">
                            <!-- Custom Dropdown Container -->
                            <div class="relative" @click.outside="open = false">
                                
                                <!-- Hidden Inputs untuk dikirim ke Controller Laravel -->
                                <template x-for="id in selected" :key="id">
                                    <input type="hidden" name="transport_id[]" :value="id">
                                </template>

                                <!-- Display Box (Mirip Input Dropdown) -->
                                <div @click="open = !open" 
                                    class="min-h-[42px] w-full cursor-pointer rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 pr-10 text-[14px] text-slate-800 transition-all duration-200 focus-within:border-[#2563eb] focus-within:ring-2 focus-within:ring-[#2563eb]/20 flex flex-wrap gap-1.5 items-center">
                                    
                                    <!-- Placeholder jika belum ada yang dipilih -->
                                    <span x-show="selected.length === 0 && !hasLainnya" class="text-slate-400">
                                        Pilih Transportasi...
                                    </span>

                                    <!-- Badge Keperluan Terpilih -->
                                    <template x-for="(name, index) in selectedNames" :key="index">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            <span x-text="name"></span>
                                            <button type="button" @click.stop="toggleOption(selected[index], name)" class="text-blue-600 hover:text-blue-800">
                                                &times;
                                            </button>
                                        </span>
                                    </template>

                                    <!-- Badge jika "Lainnya" dicentang -->
                                    <span x-show="hasLainnya" class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        <span x-text="lainnyaText ? 'Lainnya: ' + lainnyaText : 'Lainnya...'"></span>
                                    </span>

                                    <!-- Panah Dropdown -->
                                    <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg class="h-4 w-4 fill-current transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Menu Dropdown dengan Checkbox -->
                                <div x-show="open" 
                                    x-transition
                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-slate-200 bg-white p-2 shadow-lg ring-1 ring-black/5"
                                    style="display: none;">
                                    
                                    <!-- Loop Master Keperluan -->
                                    @foreach($masterTransport as $transport)
                                        <label class="flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-[14px] text-slate-700 hover:bg-slate-50 transition-colors">
                                            <input type="checkbox" 
                                                value="{{ $transport->id }}"
                                                :checked="selected.includes({{ $transport->id }})"
                                                @change="toggleOption({{ $transport->id }}, '{{ $transport->name }}')"
                                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            <span>{{ $transport->name }}</span>
                                        </label>
                                    @endforeach

                                    <div class="my-1 border-t border-slate-100"></div>

                                    <!-- Opsi Checkbox "Lainnya" -->
                                    <label class="flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-[14px] font-medium text-amber-700 hover:bg-amber-50 transition-colors">
                                        <input type="checkbox" 
                                            x-model="hasLainnya"
                                            @change="if(hasLainnya) { open = false; $nextTick(() => $refs.inputLainnya.focus()); }"
                                            class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                        <span>Lainnya (Input Manual)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Input Text tambahan yang muncul saat "Lainnya" dicentang -->
                            <div x-show="hasLainnya" x-transition class="pt-1">
                                <input type="text" 
                                    x-ref="inputLainnya"
                                    name="transport_lainnya" 
                                    x-model="lainnyaText"
                                    placeholder="Ketik transportasi lainnya di sini..."
                                    class="w-full rounded-lg border border-amber-300 bg-amber-50/30 px-4 py-2 text-[14px] text-slate-800 outline-none transition-all focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                            </div>
                        </div>
                    </div>

                    {{-- Tugas --}}
                    <div class="form-row flex gap-6 py-3">
                        <label for="tugas" class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            Tugas
                        </label>

                        <div class="flex min-h-[160px] flex-1 flex-col rounded-lg border border-[#e2e8f0] px-4 py-3 focus-within:border-[#2563eb]">
                            <textarea
                                id="tugas"
                                name="tugas"
                                class="form-textarea mt-1 flex-1 border-none outline-none focus:ring-0"
                                placeholder="1. Isi tugas pertama..."
                                required
                                oninvalid="this.setCustomValidity('Silahkan masukan tugas')"
                                oninput="this.setCustomValidity('')">1. </textarea>
                        </div>
                    </div>
                    {{-- <div class="form-row flex gap-6 py-3">

                        <label for="tugas"
                            class="form-label w-[180px] pt-2.5 text-[14px] font-bold">
                            Tugas
                        </label>

                        <div class="flex min-h-[160px] flex-1 flex-col rounded-lg border border-[#e2e8f0] px-4 py-3 focus-within:border-[#2563eb]">

                            <textarea
                                id="tugas"
                                name="tugas"
                                class="form-textarea mt-1 flex-1"
                                placeholder="1.&#10;2.&#10;3.&#10;4."
                                required
                                oninvalid="this.setCustomValidity('Silahkan masukan tugas')"
                                oninput="this.setCustomValidity('')"></textarea>

                        </div>

                    </div> --}}

                    {{-- =================================================
                        BUTTON
                    ================================================== --}}
                    <div class="form-actions flex justify-end gap-3 pt-6">

                        <a href="/dashboard"
                            class="rounded-lg border border-[#e2e8f0] bg-white px-5 py-2.5 text-[14px] font-semibold text-[#64748b] transition hover:bg-slate-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-[14px] font-semibold text-white transition hover:bg-[#1d4ed8]">
                            Ajukan
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

    @vite('resources/js/sppd/form.js')

@endpush