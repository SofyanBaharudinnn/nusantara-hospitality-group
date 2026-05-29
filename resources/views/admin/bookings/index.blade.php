@extends('layouts.dashboard')
@section('title', 'Kelola Booking')
@section('page-title', '📋 Kelola Booking')

@section('content')

{{-- Filter --}}
<div class="glass-card" style="padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
  <form method="GET" action="{{ route('admin.bookings.index') }}"
    style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
    <input type="text" name="search" class="form-input"
      style="width:220px;padding:8px 12px;"
      placeholder="🔍 Cari nama tamu / kode..."
      value="{{ request('search') }}">
    <select name="hotel_id" class="form-input" style="width:180px;padding:8px 12px;">
      <option value="">Semua Properti</option>
      @foreach($hotels as $h)
        <option value="{{ $h->id }}" {{ request('hotel_id')==$h->id ? 'selected' : '' }}>
          {{ $h->nama }}
        </option>
      @endforeach
    </select>
    <select name="status" class="form-input" style="width:150px;padding:8px 12px;">
      <option value="">Semua Status</option>
      @foreach($statuses as $s)
        <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>
          {{ ucfirst($s) }}
        </option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-primary btn-sm">🔍 Filter</button>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-sm">Reset</a>
    <div style="margin-left:auto;">
      <div style="margin-left:auto;display:flex;gap:8px;">
  <a href="{{ route('admin.export.excel.bookings', request()->all()) }}"
    class="btn btn-outline btn-sm"
    style="color:#4ade80;border-color:rgba(74,222,128,0.3);">
    📊 Excel
  </a>
  <a href="{{ route('admin.export.pdf.bookings', request()->all()) }}"
    class="btn btn-outline btn-sm"
    style="color:#f87171;border-color:rgba(248,113,113,0.3);">
    📄 PDF
  </a>
      <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
        ➕ Tambah Booking
      </a>
    </div>
  </form>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
  @foreach([
    ['Total Booking',$bookings->total(),'📋','var(--purple-light)'],
    ['Confirmed',$bookings->getCollection()->where('status','confirmed')->count(),'✅','#4ade80'],
    ['Pending',$bookings->getCollection()->where('status','pending')->count(),'⏳','#fbbf24'],
    ['Cancelled',$bookings->getCollection()->where('status','cancelled')->count(),'❌','#f87171'],
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

{{-- Table --}}
<div class="chart-wrap animate-fade-up delay-2">
  <div style="overflow-x:auto;">
    <table class="data-table">
      <thead>
        <tr>
          <th>Kode</th><th>Tamu</th><th>Properti</th><th>Kamar</th>
          <th>Check-in</th><th>Checkout</th><th>Malam</th>
          <th>Total</th><th>Channel</th><th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bookings as $b)
        <tr>
          <td style="color:var(--purple-light);font-weight:700;font-size:0.8rem;">
            {{ $b->kode_booking }}
          </td>
          <td style="color:var(--text-primary);font-weight:600;">
            {{ $b->customer->nama ?? '-' }}
          </td>
          <td style="font-size:0.82rem;">{{ $b->hotel->nama ?? '-' }}</td>
          <td>
            <span class="badge" style="font-size:0.62rem;background:rgba(124,58,237,0.1);color:var(--purple-glow);border:1px solid rgba(124,58,237,0.2);">
              {{ ucfirst($b->room->tipe ?? '-') }}
            </span>
          </td>
          <td style="font-size:0.82rem;color:var(--text-secondary);">
            {{ $b->tgl_checkin->format('d M Y') }}
          </td>
          <td style="font-size:0.82rem;color:var(--text-secondary);">
            {{ $b->tgl_checkout->format('d M Y') }}
          </td>
          <td style="text-align:center;">{{ $b->jml_malam }}</td>
          <td style="color:var(--text-primary);font-weight:600;font-size:0.85rem;">
            Rp {{ number_format($b->total_bayar, 0, ',', '.') }}
          </td>
          <td style="font-size:0.78rem;color:var(--text-muted);">
            {{ $b->channel->nama ?? '-' }}
          </td>
          <td>
            @if($b->status === 'confirmed')
              <span class="badge badge-success" style="font-size:0.62rem;">✓ Confirmed</span>
            @elseif($b->status === 'pending')
              <span class="badge badge-warning" style="font-size:0.62rem;">⏳ Pending</span>
            @elseif($b->status === 'completed')
              <span class="badge badge-success" style="font-size:0.62rem;">✅ Completed</span>
            @else
              <span class="badge badge-danger" style="font-size:0.62rem;">✗ Cancelled</span>
            @endif
          </td>
          <td>
            <div style="display:flex;gap:6px;">
              <a href="{{ route('admin.bookings.show', $b->id) }}"
                class="btn btn-outline btn-sm"
                style="padding:4px 10px;font-size:0.72rem;">👁️</a>
              <a href="{{ route('admin.bookings.edit', $b->id) }}"
                class="btn btn-outline btn-sm"
                style="padding:4px 10px;font-size:0.72rem;color:#fbbf24;border-color:rgba(251,191,36,0.3);">✏️</a>
              <form method="POST" action="{{ route('admin.bookings.destroy', $b->id) }}"
                onsubmit="return confirm('Hapus booking {{ $b->kode_booking }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline btn-sm"
                  style="padding:4px 10px;font-size:0.72rem;color:#f87171;border-color:rgba(248,113,113,0.3);">
                  🗑️
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="11" style="text-align:center;padding:2rem;color:var(--text-muted);">
            Belum ada data booking.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div style="margin-top:1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;">
    <div style="font-size:0.78rem;color:var(--text-muted);">
      Menampilkan {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }}
      dari {{ $bookings->total() }} booking
    </div>
    <div style="display:flex;gap:6px;">
      @if($bookings->onFirstPage())
        <span class="btn btn-outline btn-sm" style="opacity:0.4;cursor:not-allowed;">← Prev</span>
      @else
        <a href="{{ $bookings->previousPageUrl() }}" class="btn btn-outline btn-sm">← Prev</a>
      @endif
      @if($bookings->hasMorePages())
        <a href="{{ $bookings->nextPageUrl() }}" class="btn btn-outline btn-sm">Next →</a>
      @else
        <span class="btn btn-outline btn-sm" style="opacity:0.4;cursor:not-allowed;">Next →</span>
      @endif
    </div>
  </div>
</div>

@endsection