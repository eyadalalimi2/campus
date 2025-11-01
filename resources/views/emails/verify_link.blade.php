<!doctype html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تفعيل البريد الإلكتروني</title>
    <!-- Preheader (hidden in many clients) -->
    <style>
      /* بعض العملاء يدعم القليل من CSS؛ نعتمد أساساً على الجداول وخصائص inline */
      @media (prefers-color-scheme: dark) {
        .bg-page { background-color:#0f172a !important; }
        .card { background-color:#0b1220 !important; color:#e5e7eb !important; }
        .muted { color:#94a3b8 !important; }
        .btn { background-color:#0ea5a4 !important; }
        .link { color:#38bdf8 !important; }
      }
      @media only screen and (max-width: 600px) {
        .container { width:100% !important; padding:0 16px !important; }
        .btn { display:block !important; width:100% !important; }
      }
    </style>
  </head>
  <body class="bg-page" style="margin:0; padding:0; background:#f5f7fb;">
    <div style="display:none; font-size:1px; color:#f5f7fb; line-height:1; max-height:0; max-width:0; opacity:0; overflow:hidden;">
      تفعيل حسابك على المنهج ألأكاديمي — رابط صالح لمدة 10 دقائق
    </div>

    <!-- Full-width wrapper -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background:#f5f7fb;">
      <tr>
        <td align="center" style="padding:24px;">
          <!-- Container -->
          <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" class="container" style="max-width:620px; width:100%;">
            <tr>
              <td>
                <!-- Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" class="card" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(2,8,20,.06);">
                  <!-- Header / Brand -->
                  <tr>
                    <td style="padding:20px 24px; background:#0ea5a4;">
                      <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                          <td align="center" style="font-family:Tahoma, Arial, sans-serif;">
                            <a href="{{ config('app.url') }}" target="_blank" style="text-decoration:none; display:inline-block;">
                              <img src="https://alaaglaa.com/storage/settings/logo.png"
                                   width="160"
                                   alt="المنهج الاكاديمي"
                                   style="display:block; width:160px; max-width:160px; height:auto; border:0; outline:none; text-decoration:none; margin:0 auto;">
                            </a>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>

                  <!-- Body -->
                  <tr>
                    <td style="padding:24px 24px 12px 24px; color:#0f172a; font-family:Tahoma, Arial, sans-serif;">
                      <h2 style="margin:0 0 8px; font-size:22px;">مرحبًا {{ $userName }},</h2>
                      <p style="margin:0; line-height:1.8; font-size:15px; color:#334155;">
                        شكرًا لانضمامك إلى <strong>المنهج ألأكاديمي</strong>.
                        لإتمام عملية التسجيل وتفعيل حسابك، يُرجى النقر على الزر أدناه.
                      </p>
                    </td>
                  </tr>

                  <!-- CTA Button -->
                  <tr>
                    <td align="center" style="padding:20px 24px 12px 24px;">
                      <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                          <td align="center" bgcolor="#0ea5a4" style="border-radius:10px;">
                            <a href="{{ $verifyUrl }}" class="btn"
                               style="display:inline-block; padding:12px 28px; font-family:Tahoma, Arial, sans-serif; font-size:15px; color:#ffffff; background:#0ea5a4; text-decoration:none; border-radius:10px;">
                              تفعيل البريد الآن
                            </a>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>

                  <!-- Secondary copy + fallback link -->
                  <tr>
                    <td style="padding:8px 24px 0 24px; font-family:Tahoma, Arial, sans-serif;">
                      <p class="muted" style="margin:0 0 8px; font-size:13px; color:#64748b;">
                        إن لم يعمل الزر، يمكنك نسخ الرابط التالي ولصقه في المتصفح:
                      </p>
                      <div style="background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; padding:12px; direction:ltr; text-align:left; word-break:break-all; font-family:Consolas, 'Courier New', monospace; font-size:12px; color:#0f172a;">
                        {{ $verifyUrl }}
                      </div>
                    </td>
                  </tr>

                  <!-- Expiry / Security note -->
                  <tr>
                    <td style="padding:16px 24px 8px 24px; font-family:Tahoma, Arial, sans-serif;">
                      <p class="muted" style="margin:0; font-size:12px; color:#64748b; line-height:1.7;">
                        هذا الرابط صالح لمدة <strong>10 دقائق</strong>. إذا لم تطلب هذا الإجراء، فيُرجى تجاهل هذه الرسالة.
                      </p>
                    </td>
                  </tr>

                  <!-- Divider -->
                  <tr><td style="padding:8px 24px 0 24px;"><hr style="border:none; border-top:1px solid #e2e8f0; margin:0;"></td></tr>

                  <!-- Footer -->
                  <tr>
                    <td style="padding:16px 24px 24px 24px; font-family:Tahoma, Arial, sans-serif;">
                      <p class="muted" style="margin:0 0 6px; font-size:12px; color:#64748b;">
                        إذا واجهتك أي مشكلة، راسلنا على
                        <a class="link" href="mailto:{{ config('mail.from.address') }}" style="color:#0ea5a4; text-decoration:none;">{{ config('mail.from.address') }}</a>
                      </p>
                      <p class="muted" style="margin:0; font-size:12px; color:#94a3b8;">
                        © {{ now()->year }} المنهج ألأكاديمي. جميع الحقوق محفوظة.
                      </p>
                    </td>
                  </tr>
                </table>
                <!-- /Card -->
              </td>
            </tr>
          </table>
          <!-- /Container -->
        </td>
      </tr>
    </table>
    <!-- /Wrapper -->
  </body>
  </html>
