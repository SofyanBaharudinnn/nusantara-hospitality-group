@extends('layouts.dashboard')
@section('title', 'Kelola Tamu')
@section('page-title', '🧑‍💼 Kelola Data Tamu')

@section('content')

{{-- Filter --}}
<div class="glass-card" style="padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
  <form method="GET" action="{{ route('admin.tamu.index') }}"
    style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
    <input type="text" name="search" class="form-input"
      style="width:220px;padding:8px 12px;"
      placeholder="🔍 Cari nama / email..."
      value="{{ request('search') }}">
    <select name="segmen" class="form-input" style="width:150px;padding:8px 12px;">
      <option value="">Semua Segmen</option>
      @foreach($segmens as $s)
      <option value="{{ $s }}" {{ request('segmen')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    <select name="tier" class="form-input" style="width:140px;padding:8px 12px;">
      <option value="">Semua Tier</option>
      @foreach($tiers as $t)
      <option value="{{ $t }}" {{ request('tier')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-primary btn-sm">🔍 Filter</button>
    <a href="{{ route('admin.tamu.index') }}" class="btn btn-outline btn-sm">Reset</a>
    <div style="margin-left:auto;">
      <div style="margin-left:auto;display:flex;gap:8px;">
  <a href="{{ route('admin.export.excel.customers') }}"
    class="btn btn-outline btn-sm"
    style="color:#4ade80;border-color:rgba(74,222,128,0.3);">
    📊 Excel
  </a>
  <a href="{{ route('admin.export.pdf.customers') }}"
    class="btn btn-outline btn-sm"
    style="color:#f87171;border-color:rgba(248,113,113,0.3);">
    📄 PDF
  </a>
      <a href="{{ route('admin.tamu.create') }}" class="btn btn-primary">➕ Tambah Tamu</a>
    </div>
  </form>
</div>

{{-- Table --}}
<div class="chart-wrap animate-fade-up">
  <div style="overflow-x:auto;">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th><th>Nama</th><th>Email</th><th>Segmen</th>
          <th>Negara</th><th>Total Kunjungan</th><th>Total Spending</th>
          <th>Tier</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $c)
        <tr>
          <td style="color:var(--text-muted);">{{ $loop->iteration }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:8px;">
              <div class="avatar" style="width:30px;height:30px;font-size:0.7rem;">
                {{ strtoupper(substr($c->nama,0,2)) }}
              </div>
              <span style="color:var(--text-primary);font-weight:600;">{{ $c->nama }}</span>
            </div>
          </td>
          <td style="font-size:0.82rem;color:var(--text-secondary);">{{ $c->email }}</td>
          <td>
            @php
            $segColors=['vip'=>'#a855f7','corporate'=>'#22d3ee','leisure'=>'#ec4899','group'=>'#f59e0b'];
            @endphp
            <span style="color:{{ $segColors[$c->segmen]??'#a89fc8' }};font-weight:600;font-size:0.82rem;">
              {{ ucfirst($c->segmen) }}
            </span>
          </td>
          <td style="font-size:0.82rem;">{{ $c->negara }}</td>
          <td style="text-align:center;color:var(--text-primary);font-weight:600;">
            {{ $c->total_kunjungan }}x
          </td>
          <td style="color:var(--purple-light);font-weight:600;font-size:0.85rem;">
            Rp {{ number_format($c->total_spending,0,',','.') }}
          </td>
          <td>
            @php $tierClass=['platinum'=>'badge-purple','gold'=>'badge-warning','silver'=>'badge-success']; @endphp
            <span class="badge {{ $tierClass[$c->tier]??'' }}" style="font-size:0.62rem;">
              {{ ucfirst($c->tier) }}
            </span>
          </td>
          <td>
            <div style="display:flex;gap:6px;">
              <a href="{{ route('admin.tamu.show', $c->guest_key) }}"
                class="btn btn-outline btn-sm" style="padding:4px 10px;font-size:0.72rem;">👁️</a>
              <a href="{{ route('admin.tamu.edit', $c->guest_key) }}"
                class="btn btn-outline btn-sm"
                style="padding:4px 10px;font-size:0.72rem;color:#fbbf24;border-color:rgba(251,191,36,0.3);">✏️</a>
              <form method="POST" action="{{ route('admin.tamu.destroy', $c->guest_key) }}"
                onsubmit="return confirm('Hapus tamu {{ $c->nama }}?')">
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
          <td colspan="9" style="text-align:center;padding:2rem;color:var(--text-muted);">
            Belum ada data tamu.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div style="margin-top:1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;">
    <div style="font-size:0.78rem;color:var(--text-muted);">
      {{ $customers->total() }} tamu terdaftar
    </div>
    <div style="display:flex;gap:6px;">
      @if($customers->onFirstPage())
        <span class="btn btn-outline btn-sm" style="opacity:0.4;cursor:not-allowed;">← Prev</span>
      @else
        <a href="{{ $customers->previousPageUrl() }}" class="btn btn-outline btn-sm">← Prev</a>
      @endif
      @if($customers->hasMorePages())
        <a href="{{ $customers->nextPageUrl() }}" class="btn btn-outline btn-sm">Next →</a>
      @else
        <span class="btn btn-outline btn-sm" style="opacity:0.4;cursor:not-allowed;">Next →</span>
      @endif
    </div>
  </div>
</div>

@endsection