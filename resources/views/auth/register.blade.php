@extends('layouts.app')
@section('title', 'Daftar — NHG Analytics')
@section('content')

<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;position:relative;">
  <div class="orb orb-purple" style="width:500px;height:500px;top:-100px;left:-100px;opacity:0.4;"></div>
  <div class="orb orb-cyan"   style="width:350px;height:350px;bottom:-50px;right:-80px;opacity:0.3;"></div>
  <div style="position:fixed;inset:0;background-image:linear-gradient(rgba(124,58,237,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,0.03) 1px,transparent 1px);background-size:50px 50px;pointer-events:none;"></div>

  <div style="width:100%;max-width:480px;position:relative;z-index:1;">

    <div style="text-align:center;margin-bottom:2.5rem;" class="animate-fade-up">
      <a href="/" style="text-decoration:none;display:inline-flex;flex-direction:column;align-items:center;gap:10px;">
        <div style="width:56px;height:56px;background:linear-gradient(135deg,var(--purple-core),var(--purple-light));border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;box-shadow:0 0 30px rgba(124,58,237,0.4);">🏨</div>
        <div>
          <div style="font-family:var(--font-display);font-weight:700;font-size:1.4rem;color:var(--text-primary);">NHG Analytics</div>
          <div style="font-size:0.75rem;color:var(--text-muted);letter-spacing:0.06em;text-transform:uppercase;">Nusantara Hospitality Group</div>
        </div>
      </a>
    </div>

    <div class="glass-card animate-fade-up delay-1" style="padding:2.5rem;">
      <div style="margin-bottom:2rem;">
        <h1 style="font-family:var(--font-display);font-weight:700;font-size:1.6rem;margin-bottom:0.4rem;">Buat Akun ✨</h1>
        <p style="color:var(--text-muted);font-size:0.875rem;">Daftarkan Diri Untuk Mengakses Platform Nusantara Hospitality Group</p>
      </div>

      @if($errors->any())
      <div class="alert alert-error">❌ {{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" class="form-input"
            placeholder="Nama Anda"
            value="{{ old('name') }}"
            required autofocus>
        </div>

        <div class="form-group">
          <label class="form-label">Alamat Email</label>
          <input type="email" name="email" class="form-input"
            placeholder="nama@nhg.com"
            value="{{ old('email') }}"
            required>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-input"
            placeholder="Minimal 8 karakter"
            required>
        </div>

        <div class="form-group">
          <label class="form-label">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" class="form-input"
            placeholder="Ulangi password"
            required>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:0.5rem;">
          Buat Akun →
        </button>

        <div style="text-align:center;margin-top:1.5rem;font-size:0.85rem;color:var(--text-muted);">
          Sudah Punya Akun?
          <a href="{{ route('login') }}" style="color:var(--purple-light);text-decoration:none;font-weight:600;">Masuk Di Sini</a>
        </div>
      </form>
    </div>

    <div style="text-align:center;margin-top:1.5rem;" class="animate-fade-up delay-2">
      <a href="/" style="color:var(--text-muted);text-decoration:none;font-size:0.82rem;">← Kembali Ke Halaman Utama</a>
    </div>
  </div>
</div>

@endsection