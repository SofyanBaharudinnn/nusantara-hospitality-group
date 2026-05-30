@extends('layouts.dashboard')
@section('title', 'Profil Tamu')
@section('page-title', '👥 Profil & Demografi Tamu')

@section('content')

{{-- Charts --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
  
  {{-- Segmen --}}
  <div class="glass-card animate-fade-up">
    <div style="font-weight:600;margin-bottom:1rem;color:var(--text-primary);">Distribusi Segmen</div>
    <div style="height:250px;">
      <canvas id="segmenChart"></canvas>
    </div>
  </div>

  {{-- Negara --}}
  <div class="glass-card animate-fade-up delay-1">
    <div style="font-weight:600;margin-bottom:1rem;color:var(--text-primary);">Top 10 Negara Asal</div>
    <div style="height:250px;">
      <canvas id="negaraChart"></canvas>
    </div>
  </div>

</div>

{{-- Recent Guests --}}
<div class="glass-card animate-fade-up delay-2">
  <div style="font-weight:600;margin-bottom:1rem;color:var(--text-primary);">Daftar Tamu Terbaru</div>
  <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));gap:1rem;">
    @foreach($recentGuests as $g)
    <div style="display:flex;align-items:center;gap:10px;padding:0.75rem;background:rgba(255,255,255,0.03);border-radius:8px;border:1px solid rgba(255,255,255,0.05);">
      <div class="avatar" style="width:36px;height:36px;font-size:0.8rem;">
        {{ strtoupper(substr($g->nama,0,2)) }}
      </div>
      <div style="min-width:0;flex:1;">
        <div style="font-weight:600;font-size:0.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $g->nama }}</div>
        <div style="font-size:0.75rem;color:var(--text-muted);">{{ ucfirst($g->segmen) }} • {{ $g->negara }}</div>
      </div>
    </div>
    @endforeach
  </div>
</div>

@endsection

@push('scripts')
<script>
  const sCtx = document.getElementById('segmenChart').getContext('2d');
  new Chart(sCtx, {
    type: 'doughnut',
    data: {
      labels: {!! json_encode(array_keys($segments->toArray())) !!}.map(s=>s.charAt(0).toUpperCase()+s.slice(1)),
      datasets: [{
        data: {!! json_encode(array_values($segments->toArray())) !!},
        backgroundColor: ['#a855f7', '#3b82f6', '#ec4899', '#f59e0b', '#10b981'],
        borderWidth: 0,
        hoverOffset: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'right', labels: { color: '#a89fc8' } } }
    }
  });

  const nCtx = document.getElementById('negaraChart').getContext('2d');
  new Chart(nCtx, {
    type: 'bar',
    data: {
      labels: {!! json_encode(array_keys($nationalities->toArray())) !!},
      datasets: [{
        label: 'Jumlah Tamu',
        data: {!! json_encode(array_values($nationalities->toArray())) !!},
        backgroundColor: 'rgba(59,130,246,0.6)',
        borderRadius: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { ticks: { color: '#a89fc8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
        x: { ticks: { color: '#a89fc8' }, grid: { display: false } }
      }
    }
  });
</script>
@endpush
