<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }
  .header { background: #7c3aed; color: white; padding: 16px 24px; margin-bottom: 20px; }
  .header h1 { font-size: 18px; font-weight: 800; margin-bottom: 4px; }
  .header p  { font-size: 10px; opacity: 0.8; }
  .meta { display: flex; gap: 24px; padding: 0 24px; margin-bottom: 16px; }
  .meta-item { background: #f3f0ff; border-left: 3px solid #7c3aed; padding: 8px 12px; border-radius: 4px; }
  .meta-item .label { font-size: 9px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
  .meta-item .value { font-size: 14px; font-weight: 700; color: #7c3aed; }
  table { width: 100%; border-collapse: collapse; margin: 0 0 16px; }
  th { background: #7c3aed; color: white; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.3px; }
  td { padding: 6px 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
  tr:nth-child(even) td { background: #faf5ff; }
  .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 600; }
  .confirmed { background: #dcfce7; color: #166534; }
  .pending   { background: #fef9c3; color: #854d0e; }
  .cancelled { background: #fee2e2; color: #991b1b; }
  .completed { background: #dbeafe; color: #1e40af; }
  .footer { text-align: center; padding: 12px; color: #9ca3af; font-size: 9px; border-top: 1px solid #e5e7eb; margin-top: 8px; }
  .page-break { page-break-after: always; }
</style>
</head>
<body>

<div class="header">
  <h1>🏨 Laporan Data Booking — {{ $year }}</h1>
  <p>Nusantara Hospitality Group | Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>

<div class="meta">
  <div class="meta-item">
    <div class="label">Total Booking</div>
    <div class="value">{{ $bookings->count() }}</div>
  </div>
  <div class="meta-item">
    <div class="label">Total Revenue</div>
    <div class="value">Rp {{ number_format($bookings->whereIn('status',['confirmed','completed'])->sum('total_bayar')/1000000,1) }}M</div>
  </div>
  <div class="meta-item">
    <div class="label">Avg Rating</div>
    <div class="value">{{ number_format($bookings->whereNotNull('rating')->avg('rating'),1) }} ⭐</div>
  </div>
  <div class="meta-item">
    <div class="label">Periode</div>
    <div class="value">{{ $year }}</div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>Kode</th><th>Tamu</th><th>Hotel</th><th>Kamar</th>
      <th>Check-in</th><th>Checkout</th><th>Malam</th>
      <th>Total</th><th>Channel</th><th>Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($bookings as $b)
    <tr>
      <td><strong>{{ $b->kode_booking }}</strong></td>
      <td>{{ $b->customer->nama ?? '-' }}</td>
      <td>{{ $b->hotel->nama ?? '-' }}</td>
      <td>{{ ucfirst($b->room->tipe ?? '-') }}</td>
      <td>{{ $b->tgl_checkin->format('d/m/Y') }}</td>
      <td>{{ $b->tgl_checkout->format('d/m/Y') }}</td>
      <td style="text-align:center;">{{ $b->jml_malam }}</td>
      <td><strong>Rp {{ number_format($b->total_bayar/1000000,2) }}Jt</strong></td>
      <td>{{ $b->channel->nama ?? '-' }}</td>
      <td><span class="badge {{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="footer">
  © {{ now()->year }} Nusantara Hospitality Group — Dokumen ini digenerate otomatis oleh sistem NHG Analytics
</div>

</body>
</html>