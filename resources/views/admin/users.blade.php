@extends('layouts.dashboard')
@section('title', 'Kelola User')
@section('page-title', '👤 Manajemen User')

@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
  @foreach([
    ['Total User','badge-purple','var(--purple-light)',$users->count(),'👥'],
    ['Admin'     ,'badge-warning','#fbbf24',$users->where('role','admin')->count(),'🔑'],
    ['Staff/User','badge-success','#4ade80',$users->where('role','user')->count(),'👤'],
  ] as $s)
  <div class="stat-card animate-fade-up" style="animation-delay:{{ $loop->index * 0.08 }}s">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
      <div class="stat-label">{{ $s[0] }}</div>
      <span style="font-size:1.4rem;">{{ $s[4] }}</span>
    </div>
    <div class="stat-value" style="color:{{ $s[2] }};">{{ $s[3] }}</div>
  </div>
  @endforeach
</div>

{{-- Tambah User Form --}}
<div class="glass-card" style="padding:1.75rem;margin-bottom:1.5rem;" id="formTambah">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <div class="chart-title">➕ Tambah User Baru</div>
    <button onclick="toggleForm()" class="btn btn-outline btn-sm" id="toggleFormBtn">
      Sembunyikan Form
    </button>
  </div>

  <div id="formBody">
    @if($errors->any())
    <div class="alert alert-error">❌ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" class="form-input"
            placeholder="Nama user" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Alamat Email</label>
          <input type="email" name="email" class="form-input"
            placeholder="email@nhg.com" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-input"
            placeholder="Min. 8 karakter" required>
        </div>
        <div class="form-group">
          <label class="form-label">Role</label>
          <select name="role" class="form-input" required>
            <option value="">-- Pilih Role --</option>
            <option value="user"  {{ old('role')==='user'  ? 'selected' : '' }}>Staff / User</option>
            <option value="admin" {{ old('role')==='admin' ? 'selected' : '' }}>Admin</option>
          </select>
        </div>
      </div>
      <div style="display:flex;gap:0.75rem;margin-top:0.5rem;">
        <button type="submit" class="btn btn-primary">✅ Simpan User</button>
        <button type="reset"  class="btn btn-outline">🔄 Reset</button>
      </div>
    </form>
  </div>
</div>

{{-- Tabel User --}}
<div class="chart-wrap animate-fade-up delay-2">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
    <div class="chart-title">Daftar Semua User</div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <input type="text" id="searchInput" onkeyup="filterTable()"
        class="form-input" style="width:220px;padding:8px 12px;"
        placeholder="🔍 Cari nama atau email...">
    </div>
  </div>

  <div style="overflow-x:auto;">
    <table class="data-table" id="userTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Bergabung</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
        <tr>
          <td style="color:var(--text-muted);">{{ $loop->iteration }}</td>

          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="avatar" style="font-size:0.75rem;width:32px;height:32px;">
                {{ strtoupper(substr($u->name,0,2)) }}
              </div>
              <span style="color:var(--text-primary);font-weight:600;">{{ $u->name }}</span>
            </div>
          </td>

          <td style="color:var(--text-secondary);">{{ $u->email }}</td>

          <td>
            @if($u->role === 'admin')
              <span class="badge badge-purple" style="font-size:0.68rem;">🔑 Admin</span>
            @else
              <span class="badge badge-success" style="font-size:0.68rem;">👤 Staff</span>
            @endif
          </td>

          <td style="color:var(--text-muted);">
            {{ $u->created_at->format('d M Y') }}
          </td>

          <td>
            @if($u->email_verified_at)
              <span class="badge badge-success" style="font-size:0.65rem;">✓ Verified</span>
            @else
              <span class="badge badge-warning" style="font-size:0.65rem;">⏳ Unverified</span>
            @endif
          </td>

          <td>
            @if($u->id !== Auth::id())
            <form method="POST"
              action="{{ route('admin.users.destroy', $u->id) }}"
              onsubmit="return confirm('Hapus user {{ $u->name }}?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-outline btn-sm"
                style="color:#f87171;border-color:rgba(248,113,113,0.3);padding:5px 12px;">
                🗑️ Hapus
              </button>
            </form>
            @else
            <span style="font-size:0.75rem;color:var(--text-muted);">— Akun Anda</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">
            Belum ada user terdaftar.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top:1rem;font-size:0.78rem;color:var(--text-muted);">
    Total: {{ $users->count() }} user terdaftar
  </div>
</div>

@push('scripts')
<script>
function toggleForm() {
  const body = document.getElementById('formBody');
  const btn  = document.getElementById('toggleFormBtn');
  const show = body.style.display === 'none';
  body.style.display = show ? 'block' : 'none';
  btn.textContent    = show ? 'Sembunyikan Form' : 'Tampilkan Form';
}

function filterTable() {
  const q    = document.getElementById('searchInput').value.toLowerCase();
  const rows = document.querySelectorAll('#userTable tbody tr');
  rows.forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}
</script>
@endpush

@endsection