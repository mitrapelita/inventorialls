import re

content = open(r'resources\views\public\validasi-fisik.blade.php', 'r', encoding='utf-8').read()

start_idx = content.find('<div x-data="validasiApp()"')
end_idx = content.find('</script>', start_idx) + 9

body = content[start_idx:end_idx]

final_html = f'''@extends('user.layout')

@section('content')
<div class="pt-2 sm:pt-6 pb-10 flex flex-col items-center px-4">
    
    @if(session('success'))
        <div class="mb-6 max-w-4xl w-full bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{{{ session('success') }}}}</p>
            </div>
        </div>
    @endif

    <div class="mb-6 text-center w-full max-w-4xl">
        <h1 class="text-2xl font-bold text-slate-800">Cek Aset / Validasi Fisik</h1>
        <p class="text-slate-500 text-sm mt-1">Masukkan nomor aset untuk memeriksa data di sistem.</p>
    </div>

    {body}
</div>
@endsection
'''

with open(r'resources\views\user\cek-aset.blade.php', 'w', encoding='utf-8') as f:
    f.write(final_html)

print('Created user/cek-aset.blade.php')
