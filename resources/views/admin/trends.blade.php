@extends('layouts.dashboard')
@section('title', 'Seasonal Trend')
@section('page-title', '📈 Analisis Seasonal Trend')

@section('content')

{{-- Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
  @foreach([
    ['Peak Season','Jun — Agt, Okt — Des','Okupansi rata-rata 92%','#4ade80'],
    ['Shoulder Season','Mar — Mei, Sep','Okupansi rata-rata 78%','#fbbf24'],
    ['Low Season','Jan — Feb','Okupansi rata-rata 64%','#f87171'],
    ['Best Month','Juli 2024','Tertinggi: 96.2%','#a855f7'],
  ] as $s)
  <div class="stat-card animate-fade-up" style="animation-delay:{{ $loop->index * 0.08 }}s;border-color:{{ $s[3] }}33;">
    <div style="width:8px;height:8px;border-radius:50%;background:{{ $s[3] }};margin-bottom:0.75rem;box-shadow:0 0 8px {{ $s[3] }};"></div>
    <div class="stat-label">{{ $s[0] }}</div>
    <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;color:var(--text-primary);margin:6px 0 4px;">{{ $s[1] }}</div>
    <div style="font-size:0.78rem;color:var(--text-muted);">{{ $s[2] }}</div>
  </div>
  @endforeach
</div>

{{-- Main Trend Chart --}}
<div class="chart-wrap animate-fade-up delay-2" style="margin-bottom:1.25rem;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
    <div>
      <div class="chart-title">Tren Okupansi & Revenue — Perbandingan Tahunan</div>
      <div style="font-size:0.78rem;color:var(--text-muted);">Semua properti NHG gabungan</div>
    </div>
    <div style="display:flex;gap:8px;" id="yearBtns">
      <button class="btn btn-outline btn-sm year-btn active-year" data-year="2024" onclick="switchYear(2024)">2024</button>
      <button class="btn btn-outline btn-sm year-btn" data-year="2023" onclick="switchYear(2023)">2023</button>
      <button class="btn btn-outline btn-sm year-btn" data-year="2022" onclick="switchYear(2022)">2022</button>
    </div>
  </div>
  <div style="height:280px;"><canvas id="seasonMainChart"></canvas></div>
</div>

{{-- Charts Row --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1rem;">Pola Weekend vs Weekday</div>
    <div style="height:220px;"><canvas id="weekdayChart"></canvas></div>
  </div>
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1rem;">Lead Time Booking</div>
    <div style="height:220px;"><canvas id="leadTimeChart"></canvas></div>
  </div>
</div>

{{-- Quarterly Table --}}
<div class="chart-wrap animate-fade-up delay-4">
  <div class="chart-title" style="margin-bottom:1.25rem;">Analisis Seasonal per Kuartal</div>
  <div style="overflow-x:auto;">
    <table class="data-table">
      <thead>
        <tr>
          <th>Kuartal</th><th>Musim</th><th>Avg Okupansi</th>
          <th>Total Booking</th><th>ADR</th><th>RevPAR</th>
          <th>Total Revenue</th><th>YoY Growth</th>
        </tr>
      </thead>
      <tbody>
        @php
        $quarters = [
          ['Q1 2024','Low / Shoulder','71.2%','3.842','Rp 780.000','Rp 555.000','Rp 2,1 M','+6.2%',true],
          ['Q2 2024','Shoulder / Peak','82.5%','4.560','Rp 890.000','Rp 734.000','Rp 2,8 M','+11.4%',true],
          ['Q3 2024','Peak','93.1%','5.128','Rp 1.050.000','Rp 978.000','Rp 3,7 M','+15.8%',true],
          ['Q4 2024','Peak / Shoulder','88.4%','4.920','Rp 980.000','Rp 866.000','Rp 3,2 M','+9.7%',true],
        ];
        $seasonColor = [
          'Low / Shoulder'   => '#f87171',
          'Shoulder / Peak'  => '#fbbf24',
          'Peak'             => '#4ade80',
          'Peak / Shoulder'  => '#4ade80',
        ];
        @endphp
        @foreach($quarters as [$q,$s,$occ,$bk,$adr,$revpar,$rev,$yoy,$up])
        <tr>
          <td style="color:var(--text-primary);font-weight:700;">{{ $q }}</td>
          <td><span style="color:{{ $seasonColor[$s] ?? '#a89fc8' }};font-weight:600;font-size:0.82rem;">{{ $s }}</span></td>
          <td style="color:var(--purple-light);font-weight:700;">{{ $occ }}</td>
          <td>{{ $bk }}</td>
          <td>{{ $adr }}</td>
          <td>{{ $revpar }}</td>
          <td style="color:var(--text-primary);font-weight:600;">{{ $rev }}</td>
          <td style="color:{{ $up ? '#4ade80' : '#f87171' }};font-weight:700;">
            {{ $up ? '▲' : '▼' }} {{ $yoy }}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
<script>
const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
const yearData = {
  2024: { occ:[64,68,72,82,88,92,96,93,85,88,91,87], rev:[1.8,1.9,2.1,2.6,2.8,3.1,3.7,3.4,2.9,3.0,3.2,3.0] },
  2023: { occ:[58,62,67,76,83,87,91,88,80,83,86,82], rev:[1.5,1.6,1.8,2.2,2.4,2.7,3.2,3.0,2.5,2.7,2.9,2.7] },
  2022: { occ:[50,55,60,70,76,80,84,80,73,76,80,75], rev:[1.2,1.3,1.5,1.8,2.0,2.2,2.6,2.4,2.1,2.2,2.4,2.2] },
};

let mainChart;

function buildMain(yr) {
  const d = yearData[yr];
  if (mainChart) mainChart.destroy();
  mainChart = new Chart(document.getElementById('seasonMainChart').getContext('2d'), {
    type: 'line',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Okupansi (%)',
          data: d.occ,
          borderColor: '#a855f7',
          backgroundColor: 'rgba(168,85,247,0.08)',
          fill: true, tension: 0.4,
          pointRadius: 5, pointBackgroundColor: '#a855f7',
          yAxisID: 'y'
        },
        {
          label: 'Revenue (Rp M)',
          data: d.rev,
          borderColor: '#22d3ee',
          backgroundColor: 'rgba(34,211,238,0.05)',
          fill: true, tension: 0.4,
          pointRadius: 5, pointBackgroundColor: '#22d3ee',
          yAxisID: 'y1'
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: { legend: { display: true, labels: { color: '#a89fc8', font: { size: 11 } } } },
      scales: {
        x:  { ticks: { color: '#5e5678' }, grid: { color: 'rgba(124,58,237,0.08)' } },
        y:  { type:'linear', position:'left',  ticks: { color:'#5e5678', callback: v => v+'%' }, grid: { color:'rgba(124,58,237,0.08)' }, min:40, max:100 },
        y1: { type:'linear', position:'right', ticks: { color:'#5e5678', callback: v => 'Rp '+v+'M' }, grid: { display:false } }
      }
    }
  });
}

function switchYear(yr) {
  buildMain(yr);
  document.querySelectorAll('.year-btn').forEach(b => {
    b.style.color = '';
    b.style.borderColor = '';
  });
  const active = document.querySelector(`[data-year="${yr}"]`);
  active.style.color = 'var(--purple-light)';
  active.style.borderColor = 'var(--purple-light)';
}

buildMain(2024);

// Weekend vs Weekday
new Chart(document.getElementById('weekdayChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: months,
    datasets: [
      { label:'Weekday', data:[58,62,65,75,80,84,88,85,78,82,85,80], backgroundColor:'rgba(124,58,237,0.7)', borderRadius:4 },
      { label:'Weekend', data:[75,79,82,92,98,99,99,98,92,95,97,94], backgroundColor:'rgba(236,72,153,0.7)', borderRadius:4 },
    ]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ display:true, labels:{ color:'#a89fc8', font:{size:10} } } },
    scales:{
      x:{ ticks:{ color:'#5e5678', font:{size:9} }, grid:{ display:false } },
      y:{ ticks:{ color:'#5e5678', callback:v=>v+'%' }, grid:{ color:'rgba(124,58,237,0.08)' }, max:100 }
    }
  }
});

// Lead Time
new Chart(document.getElementById('leadTimeChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ['0-3 hari','4-7 hari','1-2 minggu','3-4 minggu','1-2 bulan','2-3 bulan','>3 bulan'],
    datasets: [{
      data: [280,420,890,1240,1580,820,340],
      backgroundColor: 'rgba(34,211,238,0.7)',
      borderRadius: 4,
      borderSkipped: false
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ display:false } },
    scales:{
      x:{ ticks:{ color:'#5e5678', font:{size:9} }, grid:{ display:false } },
      y:{ ticks:{ color:'#5e5678' }, grid:{ color:'rgba(124,58,237,0.08)' } }
    }
  }
});
</script>
@endpush

@endsection