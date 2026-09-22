<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Login - Cahaya Book</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #333333;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f4f7f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 540px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #e5eae6;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding: 36px 36px 20px 36px; text-align: center; background-color: #ffffff;">
                            <div style="display: inline-block; width: 44px; height: 44px; line-height: 44px; background-color: #4a6b54; border-radius: 12px; text-align: center; color: #ffffff; font-weight: bold; font-size: 20px;">
                                📖
                            </div>
                            <h2 style="margin: 12px 0 0 0; color: #1f2937; font-size: 20px; font-weight: 800; letter-spacing: 0.5px;">
                                CAHAYA BOOK
                            </h2>
                            <p style="margin: 4px 0 0 0; color: #6b7280; font-size: 13px;">
                                Sistem Inventori & Manajemen Buku
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 36px;">
                            <hr style="border: 0; border-top: 1px solid #edf2ee; margin: 0;">
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 30px 36px;">
                            <h3 style="margin: 0 0 12px 0; color: #111827; font-size: 17px; font-weight: 700;">
                                Halo, {{ $name }}!
                            </h3>
                            <p style="margin: 0 0 24px 0; color: #4b5563; font-size: 14px; line-height: 1.6;">
                                Anda menerima email ini karena ada permintaan login ke akun Anda. Gunakan kode verifikasi satu kali (OTP) di bawah ini untuk melanjutkan:
                            </p>

                            <!-- OTP Box -->
                            <div style="background-color: #f0f6f1; border: 2px dashed #4a6b54; border-radius: 14px; padding: 22px; text-align: center; margin-bottom: 24px;">
                                <div style="color: #6b7280; font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 8px;">
                                    KODE VERIFIKASI LOGIN
                                </div>
                                <div style="font-family: 'Courier New', Courier, monospace; font-size: 38px; font-weight: 800; letter-spacing: 10px; color: #2d4636; text-indent: 10px;">
                                    {{ $otp }}
                                </div>
                                <div style="color: #6b7280; font-size: 12px; margin-top: 8px;">
                                    ⏱️ Berlaku selama <strong>5 menit</strong>
                                </div>
                            </div>

                            <!-- Warning -->
                            <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 4px; margin-bottom: 24px;">
                                <p style="margin: 0; color: #92400e; font-size: 12px; line-height: 1.5;">
                                    <strong>Peringatan Keamanan:</strong> Jangan berikan kode OTP ini kepada siapapun. Tim Cahaya Book tidak akan pernah meminta kode ini dari Anda.
                                </p>
                            </div>

                            <p style="margin: 0; color: #6b7280; font-size: 13px; line-height: 1.5;">
                                Jika Anda tidak merasa mencoba masuk ke sistem, Anda dapat mengabaikan email ini dengan aman.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 36px; background-color: #fafbfa; text-align: center; border-top: 1px solid #edf2ee;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                © 2026 PT Cahaya Buku Indonesia. Hak cipta dilindungi.
                            </p>
                            <p style="margin: 6px 0 0 0; color: #9ca3af; font-size: 11px;">
                                Pesan ini dikirimkan secara otomatis oleh sistem verifikasi keamanan.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
