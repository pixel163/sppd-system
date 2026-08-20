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

    <main class="relative min-h-screen w-full overflow-hidden bg-white">

        {{-- =========================
             LEFT BRAND PANEL
        ========================== --}}
        <section
            class="absolute left-0 top-0 flex h-[700px] w-[450px] flex-col items-start justify-between p-12"
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

                        <img
                            src="https://www.figma.com/api/mcp/asset/a2d8e595-bc4f-4210-9d83-bfbe2d658416.svg"
                            alt=""
                            class="size-8"
                        >

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
             LOGIN FORM
        ========================== --}}
        <section
            class="absolute left-[545px] top-[138px] w-[422px]"
        >

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
            {{-- <form class="mt-[90px] flex w-full flex-col gap-6"> --}}
            <form
                method="POST"
                action="{{ route('login') }}"
                class="mt-[90px] flex w-full flex-col gap-6">
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

                        <img
                            src="https://www.figma.com/api/mcp/asset/6e82b948-392a-4ada-9517-90d24a93e27a.svg"
                            alt=""
                            class="size-[18px]"
                        >

                        {{-- <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Masukkan username atau email"
                            class="min-w-0 flex-1 border-0 bg-transparent text-[14px] font-normal text-[#1e293b] outline-none placeholder:text-[#64748b]"
                        > --}}
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

                        <img
                            src="https://www.figma.com/api/mcp/asset/08b9c8da-6652-4777-a7c4-04c50670b85f.svg"
                            alt=""
                            class="size-[18px]"
                        >

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            class="min-w-0 flex-1 border-0 bg-transparent text-[14px] font-normal text-[#1e293b] outline-none placeholder:text-[#64748b]"
                            required
                        >

                        <button
                            type="button"
                            class="flex size-[18px] items-center justify-center"
                        >
                            <img
                                src="https://www.figma.com/api/mcp/asset/0fc4929a-fe34-44ee-a593-e614102a32ec.svg"
                                alt="Tampilkan password"
                                class="size-[18px]"
                            >
                        </button>

                    </div>

                </div>


                {{-- Remember + Forgot Password --}}
                <div class="flex w-full items-center justify-between">

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
                {{-- <a
                    href="/dashboard"
                    class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#1c61e7] text-[15px] font-semibold text-white transition hover:bg-[#1857d1]"
                >

                    <img
                        src="https://www.figma.com/api/mcp/asset/29686dc9-f298-4b00-a8b0-cd07526e3bbc.svg"
                        alt=""
                        class="size-4"
                    >

                    <span>Masuk</span>

                </a> --}}
                <button
                    type="submit"
                    class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#1c61e7] text-[15px] font-semibold text-white transition hover:bg-[#1857d1]"
                >
                    <img
                        src="https://www.figma.com/api/mcp/asset/29686dc9-f298-4b00-a8b0-cd07526e3bbc.svg"
                        alt=""
                        class="size-4"
                    >

                    <span>Masuk</span>
                </button>

            </form>

        </section>

    </main>

</body>
</html>