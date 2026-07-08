<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Dokumen - {{ $transaction->doc_number }}</title>
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
        }
        .data-table th {
            background-color: #f9f9f9;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 70px;
            padding-top: 5px;
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
        <h2>DOKUMEN {{ strtoupper(str_replace('_', ' ', $transaction->type)) }}</h2>
        <p>No. Dokumen: {{ $transaction->doc_number }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Tanggal</td>
            <td>: {{ $transaction->created_at->format('d F Y') }}</td>
            <td class="label">Tipe</td>
            <td>: {{ ucwords(str_replace('_', ' ', $transaction->type)) }} {{ $transaction->borrow_type ? '('.ucfirst($transaction->borrow_type).')' : '' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Pengaju</td>
            <td>: {{ $transaction->nama_pengaju }}</td>
            <td class="label">Departemen</td>
            <td>: {{ $transaction->department }}</td>
        </tr>
        <tr>
            <td class="label">No Kontak</td>
            <td>: {{ $transaction->no_wa }}</td>
            <td class="label">Status</td>
            <td>: {{ ucwords(str_replace('_', ' ', $transaction->status)) }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>No. Aset</th>
                @if($transaction->type === 'penukaran')
                <th>SN Lama (Retur)</th>
                @endif
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ucfirst($item->kategori) }}</td>
                <td>{{ $item->no_aset }}</td>
                @if($transaction->type === 'penukaran')
                <td>{{ $item->sn_lama ?? '-' }}</td>
                @endif
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
            @if($transaction->items->isEmpty())
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada barang</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Admin IT</p>
            <div class="signature-line">
                ( {{ $transaction->validator->name ?? '....................................' }} )
            </div>
        </div>
        <div class="signature-box">
            <p>Penerima / Pengaju</p>
            <div class="signature-line">
                ( {{ $transaction->nama_pengaju }} )
            </div>
        </div>
    </div>

</body>
</html>
