<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak BAST Barang Keluar - {{ $transaction->doc_number }}</title>
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
            font-size: 11pt;
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
            justify-content: space-around;
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 35%;
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
        <p>Jl. Bintaran No. 6, Wirogunan, Kec. Mergangsan,<br>Kota Yogyakarta, DIY 55151</p>
    </div>

    <div class="title-section">
        <h2>BERITA ACARA BARANG KELUAR</h2>
        <h3>Return Vendor</h3>
    </div>

    <div class="intro-text">
        Pada hari ini {{ $transaction->created_at->translatedFormat('l, d F Y') }} telah dilakukan pengiriman inventaris kerja, dengan data sebagai berikut :
    </div>

    <ol class="points-list" style="margin-top: 5px;">
        <li>
            <strong>Alamat Pengirim</strong><br>
            PT. Mitra Pelita Terangi Bangsa (Fiona - 085263721629), Jl. Bintaran No. 6, Wirogunan, Kec. Mergangsan, Kota Yogyakarta, DIY, Kode Pos 55151
        </li>
        <li style="margin-top: 15px;">
            <strong>Alamat Tujuan</strong><br>
            {{ $transaction->alamat_tujuan ?: '(Belum diisi alamat tujuannya)' }}
        </li>
        <li style="margin-top: 15px;">
            <strong>Data Inventaris</strong>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Nama Aset</th>
                        <th style="width: 25%;">ID Aset</th>
                        <th style="width: 10%;">Jumlah</th>
                        <th style="width: 35%;">Kerusakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ ucfirst($item->kategori) }} {{ $item->inventory ? $item->inventory->merk : '' }}</td>
                        <td style="text-align: center;">{{ $item->no_aset ?: '-' }}</td>
                        <td style="text-align: center;">{{ $item->jumlah ?? 1 }}</td>
                        <td>{{ $item->keterangan ?: 'Rusak' }}</td>
                    </tr>
                    @endforeach
                    @if($transaction->items->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center; font-style: italic;">Tidak ada barang.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </li>
    </ol>

    <div class="signature-section" style="margin-top: 40px; justify-content: space-between; padding: 0 20px;">
        <div class="signature-box" style="text-align: left; width: 35%;">
            <p style="margin: 0;">Mengetahui,</p>
            <p style="margin: 5px 0 70px 0;">IT Inventory</p>
            <p style="margin: 0;"><strong>( {{ auth()->user()->name ?? '..................................' }} )</strong></p>
        </div>
        <div class="signature-box" style="text-align: left; width: 35%;">
            <p style="margin: 0;">Mengetahui,</p>
            <p style="margin: 5px 0 70px 0;">SPV Information Technology</p>
            <p style="margin: 0;"><strong>(Sambayu Kris Antoni)</strong></p>
        </div>
    </div>

</body>
</html>
