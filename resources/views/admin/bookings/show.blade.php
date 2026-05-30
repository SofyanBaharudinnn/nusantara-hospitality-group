@extends('layouts.dashboard')
@section('title', 'Detail Booking')
@section('page-title', '👁️ Detail Booking')

@section('content')

<div style="max-width:900px;">

  {{-- Header --}}
  <div class="glass-card" style="padding:1.5rem 2rem;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div>
      <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:800;color:var(--purple-light);">
        {{ $booking->kode_booking }}
      </div>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      @if($booking->status === 'confirmed')
        <span class="badge badge-success">✓ Confirmed</span>
      @elseif($booking->status === 'pending')
        <span class="badge badge-warning">⏳ Pending</span>
      @elseif($booking->status === 'completed')
        <span class="badge badge-success">✅ Completed</span>
      @else
        <span class="badge badge-danger">✗ Cancelled</span>
      @endif
      <a href="{{ route('admin.bookings.edit', $booking->reservation_key) }}" class="btn btn-primary btn-sm">
        ✏️ Edit
      </a>
      <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

    {{-- Info Tamu --}}
    <div class="chart-wrap">
      <div class="chart-title" style="margin-bottom:1rem;">👤 Info Tamu</div>
      @php $c = $booking->customer; @endphp
      <div style="display:flex;flex-direction:column;gap:0.75rem;">
        @foreach([
          ['Nama',ucfirst($c->nama ?? '-')],
          ['Email',$c->email ?? '-'],
          ['Telepon',$c->telepon ?? '-'],
          ['Segmen',ucfirst($c->segmen ?? '-')],
          ['Negara',$c->negara ?? '-'],
          ['Tier',ucfirst($c->tier ?? '-')],
        ] as [$label,$val])
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border-subtle);">
          <span style="font-size:0.8rem;color:var(--text-muted);">{{ $label }}</span>
          <span style="font-size:0.85rem;color:var(--text-primary);font-weight:500;">{{ $val }}</span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Info Properti --}}
    <div class="chart-wrap">
      <div class="chart-title" style="margin-bottom:1rem;">🏨 Info Properti & Kamar</div>
      <div style="display:flex;flex-direction:column;gap:0.75rem;">
        @foreach([
          ['Hotel',$booking->hotel->nama ?? '-'],
          ['Kota',$booking->hotel->kota ?? '-'],
          ['Kamar',$booking->room->nomor_kamar ?? '-'],
          ['Tipe Kamar',ucfirst($booking->room->tipe ?? '-')],
          ['Lantai',$booking->room->lantai ?? '-'],
          ['Channel',$booking->channel->nama ?? '-'],
        ] as [$label,$val])
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border-subtle);">
          <span style="font-size:0.8rem;color:var(--text-muted);">{{ $label }}</span>
          <span style="font-size:0.85rem;color:var(--text-primary);font-weight:500;">{{ $val }}</span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Info Menginap --}}
    <div class="chart-wrap">
      <div class="chart-title" style="margin-bottom:1rem;">📅 Info Menginap</div>
      <div style="display:flex;flex-direction:column;gap:0.75rem;">
        @foreach([
          ['Check-in',$booking->tgl_checkin ? $booking->tgl_checkin->format('d M Y') : '-'],
          ['Check-out',$booking->tgl_checkout ? $booking->tgl_checkout->format('d M Y') : '-'],
          ['Jumlah Malam',$booking->jml_malam . ' malam'],
          ['Jumlah Tamu',$booking->jml_tamu . ' orang'],
        ] as [$label,$val])
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border-subtle);">
          <span style="font-size:0.8rem;color:var(--text-muted);">{{ $label }}</span>
          <span style="font-size:0.85rem;color:var(--text-primary);font-weight:500;">{{ $val }}</span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Info Pembayaran --}}
    <div class="chart-wrap">
      <div class="chart-title" style="margin-bottom:1rem;">💰 Info Pembayaran</div>
      <div style="display:flex;flex-direction:column;gap:0.75rem;">
        @foreach([
          ['Harga/Malam','Rp '.number_format($booking->harga_per_malam,0,',','.')],
          ['Subtotal','Rp '.number_format($booking->harga_per_malam * $booking->jml_malam,0,',','.')],
          ['Diskon','Rp '.number_format($booking->diskon,0,',','.')],
          ['Total Bayar','Rp '.number_format($booking->total_bayar,0,',','.')],
          ['Rating',$booking->rating ? str_repeat('⭐',$booking->rating) : 'Belum ada'],
        ] as [$label,$val])
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border-subtle);">
          <span style="font-size:0.8rem;color:var(--text-muted);">{{ $label }}</span>
          <span style="font-size:0.85rem;color:{{ $label==='Total Bayar' ? 'var(--purple-light)' : 'var(--text-primary)' }};font-weight:{{ $label==='Total Bayar' ? '700' : '500' }};">
            {{ $val }}
          </span>
        </div>
        @endforeach
      </div>

      @if($booking->catatan)
      <div style="margin-top:1rem;padding:0.75rem;background:rgba(124,58,237,0.06);border-radius:var(--radius-sm);">
        <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:4px;">Catatan:</div>
        <div style="font-size:0.85rem;color:var(--text-secondary);">{{ $booking->catatan }}</div>
      </div>
      @endif
    </div>
  </div>

  {{-- Actions --}}
  <div style="margin-top:1.5rem;display:flex;gap:0.75rem;">
    <a href="{{ route('admin.bookings.edit', $booking->reservation_key) }}" class="btn btn-primary">
      ✏️ Edit Booking
    </a>
    <form method="POST" action="{{ route('admin.bookings.destroy', $booking->reservation_key) }}"
      onsubmit="return confirm('Hapus booking ini?')">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-outline"
        style="color:#f87171;border-color:rgba(248,113,113,0.3);">
        🗑️ Hapus
      </button>
    </form>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline">← Kembali</a>
  </div>
</div>

@endsection