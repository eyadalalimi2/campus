@extends('site.layouts.app')

@section('title','الصفحة الرئيسية')

@section('content')
@php
  // بيانات افتراضية عند غيابها من الكنترولر
  $headlineStats = $headlineStats ?? [
      ['label' => 'الطلاب',         'value' => 12450],
      ['label' => 'المقررات',       'value' => 320],
      ['label' => 'هيئة التدريس',  'value' => 540],
      ['label' => 'الأقسام',        'value' => 18],
  ];

  $usps = $usps ?? [
      ['title' => 'إدارة أكاديمية شاملة', 'desc' => 'أقسام، مقررات، طلاب، وأعضاء هيئة تدريس في نظام موحّد.'],
      ['title' => 'تقارير فورية',         'desc' => 'لوحات متابعة لحظية مع رسوم بيانية لاتخاذ قرار سريع.'],
      ['title' => 'قابلية التوسع',        'desc' => 'بنية مرنة تدعم التكامل مع الأنظمة المؤسسية.'],
  ];

  // ألوان من الثيم (محقونة عبر View Composer لـ site.*)
  $p = $themeVars['primary']   ?? '#0d6efd';
  $s = $themeVars['secondary'] ?? '#6c757d';
@endphp

{{-- Hero مع تدرّج يعتمد على ألوان الثيم --}}
<div class="hero py-5" style="background: linear-gradient(135deg, {{ $p }}22, #fff 55%);">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">

        <div class="d-flex align-items-center gap-2 mb-3">
          
          <img src="{{ asset(ltrim($themeVars['logoPath'],'/')) }}" alt="Logo" style="height:44px">
          <span class="badge" style="background: {{ $s }}; color:#fff">{{ $currentUniversity->name ?? 'بوابة الجامعات' }}</span>
        </div>

        <h1 class="display-5 fw-bold mb-3" style="color: {{ $p }}">منصّة جامعية لإدارة الأكاديمية بكفاءة</h1>
        <p class="text-muted mb-4">
          إدارة الأقسام، المقررات، الطلاب، وأعضاء هيئة التدريس — في نظام موحّد سريع ومرن، مع تقارير لحظية.
        </p>
        <div class="d-flex flex-wrap gap-2">
          {{-- روابط موقعية (غير إدارية) --}}
          <a href="{{ route('site.home') }}" class="btn btn-primary btn-lg" style="background: {{ $p }}; border-color: {{ $p }}">
            ابدأ الآن
          </a>
          <a href="javascript:void(0)" class="btn btn-outline-secondary btn-lg" style="border-color: {{ $s }}; color: {{ $s }}">
            تعرّف أكثر
          </a>
        </div>

        @if(!empty($currentUniversity))
          <div class="mt-3 small text-muted">
            <i class="bi bi-geo-alt"></i> {{ $currentUniversity->address }} &nbsp;•&nbsp;
            <i class="bi bi-telephone"></i> {{ $currentUniversity->phone }}
          </div>
        @endif
      </div>

      <div class="col-lg-6">
        <div class="row g-3">
          @foreach($headlineStats as $sItem)
            <div class="col-6">
              <div class="p-4 text-center" style="border-radius:.75rem; background:#fff; box-shadow:0 6px 16px rgba(0,0,0,.06);">
                <div class="display-6 fw-bold" style="color: {{ $p }}">{{ number_format($sItem['value']) }}</div>
                <div class="text-muted">{{ $sItem['label'] }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="h3" style="color: {{ $p }}">لماذا المنهج الأكاديمي؟</h2>
      <p class="text-muted">قيم تشغيل عالية، قرارات أسرع، ورؤية موحّدة.</p>
    </div>
    <div class="row g-3">
      @foreach($usps as $f)
        <div class="col-md-4">
          <div class="card h-100" style="border:0; box-shadow:0 6px 16px rgba(0,0,0,.06); border-radius:.75rem;">
            <div class="card-body">
              <h5 class="card-title">{{ $f['title'] }}</h5>
              <p class="card-text text-muted">{{ $f['desc'] }}</p>
              {{-- رابط موقع (غير إداري) يمكن استبداله لاحقاً --}}
              <a class="stretched-link" href="javascript:void(0)"></a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="py-5" style="background: {{ $p }}11;">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <h3 class="fw-bold mb-3" style="color: {{ $p }}">تكامل سريع مع أنظمتك الحالية</h3>
        <p class="text-muted mb-4">
          واجهات برمجية تسهّل الهجرة من الأنظمة القديمة. قابلية لإدارة الأذونات والأدوار.
        </p>
        <div class="d-flex gap-2">
          <a href="{{ route('site.home') }}" class="btn btn-primary" style="background: {{ $p }}; border-color: {{ $p }}">ابدأ الآن</a>
          <a href="javascript:void(0)" class="btn btn-outline-secondary" style="border-color: {{ $s }}; color: {{ $s }}">تواصل معنا</a>
        </div>
      </div>
      <div class="col-lg-6">
        <img class="img-fluid rounded shadow-sm" src="https://picsum.photos/960/540" alt="Campus Preview">
      </div>
    </div>
  </div>
</section>
@endsection
