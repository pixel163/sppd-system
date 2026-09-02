{{-- MOBILE OVERLAY --}}
<div
    id="sidebarOverlay"
    class="fixed inset-0 z-40 hidden bg-black/30 lg:hidden"
    onclick="toggleSidebar()"
></div>

{{-- SIDEBAR --}}
<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-[260px] -translate-x-full flex-col border-r border-[#e2e8f0] bg-white px-4 py-6 transition-transform duration-300 lg:static lg:translate-x-0"
>

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

        <button
            onclick="toggleSidebar()"
            class="ml-auto flex size-8 items-center justify-center rounded-lg text-[#64748b] hover:bg-[#f8fafc] lg:hidden"
        >
            <i data-lucide="x" class="size-5"></i>
        </button>

    </div>

    {{-- Navigation --}}
    <nav class="mt-8 flex flex-col gap-1">

        {{-- Dashboard --}}
        <a
            href="/dashboard"
            class="flex w-full items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->is('dashboard') 
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}"
        >
            <i data-lucide="layout-dashboard" class="size-5"></i>

            <span
                class="text-[14px]
                    {{ request()->is('dashboard') ? 'font-semibold' : 'font-medium' }}"
            >
                Dashboard
            </span>
        </a>

        {{-- Pengajuan SPPD --}}
        <a
            href="/sppd/create"
            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->routeIs('sppd.*') 
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}"
        >
            <i data-lucide="file-plus-2" class="size-5"></i>

            <span
                class="text-[14px]
                    {{ request()->routeIs('sppd.*') ? 'font-semibold' : 'font-medium' }}"
            >
                Pengajuan SPPD
            </span>
        </a>

        {{-- Perizinan --}}
        <a
            {{-- href="/ilpd" --}}
            href="/ilpd/create"
            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->routeIs('ilpd.*')
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}"
        >
            <i data-lucide="clipboard-pen-line" class="size-5"></i>

            <span
                class="text-[14px]
                    {{ request()->routeIs('ilpd.*') ? 'font-semibold' : 'font-medium' }}"
            >
                Perizinan
            </span>
        </a>

        {{-- Riwayat --}}
        <a
            href="/riwayat"
            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->is('riwayat*') 
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}"
        >
            <i data-lucide="history" class="size-5"></i>

            <span
                class="text-[14px]
                    {{ request()->is('riwayat*') ? 'font-semibold' : 'font-medium' }}"
            >
                Riwayat Pengajuan
            </span>
        </a>

        {{-- Dokumen --}}
        <a
            href="/dokumen"
            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                {{ request()->is('dokumen*') 
                    ? 'bg-[#eff6ff] text-[#0d6efd]' 
                    : 'text-[#64748b] hover:bg-[#f8fafc]' }}"
        >
            <i data-lucide="files" class="size-5"></i>

            <span
                class="text-[14px]
                    {{ request()->is('dokumen*') ? 'font-semibold' : 'font-medium' }}"
            >
                Dokumen & Tiket
            </span>
        </a>

    </nav>

    <div class="my-4 h-px w-full bg-[#e2e8f0]"></div>

    <div class="flex-1"></div>

</aside>