@extends('layouts.dashboard')
@section('title', 'Edit Hotel')
@section('page-title', '✏️ Edit — '.$hotel->nama)

@section('content')

<div style="max-width:700px;">
  <div class="glass-card" style="padding:2rem;">

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.5rem;">❌ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.hotels.update', $hotel->hotel_key) }}">
      @csrf @method('PUT')

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

        <div class="form-group" style="grid-column:span 2;">
          <label class="form-label">Nama Properti *</label>
          <input type="text" name="hotel_name" class="form-input" value="{{ $hotel->hotel_name }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Tipe *</label>
          <select name="hotel_type" class="form-input" required>
            @foreach(['hotel','resort','restoran'] as $t)
            <option value="{{ $t }}" {{ $hotel->hotel_type === $t ? 'selected':'' }}>{{ ucfirst($t) }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Bintang *</label>
          <select name="star_rating" class="form-input" required>
            @foreach([1,2,3,4,5] as $b)
            <option value="{{ $b }}" {{ $hotel->star_rating == $b ? 'selected':'' }}>
              {{ str_repeat('★',$b) }} ({{ $b }} bintang)
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Kota *</label>
          <input type="text" name="city" class="form-input" value="{{ $hotel->city }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Provinsi *</label>
          <input type="text" name="provinsi" class="form-input" value="{{ $hotel->provinsi }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Kapasitas Total *</label>
          <input type="number" name="kapasitas_total" class="form-input"
            value="{{ $hotel->kapasitas_total }}" min="1" required>
        </div>

        <div class="form-group">
          <label class="form-label">Telepon</label>
          <input type="text" name="telepon" class="form-input" value="{{ $hotel->telepon }}">
        </div>

        <div class="form-group" style="grid-column:span 2;">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-input" value="{{ $hotel->email }}">
        </div>

        <div class="form-group" style="grid-column:span 2;">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-input" rows="2">{{ $hotel->alamat }}</textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="is_active" class="form-input">
            <option value="1" {{ $hotel->is_active ? 'selected':'' }}>✅ Aktif</option>
            <option value="0" {{ !$hotel->is_active ? 'selected':'' }}>❌ Nonaktif</option>
          </select>
        </div>

      </div>

      <div style="display:flex;gap:0.75rem;margin-top:1rem;">
        <button type="submit" class="btn btn-primary">💾 Update Properti</button>
        <a href="{{ route('admin.hotels.show', $hotel->hotel_key) }}" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

@endsection