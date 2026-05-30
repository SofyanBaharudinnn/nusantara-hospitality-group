@extends('layouts.dashboard')
@section('title', 'Customer Behavior')
@section('page-title', '👥 Analisis Customer Behavior')

@section('content')

{{-- KPI Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
  @foreach([
    ['Total Tamu Terdaftar','12,847','+842 bulan ini',true,'👥'],
    ['Repeat Guest Rate','34.2%','+2.1%',true,'🔄'],
    ['Avg. Customer Lifetime Value','Rp 18.4Jt','+5.7%',true,'💎'],
    ['NPS Score','72','+4 poin',true,'⭐'],
  ] as $s)
  <div class="stat-card animate-fade-up" style="animation-delay:{{ $loop->index * 0.08 }}s">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">{{ $s[0] }}</div>
      <span style="font-size:1.3rem;">{{ $s[4] }}</span>
    </div>
    <div class="stat-value">{{ $s[1] }}</div>
    <div class="stat-change {{ $s[3] ? 'up' : 'down' }}">{{ $s[3] ? '▲' : '▼' }} {{ $s[2] }}</div>
  </div>
  @endforeach
</div>

{{-- Charts Row 1 --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
  <div class="chart-wrap animate-fade-up delay-2">
    <div class="chart-title" style="margin-bottom:1rem;">Segmentasi Tamu</div>
    <div style="height:220px;"><canvas id="segChart"></canvas></div>
  </div>
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1rem;">Negara Asal Tamu (Top 7)</div>
    <div style="height:220px;"><canvas id="countryChart"></canvas></div>
  </div>
</div>

{{-- Charts Row 2 --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1rem;">Tamu Baru vs Repeat Guest — 2024</div>
    <div style="height:220px;"><canvas id="repeatChart"></canvas></div>
  </div>
  <div class="chart-wrap animate-fade-up delay-4">
    <div class="chart-title" style="margin-bottom:1rem;">Preferensi Tipe Kamar</div>
    <div style="height:220px;"><canvas id="prefChart"></canvas></div>
  </div>
</div>

{{-- Top Customers Table --}}
<div class="chart-wrap animate-fade-up delay-4">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
    <div class="chart-title">Top Tamu Berdasarkan CLV</div>
    <div style="display:flex;gap:0.5rem;">
      <a href="{{ route('admin.export.excel.customers') }}" class="btn btn-outline btn-sm" style="color:#4ade80;border-color:rgba(74,222,128,0.3);">📊 Excel</a>
      <a href="{{ route('admin.export.pdf.customers') }}" class="btn btn-outline btn-sm" style="color:#f87171;border-color:rgba(248,113,113,0.3);">📄 PDF</a>
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th><th>Nama Tamu</th><th>Segmen</th><th>Total Kunjungan</th>
          <th>Properti Favorit</th><th>CLV</th><th>Last Stay</th><th>Tier</th>
        </tr>
      </thead>
      <tbody>
        @php
        $customers = [
          [1,'Rina Agustina','VIP',18,'NHG Bali Resort','Rp 42.5Jt','15 Jan 2024','Platinum'],
          [2,'Budi Hartono','Corporate',24,'NHG Jakarta','Rp 38.2Jt','20 Jan 2024','Platinum'],
          [3,'Jessica Tan','Leisure',12,'NHG Bali Resort','Rp 28.7Jt','10 Jan 2024','Gold'],
          [4,'Ahmad Fauzi','Group',8,'NHG Surabaya','Rp 22.1Jt','5 Jan 2024','Gold'],
          [5,'Siti Nurhaliza','VIP',15,'NHG Jakarta','Rp 35.8Jt','18 Jan 2024','Platinum'],
          [6,'Kevin Wijaya','Leisure',9,'NHG Bali Resort','Rp 19.4Jt','12 Jan 2024','Silver'],
        ];
        $tierBadge = ['Platinum'=>'badge-purple','Gold'=>'badge-warning','Silver'=>'badge-success'];
        $segColor  = ['VIP'=>'#a855f7','Corporate'=>'#22d3ee','Leisure'=>'#ec4899','Group'=>'#f59e0b'];
        @endphp
        @foreach($customers as [$no,$name,$seg,$visits,$prop,$clv,$last,$tier])
        <tr>
          <td style="color:var(--text-muted);">{{ $no }}</td>
          <td style="color:var(--text-primary);font-weight:600;">{{ $name }}</td>
          <td>
            <span style="color:{{ $segColor[$seg] ?? '#a89fc8' }};font-weight:600;font-size:0.82rem;">
              {{ $seg }}
            </span>
          </td>
          <td>{{ $visits }}x</td>
          <td style="color:var(--text-secondary);">{{ $prop }}</td>
          <td style="color:var(--text-primary);font-weight:700;">{{ $clv }}</td>
          <td style="color:var(--text-muted);">{{ $last }}</td>
          <td>
            <span class="badge {{ $tierBadge[$tier] ?? '' }}" style="font-size:0.65rem;">
              {{ $tier }}
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
const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];

// Segmentasi Pie
new Chart(document.getElementById('segChart').getContext('2d'), {
  type: 'pie',
  data: {
    labels: ['VIP','Corporate','Leisure','Group','Walk-in'],
    datasets: [{ data:[15,28,38,12,7], backgroundColor:['#a855f7','#22d3ee','#ec4899','#f59e0b','#6366f1'], borderWidth:0 }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ position:'right', labels:{ color:'#a89fc8', font:{size:11}, padding:12 } } }
  }
});

// Negara Asal Bar Horizontal
new Chart(document.getElementById('countryChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ['Indonesia','Australia','Singapura','Malaysia','Jepang','Belanda','USA'],
    datasets: [{ data:[4820,1840,1250,980,640,420,390], backgroundColor:'rgba(168,85,247,0.7)', borderRadius:6, borderSkipped:false }]
  },
  options: {
    indexAxis: 'y',
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ display:false } },
    scales:{
      x:{ ticks:{ color:'#5e5678' }, grid:{ color:'rgba(124,58,237,0.08)' } },
      y:{ ticks:{ color:'#a89fc8' }, grid:{ display:false } }
    }
  }
});

// Repeat Guest Bar
new Chart(document.getElementById('repeatChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: months,
    datasets: [
      { label:'Tamu Baru',    data:[420,385,410,480,530,510,580,540,490,520,560,540], backgroundColor:'rgba(124,58,237,0.7)', borderRadius:4 },
      { label:'Repeat Guest', data:[180,165,175,210,240,225,265,245,220,235,255,245], backgroundColor:'rgba(236,72,153,0.7)', borderRadius:4 },
    ]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ display:true, labels:{ color:'#a89fc8', font:{size:11} } } },
    scales:{
      x:{ ticks:{ color:'#5e5678' }, grid:{ display:false } },
      y:{ ticks:{ color:'#5e5678' }, grid:{ color:'rgba(124,58,237,0.08)' } }
    }
  }
});

// Preferensi Polar Area
new Chart(document.getElementById('prefChart').getContext('2d'), {
  type: 'polarArea',
  data: {
    labels: ['Deluxe','Suite','Standard','Villa','Penthouse'],
    datasets: [{
      data: [38,27,20,10,5],
      backgroundColor: ['rgba(168,85,247,0.7)','rgba(236,72,153,0.7)','rgba(34,211,238,0.7)','rgba(245,158,11,0.7)','rgba(99,102,241,0.7)']
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ position:'right', labels:{ color:'#a89fc8', font:{size:10}, padding:8 } } },
    scales:{ r:{ ticks:{ display:false }, grid:{ color:'rgba(124,58,237,0.1)' } } }
  }
});
</script>
@endpush

@endsection