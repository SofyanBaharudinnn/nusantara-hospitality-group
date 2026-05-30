@extends('layouts.dashboard')
@section('title', 'Tambah Properti')
@section('page-title', '➕ Tambah Properti Baru')

@section('content')

<div style="max-width:700px;">
  <div class="glass-card" style="padding:2rem;">

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.5rem;">❌ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.hotels.store') }}">
      @csrf

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

        <div class="form-group" style="grid-column:span 2;">
          <label class="form-label">Nama Properti *</label>
          <input type="text" name="hotel_name" class="form-input"
            placeholder="NHG Jakarta" value="{{ old('hotel_name') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Tipe *</label>
          <select name="hotel_type" class="form-input" required>
            <option value="">-- Pilih Tipe --</option>
            @foreach(['hotel','resort','restoran'] as $t)
            <option value="{{ $t }}" {{ old('hotel_type') === $t ? 'selected':'' }}>{{ ucfirst($t) }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Bintang *</label>
          <select name="star_rating" class="form-input" required>
            @foreach([1,2,3,4,5] as $b)
            <option value="{{ $b }}" {{ old('star_rating', 4) == $b ? 'selected':'' }}>
              {{ str_repeat('★',$b) }} ({{ $b }} bintang)
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Kota *</label>
          <input type="text" name="city" class="form-input"
            placeholder="Jakarta" value="{{ old('city') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Provinsi *</label>
          <input type="text" name="provinsi" class="form-input"
            placeholder="DKI Jakarta" value="{{ old('provinsi') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Kapasitas Total Kamar *</label>
          <input type="number" name="kapasitas_total" class="form-input"
            placeholder="100" value="{{ old('kapasitas_total') }}" min="1" required>
        </div>

        <div class="form-group">
          <label class="form-label">Telepon</label>
          <input type="text" name="telepon" class="form-input"
            placeholder="021-5551234" value="{{ old('telepon') }}">
        </div>

        <div class="form-group" style="grid-column:span 2;">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-input"
            placeholder="hotel@nhg.com" value="{{ old('email') }}">
        </div>

        <div class="form-group" style="grid-column:span 2;">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-input" rows="2"
            placeholder="Jl. Sudirman No. 1...">{{ old('alamat') }}</textarea>
        </div>

      </div>

      <div style="display:flex;gap:0.75rem;margin-top:1rem;">
        <button type="submit" class="btn btn-primary">✅ Simpan Properti</button>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

@endsection