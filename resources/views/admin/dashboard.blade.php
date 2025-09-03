@extends('admin.layouts.app')
@section('title','لوحة البيانات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">لوحة البيانات</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus"></i> إضافة طالب</a>
    <a href="{{ route('admin.contents.create') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-folder-plus"></i> إضافة محتوى</a>
    <a href="{{ route('admin.import.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-upload"></i> الاستيراد</a>
  </div>
</div>

{{-- KPIs --}}
<div class="row g-3">
  <div class="col-6 col-md-3">
    <div class="card shadow-sm p-3 text-center h-100">
      <div class="text-muted small">إجمالي الطلاب</div>
      <div class="display-6 fw-bold text-primary">{{ number_format($totalStudents) }}</div>
      <div class="small">
        @if(!is_null($studentsDeltaPct))
          @if($studentsDeltaPct >= 0)
            <span class="text-success">+{{ $studentsDeltaPct }}% خلال 30 يوم</span>
          @else
            <span class="text-danger">{{ $studentsDeltaPct }}% خلال 30 يوم</span>
          @endif
        @else
          <span class="text-muted">—</span>
        @endif
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm p-3 text-center h-100">
      <div class="text-muted small">الجامعات</div>
      <div class="display-6 fw-bold">{{ number_format($totalUniversities) }}</div>
      <a href="{{ route('admin.universities.index') }}" class="stretched-link"></a>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm p-3 text-center h-100">
      <div class="text-muted small">الكليات</div>
      <div class="display-6 fw-bold">{{ number_format($totalColleges) }}</div>
      <a href="{{ route('admin.colleges.index') }}" class="stretched-link"></a>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm p-3 text-center h-100">
      <div class="text-muted small">التخصصات</div>
      <div class="display-6 fw-bold">{{ number_format($totalMajors) }}</div>
      <a href="{{ route('admin.majors.index') }}" class="stretched-link"></a>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card shadow-sm p-3 text-center h-100">
      <div class="text-muted small">المواد</div>
      <div class="display-6 fw-bold">{{ number_format($totalMaterials) }}</div>
      <div class="small text-muted">مفعل: {{ number_format($activeMaterials) }}</div>
      <a href="{{ route('admin.materials.index') }}" class="stretched-link"></a>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm p-3 text-center h-100">
      <div class="text-muted small">الدكاترة</div>
      <div class="display-6 fw-bold">{{ number_format($totalDoctors) }}</div>
      <div class="small text-muted">مفعل: {{ number_format($activeDoctors) }}</div>
      <a href="{{ route('admin.doctors.index') }}" class="stretched-link"></a>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm p-3 text-center h-100">
      <div class="text-muted small">الأجهزة/المهام</div>
      <div class="display-6 fw-bold">{{ number_format($totalDevices) }}</div>
      <div class="small text-muted">مفعل: {{ number_format($activeDevices) }}</div>
      <a href="{{ route('admin.devices.index') }}" class="stretched-link"></a>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm p-3 text-center h-100">
      <div class="text-muted small">المحتوى التعليمي</div>
      <div class="display-6 fw-bold">{{ number_format($totalContents) }}</div>
      <div class="small text-muted">
        ملفات: {{ $cntFile }} / فيديو: {{ $cntVideo }} / روابط: {{ $cntLink }}
      </div>
      <a href="{{ route('admin.contents.index') }}" class="stretched-link"></a>
    </div>
  </div>
</div>

{{-- Charts --}}
<div class="row g-3 mt-1">
  <div class="col-lg-6">
    <div class="card shadow-sm p-3 h-100">
      <div class="d-flex justify-content-between">
        <h6 class="mb-3">توزيع الطلاب على الجامعات (Top 10)</h6>
        <span class="text-muted small">الذكور: {{ $maleCount }} / الإناث: {{ $femaleCount }}</span>
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

{{-- Universities Quick Summary --}}
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
  // بيانات الرسم: الطلاب لكل جامعة (Top 10)
  const uniLabels = @json($studentsPerUniversity->pluck('uname')->map(fn($n)=>$n ?: '—'));
  const uniData   = @json($studentsPerUniversity->pluck('c'));

  new Chart(document.getElementById('chartStudentsPerUni'), {
    type: 'bar',
    data: {
      labels: uniLabels,
      datasets: [{
        label: 'عدد الطلاب',
        data: uniData,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });

  // بيانات الرسم: نمو الطلاب شهريًا
  const monthlyLabels = @json($studentsMonthly->pluck('ym'));
  const monthlyData   = @json($studentsMonthly->pluck('c'));

  new Chart(document.getElementById('chartStudentsMonthly'), {
    type: 'line',
    data: {
      labels: monthlyLabels,
      datasets: [{
        label: 'طلاب جدد',
        data: monthlyData,
        tension: 0.3,
        fill: false,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });
</script>
@endpush
