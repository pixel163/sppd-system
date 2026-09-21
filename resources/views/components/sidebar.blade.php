{{-- MOBILE OVERLAY --}}
<div
    id="sidebarOverlay"
    class="fixed inset-0 z-40 hidden bg-black/30 lg:hidden"
    onclick="toggleSidebar()">
</div>

{{-- SIDEBAR --}}
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-[260px] -translate-x-full flex-col border-r border-[#e2e8f0] bg-white px-4 py-6 transition-transform duration-300 lg:static lg:translate-x-0">

    {{-- Brand --}}
    <div class="flex items-center gap-3 pl-3">

        <div class="flex size-9 items-center justify-center rounded-[10px] bg-[#0d6efd]">
            <i data-lucide="plane" class="size-[18px] text-white"></i>
        </div>

        <div class="flex flex-col gap-px">
            <span class="text-[16px] font-bold">
                SPPD System
            </span>

            <span class="text-[11px] text-[#64748b]">
                Sistem Perjalanan Dinas
            </span>
        </div>

        <button onclick="toggleSidebar()"
            class="ml-auto flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f8fafc] lg:hidden">
            <i data-lucide="x" class="size-5"></i>
        </button>

    </div>

    {{-- Navigation --}}
    <nav class="mt-8 flex flex-col gap-1">

        {{-- Dashboard --}}
        <a href="/dashboard"
            class="flex w-full items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->is('dashboard') 
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}">
            <i data-lucide="layout-dashboard" class="size-5"></i>

            <span class="text-[14px]
                    {{ request()->is('dashboard') ? 'font-semibold' : 'font-medium' }}">
                Dashboard
            </span>
        </a>

        {{-- Pengajuan SPPD --}}
        <a href="/sppd/create"
            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->routeIs('sppd.*') 
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}">
            <i data-lucide="file-plus-2" class="size-5"></i>

            <span class="text-[14px]
                    {{ request()->routeIs('sppd.*') ? 'font-semibold' : 'font-medium' }}">
                Pengajuan SPPD
            </span>
        </a>

        {{-- Perizinan --}}
        <a
            href="/ilpd/create"
            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->routeIs('ilpd.*')
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}">
            <i data-lucide="clipboard-pen-line" class="size-5"></i>

            <span class="text-[14px]
                    {{ request()->routeIs('ilpd.*') ? 'font-semibold' : 'font-medium' }}">
                Perizinan
            </span>
        </a>

        {{-- Riwayat --}}
        <a href="/riwayat"
            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->is('riwayat*') 
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}">
            <i data-lucide="history" class="size-5"></i>

            <span class="text-[14px]
                    {{ request()->is('riwayat*') ? 'font-semibold' : 'font-medium' }}">
                Riwayat Pengajuan
            </span>
        </a>

        {{-- Dokumen --}}
        <a href="/dokumen"
            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->is('dokumen*') 
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}">
            <i data-lucide="files" class="size-5"></i>

            <span class="text-[14px]
                    {{ request()->is('dokumen*') ? 'font-semibold' : 'font-medium' }}">
                Dokumen & Tiket
            </span>
        </a>

    </nav>

    <div class="my-4 h-px w-full bg-[#e2e8f0]"></div>

    <nav class="flex flex-col gap-1">

        {{-- KONDISI 1: Hanya muncul jika user yang login memiliki role / departemen HRGA --}}
        {{-- @if(auth()->check() && (auth()->user()->jabatan()->name === 'HRGA' || auth()->user()->jabatan()->name === 'HRGA')) --}}
        @if(optional(auth()->user()->jabatan)->name === 'HRGA')
        
            {{-- Pembungkus Menu Master Data dengan Alpine.js --}}
            <div x-data="{ open: false }" class="flex flex-col">
                
                {{-- Tombol Utama / Induk (Master Data) --}}
                <button @click="open = !open" 
                        type="button" 
                        class="flex items-center justify-between w-full rounded-xl px-4 py-3 text-[#64748b] hover:bg-slate-100 transition-colors">
                    
                    <div class="flex items-center gap-3">
                        {{-- Lucide Icon: Database --}}
                        <i data-lucide="database" class="w-5 h-5 text-[#64748b]"></i>
                        <span class="text-[14px] font-medium">Master Data</span>
                    </div>

                    {{-- Lucide Icon: Panah Chevron (Berputar otomatis saat diklik) --}}
                    <i data-lucide="chevron-down" 
                    class="w-4 h-4 text-[#64748b] transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Sub-Menu (Golongan, Kota, Role, dll.) --}}
                <div x-show="open" 
                    x-collapse
                    x-cloak
                    class="flex flex-col gap-1 pl-9 mt-1">

                    <a href="{{ route('master.department.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2 hover:bg-slate-100 transition-colors">
                        <i data-lucide="building-2" class="w-4 h-4 text-[#64748b]"></i>
                        <span class="text-[13px] font-medium text-[#64748b]">Departemen</span>
                    </a>

                    <a href="{{ route('master.golongan.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2 hover:bg-slate-100 transition-colors">
                        <i data-lucide="layers" class="w-4 h-4 text-[#64748b]"></i>
                        <span class="text-[13px] font-medium text-[#64748b]">Golongan</span>
                    </a>

                    <a href="{{ route('master.jabatan.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2 hover:bg-slate-100 transition-colors">
                        <i data-lucide="briefcase" class="w-4 h-4 text-[#64748b]"></i>
                        <span class="text-[13px] font-medium text-[#64748b]">Jabatan</span>
                    </a>

                    <a href="{{ route('master.keperluan.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2 hover:bg-slate-100 transition-colors">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#64748b]"></i>
                        <span class="text-[13px] font-medium text-[#64748b]">Keperluan</span>
                    </a>

                    <a href="{{ route('master.kota.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2 hover:bg-slate-100 transition-colors">
                        <i data-lucide="map-pin" class="w-4 h-4 text-[#64748b]"></i>
                        <span class="text-[13px] font-medium text-[#64748b]">Kota</span>
                    </a>

                    <a href="{{ route('master.kotakategori.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2 hover:bg-slate-100 transition-colors">
                        <i data-lucide="tags" class="w-4 h-4 text-[#64748b]"></i>
                        <span class="text-[13px] font-medium text-[#64748b]">Kota Kategori</span>
                    </a>

                    <a href="{{ route('master.role.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2 hover:bg-slate-100 transition-colors">
                        <i data-lucide="user-check" class="w-4 h-4 text-[#64748b]"></i>
                        <span class="text-[13px] font-medium text-[#64748b]">Role</span>
                    </a>

                    <a href="{{ route('master.tarif.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2 hover:bg-slate-100 transition-colors">
                        <i data-lucide="receipt" class="w-4 h-4 text-[#64748b]"></i>
                        <span class="text-[13px] font-medium text-[#64748b]">Tarif</span>
                    </a>

                    <a href="{{ route('master.transport.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2 hover:bg-slate-100 transition-colors">
                        <i data-lucide="bus" class="w-4 h-4 text-[#64748b]"></i>
                        <span class="text-[13px] font-medium text-[#64748b]">Transportasi</span>
                    </a>

                </div>
            </div>

        @endif

    </nav>

    <div class="flex-1"></div>

</aside>