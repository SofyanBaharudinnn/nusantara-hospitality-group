@extends('layouts.dashboard')
@section('title', 'Tambah Tamu')
@section('page-title', '➕ Tambah Tamu Baru')

@section('content')

<div style="max-width:700px;">
  <div class="glass-card" style="padding:2rem;">

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.5rem;">❌ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.tamu.store') }}">
      @csrf

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

        <div class="form-group" style="grid-column:span 2;">
          <label class="form-label">Nama Lengkap *</label>
          <input type="text" name="guest_name" class="form-input"
            placeholder="Budi Santoso" value="{{ old('guest_name') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-input"
            placeholder="budi@email.com" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Telepon</label>
          <input type="text" name="telepon" class="form-input"
            placeholder="08123456789" value="{{ old('telepon') }}">
        </div>

        <div class="form-group">
          <label class="form-label">Segmen *</label>
          <select name="segment" class="form-input" required>
            <option value="">-- Pilih Segmen --</option>
            @foreach(['vip','corporate','leisure','group'] as $s)
            <option value="{{ $s }}" {{ old('segment')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tier *</label>
          <select name="tier" class="form-input" required>
            <option value="">-- Pilih Tier --</option>
            @foreach(['silver','gold','platinum'] as $t)
            <option value="{{ $t }}" {{ old('tier')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Negara *</label>
          <input type="text" name="nationality" class="form-input"
            placeholder="Indonesia" value="{{ old('nationality','Indonesia') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Kota Asal</label>
          <input type="text" name="city" class="form-input"
            placeholder="Jakarta" value="{{ old('city') }}">
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="tgl_lahir" class="form-input"
            value="{{ old('tgl_lahir') }}">
        </div>

      </div>

      <div style="display:flex;gap:0.75rem;margin-top:1rem;">
        <button type="submit" class="btn btn-primary">✅ Simpan Tamu</button>
        <a href="{{ route('admin.tamu.index') }}" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

@endsection