<!DOCTYPE html>
<html lang="id">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SPPD System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet">

    <!-- Tom Select CSS & JS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script> --}}
    
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-[#f8fafc] font-['Inter',sans-serif] text-[#1e293b]">

    <div class="flex min-h-screen">

        @include('components.sidebar')

        <main class="min-w-0 flex-1">

            @include('components.navbar')

            @yield('content')

        </main>

    </div>

    @stack('scripts')

<script>
    lucide.createIcons();
</script>

{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        new TomSelect("#kota", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "Ketik untuk mencari kota..."
        });
    });
</script> --}}

</body>
</html>