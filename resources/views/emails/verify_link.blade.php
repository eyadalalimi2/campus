<!doctype html>
<html lang="ar" dir="rtl">
  <body style="font-family: Tahoma, Arial, sans-serif; background:#f7f7f7; padding:24px">
    <div style="max-width:560px; margin:auto; background:#fff; padding:24px; border-radius:10px">
      <h2 style="margin-top:0">مرحبًا {{ $userName }},</h2>
      <p>لإتمام عملية التسجيل وتفعيل حسابك، اضغط الزر التالي:</p>

      <p style="text-align:center; margin:24px 0">
        <a href="{{ $verifyUrl }}" 
           style="background:#0D9488; color:#fff; text-decoration:none; padding:12px 22px; border-radius:8px; display:inline-block">
          تفعيل البريد الآن
        </a>
      </p>

      <p>أو انسخ الرابط التالي في المتصفّح:</p>
      <p style="word-break:break-all; direction:ltr">{{ $verifyUrl }}</p>

      <hr style="border:none; border-top:1px solid #eee; margin:24px 0">
      <p style="font-size:12px; color:#666">هذا الرابط صالح لمدة 10 دقائق. إن لم تطلب هذا الإجراء، تجاهل الرسالة.</p>
    </div>
  </body>
</html>
