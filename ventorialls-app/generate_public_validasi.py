import re

content = open(r'resources\views\admin\validasi.blade.php', 'r', encoding='utf-8').read()

tab2_start = content.find('<!-- Tab 2: Validasi Fisik & Tools -->')
tab2_end = content.find('<!-- MODAL LENGKAPI DATA -->')
tab2_html = content[tab2_start:tab2_end]

modal_start = content.find('<!-- MODAL LENGKAPI DATA -->')
modal_end = content.find('<!-- DETAIL VALIDASI MODAL -->')
modal_html = content[modal_start:modal_end]

script_start = content.find('Alpine.data(\'validasiApp\'')
script_end = content.find('</script>', script_start)
script_js = content[script_start:script_end]

# Update form action in modal
modal_html = modal_html.replace("route('transaksi.serah-terima')", "route('public.validasi.store')")

final_html = f'''<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Fisik & Tools - Ventorialls</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {{ font-family: 'Plus Jakarta Sans', sans-serif; }}
        [x-cloak] {{ display: none !important; }}
    </style>
</head>
<body class="bg-[#f4f7fb] text-slate-800 antialiased min-h-screen flex flex-col items-center justify-center py-10 px-4">
    
    @if(session('success'))
        <div class="mb-6 max-w-4xl w-full bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="mb-8 text-center w-full max-w-4xl">
        <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-sm mb-4">
            <img src="{{ asset('image/logo-ventorialls-2.png') }}" alt="Ventorialls Logo" class="h-10 w-auto object-contain">
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Pengecekan Inventaris</h1>
        <p class="text-slate-500 text-sm mt-1">Sistem Validasi Fisik & Tools Publik</p>
    </div>

    <div x-data="validasiApp()" class="w-full max-w-4xl">
        <div style="display:block">
            {tab2_html}
        </div>
        {modal_html}
    </div>

    <script>
        document.addEventListener('alpine:init', () => {{
            {script_js}
        }});
        
        document.addEventListener('DOMContentLoaded', () => {{
            if(window.lucide) window.lucide.createIcons();
        }});
    </script>
</body>
</html>
'''

# Remove activeTab constraint so we always show the tab
final_html = final_html.replace('x-show="activeTab === \'fisik\'"', '')

with open(r'resources\views\public\validasi-fisik.blade.php', 'w', encoding='utf-8') as f:
    f.write(final_html)

print('Successfully written standalone validasi-fisik.blade.php')
