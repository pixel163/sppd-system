<header
    class="flex min-h-[72px] flex-col gap-5 border-b border-[#e2e8f0] bg-white px-5 py-4 sm:px-7 lg:flex-row lg:items-center lg:justify-between lg:px-8"
>

    {{-- LEFT --}}
    <div class="flex items-start gap-3">

        {{-- Mobile Menu --}}
        <button
            type="button"
            onclick="toggleSidebar()"
            class="mt-1 flex size-9 shrink-0 items-center justify-center rounded-lg border border-[#e2e8f0] bg-white lg:hidden"
        >
            <i data-lucide="menu" class="size-5 text-[#64748b]"></i>
        </button>

        {{-- Welcome hanya Dashboard --}}
        @if (request()->is('dashboard'))
            <div class="flex flex-col gap-1">

                <h1
                    id="welcomeText"
                    class="text-[20px] font-bold sm:text-[24px]"
                >
                    Selamat datang, {{ auth()->user()->name }} 👋
                </h1>

                <p class="text-[12px] text-[#64748b] sm:text-[13px]">
                    Kelola pengajuan SPPD dan perizinan perjalanan dinas Anda dengan mudah.
                </p>

            </div>
        @endif

    </div>

    {{-- RIGHT NAVIGATION --}}
    <div class="flex items-center justify-between gap-4 sm:justify-end">

        {{-- Notification --}}
        <button
            type="button"
            onclick="showNotification()"
            class="flex size-10 items-center justify-center rounded-xl transition hover:bg-[#f8fafc]"
            aria-label="Notifikasi"
        >
            <i
                data-lucide="bell"
                class="size-5 text-[#64748b]"
            ></i>
        </button>

        {{-- Profile --}}
        <div class="relative">

            <button
                type="button"
                id="profileToggle"
                class="flex items-center gap-[10px] rounded-xl px-2 py-1.5 transition hover:bg-[#f8fafc]"
                aria-expanded="false"
            >

                <div
                    class="flex size-9 items-center justify-center rounded-full bg-[#eff6ff]"
                >
                    <i
                        data-lucide="user"
                        class="size-5 text-[#0d6efd]"
                    ></i>
                </div>

                <div class="hidden flex-col gap-px text-left sm:flex">

                    <span
                        id="profileMenuName"
                        class="text-[13px] font-semibold"
                    >
                        {{ auth()->user()->name }}
                    </span>

                    <span
                        id="profileMenuRole"
                        class="text-[11px] text-[#64748b]"
                    >
                        {{ auth()->user()->jabatan->name }}
                    </span>

                </div>

                <i
                    data-lucide="chevron-down"
                    class="hidden size-4 text-[#94a3b8] sm:block"
                ></i>

            </button>

            {{-- Profile Dropdown --}}
            <div
                id="profileMenu"
                class="absolute right-0 top-12 z-50 hidden w-52 rounded-xl border border-[#e2e8f0] bg-white p-2 shadow-lg"
            >

                <div class="border-b border-[#e2e8f0] px-3 py-2">

                    <p
                        id="profileMenuName"
                        class="text-xs font-semibold"
                    >
                        {{ auth()->user()->name }}
                    </p>

                    <p
                        id="profileMenuRole"
                        class="mt-1 text-[11px] text-[#64748b]"
                    >
                        {{ auth()->user()->jabatan->name }}
                    </p>

                </div>

                <a
                    href="#"
                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-[#475569] hover:bg-[#f8fafc]"
                >
                    <i data-lucide="user" class="size-4"></i>
                    Profile
                </a>

                <div class="my-1 h-px bg-[#e2e8f0]"></div>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50"
                    >
                        <i data-lucide="log-out" class="size-4"></i>
                        Logout
                    </button>

                </form>

            </div>

        </div>

        {{-- Create SPPD hanya Dashboard --}}
        @if (request()->is('dashboard'))
            <a
                href="/sppd/create"
                class="flex items-center gap-2 rounded-[10px] bg-[#0d6efd] px-4 py-[10px] text-[13px] font-semibold text-white transition hover:bg-[#0958c9]"
            >
                <i data-lucide="plus" class="size-4"></i>
                <span>Buat SPPD</span>
            </a>
        @endif

    </div>

</header>