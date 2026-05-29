@extends('layouts.app')
@section('title', 'Masuk — NHG Analytics')
@section('content')

<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;position:relative;">
  <div class="orb orb-purple" style="width:500px;height:500px;top:-100px;right:-100px;opacity:0.4;"></div>
  <div class="orb orb-pink"   style="width:350px;height:350px;bottom:-50px;left:-80px;opacity:0.3;"></div>
  <div style="position:fixed;inset:0;background-image:linear-gradient(rgba(124,58,237,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,0.03) 1px,transparent 1px);background-size:50px 50px;pointer-events:none;"></div>

  <div style="width:100%;max-width:460px;position:relative;z-index:1;">

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
        <h1 style="font-family:var(--font-display);font-weight:700;font-size:1.6rem;margin-bottom:0.4rem;">Selamat Datang 👋</h1>
        <p style="color:var(--text-muted);font-size:0.875rem;">Masuk Untuk Mengakses Dashboard Analitik NHG</p>
      </div>

      @if($errors->any())
      <div class="alert alert-error">❌ {{ $errors->first() }}</div>
      @endif

      @if(session('status'))
      <div class="alert alert-success">✅ {{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <label class="form-label">Alamat Email</label>
          <input type="email" name="email" class="form-input"
            placeholder="nama@nhg.com"
            value="{{ old('email') }}"
            required autofocus>
        </div>

        <div class="form-group">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
            <label class="form-label" style="margin:0;">Password</label>
          </div>
          <div style="position:relative;">
            <input type="password" name="password" id="pwInput" class="form-input"
              placeholder="••••••••" required style="padding-right:44px;">
            <button type="button" onclick="togglePw()"
              style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1rem;"
              id="pwBtn">👁️</button>
          </div>
        </div>

        <div style="display:flex;align-items:center;gap:8px;margin-bottom:1.5rem;">
          <input type="checkbox" name="remember" id="remember" style="accent-color:var(--purple-core);width:14px;height:14px;">
          <label for="remember" style="font-size:0.82rem;color:var(--text-secondary);cursor:pointer;">Ingat Saya</label>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
          Masuk Ke Dashboard →
        </button>

        <div style="text-align:center;margin-top:1.5rem;font-size:0.85rem;color:var(--text-muted);">
          Belum Punya Akun?
          <a href="{{ route('register') }}" style="color:var(--purple-light);text-decoration:none;font-weight:600;">Daftar Sekarang</a>
        </div>
      </form>
    </div>

    <div style="margin-top:1.5rem;padding:1rem 1.25rem;background:rgba(124,58,237,0.06);border:1px solid var(--border-subtle);border-radius:var(--radius-sm);font-size:0.78rem;color:var(--text-muted);" class="animate-fade-up delay-2">
      <div style="font-weight:600;color:var(--purple-glow);margin-bottom:6px;">🔑 Demo Credentials</div>
      <div>Admin: <span style="color:var(--text-secondary);">admin@nhg.com</span> / <span style="color:var(--text-secondary);">password</span></div>
      <div>User &nbsp;: <span style="color:var(--text-secondary);">user@nhg.com</span> / <span style="color:var(--text-secondary);">password</span></div>
    </div>

    <div style="text-align:center;margin-top:1.5rem;" class="animate-fade-up delay-3">
      <a href="/" style="color:var(--text-muted);text-decoration:none;font-size:0.82rem;">← Kembali Ke Halaman Utama</a>
    </div>
  </div>
</div>

@push('scripts')
<script>
function togglePw() {
  const p = document.getElementById('pwInput');
  const b = document.getElementById('pwBtn');
  p.type = p.type === 'password' ? 'text' : 'password';
  b.textContent = p.type === 'password' ? '👁️' : '🙈';
}
</script>
@endpush

@endsection