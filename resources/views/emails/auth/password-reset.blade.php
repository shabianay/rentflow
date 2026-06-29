<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Inter, Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f1f5f9; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="480" cellpadding="0" cellspacing="0" style="background: #ffffff; border-radius: 12px; overflow: hidden;">
                    <tr>
                        <td style="padding: 32px 32px 0; text-align: center;">
                            <h1 style="margin: 0; font-size: 20px; color: #1e293b;">Reset Password</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 32px; color: #475569; font-size: 14px; line-height: 1.6;">
                            Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 32px; text-align: center;">
                            <a href="{{ $url }}"
                               style="display: inline-block; padding: 12px 32px; background: #6366f1; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600;">
                                Reset Password
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px 32px; color: #64748b; font-size: 13px; line-height: 1.5;">
                            Tautan ini akan kedaluwarsa dalam {{ config('auth.passwords.users.expire', 60) }} menit.<br>
                            Jika Anda tidak meminta reset password, abaikan email ini.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 32px 32px; color: #94a3b8; font-size: 12px; border-top: 1px solid #e2e8f0; text-align: center;">
                            Terima kasih,<br>
                            <strong>{{ config('app.name') }}</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
