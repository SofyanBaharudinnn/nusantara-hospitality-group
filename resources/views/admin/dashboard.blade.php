@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', '📊 Dashboard Overview')

@section('content')

{{-- Stat Cards --}}
<div class="grid-responsive-4" style="margin-bottom:1.5rem;">
  <div class="stat-card animate-fade-up">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">Tingkat Okupansi</div>
      <span style="font-size:1.4rem;">🏨</span>
    </div>
    <div class="stat-value">{{ $occupancyRate }}%</div>
    <div class="stat-change up">▲ {{ $terisi }}/{{ $totalKamar }} kamar terisi</div>
  </div>

  <div class="stat-card animate-fade-up delay-1">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">Total Tamu Terdaftar</div>
      <span style="font-size:1.4rem;">👥</span>
    </div>
    <div class="stat-value">{{ number_format($totalCustomers) }}</div>
    <div class="stat-change up">▲ Semua properti</div>
  </div>

  <div class="stat-card animate-fade-up delay-2">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">Total Revenue</div>
      <span style="font-size:1.4rem;">💰</span>
    </div>
    <div class="stat-value" style="font-size:1.6rem;">
      Rp {{ number_format($totalRevenue / 1000000, 1) }}M
    </div>
    <div class="stat-change up">▲ {{ $totalBookings }} total booking</div>
  </div>

  <div class="stat-card animate-fade-up delay-3">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">Avg. Rating Tamu</div>
      <span style="font-size:1.4rem;">⭐</span>
    </div>
    <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
    <div class="stat-change up">▲ Dari semua ulasan</div>
  </div>
</div>

{{-- Charts Row --}}
<div class="grid-aside" style="margin-bottom:1.25rem;">

  {{-- Occupancy Line Chart --}}
  <div class="chart-wrap animate-fade-up delay-2">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
      <div>
        <div class="chart-title">Tren Okupansi {{ $displayDate }}</div>
        <div style="font-size:0.78rem;color:var(--text-muted);">Semua properti NHG - Data diperbarui secara real-time</div>
      </div>
    </div>
    <div style="height:220px;"><canvas id="occupancyChart"></canvas></div>
  </div>

  {{-- Channel Doughnut --}}
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1.25rem;">Sumber Booking</div>
    <div style="height:180px;"><canvas id="channelChart"></canvas></div>
    <div style="margin-top:1rem;display:flex;flex-direction:column;gap:6px;">
      @php
      $colors = ['#a855f7','#ec4899','#22d3ee','#f59e0b','#4ade80','#f87171','#60a5fa'];
      @endphp
      @foreach($channelData as $i => $c)
      <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.78rem;">
        <span style="display:flex;align-items:center;gap:6px;color:var(--text-secondary);">
          <span style="width:8px;height:8px;border-radius:2px;background:{{ $colors[$i % count($colors)] }};display:inline-block;"></span>
          {{ $c['nama'] }}
        </span>
        <span style="color:var(--text-primary);font-weight:600;">{{ $c['total'] }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- Revenue + Seasonal --}}
<div class="grid-responsive-2" style="margin-bottom:1.25rem;">
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1.25rem;">Revenue per Properti (Rp Juta)</div>
    <div style="height:200px;"><canvas id="revenueChart"></canvas></div>
  </div>
  <div class="chart-wrap animate-fade-up delay-4">
    <div class="chart-title" style="margin-bottom:1.25rem;">Tren Okupansi {{ $displayMonth }} {{ $year }} — Radar</div>
    <div style="height:200px;"><canvas id="seasonChart"></canvas></div>
  </div>
</div>

