<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Alamat Email</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, sans-serif; color:#333;">

    <div style="max-width:620px; margin:30px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">

        {{-- Header --}}
        <div style="background-color:#1a56db; padding:28px 32px;">
            <h1 style="margin:0; color:#ffffff; font-size:20px; letter-spacing:0.5px;">✉️ Verifikasi Alamat Email</h1>
            <p style="margin:6px 0 0; color:#c7d9ff; font-size:13px;">Sistem Penyewaan Truk</p>
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px;">

            <p style="font-size:16px; margin-top:0;">
                Halo <strong>{{ $user->nama ?? 'Pengguna' }}</strong>,
            </p>
            <p style="margin-top:0; color:#555; line-height: 1.6;">
                Terima kasih telah mendaftar di <strong>Sistem Penyewaan Truk</strong>. 
                Sebelum Anda dapat menggunakan semua fitur dan layanan kami, silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.
            </p>

            {{-- CTA Button --}}
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $url }}" style="background-color: #1a56db; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 2px 5px rgba(26, 86, 219, 0.3);">
                    Verifikasi Email Sekarang
                </a>
            </div>

            <div style="margin-top:20px; padding:16px; background:#f9fafc; border:1px solid #e2e8f0; border-radius:6px; font-size:13px; color:#666; line-height: 1.5;">
                <p style="margin: 0 0 8px 0;"><strong>Catatan:</strong> Tautan verifikasi ini berlaku selama 60 menit.</p>
                <p style="margin: 0;">Jika Anda tidak merasa mendaftar akun di platform kami, abaikan saja email ini.</p>
            </div>

            {{-- Troubleshooting Link --}}
            <div style="margin-top: 25px; border-top: 1px solid #e8ecf0; padding-top: 15px; font-size: 12px; color: #777; line-height: 1.5; word-break: break-all;">
                Jika tombol di atas tidak dapat diklik, salin dan tempel URL berikut ke peramban web Anda:
                <br>
                <a href="{{ $url }}" style="color: #1a56db; text-decoration: underline;">{{ $url }}</a>
            </div>

        </div>

        {{-- Footer --}}
        <div style="background:#f4f6f8; padding:18px 32px; text-align:center; border-top:1px solid #e8ecf0;">
            <p style="margin:0; font-size:12px; color:#888;">
                Terima kasih &mdash; <strong>Sistem Penyewaan Truk</strong>
            </p>
        </div>

    </div>

</body>
</html>
