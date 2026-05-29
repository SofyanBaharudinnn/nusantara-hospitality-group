@extends('layouts.dashboard')
@section('title', 'Okupansi Hotel')
@section('page-title', '🏨 Data Okupansi Hotel')

@section('content')

{{-- Filter Bar --}}
<div class="glass-card" style="padding:1.25rem 1.5rem;margin-bottom:1.5rem;display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
  <div style="font-size:0.82rem;color:var(--text-muted);font-weight:500;">Filter:</div>
  <select class="form-input" style="width:auto;padding:8px 14px;">
    <option>Semua Properti</option>
    <option>NHG Jakarta</option>
    <option>NHG Bali Resort</option>
    <option>NHG Surabaya</option>
  </select>
  <select class="form-input" style="width:auto;padding:8px 14px;">
    <option>2024</option><option>2023</option><option>2022</option>
  </select>
  <select class="form-input" style="width:auto;padding:8px 14px;">
    <option>Semua Bulan</option>
    <option>Januari</option><option>Februari</option><option>Maret</option>
    <option>April</option><option>Mei</option><option>Juni</option>
    <option>Juli</option><option>Agustus</option><option>September</option>
    <option>Oktober</option><option>November</option><option>Desember</option>
  </select>
  <button class="btn btn-primary btn-sm">🔍 Terapkan</button>
  <div style="margin-left:auto;display:flex;gap:8px;">
    <button class="btn btn-outline btn-sm">📥 Export Excel</button>
    <button class="btn btn-outline btn-sm">📄 Export PDF</button>
  </div>
</div>

