@extends('layouts.dashboard')
@section('title', 'Laporan')
@section('page-title', '📋 Laporan & Export Data')

@section('content')

{{-- Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
  @foreach([
    ['Laporan Dibuat','24','Bulan ini','📋'],
    ['Total Export','18','PDF & Excel','📥'],
    ['Data Terupdate','Hari ini','Sinkron otomatis','🔄'],
    ['Periode Aktif','Jan — Des 2024','Tahun berjalan','📅'],
  ] as $s)
  <div class="stat-card animate-fade-up" style="animation-delay:{{ $loop->index * 0.08 }}s">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">{{ $s[0] }}</div>
      <span style="font-size:1.4rem;">{{ $s[3] }}</span>
    </div>
    <div class="stat-value" style="font-size:1.6rem;">{{ $s[1] }}</div>
    <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">{{ $s[2] }}</div>
  </div>
  @endforeach
</div>

{{-- Laporan Tersedia --}}
<div style="margin-bottom:1.5rem;">
  <div style="font-family:var(--font-display);font-weight:700;font-size:1rem;margin-bottom:1rem;color:var(--text-primary);">
    📂 Laporan Tersedia
  </div>
  <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">
    @foreach($reports as $r)
    <div class="stat-card animate-fade-up" style="display:flex;gap:1.25rem;align-items:flex-start;">
      <div style="width:52px;height:52px;background:rgba(124,58,237,0.12);border:1px solid var(--border-glow);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">
        {{ $r['icon'] }}
      </div>
      <div style="flex:1;">
        <div style="font-weight:700;font-size:0.95rem;color:var(--text-primary);margin-bottom:4px;">
          {{ $r['title'] }}
        </div>
        <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:10px;line-height:1.5;">
          {{ $r['description'] }}
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
          <span class="badge badge-purple" style="font-size:0.65rem;">📅 {{ $r['period'] }}</span>
          <div style="display:flex;gap:0.5rem;">
            <button class="btn btn-outline btn-sm"
              style="font-size:0.72rem;padding:5px 12px;"
              onclick="previewReport('{{ $r['title'] }}')">
              👁️ Preview
            </button>
            <a href="{{ route('admin.export.excel.occupancy') }}"
  class="btn btn-outline btn-sm"
  style="font-size:0.72rem;padding:5px 12px;color:#4ade80;border-color:rgba(74,222,128,0.3);">
  📊 Excel
</a>
<a href="{{ route('admin.export.pdf.occupancy') }}"
  class="btn btn-outline btn-sm"
  style="font-size:0.72rem;padding:5px 12px;color:#f87171;border-color:rgba(248,113,113,0.3);">
  📄 PDF
</a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

{{-- Generate Laporan Custom --}}
<div class="glass-card" style="padding:1.75rem;margin-bottom:1.5rem;">
  <div class="chart-title" style="margin-bottom:1.25rem;">⚙️ Generate Laporan Custom</div>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1rem;">
    <div class="form-group" style="margin:0;">
      <label class="form-label">Jenis Laporan</label>
      <select class="form-input" style="padding:10px 14px;" id="jenisLaporan">
        <option>Okupansi Hotel</option>
        <option>Customer Behavior</option>
        <option>Revenue & Keuangan</option>
        <option>Seasonal Trend</option>
        <option>Semua Data</option>
      </select>
    </div>
    <div class="form-group" style="margin:0;">
      <label class="form-label">Properti</label>
      <select class="form-input" style="padding:10px 14px;">
        <option>Semua Properti</option>
        <option>NHG Jakarta</option>
        <option>NHG Bali Resort</option>
        <option>NHG Surabaya</option>
      </select>
    </div>
    <div class="form-group" style="margin:0;">
      <label class="form-label">Dari Tanggal</label>
      <input type="date" class="form-input" style="padding:10px 14px;"
        value="{{ date('Y-01-01') }}">
    </div>
    <div class="form-group" style="margin:0;">
      <label class="form-label">Sampai Tanggal</label>
      <input type="date" class="form-input" style="padding:10px 14px;"
        value="{{ date('Y-m-d') }}">
    </div>
  </div>
  <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
    <button class="btn btn-primary" onclick="generateCustom()">
      ⚙️ Generate Laporan
    </button>
    <button class="btn btn-outline"
      style="color:#4ade80;border-color:rgba(74,222,128,0.3);"
      onclick="exportReport('excel','Custom')">
      📊 Export Excel
    </button>
    <button class="btn btn-outline"
      style="color:#f87171;border-color:rgba(248,113,113,0.3);"
      onclick="exportReport('pdf','Custom')">
      📄 Export PDF
    </button>
  </div>
</div>

{{-- Ringkasan Data --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">

  {{-- Ringkasan Okupansi --}}
  <div class="chart-wrap animate-fade-up delay-2">
    <div class="chart-title" style="margin-bottom:1.25rem;">📊 Ringkasan Okupansi 2024</div>
    <table class="data-table">
      <thead>
        <tr><th>Properti</th><th>Avg Okupansi</th><th>Total Tamu</th><th>Revenue</th></tr>
      </thead>
      <tbody>
        @foreach([
          ['NHG Jakarta','83.2%','5,420','Rp 4.8M'],
          ['NHG Bali Resort','87.5%','4,180','Rp 6.2M'],
          ['NHG Surabaya','74.1%','3,240','Rp 2.9M'],
          ['Total','81.6%','12,840','Rp 13.9M'],
        ] as [$prop,$occ,$tamu,$rev])
        <tr>
          <td style="color:{{ $prop==='Total' ? 'var(--purple-light)' : 'var(--text-primary)' }};font-weight:{{ $prop==='Total' ? '700' : '400' }};">
            {{ $prop }}
          </td>
          <td style="color:{{ $prop==='Total' ? 'var(--purple-light)' : 'var(--text-secondary)' }};font-weight:600;">
            {{ $occ }}
          </td>
          <td>{{ $tamu }}</td>
          <td style="color:var(--text-primary);font-weight:600;">{{ $rev }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- Ringkasan per Kuartal --}}
  <div class="chart-wrap animate-fade-up delay-3">
    <div class="chart-title" style="margin-bottom:1.25rem;">📈 Revenue per Kuartal</div>
    <div style="height:200px;"><canvas id="revenueQuartChart"></canvas></div>
  </div>
</div>

{{-- Riwayat Export --}}
<div class="chart-wrap animate-fade-up delay-4">
  <div class="chart-title" style="margin-bottom:1.25rem;">🕓 Riwayat Export Terakhir</div>
  <table class="data-table">
    <thead>
      <tr><th>Laporan</th><th>Format</th><th>Periode</th><th>Dibuat Oleh</th><th>Waktu</th><th>Ukuran</th></tr>
    </thead>
    <tbody>
      @foreach([
        ['Laporan Okupansi Bulanan','Excel','Januari 2024','Admin NHG','17 Mei 2024 08:30','245 KB'],
        ['Laporan Revenue Q1','PDF','Q1 2024','Admin NHG','15 Mei 2024 14:22','1.2 MB'],
        ['Customer Behavior Report','Excel','Jan–Mar 2024','Admin NHG','10 Mei 2024 09:15','380 KB'],
        ['Seasonal Trend 2024','PDF','Tahunan 2024','Admin NHG','5 Mei 2024 16:45','2.1 MB'],
        ['Data Semua Properti','Excel','2024','Admin NHG','1 Mei 2024 11:00','1.8 MB'],
      ] as [$nama,$fmt,$period,$by,$time,$size])
      <tr>
        <td style="color:var(--text-primary);font-weight:600;">{{ $nama }}</td>
        <td>
          <span class="badge" style="font-size:0.65rem;{{ $fmt==='Excel' ? 'background:rgba(74,222,128,0.1);color:#4ade80;border:1px solid rgba(74,222,128,0.2);' : 'background:rgba(248,113,113,0.1);color:#f87171;border:1px solid rgba(248,113,113,0.2);' }}">
            {{ $fmt==='Excel' ? '📊' : '📄' }} {{ $fmt }}
          </span>
        </td>
        <td style="color:var(--text-muted);">{{ $period }}</td>
        <td>{{ $by }}</td>
        <td style="color:var(--text-muted);font-size:0.82rem;">{{ $time }}</td>
        <td style="color:var(--text-muted);">{{ $size }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{-- Toast Notification --}}
<div id="toast" style="
  position:fixed;bottom:2rem;right:2rem;
  background:var(--bg-card);border:1px solid var(--border-glow);
  border-radius:var(--radius-md);padding:1rem 1.5rem;
  display:none;align-items:center;gap:10px;
  box-shadow:var(--shadow-purple);z-index:999;
  font-size:0.875rem;color:var(--text-primary);
  animation:fadeUp 0.3s ease;
">
  <span id="toastIcon">✅</span>
  <span id="toastMsg">Berhasil</span>
</div>

@push('scripts')
<script>
// Revenue Chart
new Chart(document.getElementById('revenueQuartChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: ['Q1 2024','Q2 2024','Q3 2024','Q4 2024'],
    datasets: [
      { label:'NHG Jakarta',    data:[4.5,5.2,5.8,5.4], backgroundColor:'rgba(168,85,247,0.7)', borderRadius:6 },
      { label:'NHG Bali Resort',data:[3.8,4.4,5.1,4.9], backgroundColor:'rgba(236,72,153,0.7)', borderRadius:6 },
      { label:'NHG Surabaya',   data:[2.2,2.6,2.9,2.7], backgroundColor:'rgba(34,211,238,0.7)',  borderRadius:6 },
    ]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{ legend:{ display:true, labels:{ color:'#a89fc8', font:{size:10} } } },
    scales:{
      x:{ ticks:{ color:'#5e5678' }, grid:{ display:false } },
      y:{ ticks:{ color:'#5e5678', callback:v=>'Rp '+v+'M' }, grid:{ color:'rgba(124,58,237,0.08)' } }
    }
  }
});

function showToast(icon, msg) {
  const t = document.getElementById('toast');
  document.getElementById('toastIcon').textContent = icon;
  document.getElementById('toastMsg').textContent  = msg;
  t.style.display = 'flex';
  setTimeout(() => t.style.display = 'none', 3000);
}

function previewReport(name) {
  showToast('👁️', 'Membuka preview: ' + name + '...');
}

function exportReport(type, name) {
  const icon = type === 'excel' ? '📊' : '📄';
  const fmt  = type === 'excel' ? 'Excel' : 'PDF';
  showToast(icon, 'Mengexport ' + fmt + ': ' + name + '...');
}

function generateCustom() {
  const jenis = document.getElementById('jenisLaporan').value;
  showToast('⚙️', 'Generating laporan: ' + jenis + '...');
}
</script>
@endpush

@endsection