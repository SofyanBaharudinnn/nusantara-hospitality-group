@extends('layouts.dashboard')
@section('title', 'Star Schema')
@section('page-title', '🗄️ Star Schema Design')

@section('content')

{{-- Header Info --}}
<div class="glass-card" style="padding:1.5rem 2rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
  <div>
    <div style="font-family:var(--font-display);font-weight:700;font-size:1.1rem;margin-bottom:4px;">
      Data Warehouse — Nusantara Hospitality Group
    </div>
    <div style="font-size:0.82rem;color:var(--text-muted);">
      Skema bintang dengan 1 fact table utama dan 5 dimension table
    </div>
  </div>
  <div style="display:flex;gap:1rem;font-size:0.8rem;">
    <div style="text-align:center;padding:0.75rem 1.25rem;background:rgba(124,58,237,0.1);border:1px solid var(--border-subtle);border-radius:var(--radius-sm);">
      <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:var(--purple-light);">2</div>
      <div style="color:var(--text-muted);">Fact Tables</div>
    </div>
    <div style="text-align:center;padding:0.75rem 1.25rem;background:rgba(34,211,238,0.07);border:1px solid rgba(34,211,238,0.15);border-radius:var(--radius-sm);">
      <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:var(--accent-cyan);">5</div>
      <div style="color:var(--text-muted);">Dimension Tables</div>
    </div>
    <div style="text-align:center;padding:0.75rem 1.25rem;background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.15);border-radius:var(--radius-sm);">
      <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:var(--accent-gold);">4</div>
      <div style="color:var(--text-muted);">Sumber Data</div>
    </div>
  </div>
</div>

