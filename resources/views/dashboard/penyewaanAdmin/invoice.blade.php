<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $penyewaan->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #4e73df;
        }
        .info-table {
            width: 100%;
            margin-bottom: 40px;
        }
        .info-table td {
            vertical-align: top;
        }
        .client-info {
            width: 50%;
        }
        .invoice-info {
            width: 50%;
            text-align: right;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8f9fc;
            border: 1px solid #e3e6f0;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            border: 1px solid #e3e6f0;
            padding: 12px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-size: 18px;
            font-weight: bold;
            color: #4e73df;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-aktif { background-color: #e1f5fe; color: #0288d1; }
        .status-selesai { background-color: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td class="logo">SISTEM ARMADA TRUK</td>
                <td class="text-right" style="font-size: 24px; font-weight: bold; color: #555;">INVOICE</td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td class="client-info">
                    <h3 style="margin-bottom: 5px;">Kepada:</h3>
                    <strong>{{ $penyewaan->client->nama }}</strong><br>
                    Email: {{ $penyewaan->client->email }}<br>
                    Telp: {{ $penyewaan->client->telepon ?? '-' }}<br>
                    Alamat: {{ $penyewaan->client->alamat ?? '-' }}
                </td>
                <td class="invoice-info">
                    <h3 style="margin-bottom: 5px;">Detail:</h3>
                    ID Penyewaan: #{{ $penyewaan->id }}<br>
                    Tanggal Transaksi: {{ $penyewaan->created_at->format('d/m/Y') }}<br>
                    Status: <span class="status-badge status-{{ $penyewaan->status }}">{{ $penyewaan->status }}</span>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Detail Armada</th>
                    <th width="20%">Jadwal</th>
                    <th width="15%">Durasi</th>
                    <th width="20%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penyewaan->keranjangs as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->armada->jenis }}</strong><br>
                        No Pol: {{ $item->armada->no_polisi }}<br>
                        <small>{{ $item->tempat_jemput }} &rarr; {{ $item->tempat_antar }}</small>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $item->estimasi_hari }} Hari</td>
                    <td class="text-right">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" class="text-right">TOTAL PEMBAYARAN:</td>
                    <td class="text-right">Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($penyewaan->pembayaran)
        <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fc; border-left: 4px solid #4e73df;">
            <strong>Informasi Pembayaran:</strong><br>
            Metode: {{ str_replace('_', ' ', strtoupper($penyewaan->pembayaran->metode)) }}<br>
            Status: {{ strtoupper($penyewaan->pembayaran->status) }}<br>
            Jumlah Bayar: Rp {{ number_format($penyewaan->pembayaran->jumlah_bayar, 0, ',', '.') }}
        </div>
        @endif

        <div class="footer">
            Terima kasih telah mempercayai layanan kami.<br>
            <strong>Penyewaan Truk - Solusi Transportasi Terpercaya</strong>
        </div>
    </div>
</body>
</html>
