<!DOCTYPE html>
<html lang="id">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SPPD System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-[#f8fafc] font-['Inter',sans-serif] text-[#1e293b]">

    <div class="flex min-h-screen">

        @include('components.sidebar')

        <main class="min-w-0 flex-1">

            @include('components.navbar')

            @if(session('warning'))
                <div style="padding: 12px; background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 6px; margin-bottom: 15px;">
                    ⚠️ {{ session('warning') }}
                </div>
            @endif

            @if(session('success'))
                <div style="padding: 12px; background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 6px; margin-bottom: 15px;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @yield('content')

        </main>

    </div>

    @stack('scripts')

<script>
    lucide.createIcons();
</script>

</body>
</html>