{{-- Star Schema Visual --}}
<div class="glass-card" style="padding:2rem;margin-bottom:1.5rem;">
  <div style="text-align:center;margin-bottom:2rem;">
    <div class="badge badge-purple" style="margin-bottom:0.75rem;">Diagram Star Schema</div>
    <h2 style="font-family:var(--font-display);font-size:1.3rem;font-weight:700;">FACT_BOOKING sebagai Pusat</h2>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1.4fr 1fr;gap:1.5rem;align-items:center;">

    {{-- Kolom Kiri --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

      {{-- DIM_DATE --}}
      <div style="background:var(--bg-card2);border:1px solid rgba(168,85,247,0.3);border-radius:var(--radius-sm);padding:1rem;position:relative;">
        <div style="position:absolute;top:-10px;left:12px;background:var(--bg-card2);padding:0 6px;font-size:0.6rem;font-weight:700;color:#a855f7;text-transform:uppercase;letter-spacing:0.08em;">DIM_DATE</div>
        @foreach(['date_id (PK)','tanggal','hari','minggu','bulan','kuartal','tahun','musim','is_weekend'] as $f)
        <div style="font-size:0.7rem;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.03);color:{{ $f==='date_id (PK)' ? '#c084fc' : 'var(--text-muted)' }};">
          {{ $f }}
        </div>
        @endforeach
      </div>

      {{-- DIM_CUSTOMER --}}
      <div style="background:var(--bg-card2);border:1px solid rgba(245,158,11,0.3);border-radius:var(--radius-sm);padding:1rem;position:relative;">
        <div style="position:absolute;top:-10px;left:12px;background:var(--bg-card2);padding:0 6px;font-size:0.6rem;font-weight:700;color:#f59e0b;text-transform:uppercase;letter-spacing:0.08em;">DIM_CUSTOMER</div>
        @foreach(['customer_id (PK)','nama','email','telepon','segmen','negara','kota_asal','tgl_lahir'] as $f)
        <div style="font-size:0.7rem;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.03);color:{{ $f==='customer_id (PK)' ? '#fbbf24' : 'var(--text-muted)' }};">
          {{ $f }}
        </div>
        @endforeach
      </div>

    </div>

    {{-- Tengah: FACT TABLE --}}
    <div style="background:linear-gradient(135deg,rgba(124,58,237,0.25),rgba(168,85,247,0.1));border:2px solid var(--purple-light);border-radius:var(--radius-md);padding:1.5rem;text-align:center;box-shadow:0 0 50px rgba(124,58,237,0.3);position:relative;">
      <div style="position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,var(--purple-core),var(--purple-light));border-radius:8px;padding:4px 14px;font-size:0.7rem;font-weight:700;white-space:nowrap;">
        ⭐ FACT TABLE
      </div>
      <div style="font-size:0.75rem;color:var(--purple-glow);font-weight:800;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:1rem;margin-top:0.5rem;">
        FACT_BOOKING
      </div>
      @foreach([
        ['booking_id (PK)','#c084fc'],
        ['date_id (FK)','#a855f7'],
        ['hotel_id (FK)','#a855f7'],
        ['room_id (FK)','#a855f7'],
        ['customer_id (FK)','#a855f7'],
        ['channel_id (FK)','#a855f7'],
        ['tgl_checkin','var(--text-secondary)'],
        ['tgl_checkout','var(--text-secondary)'],
        ['jml_malam','var(--text-secondary)'],
        ['jml_tamu','var(--text-secondary)'],
        ['harga_per_malam','var(--text-secondary)'],
        ['total_bayar','var(--text-secondary)'],
        ['diskon','var(--text-secondary)'],
        ['status','var(--text-secondary)'],
        ['rating','var(--text-secondary)'],
      ] as [$field,$color])
      <div style="font-size:0.72rem;padding:4px 0;border-bottom:1px solid rgba(124,58,237,0.1);color:{{ $color }};">
        {{ $field }}
      </div>
      @endforeach

      <div style="margin-top:1rem;padding-top:0.75rem;border-top:1px solid rgba(124,58,237,0.2);">
        <div style="font-size:0.65rem;color:var(--text-muted);margin-bottom:6px;">MEASURES</div>
        @foreach(['COUNT(booking_id)','SUM(total_bayar)','AVG(jml_malam)','AVG(rating)'] as $m)
        <div style="font-size:0.68rem;color:#4ade80;padding:2px 0;">{{ $m }}</div>
        @endforeach
      </div>
    </div>

    {{-- Kolom Kanan --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

      {{-- DIM_HOTEL --}}
      <div style="background:var(--bg-card2);border:1px solid rgba(34,211,238,0.3);border-radius:var(--radius-sm);padding:1rem;position:relative;">
        <div style="position:absolute;top:-10px;left:12px;background:var(--bg-card2);padding:0 6px;font-size:0.6rem;font-weight:700;color:#22d3ee;text-transform:uppercase;letter-spacing:0.08em;">DIM_HOTEL</div>
        @foreach(['hotel_id (PK)','nama_hotel','tipe','kota','provinsi','bintang','kapasitas'] as $f)
        <div style="font-size:0.7rem;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.03);color:{{ $f==='hotel_id (PK)' ? '#67e8f9' : 'var(--text-muted)' }};">
          {{ $f }}
        </div>
        @endforeach
      </div>

      {{-- DIM_ROOM --}}
      <div style="background:var(--bg-card2);border:1px solid rgba(236,72,153,0.3);border-radius:var(--radius-sm);padding:1rem;position:relative;">
        <div style="position:absolute;top:-10px;left:12px;background:var(--bg-card2);padding:0 6px;font-size:0.6rem;font-weight:700;color:#ec4899;text-transform:uppercase;letter-spacing:0.08em;">DIM_ROOM</div>
        @foreach(['room_id (PK)','hotel_id (FK)','tipe_kamar','kapasitas','fasilitas','harga_dasar','lantai'] as $f)
        <div style="font-size:0.7rem;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.03);color:{{ str_contains($f,'PK') ? '#f9a8d4' : (str_contains($f,'FK') ? '#ec4899' : 'var(--text-muted)') }};">
          {{ $f }}
        </div>
        @endforeach
      </div>

    </div>
  </div>

  {{-- DIM_CHANNEL di bawah --}}
  <div style="margin-top:1.5rem;display:flex;justify-content:center;">
    <div style="background:var(--bg-card2);border:1px solid rgba(74,222,128,0.3);border-radius:var(--radius-sm);padding:1rem;width:250px;position:relative;">
      <div style="position:absolute;top:-10px;left:12px;background:var(--bg-card2);padding:0 6px;font-size:0.6rem;font-weight:700;color:#4ade80;text-transform:uppercase;letter-spacing:0.08em;">DIM_CHANNEL</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 1rem;">
        @foreach(['channel_id (PK)','nama_channel','tipe','platform','komisi_pct','is_online'] as $f)
        <div style="font-size:0.7rem;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.03);color:{{ $f==='channel_id (PK)' ? '#86efac' : 'var(--text-muted)' }};">
          {{ $f }}
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Legend --}}
  <div style="display:flex;gap:2rem;justify-content:center;margin-top:2rem;font-size:0.78rem;color:var(--text-muted);flex-wrap:wrap;">
    <span><span style="color:var(--purple-light);">⭐</span> Fact Table (Pusat)</span>
    <span><span style="color:var(--border-glow);">■</span> Dimension Table</span>
    <span><span style="color:#c084fc;">PK</span> Primary Key</span>
    <span><span style="color:#a855f7;">FK</span> Foreign Key</span>
    <span><span style="color:#4ade80;">∑</span> Measures / Agregasi</span>
  </div>
</div>

{{-- Sumber Data --}}
<div class="glass-card" style="padding:2rem;margin-bottom:1.5rem;">
  <div class="chart-title" style="margin-bottom:1.5rem;">🔗 Mapping Sumber Data ke Dimension Table</div>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;">
    @foreach([
      ['📅','Reservation System','DIM_DATE, FACT_BOOKING','Pemesanan kamar & restoran','#a855f7'],
      ['💳','Payment System','FACT_BOOKING (total_bayar)','Transaksi & riwayat bayar','#22d3ee'],
      ['👤','CRM','DIM_CUSTOMER','Profil & preferensi tamu','#ec4899'],
      ['🌐','Online Booking','DIM_CHANNEL, FACT_BOOKING','Reservasi platform digital','#f59e0b'],
    ] as [$icon,$name,$tables,$desc,$color])
    <div style="background:var(--bg-card2);border:1px solid {{ $color }}33;border-radius:var(--radius-sm);padding:1.25rem;">
      <div style="font-size:1.5rem;margin-bottom:0.75rem;">{{ $icon }}</div>
      <div style="font-weight:700;font-size:0.875rem;color:var(--text-primary);margin-bottom:4px;">{{ $name }}</div>
      <div style="font-size:0.72rem;color:{{ $color }};font-weight:600;margin-bottom:6px;">→ {{ $tables }}</div>
      <div style="font-size:0.75rem;color:var(--text-muted);">{{ $desc }}</div>
    </div>
    @endforeach
  </div>
</div>

{{-- FACT_METRICS --}}
<div class="chart-wrap animate-fade-up delay-4">
  <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem;">
    <div class="chart-title">📊 FACT_METRICS — KPI Harian</div>
    <span class="badge badge-warning" style="font-size:0.65rem;">Fact Table Tambahan</span>
  </div>
  <div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem;align-items:start;">
    <div style="background:linear-gradient(135deg,rgba(245,158,11,0.15),rgba(245,158,11,0.05));border:1px solid rgba(245,158,11,0.3);border-radius:var(--radius-sm);padding:1.25rem;">
      <div style="font-size:0.7rem;color:#fbbf24;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.75rem;">FACT_METRICS</div>
      @foreach([
        ['metric_id (PK)','#fbbf24'],
        ['date_id (FK)','#f59e0b'],
        ['hotel_id (FK)','#f59e0b'],
        ['occupancy_rate','var(--text-secondary)'],
        ['adr','var(--text-secondary)'],
        ['revpar','var(--text-secondary)'],
        ['total_revenue','var(--text-secondary)'],
        ['total_bookings','var(--text-secondary)'],
        ['cancelled_bookings','var(--text-secondary)'],
      ] as [$f,$c])
      <div style="font-size:0.72rem;padding:4px 0;border-bottom:1px solid rgba(255,255,255,0.03);color:{{ $c }};">
        {{ $f }}
      </div>
      @endforeach
    </div>
    <div>
      <div style="font-size:0.85rem;color:var(--text-secondary);margin-bottom:1rem;">
        <strong style="color:var(--text-primary);">FACT_METRICS</strong> menyimpan agregasi KPI harian per properti
        untuk mempercepat query dashboard tanpa perlu join banyak tabel.
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
        @foreach([
          ['Occupancy Rate','Persentase kamar terisi','(kamar_terisi / total_kamar) × 100'],
          ['ADR','Average Daily Rate','total_revenue / kamar_terisi'],
          ['RevPAR','Revenue Per Available Room','ADR × occupancy_rate'],
          ['Cancellation Rate','Tingkat pembatalan','(cancelled / total) × 100'],
        ] as [$name,$desc,$formula])
        <div style="background:var(--bg-card2);border:1px solid var(--border-subtle);border-radius:var(--radius-sm);padding:0.875rem;">
          <div style="font-weight:700;font-size:0.82rem;color:var(--purple-light);margin-bottom:3px;">{{ $name }}</div>
          <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:4px;">{{ $desc }}</div>
          <div style="font-size:0.68rem;color:#4ade80;font-family:monospace;">{{ $formula }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

@endsection