{{-- Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
  @foreach([
    ['NHG Jakarta','148/180','82.2%','Rp 850K','Rp 699K','up'],
    ['NHG Bali Resort','92/120','76.7%','Rp 1.2Jt','Rp 920K','up'],
    ['NHG Surabaya','65/90','72.2%','Rp 680K','Rp 491K','down'],
    ['Total Grup','305/390','78.2%','Rp 910K','Rp 711K','up'],
  ] as $o)
  <div class="stat-card animate-fade-up" style="animation-delay:{{ $loop->index * 0.08 }}s">
    <div style="font-size:0.78rem;font-weight:700;color:var(--purple-glow);margin-bottom:0.75rem;text-transform:uppercase;letter-spacing:0.04em;">
      {{ $o[0] }}
    </div>
    <div style="font-family:var(--font-display);font-size:2rem;font-weight:800;color:var(--text-primary);margin-bottom:4px;">
      {{ $o[2] }}
    </div>
    <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:8px;">{{ $o[1] }} kamar terisi</div>
    <div style="height:1px;background:linear-gradient(90deg,transparent,var(--border-glow),transparent);margin:8px 0;"></div>
    <div style="display:flex;justify-content:space-between;font-size:0.75rem;">
      <div>
        <div style="color:var(--text-muted);">ADR</div>
        <div style="color:var(--text-primary);font-weight:600;">{{ $o[3] }}</div>
      </div>
      <div style="text-align:right;">
        <div style="color:var(--text-muted);">RevPAR</div>
        <div style="color:var(--text-primary);font-weight:600;">{{ $o[4] }}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- Charts --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
  <div class="chart-wrap animate-fade-up delay-2">
    <div class="chart-title" style="margin-bottom:1rem;">Tren Okupansi Bulanan per Properti</div>
    <div style="height:260px;"><canvas id="propOccChart"></canvas></div>
  </div>
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1rem;">Distribusi Tipe Kamar</div>
    <div style="height:200px;"><canvas id="roomTypeChart"></canvas></div>
    <div style="margin-top:1rem;display:flex;flex-direction:column;gap:5px;">
      @foreach([['Deluxe','45%','#a855f7'],['Suite','28%','#ec4899'],['Standard','18%','#22d3ee'],['Villa','9%','#f59e0b']] as $r)
      <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.75rem;">
        <span style="display:flex;align-items:center;gap:6px;color:var(--text-secondary);">
          <span style="width:8px;height:8px;border-radius:2px;background:{{ $r[2] }};display:inline-block;"></span>{{ $r[0] }}
        </span>
        <span style="color:var(--text-primary);font-weight:600;">{{ $r[1] }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- Occupancy Detail Table --}}
<div class="chart-wrap animate-fade-up delay-4">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
    <div class="chart-title">Detail Okupansi per Hari — Januari 2024</div>
    <div style="display:flex;gap:8px;align-items:center;font-size:0.75rem;color:var(--text-muted);">
      <span style="width:10px;height:10px;background:#4ade80;border-radius:2px;display:inline-block;"></span> >85%
      <span style="width:10px;height:10px;background:#f59e0b;border-radius:2px;display:inline-block;margin-left:6px;"></span> 60-85%
      <span style="width:10px;height:10px;background:#f87171;border-radius:2px;display:inline-block;margin-left:6px;"></span> <60%
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="data-table">
      <thead>
        <tr>
          <th>Tanggal</th><th>NHG Jakarta</th><th>NHG Bali Resort</th>
          <th>NHG Surabaya</th><th>Total Kamar</th><th>Total Tamu</th><th>Revenue</th>
        </tr>
      </thead>
      <tbody>
        @php
        $days = [
          ['1 Jan','88%','92%','75%','316','420','Rp 285Jt'],
          ['2 Jan','85%','90%','72%','308','395','Rp 271Jt'],
          ['3 Jan','91%','95%','78%','325','443','Rp 301Jt'],
          ['4 Jan','79%','85%','68%','294','368','Rp 248Jt'],
          ['5 Jan','72%','78%','58%','270','326','Rp 218Jt'],
          ['6 Jan','55%','60%','45%','208','240','Rp 162Jt'],
          ['7 Jan','58%','65%','48%','218','255','Rp 172Jt'],
        ];
        $color = fn($v) => (int)$v > 84 ? '#4ade80' : ((int)$v > 59 ? '#f59e0b' : '#f87171');
        @endphp
        @foreach($days as $d)
        <tr>
          <td style="color:var(--text-primary);font-weight:600;">{{ $d[0] }}</td>
          <td><span style="color:{{ $color($d[1]) }};font-weight:700;">{{ $d[1] }}</span></td>
          <td><span style="color:{{ $color($d[2]) }};font-weight:700;">{{ $d[2] }}</span></td>
          <td><span style="color:{{ $color($d[3]) }};font-weight:700;">{{ $d[3] }}</span></td>
          <td>{{ $d[4] }}</td>
          <td>{{ $d[5] }}</td>
          <td style="color:var(--text-primary);">{{ $d[6] }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
<script>
const labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];

new Chart(document.getElementById('propOccChart').getContext('2d'), {
  type: 'line',
  data: {
    labels,
    datasets: [
      { label:'NHG Jakarta',    data:[72,78,74,85,90,88,94,87,82,89,91,87], borderColor:'#a855f7', fill:false, tension:0.4, pointRadius:5, pointBackgroundColor:'#a855f7' },
      { label:'NHG Bali',       data:[65,72,70,82,92,95,98,91,85,88,90,84], borderColor:'#ec4899', fill:false, tension:0.4, pointRadius:5, pointBackgroundColor:'#ec4899' },
      { label:'NHG Surabaya',   data:[58,64,60,72,76,74,80,72,68,72,76,70], borderColor:'#22d3ee', fill:false, tension:0.4, pointRadius:5, pointBackgroundColor:'#22d3ee' },
    ]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ display:true, labels:{ color:'#a89fc8', font:{size:11} } } },
    scales:{
      x:{ ticks:{ color:'#5e5678' }, grid:{ color:'rgba(124,58,237,0.08)' } },
      y:{ ticks:{ color:'#5e5678', callback:v=>v+'%' }, grid:{ color:'rgba(124,58,237,0.08)' }, min:40, max:100 }
    }
  }
});

new Chart(document.getElementById('roomTypeChart').getContext('2d'), {
  type: 'doughnut',
  data: {
    labels:['Deluxe','Suite','Standard','Villa'],
    datasets:[{ data:[45,28,18,9], backgroundColor:['#a855f7','#ec4899','#22d3ee','#f59e0b'], borderWidth:0 }]
  },
  options: { responsive:true, maintainAspectRatio:false, cutout:'68%', plugins:{ legend:{ display:false } } }
});
</script>
@endpush

@endsection