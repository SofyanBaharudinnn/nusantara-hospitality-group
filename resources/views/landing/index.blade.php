@extends('layouts.app')
@section('title', 'Nusantara Hospitality Group — Data Intelligence Platform')
@section('content')

{{-- ── NAVBAR ── --}}
<nav class="navbar z1" id="navbar">
  <div class="container navbar-inner">
    <a href="/" class="navbar-logo">
      <div class="navbar-logo-icon">🏨</div>
      <div class="navbar-logo-text">NHG Analytics<span>Nusantara Hospitality Group</span></div>
    </a>
    <ul class="navbar-links">
      <li><a href="#fitur"  onclick="smoothScroll('#fitur')">Fitur</a></li>
      <li><a href="#data"   onclick="smoothScroll('#data')">Data</a></li>
      <li><a href="#skema"  onclick="smoothScroll('#skema')">Skema</a></li>
      <li><a href="#about"  onclick="smoothScroll('#about')">Tentang</a></li>
      <li><a href="#lokasi" onclick="smoothScroll('#lokasi')">Lokasi</a></li>
    </ul>
    <div class="navbar-actions">
      <a href="{{ route('login') }}"    class="btn btn-outline btn-sm">Masuk</a>
      <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
    </div>
  </div>
</nav>

{{-- ── HERO ── --}}
<section style="min-height:100vh;display:flex;align-items:center;padding-top:80px;position:relative;overflow:hidden;">
  <div class="orb orb-purple" style="width:600px;height:600px;top:-100px;right:-100px;opacity:0.5;"></div>
  <div class="orb orb-pink"   style="width:400px;height:400px;bottom:-50px;left:-100px;opacity:0.4;"></div>
  <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(124,58,237,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,0.04) 1px,transparent 1px);background-size:60px 60px;pointer-events:none;"></div>

  <div class="container z1">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
      <div>
        <div class="badge badge-purple animate-fade-up" style="margin-bottom:1.5rem;">
          <span style="width:6px;height:6px;background:var(--purple-glow);border-radius:50%;display:inline-block;"></span>
          Data Intelligence Platform 
        </div>
        <h1 class="section-title animate-fade-up delay-1" style="margin-bottom:1.5rem;">
          Kelola Data<br><span class="gradient-text">Hotel & Hospitality</span><br>Lebih Cerdas
        </h1>
        <p class="section-subtitle animate-fade-up delay-2" style="margin-bottom:2.5rem;">
          Platform Analitik Terpadu Untuk Nusantara Hospitality Group. Pantau Okupansi,
          Analisis Perilaku Tamu, Dan Temukan Tren Musiman Dari Satu Dashboard Terintegrasi.
        </p>
        <div style="display:flex;gap:1rem;flex-wrap:wrap;" class="animate-fade-up delay-3">
          <a href="{{ route('register') }}" class="btn btn-primary btn-lg">🚀 Mulai Sekarang</a>
          <a href="#fitur" onclick="smoothScroll('#fitur')" class="btn btn-outline btn-lg">Lihat Fitur ↓</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3rem;" class="animate-fade-up delay-4">
          <div style="text-align:center;">
            <div style="font-family:var(--font-display);font-size:2rem;font-weight:800;color:var(--purple-light);">3+</div>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:2px;">Properti</div>
          </div>
          <div style="text-align:center;border-left:1px solid var(--border-subtle);border-right:1px solid var(--border-subtle);">
            <div style="font-family:var(--font-display);font-size:2rem;font-weight:800;color:var(--purple-light);">4</div>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:2px;">Sumber Data</div>
          </div>
          <div style="text-align:center;">
            <div style="font-family:var(--font-display);font-size:2rem;font-weight:800;color:var(--purple-light);">Real‑time</div>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:2px;">Analitik</div>
          </div>
        </div>
      </div>

      <div class="animate-fade-up delay-3" style="position:relative;">
        <div class="glass-card" style="padding:1.5rem;overflow:hidden;">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:1.25rem;">
            <div style="width:10px;height:10px;border-radius:50%;background:#ef4444;"></div>
            <div style="width:10px;height:10px;border-radius:50%;background:#f59e0b;"></div>
            <div style="width:10px;height:10px;border-radius:50%;background:#22c55e;"></div>
            <span style="font-size:0.75rem;color:var(--text-muted);margin-left:8px;">Nusantara Hospitality Group</span>
          </div>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;margin-bottom:1rem;">
            <div style="background:rgba(124,58,237,0.1);border:1px solid var(--border-subtle);border-radius:10px;padding:0.75rem;">
              <div style="font-size:0.65rem;color:var(--text-muted);text-transform:uppercase;">Okupansi</div>
              <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;color:var(--purple-light);">87%</div>
              <div style="font-size:0.65rem;color:#4ade80;">▲ 5.2%</div>
            </div>
            <div style="background:rgba(236,72,153,0.08);border:1px solid rgba(236,72,153,0.15);border-radius:10px;padding:0.75rem;">
              <div style="font-size:0.65rem;color:var(--text-muted);text-transform:uppercase;">Tamu</div>
              <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;color:var(--accent-pink);">1.2K</div>
              <div style="font-size:0.65rem;color:#4ade80;">▲ 12%</div>
            </div>
            <div style="background:rgba(34,211,238,0.07);border:1px solid rgba(34,211,238,0.15);border-radius:10px;padding:0.75rem;">
              <div style="font-size:0.65rem;color:var(--text-muted);text-transform:uppercase;">Revenue</div>
              <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;color:var(--accent-cyan);">2.4M</div>
              <div style="font-size:0.65rem;color:#4ade80;">▲ 8.7%</div>
            </div>
          </div>
          <div style="height:120px;background:rgba(0,0,0,0.2);border-radius:8px;overflow:hidden;margin-bottom:1rem;">
            <canvas id="heroChart" style="width:100%;height:100%;"></canvas>
          </div>
          <div style="font-size:0.72rem;">
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);color:var(--text-muted);">
              <span>Hotel</span><span>Kamar</span><span>Status</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;color:var(--text-secondary);">
              <span>NHG Jakarta</span><span>148/180</span><span style="color:#4ade80;">● Aktif</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;color:var(--text-secondary);">
              <span>NHG Bali Resort</span><span>92/120</span><span style="color:#4ade80;">● Aktif</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;color:var(--text-secondary);">
              <span>NHG Surabaya</span><span>65/90</span><span style="color:#fbbf24;">● Sedang</span>
            </div>
          </div>
        </div>
        <div style="position:absolute;top:-16px;right:-16px;background:linear-gradient(135deg,var(--purple-core),var(--purple-light));border-radius:12px;padding:10px 16px;font-size:0.78rem;font-weight:700;box-shadow:0 0 30px rgba(124,58,237,0.5);">⭐ Star Schema</div>
        <div style="position:absolute;bottom:-12px;left:-12px;background:var(--bg-card);border:1px solid var(--border-glow);border-radius:12px;padding:10px 16px;font-size:0.78rem;font-weight:600;color:var(--purple-glow);">📡 Data Real-time</div>
      </div>
    </div>
  </div>
