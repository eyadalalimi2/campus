(function () {
  // قراءة JSON من الـBlade
  const dataTag = document.getElementById('dashboard-data');
  if (!dataTag) return;
  let payload = {};
  try {
    payload = JSON.parse(dataTag.textContent || '{}');
  } catch (e) {
    console.error('Invalid dashboard JSON payload', e);
    return;
  }

  // أدوات مساعدة
  const byId = (id) => document.getElementById(id);
  const mkChart = (el, cfg) => (el ? new Chart(el.getContext ? el.getContext('2d') : el, cfg) : null);
  const nf = (n) => {
    const v = Number(n);
    if (!isFinite(v)) return '0';
    try { return new Intl.NumberFormat('ar-EG').format(v); } catch { return String(v); }
  };

  // إعدادات عامة (اتجاه عربي عند الحاجة)
  const baseOptions = {
    responsive: true,
    maintainAspectRatio: false,
    locale: 'ar',
    layout: { padding: 0 },
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: (ctx) => `${ctx.dataset.label || ''}${ctx.dataset.label ? ': ' : ''}${nf(ctx.parsed.y ?? ctx.parsed)}`
        }
      }
    },
    scales: { y: { beginAtZero: true, ticks: { callback: (v) => nf(v) } } }
  };

  // === عمودي: الطلاب لكل جامعة ===
  mkChart(byId('chartStudentsPerUni'), {
    type: 'bar',
    data: {
      labels: payload.studentsPerUniversity?.labels ?? [],
      datasets: [{
        label: 'عدد الطلاب',
        data: payload.studentsPerUniversity?.data ?? [],
        borderWidth: 1
      }]
    },
    options: baseOptions
  });

  // === عمودي: الطلاب لكل فرع (جديد) ===
  mkChart(byId('chartStudentsPerBranch'), {
    type: 'bar',
    data: {
      labels: payload.studentsPerBranch?.labels ?? [],
      datasets: [{
        label: 'عدد الطلاب',
        data: payload.studentsPerBranch?.data ?? [],
        borderWidth: 1
      }]
    },
    options: baseOptions
  });

  // === خطّي: نمو الطلاب شهريًا ===
  mkChart(byId('chartStudentsMonthly'), {
    type: 'line',
    data: {
      labels: payload.studentsMonthly?.labels ?? [],
      datasets: [{
        label: 'طلاب جدد',
        data: payload.studentsMonthly?.data ?? [],
        tension: 0.3,
        fill: false,
        borderWidth: 2,
        pointRadius: 2
      }]
    },
    options: baseOptions
  });

  // === Pie: الحالة ===
  const statusArr = [
    payload.pieStatus?.active ?? 0,
    payload.pieStatus?.suspended ?? 0,
    payload.pieStatus?.graduated ?? 0
  ];
  mkChart(byId('pieStudentsStatus'), {
    type: 'pie',
    data: {
      labels: ['مفعل','موقوف','خريج'],
      datasets: [{
        data: statusArr,
        // ألوان افتراضية بسيطة؛ يمكن تخصيصها من CSS متى شئت
        backgroundColor: ['#4CAF50', '#FF9800', '#2196F3'],
        borderColor: '#fff',
        borderWidth: 2,
        hoverOffset: 8
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom', labels: { font: { size: 13 } } },
        tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${nf(ctx.parsed)}` } }
      }
    }
  });

  // === Pie: الجنس ===
  const genderArr = [
    payload.pieGender?.male ?? 0,
    payload.pieGender?.female ?? 0
  ];
  mkChart(byId('pieStudentsGender'), {
    type: 'pie',
    data: {
      labels: ['ذكور','إناث'],
      datasets: [{
        data: genderArr,
        backgroundColor: ['#2196F3', '#E91E63'],
        borderColor: '#fff',
        borderWidth: 2,
        hoverOffset: 8
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom', labels: { font: { size: 13 } } },
        tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${nf(ctx.parsed)}` } }
      }
    }
  });

  // === عمودي: توزيع تقييمات التطبيق (1..5) ===
  mkChart(byId('chartReviewsDistribution'), {
    type: 'bar',
    data: {
      labels: payload.reviewsDistribution?.labels ?? ['1','2','3','4','5'],
      datasets: [{
        label: 'عدد التقييمات',
        data: payload.reviewsDistribution?.data ?? [0,0,0,0,0],
        backgroundColor: '#f59e0b',
        borderColor: '#f59e0b',
        borderWidth: 1,
        borderRadius: 6,
      }]
    },
    options: Object.assign({}, baseOptions, {
      scales: {
        x: { grid: { display: false } },
        y: { beginAtZero: true, ticks: { callback: (v) => nf(v) }, grid: { color: '#f3f4f6' } }
      }
    })
  });
})();
