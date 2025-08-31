@extends('admin.layouts.app')

@section('title','لوحة التحكم')

@section('content')
@php
  // بيانات افتراضية في حال لم تُمرر من الكنترولر
  $kpis = $kpis ?? [
      ['title' => 'إجمالي الطلاب',      'value' => 12450, 'delta' => '+4.2%',  'icon' => 'users'],
      ['title' => 'المقررات النشطة',     'value' => 320,   'delta' => '+1.3%',  'icon' => 'book-open'],
      ['title' => 'هيئة التدريس',        'value' => 540,   'delta' => '-0.8%',  'icon' => 'briefcase'],
      ['title' => 'الإيرادات الشهرية',    'value' => 48720, 'delta' => '+12.6%', 'icon' => 'credit-card'],
  ];

  $months = ['ينا','فبر','مار','أبر','ماي','يون','يول','أغس','سبت','أكت','نوف','ديس'];

  $revenueSeries = $revenueSeries ?? [
      'labels' => $months,
      'data'   => [21000,22500,24800,26000,27800,30000,32200,34500,36800,41000,45500,48720],
  ];
  $usersSeries = $usersSeries ?? [
      'labels' => $months,
      'data'   => [820,860,910,950,990,1030,1080,1120,1180,1250,1310,1390],
  ];
  $deptDistribution = $deptDistribution ?? [
      'labels' => ['علوم الحاسب', 'هندسة', 'إدارة', 'آداب', 'علوم'],
      'data'   => [35,25,15,12,13],
  ];
  $recentEnrollments = $recentEnrollments ?? [
      ['student' => 'سارة أحمد', 'course' => 'CS101 - مقدمة برمجة',  'status' => 'enrolled',  'date' => '2025-08-29'],
      ['student' => 'محمد علي',  'course' => 'MG210 - مبادئ الإدارة','status' => 'completed', 'date' => '2025-08-28'],
      ['student' => 'أحمد سعيد', 'course' => 'EN150 - كتابة أكاديمية','status' => 'dropped',  'date' => '2025-08-27'],
      ['student' => 'ليان خالد', 'course' => 'EE201 - دوائر كهربائية','status' => 'enrolled',  'date' => '2025-08-26'],
      ['student' => 'رنا منصور', 'course' => 'CS240 - هياكل بيانات',  'status' => 'enrolled',  'date' => '2025-08-25'],
  ];
  $notifications = $notifications ?? [
      ['type' => 'warning', 'text' => 'نسبة الانسحاب زادت 3% هذا الأسبوع.'],
      ['type' => 'info',    'text' => 'تذكير: تسجيل الفصل القادم يبدأ 10 سبتمبر.'],
      ['type' => 'success', 'text' => 'اكتملت معالجة نتائج الفصل السابق.'],
  ];
  $tasks = $tasks ?? [
      ['title' => 'مراجعة مقررات قسم علوم الحاسب', 'done' => false],
      ['title' => 'اعتماد خطة التدريب لأعضاء هيئة التدريس', 'done' => true],
      ['title' => 'تحديث سياسة الحد الأدنى للساعات', 'done' => false],
  ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">نظرة عامة</h4>
  <div class="text-muted small">تاريخ اليوم: {{ now()->format('Y-m-d') }}</div>
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
  @foreach ($kpis as $kpi)
    @php $isUp = str_starts_with($kpi['delta'], '+'); @endphp
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card card-kpi border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="p-3 rounded-circle bg-light">
              <i data-feather="{{ $kpi['icon'] }}"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-muted small">{{ $kpi['title'] }}</div>
              <div class="fs-4 fw-bold">
                @if($kpi['title'] === 'الإيرادات الشهرية')
                  ${{ number_format($kpi['value']) }}
                @else
                  {{ number_format($kpi['value']) }}
                @endif
              </div>
              <div class="delta {{ $isUp ? 'up' : 'down' }} small">{{ $kpi['delta'] }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0">الإيرادات الشهرية (آخر 12 شهر)</h6>
          <span class="text-muted small">USD</span>
        </div>
        <canvas id="revenueChart" height="110"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <h6 class="mb-3">توزيع الأقسام</h6>
        <canvas id="deptChart" height="180"></canvas>
      </div>
    </div>
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="mb-3">نمو المستخدمين</h6>
        <canvas id="usersChart" height="140"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- Table + Side widgets --}}
<div class="row g-3">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0">أحدث عمليات التسجيل</h6>
          <a href="#" class="small">عرض الكل</a>
        </div>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>الطالب</th>
                <th>المقرر</th>
                <th>الحالة</th>
                <th class="text-center">التاريخ</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($recentEnrollments as $row)
                <tr>
                  <td>{{ $row['student'] }}</td>
                  <td>{{ $row['course'] }}</td>
                  <td>
                    <span class="badge status-badge {{ $row['status'] }}">{{ $row['status'] }}</span>
                  </td>
                  <td class="text-center text-muted">{{ $row['date'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <h6 class="mb-3">التنبيهات</h6>
        <ul class="list-group list-group-flush">
          @foreach ($notifications as $n)
            <li class="list-group-item d-flex align-items-center gap-2">
              @if($n['type']==='warning')
                <span class="badge text-bg-warning">تنبيه</span>
              @elseif($n['type']==='success')
                <span class="badge text-bg-success">نجاح</span>
              @else
                <span class="badge text-bg-info">معلومة</span>
              @endif
              <span>{{ $n['text'] }}</span>
            </li>
          @endforeach
        </ul>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="mb-3">المهام</h6>
        <ul class="list-group list-group-flush">
          @foreach ($tasks as $t)
            <li class="list-group-item d-flex align-items-center justify-content-between">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" {{ $t['done'] ? 'checked' : '' }} disabled>
                <label class="form-check-label">{{ $t['title'] }}</label>
              </div>
              <span class="badge {{ $t['done'] ? 'text-bg-success' : 'text-bg-secondary' }}">
                {{ $t['done'] ? 'مكتمل' : 'قيد التنفيذ' }}
              </span>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const revenueLabels = @json($revenueSeries['labels']);
  const revenueData   = @json($revenueSeries['data']);

  const usersLabels = @json($usersSeries['labels']);
  const usersData   = @json($usersSeries['data']);

  const deptLabels = @json($deptDistribution['labels']);
  const deptData   = @json($deptDistribution['data']);

  // Revenue (Line)
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: revenueLabels,
      datasets: [{ label: 'الإيرادات', data: revenueData, tension: 0.35, fill: true }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: false } } }
  });

  // Departments (Doughnut)
  new Chart(document.getElementById('deptChart'), {
    type: 'doughnut',
    data: { labels: deptLabels, datasets: [{ data: deptData }] },
    options: { plugins: { legend: { position: 'bottom' } } }
  });

  // Users growth (Bar)
  new Chart(document.getElementById('usersChart'), {
    type: 'bar',
    data: { labels: usersLabels, datasets: [{ label: 'مستخدمون', data: usersData }] },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
  });
</script>
@endpush
