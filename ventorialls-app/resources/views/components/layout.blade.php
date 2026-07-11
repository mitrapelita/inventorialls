<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Ventorialls' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] { display: none !important; }
        body.swal2-height-auto { height: 100vh !important; }
    </style>
</head>
<body class="bg-[#f4f7fb] text-slate-800 font-sans antialiased h-screen flex overflow-hidden" x-data="{ sidebarOpen: false }">
    
    <x-sidebar :active="$active ?? 'master'" />

    <main class="flex-1 flex flex-col h-full overflow-hidden relative" {{ $attributes }}>
        <x-header :title="$headerTitle ?? 'Ventorialls'" />
        
        <div class="flex-1 overflow-auto p-4 md:p-8">
            {{ $slot }}
        </div>
    </main>

    <!-- Page Specific Scripts (Alpine logic) -->
    @stack('scripts')

    <!-- Global Alerts (Success & Errors) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    position: 'center'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    html: '{!! implode("<br>", $errors->all()) !!}',
                    confirmButtonColor: '#1d4ed8'
                });
            @endif
        });
    </script>
</body>
</html>
