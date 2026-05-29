@extends('layouts.dashboard')
@section('title', 'Detail Hotel')
@section('page-title', '🏨 '.$hotel->nama)

@section('content')

{{-- Header --}}
<div class="glass-card" style="padding:1.5rem 2rem;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
  <div>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:4px;">
      <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:800;">{{ $hotel->nama }}</div>
      <span class="badge {{ $hotel->is_active ? 'badge-success':'badge-danger' }}" style="font-size:0.65rem;">
        {{ $hotel->is_active ? '● Aktif':'● Nonaktif' }}
      </span>
    </div>
    <div style="font-size:0.82rem;color:var(--text-muted);">
      {{ $hotel->kota }}, {{ $hotel->provinsi }} —
      @for($i=1;$i<=5;$i++)<span style="color:{{ $i<=$hotel->bintang?'#fbbf24':'var(--text-muted)' }};">★</span>@endfor
    </div>
  </div>
  <div style="display:flex;gap:0.75rem;">
    <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-primary btn-sm">✏️ Edit</a>
    <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
  </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
  @foreach([
    ['Total Kamar', $hotel->kapasitas_total, '🛏️', 'var(--purple-light)'],
    ['Total Booking', $hotel->bookings_count, '📋', '#22d3ee'],
    ['Tipe', ucfirst($hotel->tipe), '🏨', '#ec4899'],
    ['Bintang', $hotel->bintang . ' ★', '⭐', '#fbbf24'],
  ] as $s)
  <div class="stat-card animate-fade-up">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.5rem;">
      <div class="stat-label">{{ $s[0] }}</div>
      <span style="font-size:1.2rem;">{{ $s[2] }}</span>
    </div>
    <div class="stat-value" style="font-size:1.8rem;color:{{ $s[3] }};">{{ $s[1] }}</div>
  </div>
  @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">

  {{-- Info Properti --}}
  <div class="chart-wrap">
    <div class="chart-title" style="margin-bottom:1rem;">📋 Informasi Properti</div>
    @foreach([
      ['Nama', $hotel->nama],
      ['Tipe', ucfirst($hotel->tipe)],
      ['Kota', $hotel->kota],
      ['Provinsi', $hotel->provinsi],
      ['Alamat', $hotel->alamat ?? '-'],
      ['Telepon', $hotel->telepon ?? '-'],
      ['Email', $hotel->email ?? '-'],
    ] as [$label, $val])
    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border-subtle);">
      <span style="font-size:0.8rem;color:var(--text-muted);">{{ $label }}</span>
      <span style="font-size:0.85rem;color:var(--text-primary);font-weight:500;">{{ $val }}</span>
    </div>
    @endforeach
  </div>

  {{-- Daftar Kamar --}}
  <div class="chart-wrap">
    <div class="chart-title" style="margin-bottom:1rem;">🛏️ Tipe Kamar Tersedia</div>
    @php $roomGroups = $rooms->groupBy('tipe'); @endphp
    @foreach($roomGroups as $tipe => $roomList)
    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border-subtle);">
      <div>
        <div style="font-weight:600;font-size:0.875rem;color:var(--text-primary);">{{ ucfirst($tipe) }}</div>
        <div style="font-size:0.72rem;color:var(--text-muted);">{{ $roomList->count() }} kamar</div>
      </div>
      <div style="text-align:right;">
        <div style="font-size:0.82rem;color:var(--purple-light);font-weight:600;">
          Rp {{ number_format($roomList->first()->harga_dasar,0,',','.') }}
        </div>
        <div style="font-size:0.68rem;color:var(--text-muted);">per malam</div>
      </div>
    </div>
    @endforeach
  </div>
</div>

{{-- Recent Bookings --}}
<div class="chart-wrap">
  <div class="chart-title" style="margin-bottom:1.25rem;">📋 Booking Terbaru</div>
  <div style="overflow-x:auto;">
    <table class="data-table">
      <thead>
        <tr><th>Kode</th><th>Tamu</th><th>Kamar</th><th>Check-in</th><th>Checkout</th><th>Total</th><th>Status</th></tr>
      </thead>
      <tbody>
        @forelse($recentBookings as $b)
        <tr>
          <td style="color:var(--purple-light);font-weight:600;font-size:0.8rem;">{{ $b->kode_booking }}</td>
          <td style="color:var(--text-primary);">{{ $b->customer->nama ?? '-' }}</td>
          <td><span class="badge badge-purple" style="font-size:0.62rem;">{{ ucfirst($b->room->tipe??'-') }}</span></td>
          <td style="font-size:0.82rem;">{{ $b->tgl_checkin->format('d M Y') }}</td>
          <td style="font-size:0.82rem;">{{ $b->tgl_checkout->format('d M Y') }}</td>
          <td style="font-weight:600;">Rp {{ number_format($b->total_bayar,0,',','.') }}</td>
          <td>
            @if($b->status==='confirmed') <span class="badge badge-success" style="font-size:0.62rem;">✓ Confirmed</span>
            @elseif($b->status==='pending') <span class="badge badge-warning" style="font-size:0.62rem;">⏳ Pending</span>
            @elseif($b->status==='completed') <span class="badge badge-success" style="font-size:0.62rem;">✅ Done</span>
            @else <span class="badge badge-danger" style="font-size:0.62rem;">✗ Cancelled</span>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Belum ada booking.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection