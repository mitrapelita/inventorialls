<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak BAST Bawa Pulang - {{ $user->name }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            color: #000;
            margin: 0;
            padding: 0;
            font-size: 11.5pt;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 20pt;
            color: #2F5496;
            font-weight: bold;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 9.5pt;
            color: #333;
        }
        .title-section {
            text-align: center;
            margin-bottom: 25px;
        }
        .title-section h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            color: #000;
        }
        .title-section h3 {
            margin: 5px 0 0 0;
            font-size: 12pt;
            font-weight: bold;
            color: #2F5496;
        }
        .intro-text {
            margin-bottom: 15px;
            text-align: justify;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #000;
        }
        .data-list {
            margin-left: 0;
            margin-bottom: 15px;
        }
        .data-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-list td {
            padding: 3px 0;
            vertical-align: top;
        }
        .data-list td.label {
            width: 140px;
        }
        .data-list td.colon {
            width: 15px;
            text-align: center;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            font-size: 10pt;
        }
        .data-table th {
            background-color: transparent !important;
            color: #2F5496 !important;
            font-weight: bold;
            text-align: center;
            border-color: #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .points-list {
            margin-bottom: 20px;
            padding-left: 20px;
        }
        .points-list > li {
            margin-bottom: 12px;
            text-align: justify;
            list-style-position: outside;
        }
        .points-list li::marker {
            font-weight: bold;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 18%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 10.5pt;
        }
        .signature-title {
            font-size: 9.5pt;
            color: #555;
            margin-top: 2px;
        }
        .page-break {
            page-break-before: always;
        }
        @media print {
            body {
                width: 210mm;
                height: 297mm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; padding: 10px; background: #f1f5f9; border-bottom: 1px solid #cbd5e1;">
        <button onclick="window.print()" style="padding: 8px 16px; cursor: pointer; background: #2F5496; color: white; border: none; border-radius: 4px; font-weight: bold; font-family: Arial, sans-serif;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 8px 16px; cursor: pointer; background: #64748b; color: white; border: none; border-radius: 4px; font-weight: bold; font-family: Arial, sans-serif; margin-left: 8px;">Tutup</button>
    </div>

    <!-- HALAMAN 1 -->
    <div class="header">
        <h1>PT MITRA PELITA TERANGI BANGSA</h1>
        <p>Jl. Bintaran No. 6, Wirogunan, Kec. Mergangsan, Kota Yogyakarta, DIY 55151</p>
    </div>

    <div class="title-section">
        <h2>BERITA ACARA PEMINJAMAN INVENTARIS KERJA</h2>
        <h3>HAK BAWA PULANG</h3>
    </div>

    <div class="intro-text">
        Pada hari ini, <strong>{{ $latestTx ? $latestTx->created_at->translatedFormat('l, d F Y') : now()->translatedFormat('l, d F Y') }}</strong> telah dilakukan serah terima inventaris kerja antara :
    </div>

    <div class="section-title">PIHAK PERTAMA</div>
    <div class="data-list">
        <table>
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td><strong>{{ auth()->user()->name ?? '......................' }}</strong></td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="colon">:</td>
                <td><strong>IT Inventory</strong></td>
            </tr>
            <tr>
                <td class="label">Departemen</td>
                <td class="colon">:</td>
                <td><strong>IT</strong></td>
            </tr>
        </table>
        <p style="margin: 6px 0 0 0;">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong> mewakili atas nama Perusahaan</p>
    </div>

    <div class="section-title">PIHAK KEDUA</div>
    <div class="data-list">
        <table>
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td><strong>{{ $user->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Team Leader</td>
                <td class="colon">:</td>
                <td><strong>{{ $user->nama_tl ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="label">Departemen</td>
                <td class="colon">:</td>
                <td><strong>{{ $user->department }}</strong></td>
            </tr>
            <tr>
                <td class="label">No. KTP</td>
                <td class="colon">:</td>
                <td><strong>{{ $user->no_ktp ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="label">Alamat KTP</td>
                <td class="colon">:</td>
                <td><strong>{{ $user->alamat_ktp ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="label">No. HP</td>
                <td class="colon">:</td>
                <td><strong>{{ $user->kontak ?? '-' }}</strong></td>
            </tr>
        </table>
        <p style="margin: 6px 0 0 0;">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong></p>
    </div>

    <div class="intro-text" style="margin-top: 20px; font-weight: bold;">
        Dengan ini menyatakan bahwa:
    </div>

    <ol class="points-list" style="margin-top: 5px;">
        <li>
            <strong>PIHAK PERTAMA</strong> menyerahkan fasilitas kerja berupa Asset Laptop, Charger, dll kepada anggota tim <strong>PIHAK KEDUA</strong> secara kolektif dengan rincian terlampir :
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Nama Aset</th>
                        <th style="width: 20%;">No Aset</th>
                        <th style="width: 10%;">Kondisi</th>
                        <th style="width: 10%;">Periode Mulai</th>
                        <th style="width: 10%;">TTD Periode Mulai</th>
                        <th style="width: 10%;">Periode Berakhir</th>
                        <th style="width: 10%;">Ttd Periode Berakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ ucfirst($item->jenis) }} {{ $item->merk }}</td>
                        <td style="font-family: monospace;">{{ $item->sn }}</td>
                        <td style="text-align: center;">{{ $item->kondisi }}</td>
                        <td style="text-align: center;">{{ $latestTx ? $latestTx->created_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                    @if($items->isEmpty())
                    <tr>
                        <td colspan="8" style="text-align: center; font-style: italic;">Tidak ada barang yang memiliki Hak Bawa Pulang.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </li>
    </ol>

    <!-- BREAK HALAMAN 2 (Sesuai dokumen template) -->
    <div class="page-break"></div>

    <ol class="points-list" start="2" style="margin-top: 20px;">
        <li>Peminjaman fasilitas kerja ini diberikan kepada anggota tim <strong>PIHAK KEDUA</strong> yang mengalami underperform pada tanggal tersebut di atas, sehingga diwajibkan untuk membawa pulang laptop untuk membantu memaksimalkan kinerjanya.</li>
        <li>
            Selama masa peminjaman, seluruh anggota tim <strong>PIHAK KEDUA</strong> wajib:
            <ul style="list-style-type: disc; padding-left: 20px; margin-top: 5px;">
                <li style="margin-bottom: 4px;">Menggunakan Asset Laptop, Charger, dll hanya untuk kepentingan pekerjaan;</li>
                <li style="margin-bottom: 4px;">Menjaga dan merawat fasilitas kerja dengan baik;</li>
                <li style="margin-bottom: 4px;">Tidak memindahtangankan asset kepada pihak lain;</li>
                <li style="margin-bottom: 4px;">Bertanggung jawab penuh atas kehilangan atau kerusakan akibat kelalaian masing-masing pengguna;</li>
                <li style="margin-bottom: 4px;">Mengembalikan aset sesuai jadwal yang ditentukan atau setelah pekerjaan selesai;</li>
            </ul>
        </li>
        <li>Sebagai jaminan peminjaman, masing-masing agent dan team leader menyerahkan KTP asli kepada Perusahaan selama masa peminjaman inventaris kerja.</li>
        <li>Apabila terjadi kehilangan atau kerusakan akibat kelalaian salah satu anggota tim, maka tanggung jawab sepenuhnya dibebankan kepada agent yang bersangkutan, team leader yang bersangkutan, dan SPV yang bersangkutan, senilai inventaris yang hilang ataupun senilai biaya kerusakan yang terjadi, sesuai dengan ketentuan Perusahaan.</li>
        <li>
            Team Leader juga bertanggung jawab untuk :
            <ul style="list-style-type: disc; padding-left: 20px; margin-top: 5px;">
                <li style="margin-bottom: 4px;">Melakukan pendataan harian agent yang berhak meminjam inventaris kerja;</li>
                <li style="margin-bottom: 4px;">Memastikan seluruh fasilitas kerja dikembalikan lengkap dan dalam kondisi baik.</li>
            </ul>
        </li>
    </ol>

    <div class="intro-text" style="margin-top: 25px;">
        Demikian Berita Acara Serah Terima ini dibuat secara kolektif dan disepakati bersama untuk dipergunakan sebagaimana mestinya.
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p><strong>Pihak Pertama,</strong></p>
            <div class="signature-line">
                {{ auth()->user()->name ?? '......................' }}
            </div>
            <div class="signature-title">IT Inventory</div>
        </div>
        <div class="signature-box">
            <p><strong>Pihak Kedua,</strong></p>
            <div class="signature-line">
                {{ $user->name }}
            </div>
            <div class="signature-title">Karyawan</div>
        </div>
        <div class="signature-box">
            <p><strong>Team Leader</strong></p>
            <div class="signature-line">
                {{ $user->nama_tl ?? '......................' }}
            </div>
            <div class="signature-title">&nbsp;</div>
        </div>
        <div class="signature-box">
            <p><strong>SPV</strong></p>
            <div class="signature-line">
                {{ $latestTx->spv_name ?? '......................' }}
            </div>
            <div class="signature-title">&nbsp;</div>
        </div>
        <div class="signature-box">
            <p><strong>HRD</strong></p>
            <div class="signature-line">
                {{ $latestTx->hrd_name ?? '......................' }}
            </div>
            <div class="signature-title">&nbsp;</div>
        </div>
    </div>

</body>
</html>
