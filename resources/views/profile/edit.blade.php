@extends('layouts.dashboard')
@section('title', 'Edit Profil')
@section('page-title', '✏️ Edit Profil')

@section('content')

<div style="max-width:600px;">

  <div class="glass-card" style="padding:2rem;">

    {{-- Avatar --}}
    <div style="text-align:center;margin-bottom:2rem;">
      <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--purple-core),var(--purple-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.8rem;font-weight:800;color:#fff;margin:0 auto 1rem;box-shadow:0 0 30px rgba(124,58,237,0.4);">
        {{ strtoupper(substr($user->name,0,2)) }}
      </div>
      <div style="font-size:0.78rem;color:var(--text-muted);">
        Inisial avatar dibuat otomatis dari nama
      </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.5rem;">
      ❌ {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
      @csrf @method('PUT')

      <div class="form-group">
        <label class="form-label">Nama Lengkap *</label>
        <input type="text" name="name" class="form-input"
          value="{{ old('name', $user->name) }}"
          placeholder="Nama lengkap Anda"
          required autofocus>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Email *</label>
        <input type="email" name="email" class="form-input"
          value="{{ old('email', $user->email) }}"
          placeholder="email@nhg.com"
          required>
      </div>

      <div class="form-group">
        <label class="form-label">Role</label>
        <input type="text" class="form-input"
          value="{{ ucfirst($user->role) }}"
          disabled
          style="opacity:0.5;cursor:not-allowed;">
        <div style="font-size:0.72rem;color:var(--text-muted);margin-top:4px;">
          Role tidak dapat diubah sendiri. Hubungi Admin.
        </div>
      </div>

      <div style="display:flex;gap:0.75rem;margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary">
          💾 Simpan Perubahan
        </button>
        <a href="{{ route('profile.show') }}" class="btn btn-outline">
          Batal
        </a>
      </div>
    </form>
  </div>

  {{-- Quick Link --}}
  <div style="margin-top:1rem;text-align:center;">
    <a href="{{ route('profile.password') }}"
      style="font-size:0.82rem;color:var(--purple-light);text-decoration:none;">
      🔒 Ingin ganti password? Klik di sini
    </a>
  </div>

</div>

@endsection