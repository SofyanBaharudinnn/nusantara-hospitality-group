@extends('layouts.dashboard')
@section('title', 'Tren Musiman')
@section('page-title', '📈 Analisis Tren Musiman')

@section('content')

{{-- ── Insight Banner ── --}}
<div class="grid-responsive-3" style="margin-bottom:1.5rem;">
  <div style="background:linear-gradient(135deg,rgba(74,222,128,0.1),rgba(74,222,128,0.03));border:1px solid rgba(74,222,128,0.2);border-radius:var(--radius-md);padding:1.25rem;" class="animate-fade-up">
    <div style="font-size:0.72rem;font-weight:600;color:#4ade80;letter-spacing:0.08em;text-transform:uppercase;margin-bottom:6px;">🏆 Bulan Terbaik</div>
    <div style="font-family:var(--font-display);font-size:1.25rem;font-weight:700;color:var(--text-primary);">{{ $insight['best_month'] }}</div>
    <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">Tingkat ocupansi tertinggi</div>
  </div>
  <div style="background:linear-gradient(135deg,rgba(168,85,247,0.1),rgba(168,85,247,0.03));border:1px solid rgba(168,85,247,0.2);border-radius:var(--radius-md);padding:1.25rem;" class="animate-fade-up delay-1">
    <div style="font-size:0.72rem;font-weight:600;color:var(--purple-light);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:6px;">📊 Rata-rata Tahunan</div>
    <div style="font-family:var(--font-display);font-size:1.25rem;font-weight:700;color:var(--text-primary);">{{ $insight['avg_annual'] }}</div>
    <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">Rata-rata ocupansi {{ now()->year }}</div>
  </div>
  <div style="background:linear-gradient(135deg,rgba(248,113,113,0.1),rgba(248,113,113,0.03));border:1px solid rgba(248,113,113,0.2);border-radius:var(--radius-md);padding:1.25rem;" class="animate-fade-up delay-2">
    <div style="font-size:0.72rem;font-weight:600;color:#f87171;letter-spacing:0.08em;text-transform:uppercase;margin-bottom:6px;">📉 Bulan Terendah</div>
    <div style="font-family:var(--font-display);font-size:1.25rem;font-weight:700;color:var(--text-primary);">{{ $insight['worst_month'] }}</div>
    <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">Perlu strategi peningkatan</div>
  </div>
</div>

{{-- ── Season Cards ── --}}
<div class="grid-responsive-3" style="margin-bottom:1.5rem;">
  @foreach($seasons as $i => $s)
  @php
    $colorMap = ['#f87171' => 'rgba(248,113,113', '#fbbf24' => 'rgba(251,191,36', '#4ade80' => 'rgba(74,222,128'];
    $base = $colorMap[$s['color']] ?? 'rgba(168,85,247';
  @endphp
  <div class="stat-card animate-fade-up" style="animation-delay:{{ $i * 0.1 }}s;border-color:{{ $s['color'] }}33;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">{{ $s['name'] }}</div>
      <span style="font-size:1.3rem;">{{ ['❄️','🌤️','☀️'][$i] }}</span>
    </div>
    <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:{{ $s['color'] }};">
      {{ $s['avg'] }}
    </div>
    <div style="font-size:0.82rem;color:var(--text-muted);margin-top:4px;">Avg. Ocupansi</div>
    <div style="margin-top:8px;padding-top:8px;border-top:1px solid var(--border-subtle);">
      <span style="font-size:0.75rem;color:var(--text-secondary);">📅 {{ $s['months'] }}</span>
    </div>
  </div>
  @endforeach
</div>

{{-- ── Quarters ── --}}
<div class="grid-responsive-4" style="margin-bottom:1.5rem;">
  @foreach($quarters as $i => $q)
  <div class="chart-wrap animate-fade-up" style="animation-delay:{{ $i * 0.08 }}s;">
    <div style="text-align:center;margin-bottom:0.75rem;">
      <div style="font-size:1.5rem;margin-bottom:4px;">{{ $q['icon'] }}</div>
      <div style="font-family:var(--font-display);font-size:0.9rem;font-weight:700;color:var(--text-primary);">{{ $q['label'] }}</div>
      <div style="font-size:0.7rem;color:var(--text-muted);">{{ $q['months'] }}</div>
    </div>
    <div style="display:flex;flex-direction:column;gap:8px;padding-top:8px;border-top:1px solid var(--border-subtle);">
      <div style="display:flex;justify-content:space-between;font-size:0.78rem;">
        <span style="color:var(--text-muted);">Avg. Occ</span>
        <span style="color:var(--purple-light);font-weight:600;">{{ $q['avg_occ'] }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:0.78rem;">
        <span style="color:var(--text-muted);">Booking</span>
        <span style="color:var(--text-primary);font-weight:600;">{{ $q['count'] }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:0.78rem;">
        <span style="color:var(--text-muted);">Revenue</span>
        <span style="color:var(--accent-pink);font-weight:600;">{{ $q['revenue'] }}</span>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- ── Dual-Axis Chart ── --}}