{{-- Recent Bookings Table — DATA REAL --}}
<div class="chart-wrap animate-fade-up delay-4">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
    <div class="chart-title">Reservasi Terbaru</div>
    <a href="{{ route('admin.occupancy') }}" class="btn btn-outline btn-sm">Lihat Semua →</a>
  </div>
  <div class="table-responsive">
    <table class="data-table">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Tamu</th>
          <th>Properti</th>
          <th>Tipe Kamar</th>
          <th>Check-in</th>
          <th>Checkout</th>
          <th>Total</th>
          <th>Channel</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentBookings as $b)
        <tr>
          <td style="color:var(--purple-light);font-weight:600;">
            {{ $b->kode_booking }}
          </td>
          <td style="color:var(--text-primary);font-weight:600;">
            {{ $b->customer->nama ?? '-' }}
          </td>
          <td style="color:var(--text-secondary);">
            {{ $b->hotel->nama ?? '-' }}
          </td>
          <td>
            <span class="badge" style="font-size:0.65rem;background:rgba(124,58,237,0.1);color:var(--purple-glow);border:1px solid rgba(124,58,237,0.2);">
              {{ ucfirst($b->room->tipe ?? '-') }}
            </span>
          </td>
          <td style="color:var(--text-muted);font-size:0.82rem;">{{ $b->tgl_checkin ? \Carbon\Carbon::parse($b->tgl_checkin)->isoFormat('D MMM YYYY') : 'N/A' }}</td>
          <td style="color:var(--text-secondary);">
            {{ $b->tgl_checkout->format('d M Y') }}
          </td>
          <td style="color:var(--text-primary);font-weight:600;">
            Rp {{ number_format($b->total_bayar, 0, ',', '.') }}
          </td>
          <td>
            <span class="badge" style="font-size:0.65rem;background:rgba(34,211,238,0.08);color:var(--accent-cyan);border:1px solid rgba(34,211,238,0.2);">
              {{ $b->channel->nama ?? '-' }}
            </span>
          </td>
          <td>
            @if($b->status === 'confirmed')
              <span class="badge badge-success" style="font-size:0.65rem;">✓ Confirmed</span>
            @elseif($b->status === 'pending')
              <span class="badge badge-warning" style="font-size:0.65rem;">⏳ Pending</span>
            @elseif($b->status === 'completed')
              <span class="badge badge-success" style="font-size:0.65rem;">✅ Completed</span>
            @else
              <span class="badge badge-danger" style="font-size:0.65rem;">✗ Cancelled</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" style="text-align:center;padding:2rem;color:var(--text-muted);">
            Belum ada data booking.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
@php
  $monthlyData = $occupancyMonthly;
  $revenueLabels = $revenueByHotel->pluck('nama')->toArray();
  $revenueData = $revenueByHotel->pluck('revenue')->toArray();
  $channelLabels = $channelData->pluck('nama')->toArray();
  $channelTotals = $channelData->pluck('total')->toArray();
@endphp
<script>
const labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
const occupancyData = @json($monthlyData);
const channelLabels = @json($channelLabels);
const channelValues = @json($channelTotals);
const hotelNames    = @json($revenueLabels);
const hotelRevenue  = @json($revenueData);

// Occupancy Line
new Chart(document.getElementById('occupancyChart').getContext('2d'), {
  type: 'line',
  data: {
    labels,
    datasets: [{
      label: 'Okupansi (%)',
      data: occupancyData,
      borderColor: '#a855f7',
      backgroundColor: 'rgba(168,85,247,0.1)',
      fill: true,
      tension: 0.4,
      pointRadius: 5,
      pointBackgroundColor: '#a855f7'
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { ticks: { color: '#5e5678' }, grid: { color: 'rgba(124,58,237,0.08)' } },
      y: { ticks: { color: '#5e5678', callback: function(v){ return v + '%'; } }, grid: { color: 'rgba(124,58,237,0.08)' }, min: 0, max: 100 }
    }
  }
});

// Channel Doughnut
const channelColors = ['#a855f7','#ec4899','#22d3ee','#f59e0b','#4ade80','#f87171','#60a5fa'];
new Chart(document.getElementById('channelChart').getContext('2d'), {
  type: 'doughnut',
  data: {
    labels: channelLabels,
    datasets: [{ data: channelValues, backgroundColor: channelColors, borderWidth: 0 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    cutout: '70%',
    plugins: { legend: { display: false } }
  }
});

// Revenue Bar
new Chart(document.getElementById('revenueChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: hotelNames,
    datasets: [{
      label: 'Revenue (Rp Juta)',
      data: hotelRevenue,
      backgroundColor: ['rgba(168,85,247,0.7)','rgba(236,72,153,0.7)','rgba(34,211,238,0.7)'],
      borderRadius: 8
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { ticks: { color: '#5e5678' }, grid: { display: false } },
      y: { ticks: { color: '#5e5678', callback: function(v){ return 'Rp ' + v + 'Jt'; } }, grid: { color: 'rgba(124,58,237,0.08)' } }
    }
  }
});

// Seasonal Radar
new Chart(document.getElementById('seasonChart').getContext('2d'), {
  type: 'radar',
  data: {
    labels,
    datasets: [{
      label: '@json(now()->year)',
      data: occupancyData,
      borderColor: '#a855f7',
      backgroundColor: 'rgba(168,85,247,0.1)',
      pointBackgroundColor: '#a855f7'
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: true, labels: { color: '#a89fc8', font: { size: 10 } } } },
    scales: { r: { ticks: { display: false }, grid: { color: 'rgba(124,58,237,0.15)' }, pointLabels: { color: '#5e5678', font: { size: 10 } } } }
  }
});
</script>
@endpush

@endsection