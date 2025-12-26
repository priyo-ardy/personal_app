<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Password - Personal Application</title>
    <style type="text/css">
        /* Client-specific resets */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Responsive */
        @media screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }

            .padding-content {
                padding: 20px !important;
            }

            .header-text {
                font-size: 20px !important;
            }

            .password-text {
                font-size: 24px !important;
            }

            .mobile-stack {
                display: block !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #f7f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f7f9fc;">
        <tr>
            <td align="center" style="padding: 20px 0;">

                <table role="presentation" class="email-container" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e1e4e8;">

                    <tr>
                        <td align="center" style="background-color: #0056b3; padding: 30px 20px; background: linear-gradient(135deg, #0056b3, #003d82);">
                            <h1 class="header-text" style="color: #ffffff; font-size: 24px; margin: 0; font-weight: 600; font-family: 'Segoe UI', sans-serif;">Reset Password</h1>
                            <p style="color: #e6f0ff; font-size: 14px; margin: 5px 0 0 0;">Personal Application</p>
                        </td>
                    </tr>

                    <tr>
                        <td class="padding-content" style="padding: 40px 40px 20px 40px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #333333; line-height: 1.6;">
                                Halo, <strong style="color: #0056b3;"><?= esc(ucwords($full_name)) ?></strong>
                            </p>

                            <p style="margin: 0 0 25px 0; font-size: 15px; color: #555555; line-height: 1.6;">
                                Kami menerima permintaan untuk mereset password akun Anda. Berikut adalah password baru yang telah digenerate oleh sistem:
                            </p>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0f7ff; border: 2px dashed #0056b3; border-radius: 8px;">
                                <tr>
                                    <td align="center" style="padding: 25px;">
                                        <span style="display: block; font-size: 13px; color: #666666; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Password Baru Anda</span>
                                        <span class="password-text" style="display: block; font-family: 'Courier New', monospace; font-size: 28px; font-weight: bold; color: #0056b3; letter-spacing: 2px; background: #ffffff; padding: 10px 20px; border-radius: 4px; display: inline-block;"><?= esc($new_password) ?></span>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 40px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                                <tr>
                                    <td style="padding: 15px;">
                                        <p style="margin: 0 0 10px 0; font-size: 12px; color: #888888; text-transform: uppercase; font-weight: bold;">Detail Permintaan:</p>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; color: #555555;">
                                            <tr>
                                                <td width="30%" style="padding-bottom: 5px; font-weight: 600;">Waktu:</td>
                                                <td style="padding-bottom: 5px; font-family: monospace;"><?= esc($date) ?></td>
                                            </tr>
                                            <tr>
                                                <td width="30%" style="padding-bottom: 5px; font-weight: 600;">IP Address:</td>
                                                <td style="padding-bottom: 5px; font-family: monospace;"><?= esc($ip_address) ?></td>
                                            </tr>
                                            <tr>
                                                <td width="30%" valign="top" style="font-weight: 600;">Device:</td>
                                                <td style="font-family: monospace; line-height: 1.4;"><?= esc($user_agent) ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="padding-content" style="padding: 20px 40px 40px 40px;">
                            <div style="background-color: #fff8e6; border-left: 4px solid #ffc107; padding: 15px; font-size: 14px; color: #856404; line-height: 1.5;">
                                <strong>Penting:</strong> Jika Anda tidak merasa melakukan permintaan ini, segera hubungi tim IT kami karena keamanan akun Anda mungkin terancam.
                            </div>
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #666666; text-align: center;">
                                Mohon segera ubah password ini setelah Anda berhasil login.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #f1f3f5; padding: 30px 40px; border-top: 1px solid #e9ecef;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="color: #666666; font-size: 13px; line-height: 1.6;">
                                        <strong>System Administrator</strong><br>
                                        PT. Schlemmer Automotive Indonesia<br>
                                        Kawasan Industri Delta Silicon 3<br>
                                        Jl. Johar, Blok F8 No.6, Cikarang Pusat<br>
                                        <span style="color: #0056b3;">P: 021-8991-3741</span> | <a href="mailto:it@schlemmer.co.id" style="color: #0056b3; text-decoration: none;">it@schlemmer.co.id</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 20px;">
                                        <p style="margin: 0; font-size: 11px; color: #999999; font-style: italic;">
                                            Email ini dikirim secara otomatis oleh sistem Schlemmer APQP. Mohon jangan membalas email ini.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <p style="text-align: center; font-size: 12px; color: #999999; margin-top: 20px;">
                    &copy; <?= date('Y') ?> PT. Schlemmer Automotive Indonesia. All rights reserved.
                </p>

            </td>
        </tr>
    </table>

</body>

</html>