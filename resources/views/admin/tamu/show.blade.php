@extends('layouts.dashboard')
@section('title', 'Detail Tamu')
@section('page-title', '🧑‍💼 Detail Tamu')

@section('content')

<div style="max-width:900px;">

  {{-- Header --}}
  <div class="glass-card" style="padding:1.5rem 2rem;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
      <div class="avatar" style="width:56px;height:56px;font-size:1.2rem;">
        {{ strtoupper(substr($tamu->nama,0,2)) }}
      </div>
      <div>
        <div style="font-family:var(--font-display);font-size:1.3rem;font-weight:800;">{{ $tamu->nama }}</div>
        <div style="font-size:0.82rem;color:var(--text-muted);">{{ $tamu->email }}</div>
        @php $tierClass=['platinum'=>'badge-purple','gold'=>'badge-warning','silver'=>'badge-success']; @endphp
        <span class="badge {{ $tierClass[$tamu->tier]??'' }}" style="font-size:0.65rem;margin-top:4px;">
          {{ ucfirst($tamu->tier) }}
        </span>
      </div>
    </div>
    <div style="display:flex;gap:0.75rem;">
      <a href="{{ route('admin.tamu.edit', $tamu->guest_key) }}" class="btn btn-primary btn-sm">✏️ Edit</a>
      <a href="{{ route('admin.tamu.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
  </div>

  {{-- Stats --}}
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
    @foreach([
      ['Total Kunjungan', $tamu->total_kunjungan . 'x', '🔄', 'var(--purple-light)'],
      ['Total Spending', 'Rp '.number_format($tamu->total_spending/1000000,1).'Jt', '💰', '#22d3ee'],
      ['Avg per Kunjungan', $tamu->total_kunjungan>0 ? 'Rp '.number_format($tamu->total_spending/$tamu->total_kunjungan/1000000,1).'Jt' : '-', '📊', '#ec4899'],
    ] as $s)
    <div class="stat-card animate-fade-up">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.5rem;">
        <div class="stat-label">{{ $s[0] }}</div>
        <span style="font-size:1.2rem;">{{ $s[2] }}</span>
      </div>
      <div class="stat-value" style="font-size:1.6rem;color:{{ $s[3] }};">{{ $s[1] }}</div>
    </div>
    @endforeach
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
    {{-- Info Pribadi --}}
    <div class="chart-wrap">
      <div class="chart-title" style="margin-bottom:1rem;">👤 Informasi Pribadi</div>
      @foreach([
        ['Nama', $tamu->nama],
        ['Email', $tamu->email],
        ['Telepon', $tamu->telepon ?? '-'],
        ['Tanggal Lahir', $tamu->tgl_lahir ? $tamu->tgl_lahir->format('d M Y') : '-'],
        ['Negara', $tamu->negara],
        ['Kota Asal', $tamu->kota_asal ?? '-'],
        ['Segmen', ucfirst($tamu->segmen)],
        ['Tier', ucfirst($tamu->tier)],
      ] as [$label,$val])
      <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border-subtle);">
        <span style="font-size:0.78rem;color:var(--text-muted);">{{ $label }}</span>
        <span style="font-size:0.82rem;color:var(--text-primary);font-weight:500;">{{ $val }}</span>
      </div>
      @endforeach
    </div>

    {{-- Properti Favorit --}}
    <div class="chart-wrap">
      <div class="chart-title" style="margin-bottom:1rem;">🏨 Properti Favorit</div>
      @php
      $favHotels = $tamu->bookings->groupBy('hotel_id')
        ->map(fn($b) => ['hotel'=>$b->first()->hotel,'count'=>$b->count()])
        ->sortByDesc('count');
      @endphp
      @foreach($favHotels as $fav)
      <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border-subtle);">
        <div>
          <div style="font-weight:600;font-size:0.875rem;color:var(--text-primary);">{{ $fav['hotel']->nama ?? '-' }}</div>
          <div style="font-size:0.72rem;color:var(--text-muted);">{{ $fav['hotel']->kota ?? '' }}</div>
        </div>
        <span class="badge badge-purple" style="font-size:0.65rem;">{{ $fav['count'] }}x kunjungan</span>
      </div>
      @endforeach
    </div>
  </div>

  {{-- Riwayat Booking --}}
  <div class="chart-wrap">
    <div class="chart-title" style="margin-bottom:1.25rem;">📋 Riwayat Booking</div>
    <div style="overflow-x:auto;">
      <table class="data-table">
        <thead>
          <tr><th>Kode</th><th>Hotel</th><th>Kamar</th><th>Check-in</th><th>Checkout</th><th>Total</th><th>Status</th><th>Rating</th></tr>
        </thead>
        <tbody>
          @forelse($tamu->bookings as $b)
          <tr>
            <td style="color:var(--purple-light);font-weight:600;font-size:0.8rem;">{{ $b->kode_booking }}</td>
            <td style="font-size:0.82rem;">{{ $b->hotel->nama ?? '-' }}</td>
            <td><span class="badge badge-purple" style="font-size:0.62rem;">{{ ucfirst($b->room->tipe??'-') }}</span></td>
            <td style="font-size:0.82rem;">{{ $b->tgl_checkin->format('d M Y') }}</td>
            <td style="font-size:0.82rem;">{{ $b->tgl_checkout->format('d M Y') }}</td>
            <td style="font-weight:600;font-size:0.85rem;">Rp {{ number_format($b->total_bayar,0,',','.') }}</td>
            <td>
              @if($b->status==='confirmed') <span class="badge badge-success" style="font-size:0.62rem;">✓</span>
              @elseif($b->status==='pending') <span class="badge badge-warning" style="font-size:0.62rem;">⏳</span>
              @elseif($b->status==='completed') <span class="badge badge-success" style="font-size:0.62rem;">✅</span>
              @else <span class="badge badge-danger" style="font-size:0.62rem;">✗</span>
              @endif
            </td>
            <td>{{ $b->rating ? str_repeat('⭐',$b->rating) : '-' }}</td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Belum ada booking.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection