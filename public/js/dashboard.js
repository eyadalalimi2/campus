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
  const mkChart = (el, cfg) => {
    const C = window.Chart;
    if (!el || !C) return null;
    return new C(el.getContext ? el.getContext('2d') : el, cfg);
  };
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

  function renderCharts() {
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
    const distTarget = byId('chartReviewsDistribution');
    const fallbackBox = byId('chartReviewsFallback');
    const distLabels = payload.reviewsDistribution?.labels ?? ['1','2','3','4','5'];
    const distData = payload.reviewsDistribution?.data ?? [0,0,0,0,0];
    const distSum = (Array.isArray(distData) ? distData.reduce((a,b)=>a+Number(b||0),0) : 0);
    // حوّل الأرقام إلى نجوم لعرضها كعناوين للأعمدة
    const starLabels = (distLabels || []).map((l) => {
      const n = Number(l);
      return n > 0 ? '★'.repeat(n) : '—';
    });
    // ألوان مخصصة لكل عمود (من الأحمر إلى الأخضر/الأزرق)
    const barColors = ['#ef4444','#f59e0b','#fbbf24','#10b981','#3b82f6'];
    const drawFallbackBars = () => {
      if (!fallbackBox) return;
      const max = distData.reduce((m,v)=>Math.max(m, Number(v||0)),0) || 1;
      let html = '';
      distLabels.forEach((lab, idx) => {
        const val = Number(distData[idx] || 0);
        const pct = Math.round((val / max) * 100);
        html += `
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-light text-dark" style="min-width:48px">${starLabels[idx] || '—'}</span>
            <div class="flex-grow-1 progress" style="height:10px">
              <div class="progress-bar" role="progressbar" style="width:${pct}%;background-color:${barColors[idx]||'#888'}" aria-valuenow="${pct}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span class="text-muted small" style="min-width:28px;text-align:end">${nf(val)}</span>
          </div>`;
      });
      fallbackBox.innerHTML = html;
    };

    if (distTarget && distSum === 0) {
      drawFallbackBars();
    }
    const chart = mkChart(distTarget, {
    type: 'bar',
    data: {
      labels: starLabels,
      datasets: [{
        label: 'عدد التقييمات',
        data: distData,
        backgroundColor: barColors,
        borderColor: barColors,
        borderWidth: 1,
        borderRadius: 6,
      }]
    },
    options: Object.assign({}, baseOptions, {
      scales: {
        x: { grid: { display: false } },
        y: { beginAtZero: true, ticks: { callback: (v) => nf(v) }, grid: { color: '#f3f4f6' } }
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            title: (items) => {
              const i = items?.[0];
              return i ? (starLabels[i.dataIndex] || i.label) : '';
            },
            label: (ctx) => {
              const val = Number(ctx.parsed.y ?? ctx.parsed ?? 0);
              if (distSum > 0) {
                const pct = ((val / distSum) * 100).toFixed(1);
                return `عدد: ${nf(val)} (${pct}%)`;
              }
              return `عدد: ${nf(val)}`;
            }
          }
        }
      }
    })
    });
    if (!chart) {
      // لم تُحمّل Chart.js — استخدم fallback bars
      drawFallbackBars();
    } else if (fallbackBox) {
      // أخفِ fallback إن رُسم الرسم
      fallbackBox.innerHTML = '';
    }
  }

  // انتظر حتى تتوفر Chart.js (مع فترات إعادة محاولة قصيرة) ثم ارسم المخططات
  (function boot(){
  if (window.Chart) return void renderCharts();
    let tries = 0;
    const t = setInterval(function(){
      if (window.Chart) { clearInterval(t); renderCharts(); return; }
      if (++tries >= 20) { // ~3s
        clearInterval(t);
        // استخدم fallback bars بدل رسالة عامة
        renderCharts();
      }
    }, 150);
  })();
})();
