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

    <form method="POST" action="{{ route('admin.bookings.update', $booking->reservation_key) }}">
      @csrf @method('PUT')

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

        <div class="form-group">
          <label class="form-label">Properti / Hotel *</label>
          <select name="hotel_key" class="form-input" disabled>
            @foreach($hotels as $h)
            <option value="{{ $h->hotel_key }}" {{ $booking->hotel_key==$h->hotel_key ? 'selected' : '' }}>
              {{ $h->nama }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Kamar *</label>
          <select name="room_key" class="form-input" disabled>
            @foreach($rooms as $r)
            <option value="{{ $r->room_key }}" {{ $booking->room_key==$r->room_key ? 'selected' : '' }}>
              {{ $r->nomor_kamar }} — {{ ucfirst($r->tipe) }}
              (Rp {{ number_format($r->harga_dasar,0,',','.') }}/malam)
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tamu *</label>
          <select name="guest_key" class="form-input" disabled>
            @foreach($customers as $c)
            <option value="{{ $c->guest_key }}" {{ $booking->guest_key==$c->guest_key ? 'selected' : '' }}>
              {{ $c->nama }} — {{ $c->email }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Channel *</label>
          <select name="channel_key" class="form-input" disabled>
            @foreach($channels as $c)
            <option value="{{ $c->channel_key }}" {{ $booking->channel_key==$c->channel_key ? 'selected' : '' }}>
              {{ $c->nama }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Check-in *</label>
          <input type="date" class="form-input" disabled
            value="{{ $booking->tgl_checkin ? $booking->tgl_checkin->format('Y-m-d') : '' }}">
        </div>

        <div class="form-group">
          <label class="form-label">Jumlah Malam *</label>
          <input type="number" name="nights" class="form-input"
            value="{{ $booking->nights }}" min="1" required>
        </div>

        <div class="form-group">
          <label class="form-label">Jumlah Kamar Dipesan *</label>
          <input type="number" name="rooms_booked" class="form-input"
            value="{{ $booking->rooms_booked }}" min="1" required>
        </div>

        <div class="form-group">
          <label class="form-label">Status *</label>
          <select name="is_cancelled" class="form-input" required>
            <option value="No" {{ $booking->is_cancelled==='No' ? 'selected' : '' }}>Confirmed</option>
            <option value="Yes" {{ $booking->is_cancelled==='Yes' ? 'selected' : '' }}>Cancelled</option>
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
        <a href="{{ route('admin.bookings.show', $booking->reservation_key) }}" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

@endsection