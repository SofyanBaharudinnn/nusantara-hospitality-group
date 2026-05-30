@extends('layouts.dashboard')
@section('title', 'Dashboard Saya')
@section('page-title', '📊 Dashboard Saya')

@section('content')

{{-- ── Greeting ── --}}
<div style="margin-bottom:1.75rem;">
  <div style="font-family:var(--font-display);font-size:1.3rem;font-weight:700;color:var(--text-primary);margin-bottom:4px;">
    Selamat datang, <span style="background:linear-gradient(135deg,var(--purple-light),var(--accent-pink));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ Auth::user()->name }}</span> 👋
  </div>
  <div style="font-size:0.83rem;color:var(--text-muted);">
    {{ $summary['display_date'] }} · Data diperbarui secara real-time
  </div>
</div>

{{-- ── 4 Stat Cards ── --}}
<div class="grid-responsive-4" style="margin-bottom:2rem;">

  <div class="stat-card animate-fade-up">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">Okupansi Hari Ini</div>
      <div style="width:36px;height:36px;background:linear-gradient(135deg,rgba(168,85,247,0.2),rgba(168,85,247,0.05));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">🏨</div>
    </div>
    <div class="stat-value">{{ $summary['occupancy_today'] }}%</div>
    <div style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;">
      {{ $summary['active_guests'] }} kamar terisi dari semua properti
    </div>
    <div style="margin-top:10px;">
      <div class="progress-bar">
        <div class="progress-bar-fill" style="width:{{ $summary['occupancy_today'] }}%;"></div>
      </div>
    </div>
  </div>

  <div class="stat-card animate-fade-up delay-1">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">Revenue Bulan Ini</div>
      <div style="width:36px;height:36px;background:linear-gradient(135deg,rgba(236,72,153,0.2),rgba(236,72,153,0.05));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">💰</div>
    </div>
    <div class="stat-value" style="font-size:1.7rem;">
      Rp {{ number_format($summary['revenue_mtd'] / 1000000, 1, ',', '.') }}Jt
    </div>
    <div style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;">
      {{ $summary['display_month'] }}
    </div>
  </div>

  <div class="stat-card animate-fade-up delay-2">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">Booking Bulan Ini</div>
      <div style="width:36px;height:36px;background:linear-gradient(135deg,rgba(34,211,238,0.2),rgba(34,211,238,0.05));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">📋</div>
    </div>
    <div class="stat-value">{{ $summary['total_bookings_mtd'] }}</div>
    <div style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;">Total reservasi aktif & pending</div>
  </div>

  <div class="stat-card animate-fade-up delay-3">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">Avg. Rating Tamu</div>
      <div style="width:36px;height:36px;background:linear-gradient(135deg,rgba(245,158,11,0.2),rgba(245,158,11,0.05));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">⭐</div>
    </div>
    <div class="stat-value" style="color:var(--accent-gold);">{{ $summary['avg_rating'] }}</div>
    <div style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;">Dari semua ulasan tamu</div>
  </div>

</div>

{{-- ── Row: Chart Area + Donut ── --}}
<div class="grid-aside" style="margin-bottom:2rem;">

  {{-- Chart 7 hari --}}
  <div class="chart-wrap animate-fade-up delay-2">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
      <div>
        <div class="chart-title" style="margin-bottom:2px;">Tren Okupansi — 7 Hari Terakhir</div>
        <div style="font-size:0.78rem;color:var(--text-muted);">Persentase kamar terisi per hari</div>
      </div>
      <span class="badge badge-purple">Live</span>
    </div>
    <div style="height:220px;"><canvas id="weekChart"></canvas></div>
  </div>

  {{-- Donut Status Booking --}}
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:4px;">Status Booking</div>
    <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:1.25rem;">Distribusi bulan ini</div>
    <div style="height:160px;"><canvas id="statusDonut"></canvas></div>
    <div style="margin-top:1rem;display:flex;flex-direction:column;gap:8px;">
      @php
        $statusItems = [
          ['Confirmed', $bookingStatusDist['confirmed'], '#4ade80'],
          ['Completed', $bookingStatusDist['completed'], '#a855f7'],
          ['Pending',   $bookingStatusDist['pending'],   '#fbbf24'],
          ['Cancelled', $bookingStatusDist['cancelled'], '#f87171'],
        ];
        $statusTotal = array_sum($bookingStatusDist);
      @endphp
      @foreach($statusItems as [$label, $count, $color])
      <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.8rem;">
        <span style="display:flex;align-items:center;gap:8px;color:var(--text-secondary);">
          <span style="width:8px;height:8px;border-radius:2px;background:{{ $color }};display:inline-block;flex-shrink:0;"></span>
          {{ $label }}
        </span>
        <span style="font-weight:600;color:var(--text-primary);">{{ $count }}</span>
      </div>
      @endforeach
    </div>
  </div>

</div>

