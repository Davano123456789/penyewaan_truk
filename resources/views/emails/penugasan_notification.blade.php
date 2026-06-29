<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penugasan Baru</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, sans-serif; color:#333;">

    <div style="max-width:620px; margin:30px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">

        {{-- Header --}}
        <div style="background-color:#1a56db; padding:28px 32px;">
            <h1 style="margin:0; color:#ffffff; font-size:20px; letter-spacing:0.5px;">🚛 Penugasan Baru</h1>
            <p style="margin:6px 0 0; color:#c7d9ff; font-size:13px;">Sistem Penyewaan Truk</p>
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px;">

            <p style="font-size:16px; margin-top:0;">
                Halo <strong>{{ $sopir->nama ?? $sopir->name ?? 'Sopir' }}</strong>,
            </p>
            <p style="margin-top:0; color:#555;">
                Anda mendapat <strong>tugas baru</strong> yang telah dikonfirmasi oleh admin.
                Berikut detail penugasan Anda:
            </p>

            {{-- Kartu per item --}}
            @forelse($items as $index => $item)
            <div style="background:#f9fafc; border:1px solid #e2e8f0; border-radius:6px; padding:16px 20px; margin-bottom:16px;">

                {{-- Badge kode item --}}
                <div style="margin-bottom:12px;">
                    <span style="background:#1a56db; color:#ffffff; padding:4px 10px; border-radius:4px; font-size:12px; font-weight:bold; letter-spacing:0.5px;">
                        {{ $item->kode_keranjang ?? 'ITEM ' . ($index + 1) }}
                    </span>
                </div>

                {{-- Baris info: label kiri, nilai kanan --}}
                <table style="width:100%; border-collapse:collapse; font-size:14px;">
                    <tr>
                        <td style="padding:5px 0; color:#666; width:110px; vertical-align:top;">Tanggal</td>
                        <td style="padding:5px 0; color:#333; font-weight:bold;">
                            {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0; color:#666; vertical-align:top;">Armada</td>
                        <td style="padding:5px 0; color:#333; font-weight:bold;">
                            {{ $item->armada->no_polisi ?? '-' }}
                            <span style="font-weight:normal; color:#555;">({{ $item->armada->merek ?? '-' }})</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0; color:#666; vertical-align:top;">Muatan</td>
                        <td style="padding:5px 0; color:#333;">{{ $item->barang_muatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0; color:#666; vertical-align:top;">Jemput</td>
                        <td style="padding:5px 0; color:#c0392b;">
                            📍 {{ $item->rute->tempat_jemput ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0; color:#666; vertical-align:top;">Antar</td>
                        <td style="padding:5px 0; color:#27ae60;">
                            🏁 {{ $item->rute->tempat_antar ?? '-' }}
                        </td>
                    </tr>
                </table>

            </div>
            @empty
            <div style="padding:16px; background:#fff8e1; border-left:4px solid #f59e0b; border-radius:4px; font-size:14px; color:#555;">
                Tidak ada item penugasan.
            </div>
            @endforelse

            {{-- CTA --}}
            <div style="margin-top:8px; padding:16px; background:#f0f4ff; border-left:4px solid #1a56db; border-radius:4px;">
                <p style="margin:0; font-size:14px; color:#444;">
                    Silakan cek <strong>dashboard sopir</strong> untuk melihat detail lengkap dan upload bukti penyelesaian setelah pekerjaan selesai.
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div style="background:#f4f6f8; padding:18px 32px; text-align:center; border-top:1px solid #e8ecf0;">
            <p style="margin:0; font-size:12px; color:#888;">
                Terima kasih &mdash; <strong>Admin Penyewaan Truk</strong>
            </p>
        </div>

    </div>

</body>
</html>