<div class="chart-wrap animate-fade-up delay-2" style="margin-bottom:1.25rem;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
    <div>
      <div class="chart-title" style="margin-bottom:2px;">Tren Bulanan {{ now()->year }} — Booking & Ocupansi</div>
      <div style="font-size:0.78rem;color:var(--text-muted);">Bar = jumlah booking · Line = % ocupansi</div>
    </div>
    <div style="display:flex;gap:1rem;font-size:0.78rem;">
      <span style="display:flex;align-items:center;gap:6px;color:var(--text-muted);">
        <span style="width:16px;height:3px;background:#a855f7;border-radius:2px;display:inline-block;"></span> Booking
      </span>
      <span style="display:flex;align-items:center;gap:6px;color:var(--text-muted);">
        <span style="width:16px;height:3px;background:#22d3ee;border-radius:2px;display:inline-block;"></span> Ocupansi %
      </span>
    </div>
  </div>
  <div style="height:280px;"><canvas id="dualChart"></canvas></div>
</div>

{{-- ── Tabel Per Bulan ── --}}
<div class="chart-wrap animate-fade-up delay-3">
  <div class="chart-title" style="margin-bottom:1.25rem;">📅 Ringkasan Per Bulan — {{ now()->year }}</div>
  <div class="table-responsive">
    <table class="data-table">
      <thead>
        <tr>
          <th>Bulan</th>
          <th>Booking</th>
          <th>Ocupansi</th>
          <th>Revenue</th>
          <th>Kategori Musim</th>
        </tr>
      </thead>
      <tbody>
        @foreach($monthTable as $row)
        <tr>
          <td style="font-weight:600;color:var(--text-primary);">{{ $row['month'] }}</td>
          <td style="color:var(--purple-light);font-weight:600;">{{ number_format($row['bookings'], 0, ',', '.') }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:60px;background:rgba(255,255,255,0.06);border-radius:3px;height:5px;overflow:hidden;">
                <div style="width:{{ $row['occ'] }}%;height:100%;background:
                  {{ $row['occ'] >= 80 ? 'linear-gradient(90deg,#22c55e,#4ade80)' : ($row['occ'] >= 50 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)') }};
                  border-radius:3px;"></div>
              </div>
              <span style="font-weight:600;color:
                {{ $row['occ'] >= 80 ? '#4ade80' : ($row['occ'] >= 50 ? '#fbbf24' : '#f87171') }};">
                {{ $row['occ'] }}%
              </span>
            </div>
          </td>
          <td style="color:var(--accent-pink);font-weight:600;">
            Rp {{ number_format($row['revenue'] / 1000000, 1, ',', '.') }} Jt
          </td>
          <td>
            <span class="badge" style="font-size:0.65rem;background:transparent;
              color:{{ $row['season']['color'] }};border:1px solid {{ $row['season']['color'] }}55;">
              {{ $row['season']['label'] }}
            </span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
<script>
const monthLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
const occupancyData  = @json($monthlyData['data']);
const bookingData    = @json($bookingCountData);

// ── Dual-axis Chart ──
new Chart(document.getElementById('dualChart').getContext('2d'), {
  data: {
    labels: monthLabels,
    datasets: [
      {
        type: 'bar',
        label: 'Jumlah Booking',
        data: bookingData,
        backgroundColor: 'rgba(168,85,247,0.5)',
        borderRadius: 6,
        yAxisID: 'yBooking',
      },
      {
        type: 'line',
        label: 'Ocupansi %',
        data: occupancyData,
        borderColor: '#22d3ee',
        backgroundColor: 'rgba(34,211,238,0.06)',
        fill: true,
        tension: 0.4,
        pointRadius: 5,
        pointBackgroundColor: '#22d3ee',
        pointBorderColor: '#0a0812',
        pointBorderWidth: 2,
        yAxisID: 'yOcc',
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: 'rgba(17,14,31,0.95)',
        borderColor: 'rgba(124,58,237,0.3)',
        borderWidth: 1,
      }
    },
    scales: {
      x: { ticks: { color: '#5e5678' }, grid: { color: 'rgba(124,58,237,0.08)' } },
      yBooking: {
        type: 'linear',
        position: 'left',
        ticks: { color: '#a855f7', font: { size: 11 } },
        grid: { color: 'rgba(124,58,237,0.08)' },
        title: { display: true, text: 'Booking', color: '#a855f7', font: { size: 11 } },
      },
      yOcc: {
        type: 'linear',
        position: 'right',
        min: 0, max: 100,
        ticks: { color: '#22d3ee', callback: function(v){ return v + '%'; }, font: { size: 11 } },
        grid: { drawOnChartArea: false },
        title: { display: true, text: 'Ocupansi %', color: '#22d3ee', font: { size: 11 } },
      }
    }
  }
});
</script>
@endpush

@endsection