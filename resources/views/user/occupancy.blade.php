@extends('layouts.dashboard')
@section('title', 'Data Okupansi')
@section('page-title', '🏨 Data Okupansi Hotel')

@section('content')

{{-- ── Overall Banner ── --}}
<div style="background:linear-gradient(135deg,rgba(124,58,237,0.12),rgba(236,72,153,0.08));border:1px solid var(--border-glow);border-radius:var(--radius-md);padding:1.5rem 2rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;" class="animate-fade-up">
  <div>
    <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:4px;">Total Okupansi Seluruh Properti</div>
    <div style="font-family:var(--font-display);font-size:2.8rem;font-weight:700;color:var(--text-primary);line-height:1;">
      {{ $overallStats['rate'] }}<span style="font-size:1.5rem;color:var(--purple-light);">%</span>
    </div>
    <div style="font-size:0.82rem;color:var(--text-muted);margin-top:4px;">Per hari ini · {{ now()->isoFormat('D MMMM YYYY') }}</div>
  </div>
  <div style="display:flex;gap:2.5rem;flex-wrap:wrap;">
    <div style="text-align:center;">
      <div style="font-family:var(--font-display);font-size:1.8rem;font-weight:700;color:#4ade80;">{{ $overallStats['total_terisi'] }}</div>
      <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">Kamar Terisi</div>
    </div>
    <div style="width:1px;background:var(--border-subtle);"></div>
    <div style="text-align:center;">
      <div style="font-family:var(--font-display);font-size:1.8rem;font-weight:700;color:#f87171;">{{ $overallStats['total_kosong'] }}</div>
      <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">Kamar Kosong</div>
    </div>
    <div style="width:1px;background:var(--border-subtle);"></div>
    <div style="text-align:center;">
      <div style="font-family:var(--font-display);font-size:1.8rem;font-weight:700;color:var(--purple-light);">{{ $overallStats['total_kamar'] }}</div>
      <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">Total Kamar</div>
    </div>
  </div>
</div>

