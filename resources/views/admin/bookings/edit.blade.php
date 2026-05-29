@extends('layouts.dashboard')
@section('title', 'Edit Booking')
@section('page-title', '✏️ Edit Booking — '.$booking->kode_booking)

@section('content')

<div style="max-width:800px;">
  <div class="glass-card" style="padding:2rem;">

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.5rem;">
      ❌ {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.bookings.update', $booking->id) }}">
      @csrf @method('PUT')

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

        <div class="form-group">
          <label class="form-label">Properti / Hotel *</label>
          <select name="hotel_id" class="form-input" required>
            @foreach($hotels as $h)
            <option value="{{ $h->id }}" {{ $booking->hotel_id==$h->id ? 'selected' : '' }}>
              {{ $h->nama }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Kamar *</label>
          <select name="room_id" class="form-input" required>
            @foreach($rooms as $r)
            <option value="{{ $r->id }}" {{ $booking->room_id==$r->id ? 'selected' : '' }}>
              {{ $r->nomor_kamar }} — {{ ucfirst($r->tipe) }}
              (Rp {{ number_format($r->harga_dasar,0,',','.') }}/malam)
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tamu *</label>
          <select name="customer_id" class="form-input" required>
            @foreach($customers as $c)
            <option value="{{ $c->id }}" {{ $booking->customer_id==$c->id ? 'selected' : '' }}>
              {{ $c->nama }} — {{ $c->email }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Channel *</label>
          <select name="channel_id" class="form-input" required>
            @foreach($channels as $c)
            <option value="{{ $c->id }}" {{ $booking->channel_id==$c->id ? 'selected' : '' }}>
              {{ $c->nama }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Check-in *</label>
          <input type="date" name="tgl_checkin" class="form-input"
            value="{{ $booking->tgl_checkin->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Check-out *</label>
          <input type="date" name="tgl_checkout" class="form-input"
            value="{{ $booking->tgl_checkout->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Jumlah Tamu *</label>
          <input type="number" name="jml_tamu" class="form-input"
            value="{{ $booking->jml_tamu }}" min="1" required>
        </div>

        <div class="form-group">
          <label class="form-label">Status *</label>
          <select name="status" class="form-input" required>
            @foreach(['confirmed','pending','cancelled','completed'] as $s)
            <option value="{{ $s }}" {{ $booking->status===$s ? 'selected' : '' }}>
              {{ ucfirst($s) }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Rating (1–5)</label>
          <select name="rating" class="form-input">
            <option value="">-- Belum ada --</option>
            @foreach([1,2,3,4,5] as $r)
            <option value="{{ $r }}" {{ $booking->rating==$r ? 'selected' : '' }}>
              {{ str_repeat('⭐',$r) }} ({{ $r }})
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Diskon (Rp)</label>
          <input type="number" name="diskon" class="form-input"
            value="{{ $booking->diskon }}" min="0">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" class="form-input" rows="3">{{ $booking->catatan }}</textarea>
      </div>

      <div style="display:flex;gap:0.75rem;margin-top:1rem;">
        <button type="submit" class="btn btn-primary">💾 Update Booking</button>
        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

@endsection