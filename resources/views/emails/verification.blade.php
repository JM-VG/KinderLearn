<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email — KinderLearn</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb;padding:40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

                {{-- Header --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#F4654D,#ff8c42);border-radius:20px 20px 0 0;padding:32px 40px;text-align:center;">
                        <div style="font-size:32px;margin-bottom:8px;">🎓</div>
                        <div style="color:#fff;font-size:26px;font-weight:800;letter-spacing:-0.5px;">KinderLearn</div>
                        <div style="color:rgba(255,255,255,0.8);font-size:13px;margin-top:4px;letter-spacing:1px;text-transform:uppercase;">Email Verification</div>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="background:#ffffff;padding:40px;">
                        <p style="margin:0 0 8px;font-size:20px;font-weight:700;color:#1a202c;">Hi {{ $userName }}! 👋</p>
                        <p style="margin:0 0 28px;font-size:15px;color:#6b7280;line-height:1.6;">
                            Thanks for signing up to KinderLearn! To complete your registration, enter the
                            verification code below on the verification page.
                        </p>

                        {{-- Code box --}}
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" style="padding:8px 0 28px;">
                                    <div style="display:inline-block;background:#fff9f7;border:2.5px dashed #F4654D;border-radius:16px;padding:24px 48px;">
                                        <div style="font-size:13px;font-weight:700;color:#9ca3af;letter-spacing:2px;text-transform:uppercase;margin-bottom:10px;">Your Code</div>
                                        <div style="font-size:42px;font-weight:900;letter-spacing:12px;color:#F4654D;font-family:'Courier New',monospace;">{{ $code }}</div>
                                        <div style="font-size:12px;color:#9ca3af;margin-top:10px;">Expires in <strong>15 minutes</strong></div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 8px;font-size:14px;color:#6b7280;line-height:1.6;">
                            If you did not create a KinderLearn account, you can safely ignore this email.
                            Someone may have entered your email address by mistake.
                        </p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f9fafb;border-top:1px solid #e5e7eb;border-radius:0 0 20px 20px;padding:20px 40px;text-align:center;">
                        <p style="margin:0;font-size:12px;color:#9ca3af;">
                            © {{ date('Y') }} KinderLearn &nbsp;·&nbsp; This is an automated email, please do not reply.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