</section>

<div class="container"><div class="divider-line"></div></div>

{{-- ── FITUR ── --}}
<section id="fitur" style="padding:5rem 0;">
  <div class="container z1">
    <div style="text-align:center;margin-bottom:3.5rem;">
      <div class="badge badge-purple" style="margin-bottom:1rem;">Platform Fitur</div>
      <h2 class="section-title" style="margin-bottom:1rem;">
        Semua Yang Dibutuhkan<br><span class="gradient-text">Dalam Satu Platform</span>
      </h2>
      <p class="section-subtitle" style="margin:0 auto;">
        Dari Reservation System Hingga Analitik Mendalam — Semuanya Terhubung.
      </p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;">
      @foreach([
        ['🏨','Ocupancy Tracker','Pantau tingkat hunian hotel, resort, dan restoran secara real-time dengan visualisasi intuitif.'],
        ['👥','Customer Behavior','Analisis pola tamu, preferensi, dan histori kunjungan dari data CRM dan sistem reservasi.'],
        ['📈','Seasonal Trend','Identifikasi tren musiman dan pola permintaan untuk perencanaan kapasitas yang lebih baik.'],
        ['🗄️','Star Schema','Desain data warehouse dengan fact dan dimension table yang optimal untuk query analitik cepat.'],
        ['📋','Laporan Otomatis','Generate laporan PDF & Excel dari data reservasi, pembayaran, dan CRM secara otomatis.'],
        ['🔒','Akses Berlapis','Sistem autentikasi admin dan user dengan permission yang terpisah untuk keamanan data.'],
      ] as $f)
      <div class="stat-card">
        <div style="font-size:2rem;margin-bottom:1rem;">{{ $f[0] }}</div>
        <h3 style="font-family:var(--font-display);font-weight:700;font-size:1.05rem;margin-bottom:0.5rem;">{{ $f[1] }}</h3>
        <p style="font-size:0.875rem;color:var(--text-muted);line-height:1.6;">{{ $f[2] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

<div class="container"><div class="divider-line"></div></div>

{{-- ── DATA ── --}}
<section id="data" style="padding:5rem 0;">
  <div class="container z1">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
      <div>
        <div class="badge badge-purple" style="margin-bottom:1rem;">Integrasi Data</div>
        <h2 class="section-title" style="margin-bottom:1rem;">
          4 Sumber Data<br><span class="gradient-text">Terintegrasi</span>
        </h2>
        <p class="section-subtitle" style="margin-bottom:2rem;">
          Data Dikumpulkan dari Reservation System, Payment System, CRM, dan Online Booking
          Untuk Analisis yang Komprehensif & Akurat.
        </p>
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
          @foreach([
            ['📅','Reservation System','Data pemesanan kamar & meja restoran'],
            ['💳','Payment System','Transaksi & riwayat pembayaran tamu'],
            ['👤','CRM','Profil, preferensi & histori tamu'],
            ['🌐','Online Booking','Reservasi dari platform digital & OTA'],
          ] as $s)
          <div style="display:flex;align-items:center;gap:1rem;padding:1rem;background:var(--bg-card);border:1px solid var(--border-subtle);border-radius:var(--radius-sm);">
            <div style="width:44px;height:44px;background:rgba(124,58,237,0.12);border:1px solid var(--border-glow);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">{{ $s[0] }}</div>
            <div>
              <div style="font-weight:600;font-size:0.9rem;color:var(--text-primary);">{{ $s[1] }}</div>
              <div style="font-size:0.8rem;color:var(--text-muted);">{{ $s[2] }}</div>
            </div>
            <div style="margin-left:auto;"><span class="badge badge-success" style="font-size:0.65rem;">● Live</span></div>
          </div>
          @endforeach
        </div>
      </div>

      <div class="glass-card" style="padding:2rem;text-align:center;position:relative;min-height:360px;display:flex;align-items:center;justify-content:center;">
        <div style="position:relative;width:280px;height:280px;">
          <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:80px;height:80px;background:linear-gradient(135deg,var(--purple-core),var(--purple-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;box-shadow:0 0 40px rgba(124,58,237,0.5);animation:glowPulse 3s ease-in-out infinite;">🏨</div>
          <div style="position:absolute;top:0;left:50%;transform:translateX(-50%);background:var(--bg-card2);border:1px solid var(--border-glow);border-radius:10px;padding:8px 12px;font-size:0.75rem;font-weight:600;">📅 Reservasi</div>
          <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);background:var(--bg-card2);border:1px solid var(--border-glow);border-radius:10px;padding:8px 12px;font-size:0.75rem;font-weight:600;">💳 Pembayaran</div>
          <div style="position:absolute;left:0;top:50%;transform:translateY(-50%);background:var(--bg-card2);border:1px solid var(--border-glow);border-radius:10px;padding:8px 12px;font-size:0.75rem;font-weight:600;">👤 CRM</div>
          <div style="position:absolute;right:0;top:50%;transform:translateY(-50%);background:var(--bg-card2);border:1px solid var(--border-glow);border-radius:10px;padding:8px 12px;font-size:0.75rem;font-weight:600;">🌐 Online</div>
          <svg style="position:absolute;inset:0;width:100%;height:100%;" viewBox="0 0 280 280">
            <line x1="140" y1="40"  x2="140" y2="100" stroke="rgba(124,58,237,0.4)" stroke-width="1.5" stroke-dasharray="4 3"/>
            <line x1="140" y1="240" x2="140" y2="180" stroke="rgba(124,58,237,0.4)" stroke-width="1.5" stroke-dasharray="4 3"/>
            <line x1="40"  y1="140" x2="100" y2="140" stroke="rgba(124,58,237,0.4)" stroke-width="1.5" stroke-dasharray="4 3"/>
            <line x1="240" y1="140" x2="180" y2="140" stroke="rgba(124,58,237,0.4)" stroke-width="1.5" stroke-dasharray="4 3"/>
          </svg>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container"><div class="divider-line"></div></div>

{{-- ── SKEMA ── --}}
<section id="skema" style="padding:5rem 0;">
  <div class="container z1">
    <div style="text-align:center;margin-bottom:3rem;">
      <div class="badge badge-purple" style="margin-bottom:1rem;">Data Warehouse</div>
      <h2 class="section-title" style="margin-bottom:1rem;">Desain <span class="gradient-text">Star Schema</span></h2>
      <p class="section-subtitle" style="margin:0 auto;">Arsitektur Fact & Dimension Table Untuk Analitik Hospitality yang Efisien.</p>
    </div>
    <div class="glass-card" style="padding:2.5rem;overflow:hidden;">
      <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;align-items:center;">
        @foreach([
          ['DIM_DATE','#a855f7',['date_id (PK)','tanggal','bulan','kuartal','tahun','musim']],
          ['DIM_HOTEL','#22d3ee',['hotel_id (PK)','nama_hotel','tipe','kota','bintang']],
          ['FACT_BOOKING','#7c3aed',['booking_id (PK)','date_id (FK)','hotel_id (FK)','room_id (FK)','customer_id (FK)','jml_malam','total_bayar','status','rating']],
          ['DIM_ROOM','#ec4899',['room_id (PK)','tipe_kamar','kapasitas','fasilitas','harga_dasar']],
          ['DIM_CUSTOMER','#f59e0b',['customer_id (PK)','nama','segmen','negara','channel']],
        ] as [$tbl,$col,$fields])
        <div style="background:var(--bg-card2);border:{{ $tbl==='FACT_BOOKING'?'2px solid #a855f7':'1px solid rgba(124,58,237,0.2)' }};border-radius:var(--radius-sm);padding:1rem;{{ $tbl==='FACT_BOOKING'?'box-shadow:0 0 30px rgba(124,58,237,0.3);':'' }}">
          <div style="font-size:0.65rem;color:{{ $col }};font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.75rem;">{{ $tbl==='FACT_BOOKING'?'⭐ ':'' }}{{ $tbl }}</div>
          @foreach($fields as $f)
          <div style="font-size:0.7rem;color:{{ str_contains($f,'FK')?$col:(str_contains($f,'PK')?'#c084fc':'var(--text-muted)') }};padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.03);">{{ $f }}</div>
          @endforeach
        </div>
        @endforeach
      </div>
      <div style="display:flex;gap:2rem;justify-content:center;margin-top:2rem;font-size:0.78rem;color:var(--text-muted);flex-wrap:wrap;">
        <span><span style="color:var(--purple-light);">■</span> Fact Table (Pusat)</span>
        <span><span style="color:var(--border-glow);">■</span> Dimension Table</span>
        <span><span style="color:var(--purple-light);">PK</span> Primary Key</span>
        <span><span style="color:var(--accent-pink);">FK</span> Foreign Key</span>
      </div>
    </div>
  </div>
</section>

<div class="container"><div class="divider-line"></div></div>

{{-- ── ABOUT ── --}}
<section id="about" style="padding:5rem 0;">
  <div class="container z1">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">

      {{-- Kiri: Info --}}
      <div>
        <div class="badge badge-purple" style="margin-bottom:1rem;">Tentang Kami</div>
        <h2 class="section-title" style="margin-bottom:1.25rem;">
          Nusantara<br><span class="gradient-text">Hospitality Group</span>
        </h2>
        <p style="color:var(--text-secondary);font-size:0.95rem;line-height:1.8;margin-bottom:1.5rem;">
          Nusantara Hospitality Group (NHG) Adalah Grup Perhotelan Terkemuka Di Indonesia
          Yang Mengelola Hotel Bintang 5, Resort Mewah, Dan Restoran Premium Di Berbagai
          Kota Strategis.
        </p>
        <p style="color:var(--text-secondary);font-size:0.95rem;line-height:1.8;margin-bottom:2rem;">
          Platform Analitik NHG Dirancang Untuk Membantu Manajemen Dalam Mengambil Keputusan
          Berbasis Data — Mulai Dari Pemantauan Okupansi Real-Time Hingga Analisis Tren Musiman
          Yang Mendalam.
        </p>

        {{-- Stats About --}}
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
          @foreach([
            ['🏨','3+','Properti aktif','hotel, resort & restoran'],
            ['👥','12K+','Tamu terdaftar','di seluruh Indonesia'],
            ['⭐','4.8','Rating rata-rata','dari semua properti'],
            ['📅','2018','Tahun berdiri','7+ tahun pengalaman'],
          ] as $s)
          <div style="padding:1rem;background:var(--bg-card);border:1px solid var(--border-subtle);border-radius:var(--radius-sm);">
            <div style="font-size:1.3rem;margin-bottom:4px;">{{ $s[0] }}</div>
            <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:800;color:var(--purple-light);">{{ $s[1] }}</div>
            <div style="font-size:0.82rem;font-weight:600;color:var(--text-primary);">{{ $s[2] }}</div>
            <div style="font-size:0.72rem;color:var(--text-muted);">{{ $s[3] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Kanan: Properti Cards --}}
      <div style="display:flex;flex-direction:column;gap:1rem;">
        @foreach([
          ['🏨','NHG Jakarta','Hotel Bintang 5','Jl. Sudirman No. 1, Jakarta Pusat','180 Kamar','#a855f7'],
          ['🌴','NHG Bali Resort','Resort Bintang 5','Jl. Pantai Kuta No. 88, Badung','120 Kamar','#ec4899'],
          ['🏩','NHG Surabaya','Hotel Bintang 4','Jl. Tunjungan No. 45, Surabaya','90 Kamar','#22d3ee'],
        ] as [$icon,$nama,$tipe,$alamat,$kamar,$color])
        <div style="display:flex;align-items:center;gap:1rem;padding:1.25rem;background:var(--bg-card);border:1px solid {{ $color }}22;border-radius:var(--radius-md);transition:all 0.3s;"
          onmouseover="this.style.borderColor='{{ $color }}55';this.style.transform='translateX(4px)'"
          onmouseout="this.style.borderColor='{{ $color }}22';this.style.transform='translateX(0)'">
          <div style="width:52px;height:52px;background:{{ $color }}20;border:1px solid {{ $color }}40;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">
            {{ $icon }}
          </div>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:0.95rem;color:var(--text-primary);">{{ $nama }}</div>
            <div style="font-size:0.75rem;color:{{ $color }};font-weight:600;">{{ $tipe }}</div>
            <div style="font-size:0.72rem;color:var(--text-muted);margin-top:2px;">📍 {{ $alamat }}</div>
          </div>
          <div style="text-align:right;flex-shrink:0;">
            <div style="font-family:var(--font-display);font-size:1rem;font-weight:700;color:var(--text-primary);">{{ $kamar }}</div>
            <span class="badge badge-success" style="font-size:0.6rem;">● Aktif</span>
          </div>
        </div>
        @endforeach
      </div>

    </div>
  </div>
</section>

<div class="container"><div class="divider-line"></div></div>

{{-- ── LOKASI ── --}}
<section id="lokasi" style="padding:5rem 0;">
  <div class="container z1">
    <div style="text-align:center;margin-bottom:3rem;">
      <div class="badge badge-purple" style="margin-bottom:1rem;">Lokasi Kami</div>
      <h2 class="section-title" style="margin-bottom:1rem;">
        Temukan <span class="gradient-text">Properti NHG</span>
      </h2>
      <p class="section-subtitle" style="margin:0 auto;">
        Hadir Di Tiga Kota Strategis Indonesia untuk Melayani Tamu Dari Seluruh Penjuru Dunia.
      </p>
    </div>

    {{-- Location Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-bottom:2rem;">
      @foreach([
        ['🏨','NHG Jakarta','Hotel Bintang 5','Jl. Sudirman No. 1','Jakarta Pusat, DKI Jakarta','021-5551234','jakarta@nhg.com','-6.2088,106.8456','#a855f7'],
        ['🌴','NHG Bali Resort','Resort Bintang 5','Jl. Pantai Kuta No. 88','Badung, Bali','0361-5559876','bali@nhg.com','-8.7182,115.1686','#ec4899'],
        ['🏩','NHG Surabaya','Hotel Bintang 4','Jl. Tunjungan No. 45','Surabaya, Jawa Timur','031-5554567','surabaya@nhg.com','-7.2575,112.7521','#22d3ee'],
      ] as [$icon,$nama,$tipe,$jalan,$kota,$telp,$email,$coords,$color])
      <div class="glass-card" style="padding:1.5rem;border-color:{{ $color }}22;">
        <div style="font-size:2rem;margin-bottom:0.75rem;">{{ $icon }}</div>
        <div style="font-family:var(--font-display);font-weight:700;font-size:1rem;margin-bottom:4px;color:var(--text-primary);">{{ $nama }}</div>
        <div style="font-size:0.75rem;color:{{ $color }};font-weight:600;margin-bottom:0.75rem;">{{ $tipe }}</div>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.78rem;color:var(--text-muted);">
          <div>📍 {{ $jalan }}, {{ $kota }}</div>
          <div>📞 {{ $telp }}</div>
          <div>✉️ {{ $email }}</div>
        </div>
        <a href="https://maps.google.com/?q={{ $coords }}"
          target="_blank"
          class="btn btn-outline btn-sm"
          style="width:100%;justify-content:center;margin-top:1rem;font-size:0.78rem;color:{{ $color }};border-color:{{ $color }}44;">
          🗺️ Buka di Google Maps
        </a>
      </div>
      @endforeach
    </div>

    {{-- Embed Peta --}}
    <div class="glass-card" style="padding:0;overflow:hidden;border-radius:var(--radius-lg);">
      <div style="background:var(--bg-card);padding:1rem 1.5rem;border-bottom:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
        <div style="font-family:var(--font-display);font-weight:600;font-size:0.95rem;">🗺️ Peta Lokasi Properti NHG</div>
        <div style="display:flex;gap:8px;">
          <button onclick="showMap('jakarta')"  id="btn-jakarta"
            class="btn btn-primary btn-sm map-btn" style="font-size:0.75rem;">NHG Jakarta</button>
          <button onclick="showMap('bali')"     id="btn-bali"
            class="btn btn-outline btn-sm map-btn" style="font-size:0.75rem;">NHG Bali</button>
          <button onclick="showMap('surabaya')" id="btn-surabaya"
            class="btn btn-outline btn-sm map-btn" style="font-size:0.75rem;">NHG Surabaya</button>
        </div>
      </div>

      {{-- Map Iframes --}}
      <div id="map-jakarta" class="map-frame" style="display:block;">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521!2d106.8227!3d-6.2088!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJl.%20Jend.%20Sudirman%2C%20Jakarta!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid"
          width="100%" height="400" style="border:0;display:block;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>

      <div id="map-bali" class="map-frame" style="display:none;">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3944.6!2d115.1686!3d-8.7182!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd247434ff3bdbd%3A0x79aaacc1c7a1fc28!2sKuta%2C%20Badung%2C%20Bali!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid"
          width="100%" height="400" style="border:0;display:block;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>

      <div id="map-surabaya" class="map-frame" style="display:none;">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.5!2d112.7521!3d-7.2575!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbf8381ac47f%3A0x162bf8e95fc3fb90!2sTunjungan%2C%20Genteng%2C%20Surabaya!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid"
          width="100%" height="400" style="border:0;display:block;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>

  </div>
</section>

<div class="container"><div class="divider-line"></div></div>

{{-- ── CTA ── --}}
<section style="padding:6rem 0;">
  <div class="container z1">
    <div class="glass-card" style="padding:4rem;text-align:center;position:relative;overflow:hidden;">
      <div class="orb orb-purple" style="width:400px;height:400px;top:-100px;left:50%;transform:translateX(-50%);opacity:0.3;"></div>
      <div style="position:relative;z-index:1;">
        <div class="badge badge-purple" style="margin-bottom:1.5rem;">Bergabung Sekarang</div>
        <h2 class="section-title" style="margin-bottom:1rem;">
          Siap Mengoptimalkan<br><span class="gradient-text">Data Hospitaliti Anda?</span>
        </h2>
        <p class="section-subtitle" style="margin:0 auto 2.5rem;">
          Akses Platform Analitik NHG Dalam Satu Dashboard Terintegrasi.
        </p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
          <a href="{{ route('register') }}" class="btn btn-primary btn-lg">🚀 Daftar Gratis</a>
         
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── FOOTER ── --}}
<footer style="border-top:1px solid var(--border-subtle);padding:3rem 0 2rem;">
  <div class="container z1">
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:2rem;margin-bottom:2.5rem;flex-wrap:wrap;">

      {{-- Brand --}}
      <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:1rem;">
          <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--purple-core),var(--purple-light));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">🏨</div>
          <div>
            <div style="font-family:var(--font-display);font-weight:700;font-size:1rem;">NHG Analytics</div>
            <div style="font-size:0.65rem;color:var(--text-muted);">Nusantara Hospitality Group</div>
          </div>
        </div>
        <p style="font-size:0.8rem;color:var(--text-muted);line-height:1.7;max-width:260px;">
          Platform Analitik Data Hospitality Terpadu untuk Pengelolaan Properti Hotel, Resort, dan Restoran Di Seluruh Indonesia.
        </p>
      </div>

      {{-- Navigasi --}}
      <div>
        <div style="font-family:var(--font-display);font-weight:600;font-size:0.85rem;margin-bottom:1rem;color:var(--text-primary);">Navigasi</div>
        @foreach([['#fitur','Fitur'],['#data','Integrasi Data'],['#skema','Star Schema'],['#about','Tentang'],['#lokasi','Lokasi']] as $l)
        <a href="{{ $l[0] }}" onclick="smoothScroll('{{ $l[0] }}')"
          style="display:block;font-size:0.82rem;color:var(--text-muted);text-decoration:none;margin-bottom:6px;transition:color 0.2s;"
          onmouseover="this.style.color='var(--purple-light)'"
          onmouseout="this.style.color='var(--text-muted)'">
          {{ $l[1] }}
        </a>
        @endforeach
      </div>

      {{-- Akun --}}
      <div>
        <div style="font-family:var(--font-display);font-weight:600;font-size:0.85rem;margin-bottom:1rem;color:var(--text-primary);">Akun</div>
        @foreach([[route('login'),'Masuk'],[route('register'),'Daftar']] as $l)
        <a href="{{ $l[0] }}"
          style="display:block;font-size:0.82rem;color:var(--text-muted);text-decoration:none;margin-bottom:6px;transition:color 0.2s;"
          onmouseover="this.style.color='var(--purple-light)'"
          onmouseout="this.style.color='var(--text-muted)'">
          {{ $l[1] }}
        </a>
        @endforeach
      </div>

      {{-- Kontak --}}
      <div>
        <div style="font-family:var(--font-display);font-weight:600;font-size:0.85rem;margin-bottom:1rem;color:var(--text-primary);">Kontak</div>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.78rem;color:var(--text-muted);">
          <div>📍 Merauke, Jakarta, Bali, Surabaya</div>
          <div>📞 021-5551234</div>
          <div>✉️ info@nhg.com</div>
        </div>
      </div>
    </div>

    <div style="border-top:1px solid var(--border-subtle);padding-top:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
      <div style="font-size:0.78rem;color:var(--text-muted);">
        © {{ date('Y') }} Nusantara Hospitality Group. Kelompok 2.
      </div>
      <div style="display:flex;gap:0.75rem;">
        @foreach(['Fitur','Data','Skema','About','Lokasi'] as $nav)
        <a href="#{{ strtolower($nav) }}" onclick="smoothScroll('#{{ strtolower($nav) }}')"
          style="font-size:0.75rem;color:var(--text-muted);text-decoration:none;">
          {{ $nav }}
        </a>
        @endforeach
      </div>
    </div>
  </div>
</footer>

@push('scripts')
<script>
// ── Navbar scroll ──
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// ── Smooth Scroll ──
function smoothScroll(target) {
  event.preventDefault();
  const el = document.querySelector(target);
  if (el) {
    const offset = 80; // tinggi navbar
    const top = el.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: 'smooth' });
  }
}

// ── Map Switcher ──
function showMap(loc) {
  document.querySelectorAll('.map-frame').forEach(f => f.style.display = 'none');
  document.querySelectorAll('.map-btn').forEach(b => {
    b.className = 'btn btn-outline btn-sm map-btn';
    b.style.fontSize = '0.75rem';
  });
  document.getElementById('map-' + loc).style.display = 'block';
  const btn = document.getElementById('btn-' + loc);
  btn.className = 'btn btn-primary btn-sm map-btn';
  btn.style.fontSize = '0.75rem';
}

// ── Hero Chart ──
const ctx = document.getElementById('heroChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
    datasets: [{
      data: [72,78,68,85,90,88,94,87,82,89,91,87],
      fill: true,
      backgroundColor: 'rgba(124,58,237,0.15)',
      borderColor: '#a855f7',
      borderWidth: 2,
      tension: 0.4,
      pointRadius: 0
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { x: { display: false }, y: { display: false, min: 50, max: 100 } }
  }
});
</script>
@endpush

@endsection