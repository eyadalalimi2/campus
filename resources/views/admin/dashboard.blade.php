@extends('admin.layouts.app')
@section('title','لوحة البيانات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">لوحة البيانات</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-person-plus"></i> إضافة طالب
    </a>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-outline-primary btn-sm">
      <i class="bi bi-person-badge"></i> دكتور جديد
    </a>
    <a href="{{ route('admin.contents.create') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-folder-plus"></i> إضافة محتوى
    </a>
    <a href="{{ route('admin.import.index') }}" class="btn btn-outline-dark btn-sm">
      <i class="bi bi-upload"></i> الاستيراد
    </a>
  </div>
</div>

{{-- ======================= --}}
{{--   KPIs (Gradient Cards) --}}
{{-- ======================= --}}
<style>
  .kpi-card {
    border: 0; border-radius: 1rem; color: #fff; overflow: hidden;
    position: relative; box-shadow: 0 8px 18px rgba(0,0,0,.08);
  }
  .kpi-card .icon-wrap {
    position: absolute; inset-inline-end: 12px; inset-block-start: 12px;
    font-size: 2rem; opacity: .25;
  }
  .kpi-card .value { font-size: 2rem; font-weight: 800; line-height: 1; }
  .kpi-card .muted { opacity: .9; font-size: .875rem; }
  .grad-uni { background: linear-gradient(135deg,#5b7fff,#1aa1ff); }
  .grad-col { background: linear-gradient(135deg,#ff7a6e,#ffb86c); }
  .grad-maj { background: linear-gradient(135deg,#8e54e9,#4776e6); }
  .grad-doc { background: linear-gradient(135deg,#00c6ff,#0072ff); }
  .grad-std { background: linear-gradient(135deg,#00b09b,#96c93d); }
</style>

<div class="row g-3">
  {{-- الجامعات --}}
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card grad-uni p-3 h-100">
      <div class="icon-wrap"><i class="bi bi-building-fill"></i></div>
      <div class="muted">عدد الجامعات</div>
      <div class="value">{{ number_format($uniTotal) }}</div>
      <div class="d-flex gap-3 mt-2 small">
        <span class="badge bg-light text-dark">مفعل: {{ number_format($uniActive) }}</span>
        <span class="badge bg-dark">موقوف: {{ number_format($uniInactive) }}</span>
      </div>
      <a class="stretched-link" href="{{ route('admin.universities.index') }}"></a>
    </div>
  </div>
  {{-- الكليات --}}
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card grad-col p-3 h-100">
      <div class="icon-wrap"><i class="bi bi-bank2"></i></div>
      <div class="muted">عدد الكليات</div>
      <div class="value">{{ number_format($colTotal) }}</div>
      <div class="d-flex gap-3 mt-2 small">
        <span class="badge bg-light text-dark">مفعل: {{ number_format($colActive) }}</span>
        <span class="badge bg-dark">موقوف: {{ number_format($colInactive) }}</span>
      </div>
      <a class="stretched-link" href="{{ route('admin.colleges.index') }}"></a>
    </div>
  </div>
  {{-- التخصصات --}}
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card grad-maj p-3 h-100">
      <div class="icon-wrap"><i class="bi bi-diagram-3-fill"></i></div>
      <div class="muted">عدد الأقسام (التخصصات)</div>
      <div class="value">{{ number_format($majTotal) }}</div>
      <div class="d-flex gap-3 mt-2 small">
        <span class="badge bg-light text-dark">مفعل: {{ number_format($majActive) }}</span>
        <span class="badge bg-dark">موقوف: {{ number_format($majInactive) }}</span>
      </div>
      <a class="stretched-link" href="{{ route('admin.majors.index') }}"></a>
    </div>
  </div>
  {{-- الدكاترة --}}
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card grad-doc p-3 h-100">
      <div class="icon-wrap"><i class="bi bi-person-badge-fill"></i></div>
      <div class="muted">عدد الدكاترة</div>
      <div class="value">{{ number_format($docTotal) }}</div>
      <div class="d-flex gap-3 mt-2 small">
        <span class="badge bg-light text-dark">جامعي: {{ number_format($docUni) }}</span>
        <span class="badge bg-dark">مستقل: {{ number_format($docInd) }}</span>
      </div>
      <a class="stretched-link" href="{{ route('admin.doctors.index') }}"></a>
    </div>
  </div>

  {{-- الطلاب --}}
  <div class="col-12">
    <div class="card kpi-card grad-std p-3">
      <div class="icon-wrap"><i class="bi bi-people-fill"></i></div>
      <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div>
          <div class="muted">عدد الطلاب</div>
          <div class="value">{{ number_format($stdTotal) }}</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <span class="badge bg-light text-dark px-3 py-2">مفعلون: {{ number_format($stdActive) }}</span>
          <span class="badge bg-dark px-3 py-2">موقوفون: {{ number_format($stdSuspended) }}</span>
          <span class="badge bg-white text-dark px-3 py-2 border">خريجون: {{ number_format($stdGrad) }}</span>
        </div>
      </div>
      <a class="stretched-link" href="{{ route('admin.users.index') }}"></a>
    </div>
  </div>
</div>


{{-- ===================== --}}
{{--    Notifications      --}}
{{-- ===================== --}}
<div class="row g-3 mt-1">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header bg-white d-flex align-items-center justify-content-between">
        <strong>الإشعارات والتنبيهات</strong>
        <span class="small text-muted">فحص سلامة البيانات</span>
      </div>
      <div class="card-body">
        <div class="row g-3">
          {{-- جامعات غير مفعّلة --}}
          <div class="col-md-4">
            <div class="alert alert-warning mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div><i class="bi bi-exclamation-triangle-fill"></i> جامعات غير مفعلة</div>
                <span class="badge bg-dark">{{ $inactiveUniCount }}</span>
              </div>
              @if($inactiveUniCount > 0)
                <ul class="mt-2 mb-0 small">
                  @foreach($inactiveUniversities as $n)<li>{{ $n }}</li>@endforeach
                </ul>
              @else
                <div class="small text-muted mt-2">لا يوجد.</div>
              @endif
            </div>
          </div>

          {{-- مواد بلا محتوى --}}
          <div class="col-md-4">
            <div class="alert alert-info mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div><i class="bi bi-folder-x"></i> مواد بلا محتوى</div>
                <span class="badge bg-dark">{{ $matNoContentCount }}</span>
              </div>
              @if($matNoContentCount > 0)
                <ul class="mt-2 mb-0 small">
                  @foreach($materialsWithoutContent as $n)<li>{{ $n }}</li>@endforeach
                </ul>
              @else
                <div class="small text-muted mt-2">لا يوجد.</div>
              @endif
            </div>
          </div>

          {{-- أقسام بلا دكاترة --}}
          <div class="col-md-4">
            <div class="alert alert-secondary mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div><i class="bi bi-diagram-3"></i> أقسام بلا دكاترة</div>
                <span class="badge bg-dark">{{ $majNoDoctorsCount }}</span>
              </div>
              @if($majNoDoctorsCount > 0)
                <ul class="mt-2 mb-0 small">
                  @foreach($majorsWithoutDoctors as $n)<li>{{ $n }}</li>@endforeach
                </ul>
              @else
                <div class="small text-muted mt-2">لا يوجد.</div>
              @endif
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

{{-- ================ --}}
{{--      Charts      --}}
{{-- ================ --}}
<div class="row g-3 mt-1">
  <div class="col-lg-6">
    <div class="card shadow-sm p-3 h-100">
      <div class="d-flex justify-content-between">
        <h6 class="mb-3">توزيع الطلاب على الجامعات (Top 10)</h6>
      </div>
      <canvas id="chartStudentsPerUni" height="180"></canvas>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card shadow-sm p-3 h-100">
      <h6 class="mb-3">نمو عدد الطلاب (آخر 12 شهرًا)</h6>
      <canvas id="chartStudentsMonthly" height="180"></canvas>
    </div>
  </div>
</div>

{{-- Pie Charts --}}
<div class="row g-3 mt-1">
  <div class="col-lg-6">
    <div class="card shadow-sm p-3 h-100 text-center">
      <h6 class="mb-3">توزيع الطلاب حسب الحالة</h6>
      <div class="d-flex justify-content-center">
        <canvas id="pieStudentsStatus" style="max-width: 280px; max-height: 280px;"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card shadow-sm p-3 h-100 text-center">
      <h6 class="mb-3">توزيع الطلاب حسب الجنس</h6>
      <div class="d-flex justify-content-center">
        <canvas id="pieStudentsGender" style="max-width: 280px; max-height: 280px;"></canvas>
      </div>
    </div>
  </div>
</div>


{{-- Latest Activity --}}
<div class="row g-3 mt-1">
  <div class="col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header bg-white"><strong>أحدث الطلاب</strong></div>
      <div class="table-responsive">
        <table class="table table-sm mb-0 align-middle">
          <thead class="table-light">
            <tr><th>الاسم</th><th>الرقم الأكاديمي</th><th>الجامعة</th><th>التاريخ</th></tr>
          </thead>
          <tbody>
            @forelse($latestStudents as $s)
              <tr>
                <td>{{ $s->name ?? '—' }}</td>
                <td class="text-muted">{{ $s->student_number ?? '—' }}</td>
                <td class="small text-muted">{{ optional($s->university)->name ?? '—' }}</td>
                <td class="small text-muted">{{ $s->created_at?->format('Y-m-d') }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted">—</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header bg-white"><strong>أحدث المحتوى</strong></div>
      <div class="table-responsive">
        <table class="table table-sm mb-0 align-middle">
          <thead class="table-light">
            <tr><th>العنوان</th><th>النوع</th><th>الجامعة</th><th>التاريخ</th></tr>
          </thead>
          <tbody>
            @forelse($latestContent as $c)
              <tr>
                <td>{{ $c->title }}</td>
                <td class="small">
                  @if($c->type==='file') <span class="badge bg-secondary">ملف</span>
                  @elseif($c->type==='video') <span class="badge bg-info text-dark">فيديو</span>
                  @else <span class="badge bg-light text-dark">رابط</span>
                  @endif
                </td>
                <td class="small text-muted">{{ optional($c->university)->name ?? '—' }}</td>
                <td class="small text-muted">{{ $c->created_at?->format('Y-m-d') }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted">—</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- ملخص سريع للجامعات --}}
<div class="card shadow-sm mt-3">
  <div class="card-header bg-white"><strong>ملخص سريع للجامعات</strong></div>
  <div class="table-responsive">
    <table class="table table-sm mb-0 align-middle">
      <thead class="table-light">
        <tr><th>الجامعة</th><th>الطلاب</th><th>الكليات</th><th>المواد</th></tr>
      </thead>
      <tbody>
        @forelse($universitiesQuick as $u)
          <tr>
            <td class="fw-semibold">{{ $u->name }}</td>
            <td class="text-muted">
              {{ number_format(\App\Models\User::where('university_id',$u->id)->count()) }}
            </td>
            <td class="text-muted">
              {{ number_format(\App\Models\College::where('university_id',$u->id)->count()) }}
            </td>
            <td class="text-muted">
              {{ number_format(\App\Models\Material::where('university_id',$u->id)->count()) }}
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted">—</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  // --- العموديات: الطلاب لكل جامعة
  const uniLabels = @json($studentsPerUniversity->pluck('uname')->map(fn($n)=>$n ?: '—'));
  const uniData   = @json($studentsPerUniversity->pluck('c'));

  new Chart(document.getElementById('chartStudentsPerUni'), {
    type: 'bar',
    data: { labels: uniLabels, datasets: [{ label: 'عدد الطلاب', data: uniData, borderWidth: 1 }] },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
  });

  // --- الخطّي: نمو الطلاب شهريًا
  const monthlyLabels = @json($studentsMonthly->pluck('ym'));
  const monthlyData   = @json($studentsMonthly->pluck('c'));

  new Chart(document.getElementById('chartStudentsMonthly'), {
    type: 'line',
    data: { labels: monthlyLabels, datasets: [{ label: 'طلاب جدد', data: monthlyData, tension: 0.3, fill: false, borderWidth: 2 }] },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
  });

  // --- Pie: الحالة
  const statusLabels = ['مفعل','موقوف','خريج'];
  const statusData   = [@json($pieStatus['active']), @json($pieStatus['suspended']), @json($pieStatus['graduated'])];

  new Chart(document.getElementById('pieStudentsStatus'), {
    type: 'pie',
    data: { labels: statusLabels, datasets: [{ data: statusData }] },
    options: { responsive: true }
  });

  // --- Pie: الجنس
  const genderLabels = ['ذكور','إناث'];
  const genderData   = [@json($pieGender['male']), @json($pieGender['female'])];

  new Chart(document.getElementById('pieStudentsGender'), {
    type: 'pie',
    data: { labels: genderLabels, datasets: [{ data: genderData }] },
    options: { responsive: true }
  });
</script>
@endpush
