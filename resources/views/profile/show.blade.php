@extends('layouts.dashboard')
@section('title', 'Profil Saya')
@section('page-title', '👤 Profil Saya')

@section('content')

<div style="max-width:800px;">

  {{-- Header Card --}}
  <div class="glass-card" style="padding:2rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:2rem;flex-wrap:wrap;">
    {{-- Avatar Besar --}}
    <div style="position:relative;">
      <div style="width:90px;height:90px;background:linear-gradient(135deg,var(--purple-core),var(--purple-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:2rem;font-weight:800;color:#fff;box-shadow:0 0 30px rgba(124,58,237,0.4);">
        {{ strtoupper(substr($user->name,0,2)) }}
      </div>
      <div style="position:absolute;bottom:2px;right:2px;width:20px;height:20px;background:#4ade80;border-radius:50%;border:2px solid var(--bg-card);"></div>
    </div>

    {{-- Info --}}
    <div style="flex:1;">
      <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:800;color:var(--text-primary);margin-bottom:4px;">
        {{ $user->name }}
      </div>
      <div style="font-size:0.875rem;color:var(--text-muted);margin-bottom:8px;">
        {{ $user->email }}
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <span class="badge badge-purple">
          {{ $user->role === 'admin' ? '🔑 Admin' : '👤 User' }}
        </span>
        <span class="badge badge-success" style="font-size:0.65rem;">● Online</span>
        <span class="badge" style="background:rgba(34,211,238,0.1);color:var(--accent-cyan);border:1px solid rgba(34,211,238,0.2);font-size:0.65rem;">
          Bergabung {{ $user->created_at->format('M Y') }}
        </span>
      </div>
    </div>

    {{-- Actions --}}
    <div style="display:flex;flex-direction:column;gap:0.75rem;">
      <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
        ✏️ Edit Profil
      </a>
      <a href="{{ route('profile.password') }}" class="btn btn-outline btn-sm">
        🔒 Ganti Password
      </a>
    </div>
  </div>

  {{-- Info Detail --}}
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

    <div class="chart-wrap">
      <div class="chart-title" style="margin-bottom:1rem;">📋 Informasi Akun</div>
      @foreach([
        ['Nama Lengkap', $user->name],
        ['Email', $user->email],
        ['Role', ucfirst($user->role)],
        ['Status Email', $user->email_verified_at ? '✅ Terverifikasi' : '⏳ Belum Verifikasi'],
        ['Bergabung', $user->created_at->format('d M Y')],
        ['Terakhir Update', $user->updated_at->format('d M Y H:i')],
      ] as [$label, $val])
      <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border-subtle);">
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $label }}</span>
        <span style="font-size:0.85rem;color:var(--text-primary);font-weight:500;">{{ $val }}</span>
      </div>
      @endforeach
    </div>

    <div class="chart-wrap">
      <div class="chart-title" style="margin-bottom:1rem;">🔐 Keamanan Akun</div>

      {{-- Password Strength Visual --}}
      <div style="margin-bottom:1.5rem;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
          <span style="font-size:0.8rem;color:var(--text-muted);">Kekuatan Password</span>
          <span style="font-size:0.78rem;color:#4ade80;font-weight:600;">Kuat</span>
        </div>
        <div class="progress-bar">
          <div class="progress-bar-fill" style="width:85%;background:linear-gradient(90deg,#4ade80,#22d3ee);"></div>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:0.75rem;">
        @foreach([
          ['Email Terverifikasi', $user->email_verified_at ? true : false],
          ['Password Terkini', true],
          ['Akun Aktif', true],
        ] as [$label, $status])
        <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:rgba({{ $status ? '74,222,128':'248,113,113' }},0.06);border-radius:var(--radius-sm);border:1px solid rgba({{ $status ? '74,222,128':'248,113,113' }},0.15);">
          <span style="font-size:0.82rem;color:var(--text-secondary);">{{ $label }}</span>
          <span style="font-size:1rem;">{{ $status ? '✅' : '❌' }}</span>
        </div>
        @endforeach
      </div>

      <div style="margin-top:1.25rem;">
        <a href="{{ route('profile.password') }}" class="btn btn-outline"
          style="width:100%;justify-content:center;">
          🔒 Perbarui Password
        </a>
      </div>
    </div>

  </div>
</div>

@endsection