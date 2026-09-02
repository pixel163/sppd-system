<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SPPD System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >
</head>

<body class="min-h-screen bg-white font-['Inter',sans-serif]">

    <main class="flex min-h-screen w-full bg-white">

        {{-- =========================
             LEFT BRAND PANEL
        ========================== --}}
        <section
            class="relative flex min-h-screen w-[450px] shrink-0 flex-col items-start justify-between overflow-hidden p-12"
        >

            {{-- Background Image + Blue Overlay --}}
            <div class="pointer-events-none absolute inset-0">

                <img
                    src="https://www.figma.com/api/mcp/asset/7f81966a-4470-489f-a5f5-79b632c40f45.png"
                    alt=""
                    class="absolute inset-0 h-full w-full object-cover"
                >

                <div class="absolute inset-0 bg-[rgba(27,83,211,0.88)]"></div>

            </div>


            {{-- Top Spacer --}}
            <div class="relative h-10 w-full"></div>


            {{-- Brand Content --}}
            <div class="relative flex w-full flex-col items-start gap-8">

                {{-- Logo --}}
                <div
                    class="flex size-16 items-center justify-center rounded-2xl bg-white shadow-[0px_4px_4px_rgba(0,0,0,0.06)]"
                >
                    <div class="flex size-8 items-center justify-center">

                        <i data-lucide="plane" class="size-[40px] text-[#0d6efd]"></i>

                    </div>
                </div>


                {{-- Brand Title --}}
                <div class="flex w-full flex-col gap-2">

                    <h1 class="text-[32px] font-extrabold leading-normal text-white">
                        SPPD System
                    </h1>

                    <p class="text-[18px] font-medium leading-normal text-[#e2e8f0]">
                        Sistem Perjalanan Dinas
                    </p>

                </div>


                {{-- Line --}}
                <div class="h-0 w-[60px]">

                    <img
                        src="https://www.figma.com/api/mcp/asset/04979a65-f498-4dd8-a305-6e50900b358d.svg"
                        alt=""
                        class="block h-full w-full"
                    >

                </div>


                {{-- Description --}}
                <p class="w-full text-[15px] font-normal leading-[1.6] text-[#e2e8f0] opacity-90">
                    Kelola pengajuan perjalanan dinas secara digital,
                    mudah, cepat, dan terstruktur.
                </p>

            </div>


            {{-- Bottom Spacer --}}
            <div class="relative h-20 w-full"></div>

        </section>


        {{-- =========================
             LOGIN AREA
        ========================== --}}
        <section
            class="flex min-w-0 flex-1 items-start justify-center px-16 pt-[110px]"
        >

            {{-- Login Form Container --}}
            <div class="w-full max-w-[422px]">

                {{-- Greeting --}}
                <div class="flex w-full flex-col gap-2">

                    <h2 class="text-[24px] font-bold leading-normal text-[#1e293b]">
                        Selamat Datang Kembali 👋
                    </h2>

                    <p class="text-[14px] font-normal leading-normal text-[#64748b]">
                        Silakan masuk untuk melanjutkan ke SPPD System
                    </p>

                </div>

                {{-- Form --}}
                <form
                    method="POST"
                    action="{{ route('login') }}"
                    class="mt-[64px] flex w-full flex-col gap-6">

                    @csrf

                    {{-- Username --}}
                    <div class="flex w-full flex-col gap-2">

                        <label
                            for="username"
                            class="text-[13px] font-semibold leading-normal text-[#1e293b]"
                        >
                            Username atau Email
                        </label>

                        <div
                            class="flex h-12 w-full items-center gap-3 rounded-xl border border-[#e2e8f0] bg-white px-4"
                        >

                            <i data-lucide="user" class="size-[18px] text-[#94a3b8]"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Masukkan username atau email"
                                class="min-w-0 flex-1 border-0 bg-transparent text-[14px] font-normal text-[#1e293b] outline-none placeholder:text-[#64748b]"
                                value="{{ old('email') }}"
                                required
                            >

                        </div>

                    </div>


                    {{-- Password --}}
                    <div class="flex w-full flex-col gap-2">

                        <label
                            for="password"
                            class="text-[13px] font-semibold leading-normal text-[#1e293b]"
                        >
                            Password
                        </label>

                        <div
                            class="flex h-12 w-full items-center gap-3 rounded-xl border border-[#e2e8f0] bg-white px-4"
                        >

                            <i data-lucide="lock-keyhole" class="size-[18px] text-[#94a3b8]"></i>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                class="min-w-0 flex-1 border-0 bg-transparent text-[14px] font-normal text-[#1e293b] outline-none placeholder:text-[#64748b]"
                                required
                            >

                        </div>

                    </div>


                    {{-- Remember + Forgot Password --}}
                    <div class="flex w-full items-center justify-between gap-4">

                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="remember"
                                class="size-[18px] appearance-none rounded-[6px] border-[1.5px] border-[#e2e8f0] bg-white"
                            >

                            <span class="text-[13px] font-normal leading-normal text-[#64748b]">
                                Ingat saya
                            </span>

                        </label>


                        <a
                            href="https://example.com/forgot"
                            target="_blank"
                            class="text-[13px] font-medium leading-normal text-[#1c61e7]"
                        >
                            Lupa password?
                        </a>

                    </div>


                    {{-- Login Button --}}
                    <button
                        type="submit"
                        class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#1c61e7] text-[15px] font-semibold text-white transition hover:bg-[#1857d1]"
                    >

                        <i data-lucide="log-in" class="size-4"></i>

                        <span>Masuk</span>

                    </button>

                </form>

            </div>

        </section>

    </main>


    {{-- =========================
         RESPONSIVE
    ========================== --}}
    <style>
        /* Tablet */
        @media (max-width: 1024px) {

            main {
                display: flex;
            }

            main > section:first-child {
                width: 380px;
                padding: 40px;
            }

            main > section:last-child {
                padding-left: 48px;
                padding-right: 40px;
            }

        }


        /* Mobile */
        @media (max-width: 767px) {

            body {
                overflow-x: hidden;
            }

            main {
                display: block;
                min-height: 100vh;
            }

            /* Brand panel tetap ada,
               tetapi dibuat lebih pendek */
            main > section:first-child {
                width: 100%;
                min-height: 300px;
                height: 300px;
                padding: 32px;
            }

            main > section:first-child > div:last-child {
                display: none;
            }

            /* Login area */
            main > section:last-child {
                width: 100%;
                min-height: auto;
                padding: 40px 24px 48px;
            }

            main > section:last-child > div {
                width: 100%;
                max-width: 422px;
                margin: 0 auto;
            }

            /* Jarak greeting ke form */
            main form {
                margin-top: 48px;
            }

        }


        /* Mobile kecil */
        @media (max-width: 480px) {

            main > section:first-child {
                min-height: 270px;
                height: 270px;
                padding: 28px 24px;
            }

            main > section:last-child {
                padding: 32px 20px 40px;
            }

            main h1 {
                font-size: 28px;
            }

            main > section:first-child p {
                font-size: 14px;
            }

            main h2 {
                font-size: 22px;
            }

        }
    </style>

</body>
</html>