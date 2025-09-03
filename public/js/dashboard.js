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
  // أدوات مساعدة آمنة
  const byId = (id) => document.getElementById(id);
  const mkChart = (el, cfg) => {
    if (!el) return;
    return new Chart(el.getContext ? el.getContext('2d') : el, cfg);
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
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: { y: { beginAtZero: true } }
    }
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
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: { y: { beginAtZero: true } }
    }
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
        backgroundColor: ['#4CAF50', '#FF9800', '#2196F3'],
        borderColor: '#fff',
        borderWidth: 2,
        hoverOffset: 8
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom', labels: { font: { size: 13 } } } }
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
      plugins: { legend: { position: 'bottom', labels: { font: { size: 13 } } } }
    }
  });
})();
