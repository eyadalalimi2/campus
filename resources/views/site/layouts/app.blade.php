<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>
    @hasSection('title')
      @yield('title')
    @else
      @if(isset($setting) && !empty($setting->site_title))
        {{ $setting->site_title }}
      @else
        الموقع
      @endif
    @endif
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Bootstrap + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  {{-- Favicon (إن كان محدد في الثيم أو إعدادات الموقع) --}}
  @if(!empty($themeVars['faviconPath'] ?? null))
    <link rel="icon" href="{{ asset(ltrim($themeVars['faviconPath'],'/')) }}">
  @elseif(isset($setting) && !empty($setting->dashboard_favicon))
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->dashboard_favicon) }}">
  @endif

  <!-- Flatpickr -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <!-- التعريب العربي -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>

  {{-- تطبيق الثيم على الموقع فقط --}}
  <style>
    :root{
      --bs-primary: {{ $themeVars['primary'] ?? '#0d6efd' }};
      --bs-secondary: {{ $themeVars['secondary'] ?? '#6c757d' }};
    }
    /* تحسين العرض داخل RTL لبعض الحقول */
    .date-input { direction: ltr; text-align: right; }
    @if(($themeVars['mode'] ?? 'auto') === 'dark')
      body{ background:#0f1520; color:#e5e7eb; }
      .navbar, .card{ background:#111827; color:#e5e7eb; }
    @endif
  </style>
  @stack('styles')
</head>
<body>

  @include('site.partials.navbar')

  <main class="py-4">
    <div class="container">
      @yield('content')
    </div>
  </main>

  @include('site.partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // إخفاء جميع التوستات تلقائياً بعد 3 ثواني
    setTimeout(function() {
      document.querySelectorAll('.toast.show').forEach(function(toast) {
        toast.classList.remove('show');
        setTimeout(function(){ toast.remove(); }, 500);
      });
    }, 3000);
  });
  </script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
      document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
        if(alert.classList.contains('show')) {
          alert.classList.remove('show');
          setTimeout(function(){ alert.remove(); }, 500);
        }
      });
    }, 3000);
  });
  </script>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const base = {
        locale: 'ar',
        disableMobile: true,
        weekNumbers: true,
        position: 'auto right'
      };

      if (document.querySelector('#published_at')) {
        flatpickr('#published_at', {
          ...base,
          enableTime: true,
          time_24hr: true,
          dateFormat: 'Y-m-d H:i',
          altInput: true,
          altFormat: 'd F Y - H:i',
          minuteIncrement: 5
        });
      }

      document.querySelectorAll('.js-date').forEach(el => {
        flatpickr(el, {
          ...base,
          dateFormat: 'Y-m-d',
          altInput: true,
          altFormat: 'd F Y'
        });
      });
      document.querySelectorAll('.js-datetime').forEach(el => {
        flatpickr(el, {
          ...base,
          enableTime: true,
          time_24hr: true,
          dateFormat: 'Y-m-d H:i',
          altInput: true,
          altFormat: 'd F Y - H:i'
        });
      });
    });
  </script>
  @endpush

  <script>
  feather.replace();
  </script>
  @stack('scripts')
</body>
</html>
