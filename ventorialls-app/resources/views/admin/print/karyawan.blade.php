<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil & Tanggungan Aset - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table td.label {
            font-weight: bold;
            width: 150px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f9f9f9;
        }
        .qr-cell {
            text-align: center;
            width: 90px;
        }
        .qr-cell img {
            width: 70px;
            height: 70px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; cursor: pointer; background: #1d4ed8; color: white; border: none; border-radius: 4px;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 8px 16px; cursor: pointer; background: #64748b; color: white; border: none; border-radius: 4px;">Tutup</button>
    </div>

    <div class="header">
        <h2>DOKUMEN PROFIL & TANGGUNGAN ASET</h2>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>: {{ $user->name }}</td>
            <td class="label">Departemen</td>
            <td>: {{ $user->department }}</td>
        </tr>
        <tr>
            <td class="label">ID Karyawan</td>
            <td>: {{ $user->id_karyawan ?? '-' }}</td>
            <td class="label">Posisi</td>
            <td>: {{ $user->posisi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No Kontak</td>
            <td>: {{ $user->kontak ?? '-' }}</td>
            <td class="label">Status Kerja</td>
            <td>: {{ $user->status_kerja }}</td>
        </tr>
    </table>

    <h3 style="margin-bottom: 10px;">Daftar Aset (Status: Aktif)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Merek</th>
                <th>No. Aset (SN)</th>
                <th>Kode QR</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ucfirst($item->jenis) }}</td>
                <td>{{ $item->merk ?? '-' }}</td>
                <td style="font-family: monospace; font-size: 1.1em;">{{ $item->sn }}</td>
                <td class="qr-cell">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($item->sn) }}" alt="QR Code {{ $item->sn }}">
                </td>
            </tr>
            @endforeach
            @if($items->isEmpty())
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada aset yang sedang menjadi tanggungan karyawan ini.</td>
            </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
