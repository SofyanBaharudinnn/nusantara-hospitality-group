@extends('layouts.dashboard')
@section('title', 'Tambah Booking')
@section('page-title', '➕ Tambah Booking Baru')

@section('content')

<div style="max-width:800px;">
  <div class="glass-card" style="padding:2rem;">

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.5rem;">
      ❌ {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.bookings.store') }}">
      @csrf

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

        <div class="form-group">
          <label class="form-label">Properti / Hotel *</label>
          <select name="hotel_id" id="hotelSelect" class="form-input" required
            onchange="loadRooms(this.value)">
            <option value="">-- Pilih Hotel --</option>
            @foreach($hotels as $h)
            <option value="{{ $h->id }}" {{ old('hotel_id')==$h->id ? 'selected' : '' }}>
              {{ $h->nama }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Kamar *</label>
          <select name="room_id" id="roomSelect" class="form-input" required>
            <option value="">-- Pilih Hotel dulu --</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tamu *</label>
          <select name="customer_id" class="form-input" required>
            <option value="">-- Pilih Tamu --</option>
            @foreach($customers as $c)
            <option value="{{ $c->id }}" {{ old('customer_id')==$c->id ? 'selected' : '' }}>
              {{ $c->nama }} — {{ $c->email }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Channel Booking *</label>
          <select name="channel_id" class="form-input" required>
            <option value="">-- Pilih Channel --</option>
            @foreach($channels as $c)
            <option value="{{ $c->id }}" {{ old('channel_id')==$c->id ? 'selected' : '' }}>
              {{ $c->nama }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Check-in *</label>
          <input type="date" name="tgl_checkin" class="form-input"
            value="{{ old('tgl_checkin', date('Y-m-d')) }}" required
            onchange="hitungMalam()">
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Check-out *</label>
          <input type="date" name="tgl_checkout" class="form-input"
            value="{{ old('tgl_checkout', date('Y-m-d', strtotime('+1 day'))) }}" required
            onchange="hitungMalam()">
        </div>

        <div class="form-group">
          <label class="form-label">Jumlah Tamu *</label>
          <input type="number" name="jml_tamu" class="form-input"
            value="{{ old('jml_tamu', 1) }}" min="1" max="10" required>
        </div>

        <div class="form-group">
          <label class="form-label">Status *</label>
          <select name="status" class="form-input" required>
            @foreach(['confirmed','pending','cancelled','completed'] as $s)
            <option value="{{ $s }}" {{ old('status','confirmed')===$s ? 'selected' : '' }}>
              {{ ucfirst($s) }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Diskon (Rp)</label>
          <input type="number" name="diskon" class="form-input"
            value="{{ old('diskon', 0) }}" min="0">
        </div>

        <div class="form-group">
          <label class="form-label">Estimasi Total</label>
          <div id="estimasiTotal" style="padding:13px 16px;background:rgba(124,58,237,0.08);border:1px solid var(--border-glow);border-radius:var(--radius-sm);font-weight:700;color:var(--purple-light);">
            Pilih hotel, kamar & tanggal
          </div>
        </div>

      </div>

      <div class="form-group">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" class="form-input" rows="3"
          placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
      </div>

      <div style="display:flex;gap:0.75rem;margin-top:1rem;">
        <button type="submit" class="btn btn-primary">✅ Simpan Booking</button>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
const roomsByHotel = {};

async function loadRooms(hotelId) {
  const select = document.getElementById('roomSelect');
  select.innerHTML = '<option value="">Memuat...</option>';

  if (!hotelId) {
    select.innerHTML = '<option value="">-- Pilih Hotel dulu --</option>';
    return;
  }

  const res  = await fetch(`/admin/hotels/${hotelId}/rooms`);
  const data = await res.json();

  select.innerHTML = '<option value="">-- Pilih Kamar --</option>';
  data.forEach(r => {
    select.innerHTML += `<option value="${r.id}" data-harga="${r.harga_dasar}">
      ${r.nomor_kamar} — ${r.tipe} (Rp ${Number(r.harga_dasar).toLocaleString('id-ID')}/malam)
    </option>`;
  });

  select.addEventListener('change', hitungMalam);
  hitungMalam();
}

function hitungMalam() {
  const checkin  = document.querySelector('[name=tgl_checkin]').value;
  const checkout = document.querySelector('[name=tgl_checkout]').value;
  const roomOpt  = document.getElementById('roomSelect').selectedOptions[0];
  const diskon   = parseFloat(document.querySelector('[name=diskon]').value) || 0;
  const el       = document.getElementById('estimasiTotal');

  if (!checkin || !checkout || !roomOpt?.dataset?.harga) {
    el.textContent = 'Pilih hotel, kamar & tanggal';
    return;
  }

  const nights = Math.ceil((new Date(checkout) - new Date(checkin)) / 86400000);
  if (nights <= 0) { el.textContent = 'Tanggal tidak valid'; return; }

  const harga = parseFloat(roomOpt.dataset.harga);
  const total = (harga * nights) - diskon;
  el.textContent = `${nights} malam × Rp ${harga.toLocaleString('id-ID')} − diskon = Rp ${total.toLocaleString('id-ID')}`;
}

document.querySelector('[name=diskon]').addEventListener('input', hitungMalam);
</script>
@endpush

@endsection