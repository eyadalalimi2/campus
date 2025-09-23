<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>@yield('title','الطب البشري')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-50">
<nav style="padding:12px;background:#0e7490;color:#fff">
  <a href="{{ route('medical.systems.index') }}" style="color:#fff;margin:0 8px">الأجهزة</a>
  <a href="{{ route('medical.subjects.index') }}" style="color:#fff;margin:0 8px">المواد</a>
  <a href="{{ route('medical.doctors.index') }}" style="color:#fff;margin:0 8px">الدكاترة</a>
  <a href="{{ route('medical.doctor-subjects.index') }}" style="color:#fff;margin:0 8px">ربط دكتور↔مادة</a>
  <a href="{{ route('medical.doctor-subject-systems.index') }}" style="color:#fff;margin:0 8px">ربط دكتور↔مادة↔جهاز</a>
  <a href="{{ route('medical.resources.index') }}" style="color:#fff;margin:0 8px">الموارد</a>
  <a href="{{ route('medical.universities.index') }}" style="color:#fff;margin:0 8px">الجامعات</a>
  <a href="{{ route('medical.system-subjects.index') }}" style="color:#fff;margin:0 8px">ربط جهاز↔مادة</a>

</nav>
<main class="container" style="padding:16px">
  @if(session('ok'))<div style="background:#dcfce7;padding:10px;margin:10px 0">{{ session('ok') }}</div>@endif
  @yield('content')
</main>
</body>
</html>
