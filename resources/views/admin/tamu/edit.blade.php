@extends('layouts.dashboard')
@section('title', 'Edit Tamu')
@section('page-title', '✏️ Edit Tamu — '.$tamu->nama)

@section('content')

<div style="max-width:700px;">
  <div class="glass-card" style="padding:2rem;">

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.5rem;">❌ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.tamu.update', $tamu->guest_key) }}">
      @csrf @method('PUT')

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

        <div class="form-group" style="grid-column:span 2;">
          <label class="form-label">Nama Lengkap *</label>
          <input type="text" name="guest_name" class="form-input" value="{{ $tamu->guest_name }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-input" value="{{ $tamu->email }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Telepon</label>
          <input type="text" name="telepon" class="form-input" value="{{ $tamu->telepon }}">
        </div>

        <div class="form-group">
          <label class="form-label">Segmen *</label>
          <select name="segment" class="form-input" required>
            @foreach(['vip','corporate','leisure','group'] as $s)
            <option value="{{ $s }}" {{ $tamu->segmen===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tier *</label>
          <select name="tier" class="form-input" required>
            @foreach(['silver','gold','platinum'] as $t)
            <option value="{{ $t }}" {{ $tamu->tier===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Negara *</label>
          <input type="text" name="nationality" class="form-input" value="{{ $tamu->nationality }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Kota Asal</label>
          <input type="text" name="city" class="form-input" value="{{ $tamu->city }}">
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="tgl_lahir" class="form-input"
            value="{{ $tamu->tgl_lahir ? $tamu->tgl_lahir->format('Y-m-d') : '' }}">
        </div>

      </div>

      <div style="display:flex;gap:0.75rem;margin-top:1rem;">
        <button type="submit" class="btn btn-primary">💾 Update Tamu</button>
        <a href="{{ route('admin.tamu.show', $tamu->guest_key) }}" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

@endsection