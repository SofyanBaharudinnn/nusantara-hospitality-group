@extends('layouts.dashboard')
@section('title', 'Kelola Hotel')
@section('page-title', '🏩 Kelola Hotel & Properti')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
  <div style="font-size:0.875rem;color:var(--text-muted);">
    Total {{ $hotels->count() }} properti terdaftar
  </div>
  <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">
    ➕ Tambah Properti
  </a>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
  @foreach([
    ['Total Properti', $hotels->count(), '🏨', 'var(--purple-light)'],
    ['Total Kamar', $hotels->sum('kapasitas_total'), '🛏️', '#22d3ee'],
    ['Aktif', $hotels->where('is_active', true)->count(), '✅', '#4ade80'],
  ] as $s)
  <div class="stat-card animate-fade-up">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.5rem;">
      <div class="stat-label">{{ $s[0] }}</div>
      <span style="font-size:1.3rem;">{{ $s[2] }}</span>
    </div>
    <div class="stat-value" style="color:{{ $s[3] }};">{{ $s[1] }}</div>
  </div>
  @endforeach
</div>

{{-- Hotel Cards --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;">
  @forelse($hotels as $hotel)
  <div class="stat-card animate-fade-up" style="padding:0;overflow:hidden;">

    {{-- Header Card --}}
    <div style="padding:1.25rem;background:linear-gradient(135deg,rgba(124,58,237,0.2),rgba(168,85,247,0.05));border-bottom:1px solid var(--border-subtle);">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.5rem;">
        <div style="font-size:1.5rem;">
          {{ $hotel->tipe === 'hotel' ? '🏨' : ($hotel->tipe === 'resort' ? '🌴' : '🍽️') }}
        </div>
        <span class="badge {{ $hotel->is_active ? 'badge-success' : 'badge-danger' }}" style="font-size:0.62rem;">
          {{ $hotel->is_active ? '● Aktif' : '● Nonaktif' }}
        </span>
      </div>
      <div style="font-family:var(--font-display);font-weight:700;font-size:1rem;color:var(--text-primary);margin-bottom:2px;">
        {{ $hotel->nama }}
      </div>
      <div style="font-size:0.78rem;color:var(--text-muted);">
        {{ $hotel->kota }}, {{ $hotel->provinsi }}
      </div>
      <div style="margin-top:4px;">
        @for($i = 1; $i <= 5; $i++)
          <span style="font-size:0.8rem;color:{{ $i <= $hotel->bintang ? '#fbbf24' : 'var(--text-muted)' }};">★</span>
        @endfor
      </div>
    </div>

    {{-- Stats --}}
    <div style="padding:1rem 1.25rem;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:1rem;">
        <div style="text-align:center;padding:0.5rem;background:rgba(124,58,237,0.06);border-radius:6px;">
          <div style="font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:var(--purple-light);">
            {{ $hotel->kapasitas_total }}
          </div>
          <div style="font-size:0.65rem;color:var(--text-muted);">Total Kamar</div>
        </div>
        <div style="text-align:center;padding:0.5rem;background:rgba(34,211,238,0.06);border-radius:6px;">
          <div style="font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:#22d3ee;">
            {{ $hotel->bookings_count }}
          </div>
          <div style="font-size:0.65rem;color:var(--text-muted);">Total Booking</div>
        </div>
      </div>

      <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:0.75rem;">
        <span class="badge" style="font-size:0.62rem;background:rgba(124,58,237,0.1);color:var(--purple-glow);border:1px solid rgba(124,58,237,0.2);">
          {{ ucfirst($hotel->tipe) }}
        </span>
        @if($hotel->telepon)
        <span style="margin-left:6px;">📞 {{ $hotel->telepon }}</span>
        @endif
      </div>

      <div style="display:flex;gap:6px;">
        <a href="{{ route('admin.hotels.show', $hotel->id) }}"
          class="btn btn-outline btn-sm" style="flex:1;justify-content:center;font-size:0.75rem;">
          👁️ Detail
        </a>
        <a href="{{ route('admin.hotels.edit', $hotel->id) }}"
          class="btn btn-outline btn-sm" style="flex:1;justify-content:center;font-size:0.75rem;color:#fbbf24;border-color:rgba(251,191,36,0.3);">
          ✏️ Edit
        </a>
        <form method="POST" action="{{ route('admin.hotels.destroy', $hotel->id) }}"
          onsubmit="return confirm('Hapus {{ $hotel->nama }}?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-outline btn-sm"
            style="font-size:0.75rem;color:#f87171;border-color:rgba(248,113,113,0.3);padding:8px 10px;">
            🗑️
          </button>
        </form>
      </div>
    </div>
  </div>
  @empty
  <div style="grid-column:span 3;text-align:center;padding:3rem;color:var(--text-muted);">
    Belum ada properti. <a href="{{ route('admin.hotels.create') }}" style="color:var(--purple-light);">Tambah sekarang →</a>
  </div>
  @endforelse
</div>

@endsection