<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembatalan Penugasan Kerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, sans-serif; color:#333;">

    <div style="max-width:620px; margin:30px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">

        {{-- Header --}}
        <div style="background-color:#dc2626; padding:28px 32px;">
            <h1 style="margin:0; color:#ffffff; font-size:20px; letter-spacing:0.5px;">⚠️ Pembatalan Penugasan Kerja</h1>
            <p style="margin:6px 0 0; color:#ffd0d0; font-size:13px;">Sistem Penyewaan Truk</p>
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px;">

            <p style="font-size:16px; margin-top:0;">
                Halo Bapak/Ibu <strong>{{ $sopir->nama ?? $sopir->name ?? 'Sopir' }}</strong>,
            </p>
            <p style="margin-top:0; color:#555; line-height:1.6;">
                Kami menginformasikan bahwa penugasan pengiriman untuk item sewa di bawah ini telah **DIBATALKAN** oleh pihak admin/klien.
            </p>

            {{-- Kartu Info Detail --}}
            <div style="background:#fff5f5; border:1px solid #fecaca; border-radius:6px; padding:16px 20px; margin-bottom:16px;">

                <div style="margin-bottom:12px;">
                    <span style="background:#dc2626; color:#ffffff; padding:4px 10px; border-radius:4px; font-size:12px; font-weight:bold; letter-spacing:0.5px;">
                        {{ $keranjang->kode_keranjang ?? '-' }}
                    </span>
                </div>

                <table style="width:100%; border-collapse:collapse; font-size:14px; line-height: 1.6;">
                    <tr>
                        <td style="padding:5px 0; color:#666; width:110px; vertical-align:top;">Tanggal Awal</td>
                        <td style="padding:5px 0; color:#333; font-weight:bold;">
                            {{ $keranjang->tanggal_mulai ? \Carbon\Carbon::parse($keranjang->tanggal_mulai)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0; color:#666; vertical-align:top;">Armada Truk</td>
                        <td style="padding:5px 0; color:#333; font-weight:bold;">
                            {{ $keranjang->armada->no_polisi ?? '-' }}
                            <span style="font-weight:normal; color:#555;">({{ $keranjang->armada->merek ?? '-' }})</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0; color:#666; vertical-align:top;">Muatan Barang</td>
                        <td style="padding:5px 0; color:#333;">{{ $keranjang->barang_muatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0; color:#666; vertical-align:top;">Titik Jemput</td>
                        <td style="padding:5px 0; color:#333;">
                            📍 {{ $keranjang->rute->tempat_jemput ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0; color:#666; vertical-align:top;">Titik Antar</td>
                        <td style="padding:5px 0; color:#333;">
                            🏁 {{ $keranjang->rute->tempat_antar ?? '-' }}
                        </td>
                    </tr>
                </table>

            </div>

            {{-- Info Tambahan --}}
            <div style="margin-top:8px; padding:16px; background:#fffbf0; border-left:4px solid #d97706; border-radius:4px;">
                <p style="margin:0; font-size:14px; color:#666; line-height:1.5;">
                    Status armada Anda telah kami kembalikan menjadi <strong>Tersedia</strong> di sistem. Silakan abaikan penugasan ini dan nantikan penugasan berikutnya.
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div style="background:#f4f6f8; padding:18px 32px; text-align:center; border-top:1px solid #e8ecf0;">
            <p style="margin:0; font-size:12px; color:#888;">
                Terima kasih &mdash; <strong>Admin PT Sutra Jaya</strong>
            </p>
        </div>

    </div>

</body>
</html>
