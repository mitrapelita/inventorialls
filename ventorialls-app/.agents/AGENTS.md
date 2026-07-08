# Project Rules

## Documentation
- Whenever the user prompts or explains a new web flow (alur kerja web), immediately document and append it to `docs/flowweb.md` to ensure the flow documentation is always up to date.

## Alpine.js Conventions
- Saat menggunakan `x-show` untuk toggle tab/panel/modal, SELALU gunakan `x-cloak` bukan `style="display:none"`.
- Pastikan CSS `[x-cloak] { display: none !important; }` sudah ada di layout utama (`resources/views/components/layout.blade.php`) sebelum menggunakan `x-cloak`.
- Jangan gabungkan `x-show` dengan `style="display: none;"` secara bersamaan — ini akan bertabrakan dan menyebabkan elemen tidak bisa ditampilkan/disembunyikan dengan benar oleh Alpine.