{{-- ── Properti Cards ── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;margin-bottom:1.5rem;">
  @foreach($properties as $i => $p)
  @php
    $rateColor = $p['rate'] >= 80 ? '#4ade80' : ($p['rate'] >= 50 ? '#fbbf24' : '#f87171');
    $rateBg    = $p['rate'] >= 80 ? 'rgba(74,222,128,0.1)' : ($p['rate'] >= 50 ? 'rgba(251,191,36,0.1)' : 'rgba(248,113,113,0.1)');
    $rateGrad  = $p['rate'] >= 80 ? 'linear-gradient(90deg,#22c55e,#4ade80)' : ($p['rate'] >= 50 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
    $stars     = str_repeat('★', $p['bintang']) . str_repeat('☆', 5 - $p['bintang']);
  @endphp
  <div class="stat-card animate-fade-up" style="animation-delay:{{ $i * 0.08 }}s;">

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
      <div style="flex:1;min-width:0;">
        <div style="font-family:var(--font-display);font-size:0.95rem;font-weight:700;color:var(--text-primary);margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
          {{ $p['name'] }}
        </div>
        <div style="font-size:0.7rem;color:var(--text-muted);">
          📍 {{ $p['kota'] }} &nbsp;·&nbsp;
          <span style="color:#fbbf24;">{{ $stars }}</span>
        </div>
        <div style="margin-top:4px;">
          <span class="badge" style="font-size:0.62rem;background:rgba(124,58,237,0.1);color:var(--purple-glow);border:1px solid rgba(124,58,237,0.2);">
            {{ ucfirst($p['tipe'] ?? 'Hotel') }}
          </span>
        </div>
      </div>
      <div style="text-align:right;flex-shrink:0;">
        <div style="font-family:var(--font-display);font-size:1.8rem;font-weight:700;color:{{ $rateColor }};line-height:1;">{{ $p['rate'] }}%</div>
        <div style="font-size:0.7rem;color:var(--text-muted);">okupansi</div>
      </div>
    </div>

    {{-- Progress Bar --}}
    <div class="progress-bar" style="margin-bottom:8px;">
      <div class="progress-bar-fill" style="width:{{ $p['rate'] }}%;background:{{ $rateGrad }};"></div>
    </div>
    <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:var(--text-muted);margin-bottom:1rem;">
      <span style="color:{{ $rateColor }};font-weight:600;">{{ $p['occupied'] }} terisi</span>
      <span>{{ $p['total'] }} total kamar</span>
    </div>

    {{-- Revenue MTD --}}
    <div style="background:rgba(124,58,237,0.05);border-radius:var(--radius-sm);padding:10px 12px;margin-bottom:1rem;">
      <div style="font-size:0.7rem;color:var(--text-muted);margin-bottom:2px;">Revenue Bulan Ini</div>
      <div style="font-family:var(--font-display);font-size:1rem;font-weight:700;color:var(--accent-pink);">
        Rp {{ number_format($p['revenue_mtd'] / 1000000, 1, ',', '.') }} Jt
      </div>
    </div>

    {{-- Room Type Breakdown --}}
    @if(count($p['room_types']) > 0)
    <div>
      <div style="font-size:0.7rem;font-weight:600;color:var(--text-muted);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:8px;">Tipe Kamar</div>
      <div style="display:flex;flex-direction:column;gap:6px;">
        @foreach($p['room_types'] as $rt)
        <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.78rem;">
          <span style="color:var(--text-secondary);">{{ $rt['tipe'] }}</span>
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="color:var(--text-muted);">{{ $rt['terisi'] }}/{{ $rt['total'] }}</span>
            <span class="badge" style="font-size:0.6rem;padding:2px 8px;
              background:{{ $rt['tersedia'] > 0 ? 'rgba(34,197,94,0.1)' : 'rgba(239,68,68,0.1)' }};
              color:{{ $rt['tersedia'] > 0 ? '#4ade80' : '#f87171' }};
              border:1px solid {{ $rt['tersedia'] > 0 ? 'rgba(34,197,94,0.25)' : 'rgba(239,68,68,0.25)' }};">
              {{ $rt['tersedia'] > 0 ? $rt['tersedia'].' tersedia' : 'Full' }}
            </span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>
  @endforeach
</div>

{{-- ── Charts Row ── --}}
<div class="grid-aside" style="margin-bottom:1.25rem;">

  {{-- 30-day trend --}}
  <div class="chart-wrap animate-fade-up delay-2">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
      <div>
        <div class="chart-title" style="margin-bottom:2px;">Tren Okupansi — 30 Hari Terakhir</div>
        <div style="font-size:0.78rem;color:var(--text-muted);">Global (semua properti)</div>
      </div>
    </div>
    <div style="height:220px;"><canvas id="trend30Chart"></canvas></div>
  </div>

  {{-- Perbandingan properti --}}
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1.25rem;">Perbandingan Properti</div>
    <div style="height:220px;"><canvas id="occChart"></canvas></div>
  </div>

</div>

@push('scripts')
<script>
// ── 30-day trend ──
new Chart(document.getElementById('trend30Chart').getContext('2d'), {
  type: 'line',
  data: {
    labels: @json($trend30['labels']),
    datasets: [{
      data: @json($trend30['data']),
      borderColor: '#22d3ee',
      backgroundColor: 'rgba(34,211,238,0.08)',
      fill: true,
      tension: 0.4,
      pointRadius: 0,
      pointHoverRadius: 5,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: 'rgba(17,14,31,0.95)',
        borderColor: 'rgba(34,211,238,0.3)',
        borderWidth: 1,
        callbacks: { label: function(ctx){ return ' ' + ctx.parsed.y + '%'; } }
      }
    },
    scales: {
      x: { ticks: { color: '#5e5678', font: { size: 10 } }, grid: { color: 'rgba(124,58,237,0.08)' } },
      y: { ticks: { color: '#5e5678', callback: function(v){ return v + '%'; }, font: { size: 10 } }, grid: { color: 'rgba(124,58,237,0.08)' }, min: 0, max: 100 }
    }
  }
});

// ── Bar per properti ──
new Chart(document.getElementById('occChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: {!! json_encode(array_column($properties, 'name')) !!},
    datasets: [{
      data: {!! json_encode(array_column($properties, 'rate')) !!},
      backgroundColor: ['rgba(168,85,247,0.7)','rgba(236,72,153,0.7)','rgba(34,211,238,0.7)','rgba(245,158,11,0.7)'],
      borderRadius: 8,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: 'rgba(17,14,31,0.95)',
        borderColor: 'rgba(124,58,237,0.3)',
        borderWidth: 1,
        callbacks: { label: function(ctx){ return ' ' + ctx.parsed.y + '% ocupansi'; } }
      }
    },
    scales: {
      x: { ticks: { color: '#5e5678', font: { size: 10 } }, grid: { display: false } },
      y: { ticks: { color: '#5e5678', callback: function(v){ return v + '%'; }, font: { size: 10 } }, grid: { color: 'rgba(124,58,237,0.08)' }, max: 100 }
    }
  }
});
</script>
@endpush

@endsection