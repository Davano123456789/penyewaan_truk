<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penugasan Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Halo {{ $sopir->nama ?? $sopir->name ?? 'Sopir' }},</h2>

        <p>Ada penugasan baru untuk Anda dari penyewaan <strong>#{{ $penyewaan->id }}</strong>.</p>
        
        <p>Penyewaan ini telah dikonfirmasi oleh admin dan sekarang <strong>AKTIF</strong>.</p>

        <h3>Detail Penugasan:</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Armada</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Tanggal Mulai</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Penjemputan</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Tujuan</th>
            </tr>
            @forelse($items as $item)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 10px;">
                        {{ $item->armada->nama ?? ('Armada #' . $item->armada_id) }}
                    </td>
                    <td style="border: 1px solid #ddd; padding: 10px;">
                        {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-' }}
                    </td>
                    <td style="border: 1px solid #ddd; padding: 10px;">
                        {{ $item->tempat_jemput ?? '-' }}
                    </td>
                    <td style="border: 1px solid #ddd; padding: 10px;">
                        {{ $item->tempat_antar ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="border: 1px solid #ddd; padding: 10px; text-align: center;">Tidak ada item penugasan</td>
                </tr>
            @endforelse
        </table>

        <p style="margin-top: 20px;">Silakan cek dashboard sopir Anda untuk melihat detail lengkap dan upload bukti penyelesaian saat pekerjaan selesai.</p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">

        <p style="font-size: 12px; color: #777;">
            Terima kasih,<br>
            <strong>Tim Penyewaan Truk</strong>
        </p>
    </div>
</body>
</html>