{{-- ── Row: Top Properti + Reservasi Terbaru ── --}}
<div class="grid-aside-rev" style="margin-bottom:1.25rem;">

  {{-- Top 3 Properti --}}
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1.25rem;">🏆 Top Properti Hari Ini</div>
    <div style="display:flex;flex-direction:column;gap:12px;">
      @forelse($topHotels as $i => $h)
      <div style="background:rgba(124,58,237,0.05);border:1px solid var(--border-subtle);border-radius:var(--radius-sm);padding:14px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
          <div>
            <div style="font-size:0.85rem;font-weight:600;color:var(--text-primary);margin-bottom:2px;">
              @if($i === 0) 🥇 @elseif($i === 1) 🥈 @else 🥉 @endif
              {{ $h['nama'] }}
            </div>
            <div style="font-size:0.72rem;color:var(--text-muted);">📍 {{ $h['kota'] }} · {{ $h['bintang'] }}★</div>
          </div>
          <span style="font-family:var(--font-display);font-weight:700;font-size:1rem;
            color:{{ $h['rate'] >= 80 ? '#4ade80' : ($h['rate'] >= 50 ? '#fbbf24' : '#f87171') }};">
            {{ $h['rate'] }}%
          </span>
        </div>
        <div class="progress-bar">
          <div class="progress-bar-fill" style="width:{{ $h['rate'] }}%;
            background:{{ $h['rate'] >= 80 ? 'linear-gradient(90deg,#22c55e,#4ade80)' : ($h['rate'] >= 50 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)') }};"></div>
        </div>
        <div style="font-size:0.72rem;color:var(--text-muted);margin-top:6px;">{{ $h['occupied'] }}/{{ $h['total'] }} kamar terisi</div>
      </div>
      @empty
      <div style="text-align:center;color:var(--text-muted);padding:1rem;font-size:0.85rem;">Belum ada data properti.</div>
      @endforelse
    </div>
  </div>

  {{-- Reservasi Terbaru --}}
  <div class="chart-wrap animate-fade-up delay-4">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
      <div class="chart-title">Reservasi Terbaru</div>
      <a href="{{ route('user.occupancy') }}" class="btn btn-outline btn-sm">Lihat Okupansi →</a>
    </div>
    <div style="overflow-x:auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Tamu</th>
            <th>Properti</th>
            <th>Check-in</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentBookings as $b)
          <tr>
            <td style="color:var(--purple-light);font-weight:600;font-size:0.82rem;">{{ $b->kode_booking }}</td>
            <td style="color:var(--text-primary);font-weight:500;">{{ $b->customer->nama ?? '-' }}</td>
            <td style="color:var(--text-secondary);">{{ $b->hotel->nama ?? '-' }}</td>
            <td style="color:var(--text-muted);font-size:0.82rem;">{{ $b->dimTime ? \Carbon\Carbon::parse($b->dimTime->date)->isoFormat('D MMM YYYY') : 'N/A' }}</td>
            <td>
              @if($b->status === 'confirmed')
                <span class="badge badge-success" style="font-size:0.65rem;">✓ Confirmed</span>
              @elseif($b->status === 'completed')
                <span class="badge badge-success" style="font-size:0.65rem;">✅ Completed</span>
              @elseif($b->status === 'pending')
                <span class="badge badge-warning" style="font-size:0.65rem;">⏳ Pending</span>
              @else
                <span class="badge badge-danger" style="font-size:0.65rem;">✗ Cancelled</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted);">Belum ada data reservasi.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

@push('scripts')
@php
  $statusDonutData = [$bookingStatusDist['confirmed'], $bookingStatusDist['completed'], $bookingStatusDist['pending'], $bookingStatusDist['cancelled']];
@endphp
<script>
// ── Chart 7 hari ──
new Chart(document.getElementById('weekChart').getContext('2d'), {
  type: 'line',
  data: {
    labels: @json($weeklyOccupancy['labels']),
    datasets: [{
      label: 'Okupansi %',
      data: @json($weeklyOccupancy['data']),
      borderColor: '#a855f7',
      backgroundColor: 'rgba(168,85,247,0.1)',
      fill: true,
      tension: 0.4,
      pointRadius: 5,
      pointHoverRadius: 7,
      pointBackgroundColor: '#a855f7',
      pointBorderColor: '#0a0812',
      pointBorderWidth: 2,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: 'rgba(17,14,31,0.95)',
        borderColor: 'rgba(124,58,237,0.3)',
        borderWidth: 1,
        callbacks: { label: function(ctx){ return ' ' + ctx.parsed.y + '%'; } }
      }
    },
    scales: {
      x: { ticks: { color: '#5e5678', font: { size: 11 } }, grid: { color: 'rgba(124,58,237,0.08)' } },
      y: {
        ticks: { color: '#5e5678', callback: function(v){ return v + '%'; }, font: { size: 11 } },
        grid: { color: 'rgba(124,58,237,0.08)' },
        min: 0, max: 100
      }
    }
  }
});

// ── Donut Status ──
new Chart(document.getElementById('statusDonut').getContext('2d'), {
  type: 'doughnut',
  data: {
    labels: ['Confirmed','Completed','Pending','Cancelled'],
    datasets: [{
      data: @json($statusDonutData),
      backgroundColor: ['#4ade80','#a855f7','#fbbf24','#f87171'],
      borderWidth: 0,
      hoverOffset: 6,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '72%',
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: 'rgba(17,14,31,0.95)',
        borderColor: 'rgba(124,58,237,0.3)',
        borderWidth: 1,
      }
    }
  }
});
</script>
@endpush

@endsection