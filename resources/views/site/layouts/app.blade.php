<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'الموقع')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- تطبيق الثيم على الموقع فقط --}}
    <style>
        :root {
            --bs-primary: {{ $themeVars['primary'] }};
            --bs-secondary: {{ $themeVars['secondary'] }};
        }

        @if (($themeVars['mode'] ?? 'auto') === 'dark')
            body {
                background: #0f1520;
                color: #e5e7eb;
            }

            .navbar,
            .card {
                background: #111827;
                color: #e5e7eb;
            }
        @endif
    </style>
</head>

<body>

    {{-- هيدر الموقع --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('site.home') }}">
                <img src="{{ asset(ltrim($themeVars['logoPath'],'/')) }}" alt="Logo" style="height:40px">
                <link rel="icon" href="{{ asset(ltrim($themeVars['faviconPath'],'/')) }}">
                <span class="fw-bold">{{ $currentUniversity->name ?? 'المنهج الاكاديمي' }}</span>
            </a>

            {{-- اختيار جامعة (اختياري) --}}
            <form method="GET" action="{{ url()->current() }}" class="ms-auto d-flex gap-2">
                <select name="university_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    {{-- خيار الثيم الافتراضي --}}
                    <option value="default" @selected(request('university_id') === 'default')>
                        الثيم الافتراضي
                    </option>

                    {{-- فاصل بصري اختياري --}}
                    <option value="" disabled>──────────</option>

                    {{-- الجامعات المفعلة --}}
                    @foreach (\App\Models\University::where('is_active', true)->orderBy('name')->get() as $u)
                        <option value="{{ $u->id }}" @selected(request('university_id') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </form>

        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="border-top py-3">
        <div class="container text-muted small d-flex justify-content-between">
            <span>© {{ date('Y') }} — بوابة الطلاب</span>
            @if ($currentUniversity)
                <span>{{ $currentUniversity->address }} — {{ $currentUniversity->phone }}</span>
            @endif
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
