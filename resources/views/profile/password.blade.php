@extends('layouts.dashboard')
@section('title', 'Ganti Password')
@section('page-title', '🔒 Ganti Password')

@section('content')

<div style="max-width:500px;">

  <div class="glass-card" style="padding:2rem;">

    <div style="margin-bottom:1.75rem;">
      <div style="width:52px;height:52px;background:linear-gradient(135deg,rgba(124,58,237,0.3),rgba(168,85,247,0.1));border:1px solid var(--border-glow);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:1rem;">
        🔒
      </div>
      <div style="font-family:var(--font-display);font-weight:700;font-size:1.1rem;margin-bottom:4px;">
        Perbarui Password
      </div>
      <div style="font-size:0.82rem;color:var(--text-muted);">
        Gunakan password yang kuat dan unik untuk keamanan akun Anda.
      </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.5rem;">
      ❌ {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('profile.password.update') }}">
      @csrf @method('PUT')

      <div class="form-group">
        <label class="form-label">Password Saat Ini *</label>
        <div style="position:relative;">
          <input type="password" name="current_password" id="pw0"
            class="form-input" placeholder="••••••••"
            style="padding-right:44px;" required>
          <button type="button" onclick="togglePw('pw0','btn0')"
            id="btn0"
            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1rem;">
            👁️
          </button>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Password Baru *</label>
        <div style="position:relative;">
          <input type="password" name="password" id="pw1"
            class="form-input" placeholder="Min. 8 karakter"
            style="padding-right:44px;" required
            oninput="checkStrength(this.value)">
          <button type="button" onclick="togglePw('pw1','btn1')"
            id="btn1"
            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1rem;">
            👁️
          </button>
        </div>

        {{-- Password Strength Indicator --}}
        <div style="margin-top:8px;">
          <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
            <span style="font-size:0.72rem;color:var(--text-muted);">Kekuatan password</span>
            <span id="strengthLabel" style="font-size:0.72rem;font-weight:600;color:var(--text-muted);">—</span>
          </div>
          <div class="progress-bar" style="height:5px;">
            <div id="strengthBar" class="progress-bar-fill" style="width:0%;transition:width 0.3s,background 0.3s;"></div>
          </div>
        </div>

        <div style="margin-top:8px;display:flex;flex-direction:column;gap:3px;" id="pwChecks">
          @foreach([
            ['len','Minimal 8 karakter'],
            ['upper','Huruf kapital (A-Z)'],
            ['lower','Huruf kecil (a-z)'],
            ['num','Angka (0-9)'],
            ['sym','Karakter khusus (!@#$...)'],
          ] as [$id,$label])
          <div id="check_{{ $id }}" style="display:flex;align-items:center;gap:6px;font-size:0.72rem;color:var(--text-muted);">
            <span id="icon_{{ $id }}">○</span> {{ $label }}
          </div>
          @endforeach
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Konfirmasi Password Baru *</label>
        <div style="position:relative;">
          <input type="password" name="password_confirmation" id="pw2"
            class="form-input" placeholder="Ulangi password baru"
            style="padding-right:44px;" required
            oninput="checkMatch()">
          <button type="button" onclick="togglePw('pw2','btn2')"
            id="btn2"
            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1rem;">
            👁️
          </button>
        </div>
        <div id="matchMsg" style="font-size:0.72rem;margin-top:4px;display:none;"></div>
      </div>

      <div style="display:flex;gap:0.75rem;margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary" id="submitBtn">
          🔒 Perbarui Password
        </button>
        <a href="{{ route('profile.show') }}" class="btn btn-outline">
          Batal
        </a>
      </div>
    </form>
  </div>

  <div style="margin-top:1rem;padding:1rem 1.25rem;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.2);border-radius:var(--radius-sm);">
    <div style="font-size:0.78rem;color:#fbbf24;font-weight:600;margin-bottom:4px;">
      💡 Tips Keamanan
    </div>
    <div style="font-size:0.75rem;color:var(--text-muted);line-height:1.6;">
      Jangan gunakan password yang sama di platform lain.
      Kombinasikan huruf, angka, dan simbol untuk keamanan maksimal.
    </div>
  </div>

</div>

@push('scripts')
<script>
function togglePw(inputId, btnId) {
  const input = document.getElementById(inputId);
  const btn   = document.getElementById(btnId);
  input.type  = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁️' : '🙈';
}

function checkStrength(val) {
  const checks = {
    len:   val.length >= 8,
    upper: /[A-Z]/.test(val),
    lower: /[a-z]/.test(val),
    num:   /[0-9]/.test(val),
    sym:   /[^A-Za-z0-9]/.test(val),
  };

  let score = Object.values(checks).filter(Boolean).length;

  Object.entries(checks).forEach(([key, passed]) => {
    const icon = document.getElementById('icon_' + key);
    const row  = document.getElementById('check_' + key);
    icon.textContent = passed ? '●' : '○';
    row.style.color  = passed ? '#4ade80' : 'var(--text-muted)';
  });

  const bar   = document.getElementById('strengthBar');
  const label = document.getElementById('strengthLabel');
  const configs = [
    [0,  '0%',   '#f87171', '—'],
    [1,  '20%',  '#f87171', 'Sangat Lemah'],
    [2,  '40%',  '#fbbf24', 'Lemah'],
    [3,  '60%',  '#fbbf24', 'Sedang'],
    [4,  '80%',  '#4ade80', 'Kuat'],
    [5, '100%',  '#22d3ee', 'Sangat Kuat'],
  ];
  const [,width, color, text] = configs[score];
  bar.style.width      = width;
  bar.style.background = color;
  label.textContent    = text;
  label.style.color    = color;
}

function checkMatch() {
  const pw1 = document.getElementById('pw1').value;
  const pw2 = document.getElementById('pw2').value;
  const msg = document.getElementById('matchMsg');

  if (!pw2) { msg.style.display = 'none'; return; }

  msg.style.display = 'block';
  if (pw1 === pw2) {
    msg.textContent   = '✅ Password cocok';
    msg.style.color   = '#4ade80';
  } else {
    msg.textContent   = '❌ Password tidak cocok';
    msg.style.color   = '#f87171';
  }
}
</script>
@endpush

@endsection