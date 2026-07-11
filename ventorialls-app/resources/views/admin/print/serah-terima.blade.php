<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak BAST Onboarding - {{ $transaction->nama_pengaju }}</title>
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
            justify-content: space-between;
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 28%;
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
        <h2>BERITA ACARA SERAH TERIMA</h2>
        <h3>INVENTARIS PERUSAHAAN</h3>
    </div>

    <div class="intro-text">
        Pada hari ini, <strong>{{ $transaction->created_at->translatedFormat('l') }}</strong> tanggal <strong>{{ $transaction->created_at->format('d') }}</strong> bulan <strong>{{ $transaction->created_at->translatedFormat('F') }}</strong> tahun <strong>{{ $transaction->created_at->format('Y') }}</strong> telah dilakukan serah terima untuk pengembalian inventaris kerja antara:
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
                <td class="label">Nama Tim</td>
                <td class="colon">:</td>
                <td><strong>{{ $transaction->nama_pengaju }}</strong></td>
            </tr>
            <tr>
                <td class="label">Team Leader</td>
                <td class="colon">:</td>
                <td><strong>{{ $user ? $user->nama_tl : '......................' }}</strong></td>
            </tr>
            <tr>
                <td class="label">Departemen</td>
                <td class="colon">:</td>
                <td><strong>{{ $transaction->department }}</strong></td>
            </tr>
        </table>
        <p style="margin: 6px 0 0 0;">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong></p>
    </div>

    <div class="intro-text" style="margin-top: 20px; font-weight: bold;">
        Dengan ini menyatakan bahwa:
    </div>

    <ol class="points-list" style="margin-top: 5px;">
        <li>
            <strong>PIHAK PERTAMA</strong> menyerahkan kepada <strong>PIHAK KEDUA</strong> berupa inventaris kerja, meliputi namun tidak terbatas pada:
            <div style="margin-top: 5px; margin-left: 15px; line-height: 1.6;">
                a) Laptop<br>
                b) Charger<br>
                c) Headshet<br>
                d) Mouse / perangkat tambahan lainnya (jika ada)
            </div>
            <p style="margin-top: 10px; margin-bottom: 5px;">Dengan rincian disebutkan dalam tabel BAST berikut;</p>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 35%;">Nama Asset</th>
                        <th style="width: 45%;">No Aset</th>
                        <th style="width: 15%;">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ ucfirst($item->kategori) }}</td>
                        <td style="font-family: monospace;">{{ $item->no_aset }}</td>
                        <td style="text-align: center;">Baik</td>
                    </tr>
                    @endforeach
                    @if($transaction->items->isEmpty())
                    <tr>
                        <td colspan="4" style="text-align: center; font-style: italic;">Tidak ada barang.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </li>
        <li style="margin-top: 15px;">
            Inventaris kerja dipergunakan oleh <strong>PIHAK KEDUA</strong> untuk menunjang pelaksanaan pekerjaan dan tidak diperkenankan digunakan untuk kepentingan pribadi yang merugikan perusahaan. <strong>PIHAK PERTAMA</strong> telah melakukan pengecekan fisik atas fasilitas kerja yang dikembalikan.
        </li>
    </ol>

    <!-- HALAMAN 2 -->
    <div class="page-break"></div>

    <ol class="points-list" start="4" style="margin-top: 20px;">
        <li>Peminjaman berlaku sejak tanggal ditandatangani hingga: ______________ / sampai dengan adanya permintaan pengembalian dari <strong>PIHAK PERTAMA</strong>.</li>
        <li>
            Tanggung Jawab Penggunaan <strong>PIHAK KEDUA</strong> wajib:
            <ul style="list-style-type: lower-alpha; padding-left: 20px; margin-top: 5px;">
                <li style="margin-bottom: 4px;">Menjaga dan merawat inventaris kerja dengan sebaik-baiknya;</li>
                <li style="margin-bottom: 4px;">Menggunakan sesuai fungsi dan kepentingan pekerjaan;</li>
                <li style="margin-bottom: 4px;">Tidak memindahtangankan kepada pihak lain tanpa izin;</li>
                <li style="margin-bottom: 4px;">Bertanggung jawab penuh atas keamanan dan kondisi barang selama masa peminjaman;</li>
                <li style="margin-bottom: 4px;">Mengembalikan dalam kondisi baik seperti semula (wajar pemakaian).</li>
            </ul>
        </li>
        <li>
            <strong>PIHAK KEDUA</strong> dilarang:
            <ul style="list-style-type: lower-alpha; padding-left: 20px; margin-top: 5px;">
                <li style="margin-bottom: 4px;">Menggunakan untuk aktivitas ilegal atau melanggar hukum;</li>
                <li style="margin-bottom: 4px;">Melakukan modifikasi tanpa izin;</li>
                <li style="margin-bottom: 4px;">Meminjamkan kepada pihak lain;</li>
                <li style="margin-bottom: 4px;">Mengabaikan keamanan yang dapat menyebabkan kehilangan/kerusakan.</li>
            </ul>
        </li>
        <li>
            Dalam hal terjadi kerusakan ataupun kehilangan dijelaskan sebagai berikut:
            <ul style="list-style-type: lower-alpha; padding-left: 20px; margin-top: 5px;">
                <li style="margin-bottom: 4px;"><strong>Kerusakan PIHAK KEDUA</strong> wajib mengganti biaya perbaikan sesuai nilai yang ditentukan oleh <strong>PIHAK PERTAMA</strong> sebesar Rp 1.000.000 per Laptop.</li>
                <li style="margin-bottom: 4px;"><strong>Kerusakan Berat / Tidak Dapat Digunakan PIHAK KEDUA</strong> wajib mengganti sebesar nilai penggantian unit baru dengan spesifikasi setara atau lebih tinggi atau uang senilai harga beli sebesar Rp 2.500.000 per Laptop</li>
                <li style="margin-bottom: 4px;"><strong>Kehilangan Laptop PIHAK KEDUA</strong> wajib mengganti sebesar <strong>100% nilai barang beli</strong> Sebesar Rp 2.500.000</li>
                <li style="margin-bottom: 4px;">
                    <strong>Kehilangan Aksesoris</strong> Wajib mengganti sesuai harga pasar yang berlaku dengan rincian sebagai berikut:
                    <ul style="list-style-type: disc; padding-left: 15px; margin-top: 3px;">
                        <li>Charger &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rp500.000,00</li>
                        <li>Headshet &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rp120.000,00</li>
                        <li>Mouse &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rp50.000,00</li>
                        <li>Adapter LAN &nbsp;&nbsp;&nbsp;&nbsp; Rp50.000,00</li>
                    </ul>
                </li>
                <li style="margin-bottom: 4px;">Kerusakan yang disebabkan akibat cacat pabrik atau bukan karena <i>human error</i> tidak akan dikenakan denda.</li>
            </ul>
        </li>
        <li>
            Pemotongan Gaji dan Mekanisme Pembayaran:
            <ul style="list-style-type: lower-alpha; padding-left: 20px; margin-top: 5px;">
                <li style="margin-bottom: 4px;"><strong>PIHAK KEDUA</strong> dengan ini memberikan persetujuan tertulis kepada <strong>PIHAK PERTAMA</strong> untuk melakukan pemotongan gaji dan/atau hak finansial lainnya apabila terjadi kewajiban penggantian.</li>
                <li style="margin-bottom: 4px;">
                    Pemotongan dilakukan dengan ketentuan:
                    <ul style="list-style-type: disc; padding-left: 15px; margin-top: 3px;">
                        <li>Dilakukan secara bertahap (cicilan);</li>
                        <li>Memperhatikan kemampuan finansial PIHAK KEDUA;</li>
                    </ul>
                </li>
            </ul>
        </li>
    </ol>

    <!-- HALAMAN 3 -->
    <div class="page-break"></div>

    <ul style="list-style-type: disc; padding-left: 40px; margin-top: 20px;">
        <li style="margin-bottom: 4px;">Tidak melanggar ketentuan upah minimum yang berlaku;</li>
        <li style="margin-bottom: 4px;">Dilakukan berdasarkan perhitungan yang transparan.</li>
    </ul>
    
    <div style="margin-left: 20px; margin-top: 10px;">
        c) Apabila PIHAK KEDUA tidak lagi bekerja (resign/PHK), maka:
        <ul style="list-style-type: disc; padding-left: 15px; margin-top: 3px;">
            <li>Kewajiban tetap harus diselesaikan sebelum proses clearance;</li>
            <li>Perusahaan berhak menahan hak pembayaran (gaji terakhir, bonus, dll).</li>
        </ul>
    </div>

    <ol class="points-list" start="9" style="margin-top: 20px;">
        <li>
            Apabila PIHAK KEDUA lalai atau tidak memenuhi kewajiban:
            <ul style="list-style-type: lower-alpha; padding-left: 20px; margin-top: 5px;">
                <li style="margin-bottom: 4px;">Dikenakan kewajiban ganti rugi penuh;</li>
                <li style="margin-bottom: 4px;">Dapat dikenakan tindakan administratif sesuai kebijakan perusahaan;</li>
                <li style="margin-bottom: 4px;">Tidak menghapus kemungkinan tindakan hukum.</li>
            </ul>
        </li>
        <li>Dengan ditandatanganinya dokumen ini, PIHAK KEDUA dianggap telah membaca, memahami, dan menyetujui seluruh konsekuensi hukum yang timbul, termasuk namun tidak terbatas pada kewajiban ganti rugi, pengenaan denda, serta pemotongan hak finansial, sehingga tidak dapat mengajukan keberatan di kemudian hari dengan alasan apapun.</li>
    </ol>

    <div class="intro-text" style="margin-top: 25px;">
        Demikian Berita Acara Serah Terima ini dibuat untuk dipergunakan sebagaimana mestinya.
    </div>

    <div class="intro-text" style="margin-top: 20px;">
        Yogyakarta, <strong>{{ $transaction->created_at->translatedFormat('d F Y') }}</strong>
    </div>

    <div class="signature-section" style="margin-top: 30px;">
        <div class="signature-box">
            <p><strong>Pihak Pertama,</strong></p>
            <div class="signature-line">
                {{ auth()->user()->name }}
            </div>
            <div class="signature-title">&nbsp;</div>
        </div>
        <div class="signature-box">
            <p><strong>Pihak Kedua,</strong></p>
            <div class="signature-line">
                {{ $transaction->nama_pengaju }}
            </div>
            <div class="signature-title">&nbsp;</div>
        </div>
        <div class="signature-box">
            <p><strong>Mengetahui,</strong></p>
            <div class="signature-line">
                {{ auth()->user()->name }}
            </div>
            <div class="signature-title">HRD</div>
        </div>
    </div>

</body>
</html>
